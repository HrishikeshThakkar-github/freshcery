<?php
include 'product.functions.php';
// Ensure the request method is POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST['action'];

    // Common fields
    $title = isset($_POST['title']) ? htmlspecialchars($_POST['title']) : '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? '';
    $quantity = $_POST['quantity'] ?? '';
    $exp_date = $_POST['exp_date'] ?? '';
    $category_id = $_POST['category_id'] ?? '';

    // Handle file upload
    if (!empty($_FILES["image"]["name"])) {
        $image = basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], "../../assets/img/" . $image);
    } else {
        $image = $_POST['current_image'] ?? ''; // Keep old image for updates
    }

    if ($action === "add") {
        $message = addProduct($pdo, $title, $description, $price, $quantity, $image, $exp_date, $category_id);
    } elseif ($action === "update") {
        $id = $_POST['id'];
        $message = updateProduct($pdo, $id, $title, $description, $price, $quantity, $image, $exp_date, $category_id);
    }
    header("Location: ../products.php?message=" . urlencode($message));
    exit();
}

// Handle GET request for deletion
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['action']) && $_GET['action'] === "delete") {
    $id = $_GET['id'];
    $message = deleteProduct($pdo, $id);
    header("Location: ../products.php?message=" . urlencode($message));
    exit();
}
?>
