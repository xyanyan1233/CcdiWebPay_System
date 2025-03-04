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

// Handle adding a currency
if (isset($_POST['addCurrency'])) {
    $currencyName = $_POST['currencyName'];
    $currencySymbol = $_POST['currencySymbol'];
    $currencyRate = $_POST['currencyRate'];

    $stmt = $pdo->prepare("INSERT INTO currencies (name, symbol, rate) VALUES (?, ?, ?)");
    $stmt->execute([$currencyName, $currencySymbol, $currencyRate]);
    header('Location: currency.php'); // Redirect after adding
    exit;
}

// Handle auto currency rate update (This will be implemented based on your service/API)
if (isset($_POST['autoCurrencyRate'])) {
    // Implement the logic for auto rate update (for example, fetching from an API)
    // Update the rates in the database
}

// Handle edit currency
if (isset($_POST['editCurrency'])) {
    $currencyId = $_POST['currencyId'];
    $currencyName = $_POST['currencyName'];
    $currencySymbol = $_POST['currencySymbol'];
    $currencyRate = $_POST['currencyRate'];

    $stmt = $pdo->prepare("UPDATE currencies SET name = ?, symbol = ?, rate = ? WHERE id = ?");
    $stmt->execute([$currencyName, $currencySymbol, $currencyRate, $currencyId]);
    header('Location: currency.php'); // Redirect after edit
    exit;
}

// Handle delete currency
if (isset($_POST['deleteCurrency'])) {
    $currencyId = $_POST['currencyId'];

    $stmt = $pdo->prepare("DELETE FROM currencies WHERE id = ?");
    $stmt->execute([$currencyId]);
    header('Location: currency.php'); // Redirect after delete
    exit;
}

// Fetch all currencies
$stmt = $pdo->query("SELECT * FROM currencies");
$currencies = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Render the currency management page
echo $twig->render('currency.twig', ['currencies' => $currencies]);
?>
