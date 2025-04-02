<?php 
session_start(); 
?>

<?php include 'include/header.php' ?>
<?php include 'configration/db.config.php' ?>

<?php

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
    $user_id=$_SESSION['user_id'];
    $Total_order_value=$_SESSION['payment'];//this signifies the total order total
    if (empty($name) || empty($address) || empty($city)  || empty($country) || empty($zip_code) || empty($email) || empty($phone_number)) {
        echo "<script>alert('one or more required inputs are empty');</script>";
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

        echo "<script>alert('order details verified');</script>";

    }
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

    <section id="checkout ">
        <div class="container ">
            <div class="row" ">
                <div class=" col-xs-12 col-sm-7">
                <h5 class="mb-3">BILLING DETAILS</h5>

                <form action="checkout.php" method="POST" class="bill-detail">
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
                                                    <?php echo $PRODUCT_IN_CART->pro_title; ?> x <?php echo $PRODUCT_IN_CART->pro_qty; ?><br>
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
                                        Rp. <?php echo $_SESSION['price']; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Shipping</strong>
                                    </td>
                                    <td class="text-right">
                                        Rp. <?php echo $shipping_charges; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>ORDER TOTAL</strong>
                                    </td>
                                    <td class="text-right">
                                        <strong>Rp. <?php echo $checkout_payment = $shipping_charges + $_SESSION['price']; ?></strong>
                                        <?php $_SESSION['payment'] = $checkout_payment //to be passed on to the stripe payment?>
                                    </td>
                                </tr>
                            </tfooter>
                        </table>
                    </div>


                </div>
                <p class="text-right mt-3">
                    <input checked="" type="checkbox"> I’ve read &amp; accept the <a href="#">terms &amp; conditions</a>
                </p>
                <a href="#" class="btn btn-primary float-right">PROCEED TO CHECKOUT <i class="fa fa-check"></i>
                    <form action="submit.php" method="post">
                        <script
                            src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                            data-key="<?php echo $publishableKey ?>"
                            data-amount="<?php echo $_SESSION["payment"] * 100 ?>"
                            data-name="Freshcery"
                            data-description="Stripe Payment Gateway"
                            data-image="<?php echo freshcery; ?>/assets/img/logo/logo.png"
                            data-currency="inr"
                            data-email="hrishi.pvt@gmail.com">
                        </script>

                    </form>
                </a>
                <div class="clearfix">
                </div>
            </div>
        </div>
    </section>
</div>
<?php include 'include/footer.php' ?>