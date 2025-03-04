<?php
require_once '../system/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect input values
    $type = trim($_POST['type']);
    $amount = trim($_POST['amount']);
    $due_date = trim($_POST['due_date']); // Collect due date

    // Input validation
    if (empty($type) || empty($amount) || empty($due_date)) {
        // Redirect back with an error message if any field is empty
        header('Location: manage_fees.php?error=All fields are required');
        exit;
    }

    if (!is_numeric($amount) || $amount <= 0) {
        // Redirect back with an error if amount is invalid
        header('Location: manage_fees.php?error=Amount must be a positive number');
        exit;
    }

    // Insert the fee into the database
    try {
        $stmt = $pdo->prepare("INSERT INTO fees (type, amount, due_date) VALUES (:type, :amount, :due_date)");
        $stmt->execute([
            'type' => $type,
            'amount' => $amount,
            'due_date' => $due_date,
        ]);

        // Redirect back with success message
        header('Location: manage_fees.php?success=Fee added successfully');
    } catch (PDOException $e) {
        // Redirect back with error message
        header('Location: manage_fees.php?error=Failed to add fee: ' . $e->getMessage());
    }
}
?>
