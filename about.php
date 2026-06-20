<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>

<body class="hold-transition skin-blue layout-top-nav">
  <div class="wrapper">
  
    <?php include 'includes/navbar.php'; ?>
  
    <div class="content-wrapper">
      <div class="container">
  
        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-sm-12">
  
              <!-- Display Error Messages -->
              <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                  <?= $_SESSION['error']; ?>
                </div>
                <?php unset($_SESSION['error']); ?>
              <?php endif; ?>
  
              <!-- About Us Content -->
              <div class="box box-solid" style="padding: 30px; border-radius: var(--radius-lg) !important; border: 1px solid var(--border-color) !important;">
                <div class="box-body" style="padding: 0;">
                  <h2 class="text-center" style="font-family: var(--font-serif); color: var(--primary-color); font-size: 32px; margin-bottom: 25px;"><b>About Us</b></h2>
                  
                  <p class="about-us-description" style="font-size: 16px; line-height: 1.8; color: var(--text-color); margin-bottom: 25px;">
                    Founded in 2025, <b><?php echo !empty($settings['site_name']) ? htmlspecialchars($settings['site_name']) : 'Pavitra Designer'; ?></b> is an online business specializing in premium designer sarees.
                    We started with a passion to showcase India’s traditional handwork to the modern world. Today, we are
                    proudly operated under our parent company <b><?php echo !empty($settings['parent_company']) ? htmlspecialchars($settings['parent_company']) : 'SSSS'; ?></b> and trusted by customers across India.
                  </p>
   
                  <h3 style="font-family: var(--font-serif); color: var(--primary-color); margin-top: 30px; margin-bottom: 12px;"><b>Our Mission</b></h3>
                  <p class="about-us-description" style="font-size: 15px; line-height: 1.8; color: var(--text-color); margin-bottom: 25px;">
                    To offer authentic, high-quality sarees that blend tradition with modern elegance – making every occasion
                    memorable for our customers.
                  </p>
   
                  <h3 style="font-family: var(--font-serif); color: var(--primary-color); margin-top: 30px; margin-bottom: 12px;"><b>Our Values</b></h3>
                  <ul class="about-us-list" style="font-size: 15px; line-height: 2; color: var(--text-color); padding-left: 20px; list-style-type: none; margin-bottom: 25px;">
                    <li style="margin-bottom: 8px;"><i class="fa fa-check-circle" style="color: var(--accent-color); margin-right: 8px;"></i> <b>Authenticity</b> – Genuine fabrics & craftsmanship</li>
                    <li style="margin-bottom: 8px;"><i class="fa fa-check-circle" style="color: var(--accent-color); margin-right: 8px;"></i> <b>Quality</b> – Premium finishing with attention to detail</li>
                    <li style="margin-bottom: 8px;"><i class="fa fa-check-circle" style="color: var(--accent-color); margin-right: 8px;"></i> <b>Customer First</b> – 100% customer satisfaction</li>
                    <li style="margin-bottom: 8px;"><i class="fa fa-check-circle" style="color: var(--accent-color); margin-right: 8px;"></i> <b>Sustainability</b> – Eco-friendly practices & materials</li>
                  </ul>
   
                  <h3 style="font-family: var(--font-serif); color: var(--primary-color); margin-top: 30px; margin-bottom: 12px;"><b>Why Choose Us?</b></h3>
                  <ul class="about-us-list" style="font-size: 15px; line-height: 2; color: var(--text-color); padding-left: 20px; list-style-type: none; margin-bottom: 25px;">
                    <li style="margin-bottom: 8px;"><i class="fa fa-star" style="color: var(--accent-color); margin-right: 8px;"></i> Exclusive handpicked saree collections</li>
                    <li style="margin-bottom: 8px;"><i class="fa fa-star" style="color: var(--accent-color); margin-right: 8px;"></i> Perfect sarees for weddings, festivals, and parties</li>
                    <li style="margin-bottom: 8px;"><i class="fa fa-star" style="color: var(--accent-color); margin-right: 8px;"></i> Affordable luxury with nationwide delivery</li>
                    <li style="margin-bottom: 8px;"><i class="fa fa-star" style="color: var(--accent-color); margin-right: 8px;"></i> Trusted by <b>1000+ happy customers</b></li>
                  </ul>
   
                  <h3 style="font-family: var(--font-serif); color: var(--primary-color); margin-top: 30px; margin-bottom: 12px;"><b>Our Social Media</b></h3>
                  <p style="font-size: 15px; color: var(--text-color);">Stay connected with us for updates on our latest collections:</p>
                  <div style="margin-top: 15px; margin-bottom: 30px;">
                    <a href="<?php echo !empty($settings['instagram_link']) ? htmlspecialchars($settings['instagram_link']) : 'https://www.instagram.com/pavitra_designer?igsh=b3hic3ZuZzB2NHU0&utm_source=qr'; ?>" target="_blank" class="btn btn-primary btn-flat" style="margin-right: 10px; padding: 10px 20px; font-size: 13px;"><i class="fa fa-instagram"></i> Instagram</a>
                    <a href="<?php echo !empty($settings['youtube_link']) ? htmlspecialchars($settings['youtube_link']) : 'https://www.youtube.com/@PavitraDesignersaree'; ?>" target="_blank" class="btn btn-danger btn-flat" style="padding: 10px 20px; font-size: 13px;"><i class="fa fa-youtube-play"></i> YouTube</a>
                  </div>
   
                  <h3 style="font-family: var(--font-serif); color: var(--primary-color); margin-top: 30px; margin-bottom: 20px;"><b>What Our Customers Say</b></h3>
                  <blockquote class="customer-review" style="font-style: italic; border-left: 4px solid var(--accent-color); padding: 10px 20px; margin-bottom: 20px; background: var(--bg-color); border-radius: 0 var(--radius-sm) var(--radius-sm) 0;">
                    "Amazing quality! The silk saree I bought was stunning and elegant." <span style="display: block; margin-top: 5px; font-weight: 600; font-style: normal; color: var(--primary-color); font-size: 13px;">– Priya, Jaipur</span>
                  </blockquote>
                  <blockquote class="customer-review" style="font-style: italic; border-left: 4px solid var(--accent-color); padding: 10px 20px; margin-bottom: 20px; background: var(--bg-color); border-radius: 0 var(--radius-sm) var(--radius-sm) 0;">
                    "Fast delivery and authentic designs. I’m definitely shopping again!" <span style="display: block; margin-top: 5px; font-weight: 600; font-style: normal; color: var(--primary-color); font-size: 13px;">– Riya, Mumbai</span>
                  </blockquote>
                </div>
              </div>
  
            </div>
          </div>
        </section>
  
      </div>
    </div>
  
  </div><br><br>
  
  <?php include 'includes/footer.php'; ?>
  
</body>
<style>
    .about-us-description {
    font-size: 1.2em;
    color: #555;
}

.about-us-list {
    list-style-type: disc;
    padding-left: 20px;
}

.customer-review {
    font-style: italic;
    color: #333;
}

.box.box-solid {
    padding: 20px; /* Adjust the padding as needed */
    margin: 20px 0; /* Adjust the margin as needed */
    border: 1px solid #ddd; /* Optional: Adds a border to define the box */
    border-radius: 8px; /* Optional: Rounded corners */
    background-color: #fff; /* Optional: Background color */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Optional: Shadow for depth */
}

</style>
</html>


