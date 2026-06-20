<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include 'includes/session.php';

$code = $_GET['code'] ?? '';
$user_id = $_GET['user'] ?? '';

// ----------------------------------------------------
// 1. Password Reset Flow (User has code and user ID)
// ----------------------------------------------------
if ($code !== '' && $user_id !== '') {
    // A. Handle Form Submission (POST)
    if (isset($_POST['reset_password_submit'])) {
        $password = $_POST['password'] ?? '';
        $repassword = $_POST['repassword'] ?? '';
        $path = 'password_reset.php?code=' . urlencode($code) . '&user=' . urlencode($user_id);

        if ($password !== $repassword) {
            $_SESSION['error'] = 'Passwords did not match';
            header('location: ' . $path);
            exit();
        }

        $conn = $pdo->open();
        try {
            $stmt = $conn->prepare("SELECT * FROM users WHERE reset_code=:code AND id=:id");
            $stmt->execute(['code' => $code, 'id' => $user_id]);
            $row = $stmt->fetch();

            if ($row !== false) {
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE users SET password=:password, reset_code=NULL WHERE id=:id");
                $stmt->execute(['password' => $hashed, 'id' => $row['id']]);

                $_SESSION['success'] = 'Password successfully reset. You can now login.';
                $pdo->close();
                header('location: login.php');
                exit();
            } else {
                $_SESSION['error'] = 'Code did not match with user or reset link has expired.';
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = $e->getMessage();
        }
        $pdo->close();
        header('location: ' . $path);
        exit();
    }

    // B. Show Reset Form (GET)
    include 'includes/header.php';
    ?>
    <body class="hold-transition login-page bg-light d-flex align-items-center justify-content-center min-vh-100">
    <div class="card border-light shadow-lg p-4" style="width: 100%; max-width: 440px; border-radius: var(--radius-md);">
        <div class="card-body p-0">
            <div class="text-center mb-4">
                <a href="index.php" class="text-decoration-none">
                    <img src="images/pavitra_logo.png" alt="Logo" style="height: 60px; margin-bottom: 8px;">
                    <h3 class="brand-text text-uppercase mb-0" style="font-size: 22px !important; letter-spacing: 1px;"><span class="color-change"><?php echo !empty($settings['site_name']) ? htmlspecialchars($settings['site_name']) : 'Pavitra Designer'; ?></span></h3>
                </a>
            </div>
            
            <p class="text-center font-weight-bold text-muted mb-4" style="font-family: var(--font-sans); font-size: 15px; letter-spacing: 0.5px;">Enter new password</p>

            <?php
            if (isset($_SESSION['error'])) {
                echo "
                  <div class='alert alert-danger text-center shadow-sm py-2 mb-3'>
                    <p class='mb-0 small'>" . $_SESSION['error'] . "</p> 
                  </div>
                ";
                unset($_SESSION['error']);
            }
            ?>

            <form action="password_reset.php?code=<?php echo htmlspecialchars($code); ?>&user=<?php echo htmlspecialchars($user_id); ?>" method="POST">
                <div class="mb-3">
                    <label for="password" class="form-label small text-muted">New password</label>
                    <input type="password" class="form-control" name="password" placeholder="New password" required style="height: 46px;">
                </div>
                <div class="mb-4">
                    <label for="repassword" class="form-label small text-muted">Re-type password</label>
                    <input type="password" class="form-control" name="repassword" placeholder="Re-type password" required style="height: 46px;">
                </div>
                <button type="submit" class="btn btn-primary w-100 btn-flat py-2" name="reset_password_submit" style="height: 46px;"><i class="fa fa-check-square-o"></i> Reset Password</button>
            </form>
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
    <?php
    exit();
}

// ----------------------------------------------------
// 2. Forgot Password Link Request Flow (No code / user)
// ----------------------------------------------------

// A. Handle Form Submission (POST)
if (isset($_POST['reset_request_submit'])) {
    $email = $_POST['email'] ?? '';
    $conn = $pdo->open();

    try {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email=:email");
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch();

        if ($row !== false) {
            $set = '123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $code = substr(str_shuffle($set), 0, 15);
            
            $stmt = $conn->prepare("UPDATE users SET reset_code=:code WHERE id=:id");
            $stmt->execute(['code' => $code, 'id' => $row['id']]);

            $message = '
            <div style="font-family: Arial, sans-serif; background: #f7f7f7; padding: 20px;">
              <div style="max-width: 500px; margin: auto; background: #ffffff; border-radius: 8px; padding: 30px; border: 1px solid #e5e5e5;">
                <h2 style="color: #333; text-align:center; margin-bottom: 20px;">Password Reset Request</h2>
                <p style="font-size: 15px; color: #555;">
                  We received a request to reset the password for your account associated with:
                </p>
                <p style="font-size: 16px; font-weight: bold; color: #222; text-align:center;">' . htmlspecialchars($email) . '</p>
                <p style="font-size: 15px; color: #555; margin-top: 25px;">
                  Click the button below to reset your password:
                </p>
                <div style="text-align:center; margin: 30px 0;">
                  <a href="https://pavitradesigner.com/password_reset.php?code=' . $code . '&user=' . $row['id'] . '" 
                  style="background: #0a66c2; color: #fff; padding: 12px 22px; text-decoration:none; border-radius: 6px; font-size: 16px;">
                    Reset Password
                  </a>
                </div>
                <p style="font-size: 14px; color: #777; text-align:center;">
                  If you did not request a password reset, please ignore this email.
                </p>
              </div>
            </div>';

            require 'vendor/autoload.php';

            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = !empty($settings['smtp_host']) ? $settings['smtp_host'] : 'smtp.hostinger.com';
                $mail->SMTPAuth = true;
                $mail->Username = !empty($settings['smtp_user']) ? $settings['smtp_user'] : 'support@pavitradesigner.com';
                $mail->Password = !empty($settings['smtp_pass']) ? $settings['smtp_pass'] : 'R@vin@@1807';

                $mail->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                );
                $mail->SMTPSecure = 'ssl';
                $mail->Port = 465;

                $mail->setFrom(!empty($settings['smtp_user']) ? $settings['smtp_user'] : 'support@pavitradesigner.com', (!empty($settings['site_name']) ? $settings['site_name'] : 'Pavitra Designer') . ' Support');
                $mail->addAddress($email);
                $mail->addReplyTo(!empty($settings['smtp_user']) ? $settings['smtp_user'] : 'support@pavitradesigner.com', 'Support Team');

                $mail->isHTML(true);
                $mail->Subject = (!empty($settings['site_name']) ? $settings['site_name'] : 'Pavitra Designer') . ' Password Reset';
                $mail->Body    = $message;

                $mail->send();
                $_SESSION['success'] = 'Password reset link sent to your email.';
            } catch (Exception $e) {
                $_SESSION['error'] = 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
            }
        } else {
            $_SESSION['error'] = 'Email not found';
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = $e->getMessage();
    }
    
    $pdo->close();
    header('location: password_reset.php');
    exit();
}

