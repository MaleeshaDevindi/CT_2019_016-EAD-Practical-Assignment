<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");

require "../config.php";

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $id = $_GET['id'] ?? null;
    
    if (!$id) {
        echo json_encode(["status" => "error", "message" => "Course ID is required"]);
        exit();
    }

    $stmt = $conn->prepare("SELECT id, code, title, `description`, credits, category, `level`, semester, prerequisites, created_at FROM courses WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $course = $result->fetch_assoc();
        echo json_encode(["status" => "success", "data" => $course]);
    } else {
        echo json_encode(["status" => "error", "message" => "Course not found"]);
    }

    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}

$conn->close();
?>