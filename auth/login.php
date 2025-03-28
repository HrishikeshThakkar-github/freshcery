<?php include '../include/header.php' ?>
<?php include '../configration/db.config.php' ?>
<?php


if (isset($_SESSION['username'])) {
    echo "<script> window.location.href='" . freshcery . "'</script>"; // so that whenever if a user is already logged in then that user cant acces the login page directly by editing the url
}
if (isset($_POST['login'])) {
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];
    if (empty($username) || empty($password)) {
        echo "<script>alert('one or more inputs are empty');</script>";
    } else {

        $query = "select * from users where username='$username'";
        $login = $pdo->prepare($query);
        $login->execute();
        $fetch = $login->fetch(PDO::FETCH_ASSOC);


        if ($login->rowCount() > 0) {

            if (password_verify($password, $fetch['mypassword'])) {

                $_SESSION['username'] = $fetch['username'];
                $_SESSION['email'] = $fetch['email'];
                $_SESSION['user_id'] = $fetch['id'];
                $_SESSION['image'] = $fetch['image'];

                echo "<script> window.location.href='" . freshcery . "'</script>";
            }
        } else {
            echo "<script> alert ('Email / password is wrong') </script>";
        }
    }
}

?>
<div id="page-content" class="page-content">
    <div class="banner">
        <div class="jumbotron jumbotron-bg text-center rounded-0" style="background-image: url('<?php echo freshcery; ?>/assets/img/bg-header.jpg');">
            <div class="container">
                <h1 class="pt-5">
                    Login Page
                </h1>
                <p class="lead">
                    Save time and leave the groceries to us.
                </p>

                <div class="card card-login mb-5">
                    <div class="card-body">
                        <form class="form-horizontal" method="POST" action="login.php">
                            <div class="form-group row mt-3">
                                <div class="col-md-12">
                                    <input class="form-control" type="text" required="" placeholder="Username" name="username">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <input class="form-control" type="password" required="" placeholder="Password" name="password">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12 d-flex justify-content-between align-items-center">
                                    <!-- <div class="checkbox">
                                            <input id="checkbox0" type="checkbox" name="remember">
                                            <label for="checkbox0" class="mb-0"> Remember Me? </label>
                                        </div> -->
                                    <!-- <a href="login.html" class="text-light"><i class="fa fa-bell"></i> Forgot password?</a> -->
                                </div>
                            </div>
                            <div class="form-group row text-center mt-4">
                                <div class="col-md-12">
                                    <button type="submit" name="login" class="btn btn-primary btn-block text-uppercase">Log In</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include '../include/footer.php' ?>