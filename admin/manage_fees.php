<?php
require_once '../system/config.php';
require_once '../vendor/autoload.php';
session_start();

// Check if the admin is logged in, if not, redirect to login page
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}
// Setup Twig
$loader = new \Twig\Loader\FilesystemLoader('../theme/admin'); // Adjust the path to your templates directory
$twig = new \Twig\Environment($loader);

// Fetch all fees from the database
$stmt = $pdo->query("SELECT * FROM fees");
$fees = $stmt->fetchAll();

// Render the Twig template and pass the fees data
echo $twig->render('manage_fees.twig', ['fees' => $fees]);
?>
