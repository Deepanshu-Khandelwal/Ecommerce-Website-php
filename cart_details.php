<?php
include 'includes/session.php';

$output = '';
$conn = $pdo->open();
if (isset($_SESSION['user'])) {
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $row) {
            $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id=:user_id AND product_id=:product_id");
            $stmt->execute(['user_id' => $user['id'], 'product_id' => $row['productid']]);
            $crow = $stmt->fetch();
            if ($crow === false) {
                $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (:user_id, :product_id, :quantity)");
                $stmt->execute(['user_id' => $user['id'], 'product_id' => $row['productid'], 'quantity' => $row['quantity']]);
            } else {
                $stmt = $conn->prepare("UPDATE cart SET quantity=:quantity WHERE user_id=:user_id AND product_id=:product_id");
                $stmt->execute(['quantity' => $row['quantity'], 'user_id' => $user['id'], 'product_id' => $row['productid']]);
            }
        }
        unset($_SESSION['cart']);
    }

    try {
        $total = 0;
        $stmt = $conn->prepare("SELECT *, cart.id AS cartid FROM cart LEFT JOIN products ON products.id=cart.product_id WHERE user_id=:user");
        $stmt->execute(['user' => $user['id']]);
        foreach ($stmt as $row) {
            $image = (!empty($row['photo'])) ? 'images/' . $row['photo'] : 'images/noimage.jpg';
            $subtotal = $row['price'] * $row['quantity'];
            $total += $subtotal;
            $output .= "
                <tr>
                    <td>
                        <button type='button' data-id='" . $row['cartid'] . "' class='btn btn-outline-danger btn-sm border-0 cart_delete' style='padding: 6px 10px; border-radius: var(--radius-sm);'>
                            <i class='fa-regular fa-trash-can fs-6'></i>
                        </button>
                    </td>
                    <td><img src='" . $image . "' width='50px' height='50px' class='rounded object-fit-cover border' alt='Product Thumbnail'></td>
                    <td class='fw-bold text-dark' style='font-family: var(--font-sans);'>" . htmlspecialchars($row['name']) . "</td>
                    <td style='color: var(--text-color); font-weight: 500;'>&#8377; " . number_format($row['price'], 2) . "</td> 
                    <td>
                        <div class='input-group input-group-sm' style='width: 105px;'>
                            <button type='button' class='btn btn-outline-secondary minus' data-id='" . $row['cartid'] . "' style='border: 1px solid var(--border-color);'><i class='fa fa-minus'></i></button>
                            <input type='text' class='form-control text-center bg-white border-top border-bottom border-0' value='" . $row['quantity'] . "' id='qty_" . $row['cartid'] . "' readonly style='width: 35px; box-shadow: none;'>
                            <button type='button' class='btn btn-outline-secondary add' data-id='" . $row['cartid'] . "' style='border: 1px solid var(--border-color);'><i class='fa fa-plus'></i></button>
                        </div>
                    </td>
                    <td class='fw-bold' style='color: var(--primary-color);'>&#8377; " . number_format($subtotal, 2) . "</td>
                </tr>
            ";
        }
        if ($total > 0) {
            $output .= "
                <tr>
                    <td colspan='5' class='text-end fw-bold py-3'>Subtotal</td>
                    <td class='fw-bold py-3' style='color: var(--primary-color); font-size: 16px;'>&#8377; " . number_format($total, 2) . "</td>
                </tr>
            ";
        } else {
            $output .= "
                <tr>
                    <td colspan='6' class='text-center py-4 text-muted'>Your shopping cart is empty</td>
                </tr>
            ";
        }
    } catch (PDOException $e) {
        $output .= "<tr><td colspan='6' class='text-danger'>" . $e->getMessage() . "</td></tr>";
    }
} else {
    if (isset($_SESSION['cart']) && count($_SESSION['cart']) != 0) {
        $total = 0;
        foreach ($_SESSION['cart'] as $row) {
            $stmt = $conn->prepare("SELECT *, products.name AS prodname, category.name AS catname FROM products LEFT JOIN category ON category.id=products.category_id WHERE products.id=:id");
            $stmt->execute(['id' => $row['productid']]);
            $product = $stmt->fetch();
            if ($product) {
                $image = (!empty($product['photo'])) ? 'images/' . $product['photo'] : 'images/noimage.jpg';
                $subtotal = $product['price'] * $row['quantity'];
                $total += $subtotal;
                $output .= "
                    <tr>
                        <td>
                            <button type='button' data-id='" . $row['productid'] . "' class='btn btn-outline-danger btn-sm border-0 cart_delete' style='padding: 6px 10px; border-radius: var(--radius-sm);'>
                                <i class='fa-regular fa-trash-can fs-6'></i>
                            </button>
                        </td>
                        <td><img src='" . $image . "' width='50px' height='50px' class='rounded object-fit-cover border' alt='Product Thumbnail'></td>
                        <td class='fw-bold text-dark' style='font-family: var(--font-sans);'>" . htmlspecialchars($product['name']) . "</td>
                        <td style='color: var(--text-color); font-weight: 500;'>&#8377; " . number_format($product['price'], 2) . "</td> 
                        <td>
                            <div class='input-group input-group-sm' style='width: 105px;'>
                                <button type='button' class='btn btn-outline-secondary minus' data-id='" . $row['productid'] . "' style='border: 1px solid var(--border-color);'><i class='fa fa-minus'></i></button>
                                <input type='text' class='form-control text-center bg-white border-top border-bottom border-0' value='" . $row['quantity'] . "' id='qty_" . $row['productid'] . "' readonly style='width: 35px; box-shadow: none;'>
                                <button type='button' class='btn btn-outline-secondary add' data-id='" . $row['productid'] . "' style='border: 1px solid var(--border-color);'><i class='fa fa-plus'></i></button>
                            </div>
                        </td>
                        <td class='fw-bold' style='color: var(--primary-color);'>&#8377; " . number_format($subtotal, 2) . "</td>
                    </tr>
                ";
            }
        }
        $output .= "
            <tr>
                <td colspan='5' class='text-end fw-bold py-3'>Subtotal</td>
                <td class='fw-bold py-3' style='color: var(--primary-color); font-size: 16px;'>&#8377; " . number_format($total, 2) . "</td>
            </tr>
        ";
    } else {
        $output .= "
            <tr>
                <td colspan='6' class='text-center py-4 text-muted'>Your shopping cart is empty</td>
            </tr>
        ";
    }
}
$pdo->close();

header('Content-Type: application/json');
echo json_encode($output);
?>
