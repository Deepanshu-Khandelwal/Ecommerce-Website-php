<?php
include 'includes/session.php';
include 'includes/header.php';
?>

<body class="bg-light">
  <div class="wrapper">
    <?php include 'includes/navbar.php'; ?>

    <div class="content-wrapper py-5">
      <div class="container">

        <!-- Main content -->
        <section class="content">
          <div class="row g-4">
            <div class="col-lg-12">
              <?php
              if (isset($_SESSION['error'])) {
                echo "
                  <div class='alert alert-danger alert-dismissible fade show' role='alert'>
                    " . $_SESSION['error'] . "
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                  </div>
                ";
                unset($_SESSION['error']);
              }
              ?>

              <!-- Modern Hero Banner Slider -->
              <?php
              $conn = $pdo->open();
              $banners = [];
              try {
                $stmt = $conn->prepare("SELECT * FROM carousel_banners WHERE status = 1 ORDER BY sort_order ASC, id DESC");
                $stmt->execute();
                $banners = $stmt->fetchAll();
              } catch (PDOException $e) {
                // Query fallback
              }
              $pdo->close();

              if (!empty($banners)):
              ?>
              <div id="carouselHero" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4000">
                <div class="carousel-indicators">
                  <?php foreach ($banners as $i => $banner): ?>
                    <button type="button" data-bs-target="#carouselHero" data-bs-slide-to="<?php echo $i; ?>" class="<?php echo ($i === 0) ? 'active' : ''; ?>" <?php echo ($i === 0) ? 'aria-current="true"' : ''; ?> aria-label="Slide <?php echo $i + 1; ?>"></button>
                  <?php endforeach; ?>
                </div>

                <div class="carousel-inner">
                  <?php foreach ($banners as $i => $banner): 
                    $image_path = 'images/' . $banner['image'];
                    $alt_text = !empty($banner['title']) ? htmlspecialchars($banner['title']) : 'Slide ' . ($i + 1);
                    $slide_content = '<img src="' . $image_path . '" class="d-block w-100" alt="' . $alt_text . '">';
                    if (!empty($banner['link'])) {
                      $slide_content = '<a href="' . htmlspecialchars($banner['link']) . '">' . $slide_content . '</a>';
                    }
                  ?>
                    <div class="carousel-item <?php echo ($i === 0) ? 'active' : ''; ?>">
                      <?php echo $slide_content; ?>
                    </div>
                  <?php endforeach; ?>
                </div>

                <button class="carousel-control-prev" type="button" data-bs-target="#carouselHero" data-bs-slide="prev">
                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                  <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselHero" data-bs-slide="next">
                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                  <span class="visually-hidden">Next</span>
                </button>
              </div>
              <?php endif; ?>

              <!-- Shop By Category Quick Pills -->
              <div class="category-pills-container text-center py-3">
                <h3 class="category-pills-title">Shop By Category</h3>
                <div class="category-pills d-flex flex-wrap justify-content-center gap-2">
                  <a href="index.php" class="cat-pill active">All Collections</a>
                  <?php
                  $conn = $pdo->open();
                  try {
                    $stmt = $conn->prepare("SELECT * FROM category");
                    $stmt->execute();
                    foreach ($stmt as $c_row) {
                      echo "<a href='category.php?category=" . $c_row['cat_slug'] . "' class='cat-pill'>" . $c_row['name'] . "</a>";
                    }
                  } catch (PDOException $e) {
                    echo "<span class='text-danger'>Connection error: " . $e->getMessage() . "</span>";
                  }
                  ?>
                </div>
              </div>

              <h2 class="section-title">Monthly Top Sellers</h2>
              <div class="row row-cols-1 row-cols-md-3 g-4">
                <?php
                $month = date('m');
                try {
                  $stmt = $conn->prepare("SELECT *, SUM(quantity) AS total_qty FROM details LEFT JOIN sales ON sales.id=details.sales_id LEFT JOIN products ON products.id=details.product_id WHERE MONTH(sales_date) = :month GROUP BY details.product_id ORDER BY total_qty DESC LIMIT 6");
                  $stmt->execute(['month' => $month]);
                  foreach ($stmt as $row) {
                    $image = (!empty($row['photo'])) ? 'images/' . $row['photo'] : 'images/noimage.jpg';
                    echo "
                    <div class='col'>
                      <div class='box box-solid h-100 d-flex flex-column'>
                        <div class='product-image-container'>
                          <img src='" . $image . "' class='img-responsive w-100 h-100 object-fit-cover' alt='Product Image'>
                        </div>
                        <div class='prod-info-wrap flex-grow-1 d-flex flex-column justify-content-between'>
                          <h5><a href='product.php?product=" . $row['slug'] . "'>" . $row['name'] . "</a></h5>
                        </div>
                        <div class='box-footer mt-auto d-flex justify-content-between align-items-center'>
                          <b class='prod-price'>&#8377; " . number_format($row['price'], 2) . "</b>
                          <div class='d-flex gap-1'>
                            <a href='product.php?product=" . $row['slug'] . "' class='btn btn-primary btn-xs btn-flat'>View</a>
                            <button type='button' class='btn btn-success btn-xs btn-flat add-to-cart-btn' data-id='" . $row['id'] . "'><i class='fa-solid fa-cart-shopping'></i> Add</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  ";
                  }
                } catch (PDOException $e) {
                  echo "<div class='alert alert-danger w-100'>There is some problem in connection: " . $e->getMessage() . "</div>";
                }
                ?>
              </div>

              <!-- Heritage & Brand Story Section -->
              <div class="heritage-banner">
                <div class="heritage-content">
                  <h3>The Gota Patti Heritage</h3>
                  <p>Discover our exclusive collection of handcrafted Gota Patti and pure silk sarees. Every piece tells
                    a story of centuries-old Indian craftsmanship, woven with fine metallic threads and vibrant
                    traditional dyes.</p>
                  <a href="about.php" class="btn btn-info text-uppercase">Read Our Story</a>
                </div>
                <div class="heritage-logo-wrap">
                  <img src="images/pavitra_logo.png" alt="Brand Logo">
                </div>
              </div>

              <!-- Premium Brand Highlights Grid -->
              <div class="row row-cols-2 row-cols-lg-4 g-4 my-5 py-4 border-top border-bottom border-light-subtle">
                <div class="col text-center">
                  <div class="highlight-icon mb-3">
                    <i class="fa-solid fa-gem fs-2" style="color: var(--accent-color);"></i>
                  </div>
                  <h5 class="fw-bold mb-1" style="font-family: var(--font-sans); font-size: 15px;">Handcrafted Heritage
                  </h5>
                  <p class="text-muted small mb-0">Centuries-old traditional weavers</p>
                </div>
                <div class="col text-center">
                  <div class="highlight-icon mb-3">
                    <i class="fa-solid fa-truck-fast fs-2" style="color: var(--accent-color);"></i>
                  </div>
                  <h5 class="fw-bold mb-1" style="font-family: var(--font-sans); font-size: 15px;">Free Shipping</h5>
                  <p class="text-muted small mb-0">Complimentary delivery across India</p>
                </div>
                <div class="col text-center">
                  <div class="highlight-icon mb-3">
                    <i class="fa-solid fa-certificate fs-2" style="color: var(--accent-color);"></i>
                  </div>
                  <h5 class="fw-bold mb-1" style="font-family: var(--font-sans); font-size: 15px;">100% Authentic</h5>
                  <p class="text-muted small mb-0">Pure silks & genuine Gota work</p>
                </div>
                <div class="col text-center">
                  <div class="highlight-icon mb-3">
                    <i class="fa-solid fa-headset fs-2" style="color: var(--accent-color);"></i>
                  </div>
                  <h5 class="fw-bold mb-1" style="font-family: var(--font-sans); font-size: 15px;">Custom Designs</h5>
                  <p class="text-muted small mb-0">Dedicated support for your dream look</p>
                </div>
              </div>

              <h2 class="section-title">Our Saree Collection</h2>
              <div class="row row-cols-1 row-cols-md-3 g-4 mb-4">
                <?php
                try {
                  $limit = 9; // Number of items per page
                  $page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? (int) $_GET['page'] : 1;
                  if ($page < 1)
                    $page = 1;
                  $offset = ($page - 1) * $limit;

                  // Query total products count
                  $count_stmt = $conn->query("SELECT COUNT(*) FROM products");
                  $total_products = $count_stmt->fetchColumn();
                  $total_pages = ceil($total_products / $limit);

                  // Fetch page-specific products (using bindValue for proper PDO integer binding)
                  $stmt = $conn->prepare("SELECT * FROM products ORDER BY id DESC LIMIT :limit OFFSET :offset");
                  $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
                  $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
                  $stmt->execute();

                  foreach ($stmt as $row) {
                    $image = (!empty($row['photo'])) ? 'images/' . $row['photo'] : 'images/noimage.jpg';
                    echo "
                    <div class='col'>
                      <div class='box box-solid h-100 d-flex flex-column'>
                        <div class='product-image-container'>
                          <img src='" . $image . "' class='img-responsive w-100 h-100 object-fit-cover' alt='Product Image'>
                        </div>
                        <div class='prod-info-wrap flex-grow-1 d-flex flex-column justify-content-between'>
                          <h5><a href='product.php?product=" . $row['slug'] . "'>" . $row['name'] . "</a></h5>
                        </div>
                        <div class='box-footer mt-auto d-flex justify-content-between align-items-center'>
                          <b class='prod-price'>&#8377; " . number_format($row['price'], 2) . "</b>
                          <div class='d-flex gap-1'>
                            <a href='product.php?product=" . $row['slug'] . "' class='btn btn-primary btn-xs btn-flat'>View</a>
                            <button type='button' class='btn btn-success btn-xs btn-flat add-to-cart-btn' data-id='" . $row['id'] . "'><i class='fa-solid fa-cart-shopping'></i> Add</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  ";
                  }
                } catch (PDOException $e) {
                  echo "<div class='alert alert-danger w-100'>There is some problem in connection: " . $e->getMessage() . "</div>";
                }
                ?>
              </div>

              <!-- Modern Bootstrap 5 Pagination Bar -->
              <?php if (isset($total_pages) && $total_pages > 1): ?>
                <nav aria-label="Product Page Navigation" class="my-5">
                  <ul class="pagination justify-content-center">
                    <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                      <a class="page-link" href="?page=<?= $page - 1 ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                      </a>
                    </li>

                    <?php
                    $start_page = max(1, $page - 2);
                    $end_page = min($total_pages, $page + 2);
                    for ($i = $start_page; $i <= $end_page; $i++):
                      ?>
                      <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                      </li>
                    <?php endfor; ?>

                    <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                      <a class="page-link" href="?page=<?= $page + 1 ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                      </a>
                    </li>
                  </ul>
                </nav>
              <?php endif; ?>

              <?php $pdo->close(); ?>
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
        url: 'ajax_action.php?action=cart_add',
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