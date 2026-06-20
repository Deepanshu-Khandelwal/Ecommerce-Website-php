<?php
include 'includes/session.php';
$conn = $pdo->open();

// Prepare fallback data for SSR render
$fallback_items = [];
$fallback_total = 0.0;
function h($v){ return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }

if (isset($_SESSION['user'])) {
  $stmt = $conn->prepare("SELECT p.id, p.name, p.slug, p.photo, p.price, c.quantity, c.id AS cartid
                          FROM cart c JOIN products p ON p.id=c.product_id WHERE c.user_id=:uid");
  $stmt->execute(['uid'=>$user['id']]);
  foreach ($stmt as $r) {
    $qty = (int)$r['quantity']; 
    $price = (float)$r['price']; 
    $sub = $price * $qty; 
    $fallback_total += $sub;
    $fallback_items[] = [
      'id' => $r['id'],
      'cartid' => $r['cartid'],
      'name' => $r['name'],
      'slug' => $r['slug'],
      'photo' => $r['photo'],
      'price' => $price,
      'qty' => $qty,
      'subtotal' => $sub
    ];
  }
} else {
  if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $row) {
      $s = $conn->prepare("SELECT id, name, slug, photo, price FROM products WHERE id=:id");
      $s->execute(['id'=>$row['productid']]);
      if (!$p=$s->fetch()) continue;
      $qty = (int)$row['quantity']; 
      $price = (float)$p['price']; 
      $sub = $price * $qty; 
      $fallback_total += $sub;
      $fallback_items[] = [
        'id' => $p['id'],
        'cartid' => $row['productid'],
        'name' => $p['name'],
        'slug' => $p['slug'],
        'photo' => $p['photo'],
        'price' => $price,
        'qty' => $qty,
        'subtotal' => $sub
      ];
    }
  }
}
$pdo->close();
?>
<?php include 'includes/header.php'; ?>
<body class="bg-light">
<div class="wrapper">

<?php include 'includes/navbar.php'; ?>

<div class="content-wrapper py-5">
  <div class="container">
    <section class="content">
      <div class="row g-4">
        
        <!-- Billing Details Form -->
        <div class="col-lg-7">
          <h2 class="section-title mt-0 pb-2 mb-4">Billing & Shipping Details</h2>
          <div class="card border-light shadow-sm p-4" style="border-radius: var(--radius-md);">
            <div class="card-body p-0">
              <form id="payment-form" action="process_checkout.php" method="POST">
                <div class="mb-3">
                  <label for="name" class="form-label fw-bold">Full Name</label>
                  <input type="text" class="form-control py-2" id="name" name="name" 
                         value="<?php echo isset($user) ? h($user['firstname'] . ' ' . $user['lastname']) : ''; ?>" required>
                </div>
                <div class="mb-3">
                  <label for="email" class="form-label fw-bold">Email Address</label>
                  <input type="email" class="form-control py-2" id="email" name="email" 
                         value="<?php echo isset($user) ? h($user['email']) : ''; ?>" required>
                </div>
                <div class="mb-3">
                  <label for="address" class="form-label fw-bold">Shipping Address</label>
                  <textarea class="form-control" id="address" name="address" rows="3" required><?php echo isset($user) ? h($user['address']) : ''; ?></textarea>
                </div>
                <div class="mb-3">
                  <label for="phone" class="form-label fw-bold">Phone Number</label>
                  <input type="text" class="form-control py-2" id="phone" name="phone" 
                         value="<?php echo isset($user) ? h($user['contact_info']) : ''; ?>" required>
                </div>

                <div class="mb-4">
                  <label for="payment_method" class="form-label fw-bold">Payment Method</label>
                  <select class="form-select py-2" id="payment_method" name="payment_method" required>
                    <option value="" selected disabled>Choose payment method...</option>
                    <option value="razorpay">Razorpay (Online Payment)</option>
                    <option value="cod">Cash on Delivery (COD)</option>
                  </select>
                </div>

                <div class="border-top border-light-subtle pt-3 d-flex justify-content-between align-items-center mb-3">
                  <span class="fs-5 text-muted">Grand Total:</span>
                  <span class="fs-3 fw-bold text-emerald" id="cart-total">&#8377; <?php echo number_format($fallback_total, 2); ?></span>
                </div>

                <!-- Interactive payment containers -->
                <div class="mt-4" id="payment-actions-container">
                  <div class="alert alert-secondary border-0 text-center py-3 m-0 small" role="alert">
                    <i class="fa-solid fa-circle-info me-1"></i> Please choose a payment method to complete your order.
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>

        <!-- Order Summary & Items List -->
        <div class="col-lg-5">
          <h2 class="section-title mt-0 pb-2 mb-4">Items Summary</h2>
          <div class="card border-light shadow-sm sticky-top" style="top: 100px; z-index: 10; border-radius: var(--radius-md);">
            <div class="card-body p-4">
              <div class="table-responsive mb-3" style="max-height: 350px; overflow-y: auto;">
                <table class="table align-middle">
                  <thead>
                    <tr>
                      <th>Product</th>
                      <th class="text-center">Qty</th>
                      <th class="text-end">Subtotal</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (empty($fallback_items)): ?>
                      <tr>
                        <td colspan="3" class="text-center text-muted py-3">Your cart is empty</td>
                      </tr>
                    <?php else: ?>
                      <?php foreach ($fallback_items as $item): 
                        $img = $item['photo'] ? 'images/'.$item['photo'] : 'images/noimage.jpg';
                      ?>
                        <tr>
                          <td>
                            <div class="d-flex align-items-center gap-2">
                              <a href="product.php?product=<?php echo h($item['slug']); ?>">
                                <img src="<?php echo h($img); ?>" class="rounded object-fit-cover" style="width: 40px; height: 40px; border: 1px solid var(--border-color);" alt="Thumbnail">
                              </a>
                              <a href="product.php?product=<?php echo h($item['slug']); ?>" class="text-decoration-none text-dark hover-underline small fw-semibold text-truncate d-inline-block" style="max-width: 150px;">
                                <?php echo h($item['name']); ?>
                              </a>
                            </div>
                          </td>
                          <td class="text-center small"><?php echo h($item['qty']); ?></td>
                          <td class="text-end fw-semibold text-dark small">&#8377; <?php echo number_format($item['subtotal'], 2); ?></td>
                        </tr>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>

              <div class="d-flex justify-content-between py-2 border-top border-light-subtle">
                <span class="text-muted">Shipping</span>
                <span class="text-success fw-bold">FREE</span>
              </div>
              <div class="d-flex justify-content-between py-2 border-top border-light-subtle pt-3">
                <span class="fw-bold fs-5">Estimated Total</span>
                <span class="fw-bold fs-4 text-emerald">&#8377; <?php echo number_format($fallback_total, 2); ?></span>
              </div>
            </div>
          </div>
        </div>

      </div>
    </section>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
