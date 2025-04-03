<?php
session_start();
define("freshcery", "http://freshcery");
?>
<?php include '../configration/db.config.php' ?>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<link rel="stylesheet" href="styles/style.css">

<!-- Navbar -->
<nav class="navbar header-top fixed-top navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="#" style="font-style: italic;">
      <img src="../assets/img/logo/logo-white.png" alt="Freschcery admin-panel" height="30px" width="114px">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText"
      aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarText">
      <ul class="navbar-nav side-nav">
        <li class="nav-item">
          <a class="nav-link text-white" href="<?php echo freshcery; ?>/admin-panel/admin.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo freshcery; ?>/admin-panel/categories.php">Categories</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo freshcery; ?>/admin-panel/products.php">Products</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo freshcery; ?>/admin-panel/orders.php">Orders</a>
        </li>
      </ul>

      <ul class="navbar-nav ml-md-auto d-md-flex">
        <li class="nav-item">
          <a class="nav-link" href="<?php echo freshcery; ?>/auth/admin-logout.php">Logout</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link" id="navbarDropdown" style="color:white;">
            <?php echo $_SESSION['username'] ?>
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- Load Scripts (jQuery, Popper.js, Bootstrap) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
