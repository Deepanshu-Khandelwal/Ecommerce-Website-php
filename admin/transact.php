<?php
include 'includes/session.php';
header('Content-Type: application/json');
function h($v){ return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }

try {
  if ($_SERVER['REQUEST_METHOD'] !== 'POST') throw new Exception('Invalid request');
  $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
  if ($id <= 0) throw new Exception('Missing or invalid order id');

  $conn = $pdo->open();

  // Fetch sale order details
  $q = $conn->prepare("SELECT sales_date, pay_id, status, amount, id FROM sales WHERE id=:id");
  $q->execute(['id' => $id]);
  $sale = $q->fetch();

  if (!$sale) {
    echo json_encode(['date' => '-', 'transaction' => '-', 'list' => '<div class="alert alert-warning">Order not found.</div>', 'total' => '₹ 0.00', 'status' => '']);
    $pdo->close();
    exit;
  }

  $dateStr = $sale['sales_date'] ? date('M d, Y H:i', strtotime($sale['sales_date'])) : '-';
  $payId   = $sale['pay_id'] ?: 'Order #' . $sale['id'];
  $status  = strtolower($sale['status'] ?? 'pending');

  // Items using snapshot pricing (d.price instead of p.price)
  $stmt = $conn->prepare("
    SELECT p.name AS product_name, d.price AS unit_price, d.quantity
    FROM details d
    LEFT JOIN products p ON p.id = d.product_id
    WHERE d.sales_id = :id
    ORDER BY d.id ASC
  ");
  $stmt->execute(['id' => $id]);

  $total = 0.0;
  $rows = '';
  foreach ($stmt as $row) {
    $name = $row['product_name'] ?? 'Deleted Product';
    $price = (float)$row['unit_price'];
    $qty = (int)$row['quantity'];
    $sub = $price * $qty;
    $total += $sub;
    $rows .= '<tr><td>' . h($name) . '</td><td>₹ ' . number_format($price, 2) . '</td><td>' . h($qty) . '</td><td>₹ ' . number_format($sub, 2) . '</td></tr>';
  }

  $list = $rows
    ? '<div class="table-responsive"><table class="table table-condensed table-striped mb-0"><thead><tr><th>Item</th><th>Price</th><th>Qty</th><th>Subtotal</th></tr></thead><tbody>' . $rows . '</tbody></table></div>'
    : '<div class="alert alert-info mb-0">No items found for this order.</div>';

  echo json_encode([
    'date'        => h($dateStr),
    'transaction' => h($payId),
    'list'        => $list,
    'total'       => '₹ ' . number_format($total, 2),
    'status'      => $status
  ]);
  
  $pdo->close();

} catch (Throwable $e) {
  http_response_code(400);
  echo json_encode(['date' => '-', 'transaction' => '-', 'list' => '<div class="alert alert-danger mb-0">Error: ' . h($e->getMessage()) . '</div>', 'total' => '₹ 0.00', 'status' => '']);
}
?>
