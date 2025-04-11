<?php include '../../configration/db.config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = htmlspecialchars($_POST['title']);
    $description = $_POST['description'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $image = $_POST['image'];
    $exp_date = $_POST['exp_date'];
    $category_id = $_POST['category_id'];


    // Check for empty fields
    if (empty($title) || empty($description) || empty($price) || empty($quantity) || empty($image) || empty($exp_date) || empty($category_id)) {
        header("Location: ../products.php?message=fill in the values");
        exit(); // Stop script execution after redirection
    }

    try {
        $query = "INSERT INTO products (title, description, price, quantity, image, exp_date, category_id) 
        VALUES (:title, :description, :price, :quantity, :image, :exp_date, :category_id)";

        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'title' => $title,
            'description' => $description,
            'price' => $price,
            'quantity' => $quantity,
            'image' => $image,
            'exp_date' => $exp_date,
            'category_id' => $category_id
        ]);

        // Redirect after successful insertion
        header("Location: ../products.php?message=success");
        exit();
    } catch (PDOException $e) {
        // Redirect with error message if insertion fails
        header("Location: ../products.php?message=error");
        exit();
    }
}
