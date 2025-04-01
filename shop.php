<?php include 'configration/db.config.php' ?>
<?php
try {
    $query = "SELECT * FROM categories";
    $categories = $pdo->prepare($query);
    $categories->execute();
    $allcategories = $categories->fetchAll(PDO::FETCH_OBJ);

    //most wanted
    $query = "SELECT * FROM products where status = 1 ";
    $favourite = $pdo->prepare($query);
    $favourite->execute();
    $favourites = $favourite->fetchAll(PDO::FETCH_OBJ);


    //category product crud
    $query = "SELECT c.id AS category_id, c.name AS category_name, c.image AS category_image, 
    c.icon AS category_icon, p.id AS product_id, p.title, p.description, 
    p.price, p.image AS product_image, p.exp_date, p.status
FROM categories c
LEFT JOIN products p ON c.id = p.category_id
ORDER BY c.id, p.id";

    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_OBJ);

    // Organizing categories and products into an array
    $categories = [];
    foreach ($rows as $row) {
        $categories[$row->category_id]['name'] = $row->category_name;
        $categories[$row->category_id]['image'] = $row->category_image;
        $categories[$row->category_id]['icon'] = $row->category_icon;
        if ($row->product_id) {
            $categories[$row->category_id]['products'][] = [
                'id' => $row->product_id,
                'title' => $row->title,
                'description' => $row->description,
                'price' => $row->price,
                'image' => $row->product_image,
                'exp_date' => $row->exp_date,
                'status' => $row->status
            ];
        }
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage()); // Or log the error
}

?>


<?php include 'include/header.php' ?>
<div id="page-content" class="page-content">
    <div class="banner">
        <div class="jumbotron jumbotron-bg text-center rounded-0" style="background-image: url('assets/img/bg-header.jpg');">
            <div class="container">
                <h1 class="pt-5">
                    Shopping Page
                </h1>
                <p class="lead">
                    Save time and leave the groceries to us.
                </p>
            </div>
        </div>
    </div>


    <!-- Dynamically getting the categories -->
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="shop-categories owl-carousel mt-5">
                    <?php foreach ($allcategories as $category): ?>
                        <div class="item">
                            <a href="shop.php#<?php echo 'category-' . $category->id; ?>">
                                <div class="media d-flex align-items-center justify-content-center">
                                    <span class="d-flex mr-2"><i class="sb-<?php echo $category->icon; ?>"></i></span>
                                    <div class="media-body">
                                        <h5><?php echo $category->name; ?></h5>
                                        <p><?php echo substr($category->description, 0, 40); ?></p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Dynamically getting the most-wanted -->
    <!-- <section id="most-wanted">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="title">Most Wanted</h2>
                    <div class="product-carousel owl-carousel">
                        <?php foreach ($favourites as $favourite): ?>
                            <div class="item">
                                <div class="card card-product">
                                    <div class="card-ribbon">
                                        <div class="card-ribbon-container right">
                                            <span class="ribbon ribbon-primary">most orderd</span>
                                        </div>
                                    </div>
                                    <div class="card-badge">
                                        <div class="card-badge-container left">
                                            <span class="badge badge-default">
                                                uptill
                                                <?php echo $favourite->exp_date; ?>
                                            </span>
                                            <span class="badge badge-primary">
                                                20% OFF
                                            </span>
                                        </div>
                                        <img src="assets/img/<?php echo $favourite->image; ?>" alt="Card image 2" class="card-img-top">
                                    </div>
                                    <div class="card-body">
                                        <h4 class="card-title">
                                            <a href="detail-product.php"><?php echo $favourite->title; ?></a>
                                        </h4>
                                        <div class="card-price">
                                            <span class="discount">Rp. <?php echo $favourite->price * 1.2; ?></span>
                                            <span class="reguler">Rp. <?php echo $favourite->price; ?></span>
                                        </div>
                                        <a href="detail-product.php" class="btn btn-block btn-primary">
                                            Add to Cart
                                        </a>

                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>

                    </div>
                </div>
            </div>
        </div>
    </section> -->

    <!-- Dynamically getting the categories and the products in those categories -->


    <?php foreach ($categories as $category_id => $category): ?>
        <section id="category-<?php echo $category_id; ?>" class="gray-bg">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="title">
                            <i class="<?php echo htmlspecialchars($category['icon']); ?>"></i>
                            <?php echo htmlspecialchars($category['name']); ?>
                        </h2>
                        <div class="product-carousel owl-carousel">
                            <?php if (!empty($category['products'])): ?>
                                <?php foreach ($category['products'] as $product): ?>
                                    <div class="item">
                                        <div class="card card-product">
                                            <div class="card-badge">
                                                <div class="card-badge-container left">
                                                    <span class="badge badge-default">Expires: <?php echo htmlspecialchars($product['exp_date']); ?></span>
                                                </div>
                                                <img src="assets/img/<?php echo htmlspecialchars($product['image']); ?>"
                                                    alt="<?php echo htmlspecialchars($product['title']); ?>"
                                                    class="card-img-top">
                                            </div>
                                            <div class="card-body">
                                                <h4 class="card-title">
                                                    <a href="detail-product.php?id= <?php echo $product['id']; ?>">
                                                        <?php echo htmlspecialchars($product['title']); ?>
                                                    </a>
                                                </h4>
                                                <div class="card-price">
                                                    <span class="reguler">$<?php echo $product['price']; ?></span>
                                                </div>
                                                <!-- <a href="cart.php?add=<?php echo $product['id']; ?>" class="btn btn-block btn-primary">Add to Cart</a> -->
                                                <a href="detail-product.php?id=<?php echo $product['id']; ?>" class="btn btn-block btn-primary">Add to Cart</a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>No products available in this category.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php endforeach; ?>

</div>
<?php include 'include/footer.php' ?>