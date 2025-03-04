<?php
require_once '../system/config.php';

require_once '../vendor/autoload.php';
session_start();

// Check if the admin is logged in, if not, redirect to login page
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}
$loader = new \Twig\Loader\FilesystemLoader('../theme/admin');
$twig = new \Twig\Environment($loader);
// Fetch all transactions
$query = "SELECT * FROM transactions ORDER BY date_created DESC";
$stmt = $pdo->prepare($query);
$stmt->execute();
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Render the Twig template
echo $twig->render('transaction_history.twig', ['transactions' => $transactions]);
?>
