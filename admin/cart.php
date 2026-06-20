<?php
include 'includes/session.php';

// Handle AJAX & Form POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = $pdo->open();

    // 1. Fetch single row details (replacing cart_row.php)
    if (isset($_POST['get_row'])) {
        $id = $_POST['id'] ?? 0;
        $stmt = $conn->prepare("SELECT *, cart.id AS cartid FROM cart LEFT JOIN products ON products.id=cart.product_id WHERE cart.id=:id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        $pdo->close();
        header('Content-Type: application/json');
        echo json_encode($row);
        exit();
    }

    // 2. Add product to cart (replacing cart_add.php)
    if (isset($_POST['add'])) {
        $id = $_POST['id'] ?? 0; // user id
        $product = $_POST['product'] ?? 0;
        $quantity = $_POST['quantity'] ?? 1;

        $stmt = $conn->prepare("SELECT * FROM cart WHERE product_id=:id AND user_id=:user");
        $stmt->execute(['id' => $product, 'user' => $id]);
        $row = $stmt->fetch();

        if ($row !== false) {
            $_SESSION['error'] = 'Product already exists in cart';
        } else {
            try {
                $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (:user, :product, :quantity)");
                $stmt->execute(['user' => $id, 'product' => $product, 'quantity' => $quantity]);
                $_SESSION['success'] = 'Product added to cart';
            } catch (PDOException $e) {
                $_SESSION['error'] = $e->getMessage();
            }
        }
        $pdo->close();
        header('location: cart.php?user=' . $id);
        exit();
    }

    // 3. Edit product quantity (replacing cart_edit.php)
    if (isset($_POST['edit'])) {
        $userid = $_POST['userid'] ?? 0;
        $cartid = $_POST['cartid'] ?? 0;
        $quantity = $_POST['quantity'] ?? 1;

        try {
            $stmt = $conn->prepare("UPDATE cart SET quantity=:quantity WHERE id=:id");
            $stmt->execute(['quantity' => $quantity, 'id' => $cartid]);
            $_SESSION['success'] = 'Quantity updated successfully';
        } catch (PDOException $e) {
            $_SESSION['error'] = $e->getMessage();
        }
        $pdo->close();
        header('location: cart.php?user=' . $userid);
        exit();
    }

    // 4. Delete product from cart (replacing cart_delete.php)
    if (isset($_POST['delete'])) {
        $userid = $_POST['userid'] ?? 0;
        $cartid = $_POST['cartid'] ?? 0;

        try {
            $stmt = $conn->prepare("DELETE FROM cart WHERE id=:id");
            $stmt->execute(['id' => $cartid]);
            $_SESSION['success'] = 'Product deleted from cart';
        } catch (PDOException $e) {
            $_SESSION['error'] = $e->getMessage();
        }
        $pdo->close();
        header('location: cart.php?user=' . $userid);
        exit();
    }
    $pdo->close();
}

if(!isset($_GET['user'])){
  header('location: users.php');
  exit();
} else {
  $conn = $pdo->open();
  $stmt = $conn->prepare("SELECT * FROM users WHERE id=:id");
  $stmt->execute(['id'=>$_GET['user']]);
  $user = $stmt->fetch();
  $pdo->close();
  if (!$user) {
      header('location: users.php');
      exit();
  }
}

include 'includes/header.php'; 
?>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo htmlspecialchars($user['firstname'] . ' ' . $user['lastname']) . "'s Cart"; ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li>Users</li>
        <li class="active">Cart</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <?php
        if(isset($_SESSION['error'])){
          echo "
            <div class='alert alert-danger alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-warning'></i> Error!</h4>
              ".$_SESSION['error']."
            </div>
          ";
          unset($_SESSION['error']);
        }
        if(isset($_SESSION['success'])){
          echo "
            <div class='alert alert-success alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-check'></i> Success!</h4>
              ".$_SESSION['success']."
            </div>
          ";
          unset($_SESSION['success']);
        }
      ?>
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border">
              <a href="#addnew" data-toggle="modal" id="add" data-id="<?php echo $user['id']; ?>" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus"></i> New</a>
              <a href="users.php" class="btn btn-sm btn-primary btn-flat"><i class="fa fa-arrow-left"></i> Users</a>
            </div>
            <div class="box-body">
              <table id="example1" class="table table-bordered">
                <thead>
                  <th>Product Name</th>
                  <th>Quantity</th>
                  <th>Tools</th>
                </thead>
                <tbody>
                  <?php
                    $conn = $pdo->open();

                    try{
                      $stmt = $conn->prepare("SELECT *, cart.id AS cartid FROM cart LEFT JOIN products ON products.id=cart.product_id WHERE user_id=:user_id");
                      $stmt->execute(['user_id'=>$user['id']]);
                      foreach($stmt as $row){
                        echo "
                          <tr>
                            <td>".$row['name']."</td>
                            <td>".$row['quantity']."</td>
                            <td>
                              <button class='btn btn-success btn-sm edit btn-flat' data-id='".$row['cartid']."'><i class='fa fa-edit'></i> Edit Quantity</button>
                              <button class='btn btn-danger btn-sm delete btn-flat' data-id='".$row['cartid']."'><i class='fa fa-trash'></i> Delete</button>
                            </td>
                          </tr>
                        ";
                      }
                    }
                    catch(PDOException $e){
                      echo $e->getMessage();
                    }

                    $pdo->close();
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>
     
  </div>
  	<?php include 'includes/footer.php'; ?>
    <?php include 'includes/cart_modal.php'; ?>

</div>
<!-- ./wrapper -->

<?php include 'includes/scripts.php'; ?>
<script>
$(function(){
  $(document).on('click', '.edit', function(e){
    e.preventDefault();
    $('#edit').modal('show');
    var id = $(this).data('id');
    getRow(id);
  });

  $(document).on('click', '.delete', function(e){
    e.preventDefault();
    $('#delete').modal('show');
    var id = $(this).data('id');
    getRow(id);
  });

  $('#add').click(function(e){
    e.preventDefault();
    var id = $(this).data('id');
    getProducts(id);
  });

  $("#addnew").on("hidden.bs.modal", function () {
      $('.append_items').remove();
  });

});

function getProducts(id){
  $.ajax({
    type: 'POST',
    url: 'products.php',
    data: {fetch_products: true},
    dataType: 'json',
    success: function(response){
      $('#product').append(response);
      $('.userid').val(id);
    }
  });
}

function getRow(id){
  $.ajax({
    type: 'POST',
    url: 'cart.php',
    data: {get_row: true, id: id},
    dataType: 'json',
    success: function(response){
      $('.cartid').val(response.cartid);
      $('.userid').val(response.user_id);
      $('.productname').html(response.name);
      $('#edit_quantity').val(response.quantity);
    }
  });
}
</script>
</body>
</html>
