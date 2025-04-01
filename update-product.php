<?php
include 'include/header.php';
include 'configration/db.config.php';

echo "In update-product.php";

if(isset($_POST['update'])) {
    // Sanitize and validate the inputs to prevent SQL injection
    $id = $_POST['id'];
    $pro_qty = $_POST['pro_qty'];
    $total = $_POST['total'];

    // Debugging: print values to ensure they're received correctly
    echo "ID: $id, Quantity: $pro_qty, Total: $total";

    // Use a prepared statement to safely update the database
    $update = $pdo->prepare("UPDATE cart SET pro_qty = :pro_qty, pro_total = :pro_total WHERE id = :id");

    // Bind parameters to prevent SQL injection
    $update->bindParam(':pro_qty', $pro_qty, PDO::PARAM_INT);
    $update->bindParam(':pro_total', $total, PDO::PARAM_STR);
    $update->bindParam(':id', $id, PDO::PARAM_INT);

    // Execute the update query and check for success
    if ($update->execute()) {
        echo "Product updated successfully.";
    } else {
        echo "Error updating product.";
    }
}
?>

<?php include 'include/footer.php'; ?>
