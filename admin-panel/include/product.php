<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'product.functions.php';

// Function to validate price format like "25.99/kg"
function isValidPriceFormat($price) {
    return preg_match('/^\d+(\.\d{1,2})?\/(kg|gm|unit)$/i', $price);
}

// Function to validate image file extension
function isValidImageMime($tmpFilePath) {
    $allowedTypes = ['image/jpeg', 'image/png' , 'image/jpg'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $tmpFilePath);
    finfo_close($finfo);
    return in_array($mimeType, $allowedTypes);
}


// Function to sanitize input
function cleanInput($input) {
    return htmlspecialchars(trim($input));
}



// Process POST request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST['action'];

    var_dump($action);
    // Clean and validate inputs
    $title = cleanInput($_POST['title'] ?? '');
    $description = cleanInput($_POST['description'] ?? '');
    $price = cleanInput($_POST['price'] ?? '');
    $quantity = $_POST['quantity'] ?? '';
    $exp_date = $_POST['exp_date'] ?? '';
    $category_id = $_POST['category_id'] ?? '';

    $errors = [];

    // Validation checks
    if (empty($title)) $errors[] = "Title is required.";
    if (empty($description)) $errors[] = "Description is required.";
    if (empty($price) || !isValidPriceFormat($price)) $errors[] = "Price must be in format like 25.99/kg.";
    if (!is_numeric($quantity) || intval($quantity) <= 0) $errors[] = "Quantity must be a positive number.";
    if (empty($exp_date) || strtotime($exp_date) < strtotime(date('Y-m-d'))) $errors[] = "Expiration date must be today or in the future.";
    if (!is_numeric($category_id) || intval($category_id) <= 0) $errors[] = "Category ID must be a positive number.";

    // File upload validation
    if (!empty($_FILES["image"]["name"])) {
        $tmpPath = $_FILES["image"]["tmp_name"];
        $image = basename($_FILES["image"]["name"]);
    
        // Just validate mime type
        if (!isValidImageMime($tmpPath)) {
            $errors[] = "Uploaded file is not a valid JPG or PNG image.";
        }
    
        // ❌ Skipping move_uploaded_file() completely
    } else {
        $image = $_POST['current_image'] ?? ''; // Keep old image for updates
    }
    
    

    // Stop if validation failed
    if (!empty($errors)) {
        $message = implode(" ", $errors);
        header("Location: ../products.php?message=" . urlencode($message));
        exit();
    }

    // Proceed with actions
    if ($action === "add") {
        $message = addProduct($pdo, $title, $description, $price, $quantity, $image, $exp_date, $category_id);
    } elseif ($action === "update") {
        $id = $_POST['id'];
        if (!is_numeric($id) || $id <= 0) {
            $message = "Invalid product ID for update.";
        } else {
            $message = updateProduct($pdo, $id, $title, $description, $price, $quantity, $image, $exp_date, $category_id);
        }
    }

    header("Location: ../products.php?message=" . urlencode($message));
    exit();
}

// Process GET request for deletion
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['action']) && $_GET['action'] === "delete") {
    $id = $_GET['id'];

    if (!is_numeric($id) || $id <= 0) {
        $message = "Invalid product ID for deletion.";
    } else {
        $message = deleteProduct($pdo, $id);
    }

    header("Location: ../products.php?message=" . urlencode($message));
    exit();
}
?>
