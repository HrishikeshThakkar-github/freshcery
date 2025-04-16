<?php
include 'include/header.php';
include 'configration/db.config.php';
echo "In update-product.php";
if(isset($_POST['update'])) {
    $id = $_POST['id'];
    $pro_qty = $_POST['pro_qty'];
    $total = $_POST['total'];
    echo "ID: $id, Quantity: $pro_qty, Total: $total";
    $update = $pdo->prepare("UPDATE cart SET pro_qty = :pro_qty, pro_total = :pro_total WHERE id = :id");

    $update->bindParam(':pro_qty', $pro_qty, PDO::PARAM_INT);
    $update->bindParam(':pro_total', $total, PDO::PARAM_STR);
    $update->bindParam(':id', $id, PDO::PARAM_INT);

    if ($update->execute()) {
        echo "Product updated successfully.";
    } else {
        echo "Error updating product.";
    }
}
?>
<?php include 'include/footer.php'; ?>
