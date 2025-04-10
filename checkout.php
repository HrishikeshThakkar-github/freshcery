<?php
session_start();

$errorMessage = "";
include 'include/header.php';
include 'configration/db.config.php';
require('payment_system/config.php');
$products = $pdo->prepare("SELECT * FROM cart WHERE user_id = :user_id");
$products->execute([':user_id' => $_SESSION['user_id']]);
$cart_products = $products->fetchAll(PDO::FETCH_OBJ);
if (isset($_POST['order_details'])) {
    $name = htmlspecialchars($_POST['name']);
    $address = htmlspecialchars($_POST['address']);
    $city = htmlspecialchars($_POST['city']);
    $country = htmlspecialchars($_POST['country']);
    $zip_code = htmlspecialchars($_POST['zip_code']);
    $email = $_POST['email'];

    $phone_number = htmlspecialchars($_POST['phone_number']);
    $order_notes = htmlspecialchars($_POST['order_notes']);
    $user_id = $_SESSION['user_id'];
    $Total_order_value = $_SESSION['payment']; //this signifies the total order total
    if (empty($name) || empty($address) || empty($city)  || empty($country) || empty($zip_code) || empty($email) || empty($phone_number)) {
        $errorMessage = "One or more inputs are empty.";
    } else {
        $query = 'INSERT INTO orders (name,address, city, country, zip_code,email,phone_number,order_notes,Total_order_value,user_id) VALUES ( :name, :address, :city, :country, :zip_code, :email, :phone_number, :order_notes, :Total_order_value, :user_id);';
        $insert = $pdo->prepare($query);
        $insert->execute([
            ':name' => $name,
            ':address' => $address,
            ':city' => $city,
            ':country' => $country,
            ':zip_code' => $zip_code,
            ':email' => $email,
            ':phone_number' => $phone_number,
            ':order_notes' => $order_notes,
            ':Total_order_value' => $Total_order_value,
            ':user_id' => $user_id,
        ]);

        $_SESSION['Total_order_value'] = $Total_order_value;
        $errorMessage = "Order details verified";
        if (isset($_SESSION['Total_order_value'])) {
            $order_details = $pdo->prepare('select id FROM orders WHERE user_id = :user_id order by created_at desc limit 1;');
            $order_details->execute([':user_id' => $_SESSION['user_id']]);
            $order_details_id=$order_details->fetch(PDO::FETCH_ASSOC);
            $_SESSION['order_details_id']=$order_details_id;
        }
        
    }
}

if (isset($_SESSION['Total_order_value'])) {
    $order_details = $pdo->prepare('select pro_id FROM cart WHERE user_id = :user_id');
    $order_details->execute([':user_id' => $_SESSION['user_id']]);
}


$shipping_charges = 80;
?>

