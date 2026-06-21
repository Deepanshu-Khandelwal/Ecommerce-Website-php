<?php
include 'includes/session.php';

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$output = ['list' => '', 'transaction' => '', 'date' => ''];

$conn = $pdo->open();
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
$pdo->close();

header('Content-Type: application/json');
echo json_encode($output);
?>
