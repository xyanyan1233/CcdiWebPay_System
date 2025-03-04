<?php
// Include configuration file
require_once '../../system/config.php';
require_once '../../vendor/autoload.php';

// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['student_id'])) {
    // Redirect to login page
    header('Location: student_login.php');
    exit();
}

// Set up Twig environment
$loader = new \Twig\Loader\FilesystemLoader('../../theme/view/ccditheme');
$twig = new \Twig\Environment($loader);

// Fetch all fees
try {
    // Fetch fees from the fees table
    $stmtFees = $pdo->query('SELECT * FROM fees WHERE status = "Active"');
    $fees = $stmtFees->fetchAll(PDO::FETCH_ASSOC);

    // Fetch student details
    $stmtStudent = $pdo->prepare('SELECT * FROM students WHERE id = :id');
    $stmtStudent->execute(['id' => $_SESSION['student_id']]);
    $student = $stmtStudent->fetch(PDO::FETCH_ASSOC);

    // Redirect if student not found
    if (!$student) {
        header('Location: student_login.php');
        exit();
    }

    // Synchronize fees with payments for the student
    foreach ($fees as $fee) {
        $stmtCheck = $pdo->prepare(
            'SELECT COUNT(*) FROM payments WHERE student_id = :student_id AND category = :category'
        );
        $stmtCheck->execute([
            'student_id' => $student['id'],
            'category' => $fee['type']
        ]);

        $exists = $stmtCheck->fetchColumn();

        // If payment doesn't exist, create it
        if (!$exists) {
            $stmtInsert = $pdo->prepare(
                'INSERT INTO payments (student_id, payment_id, category, amount, status, due_date, created_at, updated_at)
                 VALUES (:student_id, :payment_id, :category, :amount, "Pending", :due_date, NOW(), NOW())'
            );
            $stmtInsert->execute([
                'student_id' => $student['id'],
                'payment_id' => uniqid('pay_'),
                'category' => $fee['type'],
                'amount' => $fee['amount'],
                'due_date' => $fee['due_date']
            ]);
        }
    }
    // Deleting a fee and its related payments
    if (isset($_POST['delete_fee'])) {
        $feeId = $_POST['fee_id'];

        try {
            // Start a transaction
            $pdo->beginTransaction();

            // Fetch the fee type before deletion
            $stmtFee = $pdo->prepare('SELECT type FROM fees WHERE id = :id');
            $stmtFee->execute(['id' => $feeId]);
            $fee = $stmtFee->fetch(PDO::FETCH_ASSOC);

            if ($fee) {
                // Delete related payments
                $stmtDeletePayments = $pdo->prepare('DELETE FROM payments WHERE category = :category');
                $stmtDeletePayments->execute(['category' => $fee['type']]);

                // Delete the fee
                $stmtDeleteFee = $pdo->prepare('DELETE FROM fees WHERE id = :id');
                $stmtDeleteFee->execute(['id' => $feeId]);

                // Commit the transaction
                $pdo->commit();
                echo 'Fee and related payments deleted successfully.';
            } else {
                echo 'Fee not found.';
            }
        } catch (PDOException $e) {
            // Rollback transaction on error
            $pdo->rollBack();
            echo 'Error deleting fee: ' . $e->getMessage();
        }
    }


    // Apply filters and search
    $filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';

    // Fetch payments for the student
    $sql = 'SELECT * FROM payments WHERE student_id = :student_id';

    if ($filter !== 'all') {
        $sql .= ' AND status = :filter';
    }
    if (!empty($search)) {
        $sql .= ' AND (payment_id LIKE :search OR category LIKE :search)';
    }

    $stmtPayments = $pdo->prepare($sql);
    $stmtPayments->bindValue(':student_id', $student['id']);
    if ($filter !== 'all') {
        $stmtPayments->bindValue(':filter', ucfirst($filter));
    }
    if (!empty($search)) {
        $stmtPayments->bindValue(':search', '%' . $search . '%');
    }
    $stmtPayments->execute();

    $payments = $stmtPayments->fetchAll(PDO::FETCH_ASSOC);

    // Render the Twig template
    echo $twig->render('payments.twig', [
        'student' => $student,
        'payments' => $payments,
        'current_filter' => $filter,
        'current_search' => $search
    ]);
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}
