<?php
require_once '../system/config.php';

$data = json_decode(file_get_contents('php://input'), true);
if (!empty($data['ids'])) {
    $ids = implode(',', array_map('intval', $data['ids']));
    $query = "DELETE FROM courses WHERE id IN ($ids)";
    $pdo->exec($query);
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'No courses selected']);
}
?>
