<?php
require_once '../system/config.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_name = $_POST['course_name'];
    $description = $_POST['description'];
    $card_color = $_POST['card_color'];

    // Handle image upload
    if (isset($_FILES['course_image']) && $_FILES['course_image']['error'] == 0) {
        $image_dir = '/images/courseImages/';
        $image_name = basename($_FILES['course_image']['name']);
        $image_path = $image_dir . $image_name;

        // Move the uploaded image to the directory
        move_uploaded_file($_FILES['course_image']['tmp_name'], $image_path);
    } else {
        $image_path = null;
    }

    // Insert the new course into the database
    $query = "INSERT INTO courses (name, description, card_color, image) VALUES (:course_name, :description, :card_color, :image)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':course_name' => $course_name,
        ':description' => $description,
        ':card_color' => $card_color,
        ':image' => $image_path
    ]);

    header('Location: manage_course.php');
    exit;
}
?>
