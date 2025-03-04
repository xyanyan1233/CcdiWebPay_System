<?php
// Include the database configuration
require_once '../system/config.php';
require_once '../vendor/autoload.php';

// Set up Twig
$loader = new \Twig\Loader\FilesystemLoader('../theme/admin'); // Adjust path as necessary
$twig = new \Twig\Environment($loader);

// Initialize an error variable
$error = null;

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validate user input
    if (empty($username) || empty($password)) {
        $error = 'Username and password are required.';
    } else {
        // Validate user credentials
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify the password
        if ($admin && password_verify($password, $admin['password'])) {
            // Start session and redirect to dashboard
            session_start();
            $_SESSION['admin_id'] = $admin['admin_id'];
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Invalid username or password.';
        }
    }
}

// Render the login template
echo $twig->render('login.twig', [
    'error' => $error,
]);
