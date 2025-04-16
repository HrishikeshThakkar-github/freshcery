<?php

require __DIR__ . '/vendor/autoload.php'; // Load Stripe via Composer

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../');
$dotenv->load();
 
\Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']); // <-- Replace with your real Stripe secret key
$publishableKey=$_ENV['STRIPE_SECRET_KEY'];

?>