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

// Set up Twig
$loader = new \Twig\Loader\FilesystemLoader('../../theme/view/ccditheme');
$twig = new \Twig\Environment($loader);

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Error: Invalid request method.");
}

// Check if the category and amount are passed from the previous form
if (!isset($_POST['category']) || !isset($_POST['amount'])) {
    die("Error: Category or amount is missing.");
}

$category = $_POST['category'];
$amount = $_POST['amount'];

// Fetch student information
$student_id = $_SESSION['student_id'];
$stmt = $pdo->prepare('SELECT first_name, last_name FROM students WHERE id = :student_id');
$stmt->execute(['student_id' => $student_id]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    die('Student not found.');
}

// Fetch available payment methods
$payment_methods = [
    ['name' => 'PayPal', 'icon' => 'fa-paypal'],
    ['name' => 'GCash', 'icon' => 'fa-mobile'],
    ['name' => 'Credit Card', 'icon' => 'fa-credit-card']
];

// Render the payment method selection page
echo $twig->render('method_payment.twig', [
    'student' => $student,
    'payment_methods' => $payment_methods,
    'category' => $category,
    'amount' => $amount
]);
?>
