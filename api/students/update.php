<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Allow-Headers: Content-Type");

require "../config.php";

if ($_SERVER["REQUEST_METHOD"] === "PUT") {
    $data = json_decode(file_get_contents("php://input"), true);

    // Validate required fields
    $required_fields = ['id', 'student_no', 'nic', 'first_name', 'last_name', 'email', 'status'];
    foreach ($required_fields as $field) {
        if (empty($data[$field])) {
            echo json_encode(["status" => "error", "message" => "$field is required"]);
            exit();
        }
    }

    // Extract data
    $id = $data['id'];
    $student_no = $data['student_no'];
    $nic = $data['nic'];
    $first_name = $data['first_name'];
    $last_name = $data['last_name'];
    $email = $data['email'];
    $phone = $data['phone'] ?? '';
    $status = $data['status'];
    $date_of_birth = $data['date_of_birth'] ?? null;
    $gender = $data['gender'] ?? '';
    $address = $data['address'] ?? '';
    $emergency_contact_name = $data['emergency_contact_name'] ?? '';
    $emergency_contact_phone = $data['emergency_contact_phone'] ?? '';

    try {
        // Check if student number already exists (excluding current student)
        $check_stmt = $conn->prepare("SELECT id FROM students WHERE student_no = ? AND id != ?");
        $check_stmt->bind_param("si", $student_no, $id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            echo json_encode(["status" => "error", "message" => "Student number already exists"]);
            exit();
        }

        // Check if NIC already exists (excluding current student)
        $check_nic_stmt = $conn->prepare("SELECT id FROM students WHERE nic = ? AND id != ?");
        $check_nic_stmt->bind_param("si", $nic, $id);
        $check_nic_stmt->execute();
        $check_nic_result = $check_nic_stmt->get_result();

        if ($check_nic_result->num_rows > 0) {
            echo json_encode(["status" => "error", "message" => "NIC already exists"]);
            exit();
        }

        // Update student
        $update_stmt = $conn->prepare("UPDATE students SET 
            student_no = ?, nic = ?, first_name = ?, last_name = ?, email = ?, phone = ?, 
            status = ?, date_of_birth = ?, gender = ?, address = ?, 
            emergency_contact_name = ?, emergency_contact_phone = ? 
            WHERE id = ?");

        $update_stmt->bind_param(
            "ssssssssssssi", 
            $student_no, $nic, $first_name, $last_name, $email, $phone, 
            $status, $date_of_birth, $gender, $address, 
            $emergency_contact_name, $emergency_contact_phone, $id
        );

        if ($update_stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Student updated successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to update student"]);
        }

        $update_stmt->close();
        $check_stmt->close();
        $check_nic_stmt->close();

    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}

$conn->close();
?>