<!-- Add -->
<div class="modal fade" id="addnew">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"><b>Add New Carousel Banner</b></h4>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" method="POST" action="carousel.php" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="photo" class="col-sm-3 control-label">Banner Image</label>
                    <div class="col-sm-9">
                      <input type="file" id="photo" name="photo" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="title" class="col-sm-3 control-label">Title / Alt Text</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="title" name="title" placeholder="Optional Alt Text/Title">
                    </div>
                </div>
                <div class="form-group">
                    <label for="link" class="col-sm-3 control-label">Link URL</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="link" name="link" placeholder="Optional Link (e.g. category.php?category=printed-sarees)">
                    </div>
                </div>
                <div class="form-group">
                    <label for="sort_order" class="col-sm-3 control-label">Sort Order</label>
                    <div class="col-sm-9">
                      <input type="number" class="form-control" id="sort_order" name="sort_order" value="0">
                    </div>
                </div>
                <div class="form-group">
                    <label for="status" class="col-sm-3 control-label">Status</label>
                    <div class="col-sm-9">
                      <select class="form-control" id="status" name="status" required>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                      </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
              <button type="submit" class="btn btn-primary btn-flat" name="add"><i class="fa fa-save"></i> Save</button>
              </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit -->
<div class="modal fade" id="edit">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"><b>Edit Banner Details</b></h4>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" method="POST" action="carousel.php">
                <input type="hidden" class="bannerid" name="id">
                <div class="form-group">
                    <label for="edit_title" class="col-sm-3 control-label">Title / Alt Text</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="edit_title" name="title">
                    </div>
                </div>
                <div class="form-group">
                    <label for="edit_link" class="col-sm-3 control-label">Link URL</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="edit_link" name="link">
                    </div>
                </div>
                <div class="form-group">
                    <label for="edit_sort_order" class="col-sm-3 control-label">Sort Order</label>
                    <div class="col-sm-9">
                      <input type="number" class="form-control" id="edit_sort_order" name="sort_order">
                    </div>
                </div>
                <div class="form-group">
                    <label for="edit_status" class="col-sm-3 control-label">Status</label>
                    <div class="col-sm-9">
                      <select class="form-control" id="edit_status" name="status" required>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                      </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
              <button type="submit" class="btn btn-success btn-flat" name="edit"><i class="fa fa-check-square-o"></i> Update</button>
              </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Photo -->
<div class="modal fade" id="edit_photo">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"><b>Update Banner Image</b></h4>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" method="POST" action="carousel.php" enctype="multipart/form-data">
                <input type="hidden" class="bannerid" name="id">
                <div class="form-group">
                    <label for="photo" class="col-sm-3 control-label">New Banner Image</label>
                    <div class="col-sm-9">
                      <input type="file" id="photo" name="photo" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
              <button type="submit" class="btn btn-success btn-flat" name="upload"><i class="fa fa-check-square-o"></i> Update Image</button>
              </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete -->
<div class="modal fade" id="delete">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"><b>Deleting Banner...</b></h4>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" method="POST" action="carousel.php">
                <input type="hidden" class="bannerid" name="id">
                <div class="text-center">
                    <p>DELETE CAROUSEL BANNER</p>
                    <h2 class="bold bannername"></h2>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
              <button type="submit" class="btn btn-danger btn-flat" name="delete"><i class="fa fa-trash"></i> Delete</button>
              </form>
            </div>
        </div>
    </div>
</div>
