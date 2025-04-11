<?php require_once '../configration/db.config.php';
require_once '../include/header.php';

if (isset($_SESSION['username'])) {
    echo "<script> window.location.href='" . freshcery . "'</script>"; // so that whenever if a user is already logged in then that user cant acces the login page directly by editing the url
}
$errorMessage = "";

if (isset($_POST['login'])) {
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $errorMessage = "One or more inputs are empty.";
    } else {
        $query = "SELECT * FROM users WHERE username='$username'";
        $login = $pdo->prepare($query);
        $login->execute();
        $fetch = $login->fetch(PDO::FETCH_ASSOC);

        if ($login->rowCount() > 0) {
            if (password_verify($password, $fetch['mypassword'])) {
                $_SESSION['username'] = $fetch['username'];
                $_SESSION['email'] = $fetch['email'];
                $_SESSION['user_id'] = $fetch['id'];
                $_SESSION['image'] = $fetch['image'];

                if ($fetch['role'] === 'admin') {
                    echo "<script> window.location.href='/dashboard'; </script>";
                } else {
                    echo "<script> window.location.href='" . freshcery . "';</script>";
                }
                exit();
            } else {
                $errorMessage = "Incorrect password.";
            }
        } else {
            $errorMessage = "User not found.";
        }
    }
}
?>
<div id="page-content" class="page-content">
    <div class="banner">
        <div class="jumbotron jumbotron-bg text-center rounded-0" style="background-image: url('<?= freshcery; ?>/assets/img/bg-header.jpg');">
            <div class="container">
                <h1 class="pt-5">
                    Login Page
                </h1>
                <p class="lead">
                    Save time and leave the groceries to us.
                </p>

                <div class="card card-login mb-5">
                    <div class="card-body">
                        <form class="form-horizontal" method="POST" action="login">
                        <?php if (!empty($errorMessage)): ?>
                                <div class="custom-alert mt-3">
                                    <?= $errorMessage ?>
                                </div>
                            <?php endif; ?>
                            <div class="form-group row mt-3">
                                <div class="col-md-12">
                                    <input class="form-control" type="text" placeholder="Username" name="username">
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
                        <div class="register-form">
                            <h5>Not a user?<a style="color: #E91E63;" href="<?= freshcery; ?>/register"> register</a></h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {

        const username = document.querySelector('input[name="username"]');
        username.focus();
        const usernameError = document.createElement("div");
        usernameError.style.color = "red";
        username.parentNode.appendChild(usernameError);

        username.addEventListener("blur", function() {
            if (this.value.trim() === "") {
                usernameError.textContent = "Username is required.";
            } else {
                usernameError.textContent = "";
            }
        });

        const password = document.querySelector('input[name="password"]');
        const passwordError = document.createElement("div");
        passwordError.style.color = "red";
        password.parentNode.appendChild(passwordError);

        password.addEventListener("blur", function() {
            if (this.value.trim() === "") {
                passwordError.textContent = "Password is required.";
            } else {
                passwordError.textContent = "";
            }
        });
    });
</script>
<?php include '../include/footer.php' ?>