<?php include 'include/header.php' ?>
<?php include 'configration/db.config.php' ?>

<?php
require('payment_system/config.php');
$products = $pdo->prepare("select * from cart where user_id=$_SESSION[user_id]");
$products->execute();

$cart_products = $products->fetchAll(PDO::FETCH_OBJ);
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
            <div class="row" style="display: flex; justify-content: center; align-items: center;">
                <!-- <div class="col-xs-12 col-sm-7">
                        <h5 class="mb-3">BILLING DETAILS</h5>

                        <form action="#" class="bill-detail">
                            <fieldset>
                                <div class="form-group row">
                                    <div class="col">
                                        <input class="form-control" placeholder="Name" type="text">
                                    </div>
                                    <div class="col">
                                        <input class="form-control" placeholder="Last Name" type="text">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Company Name" type="text">
                                </div>
                                <div class="form-group">
                                    <textarea class="form-control" placeholder="Address"></textarea>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Town / City" type="text">
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="State / Country" type="text">
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Postcode / Zip" type="text">
                                </div>
                                <div class="form-group row">
                                    <div class="col">
                                        <input class="form-control" placeholder="Email Address" type="email">
                                    </div>
                                    <div class="col">
                                        <input class="form-control" placeholder="Phone Number" type="tel">
                                    </div>
                                </div>
                              
                                <div class="form-group">
                                    <textarea class="form-control" placeholder="Order Notes"></textarea>
                                </div>
                            </fieldset>
                        </form>

                    </div> -->
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
                                            <?php $_SESSION['payment'] = $checkout_payment ?>
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
                                data-amount="<?php echo $_SESSION["payment"]*100 ?>"
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