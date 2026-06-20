<?php include 'includes/session.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php include 'includes/header.php'; ?>
  <title>Order Success</title>
</head>
<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper">
  <?php include 'includes/navbar.php'; ?>
  <div class="content-wrapper">
    <div class="container" style="padding: 50px 15px;">
      <div class="box box-solid" style="max-width: 600px; margin: 0 auto; text-align: center; padding: 40px; border-radius: var(--radius-lg) !important; border: 1px solid var(--border-color) !important; box-shadow: var(--shadow-lg) !important;">
        <div class="box-body" style="padding: 0;">
          <div style="font-size: 70px; color: #2E7D32; margin-bottom: 20px;">
            <i class="fa fa-check-circle"></i>
          </div>
          <h2 style="font-family: var(--font-serif); color: var(--primary-color); font-size: 28px; margin-bottom: 15px;"><b>Order Placed Successfully!</b></h2>
          <p style="font-size: 16px; color: var(--text-color); line-height: 1.8; margin-bottom: 30px;">
            Thank you for shopping with us! Your order has been registered, and a confirmation email has been dispatched with transaction specifics.
          </p>
          <div style="display: flex; gap: 15px; justify-content: center;">
            <a href="profile.php" class="btn btn-default btn-flat" style="padding: 10px 20px; font-size: 14px; border: 1px solid var(--border-color); background: #fff; margin: 0;">Order History</a>
            <a href="index.php" class="btn btn-primary btn-flat" style="padding: 10px 20px; font-size: 14px; margin: 0;">Continue Shopping</a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php include 'includes/footer.php'; ?>
</div>
</body>
</html>
