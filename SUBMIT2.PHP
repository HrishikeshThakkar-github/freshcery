<?php 
session_start();
include 'configration/db.config.php';
require('payment_system/config.php');

if (isset($_POST['stripeToken'])) {
    \Stripe\Stripe::setVerifySslCerts(false);

    $token = $_POST['stripeToken'];
    if (isset($_SESSION['user_id'])) {
        $products = $pdo->prepare("SELECT * FROM orders WHERE user_id = :user_id");
        $products->execute([':user_id' => $_SESSION['user_id']]);
        $orders = $products->fetchAll(PDO::FETCH_OBJ);
    }

    $data = \Stripe\Charge::create([
        "amount" => $_SESSION['payment'] * 100, 
        "currency" => "inr",
        "description" => "Freshcery Order",
        "source" => $token,
        "receipt_email" => "customer@example.com",
        "metadata" => [
            "order_id" => "ORD12345",
            "user_id" => $_SESSION['user_id']
        ],
        "statement_descriptor" => "Freshcery Payment",
        "capture" => true
    ]);

    $_SESSION['payment'] = $data;

    if (isset($_SESSION['user_id'])) {
        $delete = $pdo->prepare('DELETE FROM cart WHERE user_id = :user_id');
        $delete->execute([':user_id' => $_SESSION['user_id']]);
    
        unset($_SESSION['Total_order_value']);
    
        $update = $pdo->prepare('UPDATE orders SET status = "payment done" WHERE user_id = :user_id');
        $update->execute(params: [':user_id' => $_SESSION['user_id']]);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success</title>
    <style>
        /* Full-screen loader */
        .loader-wrapper {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        /* Spinner */
        .spinner {
            width: 60px;
            height: 60px;
            border: 6px solid #3498db;
            border-top: 6px solid transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
    <script>
        // Redirect after 5 seconds
        setTimeout(() => {
            window.location.href = "index.php";
        }, 5000);
    </script>
</head>
<body>

    <!-- Loader -->
    <div class="loader-wrapper">
        <div class="spinner"></div>
    </div>

    <!-- Success message -->
    <div style="text-align: center; margin-top: 50px; font-family: Arial, sans-serif;">
        <h1 style="color: green; font-size: 50px;">Payment Successful ✅</h1>
        <p style="font-size: 20px;">Thank you for your payment!</p>
        <p>Redirecting to the homepage...</p>
    </div>

</body>
</html>

<?php
    exit(); // Ensure script stops executing
}
?>
