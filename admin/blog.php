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

if (isset($_POST['publish'])) {
    $blogName = $_POST['blog_name'];
    $blogContent = $_POST['blog_content'];
    $releaseDate = date('Y-m-d');

    // Read the uploaded image file
    $imageData = file_get_contents($_FILES['blog_picture']['tmp_name']);

    // Insert into database
    $stmt = $pdo->prepare("INSERT INTO blogs (name, content, image, release_date) VALUES (?, ?, ?, ?)");
    $stmt->bindParam(1, $blogName);
    $stmt->bindParam(2, $blogContent);
    $stmt->bindParam(3, $imageData, PDO::PARAM_LOB);
    $stmt->bindParam(4, $releaseDate);
    $stmt->execute();

    header("Location: blog.php"); // Redirect after adding
    exit;
}

if (isset($_POST['delete'])) {
    $blogId = $_POST['blog_id'];

    // Delete blog from the database
    $stmt = $pdo->prepare("DELETE FROM blogs WHERE id = ?");
    $stmt->execute([$blogId]);

    header("Location: blog.php"); // Refresh page after deletion
    exit;
}

$stmt = $pdo->query("SELECT id, name, content, release_date, 
                    TO_BASE64(image) AS image_base64 FROM blogs ORDER BY release_date DESC");
$blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo $twig->render('blog.twig', ['blogs' => $blogs]);

?>
