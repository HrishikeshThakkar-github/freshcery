<?php
include 'include/header.php';
include 'configration/db.config.php';
$products = $pdo->prepare("select * from cart where user_id=$_SESSION[user_id]");
$products->execute();
$cart_products = $products->fetchAll(PDO::FETCH_OBJ);
if (isset($_POST['submit'])) {
    $inp_price = $_POST['inp_price'];
    $_SESSION['price'] = $inp_price;

    echo "<script>window.location.href='" . freshcery . "/checkout';</script>";
}
?>
<div id="page-content" class="page-content">
    <div class="banner">
        <div class="jumbotron jumbotron-bg text-center rounded-0" style="background-image: url('<?= freshcery; ?>/assets/img/bg-header.jpg');">
            <div class="container">
                <h1 class="pt-5">
                    Your Cart
                </h1>
                <p class="lead">
                    Save time and leave the groceries to us.
                </p>
            </div>
        </div>
    </div>
    <section id="cart">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th width="10%"></th>
                                    <th>Products</th>
                                    <th>Price</th>
                                    <th width="15%">Quantity</th>
                                    <th width="15%">Update</th>
                                    <th>Subtotal</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($_SESSION['username'])&& count($cart_products) > 0):
                                        foreach ($cart_products as $PRODUCT_IN_CART):
                                        ?>
                                            <tr>
                                                <td>
                                                    <img src="<?= freshcery; ?>/assets/img/<?= $PRODUCT_IN_CART->pro_image; ?>" width="60">
                                                </td>
                                                <td>
                                                    <?= $PRODUCT_IN_CART->pro_title; ?><br>
                                                </td>
                                                <td class="pro_price">
                                                    Rp <?= $PRODUCT_IN_CART->pro_price; ?>
                                                </td>
                                                <td>
                                                    <input class="pro_qty form-control" type="number" min="1" data-bts-button-down-class="btn btn-primary" data-bts-button-up-class="btn btn-primary" value="<?= $PRODUCT_IN_CART->pro_qty; ?>" name="vertical-spin">
                                                </td>
                                                <td>
                                                    <a data-prod-id="<?= $PRODUCT_IN_CART->id; ?>" class="btn-update btn btn-primary">UPDATE</a>
                                                </td>
                                                <td class="total_price"> Rp.
                                                    <?= $PRODUCT_IN_CART->pro_total; ?>
                                                </td>
                                                <td>
                                                    <a data-prod-id="<?= $PRODUCT_IN_CART->id; ?>" class="btn-delete btn btn-danger text-white"><i class="fa fa-times"></i></a>
                                                </td>
                                            </tr>
                                        <?php endforeach; else : ?>
                                        <div class="alert alert-danger bg-danger text-white text-centre">
                                            <h1>Cart is empty !!!</h1>
                                        </div>
                                    <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col ">
                    <a href="<?= freshcery; ?>/shop" class="btn btn-default">Continue Shopping</a>
                    <?php if (!isset($PRODUCT_IN_CART) || empty($PRODUCT_IN_CART)) : ?>
                        <div style="
                               background: #f8d7da; 
                               color: #721c24; 
                               padding: 10px 20px; 
                               border-radius: 8px; 
                               display: inline-block; 
                               font-weight: bold; 
                               margin-top: 15px;
                               animation: bounce 1.5s infinite;
                           ">
                            <p style="margin: 0;"><strong>Your cart is empty. Start shopping now!</strong></p>
                        </div>
                        <style>
                            @keyframes bounce {
                                0%,
                                100% {
                                    transform: translateY(0);
                                }
                                50% {
                                    transform: translateY(-5px);
                                }
                            }
                        </style>
                    <?php endif; ?>
                </div>
                <div class="col text-right">
                    <div class="clearfix"></div>
                    <h6 class="full_price mt-3"></h6>
                    <form action="cart.php" method="post">
                        <input hidden class="inp_price form-control" type="text" value="" name="inp_price">
                        <button type="submit" name="submit" class="btn btn-lg btn-primary" hidden>Checkout <i class="fa fa-long-arrow-right"></i></button>
                        <?php if (!empty($PRODUCT_IN_CART)) : ?>
                            <button type="submit" name="submit" class="btn btn-lg btn-primary">Checkout <i class="fa fa-long-arrow-right"></i></button>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
<?php include 'include/footer.php'; ?>
<script>
    $(document).ready(function() {
        $(".form-control").keyup(function() {
            var value = $(this).val();
            value = value.replace(/^(0*)/, "");
            $(this).val(1);
        });

        $(".pro_qty").on("input", function() {
            var $el = $(this).closest("tr");
            var pro_qty = parseInt($el.find(".pro_qty").val());
            var pro_price = $el.find(".pro_price").text().replace("Rp", "").trim(); 
            pro_price = parseFloat(pro_price); 

            if (!isNaN(pro_qty) && !isNaN(pro_price)) {
                var total = pro_qty * pro_price;
                $el.find(".total_price").text("Rp " + total.toFixed(2)); 
            }
        });
        $(".btn-update").on('click', function(e) {
            e.preventDefault(); 
            var $el = $(this).closest('tr');
            var id = $(this).data("prod-id");
            console.log(id);
            $el
            var pro_qty = $el.find(".pro_qty").val(); 
            var pro_price = $el.find(".pro_price").text().replace("Rp", "").trim(); 
            pro_price = parseFloat(pro_price);
            if (isNaN(pro_qty) || isNaN(pro_price)) {
                alert("Please check the quantity and price values.");
                return;
            }

            var total = pro_qty * pro_price;

            $.ajax({
                type: "POST",
                url: "update-product.php",
                data: {
                    update: "update",
                    id: id, // Send cart id
                    pro_qty: pro_qty,
                    total: total
                },
                success: function(response) {
                    // alert("Update Successful!");
                    reload(); // Reload the page after the update
                    fetch_cart_total();
                },
                error: function() {
                    alert("Error updating the product.");
                }
            });
        });
        $(".btn-delete").on('click', function(e) {
            e.preventDefault(); // Prevent default action of the anchor tag

            var $el = $(this).closest('tr');


            // Fetch the cart id, product quantity, and price from the table row
            // var id = $(this).val(); // The cart item id from the button value
            var id = $(this).data("prod-id");
            console.log(id);
            $el

            $.ajax({
                type: "POST",
                url: "delete-product.php",
                data: {
                    delete: "delete",
                    id: id, // Send cart id
                },
                success: function(response) {
                    // alert("Update Successful!");
                    reload(); // Reload the page after the update
                    fetch_cart_total();
                },
                error: function() {
                    alert("Error deleting the product.");
                }
            });
        });
        fetch_cart_total();

        function reload() {
            $("body").load("cart.php")
        }

        function fetch_cart_total() {
            var sum = 0;

            $(".total_price").each(function() {
                var priceText = $(this).text().replace(/\D/g, ""); // Remove all non-numeric characters
                var price = parseInt(priceText, 10); // Convert to integer

                if (!isNaN(price)) {
                    sum += price;
                }
            });

            $(".full_price").html("Total: Rs. " + sum);
            $(".inp_price").val(sum);
        }

    });
</script>