<?php
session_start();
define("freshcery", "http://freshcery");
include '../configration/db.config.php' ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Admin Panel</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="http://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
  <link href="styles/style.css" rel="stylesheet">
  <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
  <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
</head>
<?php
$query = $pdo->prepare("SELECT * FROM categories ");
$query->execute();
$categories = $query->fetchAll(PDO::FETCH_OBJ);

$count = $pdo->prepare("SELECT count(*) FROM categories");
$count->execute();
$_SESSION['categories_count'] = $count->fetchColumn(); 
?>
<body>
  <div id="wrapper">
    <?php include 'nav.php' ?>
    <div style="display: flex; flex-direction:column; justify-content:center; align-items: center;">
    <h1 class="display-3 fw-semibold text-center">CATEGORIES</h1>
    <button class="btn btn-primary mb-4 text-center" data-toggle="modal" data-target="#exampleModal">ADD Categories</button>
    </div>
    <div class="container-fluid">
      <table class="table">
        <thead>
          <tr>
            <th>category-ID</th>
            <th>category-image</th>
            <th>category-name</th>
            <th>Description</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if (count($categories) > 0): foreach ($categories as $category): ?>
              <tr>
                <td><?= $category->id; ?></td>
                <td><img src="<?= freshcery; ?>/assets/img/<?= htmlspecialchars($category->image); ?>" height="100px" width="100px"></td>
                <td><?= $category->name; ?></td>
                <td><?= $category->description; ?></td>
                <td>
                    <button class="btn btn-warning text-white update-category-btn"
                            data-toggle="modal"
                            data-target="#updateModal"
                            data-id="<?= $category->id; ?>"
                            data-name="<?= $category->name; ?>"
                            data-description="<?= $category->description; ?>"
                            data-icon="<?= $category->icon; ?>"
                            data-image="<?= $category->image; ?>">
                        Update
                    </button>
                    <a href="../admin-panel/include/category.inc.php?delete=<?= $category->id; ?>" class="btn btn-danger">Delete</a>
                </td>
              </tr>
            <?php endforeach; else : ?>
            <div class="alert alert-danger bg-danger text-white text-centre">
              <h1>error!</h1>
            </div>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

<form action="../admin-panel/include/category.inc.php" method="POST" enctype="multipart/form-data">
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add Category</h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="name">Category Name</label>
              <input type="text" name="name" class="form-control" >
            </div>
            <div class="form-group">
              <label for="description">Description</label>
              <input type="text" name="description" class="form-control" >
            </div>
            <div class="form-group">
              <label for="icon">Icon</label>
              <input type="text" name="icon" class="form-control" >
            </div>
            <div class="form-group">
              <label for="image">Category Image</label>
              <input type="file" name="image" class="form-control">
            </div>
          </div>
          <div class="modal-footer">
            <input type="submit" class="btn btn-success" name="add_category" value="ADD">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
  </form>
  <!-- Update Category Modal -->
  <form action="../admin-panel/include/category.inc.php" method="POST" enctype="multipart/form-data">
    <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Update Category</h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="update_id" id="update_id">

            <div class="form-group">
              <label for="update_name">Category Name</label>
              <input type="text" name="update_name" id="update_name" class="form-control" >
            </div>
            <div class="form-group">
              <label for="update_description">Description</label>
              <input type="text" name="update_description" id="update_description" class="form-control" >
            </div>
            <div class="form-group">
              <label for="update_icon">Icon</label>
              <input type="text" name="update_icon" id="update_icon" class="form-control" >
            </div>
            <div class="form-group">
              <label for="update_image">Category Image</label>
              <input type="file" name="update_image" class="form-control">
              <input type="hidden" name="update_current_image" id="update_current_image">
            </div>
          </div>
          <div class="modal-footer">
            <input type="submit" class="btn btn-success" name="update_category" value="Update">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
  </form>
  <script>
document.addEventListener('DOMContentLoaded', function () {
  const form = document.querySelector('form');
  const nameInput = form.querySelector('input[name="name"]');
  const descInput = form.querySelector('input[name="description"]');
  const iconInput = form.querySelector('input[name="icon"]');
  const imageInput = form.querySelector('input[name="image"]');

  form.addEventListener('submit', function (e) {
    let isValid = true;
    let errorMessages = [];

    // Category Name
    if (nameInput.value.trim() === '') {
      isValid = false;
      errorMessages.push('Category Name is required.');
    }

    // Description
    if (descInput.value.trim() === '') {
      isValid = false;
      errorMessages.push('Description is required.');
    }

    // Icon
    if (iconInput.value.trim() === '') {
      isValid = false;
      errorMessages.push('Icon is required.');
    }

    // Image (optional but must be an image type if selected)
    if (imageInput.value) {
      const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
      const file = imageInput.files[0];

      if (file && !allowedTypes.includes(file.type)) {
        isValid = false;
        errorMessages.push('Only JPG, PNG, or GIF image types are allowed for Category Image.');
      }
    }

    if (!isValid) {
      e.preventDefault();
      alert(errorMessages.join('\n'));
    }
  });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const updateForm = document.querySelector('form[action*="category.inc.php"]');
  const updateName = document.getElementById('update_name');
  const updateDesc = document.getElementById('update_description');
  const updateIcon = document.getElementById('update_icon');
  const updateImage = updateForm.querySelector('input[name="update_image"]');

  updateForm.addEventListener('submit', function (e) {
    let isValid = true;
    let errors = [];

    // Check Category Name
    if (updateName.value.trim() === '') {
      isValid = false;
      errors.push('Category Name is required.');
    }

    // Check Description
    if (updateDesc.value.trim() === '') {
      isValid = false;
      errors.push('Description is required.');
    }

    // Check Icon
    if (updateIcon.value.trim() === '') {
      isValid = false;
      errors.push('Icon is required.');
    }

    // Check Image type (only if a new file is selected)
    if (updateImage.value) {
      const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
      const file = updateImage.files[0];

      if (file && !allowedTypes.includes(file.type)) {
        isValid = false;
        errors.push('Only JPG, PNG, or GIF image types are allowed for Category Image.');
      }
    }

    if (!isValid) {
      e.preventDefault();
      alert(errors.join('\n'));
    }
  });
});
</script>


  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript">
  $(document).ready(function() {
    $('.table').DataTable({
      "pageLength": 4, // Show only 4 entries per page
      "lengthMenu": [4, 8, 12, 16], // Allow user to change entries per page
      "ordering": false // (Optional) Disable sorting if not needed
    });
  });
</script>
<script>
$(document).ready(function() {
    $('.update-category-btn').click(function() {
        $('#update_id').val($(this).data('id'));
        $('#update_name').val($(this).data('name'));
        $('#update_description').val($(this).data('description'));
        $('#update_icon').val($(this).data('icon'));
        $('#update_current_image').val($(this).data('image'));
    });
});
</script>
</body>
</html>