<?php include 'includes/session.php'; ?>
<?php
if (!isset($_GET['category']) || empty($_GET['category'])) {
    header('location: index.php');
    exit();
}

$slug = $_GET['category'];

$conn = $pdo->open();

try {
    $stmt = $conn->prepare("SELECT * FROM category WHERE cat_slug = :slug");
    $stmt->execute(['slug' => $slug]);
    $cat = $stmt->fetch();
} catch (PDOException $e) {
    echo "There is some problem in connection: " . $e->getMessage();
}

if (!$cat) {
    $pdo->close();
    header('location: index.php');
    exit();
}

$catid = $cat['id'];
$pdo->close();
?>
<?php include 'includes/header.php'; ?>
<body class="bg-light">
<div class="wrapper">

	<?php include 'includes/navbar.php'; ?>
	 
	  <div class="content-wrapper py-5">
	    <div class="container">

	      <!-- Main content -->
	      <section class="content">
	        <div class="row g-4">
	        	<div class="col-lg-12">
		            <h1 class="section-title mt-0 pb-2 mb-4"><?php echo $cat['name']; ?></h1>
		            <div class="row row-cols-1 row-cols-md-3 g-4">
		       		<?php
		       			$conn = $pdo->open();

		       			try{
						    $stmt = $conn->prepare("SELECT * FROM products WHERE category_id = :catid");
						    $stmt->execute(['catid' => $catid]);
						    foreach ($stmt as $row) {
						    	$image = (!empty($row['photo'])) ? 'images/'.$row['photo'] : 'images/noimage.jpg';
	       						echo "
	       							<div class='col'>
	       								<div class='box box-solid h-100 d-flex flex-column'>
		       								<div class='product-image-container'>
                               <img src='".$image."' class='img-responsive w-100 h-100 object-fit-cover' alt='Product Image'>
                               <img src='images/pavitra_logo.png' class='watermark' alt='Watermark'>
                             </div>
                             <div class='prod-info-wrap flex-grow-1 d-flex flex-column justify-content-between'>
                               <h5><a href='product.php?product=".$row['slug']."'>".$row['name']."</a></h5>
                             </div>
		       								<div class='box-footer mt-auto d-flex justify-content-between align-items-center'>
		       									<b class='prod-price'>&#8377; ".number_format($row['price'], 2)."</b>
                            <div class='d-flex gap-1'>
                              <a href='product.php?product=".$row['slug']."' class='btn btn-primary btn-xs btn-flat'>View</a>
                              <button type='button' class='btn btn-success btn-xs btn-flat add-to-cart-btn' data-id='".$row['id']."'><i class='fa-solid fa-cart-shopping'></i> Add</button>
                            </div>
		       								</div>
	       								</div>
	       							</div>
	       						";
						    }
						}
						catch(PDOException $e){
							echo "<div class='alert alert-danger w-100'>There is some problem in connection: " . $e->getMessage() . "</div>";
						}

						$pdo->close();

		       		?> 
		            </div>
	        	</div>
	        </div>
	      </section>
	     
	    </div>
	  </div>
  
  	<?php include 'includes/footer.php'; ?>
</div>

<script>
$(function(){
  $(document).on('click', '.add-to-cart-btn', function(e){
    e.preventDefault();
    var id = $(this).data('id');
    var btn = $(this);
    btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i>');
    $.ajax({
      type: 'POST',
      url: 'cart_add.php',
      data: {id: id, quantity: 1},
      dataType: 'json',
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      },
      success: function(response){
        btn.prop('disabled', false).html('<i class="fa-solid fa-cart-shopping"></i> Add');
        if(response.error){
          showAlert(response.message, 'danger');
        }
        else{
          showAlert(response.message, 'success');
          getCart();
        }
      },
      error: function(){
        btn.prop('disabled', false).html('<i class="fa-solid fa-cart-shopping"></i> Add');
        showAlert('Unable to add item to cart. Please try again.', 'danger');
      }
    });
  });
});
</script>
</body>
</html>