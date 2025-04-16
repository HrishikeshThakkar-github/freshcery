<?php
session_start();
define("freshcery", "http://freshcery");
?>
<?php include '../configration/db.config.php' ?>
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
  <link href="http://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
  <link href="/admin-panel/styles/style.css" rel="stylesheet">
  <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>


  <div id="wrapper">
    <?php include 'nav.php'?>
    <div class="container-fluid">

      <div class="row">
        <div class="col-md-4">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Products</h5>
              <p class="card-text">number of products:     <?= $_SESSION['product_count']?></p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Orders</h5>
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