$(function(){
  // Payment method selection changes actions container
  $('#payment_method').on('change', function(){
    var method = $(this).val();
    var container = $('#payment-actions-container');
    
    if (method === 'cod') {
      container.html(`
        <button type="submit" class="btn btn-success btn-lg w-100 btn-flat py-3 font-weight-bold text-uppercase" id="confirm-payment">
          <i class="fa-solid fa-circle-check me-2"></i> Confirm Purchase (COD)
        </button>
      `);
    } else if (method === 'razorpay') {
      container.html(`
        <button type="button" class="btn btn-primary btn-lg w-100 btn-flat py-3 font-weight-bold text-uppercase" id="pay-razorpay">
          <i class="fa-solid fa-credit-card me-2"></i> Pay with Razorpay
        </button>
      `);
    } else {
      container.html(`
        <div class="alert alert-secondary border-0 text-center py-3 m-0 small" role="alert">
          <i class="fa-solid fa-circle-info me-1"></i> Please choose a payment method to complete your order.
        </div>
      `);
    }
  });

  // Client side validation
  function validateForm() {
    var valid = true;
    $('#payment-form [required]').each(function(){
      if ($.trim($(this).val()) === '') {
        $(this).addClass('is-invalid');
        valid = false;
      } else {
        $(this).removeClass('is-invalid');
      }
    });
    return valid;
  }

  // Clear is-invalid on change/input
  $(document).on('input change', '#payment-form [required]', function(){
    if ($.trim($(this).val()) !== '') {
      $(this).removeClass('is-invalid');
    }
  });

  // COD Order Form submission validation
  $(document).on('submit', '#payment-form', function(e){
    if (!validateForm()) {
      e.preventDefault();
      alert('Please fill out all billing and shipping fields.');
      return false;
    }
  });

  // Razorpay Pay action
  $(document).on('click', '#pay-razorpay', function(e){
    e.preventDefault();
    if (!validateForm()) {
      alert('Please fill out all billing and shipping fields.');
      return;
    }

    var btn = $(this);
    btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin me-2"></i> Initiating Payment...');

    $.ajax({
      type: 'POST',
      url: 'process_checkout.php?action=create_razorpay_order',
      data: {
        name: $('#name').val(),
        email: $('#email').val(),
        phone: $('#phone').val(),
        address: $('#address').val()
      },
      dataType: 'json'
    }).done(function(res){
      if (!res.ok) {
        alert(res.error || 'Failed to create order.');
        btn.prop('disabled', false).html('<i class="fa-solid fa-credit-card me-2"></i> Pay with Razorpay');
        return;
      }

      var options = {
        key: res.key_id,
        amount: res.amount,
        currency: res.currency,
        name: "Pavitra Sarees",
        description: "Order Checkout Payment",
        image: "images/pavitra_logo_updated.png",
        order_id: res.order_id,
        prefill: res.prefill,
        notes: {
          address: $('#address').val()
        },
        handler: function(response) {
          btn.html('<i class="fa-solid fa-spinner fa-spin me-2"></i> Verifying Payment...');
          $.post('process_checkout.php?action=verify_razorpay_payment', {
            razorpay_payment_id: response.razorpay_payment_id,
            razorpay_order_id: response.razorpay_order_id,
            razorpay_signature: response.razorpay_signature
          }, function(verify){
            if (verify.ok) {
              window.location.href = 'order_success.php';
            } else {
              alert(verify.error || 'Payment verification failed');
              btn.prop('disabled', false).html('<i class="fa-solid fa-credit-card me-2"></i> Pay with Razorpay');
            }
          }, 'json').fail(function() {
            alert('Error verifying payment.');
            btn.prop('disabled', false).html('<i class="fa-solid fa-credit-card me-2"></i> Pay with Razorpay');
          });
        },
        modal: {
          ondismiss: function() {
            btn.prop('disabled', false).html('<i class="fa-solid fa-credit-card me-2"></i> Pay with Razorpay');
          }
        }
      };
      
      var rzp = new Razorpay(options);
      rzp.open();
    }).fail(function(xhr){
      var msg = (xhr.responseJSON && xhr.responseJSON.error) ? xhr.responseJSON.error : 'Request failed';
      alert('Could not create Razorpay order: ' + msg);
      btn.prop('disabled', false).html('<i class="fa-solid fa-credit-card me-2"></i> Pay with Razorpay');
    });
  });

});
</script>
</body>
</html>
