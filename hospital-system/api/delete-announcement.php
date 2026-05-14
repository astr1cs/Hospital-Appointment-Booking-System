<?php
require_once '../includes/init.php';

// Only admin can delete
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$id = $_POST['id'] ?? null;

header('Content-Type: application/json');

if (!$id) {
    echo json_encode(['success' => false, 'message' => 'Announcement ID required']);
    exit();
}

// Delete from database
$db = Database::getInstance()->getConnection();
$sql = "DELETE FROM announcements WHERE id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute() && $stmt->affected_rows > 0) {
    echo json_encode(['success' => true, 'message' => 'Announcement deleted successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to delete announcement']);
}
?>