<?php
include 'includes/session.php';

$output = ['list' => '', 'count' => 0];

$conn = $pdo->open();
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
$pdo->close();

header('Content-Type: application/json');
echo json_encode($output);
?>
