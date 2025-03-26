<?php
// Include the required configuration and setup files
require_once '../system/config.php';
require_once '../vendor/autoload.php';

// Set up Twig
$loader = new \Twig\Loader\FilesystemLoader('../theme/admin');
$twig = new \Twig\Environment($loader);

session_start();

// Redirect if not logged in as admin
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Fetch cPanel settings from the database
try {
    $stmt = $pdo->query("SELECT * FROM cpanel_settings ORDER BY id DESC LIMIT 1");
    $cpanel = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

// Handle form submission to update or insert settings
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cpanel_user = $_POST['cpanel_user'] ?? '';
    $cpanel_api_key = $_POST['cpanel_api_key'] ?? '';
    $cpanel_host = $_POST['cpanel_host'] ?? '';
    $ns1 = $_POST['ns1'] ?? '';
    $ns2 = $_POST['ns2'] ?? '';

    try {
        if ($cpanel) {
            // Update existing record
            $stmt = $pdo->prepare("UPDATE cpanel_settings SET cpanel_user = ?, cpanel_api_key = ?, cpanel_host = ?, ns1 = ?, ns2 = ? WHERE id = ?");
            $stmt->execute([$cpanel_user, $cpanel_api_key, $cpanel_host, $ns1, $ns2, $cpanel['id']]);
        } else {
            // Insert a new record if none exists
            $stmt = $pdo->prepare("INSERT INTO cpanel_settings (cpanel_user, cpanel_api_key, cpanel_host, ns1, ns2) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$cpanel_user, $cpanel_api_key, $cpanel_host, $ns1, $ns2]);
        }

        // Redirect to refresh page
        header("Location: cpanel_settings.php?success=1");
        exit;
    } catch (PDOException $e) {
        die("Update error: " . $e->getMessage());
    }
}

// Render the settings page
echo $twig->render('cpanel_settings.twig', [
    'cpanel_user' => $cpanel['cpanel_user'] ?? '',
    'cpanel_api_key' => $cpanel['cpanel_api_key'] ?? '',
    'cpanel_host' => $cpanel['cpanel_host'] ?? '',
    'ns1' => $cpanel['ns1'] ?? '',
    'ns2' => $cpanel['ns2'] ?? '',
    'success' => $_GET['success'] ?? 0
]);
?>
