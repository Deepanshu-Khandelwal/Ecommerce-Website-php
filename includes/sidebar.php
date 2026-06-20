<div class="card border-light shadow-sm mb-4">
	<div class="card-header bg-white py-3">
		<h5 class="card-title mb-0"><b>Most Viewed Today</b></h5>
	</div>
	<div class="card-body p-0">
		<ul id="trending" class="list-unstyled mb-0 px-3 py-2">
			<?php
			$now = date('Y-m-d');
			$conn = $pdo->open();

			$stmt = $conn->prepare("SELECT * FROM products WHERE date_view=:now ORDER BY counter DESC LIMIT 10");
			$stmt->execute(['now' => $now]);
			foreach ($stmt as $row) {
				echo "<li><a href='product.php?product=" . $row['slug'] . "'>" . $row['name'] . "</a></li>";
			}

			$pdo->close();
			?>
		</ul>
	</div>
</div>

<div class="card border-light shadow-sm mb-4">
	<div class="card-header bg-white py-3">
		<h5 class="card-title mb-0"><b>Become a Subscriber</b></h5>
	</div>
	<div class="card-body">
		<p class="text-muted small mb-3">Get free updates on the latest products and discounts, straight to your inbox.</p>
		<form method="POST" action="">
			<div class="input-group">
				<input type="email" class="form-control" placeholder="Your email address" aria-label="Subscriber Email" required>
				<button type="submit" class="btn btn-info"><i class="fa fa-envelope"></i></button>
			</div>
		</form>
	</div>
</div>

<div class="card border-light shadow-sm mb-4">
	<div class="card-header bg-white py-3">
		<h5 class="card-title mb-0"><b>Follow us on Social Media</b></h5>
	</div>
	<div class="card-body">
		<div class="d-flex flex-wrap gap-2 justify-content-center">
			<a class="btn btn-social-icon btn-facebook" href="#" aria-label="Facebook"><i class="fa-brands fa-facebook"></i></a>
			<a class="btn btn-social-icon btn-twitter" href="#" aria-label="Twitter"><i class="fa-brands fa-x-twitter"></i></a>
			<a class="btn btn-social-icon btn-instagram" href="#" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
			<a class="btn btn-social-icon btn-google" href="#" aria-label="Google Plus"><i class="fa-brands fa-google-plus-g"></i></a>
			<a class="btn btn-social-icon btn-linkedin" href="#" aria-label="LinkedIn"><i class="fa-brands fa-linkedin"></i></a>
		</div>
	</div>
</div>