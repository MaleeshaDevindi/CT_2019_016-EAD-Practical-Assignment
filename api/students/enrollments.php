<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");

require "../config.php";

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $course_id = $_GET['course_id'] ?? null;
    
    if (!$course_id) {
        echo json_encode(["status" => "error", "message" => "Course ID is required"]);
        exit();
    }

    $sql = "SELECT s.student_no, s.first_name, s.last_name, s.email, s.phone, e.enrolled_at, e.status 
            FROM students s 
            JOIN enrollments e ON s.id = e.student_id 
            WHERE e.course_id = ? AND e.status = 'enrolled'
            ORDER BY e.enrolled_at DESC 
            LIMIT 10";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $course_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $students = [];
        while ($row = $result->fetch_assoc()) {
            $students[] = $row;
        }
        echo json_encode(["status" => "success", "data" => $students]);
    } else {
        echo json_encode(["status" => "success", "data" => []]);
    }

    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}

$conn->close();
?>