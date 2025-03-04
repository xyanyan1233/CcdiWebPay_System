<?php
// Include configuration file
require_once '../../system/config.php';
require_once '../../vendor/autoload.php';

// Start session
session_start();

// Check if the student is logged in
if (!isset($_SESSION['student_id'])) {
    header('Location: student_login.php');
    exit();
}

// Retrieve student ID
$student_id = $_SESSION['student_id'];

// Check if category is provided
if (!isset($_POST['category']) || empty($_POST['category'])) {
    $_SESSION['error_message'] = "Please select a payment category.";
    header('Location: new_payment.php');
    exit();
}

$category = trim($_POST['category']);

try {
    // Fetch the fee details
    $stmt = $pdo->prepare('SELECT * FROM fees WHERE type = :category AND status = "Pending" LIMIT 1');
    $stmt->execute(['category' => $category]);
    $fee = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$fee) {
        $_SESSION['error_message'] = "Invalid payment category or already paid.";
        header('Location: new_payment.php');
        exit();
    }

    // Extract fee details
    $amount = $fee['amount'];
    $due_date = $fee['due_date'];

    // Render the confirmation page
    echo $twig->render('confirm_payment.twig', [
        'student_id' => $student_id,
        'category'   => $category,
        'amount'     => $amount,
        'due_date'   => $due_date
    ]);
} catch (Exception $e) {
    $_SESSION['error_message'] = "An error occurred: " . $e->getMessage();
    header('Location: new_payment.php');
    exit();
}
