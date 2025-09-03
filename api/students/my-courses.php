<?php
header('Content-Type: application/json');
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student' || !isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

require '../config.php';

try {
    $stmt = $pdo->prepare('
        SELECT c.id, c.title, c.description, i.name as instructor_name
        FROM enrollments e
        JOIN courses c ON e.course_id = c.id
        JOIN instructors i ON c.instructor_id = i.id
        WHERE e.student_id = ?
    ');
    $stmt->execute([$_SESSION['user_id']]);
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($courses);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>