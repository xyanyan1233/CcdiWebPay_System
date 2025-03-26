<?php
// Include database connection
require_once '../system/config.php';
require_once '../vendor/autoload.php';

// Set up Twig
$loader = new \Twig\Loader\FilesystemLoader('../theme/admin');
$twig = new \Twig\Environment($loader);

// Fetch the latest update version from the database
try {
    $stmt = $pdo->query("SELECT version FROM updates ORDER BY id DESC LIMIT 1");
    $update = $stmt->fetch(PDO::FETCH_ASSOC);
    $current_version = $update ? $update['version'] : 'Unknown';
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

// Render the update page with the current version
echo $twig->render('update.twig', ['current_version' => $current_version]);
?>
