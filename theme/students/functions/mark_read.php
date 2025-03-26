<?php
require_once '../../system/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $messageId = $_POST['id'];

    try {
        $stmt = $pdo->prepare("UPDATE inbox SET is_read = 1 WHERE id = :id");
        $stmt->execute(['id' => $messageId]);

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
}
?>
