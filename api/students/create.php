<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

require "../config.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    // Validate required fields
    $required_fields = ['student_no', 'nic', 'first_name', 'last_name', 'email'];
    foreach ($required_fields as $field) {
        if (empty($data[$field])) {
            echo json_encode(["status" => "error", "message" => "$field is required"]);
            exit();
        }
    }

    // Extract data
    $student_no = $data['student_no'];
    $nic = $data['nic'];
    $first_name = $data['first_name'];
    $last_name = $data['last_name'];
    $email = $data['email'];
    $phone = $data['phone'] ?? '';
    $date_of_birth = $data['date_of_birth'] ?? null;
    $gender = $data['gender'] ?? '';
    $enrollment_date = $data['enrollment_date'] ?? date('Y-m-d');
    $address = $data['address'] ?? '';
    $emergency_contact_name = $data['emergency_contact_name'] ?? '';
    $emergency_contact_phone = $data['emergency_contact_phone'] ?? '';

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
        

        // Check if student number already exists
        $check_student_stmt = $conn->prepare("SELECT id FROM students WHERE student_no = ?");
        $check_student_stmt->bind_param("s", $student_no);
        $check_student_stmt->execute();
        
        if ($check_student_stmt->get_result()->num_rows > 0) {
            throw new Exception("Student number already exists");
        }

        // Hash password (using student number as default password)
        $password_hash = password_hash($student_no, PASSWORD_DEFAULT);
        $role = 'student';

        // Insert into users table
        $user_stmt = $conn->prepare("INSERT INTO users (nic, password_hash, role, created_at) VALUES (?, ?, ?, NOW())");
        $user_stmt->bind_param("sss", $nic, $password_hash, $role);
        
        if (!$user_stmt->execute()) {
            throw new Exception("Error creating user account");
        }

        $student_stmt = $conn->prepare("INSERT INTO students 
        (student_no, nic, first_name, last_name, email, phone, date_of_birth, gender, `address`, emergency_contact_name, emergency_contact_phone, `status`, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'active', NOW())");

        $student_stmt->bind_param("sssssssssss", 
            $student_no, 
            $nic, 
            $first_name, 
            $last_name, 
            $email, 
            $phone, 
            $date_of_birth, 
            $gender, 
            $address, 
            $emergency_contact_name, 
            $emergency_contact_phone
        );


        
        if (!$student_stmt->execute()) {
            throw new Exception("Error creating student record");
        }

        // Commit transaction
        $conn->commit();
        echo json_encode(["status" => "success", "message" => "Student added successfully"]);

    } catch (Exception $e) {
        // Rollback transaction
        $conn->rollback();
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }

    // Close statements
    if (isset($check_user_stmt)) $check_user_stmt->close();
    if (isset($check_student_stmt)) $check_student_stmt->close();
    if (isset($user_stmt)) $user_stmt->close();
    if (isset($student_stmt)) $student_stmt->close();

} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}

$conn->close();
?>