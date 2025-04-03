<?php 
session_start();
include 'configration/db.config.php';
?>


<?php
require('payment_system/config.php');

if (isset($_POST['stripeToken'])) {
    \Stripe\Stripe::setVerifySslCerts(false);

    $token = $_POST['stripeToken'];

    $data = \Stripe\Charge::create([
        "amount" => $_SESSION['payment']*100, 
        "currency" => "inr",
        "description" => "Freshcery",
        "source" => $token,
    ]);

    // Store payment data in session
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
        /* Center content */
        .content-wrapper {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: Arial, sans-serif;
        }

        /* Success message */
        .success-message {
            text-align: center;
            margin-bottom: 20px;
        }

        .success-message h1 {
            color: green;
            font-size: 50px;
            margin: 0;
        }

        .success-message p {
            font-size: 20px;
            margin: 5px 0;
        }

        /* Loader */
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
        }, 2500);
    </script>
</head>
<body>

    <div class="content-wrapper">
        <!-- Success message -->
        <div class="success-message">
            <h1>Payment Successful ✅</h1>
            <p>Thank you for your payment!</p>
            <p>Redirecting to the homepage...</p>
        </div>

        <!-- Loader -->
        <div class="spinner"></div>
    </div>

</body>
</html>

<?php
    exit(); // Ensure script stops executing
}
?>
