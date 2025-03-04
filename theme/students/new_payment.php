<?php
// Include configuration file
require_once '../../system/config.php';
require_once '../../vendor/autoload.php';

// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['student_id'])) {
    // Redirect to login page
    header('Location: student_login.php');
    exit();
}

// Set up Twig environment
$loader = new \Twig\Loader\FilesystemLoader('../../theme/view/ccditheme');
$twig = new \Twig\Environment($loader);

// Fetch necessary data for the payment page
try {
    $stmtStudent = $pdo->prepare('SELECT * FROM students WHERE id = :id');
    $stmtStudent->execute(['id' => $_SESSION['student_id']]);
    $student = $stmtStudent->fetch(PDO::FETCH_ASSOC);

    if (!$student) {
        header('Location: student_login.php');
        exit();
    }

    // Fetch pending payments data
    $stmtPendingPayments = $pdo->prepare('SELECT * FROM payments WHERE student_id = :student_id AND status = "Pending"');
    $stmtPendingPayments->execute(['student_id' => $_SESSION['student_id']]);
    $pendingPayments = $stmtPendingPayments->fetchAll(PDO::FETCH_ASSOC);

    // Fetch active fees (all categories)
    $stmtFees = $pdo->prepare('SELECT * FROM fees WHERE status = "Active"');
    $stmtFees->execute();
    $fees = $stmtFees->fetchAll(PDO::FETCH_ASSOC);

    // Calculate total balance (sum of pending payments if any)
    $totalBalance = 0;
    foreach ($pendingPayments as $payment) {
        $totalBalance += $payment['amount'];
    }

    // Render the payment template
    echo $twig->render('new_payment.twig', [
        'student' => $student,
        'pending_payments' => $pendingPayments,
        'fees' => $fees,
        'total_balance' => $totalBalance,
        'categories' => $fees, // Pass the categories to the template
    ]);
} catch (PDOException $e) {
    echo 'Error fetching data: ' . $e->getMessage();
}
?>
