<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'includes/session.php';

// Handle POST request for signup
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signup'])) {
    require 'vendor/autoload.php';

    function redirect_with_message($type, $message, $data = []) {
        $_SESSION[$type] = $message;
        if ($data) $_SESSION['form_data'] = $data;
        header('Location: register.php');
        exit();
    }

    function generate_activation_code($length = 30) {
        return bin2hex(random_bytes($length / 2));
    }

    // Sanitize Inputs
    $firstname  = htmlspecialchars(trim($_POST['firstname'] ?? ''), ENT_QUOTES, 'UTF-8');
    $lastname   = htmlspecialchars(trim($_POST['lastname'] ?? ''), ENT_QUOTES, 'UTF-8');
    $email      = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $password   = trim($_POST['password'] ?? '');
    $repassword = trim($_POST['repassword'] ?? '');

    $form_data = ['firstname' => $firstname, 'lastname' => $lastname, 'email' => $email];

    // Validation
    if (!$firstname || !$lastname || !$email || !$password || !$repassword) {
        redirect_with_message('error', 'All fields are required.', $form_data);
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        redirect_with_message('error', 'Invalid email format.', $form_data);
    }

    if ($password !== $repassword) {
        redirect_with_message('error', 'Passwords do not match.', $form_data);
    }

    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password)) {
        redirect_with_message('error', 'Password must be at least 8 characters and include uppercase, lowercase, number, and special character.', $form_data);
    }

    $conn = $pdo->open();

    // Check email existence
    try {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        if ($stmt->rowCount() > 0) {
            $pdo->close();
            redirect_with_message('error', 'This email is already registered.', $form_data);
        }
    } catch (PDOException $e) {
        error_log('Database error checking email: ' . $e->getMessage());
        $pdo->close();
        redirect_with_message('error', 'An internal error occurred. Please try again later.', $form_data);
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $activation_code = generate_activation_code();

    try {
        $stmt = $conn->prepare("
            INSERT INTO users (firstname, lastname, email, password, status, activate_code, created_on)
            VALUES (:firstname, :lastname, :email, :password, 0, :activate_code, NOW())
        ");
        $stmt->execute([
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'password' => $hashed_password,
            'activate_code' => $activation_code
        ]);
    } catch (PDOException $e) {
        error_log('Database error inserting user: ' . $e->getMessage());
        $pdo->close();
        redirect_with_message('error', 'An internal error occurred. Please try again later.', $form_data);
    }

    $pdo->close();

    // ✅ Send Activation Email (Hostinger SMTP)
    try {
        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host       = !empty($settings['smtp_host']) ? $settings['smtp_host'] : 'smtp.hostinger.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = !empty($settings['smtp_user']) ? $settings['smtp_user'] : 'support@pavitradesigner.com';
        $mail->Password   = !empty($settings['smtp_pass']) ? $settings['smtp_pass'] : 'R@vin@@1807';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            ],
        ];

        $mail->setFrom(!empty($settings['smtp_user']) ? $settings['smtp_user'] : 'support@pavitradesigner.com', !empty($settings['site_name']) ? $settings['site_name'] : 'Pavitra Designer');
        $mail->addAddress($email, "{$firstname} {$lastname}");
        $mail->isHTML(true);
        $mail->Subject = 'Activate Your ' . (!empty($settings['site_name']) ? $settings['site_name'] : 'Pavitra Designer') . ' Account';

        $activation_link = "https://pavitradesigner.com/login.php?code={$activation_code}";

        $mail->Body = "
            <h2>Hi {$firstname},</h2>
            <p>Thank you for registering at <strong>" . (!empty($settings['site_name']) ? htmlspecialchars($settings['site_name']) : 'Pavitra Designer') . "</strong>.</p>
            <p>Please activate your account by clicking the link below:</p>
            <p><a href='{$activation_link}' style='background:#4CAF50;color:white;padding:10px 20px;border-radius:5px;text-decoration:none;'>Activate Account</a></p>
            <br><p>Best regards,<br>" . (!empty($settings['site_name']) ? htmlspecialchars($settings['site_name']) : 'Pavitra Designer') . " Team</p>
        ";

        $mail->AltBody = "Hi {$firstname},\n\nThank you for registering at " . (!empty($settings['site_name']) ? $settings['site_name'] : 'Pavitra Designer') . ".\nActivate your account here: {$activation_link}\n\nBest regards,\n" . (!empty($settings['site_name']) ? $settings['site_name'] : 'Pavitra Designer') . " Team";

        $mail->send();
    } catch (Exception $e) {
        error_log("Mailer Error: " . $mail->ErrorInfo);
    }

    redirect_with_message('success', 'Registration successful! Please check your email to activate your account.');
    exit();
}

