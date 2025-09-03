<?php
header('Content-Type: application/json');
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized or missing course ID']);
    exit();
}

require '../config.php';

try {
    $stmt = $pdo->prepare('
        SELECT c.id, c.title, c.description, i.name as instructor_name
        FROM courses c
        JOIN instructors i ON c.instructor_id = i.id
        JOIN enrollments e ON e.course_id = c.id
        WHERE c.id = ? AND e.student_id = ?
    ');
    $stmt->execute([$_GET['id'], $_SESSION['user_id']]);
    $course = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$course) {
        http_response_code(404);
        echo json_encode(['error' => 'Course not found or not enrolled']);
        exit();
    }
    
    echo json_encode($course);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>