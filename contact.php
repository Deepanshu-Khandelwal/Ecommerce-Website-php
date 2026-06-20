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
            <div class="box box-solid" style="padding: 30px; border-radius: var(--radius-lg) !important; border: 1px solid var(--border-color) !important;">
              <div class="box-body" style="padding: 0;">

                <h2 class="text-center" style="font-family: var(--font-serif); color: var(--primary-color); font-size: 32px; margin-bottom: 10px;"><b>Contact Us</b></h2>
                <p class="text-center" style="font-size:16px; color: var(--text-muted); max-width: 600px; margin: 0 auto 30px auto;">
                  Have questions or need support? We’d love to hear from you!  
                  Reach out using the details below or send us a message directly.
                </p>

                <div class="row" style="margin-top:30px;">
                  <!-- Left Column: Contact Info -->
                  <div class="col-md-6 col-sm-12" style="padding-right: 40px; border-right: 1px solid var(--border-color);">
                    <h4 style="font-family: var(--font-serif); color: var(--primary-color); margin-top: 0; margin-bottom: 15px;"><b>Our Office</b></h4>
                    <p style="font-size: 15px; line-height: 1.8; color: var(--text-color); margin-bottom: 25px;">
                      <i class="fa fa-map-marker" style="color: var(--accent-color); margin-right: 8px; font-size: 18px;"></i>
                      <?php echo !empty($settings['address']) ? nl2br(htmlspecialchars($settings['address'])) : 'Pavitra Designer <br> Jaipur, Rajasthan - 302001 <br> India'; ?>
                    </p>

                    <h4 style="font-family: var(--font-serif); color: var(--primary-color); margin-bottom: 15px;"><b>Phone</b></h4>
                    <p style="font-size: 15px; margin-bottom: 25px;">
                      <i class="fa fa-phone" style="color: var(--accent-color); margin-right: 8px; font-size: 18px;"></i>
                      <a href="tel:<?php echo !empty($settings['phone']) ? htmlspecialchars($settings['phone']) : '+919950489678'; ?>" style="color: var(--text-color); font-weight: 500;"><?php echo !empty($settings['phone']) ? htmlspecialchars($settings['phone']) : '+919950489678'; ?></a>
                    </p>

                    <h4 style="font-family: var(--font-serif); color: var(--primary-color); margin-bottom: 15px;"><b>Email</b></h4>
                    <p style="font-size: 15px; margin-bottom: 25px;">
                      <i class="fa fa-envelope" style="color: var(--accent-color); margin-right: 8px; font-size: 18px;"></i>
                      <a href="mailto:<?php echo !empty($settings['email']) ? htmlspecialchars($settings['email']) : 'p14115419@gmail.com'; ?>" style="color: var(--text-color); font-weight: 500;"><?php echo !empty($settings['email']) ? htmlspecialchars($settings['email']) : 'p14115419@gmail.com'; ?></a>
                    </p>

                    <h4 style="font-family: var(--font-serif); color: var(--primary-color); margin-bottom: 15px;"><b>Follow Us</b></h4>
                    <div style="margin-top: 10px;">
                      <a href="<?php echo !empty($settings['instagram_link']) ? htmlspecialchars($settings['instagram_link']) : 'https://instagram.com/pavitra_designer'; ?>" target="_blank" class="btn-social-icon btn-instagram" style="display: inline-block; vertical-align: middle;"><i class="fa fa-instagram"></i></a>
                      <a href="<?php echo !empty($settings['youtube_link']) ? htmlspecialchars($settings['youtube_link']) : 'https://www.youtube.com/@PavitraDesignersaree'; ?>" target="_blank" class="btn-social-icon btn-youtube" style="display: inline-block; vertical-align: middle; padding: 0; text-align: center;"><i class="fa fa-youtube-play"></i></a>
                    </div>
                  </div>

                  <!-- Right Column: Contact Form -->
                  <div class="col-md-6 col-sm-12" style="padding-left: 40px;">
                    <h4 style="font-family: var(--font-serif); color: var(--primary-color); margin-top: 0; margin-bottom: 20px;"><b>Send Us a Message</b></h4>
                    <form method="post" action="send_message.php">
                      <div class="form-group">
                        <label for="name" style="font-weight: 500;">Your Name</label>
                        <input type="text" class="form-control" name="name" required style="height: 42px; border-radius: var(--radius-sm);">
                      </div>
                      <div class="form-group">
                        <label for="email" style="font-weight: 500;">Your Email</label>
                        <input type="email" class="form-control" name="email" required style="height: 42px; border-radius: var(--radius-sm);">
                      </div>
                      <div class="form-group">
                        <label for="message" style="font-weight: 500;">Message</label>
                        <textarea class="form-control" name="message" rows="4" required style="border-radius: var(--radius-sm); resize: vertical;"></textarea>
                      </div>
                      <button type="submit" class="btn btn-primary btn-block btn-lg btn-flat" style="height: 46px; margin-top: 15px;">Send Message</button>
                    </form>
                  </div>
                </div>

                <hr style="margin: 40px 0; border-color: var(--border-color);">

                <!-- Google Map -->
                <h4 class="text-center" style="font-family: var(--font-serif); color: var(--primary-color); font-size: 22px; margin-bottom: 20px;"><b>Find Us on the Map</b></h4>
                <div class="row">
                  <div class="col-sm-12">
                    <div style="border: 1px solid var(--border-color); border-radius: var(--radius-md); overflow: hidden; box-shadow: var(--shadow-sm);">
                      <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3562.191238548604!2d75.78727057463505!3d26.912433483117687!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x396db4ecf4bfcf63%3A0x6d3e1af7e4a4db4d!2sJaipur%2C%20Rajasthan!5e0!3m2!1sen!2sin!4v1693679242546!5m2!1sen!2sin" 
                        width="100%" height="380" frameborder="0" style="border:0; display: block;" allowfullscreen>
                      </iframe>
                    </div>
                  </div>
                </div>

              </div>
            </div>
          </div>
        </div>
      </section>

    </div>
  </div>
  <?php include 'includes/footer.php'; ?>
</div>
</body>
</html>
