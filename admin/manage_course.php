<?php
require_once '../system/config.php';
require_once '../vendor/autoload.php';

// Set the path to the Twig templates folder
$loader = new \Twig\Loader\FilesystemLoader('../theme/admin');
$twig = new \Twig\Environment($loader);

session_start();

// Check if the admin is logged in, if not, redirect to login page
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$query = "SELECT * FROM courses";
$stmt = $pdo->prepare($query);
$stmt->execute();
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Render the manage_course.twig template
echo $twig->render('manage_course.twig', ['courses' => $courses]);
?>
