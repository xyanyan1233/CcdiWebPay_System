<?php
require_once '../system/config.php';

$success = false; // Variable to track success

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $password = hash('sha256', $_POST['password']); 
    $class = $_POST['class'];
    $tuitionFee = $_POST['tuition_fee'];
    $miscFee = $_POST['misc_fee'];
    $registrationFee = $_POST['registration_fee'];
    $labFee = $_POST['lab_fee'];
    $totalFee = $_POST['total_fee'];
    $status = 'Active'; // Default status
     $imageName = null;  // Initialize imageName as null

    // Image upload logic
    $targetDir = __DIR__ . "/images/studentsPic/"; // Define the target directory for images
    $imageName = basename($_FILES["student_picture"]["name"]); // Get the name of the uploaded image
    $targetFile = $targetDir . $imageName;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if the directory exists, if not create it
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true); // Creates the directory with appropriate permissions
    }

    // Check if the file is an actual image or a fake image
    $check = getimagesize($_FILES["student_picture"]["tmp_name"]);
    if ($check !== false) {
        // Check file size (limit set to 5MB)
        if ($_FILES["student_picture"]["size"] <= 5000000) {
            // Allow only certain file formats
            if (in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
                // Attempt to move the uploaded file
                if (move_uploaded_file($_FILES["student_picture"]["tmp_name"], $targetFile)) {
                    $imageName = $imageName; // Save the image name
                } else {
                    $imageName = null; // Set image name to null if upload fails
                }
            } else {
                $imageName = null;
            }
        } else {
            $imageName = null;
        }
    } else {
        $imageName = null;
    }

    // Insert student data into the database
    try {
        $sql = "INSERT INTO students (first_name, last_name, email, password, class, tuition_fee, misc_fee, registration_fee, lab_fee, total_fee, status, picture) 
                VALUES (:first_name, :last_name, :email, :password, :class, :tuition_fee, :misc_fee, :registration_fee, :lab_fee, :total_fee, :status, :picture)";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':first_name', $firstName);
        $stmt->bindParam(':last_name', $lastName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':class', $class);
        $stmt->bindParam(':tuition_fee', $tuitionFee);
        $stmt->bindParam(':misc_fee', $miscFee);
        $stmt->bindParam(':registration_fee', $registrationFee);
        $stmt->bindParam(':lab_fee', $labFee);
        $stmt->bindParam(':total_fee', $totalFee);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':picture', $imageName);

        $stmt->execute();

        $success = true; // Set success to true if student is added successfully
    } catch (PDOException $e) {
        $success = false; // Set success to false if there is an error
    }
    
    // Return JSON response
    echo json_encode(['success' => $success]);
    exit; // Ensure no further output is sent
}
?>
