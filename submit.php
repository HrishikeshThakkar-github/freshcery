<?php 
session_start();
include 'configration/db.config.php';
?>


<?php
session_start();
require('payment_system/config.php');

if (isset($_POST['stripeToken'])) {
    \Stripe\Stripe::setVerifySslCerts(false);

    $token = $_POST['stripeToken'];

    // $data = \Stripe\Charge::create([
    //     "amount" => $_SESSION['payment']*100, 
    //     "currency" => "inr",
    //     "description" => "Freshcery",
    //     "source" => $token,
    // ]);

    // Store payment data in session
    $_SESSION['payment'] = $data;

    // Display success message and redirect
    echo "<div style='text-align: center; margin-top: 50px; font-family: Arial, sans-serif;'>
            <h1 style='color: green; font-size: 50px;'>Payment Successful ✅</h1>
            <p style='font-size: 20px;'>Thank you for your payment!</p>
            <p>Redirecting to the homepage...</p>
          </div>";

    // Redirect to home page after 5 seconds

    if (isset($_SESSION['user_id'])) {
        $delete = $pdo->prepare('DELETE FROM cart WHERE user_id = :user_id');
        $delete->execute([':user_id' => $_SESSION['user_id']]);
    }
    header("refresh:5;url=index.php");
    exit();
}


?>