// B. Show Request Reset Link Form (GET)
include 'includes/header.php';
?>
<body class="hold-transition login-page bg-light d-flex align-items-center justify-content-center min-vh-100">
<div class="card border-light shadow-lg p-4" style="width: 100%; max-width: 440px; border-radius: var(--radius-md);">
    <div class="card-body p-0">
        <div class="text-center mb-4">
            <a href="index.php" class="text-decoration-none">
                <img src="images/pavitra_logo.png" alt="Logo" style="height: 60px; margin-bottom: 8px;">
                <h3 class="brand-text text-uppercase mb-0" style="font-size: 22px !important; letter-spacing: 1px;"><span class="color-change"><?php echo !empty($settings['site_name']) ? htmlspecialchars($settings['site_name']) : 'Pavitra Designer'; ?></span></h3>
            </a>
        </div>
        
        <p class="text-center font-weight-bold text-muted mb-4" style="font-family: var(--font-sans); font-size: 15px; letter-spacing: 0.5px;">Enter email associated with account</p>

        <?php
        if (isset($_SESSION['error'])) {
            echo "
              <div class='alert alert-danger text-center shadow-sm py-2 mb-3'>
                <p class='mb-0 small'>" . $_SESSION['error'] . "</p> 
              </div>
            ";
            unset($_SESSION['error']);
        }
        if (isset($_SESSION['success'])) {
            echo "
              <div class='alert alert-success text-center shadow-sm py-2 mb-3'>
                <p class='mb-0 small'>" . $_SESSION['success'] . "</p> 
              </div>
            ";
            unset($_SESSION['success']);
        }
        ?>

        <form action="password_reset.php" method="POST">
            <div class="mb-4">
                <label for="email" class="form-label small text-muted">Email address</label>
                <input type="email" class="form-control px-3" name="email" id="email" placeholder="Email" required style="height: 46px;">
            </div>
            <button type="submit" class="btn btn-primary w-100 btn-flat py-2" name="reset_request_submit" style="height: 46px;"><i class="fa fa-mail-forward"></i> Send Reset Link</button>
        </form>
        
        <div class="text-center mt-4 border-top border-light-subtle pt-3">
            <a href="login.php" class="small text-decoration-none">I remembered my password</a>
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