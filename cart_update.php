<?php
include 'includes/session.php';

$output = ['error' => false];
$id = $_POST['id'] ?? 0;
$qty = $_POST['qty'] ?? 1;

$conn = $pdo->open();
if (isset($_SESSION['user'])) {
    try {
        $stmt = $conn->prepare("UPDATE cart SET quantity=:quantity WHERE id=:id");
        $stmt->execute(['quantity' => $qty, 'id' => $id]);
        $output['message'] = 'Updated';
    } catch (PDOException $e) {
        $output['error'] = true;
        $output['message'] = $e->getMessage();
    }
} else {
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $key => $row) {
            if ($row['productid'] == $id) {
                $_SESSION['cart'][$key]['quantity'] = $qty;
                $output['message'] = 'Updated';
            }
        }
    }
}
$pdo->close();

header('Content-Type: application/json');
echo json_encode($output);
?>
