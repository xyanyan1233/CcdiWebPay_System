<?php
require_once '../system/config.php';

// Check if the ID is provided and that the request is a POST
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id = (int) $_POST['id']; // Cast to an integer for safety

    // Ensure the ID is valid
    if ($id > 0) {
        // Prepare the SQL statement to delete the fee
        $stmt = $pdo->prepare("DELETE FROM fees WHERE id = :id");
        $stmt->execute(['id' => $id]);

        // Redirect to manage fees page after deletion
        header('Location: manage_fees.php');
        exit; // Always exit after redirect
    } else {
        // Handle invalid ID (optional, depending on your needs)
        echo "Invalid fee ID.";
    }
} else {
    // Handle invalid request (GET or missing ID)
    echo "Invalid request.";
}
?>
