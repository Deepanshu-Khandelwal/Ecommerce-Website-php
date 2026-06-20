<?php
include 'includes/session.php';

// Handle Razorpay Actions
$action = $_GET['action'] ?? $_POST['action'] ?? '';

if ($action === 'create_razorpay_order') {
    include 'config/razorpay.php';
    header('Content-Type: application/json');

    try {
        $conn = $pdo->open();

        // cache checkout info for verify step
        $_SESSION['checkout'] = [
          'name'    => trim($_POST['name']  ?? ''),
          'email'   => trim($_POST['email'] ?? ''),
          'phone'   => trim($_POST['phone'] ?? ''),
          'address' => trim($_POST['address'] ?? ''),
          'method'  => 'razorpay'
        ];

        // recompute total from DB/session
        $total = 0.0;
        if (isset($_SESSION['user'])) {
            $stmt = $conn->prepare("SELECT p.price, c.quantity
                                    FROM cart c JOIN products p ON p.id=c.product_id
                                    WHERE c.user_id=:uid");
            $stmt->execute(['uid'=>$user['id']]);
            foreach ($stmt as $row) $total += (float)$row['price'] * (int)$row['quantity'];
        } else {
            if (!empty($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as $row) {
                    $s = $conn->prepare("SELECT price FROM products WHERE id=:id");
                    $s->execute(['id'=>$row['productid']]);
                    if ($p=$s->fetch()) $total += (float)$p['price'] * (int)$row['quantity'];
                }
            }
        }

        if ($total <= 0) throw new Exception('Cart is empty');

        $amountPaise = (int)round($total * 100);
        if ($amountPaise < 100) throw new Exception('Amount must be at least ₹1.00');

        $api = razorpayApi();
        $order = $api->order->create([
            'amount'          => $amountPaise,
            'currency'        => 'INR',
            'receipt'         => 'rcpt_'.time(),
            'payment_capture' => 1,
            'notes'           => ['user_id' => isset($user['id'])?$user['id']:'guest']
        ]);

        echo json_encode([
            'ok'       => true,
            'order_id' => $order['id'],
            'amount'   => $order['amount'],
            'currency' => $order['currency'],
            'key_id'   => RAZORPAY_KEY_ID,
            'prefill'  => [
              'name'    => $_SESSION['checkout']['name'],
              'email'   => $_SESSION['checkout']['email'],
              'contact' => $_SESSION['checkout']['phone']
            ]
        ]);
    } catch (Throwable $e) {
        http_response_code(400);
        echo json_encode(['ok'=>false,'error'=>$e->getMessage()]);
    }
    $pdo->close();
    exit;
}

if ($action === 'verify_razorpay_payment') {
    include 'config/razorpay.php';
    header('Content-Type: application/json');

    $paymentId = $_POST['razorpay_payment_id'] ?? '';
    $orderId   = $_POST['razorpay_order_id'] ?? '';
    $signature = $_POST['razorpay_signature'] ?? '';

    if ($paymentId === '' || $orderId === '' || $signature === '') {
      http_response_code(400);
      echo json_encode(['ok' => false, 'error' => 'Missing signature parameters']);
      exit;
    }

    try {
      // 1. Verify Razorpay Payment Signature
      $api = razorpayApi();
      $api->utility->verifyPaymentSignature([
        'razorpay_order_id'   => $orderId,
        'razorpay_payment_id' => $paymentId,
        'razorpay_signature'  => $signature
      ]);

      // 2. Insert order details to database
      $checkout = $_SESSION['checkout'] ?? [];
      $name = trim($checkout['name'] ?? '');
      $email = trim($checkout['email'] ?? '');
      $phone = trim($checkout['phone'] ?? '');
      $address = trim($checkout['address'] ?? '');

      if ($name === '' && isset($_SESSION['user'])) {
        $name = $user['firstname'] . ' ' . $user['lastname'];
        $email = $user['email'];
        $phone = $user['contact_info'];
        $address = $user['address'];
      }

      $conn = $pdo->open();
      $conn->beginTransaction();

      $lines = [];
      $total = 0.0;

      if (isset($_SESSION['user'])) {
        $stmt = $conn->prepare("SELECT p.id AS product_id, p.price AS unit_price, c.quantity
                                FROM cart c JOIN products p ON p.id=c.product_id WHERE c.user_id=:uid");
        $stmt->execute(['uid' => $user['id']]);
        foreach ($stmt as $r) {
          $qty = (int)$r['quantity'];
          $price = (float)$r['unit_price'];
          $lines[] = [
            'product_id' => (int)$r['product_id'],
            'price' => $price,
            'qty' => $qty
          ];
          $total += $price * $qty;
        }
      } else {
        if (!empty($_SESSION['cart'])) {
          foreach ($_SESSION['cart'] as $row) {
            $s = $conn->prepare("SELECT id, price FROM products WHERE id=:id");
            $s->execute(['id' => $row['productid']]);
            if ($p = $s->fetch()) {
              $qty = (int)$row['quantity'];
              $price = (float)$p['price'];
              $lines[] = [
                'product_id' => (int)$p['id'],
                'price' => $price,
                'qty' => $qty
              ];
              $total += $price * $qty;
            }
          }
        }
      }

      if (empty($lines)) {
        throw new Exception('Cart is empty.');
      }

      // Insert sales record
      $stmt = $conn->prepare("INSERT INTO sales (user_id, sales_date, pay_id, name, email, phone, address, payment_method, amount, status, razorpay_order_id, razorpay_payment_id, razorpay_signature)
                            VALUES (:uid, NOW(), :pay_id, :name, :email, :phone, :address, 'razorpay', :amount, 'paid', :order_id, :payment_id, :sig)");
      $stmt->execute([
        'uid'        => isset($user['id']) ? $user['id'] : null,
        'pay_id'     => $paymentId,
        'name'       => $name,
        'email'      => $email,
        'phone'      => $phone,
        'address'    => $address,
        'amount'     => $total,
        'order_id'   => $orderId,
        'payment_id' => $paymentId,
        'sig'        => $signature
      ]);
      $salesId = (int)$conn->lastInsertId();

      // Insert line items with snapshot pricing
      $ins = $conn->prepare("INSERT INTO details (sales_id, product_id, quantity, price) VALUES (:sid, :pid, :qty, :price)");
      foreach ($lines as $ln) {
        $ins->execute([
          'sid'   => $salesId,
          'pid'   => $ln['product_id'],
          'qty'   => $ln['qty'],
          'price' => $ln['price']
        ]);
      }

      // Clear cart
      if (isset($_SESSION['user'])) {
        $del = $conn->prepare("DELETE FROM cart WHERE user_id=:uid");
        $del->execute(['uid' => $user['id']]);
      } else {
        $_SESSION['cart'] = [];
      }

      unset($_SESSION['checkout']); // Clear checkout cache
      $conn->commit();
      $pdo->close();

      echo json_encode(['ok' => true]);
    } catch (Throwable $e) {
      if (isset($conn) && $conn->inTransaction()) {
        $conn->rollBack();
      }
      http_response_code(400);
      echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
    }
    exit;
}

// ----------------------------------------------------
// 3. Default Cash on Delivery (COD) Checkout Flow
// ----------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || ($_POST['payment_method'] ?? '') !== 'cod') {
  header('Location: checkout.php');
  exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$address = trim($_POST['address'] ?? '');

if ($name === '' || $email === '' || $phone === '' || $address === '') {
  $_SESSION['error'] = 'Please fill all billing fields.';
  header('Location: checkout.php');
  exit;
}

$conn = $pdo->open();

try {
  $conn->beginTransaction();

  $lines = [];
  $total = 0.0;

  if (isset($_SESSION['user'])) {
    $stmt = $conn->prepare("SELECT p.id AS product_id, p.price AS unit_price, c.quantity
                            FROM cart c JOIN products p ON p.id=c.product_id WHERE c.user_id=:uid");
    $stmt->execute(['uid' => $user['id']]);
    foreach ($stmt as $r) {
      $qty = (int)$r['quantity'];
      $price = (float)$r['unit_price'];
      $lines[] = [
        'product_id' => (int)$r['product_id'],
        'price' => $price,
        'qty' => $qty
      ];
      $total += $price * $qty;
    }
  } else {
    if (!empty($_SESSION['cart'])) {
      foreach ($_SESSION['cart'] as $row) {
        $s = $conn->prepare("SELECT id, price FROM products WHERE id=:id");
        $s->execute(['id' => $row['productid']]);
        if ($p = $s->fetch()) {
          $qty = (int)$row['quantity'];
          $price = (float)$p['price'];
          $lines[] = [
            'product_id' => (int)$p['id'],
            'price' => $price,
            'qty' => $qty
          ];
          $total += $price * $qty;
        }
      }
    }
  }

  if (empty($lines)) {
    $_SESSION['error'] = 'Cart is empty.';
    $conn->rollBack();
    header('Location: checkout.php');
    exit;
  }

  // Insert sales record for COD order (status 'pending')
  $stmt = $conn->prepare("INSERT INTO sales (user_id, sales_date, pay_id, name, email, phone, address, payment_method, amount, status)
                        VALUES (:uid, NOW(), NULL, :name, :email, :phone, :address, 'cod', :amount, 'pending')");
  $stmt->execute([
    'uid' => isset($user['id']) ? $user['id'] : null,
    'name' => $name,
    'email' => $email,
    'phone' => $phone,
    'address' => $address,
    'amount' => $total
  ]);
  $salesId = (int)$conn->lastInsertId();

  // Insert line items with snapshot pricing
  $ins = $conn->prepare("INSERT INTO details (sales_id, product_id, quantity, price) VALUES (:sid, :pid, :qty, :price)");
  foreach ($lines as $ln) {
    $ins->execute([
      'sid' => $salesId,
      'pid' => $ln['product_id'],
      'qty' => $ln['qty'],
      'price' => $ln['price']
    ]);
  }

  // Clear cart
  if (isset($_SESSION['user'])) {
    $del = $conn->prepare("DELETE FROM cart WHERE user_id=:uid");
    $del->execute(['uid' => $user['id']]);
  } else {
    $_SESSION['cart'] = [];
  }

  $conn->commit();
  $_SESSION['success'] = 'Order placed successfully!';
  header('Location: order_success.php');
  exit;

} catch (Throwable $e) {
  if ($conn->inTransaction()) {
    $conn->rollBack();
  }
  $_SESSION['error'] = 'Could not place COD order: ' . $e->getMessage();
  header('Location: checkout.php');
  exit;
}
?>
