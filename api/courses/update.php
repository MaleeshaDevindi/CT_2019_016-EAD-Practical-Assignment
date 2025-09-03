<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Allow-Headers: Content-Type");

require "../config.php";

if ($_SERVER["REQUEST_METHOD"] === "PUT") {
    $data = json_decode(file_get_contents("php://input"), true);

    // Validate required fields
    $required_fields = ['course_id', 'course_code', 'course_title', 'credits', 'category', 'level', 'semester', 'description'];
    foreach ($required_fields as $field) {
        if (empty($data[$field])) {
            echo json_encode(["status" => "error", "message" => "$field is required"]);
            exit();
        }
    }

    // Extract data
    $id = $data['course_id'];
    $code = $data['course_code'];
    $title = $data['course_title'];
    $credits = $data['credits'];
    $category = $data['category'];
    $level = $data['level'];
    $semester = $data['semester'];
    $prerequisites = $data['prerequisites'] ?? '';
    $description = $data['description'];

    try {
        // Check if course code already exists (excluding current course)
        $check_stmt = $conn->prepare("SELECT id FROM courses WHERE code = ? AND id != ?");
        $check_stmt->bind_param("si", $code, $id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            echo json_encode(["status" => "error", "message" => "Course code already exists"]);
            exit();
        }

        // Update course
        $update_stmt = $conn->prepare("UPDATE courses SET code = ?, title = ?, description = ?, credits = ?, category = ?, level = ?, semester = ?, prerequisites = ? WHERE id = ?");
        $update_stmt->bind_param("sssissssi", $code, $title, $description, $credits, $category, $level, $semester, $prerequisites, $id);

        if ($update_stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Course updated successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to update course"]);
        }

        $update_stmt->close();
        $check_stmt->close();

    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}

$conn->close();
?>