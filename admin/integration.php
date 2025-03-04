<?php
// Include the required configuration and setup files
require_once '../system/config.php';
require_once '../vendor/autoload.php';

// Set up Twig
$loader = new \Twig\Loader\FilesystemLoader('../theme/admin');
$twig = new \Twig\Environment($loader);

session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Fetch all integrations
try {
    $stmt = $pdo->query("SELECT id, name, description, icon, status FROM integrations ORDER BY status DESC, name ASC");
    $integrations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

// Handle integration activation
if (isset($_POST['activateIntegration'])) {
    $integrationId = $_POST['integrationId'];
    $stmt = $pdo->prepare("UPDATE integrations SET status = 'active' WHERE id = ?");
    $stmt->execute([$integrationId]);
    header("Location: integration.php");
    exit;
}

// Handle integration deactivation
if (isset($_POST['deactivateIntegration'])) {
    $integrationId = $_POST['integrationId'];
    $stmt = $pdo->prepare("UPDATE integrations SET status = 'inactive' WHERE id = ?");
    $stmt->execute([$integrationId]);
    header("Location: integration.php");
    exit;
}

// Handle integration deletion
if (isset($_POST['deleteIntegration'])) {
    $integrationId = $_POST['integrationId'];
    $stmt = $pdo->prepare("DELETE FROM integrations WHERE id = ?");
    $stmt->execute([$integrationId]);
    header("Location: integration.php");
    exit;
}

// Separate active and inactive integrations
$activeIntegrations = [];
$otherIntegrations = [];

foreach ($integrations as $integration) {
    if ($integration['status'] === 'active') {
        $activeIntegrations[] = $integration;
    } else {
        $otherIntegrations[] = $integration;
    }
}

// Render the integration management page
echo $twig->render('integration.twig', [
    'activeIntegrations' => $activeIntegrations,
    'otherIntegrations' => $otherIntegrations
]);