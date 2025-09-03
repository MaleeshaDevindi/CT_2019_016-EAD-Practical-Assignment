<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Allow-Headers: Content-Type");

require "../config.php";

if ($_SERVER["REQUEST_METHOD"] === "PUT") {
    $data = json_decode(file_get_contents("php://input"), true);

    // Validate required fields
    $required_fields = ['id', 'instructor_no', 'nic', 'first_name', 'last_name', 'email', 'department', 'designation', 'qualification', 'status'];
    foreach ($required_fields as $field) {
        if (empty($data[$field])) {
            echo json_encode(["status" => "error", "message" => "$field is required"]);
            exit();
        }
    }

    // Extract data
    $id = $data['id'];
    $instructor_no = $data['instructor_no'];
    $nic = $data['nic'];
    $first_name = $data['first_name'];
    $last_name = $data['last_name'];
    $email = $data['email'];
    $phone = $data['phone'] ?? '';
    $department = $data['department'];
    $designation = $data['designation'];
    $qualification = $data['qualification'];
    $experience = $data['experience'] ?? 0;
    $specialization = $data['specialization'] ?? '';
    $date_of_birth = $data['date_of_birth'] ?? null;
    $gender = $data['gender'] ?? '';
    $join_date = $data['join_date'] ?? null;
    $address = $data['address'] ?? '';
    $office_location = $data['office_location'] ?? '';
    $office_hours = $data['office_hours'] ?? '';
    $status = $data['status'];
    $salary = $data['salary'] ?? 0;

    try {
        // Check if NIC already exists (excluding current instructor)
        $check_nic_stmt = $conn->prepare("SELECT id FROM staff WHERE nic = ? AND id != ?");
        $check_nic_stmt->bind_param("si", $nic, $id);
        $check_nic_stmt->execute();
        
        if ($check_nic_stmt->get_result()->num_rows > 0) {
            throw new Exception("NIC already exists");
        }

        // Update instructor
        $update_stmt = $conn->prepare("UPDATE staff SET 
            nic = ?, first_name = ?, last_name = ?, email = ?, phone = ?, 
            department = ?, designation = ?, qualification = ?, experience = ?, specialization = ?,
            date_of_birth = ?, gender = ?, join_date = ?, `address` = ?, office_location = ?, 
            office_hours = ?, `status` = ?, salary = ?
            WHERE id = ?");

        $update_stmt->bind_param(
            "ssssssssissssssssdi",
            $nic, $first_name, $last_name, $email, $phone,
            $department, $designation, $qualification, $experience, $specialization,
            $date_of_birth, $gender, $join_date, $address, $office_location,
            $office_hours, $status, $salary, $id
        );

        if ($update_stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Instructor updated successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to update instructor"]);
        }

        $update_stmt->close();
        $check_nic_stmt->close();

    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}

$conn->close();

?>