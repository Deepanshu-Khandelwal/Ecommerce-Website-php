<?php
include 'includes/session.php';
header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method.');
    }
    
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $status = isset($_POST['status']) ? trim($_POST['status']) : '';
    
    if ($id <= 0) {
        throw new Exception('Invalid order ID.');
    }
    
    $allowed = ['pending', 'paid', 'shipped', 'cancelled'];
    if (!in_array($status, $allowed)) {
        throw new Exception('Invalid status value.');
    }
    
    $conn = $pdo->open();
    
    // Check if order exists
    $stmt = $conn->prepare("SELECT id FROM sales WHERE id = :id");
    $stmt->execute(['id' => $id]);
    if (!$stmt->fetch()) {
        throw new Exception('Order not found.');
    }
    
    // Update status
    $stmt = $conn->prepare("UPDATE sales SET status = :status, updated_at = NOW() WHERE id = :id");
    $stmt->execute(['status' => $status, 'id' => $id]);
    
    $pdo->close();
    echo json_encode(['ok' => true, 'message' => 'Order status updated successfully.']);

} catch (Throwable $e) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
}
?>
