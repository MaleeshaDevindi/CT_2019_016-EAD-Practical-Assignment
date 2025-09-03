<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");

require "../config.php";

$sql = "SELECT id, instructor_no, first_name, last_name, email, phone, 
               department, designation, qualification, experience, status, created_at 
        FROM staff 
        ORDER BY created_at DESC";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $instructors = [];
    while ($row = $result->fetch_assoc()) {
        $instructors[] = $row;
    }
    echo json_encode(["status" => "success", "data" => $instructors]);
} else {
    echo json_encode(["status" => "success", "data" => []]);
}

$conn->close();
?>