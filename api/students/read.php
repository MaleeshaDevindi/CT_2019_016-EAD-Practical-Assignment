<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");

require "../config.php";

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $id = $_GET['id'] ?? null;
    
    if (!$id) {
        echo json_encode(["status" => "error", "message" => "Student ID is required"]);
        exit();
    }

    $stmt = $conn->prepare("SELECT id, student_no, first_name, last_name, email, phone, nic, date_of_birth, gender, `address`, `status`, emergency_contact_name, emergency_contact_phone, created_at FROM students WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();
        echo json_encode(["status" => "success", "data" => $student]);
    } else {
        echo json_encode(["status" => "error", "message" => "Student not found"]);
    }

    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}

$conn->close();
?>