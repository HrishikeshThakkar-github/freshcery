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
  <link href="http://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
  <link href="styles/style.css" rel="stylesheet">
  <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>


  <div id="wrapper">
    <!-- <nav class="navbar header-top fixed-top navbar-expand-lg  navbar-dark bg-dark">
      <div class="container">
        <a class="navbar-brand" href="#" style="font-style: italic;"><img src="../assets/img/logo/logo-white.png" alt=" Freschcery admin-panel" height="30px" width="114px">Admin panel</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText"
          aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarText">
          <ul class="navbar-nav side-nav">
            <li class="nav-item">
              <a class="nav-link text-white" style="margin-left: 20px;" href="<?= freshcery; ?>/admin-panel/admin.php">Home
                <span class="sr-only">(current)</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?= freshcery; ?>/admin-panel/categories.php" style="margin-left: 20px;">Categories</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?= freshcery; ?>/admin-panel/products.php" style="margin-left: 20px;">Products</a>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="<?= freshcery; ?>/admin-panel/orders.php" style="margin-left: 20px;">Orders</a>
            </li>

          </ul>
          <ul class="navbar-nav ml-md-auto d-md-flex">
            <li class="nav-item">
              <a class="nav-link" href="admin.php">Home
                <span class="sr-only">(current)</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?= freshcery; ?>/auth/admin-logout.php"">logout
                <span class="sr-only">(current)</span>
              </a>
            </li>
            <li class="nav-item dropdown">
              
              <a class="nav-link"  id="navbarDropdown" role="button"  aria-haspopup="true" aria-expanded="false" style="color:white">
                <?= $_SESSION['username']?>
              </a>
            </li>


          </ul>
        </div>
      </div>
    </nav> -->
    <?php include 'nav.php'?>
    <div class="container-fluid">

      <div class="row">
        <div class="col-md-4">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Products</h5>
              <!-- <h6 class="card-subtitle mb-2 text-muted">Bootstrap 4.0.0 Snippet by pradeep330</h6> -->
              <p class="card-text">number of products:     <?= $_SESSION['product_count']?></p>

            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Orders</h5>
              <!-- <h6 class="card-subtitle mb-2 text-muted">Bootstrap 4.0.0 Snippet by pradeep330</h6> -->
              <p class="card-text">number of orders:  <?= $_SESSION['orders_count']?></p>

            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Categories</h5>

              <p class="card-text">number of categories:  <?= $_SESSION['categories_count']?></p>

            </div>
          </div>
        </div>
      </div>
      <div class="row ">
      <div class="col-md-6">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Products per Category</h5>
            <canvas id="productsByCategory"></canvas>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Top 5 Ordered Products</h5>
            <canvas id="topOrderedProducts"></canvas>
          </div>
        </div>
      </div>
    </div>

    </div>
  </div>
  <script>
    // Bar Chart: Products per Category
    const categoryData = {
      labels: ['Electronics', 'Fashion', 'Home Decor', 'Sports', 'Books'],
      datasets: [{
        label: 'Products per Category',
        data: [120, 95, 78, 50, 40],
        backgroundColor: ['#3498db', '#e74c3c', '#2ecc71', '#f1c40f', '#9b59b6'],
        borderColor: ['#2980b9', '#c0392b', '#27ae60', '#f39c12', '#8e44ad'],
        borderWidth: 1
      }]
    };
    new Chart(document.getElementById('productsByCategory'), {
      type: 'bar',
      data: categoryData,
      options: {
        responsive: true,
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });

    // Line Chart: Top 5 Ordered Products
    const topProductsData = {
      labels: ['Laptop', 'Shoes', 'Smartphone', 'Watch', 'Backpack'],
      datasets: [{
        label: 'Orders',
        data: [250, 180, 150, 130, 100],
        borderColor: '#e67e22',
        backgroundColor: 'rgba(230, 126, 34, 0.2)',
        fill: true,
        tension: 0.3
      }]
    };
    new Chart(document.getElementById('topOrderedProducts'), {
      type: 'line',
      data: topProductsData,
      options: {
        responsive: true,
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });
  </script>
  <script type="text/javascript">

  </script>
</body>

</html>