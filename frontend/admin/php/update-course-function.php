<?php
session_start();
include '../../php/config.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get and sanitize form data
    $course_id = isset($_POST['course_id']) ? intval($_POST['course_id']) : 0;
    $course_code = isset($_POST['course_code']) ? mysqli_real_escape_string($conn, trim($_POST['course_code'])) : '';
    $course_title = isset($_POST['course_title']) ? mysqli_real_escape_string($conn, trim($_POST['course_title'])) : '';
    $description = isset($_POST['description']) ? mysqli_real_escape_string($conn, trim($_POST['description'])) : '';
    $credits = isset($_POST['credits']) ? intval($_POST['credits']) : 0;
    $category = isset($_POST['category']) ? mysqli_real_escape_string($conn, trim($_POST['category'])) : '';
    $level = isset($_POST['level']) ? mysqli_real_escape_string($conn, trim($_POST['level'])) : '';
    $semester = isset($_POST['semester']) ? mysqli_real_escape_string($conn, trim($_POST['semester'])) : '';
    $prerequisites = isset($_POST['prerequisites']) ? mysqli_real_escape_string($conn, trim($_POST['prerequisites'])) : '';

    // Validate required fields
    $errors = [];
    
    if ($course_id <= 0) {
        $errors[] = "Invalid course ID.";
    }
    
    if (empty($course_code)) {
        $errors[] = "Course code is required.";
    }
    
    if (empty($course_title)) {
        $errors[] = "Course title is required.";
    }
    
    if (empty($description)) {
        $errors[] = "Course description is required.";
    }
    
    if ($credits <= 0) {
        $errors[] = "Valid number of credits is required.";
    }
    
    if (empty($category)) {
        $errors[] = "Category is required.";
    }
    
    if (empty($level)) {
        $errors[] = "Level is required.";
    }
    
    if (empty($semester)) {
        $errors[] = "Semester is required.";
    }

    // If there are errors, redirect back with error message
    if (!empty($errors)) {
        $_SESSION['message'] = implode(" ", $errors);
        $_SESSION['message-type'] = 'error';
        header("Location: ../update-course.php?id=" . $course_id);
        exit();
    }

    // Check if course code already exists (excluding current course)
    $check_sql = "SELECT id FROM courses WHERE code = '$course_code' AND id != $course_id";
    $check_result = mysqli_query($conn, $check_sql);
    
    if (mysqli_num_rows($check_result) > 0) {
        $_SESSION['message'] = "Course code already exists. Please use a different code.";
        $_SESSION['message-type'] = 'error';
        header("Location: ../update-course.php?id=" . $course_id);
        exit();
    }

    // Update course in database
    $update_sql = "UPDATE courses SET 
                    code = '$course_code',
                    title = '$course_title',
                    description = '$description',
                    credits = $credits,
                    category = '$category',
                    level = '$level',
                    semester = '$semester',
                    prerequisites = '$prerequisites'
                  WHERE id = $course_id";

    if (mysqli_query($conn, $update_sql)) {
        if (mysqli_affected_rows($conn) > 0) {
            $_SESSION['message'] = "Course updated successfully!";
            $_SESSION['message-type'] = 'success';
            // Redirect back to update course page
            header("Location: ../course-details.php?succeess=updated&id=" . $course_id);
            exit();
        } else {
            $_SESSION['message'] = "No changes were made to the course.";
            $_SESSION['message-type'] = 'info';
            // Redirect back to update course page
            header("Location: ../update-course.php?error=failed&id=" . $course_id);
            exit();
        }
    } else {
        $_SESSION['message'] = "Error updating course: " . mysqli_error($conn);
        $_SESSION['message-type'] = 'error';
    }

    
} else {
    // If not a POST request, redirect to courses page
    $_SESSION['message'] = "Invalid request method.";
    $_SESSION['message-type'] = 'error';
    header("Location: ../all-courses.php");
    exit();
}
?>