<?php
// Include your config file for database connection
require_once '../system/config.php';

// Check if the 'id' and 'type' parameters are passed in the URL
if (isset($_GET['id']) && isset($_GET['type'])) {
    $id = $_GET['id'];
    $type = $_GET['type'];

    // Check if the current status is "Active" or "Inactive"
    if ($type == 'Active') {
        // If it is "Active", change the status to "Inactive"
        $newStatus = 'Inactive';
    } else {
        // If it is "Inactive", change the status to "Active"
        $newStatus = 'Active';
    }

    // Prepare the SQL query to update the status of the fee
    $stmt = $pdo->prepare("UPDATE fees SET status = :status WHERE id = :id");
    $stmt->execute(['status' => $newStatus, 'id' => $id]);

    // Redirect back to the manage fees page with a success message
    header('Location: manage_fees.php?status=success');
    exit();
} else {
    // If no valid ID or type is provided, redirect back to manage fees with an error message
    header('Location: manage_fees.php?status=error');
    exit();
}
