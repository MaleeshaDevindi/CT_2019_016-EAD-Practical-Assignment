<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

require "../config.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    // Validate required fields
    $required_fields = ['course_code', 'course_title', 'credits', 'category', 'level', 'semester', 'description'];
    foreach ($required_fields as $field) {
        if (empty($data[$field])) {
            echo json_encode(["status" => "error", "message" => "$field is required"]);
            exit();
        }
    }

    // Extract data
    $code = $data['course_code'];
    $title = $data['course_title'];
    $description = $data['description'];
    $credits = $data['credits'];
    $category = $data['category'];
    $level = $data['level'];
    $semester = $data['semester'];
    $prerequisites = $data['prerequisites'] ?? '';

    // Check if course code already exists
    $check_stmt = $conn->prepare("SELECT id FROM courses WHERE code = ?");
    $check_stmt->bind_param("s", $code);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "Course code already exists"]);
        exit();
    }

    // Insert new course
    $insert_stmt = $conn->prepare("INSERT INTO courses (code, title, description, credits, category, level, semester, prerequisites) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $insert_stmt->bind_param("sssissss", $code, $title, $description, $credits, $category, $level, $semester, $prerequisites);

    if ($insert_stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Course added successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to add course: " . $conn->error]);
    }

    $insert_stmt->close();
    $check_stmt->close();
    $conn->close();

} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}
?>