<?php
include 'includes/session.php';

if(!isset($_SESSION['user'])){
    header('location: index.php');
    exit();
}

// Handle Profile Edit POST request
if (isset($_POST['edit'])) {
    $conn = $pdo->open();

    $curr_password = $_POST['curr_password'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $firstname = $_POST['firstname'] ?? '';
    $lastname = $_POST['lastname'] ?? '';
    $contact = $_POST['contact'] ?? '';
    $address = $_POST['address'] ?? '';
    $photo = $_FILES['photo']['name'] ?? '';

    if (password_verify($curr_password, $user['password'])) {
        if (!empty($photo)) {
            move_uploaded_file($_FILES['photo']['tmp_name'], 'images/' . $photo);
            $filename = $photo;	
        } else {
            $filename = $user['photo'];
        }

        if ($password == $user['password']) {
            $password = $user['password'];
        } else {
            $password = password_hash($password, PASSWORD_DEFAULT);
        }

        try {
            $stmt = $conn->prepare("UPDATE users SET email=:email, password=:password, firstname=:firstname, lastname=:lastname, contact_info=:contact, address=:address, photo=:photo WHERE id=:id");
            $stmt->execute([
                'email' => $email,
                'password' => $password,
                'firstname' => $firstname,
                'lastname' => $lastname,
                'contact' => $contact,
                'address' => $address,
                'photo' => $filename,
                'id' => $user['id']
            ]);

            $_SESSION['success'] = 'Account updated successfully';
        } catch (PDOException $e) {
            $_SESSION['error'] = $e->getMessage();
        }
    } else {
        $_SESSION['error'] = 'Incorrect password';
    }

    $pdo->close();
    header('location: profile.php');
    exit();
}

if(!function_exists('h')){
    function h($v){ return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
}

include 'includes/header.php';
?>
<body class="bg-light">
<div class="wrapper">

	<?php include 'includes/navbar.php'; ?>
	 
	  <div class="content-wrapper py-5">
	    <div class="container">

	      <!-- Main content -->
	      <section class="content">
	        <div class="row g-4">
	        	<div class="col-lg-12">
	        		<?php
	        			if(isset($_SESSION['error'])){
	        				echo "
	        					<div class='alert alert-danger shadow-sm mb-4 alert-dismissible fade show' role='alert'>
	        						".$_SESSION['error']."
                                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
	        					</div>
	        				";
	        				unset($_SESSION['error']);
	        			}

	        			if(isset($_SESSION['success'])){
	        				echo "
	        					<div class='alert alert-success shadow-sm mb-4 alert-dismissible fade show' role='alert'>
	        						".$_SESSION['success']."
                                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
	        					</div>
	        				";
	        				unset($_SESSION['success']);
	        			}
	        		?>
	        		<div class="card border-light shadow-sm p-4 mb-4">
	        			<div class="card-body p-0">
                          <div class="row g-4 align-items-center">
                            <div class="col-md-3 text-center">
                              <img src="<?php echo (!empty($user['photo'])) ? 'images/'.$user['photo'] : 'images/profile.jpg'; ?>" class="rounded-circle border border-3 border-warning shadow-sm" style="width: 120px; height: 120px; object-fit: cover;">
                            </div>
                            <div class="col-md-9">
                              <div class="d-flex justify-content-between align-items-center border-bottom border-light-subtle pb-3 mb-3">
                                <h3 class="mb-0" style="font-family: var(--font-serif); color: var(--primary-color); font-size: 24px; font-weight: 700;"><?php echo $user['firstname'].' '.$user['lastname']; ?></h3>
                                <a href="#edit" class="btn btn-primary btn-sm btn-flat" data-bs-toggle="modal"><i class="fa fa-edit me-1"></i> Edit Profile</a>
                              </div>
                              
                              <div class="row g-2" style="font-size: 15px;">
                                <div class="col-sm-3 text-muted font-weight-bold">Email:</div>
                                <div class="col-sm-9 text-dark mb-2"><?php echo $user['email']; ?></div>
                                
                                <div class="col-sm-3 text-muted font-weight-bold">Contact:</div>
                                <div class="col-sm-9 text-dark mb-2"><?php echo (!empty($user['contact_info'])) ? $user['contact_info'] : '<em class="text-black-50">N/A</em>'; ?></div>
                                
                                <div class="col-sm-3 text-muted font-weight-bold">Address:</div>
                                <div class="col-sm-9 text-dark mb-2"><?php echo (!empty($user['address'])) ? $user['address'] : '<em class="text-black-50">N/A</em>'; ?></div>
                                
                                <div class="col-sm-3 text-muted font-weight-bold">Member Since:</div>
                                <div class="col-sm-9 text-dark"><?php echo date('M d, Y', strtotime($user['created_on'])); ?></div>
                              </div>
                            </div>
                          </div>
	        			</div>
	        		</div>
	        		<div class="card border-light shadow-sm mb-4">
	        			<div class="card-header bg-white py-3 border-bottom border-light">
	        				<h5 class="mb-0 text-emerald font-weight-bold"><i class="fa-solid fa-clock-rotate-left me-2"></i><b>Transaction History</b></h5>
	        			</div>
	        			<div class="card-body p-0 table-responsive">
	        				<table class="table align-middle mb-0" id="example1">
	        					<thead>
                                    <tr>
                                        <th class="hidden"></th>
                                        <th>Date</th>
                                        <th>Order ID / Txn ID</th>
                                        <th>Amount</th>
                                        <th>Method</th>
                                        <th>Status</th>
                                        <th>Details</th>
                                    </tr>
	        					</thead>
	        					<tbody>
	        					<?php
	        						$conn = $pdo->open();

	        						try{
	        							$stmt = $conn->prepare("SELECT * FROM sales WHERE user_id=:user_id ORDER BY sales_date DESC");
	        							$stmt->execute(['user_id'=>$user['id']]);
	        							foreach($stmt as $row){
                                             $method = strtolower($row['payment_method'] ?? 'razorpay');
                                             $methodBadge = ($method === 'cod') 
                                                 ? '<span class="badge bg-secondary py-1 px-2">COD</span>' 
                                                 : '<span class="badge bg-primary py-1 px-2">Online</span>';
                                             
                                             $status = strtolower($row['status'] ?? 'paid');
                                             if ($status === 'paid') {
                                                 $statusBadge = '<span class="badge bg-success py-1 px-2">Paid</span>';
                                             } elseif ($status === 'pending') {
                                                 $statusBadge = '<span class="badge bg-warning text-dark py-1 px-2">Pending</span>';
                                             } elseif ($status === 'shipped') {
                                                 $statusBadge = '<span class="badge bg-info py-1 px-2">Shipped</span>';
                                             } else {
                                                 $statusBadge = '<span class="badge bg-danger py-1 px-2">' . ucfirst($status) . '</span>';
                                             }

                                             $txnId = $row['pay_id'] ?: 'Order #' . $row['id'];
                                             $orderDate = $row['sales_date'] ? date('M d, Y H:i', strtotime($row['sales_date'])) : '-';

	        								echo "
	        									<tr>
	        										<td class='hidden'></td>
	        										<td>".$orderDate."</td>
	        										<td class='font-monospace small'>".h($txnId)."</td>
	        										<td class='text-emerald font-weight-bold'>&#8377; ".number_format($row['amount'], 2)."</td>
                                                     <td>".$methodBadge."</td>
                                                     <td>".$statusBadge."</td>
	        										<td><button class='btn btn-sm btn-flat btn-info transact' data-id='".$row['id']."'><i class='fa fa-search me-1'></i> View</button></td>
	        									</tr>
	        								";
	        							}

	        						}
        							catch(PDOException $e){
										echo "<tr><td colspan='7' class='text-danger'>There is some problem in connection: " . $e->getMessage() . "</td></tr>";
									}

	        						$pdo->close();
	        					?>
	        					</tbody>
	        				</table>
	        			</div>
	        		</div>
	        </div>
	      </section>
	     
	    </div>
	  </div>
  
  	<?php include 'includes/footer.php'; ?>
  	<?php include 'includes/profile_modal.php'; ?>
</div>

<script>
$(function(){
	$(document).on('click', '.transact', function(e){
		e.preventDefault();
		$('#transaction').modal('show');
		var id = $(this).data('id');
		$.ajax({
			type: 'POST',
			url: 'ajax_action.php?action=transaction_details',
			data: {id:id},
			dataType: 'json',
			success:function(response){
				$('#date').html(response.date);
				$('#transid').html(response.transaction);
				$('#detail').prepend(response.list);
				$('#total').html(response.total);
			}
		});
	});

	$("#transaction").on("hidden.bs.modal", function () {
	    $('.prepend_items').remove();
	});
});
</script>
</body>
</html>