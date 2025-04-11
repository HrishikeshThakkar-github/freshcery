<?php include 'configration/db.config.php';?>

<?php  function get_cart_items($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM cart WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result['COUNT(*)'];
}?>