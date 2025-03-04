<?php
require_once '../system/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['course_id'])) {
    // Get the form data
    $course_id = $_POST['course_id'];
    $course_name = $_POST['course_name'];
    $description = $_POST['description'];
    $color = $_POST['color'];  // For the card color
    $image = null;

    // Handle image upload (optional)
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = 'uploads/' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/' . basename($_FILES['image']['name']));
    }

    // Update the course in the database
    $stmt = $pdo->prepare("UPDATE courses SET name = ?, description = ?, color = ?, image = ? WHERE id = ?");
    $stmt->execute([$course_name, $description, $color, $image, $course_id]);

    // Redirect back to the manage courses page
    header('Location: manage_courses.php');
    exit;
}
?>
