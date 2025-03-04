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

// Fetch all themes
try {
    $stmt = $pdo->query("SELECT id, theme_name, theme_image FROM themes");
    $themes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

// Handle theme activation
if (isset($_POST['activateTheme'])) {
    $themeId = $_POST['themeId'];
    $stmt = $pdo->prepare("UPDATE settings SET active_theme = ? WHERE id = 1");
    $stmt->execute([$themeId]);
    header("Location: theme.php");
    exit;
}

// Handle theme deletion
if (isset($_POST['deleteTheme'])) {
    $themeId = $_POST['themeId'];
    $stmt = $pdo->prepare("DELETE FROM themes WHERE id = ?");
    $stmt->execute([$themeId]);
    header("Location: theme.php");
    exit;
}

// Ensure theme_image is a valid file path
foreach ($themes as &$theme) {
    if (empty($theme['theme_image'])) {
        $theme['theme_image'] = 'theme/view/ccditheme/CCDITHEME.png'; // Default image
    }
}

// Render the theme management page
echo $twig->render('theme.twig', ['themes' => $themes]);
?>
