<?php
require_once '../../../system/config.php';
session_start();

if (!isset($_SESSION['student_id'])) {
    echo json_encode([]);
    exit();
}

$student_id = $_SESSION['student_id'];

// Fetch latest 10 messages, ordered by ID (assuming higher ID = newer message)
$stmt = $pdo->prepare("SELECT id, message, is_read FROM inbox WHERE student_id = :student_id ORDER BY id DESC LIMIT 10");
$stmt->execute(['student_id' => $student_id]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return JSON response
echo json_encode($messages);
?>
