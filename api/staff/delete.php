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
        echo json_encode(["status" => "error", "message" => "Instructor ID is required"]);
        exit();
    }

    // Start transaction
    $conn->begin_transaction();

    try {
        // Get instructor NIC first
        $nic_stmt = $conn->prepare("SELECT nic FROM staff WHERE id = ?");
        $nic_stmt->bind_param("i", $id);
        $nic_stmt->execute();
        $nic_result = $nic_stmt->get_result();
        
        if ($nic_result->num_rows === 0) {
            throw new Exception("Instructor not found");
        }

        $instructor = $nic_result->fetch_assoc();
        $nic = $instructor['nic'];

        // Delete from staff table
        $staff_stmt = $conn->prepare("DELETE FROM staff WHERE id = ?");
        $staff_stmt->bind_param("i", $id);
        
        if (!$staff_stmt->execute()) {
            throw new Exception("Error deleting instructor record");
        }

        // Delete from users table
        $user_stmt = $conn->prepare("DELETE FROM users WHERE nic = ?");
        $user_stmt->bind_param("s", $nic);
        
        if (!$user_stmt->execute()) {
            throw new Exception("Error deleting user account");
        }

        // Commit transaction
        $conn->commit();
        echo json_encode(["status" => "success", "message" => "Instructor deleted successfully"]);

    } catch (Exception $e) {
        // Rollback transaction
        $conn->rollback();
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }

    // Close statements
    if (isset($nic_stmt)) $nic_stmt->close();
    if (isset($staff_stmt)) $staff_stmt->close();
    if (isset($user_stmt)) $user_stmt->close();

} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}

$conn->close();
?>