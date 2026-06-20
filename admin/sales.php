<?php
include 'includes/session.php';
include 'includes/header.php';
function h($v){ return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
?>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>Sales & Order Management</h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Sales</li>
      </ol>
    </section>

    <section class="content">
      <div class="row"><div class="col-xs-12">
        <div class="box">
          <div class="box-header with-border">
            <div class="pull-right">
              <form method="POST" class="form-inline" action="sales_print.php">
                <div class="input-group">
                  <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                  <input type="text" class="form-control pull-right col-sm-8" id="reservation" name="date_range" placeholder="Select date range">
                </div>
                <button type="submit" class="btn btn-success btn-sm btn-flat" name="print">
                  <span class="glyphicon glyphicon-print"></span> Print Report
                </button>
              </form>
            </div>
          </div>

          <div class="box-body">
            <table id="example1" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Order #</th>
                  <th>Date</th>
                  <th>Buyer Name</th>
                  <th>Method</th>
                  <th>Status</th>
                  <th>Txn ID / Ref</th>
                  <th>Amount</th>
                  <th>Details</th>
                </tr>
              </thead>
              <tbody>
              <?php
                $conn = $pdo->open();
                try {
                  // Fetch all orders
                  $sql = "
                    SELECT
                      s.id AS salesid,
                      s.sales_date,
                      s.pay_id,
                      s.payment_method,
                      s.status,
                      s.amount AS total_amount,
                      COALESCE(CONCAT(u.firstname,' ',u.lastname), s.name, 'Guest') AS buyer_name
                    FROM sales s
                    LEFT JOIN users u ON u.id = s.user_id
                    ORDER BY s.sales_date DESC
                  ";
                  $stmt = $conn->prepare($sql);
                  $stmt->execute();
                  foreach ($stmt as $row) {
                    $method = strtolower($row['payment_method'] ?? 'razorpay');
                    $methodBadge = ($method === 'cod') 
                        ? '<span class="label label-default">COD</span>' 
                        : '<span class="label label-primary">Online</span>';
                    
                    $status = strtolower($row['status'] ?? 'pending');
                    if ($status === 'paid') {
                        $statusBadge = '<span class="label label-success">Paid</span>';
                    } elseif ($status === 'pending') {
                        $statusBadge = '<span class="label label-warning">Pending</span>';
                    } elseif ($status === 'shipped') {
                        $statusBadge = '<span class="label label-info">Shipped</span>';
                    } else {
                        $statusBadge = '<span class="label label-danger">' . ucfirst($status) . '</span>';
                    }

                    $txnId = $row['pay_id'] ?: '-';
                    $orderDate = $row['sales_date'] ? date("M d, Y H:i", strtotime($row['sales_date'])) : '';

                    echo '<tr>
                      <td>'.h($row['salesid']).'</td>
                      <td>'.h($orderDate).'</td>
                      <td>'.h($row['buyer_name']).'</td>
                      <td>'.$methodBadge.'</td>
                      <td>'.$statusBadge.'</td>
                      <td class="font-monospace small">'.h($txnId).'</td>
                      <td class="text-bold">₹ '.number_format((float)$row['total_amount'], 2).'</td>
                      <td><button type="button" class="btn btn-info btn-sm btn-flat transact" data-id="'.h($row['salesid']).'">
                            <i class="fa fa-search"></i> View
                          </button></td>
                    </tr>';
                  }
                } catch (PDOException $e) {
                  echo '<tr><td colspan="8">'.h($e->getMessage()).'</td></tr>';
                }
                $pdo->close();
              ?>
              </tbody>
            </table>
          </div>
        </div>
      </div></div>

      <!-- Transaction Modal -->
      <div class="modal fade" id="transaction" tabindex="-1" role="dialog" aria-labelledby="transactionLabel">
        <div class="modal-dialog" role="document"><div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            <h4 class="modal-title" id="transactionLabel">Order Details</h4>
          </div>
          <div class="modal-body">
            <p><strong>Date:</strong> <span id="date">-</span></p>
            <p><strong>Transaction ID / Ref:</strong> <span id="transid">-</span></p>
            <div id="detail" style="margin-top: 15px;"></div>
            <hr>
            <p class="text-right" style="font-size: 16px; margin-bottom: 20px;"><strong>Total:</strong> <span id="total">₹ 0.00</span></p>
            
            <div class="panel panel-default" style="margin-top: 20px;">
              <div class="panel-heading"><strong>Manage Order Status</strong></div>
              <div class="panel-body">
                <div class="form-group" style="margin-bottom: 0;">
                  <label for="modal-status">Change Status:</label>
                  <div class="input-group">
                    <select id="modal-status" class="form-control">
                      <option value="pending">Pending</option>
                      <option value="paid">Paid</option>
                      <option value="shipped">Shipped</option>
                      <option value="cancelled">Cancelled</option>
                    </select>
                    <span class="input-group-btn">
                      <button type="button" id="update-status-btn" class="btn btn-success btn-flat">Update Status</button>
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Close</button>
          </div>
        </div></div>
      </div>
    </section>
  </div>

  <?php include 'includes/footer.php'; ?>
  <?php include '../includes/profile_modal.php'; ?>
</div>

<?php include 'includes/scripts.php'; ?>
<script>
$(function(){
  $('#reservation').daterangepicker();

  $(document).on('click','.transact',function(e){
    e.preventDefault();
    var id = $(this).data('id');
    $('#date').text('-'); $('#transid').text('-'); $('#detail').html(''); $('#total').text('₹ 0.00');
    $('#update-status-btn').data('id', id);
    $('#transaction').modal('show');
    
    $.post('transact.php', {id:id}, function(res){
      $('#date').html(res.date||'-');
      $('#transid').html(res.transaction||'-');
      $('#detail').html(res.list||'');
      $('#total').html(res.total||'₹ 0.00');
      $('#modal-status').val(res.status);
    }, 'json').fail(function(){
      $('#detail').html('<div class="alert alert-danger">Failed to load details.</div>');
    });
  });

  $(document).on('click', '#update-status-btn', function(e){
    e.preventDefault();
    var id = $(this).data('id');
    var status = $('#modal-status').val();
    var btn = $(this);
    
    btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Updating...');
    
    $.post('order_status.php', {id: id, status: status}, function(res){
      if(res.ok) {
        $('#transaction').modal('hide');
        window.location.reload();
      } else {
        alert(res.error || 'Failed to update status.');
        btn.prop('disabled', false).text('Update Status');
      }
    }, 'json').fail(function(){
      alert('Error connecting to status endpoint.');
      btn.prop('disabled', false).text('Update Status');
    });
  });

  $('#transaction').on('hidden.bs.modal', function(){ $('#detail').html(''); });
});
</script>
</body>
</html>
