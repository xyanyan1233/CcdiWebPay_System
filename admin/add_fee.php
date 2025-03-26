<?php
require_once '../system/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect input values
    $type = trim($_POST['type']);
    $amount = trim($_POST['amount']);
    $due_date = trim($_POST['due_date']); 

    // Input validation
    if (empty($type) || empty($amount) || empty($due_date)) {
        header('Location: manage_fees.php?error=All fields are required');
        exit;
    }

    if (!is_numeric($amount) || $amount <= 0) {
        header('Location: manage_fees.php?error=Amount must be a positive number');
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO fees (type, amount, due_date) VALUES (:type, :amount, :due_date)");
        $stmt->execute([
            'type' => $type,
            'amount' => $amount,
            'due_date' => $due_date,
        ]);

        // 📩 Send Inbox Notification to Students
        $stmtStudents = $pdo->query("SELECT id FROM students");
        $students = $stmtStudents->fetchAll(PDO::FETCH_ASSOC);

        $stmtInbox = $pdo->prepare("INSERT INTO inbox (student_id, sender, message, status, is_read, timestamp) VALUES (?, ?, ?, ?, ?, ?)");

        foreach ($students as $student) {
            $student_id = $student['id'];
            $message = "New Fee Added: $type - ₱$amount (Due: $due_date)";
            $stmtInbox->execute([$student_id, 'Admin', $message, 'unread', 0, date('Y-m-d H:i:s')]);
        }

        header('Location: manage_fees.php?success=Fee added successfully and notifications sent');
    } catch (PDOException $e) {
        header('Location: manage_fees.php?error=Failed to add fee: ' . $e->getMessage());
    }
}
?>
