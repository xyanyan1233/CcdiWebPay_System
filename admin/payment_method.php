<?php
// Include configuration file
require_once '../system/config.php';
require_once '../vendor/autoload.php';

// Start session
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

// Set up Twig
$loader = new \Twig\Loader\FilesystemLoader('../theme/admin');
$twig = new \Twig\Environment($loader);

// Handle adding a new payment method
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    
    if ($action === 'add') {
        $name = $_POST['name'];
        $type = $_POST['type'];
        
        $stmt = $pdo->prepare("INSERT INTO payment_methods (name, type) VALUES (:name, :type)");
        $stmt->execute(['name' => $name, 'type' => $type]);
    } elseif ($action === 'delete' && isset($_POST['id'])) {
        $id = $_POST['id'];
        
        $stmt = $pdo->prepare("DELETE FROM payment_methods WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
    
    header('Location: payment_method.php');
    exit();
}

// Fetch existing payment methods
$stmt = $pdo->query("SELECT * FROM payment_methods");
$payment_methods = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Render the payment method management page
echo $twig->render('payment_method.twig', ['payment_methods' => $payment_methods]);


?>
