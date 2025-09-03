<?php
session_start();
// Database connection
include "../../php/config.php";

// Process form when submitted
$success = false;
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $nic = $_POST['nic'];
    $student_no = $_POST['student_no'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $student_no;

    // Basic validation
    if (empty($nic) || empty($student_no) || empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
        $error = "Please fill in all required fields";
    } else {
        // Escape input values to prevent SQL injection
        $nic = $conn->real_escape_string($nic);
        $student_no = $conn->real_escape_string($student_no);
        $first_name = $conn->real_escape_string($first_name);
        $last_name = $conn->real_escape_string($last_name);
        $email = $conn->real_escape_string($email);
        $phone = $conn->real_escape_string($phone);
        
        // Hash the password
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $role = 'student'; // Set default role
        
        // Start transaction to ensure both inserts succeed or fail together
        $conn->begin_transaction();
        
        try {
            // Insert into users table
            $user_sql = "INSERT INTO users (nic, password_hash, role, created_at) 
                        VALUES ('$nic', '$password_hash', '$role', NOW())";
            
            if (!$conn->query($user_sql)) {
                throw new Exception("Error inserting into users: " . $conn->error);
            }
            
            // Insert into students table 
            $student_sql = "INSERT INTO students (nic, student_no, first_name, last_name, email, phone, status, created_at) 
                           VALUES ('$nic','$student_no', '$first_name', '$last_name', '$email', '$phone', 'active', NOW())";
            
            if (!$conn->query($student_sql)) {
                throw new Exception("Error inserting into students: " . $conn->error);
            }
            
            // Commit transaction if both queries succeed
            $conn->commit();
            
            $success = true;
            $_SESSION['message'] = "Student Added Successfully"; 
            $_SESSION['message-type'] = "success";
            header("Location: ../all-students.php?success=StudentAddedSuccessfully");
            exit();
            
        } catch (Exception $e) {
            // Rollback transaction if any query fails
            $conn->rollback();
            $error = $e->getMessage();
            $_SESSION['message'] = "Something Went Wrong!!!"; 
            $_SESSION['message-type'] = "error";
            header("Location: ../add-student.php?error=" . urlencode($error));
            exit();
        }
    }
}
?>