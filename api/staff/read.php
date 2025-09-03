<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");

require "../config.php";

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $id = $_GET['id'] ?? null;
    
    if (!$id) {
        echo json_encode(["status" => "error", "message" => "Instructor ID is required"]);
        exit();
    }

    $sql = "SELECT id, instructor_no, nic, first_name, last_name, email, phone, 
                   department, designation, qualification, experience, specialization,
                   date_of_birth, gender, join_date, `address`,`status`, salary, created_at 
            FROM staff 
            WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $instructor = $result->fetch_assoc();
        echo json_encode(["status" => "success", "data" => $instructor]);
    } else {
        echo json_encode(["status" => "error", "message" => "Instructor not found"]);
    }

    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}

$conn->close();
?>