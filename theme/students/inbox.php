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

// Fetch student ID from session
$student_id = $_SESSION['student_id'];

// Fetch messages for the student
$stmt = $pdo->prepare("SELECT * FROM inbox WHERE student_id = :student_id ORDER BY timestamp DESC");
$stmt->execute(['student_id' => $student_id]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

// If a message is marked as read, update the database
if (isset($_POST['mark_as_read'])) {
    $message_id = $_POST['message_id'];
    $updateStmt = $pdo->prepare("UPDATE inbox SET is_read = 1 WHERE id = :id");
    $updateStmt->execute(['id' => $message_id]);

    echo json_encode(['status' => 'success']);
    exit;
}

// Render inbox page
echo $twig->render('inbox.twig', [
    'messages' => $messages
]);
?>
