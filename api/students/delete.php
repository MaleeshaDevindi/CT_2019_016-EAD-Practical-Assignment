<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Allow-Headers: Content-Type");

require "../config.php";

if ($_SERVER["REQUEST_METHOD"] === "DELETE") {
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'] ?? null;

    if (!$id) {
        echo json_encode(["status" => "error", "message" => "Student ID is required"]);
        exit();
    }

    // Start transaction
    $conn->begin_transaction();

    try {
        // Get student NIC first
        $nic_stmt = $conn->prepare("SELECT nic FROM students WHERE id = ?");
        $nic_stmt->bind_param("i", $id);
        $nic_stmt->execute();
        $nic_result = $nic_stmt->get_result();
        
        if ($nic_result->num_rows === 0) {
            throw new Exception("Student not found");
        }

        $student = $nic_result->fetch_assoc();
        $nic = $student['nic'];

        // Delete from students table
        $student_stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
        $student_stmt->bind_param("i", $id);
        
        if (!$student_stmt->execute()) {
            throw new Exception("Error deleting student record");
        }

        // Delete from users table
        $user_stmt = $conn->prepare("DELETE FROM users WHERE nic = ?");
        $user_stmt->bind_param("s", $nic);
        
        if (!$user_stmt->execute()) {
            throw new Exception("Error deleting user account");
        }

        // Commit transaction
        $conn->commit();
        echo json_encode(["status" => "success", "message" => "Student deleted successfully"]);

    } catch (Exception $e) {
        // Rollback transaction
        $conn->rollback();
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }

    // Close statements
    if (isset($nic_stmt)) $nic_stmt->close();
    if (isset($student_stmt)) $student_stmt->close();
    if (isset($user_stmt)) $user_stmt->close();

} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}

$conn->close();
?>