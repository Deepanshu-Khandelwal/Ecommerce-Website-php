<?php
include 'includes/session.php';

// Handle AJAX & Form POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = $pdo->open();

    // 1. Fetch single row details (AJAX)
    if (isset($_POST['get_row'])) {
        $id = $_POST['id'] ?? 0;
        $stmt = $conn->prepare("SELECT * FROM carousel_banners WHERE id=:id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        $pdo->close();
        header('Content-Type: application/json');
        echo json_encode($row);
        exit();
    }

    // 2. Add Banner
    if (isset($_POST['add'])) {
        $title = $_POST['title'] ?? '';
        $link = $_POST['link'] ?? '';
        $sort_order = $_POST['sort_order'] ?? 0;
        $status = $_POST['status'] ?? 1;
        $filename = $_FILES['photo']['name'] ?? '';

        if (!empty($filename)) {
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            $new_filename = 'banner_' . time() . '.' . $ext;
            
            if (move_uploaded_file($_FILES['photo']['tmp_name'], '../images/' . $new_filename)) {
                try {
                    $stmt = $conn->prepare("INSERT INTO carousel_banners (image, title, link, sort_order, status) VALUES (:image, :title, :link, :sort_order, :status)");
                    $stmt->execute([
                        'image' => $new_filename,
                        'title' => $title,
                        'link' => $link,
                        'sort_order' => $sort_order,
                        'status' => $status
                    ]);
                    $_SESSION['success'] = 'Banner added successfully';
                } catch (PDOException $e) {
                    $_SESSION['error'] = $e->getMessage();
                }
            } else {
                $_SESSION['error'] = 'Failed to upload image file';
            }
        } else {
            $_SESSION['error'] = 'Please select a banner image';
        }
        $pdo->close();
        header('location: carousel.php');
        exit();
    }

    // 3. Edit Banner details
    if (isset($_POST['edit'])) {
        $id = $_POST['id'] ?? 0;
        $title = $_POST['title'] ?? '';
        $link = $_POST['link'] ?? '';
        $sort_order = $_POST['sort_order'] ?? 0;
        $status = $_POST['status'] ?? 1;

        try {
            $stmt = $conn->prepare("UPDATE carousel_banners SET title=:title, link=:link, sort_order=:sort_order, status=:status WHERE id=:id");
            $stmt->execute([
                'title' => $title,
                'link' => $link,
                'sort_order' => $sort_order,
                'status' => $status,
                'id' => $id
            ]);
            $_SESSION['success'] = 'Banner details updated successfully';
        } catch (PDOException $e) {
            $_SESSION['error'] = $e->getMessage();
        }
        $pdo->close();
        header('location: carousel.php');
        exit();
    }

    // 4. Update Banner Photo
    if (isset($_POST['upload'])) {
        $id = $_POST['id'] ?? 0;
        $filename = $_FILES['photo']['name'] ?? '';

        if (!empty($filename)) {
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            $new_filename = 'banner_' . time() . '.' . $ext;

            // Fetch old image to delete it and save space
            $stmt = $conn->prepare("SELECT image FROM carousel_banners WHERE id=:id");
            $stmt->execute(['id' => $id]);
            $row = $stmt->fetch();
            $old_image = $row['image'] ?? '';

            if (move_uploaded_file($_FILES['photo']['tmp_name'], '../images/' . $new_filename)) {
                try {
                    $stmt = $conn->prepare("UPDATE carousel_banners SET image=:image WHERE id=:id");
                    $stmt->execute(['image' => $new_filename, 'id' => $id]);
                    $_SESSION['success'] = 'Banner image updated successfully';
                    
                    // Delete old image if it is dynamic and exists
                    if (!empty($old_image) && file_exists('../images/' . $old_image) && strpos($old_image, 'banner_') === 0) {
                        unlink('../images/' . $old_image);
                    }
                } catch (PDOException $e) {
                    $_SESSION['error'] = $e->getMessage();
                }
            } else {
                $_SESSION['error'] = 'Failed to upload new image file';
            }
        } else {
            $_SESSION['error'] = 'Please select an image file to update';
        }
        $pdo->close();
        header('location: carousel.php');
        exit();
    }

    // 5. Delete Banner
    if (isset($_POST['delete'])) {
        $id = $_POST['id'] ?? 0;

        try {
            // Get image name to delete file
            $stmt = $conn->prepare("SELECT image FROM carousel_banners WHERE id=:id");
            $stmt->execute(['id' => $id]);
            $row = $stmt->fetch();
            $image = $row['image'] ?? '';

            $stmt = $conn->prepare("DELETE FROM carousel_banners WHERE id=:id");
            $stmt->execute(['id' => $id]);
            $_SESSION['success'] = 'Banner deleted successfully';

            // Delete file if it exists and was uploaded dynamically
            if (!empty($image) && file_exists('../images/' . $image) && strpos($image, 'banner_') === 0) {
                unlink('../images/' . $image);
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = $e->getMessage();
        }
        $pdo->close();
        header('location: carousel.php');
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
        Hero Carousel Banners
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Carousel</li>
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
              <a href="#addnew" data-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus"></i> New Banner</a>
            </div>
            <div class="box-body">
              <table id="example1" class="table table-bordered">
                <thead>
                  <th style="width: 100px;">Preview</th>
                  <th>Title/Alt Text</th>
                  <th>Link URL</th>
                  <th>Sort Order</th>
                  <th>Status</th>
                  <th>Tools</th>
                </thead>
                <tbody>
                  <?php
                    $conn = $pdo->open();

                    try{
                      $stmt = $conn->prepare("SELECT * FROM carousel_banners ORDER BY sort_order ASC, id DESC");
                      $stmt->execute();
                      foreach($stmt as $row){
                        $image = (!empty($row['image'])) ? '../images/'.$row['image'] : '../images/noimage.jpg';
                        $status = ($row['status']) ? '<span class="label label-success">active</span>' : '<span class="label label-danger">inactive</span>';
                        echo "
                          <tr>
                            <td>
                              <img src='".$image."' height='50px' style='object-fit: cover; width: 80px; border-radius: 4px; border: 1px solid #ddd;'>
                              <span class='pull-right'><a href='#edit_photo' class='photo' data-toggle='modal' data-id='".$row['id']."'><i class='fa fa-edit'></i></a></span>
                            </td>
                            <td>".htmlspecialchars($row['title'])."</td>
                            <td>".htmlspecialchars($row['link'])."</td>
                            <td>".$row['sort_order']."</td>
                            <td>".$status."</td>
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
    <?php include 'includes/carousel_modal.php'; ?>

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

});

function getRow(id){
  $.ajax({
    type: 'POST',
    url: 'carousel.php',
    data: {get_row: true, id: id},
    dataType: 'json',
    success: function(response){
      $('.bannerid').val(response.id);
      $('#edit_title').val(response.title);
      $('#edit_link').val(response.link);
      $('#edit_sort_order').val(response.sort_order);
      $('#edit_status').val(response.status);
      $('.bannername').html(response.title ? response.title : 'Banner ID ' + response.id);
    }
  });
}
</script>
</body>
</html>
