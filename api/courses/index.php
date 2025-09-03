<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");

require "../config.php";

$sql = "SELECT id, code, title, description, credits, category, level, semester, prerequisites, created_at FROM courses";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $courses = [];
    while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }
    echo json_encode(["status" => "success", "data" => $courses]);
} else {
    echo json_encode(["status" => "success", "data" => []]);
}

$conn->close();
?>