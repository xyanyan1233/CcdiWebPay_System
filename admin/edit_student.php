<?php
// edit_student.php

require_once '../system/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $class = $_POST['class'];
    $password = isset($_POST['password']) ? hash('sha256', $_POST['password']) : null;

    
    $query = "UPDATE students SET first_name = :first_name, last_name = :last_name, email = :email, class = :class";
    if (!empty($password)) {
        $query .= ", password = :password";
    }
    $query .= " WHERE id = :student_id";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':first_name', $first_name);
    $stmt->bindParam(':last_name', $last_name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':class', $class);
    if (!empty($password)) {
        $stmt->bindParam(':password', $password);
    }
    $stmt->bindParam(':student_id', $student_id);

    if ($stmt->execute()) {
        header('Location: manage_students.php?message=Student+updated+successfully');
        exit;
    } else {
        echo "Error: Could not update student information.";
    }
}
?>
