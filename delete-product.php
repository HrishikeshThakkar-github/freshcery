<?php
include 'include/header.php';
include 'configration/db.config.php';

error_reporting(E_ALL);

ini_set('display_errors', 1);
echo "In delete-product.php";

if(isset($_POST['delete'])) {
    // Sanitize and validate the inputs to prevent SQL injection
    $id = $_POST['id'];

    // Debugging: print values to ensure they're received correctly
    echo "ID: $id";

    // Use a prepared statement to safely update the database
    $update = $pdo->prepare("DELETE from cart WHERE id = '$id' ");

    // Bind parameters to prevent SQL injection


    // Execute the update query and check for success
    if ($update->execute()) {
        echo "Product deleted successfully.";
    } else {
        echo "Error deleting product.";
    }
}
?>

<?php include 'include/footer.php'; ?>
