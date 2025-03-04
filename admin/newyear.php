<?php
// Include configuration and setup
require_once '../system/config.php';
require_once '../vendor/autoload.php';

session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Fetch current New Year settings
$stmt = $pdo->query("SELECT * FROM newyear_settings WHERE id = 1");
$settings = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $falling_snow = isset($_POST['falling_snow']) ? 1 : 0;
    $snowflakes = $_POST['snowflakes'] ?? 20;
    $fall_speed = $_POST['fall_speed'] ?? 'Slow';
    $garlands = isset($_POST['garlands']) ? 1 : 0;
    $shape = $_POST['shape'] ?? 'Oval';
    $colors = $_POST['colors'] ?? 'Style 1';
    $fireworks = isset($_POST['fireworks']) ? 1 : 0;
    $fireworks_size = $_POST['fireworks_size'] ?? 'Small';
    $fireworks_delay = $_POST['fireworks_delay'] ?? 'Slow';
    $toys_enabled = isset($_POST['toys_enabled']) ? 1 : 0;
    $selected_toys = json_encode($_POST['selected_toys'] ?? []);
    $toy_size = $_POST['toy_size'] ?? 'Small';
    $toy_quantity = $_POST['toy_quantity'] ?? '20';
    $toy_fall_speed = $_POST['toy_fall_speed'] ?? 'Slow';
    $toy_launches = $_POST['toy_launches'] ?? 'One time';
    
    $stmt = $pdo->prepare("UPDATE newyear_settings SET falling_snow=?, snowflakes=?, fall_speed=?, garlands=?, shape=?, colors=?, fireworks=?, fireworks_size=?, fireworks_delay=?, toys_enabled=?, selected_toys=?, toy_size=?, toy_quantity=?, toy_fall_speed=?, toy_launches=? WHERE id = 1");
    $stmt->execute([$falling_snow, $snowflakes, $fall_speed, $garlands, $shape, $colors, $fireworks, $fireworks_size, $fireworks_delay, $toys_enabled, $selected_toys, $toy_size, $toy_quantity, $toy_fall_speed, $toy_launches]);
    header("Location: newyear.php");
    exit;
}

// Set up Twig
$loader = new \Twig\Loader\FilesystemLoader('../theme/admin');
$twig = new \Twig\Environment($loader);

echo $twig->render('newyear.twig', ['settings' => $settings]);
?>
