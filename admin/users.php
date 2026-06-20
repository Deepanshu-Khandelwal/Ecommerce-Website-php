<?php
include 'includes/session.php';

// Handle AJAX & Form POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = $pdo->open();

    // 1. Fetch single row details (replacing users_row.php)
    if (isset($_POST['get_row'])) {
        $id = $_POST['id'] ?? 0;
        $stmt = $conn->prepare("SELECT * FROM users WHERE id=:id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        $pdo->close();
        header('Content-Type: application/json');
        echo json_encode($row);
        exit();
    }

    // 2. Add User (replacing users_add.php)
    if (isset($_POST['add'])) {
        $firstname = $_POST['firstname'] ?? '';
        $lastname = $_POST['lastname'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $address = $_POST['address'] ?? '';
        $contact = $_POST['contact'] ?? '';
        $photo = $_FILES['photo']['name'] ?? '';
        $type = ($admin['type'] == 2) ? ($_POST['type'] ?? 0) : 0;

        $stmt = $conn->prepare("SELECT * FROM users WHERE email=:email");
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch();

        if ($row !== false) {
            $_SESSION['error'] = 'Email already taken';
        } else {
            $password = password_hash($password, PASSWORD_DEFAULT);
            if (!empty($photo)) {
                move_uploaded_file($_FILES['photo']['tmp_name'], '../images/' . $photo);	
            }
            try {
                $now = date('Y-m-d');
                $stmt = $conn->prepare("INSERT INTO users (email, password, firstname, lastname, address, contact_info, photo, status, created_on, type) VALUES (:email, :password, :firstname, :lastname, :address, :contact, :photo, :status, :created_on, :type)");
                $stmt->execute([
                    'email' => $email,
                    'password' => $password,
                    'firstname' => $firstname,
                    'lastname' => $lastname,
                    'address' => $address,
                    'contact' => $contact,
                    'photo' => $photo,
                    'status' => 1,
                    'created_on' => $now,
                    'type' => $type
                ]);
                $_SESSION['success'] = 'User added successfully';
            } catch (PDOException $e) {
                $_SESSION['error'] = $e->getMessage();
            }
        }
        $pdo->close();
        header('location: users.php');
        exit();
    }

    // 3. Edit User (replacing users_edit.php)
    if (isset($_POST['edit'])) {
        $id = $_POST['id'] ?? 0;
        $firstname = $_POST['firstname'] ?? '';
        $lastname = $_POST['lastname'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $address = $_POST['address'] ?? '';
        $contact = $_POST['contact'] ?? '';

        $stmt = $conn->prepare("SELECT * FROM users WHERE id=:id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        if ($row) {
            if ($password == $row['password']) {
                $password = $row['password'];
            } else {
                $password = password_hash($password, PASSWORD_DEFAULT);
            }

            $type = ($admin['type'] == 2 && $id != $admin['id']) ? ($_POST['type'] ?? 0) : $row['type'];

            try {
                $stmt = $conn->prepare("UPDATE users SET email=:email, password=:password, firstname=:firstname, lastname=:lastname, address=:address, contact_info=:contact, type=:type WHERE id=:id");
                $stmt->execute([
                    'email' => $email,
                    'password' => $password,
                    'firstname' => $firstname,
                    'lastname' => $lastname,
                    'address' => $address,
                    'contact' => $contact,
                    'type' => $type,
                    'id' => $id
                ]);
                $_SESSION['success'] = 'User updated successfully';
            } catch (PDOException $e) {
                $_SESSION['error'] = $e->getMessage();
            }
        } else {
            $_SESSION['error'] = 'User not found';
        }
        $pdo->close();
        header('location: users.php');
        exit();
    }

    // 4. Delete User (replacing users_delete.php)
    if (isset($_POST['delete'])) {
        $id = $_POST['id'] ?? 0;

        if ($id == $admin['id']) {
            $_SESSION['error'] = 'You cannot delete yourself';
        } else {
            try {
                $stmt = $conn->prepare("DELETE FROM users WHERE id=:id");
                $stmt->execute(['id' => $id]);
                $_SESSION['success'] = 'User deleted successfully';
            } catch (PDOException $e) {
                $_SESSION['error'] = $e->getMessage();
            }
        }
        $pdo->close();
        header('location: users.php');
        exit();
    }

    // 5. Upload User Photo (replacing users_photo.php)
    if (isset($_POST['upload'])) {
        $id = $_POST['id'] ?? 0;
        $filename = $_FILES['photo']['name'] ?? '';

        if (!empty($filename)) {
            move_uploaded_file($_FILES['photo']['tmp_name'], '../images/' . $filename);	
            try {
                $stmt = $conn->prepare("UPDATE users SET photo=:photo WHERE id=:id");
                $stmt->execute(['photo' => $filename, 'id' => $id]);
                $_SESSION['success'] = 'User photo updated successfully';
            } catch (PDOException $e) {
                $_SESSION['error'] = $e->getMessage();
            }
        } else {
            $_SESSION['error'] = 'Select user to update photo first';
        }
        $pdo->close();
        header('location: users.php');
        exit();
    }

    // 6. Activate User (replacing users_activate.php)
    if (isset($_POST['activate'])) {
        $id = $_POST['id'] ?? 0;

        try {
            $stmt = $conn->prepare("UPDATE users SET status=:status WHERE id=:id");
            $stmt->execute(['status' => 1, 'id' => $id]);
            $_SESSION['success'] = 'User activated successfully';
        } catch (PDOException $e) {
            $_SESSION['error'] = $e->getMessage();
        }
        $pdo->close();
        header('location: users.php');
        exit();
    }
    $pdo->close();
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
        Users
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Users</li>
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
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border">
              <a href="#addnew" data-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus"></i> New</a>
            </div>
            <div class="box-body">
              <table id="example1" class="table table-bordered">
                <thead>
                  <th>Photo</th>
                  <th>Email</th>
                  <th>Name</th>
                  <?php if($admin['type'] == 2): ?>
                  <th>Role</th>
                  <?php endif; ?>
                  <th>Status</th>
                  <th>Date Added</th>
                  <th>Tools</th>
                </thead>
                <tbody>
                  <?php
                    $conn = $pdo->open();

                    try{
                      if($admin['type'] == 2){
                        $stmt = $conn->prepare("SELECT * FROM users");
                        $stmt->execute();
                      }
                      else{
                        $stmt = $conn->prepare("SELECT * FROM users WHERE type=:type");
                        $stmt->execute(['type'=>0]);
                      }
                      foreach($stmt as $row){
                        $image = (!empty($row['photo'])) ? '../images/'.$row['photo'] : '../images/profile.jpg';
                        $status = ($row['status']) ? '<span class="label label-success">active</span>' : '<span class="label label-danger">not verified</span>';
                        $active = (!$row['status']) ? '<span class="pull-right"><a href="#activate" class="status" data-toggle="modal" data-id="'.$row['id'].'"><i class="fa fa-check-square-o"></i></a></span>' : '';
                        
                        $role = '';
                        if($row['type'] == 2){
                          $role = '<span class="label label-danger">Super Admin</span>';
                        }
                        elseif($row['type'] == 1){
                          $role = '<span class="label label-warning">Admin</span>';
                        }
                        else{
                          $role = '<span class="label label-primary">User</span>';
                        }
                        $role_td = ($admin['type'] == 2) ? "<td>".$role."</td>" : "";

                        $cart_btn = ($row['type'] == 0) ? "<a href='cart.php?user=".$row['id']."' class='btn btn-info btn-sm btn-flat'><i class='fa fa-search'></i> Cart</a>" : "";

                        echo "
                          <tr>
                            <td>
                              <img src='".$image."' height='30px' width='30px'>
                              <span class='pull-right'><a href='#edit_photo' class='photo' data-toggle='modal' data-id='".$row['id']."'><i class='fa fa-edit'></i></a></span>
                            </td>
                            <td>".$row['email']."</td>
                            <td>".$row['firstname'].' '.$row['lastname']."</td>
                            ".$role_td."
                            <td>
                              ".$status."
                              ".$active."
                            </td>
                            <td>".date('M d, Y', strtotime($row['created_on']))."</td>
                            <td>
                              ".$cart_btn."
                              <button class='btn btn-success btn-sm edit btn-flat' data-id='".$row['id']."'><i class='fa fa-edit'></i> Edit</button>
                              <button class='btn btn-danger btn-sm delete btn-flat' data-id='".$row['id']."'><i class='fa fa-trash'></i> Delete</button>
                            </td>
                          </tr>
                        ";
                      }
                    }
                    catch(PDOException $e){
                      echo $e->getMessage();
                    }

                    $pdo->close();
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>
     
  </div>
  	<?php include 'includes/footer.php'; ?>
    <?php include 'includes/users_modal.php'; ?>

</div>
<!-- ./wrapper -->

<?php include 'includes/scripts.php'; ?>
<script>
$(function(){

  $(document).on('click', '.edit', function(e){
    e.preventDefault();
    $('#edit').modal('show');
    var id = $(this).data('id');
    getRow(id);
  });

  $(document).on('click', '.delete', function(e){
    e.preventDefault();
    $('#delete').modal('show');
    var id = $(this).data('id');
    getRow(id);
  });

  $(document).on('click', '.photo', function(e){
    e.preventDefault();
    var id = $(this).data('id');
    getRow(id);
  });

  $(document).on('click', '.status', function(e){
    e.preventDefault();
    var id = $(this).data('id');
    getRow(id);
  });

});

function getRow(id){
  $.ajax({
    type: 'POST',
    url: 'users.php',
    data: {get_row: true, id: id},
    dataType: 'json',
    success: function(response){
      $('.userid').val(response.id);
      $('#edit_email').val(response.email);
      $('#edit_password').val(response.password);
      $('#edit_firstname').val(response.firstname);
      $('#edit_lastname').val(response.lastname);
      $('#edit_address').val(response.address);
      $('#edit_contact').val(response.contact_info);
      $('#edit_type').val(response.type);
      $('.fullname').html(response.firstname+' '+response.lastname);
    }
  });
}
</script>
</body>
</html>
