<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

include '../config.php';

session_start();

// Check if user is authenticated and has student role
if (!isset($_SESSION['nic']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    echo json_encode(['status' => 'error', 'message' => 'User not authenticated or not a student']);
    exit;
}

$nic = mysqli_real_escape_string($conn, $_SESSION['nic']);

// Fetch student_id from students table using NIC
$query = "SELECT id FROM students WHERE nic = '$nic'";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    echo json_encode(['status' => 'error', 'message' => 'Student not found']);
    exit;
}

$student = mysqli_fetch_assoc($result);
$student_id = $student['id'];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Fetch enrolled courses
    $query = "SELECT course_id FROM enrollments WHERE student_id = $student_id AND status = 'enrolled'";
    $result = mysqli_query($conn, $query);
    
    if ($result) {
        $enrollments = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $enrollments[] = $row;
        }
        echo json_encode(['status' => 'success', 'data' => $enrollments]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error fetching enrollments']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Enroll in a course
    $input = json_decode(file_get_contents('php://input'), true);
    $course_id = isset($input['course_id']) ? (int)$input['course_id'] : 0;

    if ($course_id <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid course ID']);
        exit;
    }

    // Check if course exists
    $query = "SELECT id FROM courses WHERE id = $course_id";
    $result = mysqli_query($conn, $query);
    if (!$result || mysqli_num_rows($result) == 0) {
        echo json_encode(['status' => 'error', 'message' => 'Course not found']);
        exit;
    }

    // Check if already enrolled
    $query = "SELECT id FROM enrollments WHERE student_id = $student_id AND course_id = $course_id AND status = 'enrolled'";
    $result = mysqli_query($conn, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Already enrolled in this course']);
        exit;
    }

    // Check prerequisites
    $query = "SELECT prerequisites FROM courses WHERE id = $course_id";
    $result = mysqli_query($conn, $query);
    $course = mysqli_fetch_assoc($result);
    $prerequisites = $course['prerequisites'];

    if ($prerequisites && $prerequisites !== 'None') {
        $prerequisites = mysqli_real_escape_string($conn, $prerequisites);
        $query = "SELECT COUNT(*) as count FROM enrollments e 
                  JOIN results r ON e.id = r.enrollment_id 
                  WHERE e.student_id = $student_id 
                  AND e.course_id IN (SELECT id FROM courses WHERE code = '$prerequisites') 
                  AND r.grade IS NOT NULL";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
        if ($row['count'] == 0) {
            echo json_encode(['status' => 'error', 'message' => 'Prerequisite course not completed']);
            exit;
        }
    }

    // Enroll student
    $query = "INSERT INTO enrollments (student_id, course_id, enrolled_at, status) 
              VALUES ($student_id, $course_id, NOW(), 'enrolled')";
    if (mysqli_query($conn, $query)) {
        echo json_encode(['status' => 'success', 'message' => 'Successfully enrolled in the course']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error enrolling in course']);
    }
}
?>