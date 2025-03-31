<?php
include 'include/header.php';
include 'configration/db.config.php';

if (!isset($_GET['id']) || empty($_GET['id']) || !is_numeric($_GET['id'])) {
    die("<h2>Invalid Product ID!</h2>");
}

$product_id = intval($_GET['id']);
if ($product_id <= 0) {
    die("<h2>Invalid Product ID!</h2>");
}

$query = "SELECT p.*, c.name AS category_name, c.id AS category_id 
          FROM products p 
          JOIN categories c ON p.category_id = c.id 
          WHERE p.id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    die("<h2>Product Not Found!</h2>");
}

$category_id = $product['category_id'];

// Debug: Ensure the correct product is fetched
// echo "<pre>";
// var_dump($product);
// echo "</pre>";

// ✅ Fetch related products in the same category (excluding current product)
$related_query = "SELECT * FROM products WHERE category_id = ? AND id != ? LIMIT 5";
$stmt = $pdo->prepare($related_query);
$stmt->execute([$category_id, $product_id]);
$related_products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Debug: Ensure related products are correct
// foreach($related_products as $related_product){
//     var_dump($related_product);
// }


if (isset($_POST['submit'])) {


    $pro_id = $_POST['pro_id'];
    $pro_title = $_POST['pro_title'];
    $pro_image = $_POST['pro_image'];
    $pro_price = $_POST['pro_price'];
    $pro_qty = $_POST['pro_qty'];
    $user_id = $_POST['user_id'];

    $query = "INSERT INTO cart (pro_id,pro_title,pro_image,pro_price,pro_qty,user_id) VALUES (:pro_id,:pro_title,:pro_image,:pro_price,:pro_qty,:user_id)";
    echo $query;
    var_dump($_SESSION['user_id']);
    $insert = $pdo->prepare($query);

    $insert -> bindParam(":pro_id",$pro_id);
    $insert -> bindParam(":pro_title",$pro_title);
    $insert -> bindParam(":pro_image",$pro_image);
    $insert -> bindParam(":pro_price",$pro_price);
    $insert -> bindParam(":pro_qty",$pro_qty);
    $insert -> bindParam(":user_id",$user_id);
    $insert -> execute();

    // $insert->execute([
    //     ':pro_id'    => $pro_id,
    //     ':pro_title' => $pro_title,
    //     ':pro_image' => $pro_image,
    //     ':pro_price' => $pro_price, 
    //     ':pro_qty'   => $pro_qty,
    //     ':user_id'   => $user_id,
    // ]);

}
?>


<div id="page-content" class="page-content">
    <div class="banner">
        <div class="jumbotron jumbotron-bg text-center rounded-0" style="background-image: url('assets/img/bg-header.jpg');">
            <div class="container">
                <h1 class="pt-5"><?php echo htmlspecialchars($product['title'] ?? 'No Title'); ?></h1>
                <p class="lead"><?php echo htmlspecialchars($product['description'] ?? 'No Description'); ?></p>
            </div>
        </div>
    </div>

    <div class="product-detail">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <img alt="Product Image" src="assets/img/<?php echo htmlspecialchars($product['image'] ?? 'default.jpg'); ?>" style="width: 100%;">
                </div>
                <div class="col-sm-6">
                    <p>
                        <strong>Category:</strong> <?php echo htmlspecialchars($product['category_name'] ?? 'Unknown'); ?><br>
                        <strong>Price:</strong> Rp <?php echo $product['price']; ?>
                    </p>
                    <p class="mb-1"><strong>Quantity</strong></p>

                    <form method="post" id="form-data">
                        <div class="col-sm-3">
                            <input class="form-control" name="pro_id" type="text" value="<?php echo $product['id']; ?>">
                        </div>
                        <div class="col-sm-3">
                            <input class="form-control" name="pro_title" type="text" value="<?php echo $product['title']; ?>">
                        </div>
                        <div class="col-sm-3">
                            <input class="form-control" name="pro_image" type="text" value="<?php echo $product['image']; ?>">
                        </div>

                        <div class="col-sm-3">
                            <input class="form-control" name="pro_price" type="text" value="<?php echo $product['price']; ?>">
                        </div>

                        <div class="col-sm-3">
                            <input class="form-control" name="user_id" type="text" value="<?php echo $_SESSION['user_id']; ?>">
                        </div>
                        <div class="row">
                            <div class="col-sm-5">
                                <input class="form-control" type="number" min="1" value="1" name="pro_qty">
                            </div>
                        </div>
                        <button name="submit" type="button" class="btn-insert mt-3 btn btn-primary btn-lg">

                            <i class="fa fa-shopping-basket"></i> Add to Cart
                        </button>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <section id="related-product">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="title">Related Products</h2>
                    <div class="product-carousel owl-carousel">
                        <?php foreach ($related_products as $related) { ?>
                            <div class="item">
                                <div class="card card-product">
                                    <img src="assets/img/<?php echo htmlspecialchars($related['image'] ?? 'default.jpg'); ?>" alt="Related Product" class="card-img-top">
                                    <div class="card-body">
                                        <h4 class="card-title">
                                            <a href="detail-product.php?id=<?php echo $related['id']; ?>">
                                                <?php echo htmlspecialchars($related['title'] ?? 'No Title'); ?>
                                            </a>
                                        </h4>
                                        <div class="card-price">
                                            <span class="reguler">Rp <?php echo $related['price'] ?></span>
                                        </div>
                                        <a href="detail-product.php?id=<?php echo $related['id']; ?>" class="btn btn-block btn-primary">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include 'include/footer.php'; ?>



<script>
    $(document).ready(function(){
        $(".form-control").keyup(function(){
            var value =$(this).val();
            value=value.replace(/^(0*)/,"");
            $(this).val(1);
        })
        $(".btn-insert").on("click", function(e){
            e.preventDefault(); //prevent from reloading

            var form_data = $("#form-data").serialize()+'&submit=submit';

            $.ajax({
                url: "detail-product.php?id=<?php echo $product_id; ?>",
                method: "POST",
                data: form_data,
                success: function(){
                    alert("added to cart");
                }

            });
        })
    })
</script>