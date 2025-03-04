<?php
// Include configuration file
require_once '../../system/config.php';
require_once '../../vendor/autoload.php';

// Start the session
session_start();

// Set the path to the Twig templates folder
$loader = new \Twig\Loader\FilesystemLoader('../../theme/view/ccditheme');
$twig = new \Twig\Environment($loader);

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get email and password from the POST request
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        // Query the database to find the user
        $stmt = $pdo->prepare('SELECT * FROM students WHERE email = :email');
        $stmt->execute(['email' => $email]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC);

        // Validate the credentials
        if ($student && hash('sha256', $password) === $student['password']) {
            // Store user data in session
            $_SESSION['student_id'] = $student['id'];
            $_SESSION['student_name'] = $student['first_name'] . ' ' . $student['last_name'];

            // Redirect to the payment page
            header('Location: new_payment.php');
            exit();
        } else {
            $error_message = 'Invalid email or password.';
        }
    } catch (PDOException $e) {
        $error_message = 'An error occurred. Please try again later.';
    }
}

// Render the Twig template
echo $twig->render('student_login.twig', [
    'error_message' => $error_message ?? null
]);
