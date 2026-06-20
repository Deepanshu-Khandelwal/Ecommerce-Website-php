<?php
include 'includes/session.php';

$action = $_GET['action'] ?? $_POST['action'] ?? '';
$conn = $pdo->open();

switch ($action) {
    case 'cart_add':
        $output = ['error' => false, 'message' => ''];
        $id = $_POST['id'] ?? $_GET['id'] ?? 0;
        $quantity = $_POST['quantity'] ?? $_GET['quantity'] ?? 1;
        $quantity = max(1, (int)$quantity);

        if ($id == 0) {
            $output['error'] = true;
            $output['message'] = 'Invalid product ID.';
        } else {
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
        break;

    case 'cart_delete':
        $output = ['error' => false];
        $id = $_POST['id'] ?? 0;

        if (isset($_SESSION['user'])) {
            try {
                $stmt = $conn->prepare("DELETE FROM cart WHERE id=:id");
                $stmt->execute(['id' => $id]);
                $output['message'] = 'Deleted';
            } catch (PDOException $e) {
                $output['error'] = true;
                $output['message'] = $e->getMessage();
            }
        } else {
            if (isset($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as $key => $row) {
                    if ($row['productid'] == $id) {
                        unset($_SESSION['cart'][$key]);
                        $output['message'] = 'Deleted';
                    }
                }
            }
        }
        header('Content-Type: application/json');
        echo json_encode($output);
        break;

    case 'cart_update':
        $output = ['error' => false];
        $id = $_POST['id'] ?? 0;
        $qty = $_POST['qty'] ?? 1;

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
        header('Content-Type: application/json');
        echo json_encode($output);
        break;

    case 'cart_fetch':
        $output = ['list' => '', 'count' => 0];

        if (isset($_SESSION['user'])) {
            try {
                $stmt = $conn->prepare("SELECT *, products.name AS prodname, category.name AS catname FROM cart LEFT JOIN products ON products.id=cart.product_id LEFT JOIN category ON category.id=products.category_id WHERE user_id=:user_id");
                $stmt->execute(['user_id' => $user['id']]);
                foreach ($stmt as $row) {
                    $output['count']++;
                    $image = (!empty($row['photo'])) ? 'images/' . $row['photo'] : 'images/noimage.jpg';
                    $productname = (strlen($row['prodname']) > 32) ? substr_replace($row['prodname'], '...', 29) : $row['prodname'];
                    $output['list'] .= "
                        <li class='p-2 border-bottom border-light-subtle'>
                            <a href='product.php?product=" . $row['slug'] . "' class='d-flex align-items-center gap-3 text-decoration-none'>
                                <img src='" . $image . "' class='rounded object-fit-cover' style='width: 44px; height: 44px; flex-shrink: 0; border: 1px solid var(--border-color);' alt='Product Image'>
                                <div class='flex-grow-1 min-width-0'>
                                    <h6 class='mb-0 text-dark text-truncate small' style='font-family: var(--font-sans); font-weight: 600;'>" . htmlspecialchars($productname) . "</h6>
                                    <small class='text-muted d-block' style='font-size: 11px;'>" . htmlspecialchars($row['catname']) . " <span class='text-warning mx-1'>&bull;</span> Qty: " . $row['quantity'] . "</small>
                                </div>
                            </a>
                        </li>
                    ";
                }
            } catch (PDOException $e) {
                $output['message'] = $e->getMessage();
            }
        } else {
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

            if (empty($_SESSION['cart'])) {
                $output['count'] = 0;
            } else {
                foreach ($_SESSION['cart'] as $row) {
                    $output['count']++;
                    $stmt = $conn->prepare("SELECT *, products.name AS prodname, category.name AS catname FROM products LEFT JOIN category ON category.id=products.category_id WHERE products.id=:id");
                    $stmt->execute(['id' => $row['productid']]);
                    $product = $stmt->fetch();
                    if ($product) {
                        $image = (!empty($product['photo'])) ? 'images/' . $product['photo'] : 'images/noimage.jpg';
                        $productname = (strlen($product['prodname']) > 32) ? substr_replace($product['prodname'], '...', 29) : $product['prodname'];
                        $output['list'] .= "
                            <li class='p-2 border-bottom border-light-subtle'>
                                <a href='product.php?product=" . $product['slug'] . "' class='d-flex align-items-center gap-3 text-decoration-none'>
                                    <img src='" . $image . "' class='rounded object-fit-cover' style='width: 44px; height: 44px; flex-shrink: 0; border: 1px solid var(--border-color);' alt='Product Image'>
                                    <div class='flex-grow-1 min-width-0'>
                                        <h6 class='mb-0 text-dark text-truncate small' style='font-family: var(--font-sans); font-weight: 600;'>" . htmlspecialchars($productname) . "</h6>
                                        <small class='text-muted d-block' style='font-size: 11px;'>" . htmlspecialchars($product['catname']) . " <span class='text-warning mx-1'>&bull;</span> Qty: " . $row['quantity'] . "</small>
                                    </div>
                                </a>
                            </li>
                        ";
                    }
                }
            }
        }
        header('Content-Type: application/json');
        echo json_encode($output);
        break;

    case 'cart_total':
        $total = 0.0;
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
        header('Content-Type: application/json');
        echo json_encode('&#8377; ' . number_format($total, 2));
        break;

    case 'cart_details':
        $output = '';
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
        header('Content-Type: application/json');
        echo json_encode($output);
        break;

    case 'transaction_details':
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $output = ['list' => '', 'transaction' => '', 'date' => ''];
        
        try {
            $stmt = $conn->prepare("
                SELECT d.price AS detail_price, p.name AS prod_name, p.slug AS prod_slug, d.quantity, s.pay_id, s.sales_date, s.id AS sales_id
                FROM details d 
                LEFT JOIN products p ON p.id = d.product_id 
                LEFT JOIN sales s ON s.id = d.sales_id 
                WHERE d.sales_id = :id
            ");
            $stmt->execute(['id' => $id]);

            $total = 0;
            foreach ($stmt as $row) {
                $output['transaction'] = $row['pay_id'] ?: 'Order #' . $row['sales_id'];
                $output['date'] = $row['sales_date'] ? date('M d, Y H:i', strtotime($row['sales_date'])) : '-';
                
                $price = (float)$row['detail_price'];
                $qty = (int)$row['quantity'];
                $subtotal = $price * $qty;
                $total += $subtotal;
                
                $prodName = $row['prod_name'] ?? 'Deleted Product';
                $prodSlug = $row['prod_slug'] ?? '';
                
                $prodDisplay = !empty($prodSlug) 
                    ? "<a href='product.php?product=" . htmlspecialchars($prodSlug, ENT_QUOTES, 'UTF-8') . "' target='_blank'>" . htmlspecialchars($prodName, ENT_QUOTES, 'UTF-8') . "</a>" 
                    : htmlspecialchars($prodName, ENT_QUOTES, 'UTF-8');
                
                $output['list'] .= "
                    <tr class='prepend_items'>
                        <td>" . $prodDisplay . "</td>
                        <td>&#8377; " . number_format($price, 2) . "</td>
                        <td>" . htmlspecialchars($qty, ENT_QUOTES, 'UTF-8') . "</td>
                        <td>&#8377; " . number_format($subtotal, 2) . "</td>
                    </tr>
                ";
            }
            $output['total'] = '<b>&#8377; ' . number_format($total, 2) . '</b>';
        } catch (PDOException $e) {
            $output['list'] = "<tr><td colspan='4' class='text-danger'>Error: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</td></tr>";
        }
        header('Content-Type: application/json');
        echo json_encode($output);
        break;

    default:
        http_response_code(400);
        echo json_encode(['error' => true, 'message' => 'Invalid action']);
        break;
}

$pdo->close();
?>
