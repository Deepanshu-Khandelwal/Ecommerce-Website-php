<?php
include 'includes/session.php';

// Handle account activation code
if (isset($_GET['code'])) {
    $code = $_GET['code'];
    $conn = $pdo->open();
    try {
        $stmt = $conn->prepare("SELECT id, status FROM users WHERE activate_code = :code");
        $stmt->execute(['code' => $code]);
        $act_user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$act_user) {
            $_SESSION['error'] = "Invalid activation code.";
        } else if ($act_user['status'] == 1) {
            $_SESSION['success'] = "Your account is already activated. Please log in.";
        } else {
            $stmt = $conn->prepare("UPDATE users SET status = 1, activate_code = NULL WHERE id = :id");
            $stmt->execute(['id' => $act_user['id']]);
            $_SESSION['success'] = "Your account has been activated! You can now log in.";
        }
    } catch (PDOException $e) {
        error_log('Activation error: ' . $e->getMessage());
        $_SESSION['error'] = "An error occurred during account activation.";
    }
    $pdo->close();
    header('Location: login.php');
    exit();
}

// Handle POST request for login
if (isset($_POST['login'])) {
    $conn = $pdo->open();
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    try {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch();

        if ($row !== false) {
            if ($row['status']) {
                if (password_verify($password, $row['password'])) {
                    if ($row['type']) {
                        $_SESSION['admin'] = $row['id'];
                        $pdo->close();
                        header('location: admin/home.php');
                        exit();
                    } else {
                        $_SESSION['user'] = $row['id'];
                        $pdo->close();
                        header('location: index.php');
                        exit();
                    }
                } else {
                    $_SESSION['error'] = 'Incorrect Password';
                }
            } else {
                $_SESSION['error'] = 'Account not activated.';
            }
        } else {
            $_SESSION['error'] = 'Email not found';
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Connection error: ' . $e->getMessage();
    }
    $pdo->close();
    header('location: login.php');
    exit();
}

// Redirect logged-in users
if (isset($_SESSION['user'])) {
    header('location: index.php');
    exit();
}
if (isset($_SESSION['admin'])) {
    header('location: admin/home.php');
    exit();
}

include 'includes/header.php';
?>
<body class="bg-light d-flex align-items-center justify-content-center min-vh-100">

<div class="card border-light shadow-lg p-4" style="width: 100%; max-width: 440px; border-radius: var(--radius-md);">
  	<div class="card-body p-0">
        <!-- Logo Header -->
        <div class="text-center mb-4">
            <a href="index.php" class="text-decoration-none">
                <img src="images/pavitra_logo.png" alt="Logo" style="height: 60px; margin-bottom: 8px;">
                <h3 class="brand-text text-uppercase mb-0" style="font-size: 22px !important; letter-spacing: 1px;"><span class="color-change"><?php echo !empty($settings['site_name']) ? htmlspecialchars($settings['site_name']) : 'Pavitra Designer'; ?></span></h3>
            </a>
        </div>

    	<h4 class="text-center font-weight-bold text-muted mb-4" style="font-family: var(--font-sans); font-size: 15px; letter-spacing: 0.5px;">Sign in to start your session</h4>

        <?php
          if(isset($_SESSION['error'])){
            echo "
              <div class='alert alert-danger text-center shadow-sm py-2 mb-3'>
                <p class='mb-0 small'>".$_SESSION['error']."</p> 
              </div>
            ";
            unset($_SESSION['error']);
          }
          if(isset($_SESSION['success'])){
            echo "
              <div class='alert alert-success text-center shadow-sm py-2 mb-3'>
                <p class='mb-0 small'>".$_SESSION['success']."</p> 
              </div>
            ";
            unset($_SESSION['success']);
          }
        ?>

    	<form action="login.php" method="POST">
      		<div class="mb-3 position-relative">
                <label for="email" class="form-label small text-muted">Email address</label>
        		<input type="email" class="form-control px-3" name="email" id="email" placeholder="name@example.com" required style="height: 46px;">
      		</div>
            <div class="mb-4 position-relative">
                <div class="d-flex justify-content-between">
                    <label for="password" class="form-label small text-muted">Password</label>
                    <a href="password_reset.php" class="small text-decoration-none" style="margin-top: 0; font-size: 13px;">Forgot?</a>
                </div>
                <input type="password" class="form-control px-3" name="password" id="password" placeholder="••••••••" required style="height: 46px;">
            </div>
  			<button type="submit" class="btn btn-primary w-100 btn-flat py-2" name="login" style="height: 46px;"><i class="fa-solid fa-right-to-bracket me-2"></i> Sign In</button>
    	</form>

        <div class="text-center mt-4 border-top border-light-subtle pt-3">
            <p class="small text-muted mb-2">New to Pavitra Designer?</p>
            <a href="register.php" class="btn btn-outline-secondary w-100 btn-flat btn-sm py-2" style="font-weight: 600;">Create Account</a>
        </div>
        
        <div class="text-center mt-3">
            <a href="index.php" class="small text-muted text-decoration-none"><i class="fa-solid fa-house me-1"></i> Back to Home</a>
        </div>
  	</div>
</div>
	
<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 5.3.3 Bundle (includes Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<!-- Custom JS -->
<script src="dist/js/script.js"></script>
</body>
</html>