// Redirect logged-in users
if (!empty($_SESSION['user'])) {
    header('Location: cart_view.php');
    exit();
}
if (!empty($_SESSION['admin'])) {
    header('Location: admin/home.php');
    exit();
}

include 'includes/header.php'; 
?>

<body class="bg-light d-flex align-items-center justify-content-center min-vh-100 py-5">
<div class="card border-light shadow-lg p-4 my-5" style="width: 100%; max-width: 480px; border-radius: var(--radius-md);">
    <div class="card-body p-0">
        <!-- Logo Header -->
        <div class="text-center mb-4">
            <a href="index.php" class="text-decoration-none">
                <img src="images/pavitra_logo.png" alt="Logo" style="height: 60px; margin-bottom: 8px;">
                <h3 class="brand-text text-uppercase mb-0" style="font-size: 22px !important; letter-spacing: 1px;"><span class="color-change"><?php echo !empty($settings['site_name']) ? htmlspecialchars($settings['site_name']) : 'Pavitra Designer'; ?></span></h3>
            </a>
        </div>

        <h4 class="text-center font-weight-bold text-muted mb-4" style="font-family: var(--font-sans); font-size: 15px; letter-spacing: 0.5px;">Register a new membership</h4>

        <!-- Display error message -->
        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-danger text-center shadow-sm py-2 mb-3">
                <p class="mb-0 small"><?= htmlspecialchars($_SESSION['error']); ?></p>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <!-- Display success message -->
        <?php if (!empty($_SESSION['success'])): ?>
            <div class="alert alert-success text-center shadow-sm py-2 mb-3">
                <p class="mb-0 small"><?= htmlspecialchars($_SESSION['success']); ?></p>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <form action="register.php" method="POST">
            <div class="row g-2 mb-3">
                <div class="col-md-6">
                    <label for="firstname" class="form-label small text-muted">First name</label>
                    <input type="text" class="form-control" name="firstname" id="firstname" placeholder="Firstname" value="<?= htmlspecialchars($_SESSION['form_data']['firstname'] ?? '') ?>" required style="height: 46px;">
                </div>
                <div class="col-md-6">
                    <label for="lastname" class="form-label small text-muted">Last name</label>
                    <input type="text" class="form-control" name="lastname" id="lastname" placeholder="Lastname" value="<?= htmlspecialchars($_SESSION['form_data']['lastname'] ?? '') ?>" required style="height: 46px;">
                </div>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label small text-muted">Email address</label>
                <input type="email" class="form-control" name="email" id="email" placeholder="name@example.com" value="<?= htmlspecialchars($_SESSION['form_data']['email'] ?? '') ?>" required style="height: 46px;">
            </div>

            <div class="mb-3">
                <label for="password" class="form-label small text-muted">Password</label>
                <input type="password" class="form-control" name="password" id="password" placeholder="••••••••" required autocomplete="off" style="height: 46px;">
            </div>

            <div class="mb-4">
                <label for="repassword" class="form-label small text-muted">Confirm Password</label>
                <input type="password" class="form-control" name="repassword" id="repassword" placeholder="••••••••" required autocomplete="off" style="height: 46px;">
            </div>

            <?php
            // Clear form data after rendering
            if (!empty($_SESSION['form_data'])) {
                unset($_SESSION['form_data']);
            }
            ?>

            <button type="submit" class="btn btn-primary w-100 btn-flat py-2 mb-3" name="signup" style="height: 46px;">
                <i class="fa-solid fa-user-plus me-2"></i> Sign Up
            </button>
        </form>

        <div class="text-center mt-4 border-top border-light-subtle pt-3">
            <p class="small text-muted mb-2">Already have an account?</p>
            <a href="login.php" class="btn btn-outline-secondary w-100 btn-flat btn-sm py-2" style="font-weight: 600;">Sign In Instead</a>
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
