<?php
require_once '../../../system/config.php';
session_start();

if (!isset($_SESSION['student_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

$student_id = $_SESSION['student_id'];

try {
    $stmt = $pdo->prepare("SELECT first_name FROM students WHERE id = :student_id");
    $stmt->execute(['student_id' => $student_id]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($student) {
        echo json_encode(['first_name' => $student['first_name']]);
    } else {
        echo json_encode(['error' => 'User not found']);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error']);
}
?>
