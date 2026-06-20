<footer class="main-footer py-5 mt-5">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-3 col-md-6">
        <h4 class="footer-title"><?php echo !empty($settings['site_name']) ? htmlspecialchars($settings['site_name']) : 'Pavitra Designer'; ?></h4>
        <p class="footer-text">
          <?php echo !empty($settings['about_text']) ? htmlspecialchars($settings['about_text']) : 'Exclusive collection of premium traditional handwork sarees representing the rich Indian heritage. Handcrafted with passion, bringing you authentic designs for weddings, festivals, and special occasions.'; ?>
        </p>
        <p class="footer-text" style="font-size: 13px; color: var(--accent-color);">
          Operated under parent company <b><?php echo !empty($settings['parent_company']) ? htmlspecialchars($settings['parent_company']) : 'SSSS'; ?></b>.
        </p>
      </div>
      <div class="col-lg-2 col-md-6">
        <h4 class="footer-title">Quick Links</h4>
        <ul class="footer-links list-unstyled">
          <li><a href="index.php"><i class="fa-solid fa-angle-right me-2"></i>Home</a></li>
          <li><a href="about.php"><i class="fa-solid fa-angle-right me-2"></i>About Us</a></li>
          <li><a href="contact.php"><i class="fa-solid fa-angle-right me-2"></i>Contact Us</a></li>
          <li><a href="cart_view.php"><i class="fa-solid fa-angle-right me-2"></i>Shopping Cart</a></li>
        </ul>
      </div>
      <div class="col-lg-3 col-md-6">
        <h4 class="footer-title">Contact & Socials</h4>
        <p class="footer-text mb-3">
          <i class="fa-solid fa-envelope me-2" style="color: var(--accent-color);"></i> <?php echo !empty($settings['email']) ? htmlspecialchars($settings['email']) : 'support@pavitradesigner.com'; ?>
        </p>
        <p class="footer-text mb-3" style="font-size: 13px;">
          Follow us for the latest collections:
        </p>
        <div class="d-flex flex-wrap gap-2">
          <a class="btn-social-icon" href="<?php echo !empty($settings['facebook_link']) ? htmlspecialchars($settings['facebook_link']) : '#'; ?>" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
          <a class="btn-social-icon" href="<?php echo !empty($settings['twitter_link']) ? htmlspecialchars($settings['twitter_link']) : '#'; ?>" aria-label="Twitter"><i class="fa-brands fa-x-twitter"></i></a>
          <a class="btn-social-icon" href="<?php echo !empty($settings['instagram_link']) ? htmlspecialchars($settings['instagram_link']) : '#'; ?>" target="_blank" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
          <a class="btn-social-icon" href="<?php echo !empty($settings['youtube_link']) ? htmlspecialchars($settings['youtube_link']) : '#'; ?>" target="_blank" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a>
          <a class="btn-social-icon" href="<?php echo !empty($settings['linkedin_link']) ? htmlspecialchars($settings['linkedin_link']) : '#'; ?>" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
        </div>
      </div>
      <div class="col-lg-4 col-md-6">
        <h4 class="footer-title">Become a Subscriber</h4>
        <p class="footer-text mb-3" style="font-size: 13px;">
          Get free updates on the latest products and discounts, straight to your inbox.
        </p>
        <form method="POST" action="">
          <div class="input-group">
            <input type="email" class="form-control" placeholder="Your email address" aria-label="Subscriber Email" required style="border-radius: var(--radius-sm) 0 0 var(--radius-sm) !important;">
            <button type="submit" class="btn btn-info" style="border-radius: 0 var(--radius-sm) var(--radius-sm) 0 !important; border: 1px solid var(--accent-color);"><i class="fa-solid fa-envelope"></i></button>
          </div>
        </form>
      </div>
    </div>
    
    <div class="row footer-bottom border-top border-secondary-subtle pt-4 mt-4">
      <div class="col-xs-12 text-center footer-bottom-text">
        <p class="mb-0">&copy; <?php echo date('Y'); ?> <a href="index.php"><?php echo !empty($settings['site_name']) ? htmlspecialchars($settings['site_name']) : 'Pavitra Designer'; ?></a>. All rights reserved.</p>
      </div>
    </div>
  </div>
</footer>

<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 5.3.3 Bundle (includes Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<!-- DataTables -->
<script src="bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<!-- CK Editor -->
<script src="bower_components/ckeditor/ckeditor.js"></script>
<!-- Magnify -->
<script src="magnify/magnify.min.js"></script>
<!-- Custom JS -->
<script src="dist/js/script.js"></script>