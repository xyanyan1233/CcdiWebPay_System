<?php
require_once '../../../system/config.php';
require_once '../../../vendor/autoload.php';

// Allow CORS for AJAX if needed
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/javascript");


// Handle live search
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
$searchTerm = isset($_GET['category']) ? trim($_GET['category']) : '';

try {
    $stmt = $pdo->prepare('SELECT * FROM fees WHERE status = "Active" AND type LIKE :search');
    $stmt->execute(['search' => "%$searchTerm%"]);
    $fees = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($fees);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Error fetching data']);
}