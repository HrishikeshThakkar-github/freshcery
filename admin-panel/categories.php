<?php
session_start();
define("freshcery", "http://freshcery");
?>
<?php include '../configration/db.config.php' ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <!-- This file has been downloaded from Bootsnipp.com. Enjoy! -->
  <title>Admin Panel</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="http://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
  <link href="styles/style.css" rel="stylesheet">

  <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>

  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

  <!-- DataTables JS -->
  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

  <!-- DataTables Buttons (for export options) -->
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
          <?php if (count($categories) > 0): ?>
            <?php foreach ($categories as $category): ?>
              <tr>

                <td><?php echo $category->id; ?></td>
                <td><img src="<?php echo freshcery; ?>/assets/img/<?php echo htmlspecialchars($category->image); ?>" height="100px" width="100px"></td>
                <td><?php echo $category->name; ?></td>
                <td><?php echo $category->description; ?></td>
                <td><a href="#" class="btn btn-warning text-white text-center ">Update </a> <a href="#" class="btn btn-danger text-center">Delete </a></td>
              </tr>
            <?php endforeach; ?>
          <?php else : ?>
            <div class="alert alert-danger bg-danger text-white text-centre">
              <h1>error!</h1>
            </div>
          <?php endif; ?>
        </tbody>
      </table>



    </div>
  </div>

  <form action="../admin-panel/include/categories_add.inc.php" method="POST">
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add Categories</h5>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="id">category-ID</label>
              <input type="number" name="id" class="form-control">
            </div>
            <div class="form-group">
              <label for="name">Category-Name</label>
              <input type="text" name="name" class="form-control">
            </div>
            <div class="form-group">
              <label for="icon">Icon</label>
              <input type="text" name="icon" class="form-control" placeholder="hint: bistro-vegetables">
            </div>
            <div class="form-group">
              <label for="image">image</label>
              <input type="file" name="image" class="form-control">
            </div>
            <div class="form-group">
              <label for="description">Description</label>
              <input type="text" name="description" class="form-control">
            </div>

          </div>
          <div class="modal-footer">
            <input type="submit" class="btn btn-success" name="add_student" value="ADD">
            <a href="<?php echo freshcery; ?>/admin-panel/categories.php" class="btn btn-danger" data-bs-dismiss="modal">Close</a>

          </div>
        </div>
      </div>
    </div>
  </form>

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


  <script type="text/javascript">

  </script>
</body>

</html>