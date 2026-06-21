<?php include 'includes/session.php'; ?>
<?php
if (!isset($_GET['product']) || empty($_GET['product'])) {
    header('location: index.php');
    exit();
}

$conn = $pdo->open();
$slug = $_GET['product'];

try {
    $stmt = $conn->prepare("SELECT *, products.name AS prodname, category.name AS catname, products.id AS prodid 
                            FROM products 
                            LEFT JOIN category ON category.id = products.category_id 
                            WHERE slug = :slug");
    $stmt->execute(['slug' => $slug]);
    $product = $stmt->fetch();
} catch (PDOException $e) {
    echo "There is some problem in connection: " . $e->getMessage();
}

if (!$product) {
    $pdo->close();
    header('location: index.php');
    exit();
}

// page view logic
$now = date('Y-m-d');
if ($product['date_view'] == $now) {
    $stmt = $conn->prepare("UPDATE products SET counter = counter + 1 WHERE id = :id");
    $stmt->execute(['id' => $product['prodid']]);
} else {
    $stmt = $conn->prepare("UPDATE products SET counter = 1, date_view = :now WHERE id = :id");
    $stmt->execute(['id' => $product['prodid'], 'now' => $now]);
}

?>

<?php include 'includes/header.php'; ?>

<body class="bg-light">
    <!-- Include Facebook SDK for comments -->
    <script>
        (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = 'https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.12';
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script>

    <div class="wrapper">
        <?php include 'includes/navbar.php'; ?>

        <div class="content-wrapper py-5">
            <div class="container">
                <!-- Main content -->
                <section class="content">
                    <div class="row g-4">
                        <div class="col-lg-12">
                            <div class="alert alert-dismissible fade show shadow-sm" role="alert" id="callout" style="display:none">
                                <span class="message"></span>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>

                            <div class="card border-light shadow-sm p-4 mb-4">
                                <div class="card-body p-0">
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <!-- Product Image with Magnify Zoom -->
                                            <div class="border border-light-subtle rounded overflow-hidden mb-3">
                                                <a href="images/large-<?php echo $product['photo']; ?>" class="image-link">
                                                    <img src="images/<?php echo $product['photo']; ?>" class="img-fluid w-100 zoom" data-magnify-src="images/large-<?php echo $product['photo']; ?>" alt="Product Image" style="transition: var(--transition);">
                                                </a>
                                            </div>

                                            <!-- Add to Cart Form with AJAX -->
                                            <form id="productForm">
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="input-group" style="width: 140px;">
                                                        <button type="button" id="minus" class="btn btn-outline-secondary btn-flat"><i class="fa-solid fa-minus"></i></button>
                                                        <input type="text" name="quantity" id="quantity" class="form-control text-center font-weight-bold" value="1">
                                                        <button type="button" id="add" class="btn btn-outline-secondary btn-flat"><i class="fa-solid fa-plus"></i></button>
                                                    </div>
                                                    <input type="hidden" value="<?php echo $product['prodid']; ?>" name="id">
                                                    <button type="submit" class="btn btn-primary btn-lg btn-flat flex-grow-1"><i class="fa-solid fa-cart-shopping me-2"></i> Add to Cart</button>
                                                </div>
                                            </form>
                                        </div>

                                        <div class="col-md-6">
                                            <h1 class="section-title mt-0 border-0 pb-2 mb-3" style="font-size: 28px !important;"><?php echo $product['prodname']; ?></h1>
                                            <h3 class="mb-4 text-emerald font-weight-bold" style="color: var(--primary-color);"><b>&#8377; <?php echo number_format($product['price'], 2); ?></b></h3>
                                            <p class="mb-2"><b>Category:</b> <a href="category.php?category=<?php echo $product['cat_slug']; ?>" style="color: var(--accent-color); font-weight: 600;"><?php echo $product['catname']; ?></a></p>
                                            <hr class="my-3 border-light-subtle">
                                            <p class="mb-2"><b>Description:</b></p>
                                            <div class="text-muted" style="font-size: 14px; line-height: 1.8;"><?php echo $product['description']; ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <!-- Facebook comments plugin -->
                                <div class="fb-comments" data-href="https://pavitradesigner.com/product.php?product=<?php echo $slug; ?>" data-numposts="10" width="100%"></div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>

        <?php $pdo->close(); ?>
        <?php include 'includes/footer.php'; ?>
    </div>


    <!-- Include Magnific Popup CSS (for Image Zoom) -->
    <link rel="stylesheet" href="magnific-popup/magnific-popup.css">

    <!-- Add jQuery, Magnific Popup JS -->
    <script src="magnific-popup/jquery.magnific-popup.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize Magnific Popup for image zoom
            if ($('.image-link').length) {
                $('.image-link').magnificPopup({
                    type: 'image',
                    gallery: {
                        enabled: false // Disable gallery, only single image
                    }
                });
            }

            // Increase quantity
            $('#add').click(function(e) {
                e.preventDefault();
                var quantity = parseInt($('#quantity').val()) + 1;
                $('#quantity').val(quantity);
            });

            // Decrease quantity
            $('#minus').click(function(e) {
                e.preventDefault();
                var quantity = parseInt($('#quantity').val());
                if (quantity > 1) {
                    $('#quantity').val(quantity - 1);
                }
            });

            // Handle Add to Cart AJAX Form Submission
            $('#productForm').submit(function(e){
                e.preventDefault();
                var product = $(this).serialize();
                var form = $(this);
                var submitBtn = form.find('button[type="submit"]');
                var originalHtml = submitBtn.html();
                submitBtn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin me-2"></i> Adding...');
                $.ajax({
                    type: 'POST',
                    url: 'cart_add.php',
                    data: product,
                    dataType: 'json',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function(response){
                        submitBtn.prop('disabled', false).html(originalHtml);
                        if(response.error){
                            showAlert(response.message, 'danger');
                        }
                        else{
                            showAlert(response.message, 'success');
                            getCart();
                        }
                    },
                    error: function(){
                        submitBtn.prop('disabled', false).html(originalHtml);
                        showAlert('Unable to add item to cart. Please try again.', 'danger');
                    }
                });
            });
        });
    </script>
</body>
</html>
