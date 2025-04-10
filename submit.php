<?php 
session_start();
include 'configration/db.config.php';
require('payment_system/config.php');


//add the mail feature in the session email id

$order_details = $pdo->prepare('select pro_id,pro_title,pro_total FROM cart WHERE user_id = :user_id');
$order_details->execute([':user_id' => $_SESSION['user_id']]);
$products=$order_details->fetchAll(PDO::FETCH_ASSOC);
$order_id = $_SESSION['order_details_id']['id'];
$total_order_value = $_SESSION['Total_order_value'];

foreach ($products as $product) {
    $insert = $pdo->prepare('
        INSERT INTO order_details (order_id, pro_id, pro_title, pro_total, total_order_value)
        VALUES (:order_id, :pro_id, :pro_title, :pro_total, :total_order_value)
    ');

    $insert->execute([
        ':order_id' => $order_id,
        ':pro_id' => $product['pro_id'],
        ':pro_title' => $product['pro_title'],
        ':pro_total' => $product['pro_total'],
        ':total_order_value' => $total_order_value
    ]);
}


if (isset($_POST['stripeToken'])) {
    \Stripe\Stripe::setVerifySslCerts(false);
    $token = $_POST['stripeToken'];
    $data = \Stripe\Charge::create([
        "amount" => $_SESSION['payment'] * 100, // amount in paise
        "currency" => "inr",
        "description" => "Freshcery",
        "source" => $token,
        "metadata" => [
            "order_id" => $_SESSION['order_details_id']['id'],
            "user_email" => $_SESSION['email']
        ]
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

<?php

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

$mail = new PHPMailer\PHPMailer\PHPMailer();

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'hrishikeshthakkar.19@gmail.com';
    $mail->Password   = 'njxppekpohbqxafy'; // use App Password from Gmail
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    // Recipients
    $mail->setFrom('your_email@gmail.com', 'Freshcery');
    $mail->addAddress($_SESSION['email'], $_SESSION['username']);

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Order Confirmation';
    $mail->Body    = 'Thank you for your order! Your Order ID is <b>' . $_SESSION['order_details_id']['id'] . '</b>.';

    $mail->send();
    echo 'Email sent successfully.';
} catch (Exception $e) {
    echo "Email could not be sent. Error: {$mail->ErrorInfo}";
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
        setTimeout(() => {
            window.location.href = "/";
        }, 2500);
    </script>
</head>
<body>
    <div class="content-wrapper">
        <div class="success-message">
            <h1>Payment Successful ✅</h1>
            <p>Thank you for your payment!</p>
            <p>Redirecting to the homepage...</p>
        </div>
        <div class="spinner"></div>
    </div>
</body>
</html>
<?php
    exit();
}
?>
