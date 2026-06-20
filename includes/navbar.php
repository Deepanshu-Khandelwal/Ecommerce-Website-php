<header class="main-header">
  <nav class="navbar navbar-expand-lg navbar-dark py-2">
    <div class="container">
      <!-- Brand Logo -->
      <a href="index.php" class="navbar-brand">
        <img src="images/<?php echo !empty($settings['logo']) ? $settings['logo'] : 'pavitra_logo.png'; ?>" alt="<?php echo !empty($settings['site_name']) ? htmlspecialchars($settings['site_name']) : 'Pavitra Designer'; ?>" style="height:60px; margin-right:8px;">
        <span class="brand-text">
          <span class="color-change"><?php echo !empty($settings['site_name']) ? htmlspecialchars($settings['site_name']) : 'Pavitra Designer'; ?></span>
        </span>
      </a>

      <!-- Navbar Toggler for Mobile -->
      <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Collapsible items -->
      <div class="collapse navbar-collapse" id="navbarContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item"><a class="nav-link" href="index.php">HOME</a></li>
          <li class="nav-item"><a class="nav-link" href="about.php">ABOUT US</a></li>
          <li class="nav-item"><a class="nav-link" href="contact.php">CONTACT US</a></li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="catDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              CATEGORY
            </a>
            <ul class="dropdown-menu shadow" aria-labelledby="catDropdown">
              <?php
                $conn = $pdo->open();
                try{
                  $stmt = $conn->prepare("SELECT * FROM category");
                  $stmt->execute();
                  foreach($stmt as $row){
                    echo "
                      <li><a class='dropdown-item' href='category.php?category=".$row['cat_slug']."'>".$row['name']."</a></li>
                    ";                  
                  }
                }
                catch(PDOException $e){
                  echo "<li><span class='dropdown-item text-danger'>Connection error: ".$e->getMessage()."</span></li>";
                }
                $pdo->close();
              ?>
            </ul>
          </li>
        </ul>

        <!-- Search Bar -->
        <form method="POST" class="d-flex my-2 my-lg-0 me-3" action="search.php">
          <div class="input-group">
            <input type="text" class="form-control" id="navbar-search-input" name="keyword" placeholder="Search for Product" required>
            <span class="input-group-btn" id="searchBtn" style="display:none;">
              <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
            </span>
          </div>
        </form>

        <!-- Right Side Menu -->
        <ul class="navbar-nav align-items-lg-center">
          <!-- Cart Dropdown -->
          <li class="nav-item dropdown me-3">
            <a href="#" class="nav-link dropdown-toggle position-relative" id="cartDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="fa fa-shopping-cart fs-5"></i>
              <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success cart_count">0</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow p-3" aria-labelledby="cartDropdown" style="min-width: 280px;">
              <li class="dropdown-header px-0">You have <span class="cart_count"></span> item(s) in cart</li>
              <li><hr class="dropdown-divider"></li>
              <li>
                <ul class="list-unstyled p-0 m-0" id="cart_menu" style="max-height: 240px; overflow-y: auto;">
                  <!-- Cart items load here dynamically -->
                </ul>
              </li>
              <li><hr class="dropdown-divider"></li>
              <li class="text-center pt-2"><a href="cart_view.php" class="btn btn-primary btn-sm w-100 btn-flat">Go to Cart</a></li>
            </ul>
          </li>

          <!-- Account Dropdown -->
          <?php
            if(isset($_SESSION['user'])){
              $image = (!empty($user['photo'])) ? 'images/'.$user['photo'] : 'images/profile.jpg';
              echo '
                <li class="nav-item dropdown user user-menu">
                  <a href="#" class="nav-link dropdown-toggle d-flex align-items-center" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="'.$image.'" class="user-image rounded-circle me-2" alt="User Image" style="width: 32px; height: 32px; object-fit: cover;">
                    <span class="d-none d-md-inline text-white">'.$user['firstname'].' '.$user['lastname'].'</span>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userDropdown">
                    <li class="user-header text-center p-3 text-white" style="background: var(--primary-color);">
                      <img src="'.$image.'" class="rounded-circle mb-2 border border-2 border-warning" alt="User Image" style="width: 70px; height: 70px; object-fit: cover; margin: auto;">
                      <p class="mb-0 font-weight-bold text-white">
                        '.$user['firstname'].' '.$user['lastname'].'
                      </p>
                      <small class="text-white-50">Member since '.date('M. Y', strtotime($user['created_on'])).'</small>
                    </li>
                    <li class="user-footer d-flex justify-content-between p-2 bg-light">
                      <a href="profile.php" class="btn btn-outline-secondary btn-sm btn-flat">Profile</a>
                      <a href="logout.php" class="btn btn-outline-danger btn-sm btn-flat">Sign out</a>
                    </li>
                  </ul>
                </li>
              ';
            }
            else{
              echo "
                <li class='nav-item'><a class='nav-link' href='login.php'>LOGIN</a></li>
                <li class='nav-item'><a class='nav-link' href='register.php'>SIGNUP</a></li>
              ";
            }
          ?>
        </ul>
      </div>
    </div>
  </nav>
</header>