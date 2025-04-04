<?php
include 'categories.functions.php';

// Handle Add Category
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add_category'])) {
    $name = htmlspecialchars($_POST['name']);
    $description = htmlspecialchars($_POST['description']);
    $icon = htmlspecialchars($_POST['icon']);

    // Handle Image Upload
    $image = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    $upload_dir = "../../assets/img/";

    if (!empty($image)) {
        move_uploaded_file($image_tmp, $upload_dir . $image);
    } else {
        $image = "default.jpg"; // Default image
    }

    if (addCategory($name, $description, $icon, $image)) {
        header("Location: ../categories.php?message=Category added successfully");
    } else {
        header("Location: ../categories.php?message=Error adding category");
    }
    exit();
}

// Handle Update Category
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_category'])) {
    $id = $_POST['update_id'];
    $name = htmlspecialchars($_POST['update_name']);
    $description = htmlspecialchars($_POST['update_description']);
    $icon = htmlspecialchars($_POST['update_icon']);

    // Handle Image Upload
    $image = $_FILES['update_image']['name'];
    $image_tmp = $_FILES['update_image']['tmp_name'];
    $upload_dir = "../../assets/img/";

    if (!empty($image)) {
        move_uploaded_file($image_tmp, $upload_dir . $image);
    } else {
        $image = $_POST['update_current_image']; // Keep old image
    }

    if (updateCategory($id, $name, $description, $icon, $image)) {
        header("Location: ../categories.php?message=Category updated successfully");
    } else {
        header("Location: ../categories.php?message=Error updating category");
    }
    exit();
}

// Handle Delete Category
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    if (deleteCategory($id)) {
        header("Location: ../categories.php?message=Category deleted successfully");
    } else {
        header("Location: ../categories.php?message=Error deleting category");
    }
    exit();
}
?>
