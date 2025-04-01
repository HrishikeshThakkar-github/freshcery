<?php include '../include/header.php' ?>
<?php include '../configration/db.config.php' ?>

<?php
if (isset($_SESSION['username'])) {
    echo "<script> window.location.href='" . freshcery . "'</script>";
}
if (isset($_POST['Register'])) {
    $fullname = htmlspecialchars($_POST['fullname']);
    $username = htmlspecialchars($_POST['username']);
    $email = $_POST['email'];
    $password = $_POST['password'];
    if (empty($fullname) || empty($username) || empty($password)  || empty($email)) {
        echo "<script>alert('one or more inputs are empty');</script>";
    } else {
        $image = "user.png";
        if ($password == $_POST['confirm_password']) {
            $confirm_password = $_POST['confirm_password'];



            $query = 'INSERT INTO users (fullname,username, email, mypassword, image) VALUES (:fullname ,:username, :email, :mypassword, :image);';
            $insert = $pdo->prepare($query);
            $insert->execute([
                ':fullname' => $fullname,
                ':username' => $username,
                ':email' => $email,
                ':mypassword' => password_hash($password, PASSWORD_DEFAULT),
                ':image' => $image
            ]);

            //$result = $insert->fetch(PDO::FETCH_ASSOC);
            //header("Location: ".freshcery."/auth/login.php");
            echo "<script> window.location.href='login.php'</script>";
        } else {
            echo "<script>alert('password didnot match');</script>";
        }
    }
}

?>
<div id="page-content" class="page-content">
    <div class="banner">
        <div class="jumbotron jumbotron-bg text-center rounded-0" style="background-image: url('<?php echo freshcery; ?>/assets/img/bg-header.jpg');">
            <div class="container">
                <h1 class="pt-5">
                    Register Page
                </h1>
                <p class="lead">
                    Save time and leave the groceries to us.
                </p>

                <div class="card card-login mb-5">
                    <div class="card-body">
                        <form class="form-horizontal" method="POST" action="register.php">
                            <div class="form-group row mt-3">
                                <div class="col-md-12">
                                    <input class="form-control" type="text" required="" placeholder="Full Name" name="fullname">
                                </div>
                            </div>
                            <div class="form-group row mt-3">
                                <div class="col-md-12">
                                    <input class="form-control" type="email" required="" placeholder="Email" name="email">
                                </div>
                            </div>

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
                                <div class="col-md-12">
                                    <input class="form-control" type="password" required="" placeholder="Confirm Password" name="confirm_password">
                                </div>
                            </div>
                            <div class="form-group row">
                                <!-- <div class="col-md-12">
                                        <div class="checkbox">
                                            <input id="checkbox0" type="checkbox" name="terms">
                                            <label for="checkbox0" class="mb-0">I Agree with <a href="terms.html" class="text-light">Terms & Conditions</a> </label>
                                        </div>
                                    </div> -->
                            </div>
                            <div class="form-group row text-center mt-4">
                                <div class="col-md-12">
                                    <button type="submit" name="Register" class="btn btn-primary btn-block text-uppercase">Register</button>
                                </div>
                            </div>
                        </form>
                        <div class="login-form">
                            <h5>already a user?<a style="color: #E91E63;" href="<?php echo freshcery; ?>/auth/login.php"> login</a></h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include '../include/footer.php' ?>