<div id="page-content" class="page-content">
    <div class="banner">
        <div class="jumbotron jumbotron-bg text-center rounded-0" style="background-image: url('assets/img/bg-header.jpg');">
            <div class="container">
                <h1 class="pt-5">
                    Checkout
                </h1>
                <p class="lead">
                    Save time and leave the groceries to us.
                </p>
            </div>
        </div>
    </div>

    <section id="checkout">
        <div class="container ">
            <div class="row">
                <div class=" col-xs-12 col-sm-7">
                    <h5 class="mb-3">BILLING DETAILS</h5>

                    <form action="checkout" method="POST" class="bill-detail">
                        <fieldset>
                            <div class="form-group">
                                <input class="form-control" name="name" placeholder="Name" type="text">
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" name="address" placeholder="Address"></textarea>
                            </div>
                            <div class="form-group">
                                <input class="form-control" name="city" placeholder="Town / City" type="text">
                            </div>
                            <div class="form-group">
                                <input class="form-control" name="country" placeholder="State / Country" type="text">
                            </div>
                            <div class="form-group">
                                <input class="form-control" name="zip_code" placeholder="Postcode / Zip" type="text">
                            </div>
                            <div class="form-group row">
                                <div class="col">
                                    <input class="form-control" name="email" placeholder="Email Address" type="email">
                                </div>
                                <div class="col">
                                    <input class="form-control" name="phone_number" placeholder="Phone Number" type="tel">
                                </div>
                            </div>

                            <div class="form-group">
                                <textarea class="form-control" name="order_notes" placeholder="Order Notes"></textarea>
                            </div>
                        </fieldset>
                        <button name="order_details" class="btn btn-primary float-right">submit order details</button>
                    </form>
                </div>

                <div class="col-xs-12 col-sm-5">
                    <div class="holder ">
                        <h5 class="mb-3">YOUR ORDER</h5>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Products</th>
                                        <th class="text-right">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (isset($_SESSION['username'])): ?>
                                        <?php if (count($cart_products) > 0): ?>
                                            <?php
                                            foreach ($cart_products as $PRODUCT_IN_CART):
                                            ?>
                                                <tr>
                                                    <td>
                                                        <?= $PRODUCT_IN_CART->pro_title; ?> x <?= $PRODUCT_IN_CART->pro_qty; ?><br>
                                                        <!-- <small>1000g</small> -->
                                                    </td>

                                                    <td class="text-right total_price"> Rp.
                                                        <?php
                                                        //$numeric_price = (float) explode("/", $PRODUCT_IN_CART->pro_price)[0];
                                                        echo $PRODUCT_IN_CART->pro_total;
                                                        ?>
                                                    </td>

                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else : ?>
                                            <div class="alert alert-danger bg-danger text-white text-centre">
                                                <h1>error!</h1>
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </tbody>
                                <tfooter>
                                    <tr>
                                        <td>
                                            <strong>Cart Subtotal</strong>
                                        </td>
                                        <td class="text-right">
                                            Rp. <?= $_SESSION['price']; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>Shipping</strong>
                                        </td>
                                        <td class="text-right">
                                            Rp. <?= $shipping_charges; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>ORDER TOTAL</strong>
                                        </td>
                                        <td class="text-right">
                                            <strong>Rp. <?= $checkout_payment = $shipping_charges + $_SESSION['price']; ?></strong>
                                            <?php $_SESSION['payment'] = $checkout_payment //to be passed on to the stripe payment
                                            ?>
                                        </td>
                                    </tr>
                                </tfooter>
                            </table>
                        </div>


                    </div>
                    <?php
                    if (isset($_SESSION['Total_order_value'])) : ?>


                        <p class="text-right mt-3">
                            <input checked="" type="checkbox"> I’ve read &amp; accept the <a href="#">terms &amp; conditions</a>
                        </p>
                        <a href="/checkout" class="btn btn-primary float-right">PROCEED TO CHECKOUT <i class="fa fa-check"></i>
                            <form action="submit" method="post">
                                <script
                                    src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                                    data-key="<?= $publishableKey ?>"
                                    data-amount="<?= $_SESSION["payment"] * 100 ?>"
                                    data-name="Freshcery"
                                    data-description="Stripe Payment Gateway"
                                    data-image="<?= freshcery; ?>/assets/img/logo/logo.png"
                                    data-currency="inr"
                                    data-email="hrishi.pvt@gmail.com">
                                </script>

                            </form>
                        </a>

                        <br><br><br><br><br><br><br>

                        <div class="custom-alert mt-3">
                            <?= $errorMessage ?>
                        </div>
                    <?php endif; ?>
                    <div class="clearfix">
                    </div>
                </div>
            </div>
    </section>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // FULL NAME VALIDATION
        const name = document.querySelector('input[name="name"]');
        name.focus();
        const nameError = document.createElement("div");
        nameError.style.color = "red";
        name.parentNode.appendChild(nameError);
        nameError.id = "nameError";
        name.addEventListener("blur", function() {
            const errorDiv = document.getElementById("nameError");
            if (this.value.trim() === "") {
                errorDiv.textContent = "Name is required.";
            } else {
                errorDiv.textContent = "";
            }
        });

        const address = document.querySelector('textarea[name="address"]');


        const addressError = document.createElement("div");
        addressError.style.color = "red";
        address.parentNode.appendChild(addressError);

        address.addEventListener("blur", function() {
            const value = this.value.trim();
            if (value === "") {
                addressError.textContent = "Address is required.";
            } else if (value.length < 20) {
                addressError.textContent = "Address must be at least 20 characters.";
            } else if (value.length > 100) {
                addressError.textContent = "Address must be less than 100 characters.";
            } else {
                addressError.textContent = "";
            }
        });

        const city = document.querySelector('input[name="city"]');
        const cityError = document.createElement("div");
        cityError.style.color = "red";
        city.parentNode.appendChild(cityError);

        city.addEventListener("blur", function() {
            const value = this.value.trim().toLowerCase();

            if (value === "") {
                cityError.textContent = "City is required.";
            } else if (value !== "ahmedabad") {
                cityError.textContent = "Currently, we only deliver in Ahmedabad.";
            } else {
                cityError.textContent = "";
            }
        });

        const country = document.querySelector('input[name="country"]');
        const countryError = document.createElement("div");
        countryError.style.color = "red";
        country.parentNode.appendChild(countryError);

        country.addEventListener("blur", function() {
            const value = this.value.trim().toLowerCase();

            if (value === "") {
                countryError.textContent = "Country is required.";
            } else if (value !== "india") {
                countryError.textContent = "Currently, we only operate in India.";
            } else {
                countryError.textContent = "";
            }
        });

        const zipcode = document.querySelector('input[name="zip_code"]');
        const zipError = document.createElement("div");
        zipError.style.color = "red";
        zipcode.parentNode.appendChild(zipError);

        zipError.id = "zipError";

        const serviceablePins = [
            "380001", "380002", "380003", "380004", "380005", "380006", "380007", "380008", "380009",
            "380010", "380013", "380014", "380015", "380016", "380017", "380018", "380019", "380021",
            "380022", "380023", "380024", "380025", "380026", "380027", "380028", "380050", "382415",
            "382418", "382350", "382345"
        ];

        zipcode.addEventListener("blur", function() {
            const value = this.value.trim();
            const zipRegex = /^[1-9][0-9]{5}$/;

            if (value === "") {
                zipError.textContent = "PIN code is required.";
            } else if (!zipRegex.test(value)) {
                zipError.textContent = "PIN code must be exactly 6 digits (e.g., 380001).";
            } else if (!serviceablePins.includes(value)) {
                zipError.textContent = "Sorry, we are currently delivering only in Ahmedabad";
            } else {
                zipError.textContent = "";
            }
        });


        // EMAIL VALIDATION
        const email = document.querySelector('input[name="email"]');
        const emailError = document.createElement("div");
        emailError.style.color = "red";
        email.parentNode.appendChild(emailError);

        email.addEventListener("blur", function() {
            const value = this.value.trim();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (value === "") {
                emailError.textContent = "Email is required.";
            } else if (!emailRegex.test(value)) {
                emailError.textContent = "Enter a valid email address.";
            } else {
                emailError.textContent = "";
            }
        });

        // PHONE NUMBER VALIDATION
        const phone = document.querySelector('input[name="phone_number"]');
        const phoneError = document.createElement("div");
        phoneError.style.color = "red";
        phone.parentNode.appendChild(phoneError);

        phone.addEventListener("blur", function() {
            const value = this.value.trim();
            const phoneRegex = /^[6-9][0-9]{9}$/;

            if (value === "") {
                phoneError.textContent = "Phone number is required.";
            } else if (!phoneRegex.test(value)) {
                phoneError.textContent = "Enter a valid 10-digit Indian phone number.";
            } else {
                phoneError.textContent = "";
            }
        });



        const order_notes = document.querySelector('textarea[name="order_notes"]');
        const notesError = document.createElement("div");
        notesError.style.color = "red";
        orderNotes.parentNode.appendChild(notesError);

        orderNotes.addEventListener("blur", function() {
            const value = this.value.trim();

            if (value.length === 0) {
                notesError.textContent = "Order notes cannot be empty.";
            } else if (value.length > 100) {
                notesError.textContent = "Order notes must not exceed 100 characters.";
            } else {
                notesError.textContent = "";
            }
        });

    });
</script>
<?php include 'include/footer.php' ?>