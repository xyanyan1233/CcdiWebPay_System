<?php
// Include database connection
require_once '../system/config.php';
require_once '../vendor/autoload.php';

// Set up Twig
$loader = new \Twig\Loader\FilesystemLoader('../theme/admin');
$twig = new \Twig\Environment($loader);

try {
    // Fetch all cron settings
    $stmt = $pdo->query("SELECT * FROM cron_settings");
    $cron_jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

// Example cron URL
$cron_url = "curl --silent https://ccdionlinepayment.com/crons?token=SCHOOL-PHM4-GION-A580-5TSE";

echo $twig->render('cron_settings.twig', [
    'cron_jobs' => $cron_jobs,
    'cron_url' => $cron_url
]);
?>
