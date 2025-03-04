<?php
require_once '../system/config.php';
require_once '../vendor/autoload.php';

// Initialize Twig
$loader = new \Twig\Loader\FilesystemLoader([
    '../theme/admin'   // Path for language.twig
]);
$twig = new \Twig\Environment($loader);
session_start();

// Check if the admin is logged in, if not, redirect to login page
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}
// Fetch all languages
$stmt = $pdo->query("SELECT * FROM languages ORDER BY id DESC");
$languages = $stmt->fetchAll(PDO::FETCH_ASSOC);



// Handle Add Language
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["language_name"], $_POST["language_code"])) {
    $language_name = trim($_POST["language_name"]);
    $language_code = trim($_POST["language_code"]);
    $language_type = $_POST["language_type"];
    $default_language = isset($_POST["default_language"]) ? '1' : '0';

    if (!empty($language_name) && !empty($language_code)) {
        $stmt = $pdo->prepare("INSERT INTO languages (language_name, language_code, language_type, default_language) VALUES (?, ?, ?, ?)");
        $stmt->execute([$language_name, $language_code, $language_type, $default_language]);
        header("Location: language.php");
        exit();
    }
}

// Handle Edit Language
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["edit_language_id"])) {
    $id = $_POST["edit_language_id"];
    $name = $_POST["edit_language_name"];
    $code = $_POST["edit_language_code"];
    $type = $_POST["edit_language_type"];
    $default = isset($_POST["edit_default_language"]) ? '1' : '0';

    $stmt = $pdo->prepare("UPDATE languages SET language_name = ?, language_code = ?, language_type = ?, default_language = ? WHERE id = ?");
    $stmt->execute([$name, $code, $type, $default, $id]);
    header("Location: language.php");
    exit();
}

// Handle Default Language Update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["default_id"])) {
    $id = $_POST["default_id"];

    // Reset all languages to not default
    $pdo->query("UPDATE languages SET default_language = '0'");

    // Set the selected language as default
    $stmt = $pdo->prepare("UPDATE languages SET default_language = '1' WHERE id = ?");
    $stmt->execute([$id]);
    exit();
}
// Get the default language
$defaultLanguage = $pdo->query("SELECT * FROM languages WHERE default_language = '1' LIMIT 1")->fetch(PDO::FETCH_ASSOC);
$direction = ($defaultLanguage && $defaultLanguage['language_type'] == '1') ? 'rtl' : 'ltr';

echo $twig->render('language.twig', [
    'languages' => $languages,
    'direction' => $direction
]);
?>
