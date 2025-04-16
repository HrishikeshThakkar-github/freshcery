<?php include '../include/header.php';
include '../configration/db.config.php';

$usernameError = "";
if (isset($_SESSION['username'])) {
    echo "<script> window.location.href='" . freshcery . "'</script>";
}
if (isset($_POST['Register'])) {
    $fullname = htmlspecialchars(trim($_POST['fullname']));
    $username = htmlspecialchars(trim($_POST['username']));
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (!isset($_POST['terms'])) {
        $termsError = "You must agree to the Terms & Conditions.";
    }

    if (empty($fullname) || empty($username) || empty($password) || empty($email)) {
        $formError = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $formError = "Invalid email format.";
    } elseif ($password !== $confirm_password) {
        $formError = "Passwords do not match.";
    } else {
        // Check if username exists
        $checkUserQuery = 'SELECT COUNT(*) FROM users WHERE username = :username';
        $checkStmt = $pdo->prepare($checkUserQuery);
        $checkStmt->execute([':username' => $username]);
        $userExists = $checkStmt->fetchColumn();

        if ($userExists > 0) {
            $usernameError = "Username is already taken.";
        } else {
            // Insert the user
            $image = "user.png";
            $query = 'INSERT INTO users (fullname, username, email, mypassword, image) 
                      VALUES (:fullname, :username, :email, :mypassword, :image);';
            $insert = $pdo->prepare($query);
            $insert->execute([
                ':fullname' => $fullname,
                ':username' => $username,
                ':email' => $email,
                ':mypassword' => password_hash($password, PASSWORD_DEFAULT),
                ':image' => $image
            ]);

            header('Location: login');
            exit;
        }
    }
}
?>

<div id="page-content" class="page-content">
    <div class="banner">
        <div class="jumbotron jumbotron-bg text-center rounded-0" style="background-image: url('<?= freshcery; ?>/assets/img/bg-header.jpg');">
            <div class="container">
                <h1 class="pt-5">
                    Register Page
                </h1>
                <p class="lead">
                    Save time and leave the groceries to us.
                </p>

                <div class="card card-login mb-5">
                    <div class="card-body">
                        <form class="form-horizontal" method="POST" action="register" id="myForm">
                            <div class="form-group row mt-3">
                                <div class="col-md-12">
                                    <input class="form-control" type="text" placeholder="Full Name" name="fullname">
                                    <div id="fullnameError" style="color:red;"></div>
                                </div>
                            </div>
                            <div class="form-group row mt-3">
                                <div class="col-md-12">
                                    <input class="form-control" type="email" placeholder="Email" name="email">
                                </div>
                            </div>

                            <div class="form-group row mt-3">
                                <div class="col-md-12">
                                    <input class="form-control" type="text" placeholder="Username" name="username">
                                    <div style="color: red;"><?= $usernameError ?? '' ?></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <input class="form-control" type="password" placeholder="Password" name="password">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <input class="form-control" type="password" placeholder="Confirm Password" name="confirm_password">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                        <div class="checkbox">
                                            <input id="checkbox0" type="checkbox" name="terms">
                                            <label for="checkbox0" class="mb-0">I Agree with <a class="text-light">Terms & Conditions</a> </label>
                                            <div id="checkboxError" style="color:red; display:none; text-decoration: double;">You must agree to the terms.</div>
                                        </div>
                                    </div>
                            </div>
                            <div class="form-group row text-center mt-4">
                                <div class="col-md-12">
                                    <button type="submit" name="Register" class="btn btn-primary btn-block text-uppercase">Register</button>
                                </div>
                            </div>
                        </form>
                        <div class="login-form">
                            <h5>already a user?<a style="color: #E91E63;" href="<?= freshcery; ?>/login"> login</a></h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // FULL NAME VALIDATION
    const fullName = document.querySelector('input[name="fullname"]');
    fullName.focus();
    fullName.addEventListener("blur", function() {
        const errorDiv = document.getElementById("fullnameError");
        if (this.value.trim() === "") {
            errorDiv.textContent = "Full Name is required.";
        } else {
            errorDiv.textContent = "";
        }
    });

    // EMAIL VALIDATION
    const email = document.querySelector('input[name="email"]');
    const emailError = document.createElement("div");
    emailError.style.color = "red";
    email.parentNode.appendChild(emailError);

    email.addEventListener("blur", function() {
        const pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!pattern.test(this.value.trim())) {
            emailError.textContent = "Please enter a valid email.";
        } else {
            emailError.textContent = "";
        }
    });

    // USERNAME VALIDATION
    const username = document.querySelector('input[name="username"]');
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

    // PASSWORD VALIDATION
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

    // CONFIRM PASSWORD VALIDATION
    const confirmPassword = document.querySelector('input[name="confirm_password"]');
    const confirmPasswordError = document.createElement("div");
    confirmPasswordError.style.color = "red";
    confirmPassword.parentNode.appendChild(confirmPasswordError);

    confirmPassword.addEventListener("blur", function() {
        if (this.value.trim() !== password.value.trim()) {
            confirmPasswordError.textContent = "Passwords do not match.";
        } else {
            confirmPasswordError.textContent = "";
        }
    });

    // TERMS CHECKBOX VALIDATION ON SUBMIT (already present)
    document.getElementById("myForm").addEventListener("submit", function(e) {
        var checkbox = document.getElementById("checkbox0");
        var error = document.getElementById("checkboxError");

        if (!checkbox.checked) {
            e.preventDefault();
            error.style.display = "block";
        } else {
            error.style.display = "none";
        }
    });
});
</script>

<?php include '../include/footer.php' ?>
