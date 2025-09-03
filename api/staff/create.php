<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

require "../config.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    // Validate required fields
    $required_fields = ['instructor_no', 'nic', 'first_name', 'last_name', 'email', 'department', 'designation', 'qualification', 'status'];
    foreach ($required_fields as $field) {
        if (empty($data[$field])) {
            echo json_encode(["status" => "error", "message" => "$field is required"]);
            exit();
        }
    }

    // Extract data
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
    $join_date = $data['join_date'] ?? date('Y-m-d');
    $address = $data['address'] ?? '';
    $status = $data['status'];
    $salary = $data['salary'] ?? 0;

    // Start transaction
    $conn->begin_transaction();

    try {
        // Check if NIC already exists in users table
        $check_user_stmt = $conn->prepare("SELECT id FROM users WHERE nic = ?");
        $check_user_stmt->bind_param("s", $nic);
        $check_user_stmt->execute();
        
        if ($check_user_stmt->get_result()->num_rows > 0) {
            throw new Exception("NIC already exists in system");
        }

        // Check if instructor number already exists
        $check_instructor_stmt = $conn->prepare("SELECT id FROM staff WHERE instructor_no = ?");
        $check_instructor_stmt->bind_param("s", $instructor_no);
        $check_instructor_stmt->execute();
        
        if ($check_instructor_stmt->get_result()->num_rows > 0) {
            throw new Exception("Instructor number already exists");
        }

        // Hash password (using instructor number as default password)
        $password_hash = password_hash($instructor_no, PASSWORD_DEFAULT);
        $role = 'staff';

        // Insert into users table
        $user_stmt = $conn->prepare("INSERT INTO users (nic, password_hash, `role`, created_at) VALUES (?, ?, ?, NOW())");
        $user_stmt->bind_param("sss", $nic, $password_hash, $role);
        
        if (!$user_stmt->execute()) {
            throw new Exception("Error creating user account");
        }

        // Insert into staff table
        $staff_stmt = $conn->prepare("INSERT INTO staff (instructor_no, nic, first_name, last_name, email, phone, department, designation, qualification, experience, specialization, date_of_birth, gender, join_date, `address`, `status`, salary, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        
        $staff_stmt->bind_param(
            "sssssssssissssssd",
            $instructor_no, $nic, $first_name, $last_name, $email, $phone, 
            $department, $designation, $qualification, $experience, $specialization,
            $date_of_birth, $gender, $join_date, $address, $status, $salary
        );
        
        if (!$staff_stmt->execute()) {
            throw new Exception("Error creating instructor record");
        }

        // Commit transaction
        $conn->commit();
        echo json_encode(["status" => "success", "message" => "Instructor added successfully"]);

    } catch (Exception $e) {
        // Rollback transaction
        $conn->rollback();
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }

    // Close statements
    if (isset($check_user_stmt)) $check_user_stmt->close();
    if (isset($check_instructor_stmt)) $check_instructor_stmt->close();
    if (isset($user_stmt)) $user_stmt->close();
    if (isset($staff_stmt)) $staff_stmt->close();

} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}

$conn->close();
?>