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
$products = $pdo->prepare("SELECT * FROM orders ");
$products->execute();
$orders = $products->fetchAll(PDO::FETCH_OBJ);

$count = $pdo->prepare("SELECT count(*) FROM orders");
$count->execute();
$_SESSION['orders_count'] = $count->fetchColumn(); 
?>

<body>




  <div id="wrapper">
    <?php include 'nav.php' ?>
    <h1 class="display-3 fw-semibold text-center">ORDERS</h1>
    <div class="container-fluid">
      <table class="table">
        <thead>
          <tr>
            <th>Order-ID</th>
            <th>Zip-code</th>
            <th>phone_number</th>
            <th>Date</th>
            <th>Total</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php if (count($orders) > 0): ?>
            <?php foreach ($orders as $order): ?>
              <tr>

                <td><?php echo $order->id; ?></td>
                <td><?php echo $order->zip_code; ?></td>
                <td><?php echo $order->phone_number; ?></td>
                <td><?php echo $order->created_at; ?></td>
                <td>Rp. <?php echo $order->Total_order_value; ?></td>
                <td><?php echo $order->status; ?></td>
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

  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {
      $('.table').DataTable();
    });
  </script>

  <script type="text/javascript">

  </script>
</body>

</html>

<td><a href="#" class="btn btn-warning text-white text-center ">Update </a></td>
<td><a href="#" class="btn btn-danger text-center">Delete </a></td>