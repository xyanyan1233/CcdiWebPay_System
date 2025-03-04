<?php
// delete_student.php

require_once '../system/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['student_id'])) {
    $student_id = $_POST['student_id'];

    // Delete the student record
    $stmt = $pdo->prepare("DELETE FROM students WHERE id = :id");
    $stmt->bindParam(':id', $student_id);
    if ($stmt->execute()) {
        header('Location: manage_students.php?message=Student+deleted+successfully');
        exit;
    } else {
        echo "Error: Could not delete student.";
    }
}

?>
