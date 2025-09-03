<?php
session_start();

header("Content-Type: application/json");
require "../config.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    $nic = $data["nic"] ?? "";
    $password = $data["password"] ?? "";

    // Validate input
    if (empty($nic) || empty($password)) {
        echo json_encode(["status" => "error", "message" => "NIC and password are required"]);
        exit();
    }

    // Check if user exists with this NIC
    $stmt = $conn->prepare("SELECT nic, password_hash, role FROM users WHERE nic = ?");
    if (!$stmt) {
        echo json_encode(["status" => "error", "message" => "Database error: " . $conn->error]);
        exit();
    }
    
    $stmt->bind_param("s", $nic);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $hashedPassword = $row["password_hash"];

        // Verify entered password against hash
        if (password_verify($password, $hashedPassword)) {
            $_SESSION['role'] = $row['role'];
            $_SESSION['nic'] = $row['nic'];
            echo json_encode([
                "status" => "success", 
                "message" => "Login successful",
                "role" => $row['role']
            ]);
        } else {
            echo json_encode(["status" => "error", "message" => "Invalid password"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "User not found"]);
    }
    
    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}

$conn->close();
?>