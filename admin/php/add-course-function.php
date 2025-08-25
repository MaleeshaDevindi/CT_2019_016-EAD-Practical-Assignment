<?php
session_start();
// Database connection
include "../../php/config.php";

// Process form when submitted
$success = false;
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $code = $_POST['course_code'];
    $title = $_POST['course_title'];
    $description = $_POST['description'];
    $credits = $_POST['credits'];
    $category = $_POST['category'];
    $level = $_POST['level'];
    $semester = $_POST['semester'];
    $prerequisites = $_POST['prerequisites'];
    
    // Basic validation
    if (empty($code) || empty($title) || empty($description) || empty($credits) || 
        empty($category) || empty($level) || empty($semester)) {
        $error = "Please fill in all required fields";
    } else {
        // Escape input values to prevent SQL injection
        $code = $conn->real_escape_string($code);
        $title = $conn->real_escape_string($title);
        $description = $conn->real_escape_string($description);
        $credits = $conn->real_escape_string($credits);
        $category = $conn->real_escape_string($category);
        $level = $conn->real_escape_string($level);
        $semester = $conn->real_escape_string($semester);
        $prerequisites = $conn->real_escape_string($prerequisites);
        
        // Create SQL query
        $sql = "INSERT INTO courses (code, title, `description`, credits, category, `level`, semester, prerequisites, created_at) 
                VALUES ('$code', '$title', '$description', '$credits', '$category', '$level', '$semester', '$prerequisites', NOW())";
        
        // Execute query
        if ($conn->query($sql)) {
            $success = true;
            $_SESSION['message']="Course Added Successfully"; 
            $_SESSION['message-type']="success";
            header("Location: ../all-courses.php?success=CourseAddedSuccessfully");
            exit();
        } else {
            $error = "Error: " . $sql . "<br>" . $conn->error;
            $_SESSION['message']="Something Went Wrong!!!"; 
            $_SESSION['message-type']="error";
            header("Location: ../add-course.php?error=Something Went Wrong");
            exit();
        }
    }
}
?>