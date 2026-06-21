<?php
include 'includes/session.php';

$total = 0.0;
$conn = $pdo->open();
if (isset($_SESSION['user'])) {
    try {
        $stmt = $conn->prepare("SELECT p.price, c.quantity FROM cart c JOIN products p ON p.id = c.product_id WHERE c.user_id = :user_id");
        $stmt->execute(['user_id' => $user['id']]);
        foreach ($stmt as $row) {
            $total += (float)$row['price'] * (int)$row['quantity'];
        }
    } catch (PDOException $e) {
        // Log or handle error
    }
} else {
    if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $row) {
            try {
                $stmt = $conn->prepare("SELECT price FROM products WHERE id = :id");
                $stmt->execute(['id' => $row['productid']]);
                if ($p = $stmt->fetch()) {
                    $total += (float)$p['price'] * (int)$row['quantity'];
                }
            } catch (PDOException $e) {
                // Log or handle error
            }
        }
    }
}
$pdo->close();

header('Content-Type: application/json');
echo json_encode('&#8377; ' . number_format($total, 2));
?>
