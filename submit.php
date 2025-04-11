<?php
session_start();
include 'configration/db.config.php';
require_once __DIR__ . '/payment_system/vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require __DIR__ . '/payment_system/vendor/autoload.php'; // Adjust path based on folder location
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$order_details = $pdo->prepare('select pro_id,pro_title,pro_total FROM cart WHERE user_id = :user_id');
$order_details->execute([':user_id' => $_SESSION['user_id']]);
$products = $order_details->fetchAll(PDO::FETCH_ASSOC);
$order_id = $_SESSION['order_details_id']['id'];
$total_order_value = $_SESSION['Total_order_value'];
foreach ($products as $product) {
    $insert = $pdo->prepare('
        INSERT INTO order_details (order_id, pro_id, pro_title, pro_total, total_order_value)
        VALUES (:order_id, :pro_id, :pro_title, :pro_total, :total_order_value);
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
    \Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);
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
    $mail = new PHPMailer();
    try {
        $order_id = $_SESSION['order_details_id']['id'];
        $query = "SELECT pro_title, pro_total FROM order_details WHERE order_id = :order_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['order_id' => $order_id]);
        $order_products = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $order_products[] = $row;
        }
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = $_ENV['FRESHCERY_EMAIL'];
        $mail->Password   = $_ENV['FRESHCERY_EMAIL_PASS']; // use App Password from Gmail
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom('hrishikeshthakkar.19@gmail.com', 'Freshcery');
        $mail->addAddress($_SESSION['email'], $_SESSION['username']);

        $mail->isHTML(true);
        $mail->Subject = 'Your Freshcery Order Confirmation';

        $mail->Body = '
        <html>
        <head>
          <style>
            body {
              font-family: Arial, sans-serif;
              color: #333;
            }
            .container {
              max-width: 600px;
              margin: auto;
              border: 1px solid #e2e2e2;
              padding: 20px;
              background-color: #f9f9f9;
            }
            .header {
              text-align: center;
              padding-bottom: 20px;
            }
            .header img {
              max-width: 150px;
            }
            .order-info {
              background-color: #fff;
              padding: 15px;
              border-radius: 8px;
              box-shadow: 0 0 5px rgba(0,0,0,0.1);
            }
            .order-info h2 {
              color: #5cb85c;
            }
            .product-table {
              width: 100%;
              border-collapse: collapse;
              margin-top: 15px;
            }
            .product-table th, .product-table td {
              border: 1px solid #ddd;
              padding: 8px;
            }
            .product-table th {
              background-color: #5cb85c;
              color: white;
            }
            .total {
              text-align: right;
              font-size: 18px;
              font-weight: bold;
              padding-top: 15px;
            }
          </style>
        </head>
        <body>
          <div class="container">
            <div class="header">
              <img src="https://drive.google.com/uc?export=view&id=1Dp2uCgD0aYTVMqKfB1UyEUIkjoGPHC4c" alt="Freshcery Logo" width="150">
            </div>
        
            <div class="order-info">
              <h2>Thank you for your order!</h2>
              <p>Hi ' . $_SESSION['username'] . ',</p>
              <p>We appreciate your purchase. Your order has been successfully placed.</p>
              <p><strong>Order ID:</strong> #' . $_SESSION['order_details_id']['id'] . '</p>
        
              <h3>Order Summary:</h3>
                <table class="product-table">
                    <thead>
                      <tr>
                        <th>Product Name</th>
                        <th>Subtotal</th>
                      </tr>
                    </thead>
                    <tbody>';
                        foreach ($order_products as $product) {
                                $mail->Body .= '
                                    <tr>
                                        <td>' . $product['pro_title'] . '</td>
                                        <td>₹' . $product['pro_total'] . '</td>
                                    </tr>';
                        }
                                $mail->Body .= '
                                    <tr>
                                        <td> Delivery Charges </td>
                                        <td> ₹80 </td>
                                  </tr>
                    </tbody>
                </table>
        
              <div class="total">
                Total: ₹' . $_SESSION['Total_order_value'] . '
              </div>
            </div>
          </div>
        </body>
        </html>';
        $mail->send();
    } catch (Exception $e) {
    }
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
            .content-wrapper {
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                height: 100vh;
                font-family: Arial, sans-serif;
            }

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

            .spinner {
                width: 60px;
                height: 60px;
                border: 6px solid #3498db;
                border-top: 6px solid transparent;
                border-radius: 50%;
                animation: spin 1s linear infinite;
            }

            @keyframes spin {
                0% {
                    transform: rotate(0deg);
                }

                100% {
                    transform: rotate(360deg);
                }
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