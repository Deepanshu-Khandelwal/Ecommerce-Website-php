<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>

<body class="bg-light">
<div class="wrapper">

    <?php include 'includes/navbar.php'; ?>

    <div class="content-wrapper py-5">
        <div class="container">

            <!-- Main content -->
            <section class="content">
                <div class="row g-4">
                    <!-- Shopping Cart List -->
                    <div class="col-lg-8">
                        <h1 class="section-title mt-0 pb-2 mb-4">Your Shopping Cart</h1>
                        <div class="card border-light shadow-sm mb-4">
                            <div class="card-body p-0 table-responsive">
                                <table class="table align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>Action</th>
                                            <th>Photo</th>
                                            <th>Product Name</th>
                                            <th>Price</th>
                                            <th width="22%">Quantity</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody">
                                        <!-- Cart items will be dynamically added here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary Sidebar -->
                    <div class="col-lg-4">
                        <div class="card border-light shadow-sm p-4 sticky-top" style="top: 100px; z-index: 10; border-radius: var(--radius-md);">
                            <h4 class="mb-4 font-weight-bold" style="font-family: var(--font-serif); color: var(--primary-color);">Order Summary</h4>
                            
                            <div class="d-flex justify-content-between mb-3 border-bottom border-light-subtle pb-3">
                                <span class="text-muted">Subtotal</span>
                                <span class="fw-bold fs-5 text-dark" id="cart-total">&#8377; 0.00</span>
                            </div>
                            
                            <div class="d-flex justify-content-between mb-4">
                                <span class="text-muted">Shipping</span>
                                <span class="text-success fw-bold">FREE</span>
                            </div>
                            
                            <div class="d-flex justify-content-between mb-4 border-top border-light-subtle pt-3">
                                <span class="fw-bold fs-5">Estimated Total</span>
                                <span class="fw-bold fs-4" id="cart-estimated-total" style="color: var(--primary-color);">&#8377; 0.00</span>
                            </div>

                            <?php
                                if (isset($_SESSION['user'])) {
                                    echo '
                                        <div class="mt-4">
                                            <form action="checkout.php" method="POST">
                                                <button type="submit" class="btn btn-success btn-lg w-100 btn-flat py-3 font-weight-bold text-uppercase" style="letter-spacing: 0.5px;">
                                                    <i class="fa-solid fa-credit-card me-2"></i> Confirm Purchase & Checkout
                                                </button>
                                            </form>
                                        </div>
                                    ';
                                } else {
                                    echo '
                                        <div class="alert alert-warning border-0 shadow-sm p-3 mb-0" role="alert" style="border-radius: var(--radius-sm);">
                                            <p class="mb-2 small"><i class="fa-solid fa-circle-info me-1"></i> You must be logged in to checkout.</p>
                                            <a href="login.php" class="btn btn-primary btn-sm w-100 btn-flat py-2">Sign In to Purchase</a>
                                        </div>
                                    ';
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <?php $pdo->close(); ?>
    <?php include 'includes/footer.php'; ?>

</div>

<script>
    var total = 0;

    $(function () {
        // Remove an item from the cart
        $(document).on('click', '.cart_delete', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            $.ajax({
                type: 'POST',
                url: 'ajax_action.php?action=cart_delete',
                data: { id: id },
                dataType: 'json',
                success: function (response) {
                    if (!response.error) {
                        getDetails();
                        getCart();
                        getTotal();
                    }
                }
            });
        });

        // Decrease the quantity of a product in the cart
        $(document).on('click', '.minus', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            var qty = $('#qty_' + id).val();
            if (qty > 1) qty--;
            $('#qty_' + id).val(qty);
            $.ajax({
                type: 'POST',
                url: 'ajax_action.php?action=cart_update',
                data: { id: id, qty: qty },
                dataType: 'json',
                success: function (response) {
                    if (!response.error) {
                        getDetails();
                        getCart();
                        getTotal();
                    }
                }
            });
        });

        // Increase the quantity of a product in the cart
        $(document).on('click', '.add', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            var qty = $('#qty_' + id).val();
            qty++;
            $('#qty_' + id).val(qty);
            $.ajax({
                type: 'POST',
                url: 'ajax_action.php?action=cart_update',
                data: { id: id, qty: qty },
                dataType: 'json',
                success: function (response) {
                    if (!response.error) {
                        getDetails();
                        getCart();
                        getTotal();
                    }
                }
            });
        });

        // Initial function calls to load cart details and total
        getDetails();
        getTotal();
    });

    // Function to get the cart details and render them in the table
    function getDetails() {
        $.ajax({
            type: 'POST',
            url: 'ajax_action.php?action=cart_details',
            dataType: 'json',
            success: function (response) {
                $('#tbody').html(response);
                getCart();
            }
        });
    }

    // Function to get the total amount of the cart in INR
    function getTotal() {
        $.ajax({
            type: 'POST',
            url: 'ajax_action.php?action=cart_total',
            dataType: 'json',
            success: function (response) {
                total = response;  // Store the total price
                // Update the total price on the page
                $('#cart-total').html(response);
                $('#cart-estimated-total').html(response);
            }
        });
    }
</script>
</body>
</html>
