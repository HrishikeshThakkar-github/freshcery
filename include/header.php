<?php
session_start();
define("freshcery", "http://freshcery");
include 'cart_function.php'; 
if (isset($_SESSION['user_id'])) {
    $cart_items = get_cart_items($pdo, $_SESSION['user_id']);
    
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Freshcery | Groceries Organic Store</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css">
    <link href="<?= freshcery; ?>/assets/fonts/sb-bistro/sb-bistro.css" rel="stylesheet" type="text/css">
    <link href="<?= freshcery; ?>/assets/fonts/font-awesome/font-awesome.css" rel="stylesheet" type="text/css">

    <link rel="stylesheet" type="text/css" media="all" href="<?= freshcery; ?>/assets/packages/bootstrap/bootstrap.css">
    <link rel="stylesheet" type="text/css" media="all" href="<?= freshcery; ?>/assets/packages/o2system-ui/o2system-ui.css">
    <link rel="stylesheet" type="text/css" media="all" href="<?= freshcery; ?>/assets/packages/owl-carousel/owl-carousel.css">
    <link rel="stylesheet" type="text/css" media="all" href="<?= freshcery; ?>/assets/packages/cloudzoom/cloudzoom.css">
    <link rel="stylesheet" type="text/css" media="all" href="<?= freshcery; ?>/assets/packages/thumbelina/thumbelina.css">
    <link rel="stylesheet" type="text/css" media="all" href="<?= freshcery; ?>/assets/packages/bootstrap-touchspin/bootstrap-touchspin.css">
    <link rel="stylesheet" type="text/css" media="all" href="<?= freshcery; ?>/assets/css/theme.css">
    <!-- Latest FontAwesome (CDN) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="icon" type="image/png" href="<?= freshcery; ?>/assets/img/favicon.jpeg">


</head>

<body>
    <div class="page-header">
        <!--=============== Navbar ===============-->
        <nav class="navbar fixed-top navbar-expand-md navbar-dark bg-transparent" id="page-navigation">
            <div class="container">
                <!-- Navbar Brand -->
                <a href="<?= freshcery; ?>/index" class="navbar-brand">
                    <img src="<?= freshcery; ?>/assets/img/logo/logo.png" alt="">
                </a>

                <!-- Toggle Button -->
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarcollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarcollapse">
                    <!-- Navbar Menu -->
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a href="<?= freshcery; ?>/shop" class="nav-link">Shop</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= freshcery; ?>/faq" class="nav-link">FAQ</a>
                        </li>
                        <?php if (!isset($_SESSION['username'])): ?>
                            <li class="nav-item">
                                <a href="<?= freshcery; ?>/register" class="nav-link">Register</a>
                            </li>
                            <li class="nav-item">
                                <a href="/login" class="nav-link">Login</a>
                            </li>
                        <?php else : ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="javascript:void(0)" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <div class="avatar-header"><img src="<?= freshcery; ?>/assets/img/logo/avatar.png"></div> <?= $_SESSION['username']; ?>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="<?= freshcery; ?>/transaction">Transactions History</a>
                                    <a class="dropdown-item" href="<?= freshcery; ?>/logout">Logout</a>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a href="<?= freshcery; ?>/cart" class="nav-link" data-toggle="" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-shopping-basket"></i> <span class="badge badge-primary"><?= $cart_items ;?></span>
                                </a>
                            </li>
                        <?php endif;  ?>
                    </ul>
                </div>

            </div>
        </nav>
    </div>