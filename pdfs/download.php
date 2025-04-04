<?php
// Include configuration and necessary libraries
require_once '../system/config.php';
require_once '../vendor/autoload.php';

// Start the session
session_start();

// Check if the student is logged in
if (!isset($_SESSION['student_id'])) {
    header('Location: student_login.php');
    exit();
}

// Get the student ID from the session
$studentId = $_SESSION['student_id'];

try {
    // Fetch student details
    $stmtStudent = $pdo->prepare('SELECT * FROM students WHERE id = :id');
    $stmtStudent->execute(['id' => $studentId]);
    $student = $stmtStudent->fetch(PDO::FETCH_ASSOC);

    if (!$student) {
        die('Student not found.');
    }

    // Fetch payments for the student
    $stmtPayments = $pdo->prepare('SELECT * FROM payments WHERE student_id = :student_id');
    $stmtPayments->execute(['student_id' => $student['id']]);
    $payments = $stmtPayments->fetchAll(PDO::FETCH_ASSOC);

    // Create a PDF instance
    $pdf = new FPDF();
    $pdf->AddPage();

    // Header with background color
    $pdf->SetFillColor(0, 112, 192); // Blue header
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 15, 'My One Page Profile', 0, 1, 'C', true);
    $pdf->Ln(5);

    // Student details section
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(90, 10, 'Student Name:', 1, 0, 'L', true);
    $pdf->Cell(90, 10, $student['first_name'] . ' ' . $student['last_name'], 1, 1);
    $pdf->Cell(90, 10, 'Gender:', 1, 0, 'L', true);
    $pdf->Cell(90, 10, $student['gender'], 1, 1);
    $pdf->Cell(90, 10, 'School Year:', 1, 0, 'L', true);
    $pdf->Cell(90, 10, $student['school_year'], 1, 1);
    $pdf->Cell(90, 10, 'Semester/Summer:', 1, 0, 'L', true);
    $pdf->Cell(90, 10, $student['semester'], 1, 1);
    $pdf->Cell(90, 10, 'Registration Date:', 1, 0, 'L', true);
    $pdf->Cell(90, 10, date('F j, Y', strtotime($student['registration_date'])), 1, 1);
    $pdf->Ln(5);

    // Payment section header
    $pdf->SetFillColor(200, 200, 200);
    $pdf->Cell(0, 10, 'Payments:', 1, 1, 'L', true);
    
    // Payment details
    foreach ($payments as $payment) {
        $pdf->Cell(90, 10, $payment['category'], 1, 0, 'L');
        $pdf->Cell(45, 10, '₱' . number_format($payment['amount'], 2), 1, 0, 'C');
        $pdf->Cell(45, 10, $payment['status'], 1, 1, 'C');
    }

    // Save PDF file to the server
    $pdfDir = '../../pdfs/';
    if (!is_dir($pdfDir)) {
        mkdir($pdfDir, 0777, true);
    }
    $pdfPath = $pdfDir . 'student_' . $student['id'] . '.pdf';
    $pdf->Output('F', $pdfPath);

    // Send the PDF to the browser for download
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="student_' . $student['id'] . '.pdf"');
    readfile($pdfPath);
    exit();

} catch (Exception $e) {
    echo 'Error generating PDF: ' . $e->getMessage();
}
?>
