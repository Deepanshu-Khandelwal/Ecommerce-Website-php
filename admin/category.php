<?php
include 'includes/session.php';

// Handle AJAX & Form POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = $pdo->open();

    // 1. Fetch single row details (replacing category_row.php)
    if (isset($_POST['get_row'])) {
        $id = $_POST['id'] ?? 0;
        $stmt = $conn->prepare("SELECT * FROM category WHERE id=:id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        $pdo->close();
        header('Content-Type: application/json');
        echo json_encode($row);
        exit();
    }

    // 2. Fetch category option tags (replacing category_fetch.php)
    if (isset($_POST['fetch_options'])) {
        $output = '';
        $stmt = $conn->prepare("SELECT * FROM category");
        $stmt->execute();
        foreach ($stmt as $row) {
            $output .= "<option value='" . $row['id'] . "' class='append_items'>" . htmlspecialchars($row['name']) . "</option>";
        }
        $pdo->close();
        header('Content-Type: application/json');
        echo json_encode($output);
        exit();
    }

    // 3. Add Category (replacing category_add.php)
    if (isset($_POST['add'])) {
        $name = $_POST['name'] ?? '';
        $cat_slug = strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', $name));

        $stmt = $conn->prepare("SELECT * FROM category WHERE name=:name");
        $stmt->execute(['name' => $name]);
        $row = $stmt->fetch();

        if ($row !== false) {
            $_SESSION['error'] = 'Category already exists';
        } else {
            try {
                $stmt = $conn->prepare("INSERT INTO category (name, cat_slug) VALUES (:name, :cat_slug)");
                $stmt->execute([
                    'name' => $name,
                    'cat_slug' => $cat_slug
                ]);
                $_SESSION['success'] = 'Category added successfully';
            } catch (PDOException $e) {
                $_SESSION['error'] = $e->getMessage();
            }
        }
        $pdo->close();
        header('location: category.php');
        exit();
    }

    // 4. Edit Category (replacing category_edit.php)
    if (isset($_POST['edit'])) {
        $id = $_POST['id'] ?? 0;
        $name = $_POST['name'] ?? '';

        try {
            $stmt = $conn->prepare("UPDATE category SET name=:name WHERE id=:id");
            $stmt->execute(['name' => $name, 'id' => $id]);
            $_SESSION['success'] = 'Category updated successfully';
        } catch (PDOException $e) {
            $_SESSION['error'] = $e->getMessage();
        }
        $pdo->close();
        header('location: category.php');
        exit();
    }

    // 5. Delete Category (replacing category_delete.php)
    if (isset($_POST['delete'])) {
        $id = $_POST['id'] ?? 0;

        try {
            $stmt = $conn->prepare("DELETE FROM category WHERE id=:id");
            $stmt->execute(['id' => $id]);
            $_SESSION['success'] = 'Category deleted successfully';
        } catch (PDOException $e) {
            $_SESSION['error'] = $e->getMessage();
        }
        $pdo->close();
        header('location: category.php');
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
        Category
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li>Products</li>
        <li class="active">Category</li>
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
                  <th>Category Name</th>
                  <th>Tools</th>
                </thead>
                <tbody>
                  <?php
                    $conn = $pdo->open();

                    try{
                      $stmt = $conn->prepare("SELECT * FROM category");
                      $stmt->execute();
                      foreach($stmt as $row){
                        echo "
                          <tr>
                            <td>".$row['name']."</td>
                            <td>
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
    <?php include 'includes/category_modal.php'; ?>

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

});

function getRow(id){
  $.ajax({
    type: 'POST',
    url: 'category.php',
    data: {get_row: true, id: id},
    dataType: 'json',
    success: function(response){
      $('.catid').val(response.id);
      $('#edit_name').val(response.name);
      $('.catname').html(response.name);
    }
  });
}
</script>
</body>
</html>
