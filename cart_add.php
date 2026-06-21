<?php
include 'includes/session.php';

$output = ['error' => false, 'message' => ''];
$id = $_POST['id'] ?? $_GET['id'] ?? 0;
$quantity = $_POST['quantity'] ?? $_GET['quantity'] ?? 1;
$quantity = max(1, (int)$quantity);

if ($id == 0) {
    $output['error'] = true;
    $output['message'] = 'Invalid product ID.';
} else {
    $conn = $pdo->open();
    if (isset($_SESSION['user'])) {
        try {
            $stmt = $conn->prepare("SELECT quantity FROM cart WHERE user_id=:user_id AND product_id=:product_id");
            $stmt->execute(['user_id' => $user['id'], 'product_id' => $id]);
            $row = $stmt->fetch();

            if ($row) {
                $newQty = $row['quantity'] + $quantity;
                $stmt = $conn->prepare("UPDATE cart SET quantity=:quantity WHERE user_id=:user_id AND product_id=:product_id");
                $stmt->execute(['quantity' => $newQty, 'user_id' => $user['id'], 'product_id' => $id]);
                $output['message'] = "Cart updated. Quantity: $newQty";
            } else {
                $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (:user_id, :product_id, :quantity)");
                $stmt->execute(['user_id' => $user['id'], 'product_id' => $id, 'quantity' => $quantity]);
                $output['message'] = "Item added to cart. Quantity: $quantity";
            }
        } catch (PDOException $e) {
            $output['error'] = true;
            $output['message'] = 'Database error: ' . $e->getMessage();
        }
    } else {
        if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
        $exist = array_column($_SESSION['cart'], 'productid');

        if (in_array($id, $exist)) {
            foreach ($_SESSION['cart'] as &$item) {
                if ($item['productid'] == $id) {
                    $item['quantity'] += $quantity;
                    $output['message'] = "Cart updated. Quantity: {$item['quantity']}";
                    break;
                }
            }
            unset($item);
        } else {
            $_SESSION['cart'][] = ['productid' => $id, 'quantity' => $quantity];
            $output['message'] = "Item added to cart. Quantity: $quantity";
        }
    }
    $pdo->close();
}

$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
if ($isAjax) {
    header('Content-Type: application/json');
    echo json_encode($output);
} else {
    if ($output['error']) $_SESSION['error'] = $output['message'];
    else $_SESSION['success'] = $output['message'];
    header('Location: cart_view.php');
}
?>
