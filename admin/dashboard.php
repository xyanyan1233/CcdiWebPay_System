<?php

require_once '../system/config.php';

require_once '../vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('../theme/admin');
$twig = new \Twig\Environment($loader);


session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$stmt = $pdo->query("SELECT COUNT(*) as total_students FROM students");
$total_students = $stmt->fetchColumn();

// Render the dashboard template
echo $twig->render('dashboard.twig', [
    'total_students' => $total_students,
]);
?>
