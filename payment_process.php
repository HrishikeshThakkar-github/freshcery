<?php
session_start();
require_once 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$secret_key = $_ENV['secretKey'];
\Stripe\Stripe::setApiKey($secret_key);
function create_payment(){
    $line_items=[];
    $totalAmount=$_SESSION['payment'];
    try {
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $line_items,
            'mode' => 'payment',
            'success_url' => 'http://freshcery/submit.php?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => 'http://freshcery/cart.php',
        ]);
        return ['sessionUrl' => $session->url, 'totalAmount' => $totalAmount];
    } catch (Exception $e) {
        return ['error' => $e->getMessage()];
    }
}
