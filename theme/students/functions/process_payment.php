<?php
// Include configuration file
require_once '../../../system/config.php';
require_once '../../../vendor/autoload.php';

// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the student is logged in
if (!isset($_SESSION['student_id'])) {
    header('Location: student_login.php');
    exit();
}
// Initialize Twig
$loader = new \Twig\Loader\FilesystemLoader('../../../theme/view/ccditheme'); // Adjust the path if needed
$twig = new \Twig\Environment($loader);
// Error message variable
$error_message = null;

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Retrieve student ID
        $student_id = $_SESSION['student_id'];

        // Validate category
        if (empty($_POST['category'])) {
            throw new Exception('Please select a payment category.');
        }
        $category = trim($_POST['category']);

        // Fetch the fee details
        $stmt = $pdo->prepare('SELECT * FROM fees WHERE type = :category AND status = :status LIMIT 1');
        $stmt->execute([
            'category' => $category,
            'status'   => 'Pending'
        ]);
        $fee = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$fee) {
            throw new Exception('Invalid payment category or already paid.');
        }

        $amount   = $fee['amount'];
        $due_date = $fee['due_date'];

        // Generate unique payment ID
        $payment_id = 'PAY-' . strtoupper(uniqid());

        // Start transaction
        $pdo->beginTransaction();

        // Insert payment record
        $stmt = $pdo->prepare('INSERT INTO payments (student_id, payment_id, category, amount, status, due_date, created_at)
                               VALUES (:student_id, :payment_id, :category, :amount, :status, :due_date, NOW())');
        $stmt->execute([
            'student_id' => $student_id,
            'payment_id' => $payment_id,
            'category'   => $category,
            'amount'     => $amount,
            'status'     => 'Completed',
            'due_date'   => $due_date
        ]);

        // Update fee status to "Paid"
        $stmt = $pdo->prepare('UPDATE fees SET status = :status WHERE id = :id');
        $stmt->execute([
            'status' => 'Paid',
            'id'     => $fee['id']
        ]);

        // Commit transaction
        $pdo->commit();

        // Redirect to success page
        header('Location: payment_success.php?payment_id=' . urlencode($payment_id));
        exit();
    } catch (Exception $e) {
        // Rollback only if the transaction is active
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        $error_message = $e->getMessage();
    }
}

// Render the error page only if there's an error
if ($error_message) {
    echo $twig->render('payment_error.twig', ['error_message' => $error_message]);
}
