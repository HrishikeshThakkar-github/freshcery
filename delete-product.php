<?php
include 'include/header.php';
include 'configration/db.config.php';

echo "In delete-product.php";
if(isset($_POST['delete'])) {
    $id = $_POST['id'];
    echo "ID: $id";
    $update = $pdo->prepare("DELETE from cart WHERE id = '$id' ");
    if ($update->execute()) {
        echo "Product deleted successfully.";
    } else {
        echo "Error deleting product.";
    }
}
?>
<?php include 'include/footer.php'; ?>
