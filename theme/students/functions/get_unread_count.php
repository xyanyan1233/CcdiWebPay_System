<?php
require_once '../../../system/config.php';
session_start();

if (!isset($_SESSION['student_id'])) {
    echo json_encode(['unreadCount' => 0]);
    exit();
}

$student_id = $_SESSION['student_id'];

$stmt = $pdo->prepare("SELECT COUNT(*) AS unread_count FROM inbox WHERE student_id = :student_id AND is_read = 0");
$stmt->execute(['student_id' => $student_id]);
$unreadCount = $stmt->fetch(PDO::FETCH_ASSOC)['unread_count'] ?? 0;

echo json_encode(['unreadCount' => $unreadCount]);
?>
