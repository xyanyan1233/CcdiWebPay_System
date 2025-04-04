<?php
// Include configuration file
require_once '../../system/config.php';
require_once '../../vendor/autoload.php';
require_once 'header.php';

// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['student_id'])) {
    header('Location: student_login.php');
    exit();
}

// Set up Twig environment
$loader = new \Twig\Loader\FilesystemLoader('../../theme/view/ccditheme');
$twig = new \Twig\Environment($loader);

try {
    // Fetch student details
    $stmtStudent = $pdo->prepare('SELECT * FROM students WHERE id = :id');
    $stmtStudent->execute(['id' => $_SESSION['student_id']]);
    $student = $stmtStudent->fetch(PDO::FETCH_ASSOC);

    if (!$student) {
        header('Location: student_login.php');
        exit();
    }

    // Fetch payments for the student
    $stmtPayments = $pdo->prepare('SELECT * FROM payments WHERE student_id = :student_id');
    $stmtPayments->execute(['student_id' => $student['id']]);
    $payments = $stmtPayments->fetchAll(PDO::FETCH_ASSOC);

    // Render the Twig template
    echo $twig->render('profile.twig', [
        'student' => $student,
        'payments' => $payments,
        'pageTitle' => 'Profile',
        'navLinks' => $navLinks,
        'activePage' => 'profile'
    ]);
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
?>
