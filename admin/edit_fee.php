<?php
require_once '../system/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Fetch data from the POST request
    $id = $_POST['id'];
    $type = $_POST['type'];
    $amount = $_POST['amount'];
    $due_date = $_POST['due_date'];
    $status = $_POST['status'];

    try {
        // Update query to include due_date and status
        $stmt = $pdo->prepare("
            UPDATE fees 
            SET type = :type, 
                amount = :amount, 
                due_date = :due_date, 
                status = :status 
            WHERE id = :id
        ");
        $stmt->execute([
            'type' => $type,
            'amount' => $amount,
            'due_date' => $due_date,
            'status' => $status,
            'id' => $id
        ]);

        // Redirect back to manage fees page
        header('Location: manage_fees.php');
        exit;
    } catch (PDOException $e) {
        // Handle database errors
        die("Error updating fee: " . $e->getMessage());
    }
} else {
    // Invalid request method
    header('Location: manage_fees.php');
    exit;
}
?>
