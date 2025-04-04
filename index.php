<?php
// Start the session
session_start();

// Define base directory dynamically
$basePath = __DIR__; // Gets the absolute path of index.php

// Get the requested URL path
$request_uri = trim($_SERVER['REQUEST_URI'], '/');

// Define redirections
if ($request_uri === '' || $request_uri === 'index.php') {
    // Redirect to the default student dashboard
    header("Location: /dashboard");
    exit();
} elseif ($request_uri === 'dashboard') {
    // Redirect to student login page
    header("Location: /theme/students/new_payment.php");
    exit();
} elseif ($request_uri === 'administrator') {
    // Redirect to admin login page
    header("Location: /admin/login.php");
    exit();
} else {
    // Redirect to custom 404 page
    header("Location: /404.php");
    exit();
}
