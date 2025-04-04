<?php
require_once '../../../system/config.php';
session_start();

if (!isset($_SESSION['student_id'])) {
    echo json_encode(['dueCount' => 0]);
    exit();
}

$student_id = $_SESSION['student_id'];

// Get payments due in 7 days or less
$stmt = $pdo->prepare("
    SELECT COUNT(*) AS due_count 
    FROM payments 
    WHERE student_id = :student_id 
    AND status = 'Pending' 
    AND due_date BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 7 DAY)
");
$stmt->execute(['student_id' => $student_id]);
$dueCount = $stmt->fetch(PDO::FETCH_ASSOC)['due_count'] ?? 0;

echo json_encode(['dueCount' => $dueCount]);
?>
