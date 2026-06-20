<?php
include 'includes/session.php';

// Only Super Admins can access this page
if($admin['type'] != 2){
    header('location: home.php');
    exit();
}

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_settings'])){
    $site_name = $_POST['site_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    $parent_company = $_POST['parent_company'] ?? '';
    $about_text = $_POST['about_text'] ?? '';
    $facebook_link = $_POST['facebook_link'] ?? '';
    $twitter_link = $_POST['twitter_link'] ?? '';
    $instagram_link = $_POST['instagram_link'] ?? '';
    $youtube_link = $_POST['youtube_link'] ?? '';
    $linkedin_link = $_POST['linkedin_link'] ?? '';
    $smtp_host = $_POST['smtp_host'] ?? '';
    $smtp_user = $_POST['smtp_user'] ?? '';
    $smtp_pass = $_POST['smtp_pass'] ?? '';
    $razorpay_key_id = $_POST['razorpay_key_id'] ?? '';
    $razorpay_key_secret = $_POST['razorpay_key_secret'] ?? '';

    $logo = $settings['logo'] ?? '';
    if(!empty($_FILES['logo']['name'])){
        $ext = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
        $allowed_ext = ['png', 'jpg', 'jpeg', 'gif', 'svg', 'webp'];
        if(in_array(strtolower($ext), $allowed_ext)){
            $logo_filename = 'logo_' . time() . '.' . $ext;
            if(move_uploaded_file($_FILES['logo']['tmp_name'], '../images/' . $logo_filename)){
                $logo = $logo_filename;
            } else {
                $_SESSION['error'] = 'Failed to upload logo image';
            }
        } else {
            $_SESSION['error'] = 'Invalid image file type for logo';
        }
    }

    if(!isset($_SESSION['error'])){
        $conn = $pdo->open();
        try{
            $stmt = $conn->prepare("UPDATE site_settings SET 
                site_name = :site_name,
                logo = :logo,
                email = :email,
                phone = :phone,
                address = :address,
                parent_company = :parent_company,
                about_text = :about_text,
                facebook_link = :facebook_link,
                twitter_link = :twitter_link,
                instagram_link = :instagram_link,
                youtube_link = :youtube_link,
                linkedin_link = :linkedin_link,
                smtp_host = :smtp_host,
                smtp_user = :smtp_user,
                smtp_pass = :smtp_pass,
                razorpay_key_id = :razorpay_key_id,
                razorpay_key_secret = :razorpay_key_secret
                WHERE id = 1");
            
            $stmt->execute([
                'site_name' => $site_name,
                'logo' => $logo,
                'email' => $email,
                'phone' => $phone,
                'address' => $address,
                'parent_company' => $parent_company,
                'about_text' => $about_text,
                'facebook_link' => $facebook_link,
                'twitter_link' => $twitter_link,
                'instagram_link' => $instagram_link,
                'youtube_link' => $youtube_link,
                'linkedin_link' => $linkedin_link,
                'smtp_host' => $smtp_host,
                'smtp_user' => $smtp_user,
                'smtp_pass' => $smtp_pass,
                'razorpay_key_id' => $razorpay_key_id,
                'razorpay_key_secret' => $razorpay_key_secret
            ]);

            $_SESSION['success'] = 'Site settings updated successfully';
        }
        catch(PDOException $e){
            $_SESSION['error'] = $e->getMessage();
        }
        $pdo->close();
    }

    header('location: settings.php');
    exit();
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
        Site Settings
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Site Settings</li>
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

      <form role="form" method="POST" action="settings.php" enctype="multipart/form-data">
        <div class="row">
          <!-- Left Column -->
          <div class="col-md-6">
            
            <!-- General Settings Box -->
            <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-cogs"></i> General Settings</h3>
              </div>
              <div class="box-body">
                <div class="form-group">
                  <label for="site_name">Site Name</label>
                  <input type="text" class="form-control" id="site_name" name="site_name" value="<?php echo htmlspecialchars($settings['site_name'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                  <label for="logo">Site Logo</label>
                  <?php if(!empty($settings['logo'])): ?>
                    <div style="margin-bottom: 10px;">
                      <img src="../images/<?php echo $settings['logo']; ?>" alt="Current Logo" style="max-height: 80px; padding: 5px; border: 1px solid #ddd; background: #f9f9f9; border-radius: 4px;">
                      <p class="help-block" style="font-size:12px;">Current logo shown above</p>
                    </div>
                  <?php endif; ?>
                  <input type="file" id="logo" name="logo">
                  <p class="help-block">Upload a new image to replace the current logo (Supported: PNG, JPG, JPEG, GIF, SVG, WEBP).</p>
                </div>
              </div>
            </div>

            <!-- Company Info Box -->
            <div class="box box-info">
              <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-building"></i> Company & Description</h3>
              </div>
              <div class="box-body">
                <div class="form-group">
                  <label for="parent_company">Parent Company Name</label>
                  <input type="text" class="form-control" id="parent_company" name="parent_company" value="<?php echo htmlspecialchars($settings['parent_company'] ?? ''); ?>">
                </div>
                <div class="form-group">
                  <label for="about_text">About Us / Footer Description</label>
                  <textarea class="form-control" id="about_text" name="about_text" rows="5"><?php echo htmlspecialchars($settings['about_text'] ?? ''); ?></textarea>
                </div>
              </div>
            </div>

            <!-- Contact Details Box -->
            <div class="box box-warning">
              <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-phone"></i> Contact Details</h3>
              </div>
              <div class="box-body">
                <div class="form-group">
                  <label for="email">Contact Email</label>
                  <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($settings['email'] ?? ''); ?>">
                </div>
                <div class="form-group">
                  <label for="phone">Contact Phone</label>
                  <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($settings['phone'] ?? ''); ?>">
                </div>
                <div class="form-group">
                  <label for="address">Physical Address</label>
                  <textarea class="form-control" id="address" name="address" rows="3"><?php echo htmlspecialchars($settings['address'] ?? ''); ?></textarea>
                </div>
              </div>
            </div>

          </div>

          <!-- Right Column -->
          <div class="col-md-6">

            <!-- Social Networks Box -->
            <div class="box box-success">
              <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-share-alt"></i> Social Networks</h3>
              </div>
              <div class="box-body">
                <div class="form-group">
                  <label for="facebook_link"><i class="fa fa-facebook"></i> Facebook Link</label>
                  <input type="text" class="form-control" id="facebook_link" name="facebook_link" value="<?php echo htmlspecialchars($settings['facebook_link'] ?? ''); ?>">
                </div>
                <div class="form-group">
                  <label for="twitter_link"><i class="fa fa-twitter"></i> Twitter/X Link</label>
                  <input type="text" class="form-control" id="twitter_link" name="twitter_link" value="<?php echo htmlspecialchars($settings['twitter_link'] ?? ''); ?>">
                </div>
                <div class="form-group">
                  <label for="instagram_link"><i class="fa fa-instagram"></i> Instagram Link</label>
                  <input type="text" class="form-control" id="instagram_link" name="instagram_link" value="<?php echo htmlspecialchars($settings['instagram_link'] ?? ''); ?>">
                </div>
                <div class="form-group">
                  <label for="youtube_link"><i class="fa fa-youtube"></i> YouTube Link</label>
                  <input type="text" class="form-control" id="youtube_link" name="youtube_link" value="<?php echo htmlspecialchars($settings['youtube_link'] ?? ''); ?>">
                </div>
                <div class="form-group">
                  <label for="linkedin_link"><i class="fa fa-linkedin"></i> LinkedIn Link</label>
                  <input type="text" class="form-control" id="linkedin_link" name="linkedin_link" value="<?php echo htmlspecialchars($settings['linkedin_link'] ?? ''); ?>">
                </div>
              </div>
            </div>

            <!-- API & Security Box -->
            <div class="box box-danger">
              <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-key"></i> Security & Third-Party APIs</h3>
              </div>
              <div class="box-body">
                <h4 style="border-bottom: 1px solid #f4f4f4; padding-bottom: 8px; margin-top:0;">SMTP Mail Server Settings</h4>
                <div class="form-group">
                  <label for="smtp_host">SMTP Host</label>
                  <input type="text" class="form-control" id="smtp_host" name="smtp_host" placeholder="e.g. smtp.gmail.com" value="<?php echo htmlspecialchars($settings['smtp_host'] ?? ''); ?>">
                </div>
                <div class="form-group">
                  <label for="smtp_user">SMTP Username / Email</label>
                  <input type="text" class="form-control" id="smtp_user" name="smtp_user" placeholder="e.g. user@gmail.com" value="<?php echo htmlspecialchars($settings['smtp_user'] ?? ''); ?>">
                </div>
                <div class="form-group">
                  <label for="smtp_pass">SMTP Password</label>
                  <input type="password" class="form-control" id="smtp_pass" name="smtp_pass" value="<?php echo htmlspecialchars($settings['smtp_pass'] ?? ''); ?>">
                </div>

                <h4 style="border-bottom: 1px solid #f4f4f4; padding-bottom: 8px; margin-top:20px;">Razorpay Payment Credentials</h4>
                <div class="form-group">
                  <label for="razorpay_key_id">Razorpay Key ID</label>
                  <input type="text" class="form-control" id="razorpay_key_id" name="razorpay_key_id" placeholder="rzp_live_xxxxxxxx" value="<?php echo htmlspecialchars($settings['razorpay_key_id'] ?? ''); ?>">
                </div>
                <div class="form-group">
                  <label for="razorpay_key_secret">Razorpay Key Secret</label>
                  <input type="password" class="form-control" id="razorpay_key_secret" name="razorpay_key_secret" value="<?php echo htmlspecialchars($settings['razorpay_key_secret'] ?? ''); ?>">
                </div>
              </div>
            </div>

          </div>
        </div>

        <!-- Submit Button Row -->
        <div class="row">
          <div class="col-xs-12">
            <div class="box" style="border-top: none;">
              <div class="box-body text-center">
                <button type="submit" name="save_settings" class="btn btn-primary btn-lg btn-flat" style="padding: 10px 40px; font-weight: bold;"><i class="fa fa-save"></i> Save All Site Settings</button>
              </div>
            </div>
          </div>
        </div>
      </form>

    </section>
  </div>

  <?php include 'includes/footer.php'; ?>
</div>

<?php include 'includes/scripts.php'; ?>
</body>
</html>
