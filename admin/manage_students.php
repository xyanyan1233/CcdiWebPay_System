<?php
// Include the required configuration and setup files
require_once '../system/config.php';
require_once '../vendor/autoload.php';

// Set up Twig
$loader = new \Twig\Loader\FilesystemLoader('../theme/admin');
$twig = new \Twig\Environment($loader);

session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Fetch students data from the database
$stmt = $pdo->query("SELECT * FROM students");
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Render the manage_students.twig template with student data
echo $twig->render('manage_students.twig', ['students' => $students]);
?>
