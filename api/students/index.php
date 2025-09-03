<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");

require "../config.php";

$sql = "SELECT id, student_no, first_name, last_name, gender,  email, phone, `status`, created_at FROM students ORDER BY created_at DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $students = [];
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
    echo json_encode(["status" => "success", "data" => $students]);
} else {
    echo json_encode(["status" => "success", "data" => []]);
}

$conn->close();
?>