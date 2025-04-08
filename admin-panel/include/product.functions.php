<?php
include '../../configration/db.config.php';
// Function to add a product
function addProduct($pdo, $title, $description, $price, $quantity, $image, $exp_date, $category_id) {
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

        return "Product added successfully!";
    } catch (PDOException $e) {
        return "Error adding product: " . $e->getMessage();
    }
}

// Function to update a product
function updateProduct($pdo, $id, $title, $description, $price, $quantity, $image, $exp_date, $category_id) {
    try {
        $query = "UPDATE products SET title=:title, description=:description, price=:price, quantity=:quantity, 
                  image=:image, exp_date=:exp_date, category_id=:category_id WHERE id=:id";

        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'id' => $id,
            'title' => $title,
            'description' => $description,
            'price' => $price,
            'quantity' => $quantity,
            'image' => $image,
            'exp_date' => $exp_date,
            'category_id' => $category_id
        ]);

        return "Product updated successfully!";
    } catch (PDOException $e) {
        return "Error updating product: " . $e->getMessage();
    }
}

// Function to delete a product
function deleteProduct($pdo, $id) {
    try {
        $query = "DELETE FROM products WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['id' => $id]);

        return "Product deleted successfully!";
    } catch (PDOException $e) {
        return "Error deleting product: " . $e->getMessage();
    }
}
?>
