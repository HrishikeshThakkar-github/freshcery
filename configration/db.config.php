<?php

require __DIR__ . '/../payment_system/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../');
$dotenv->load();
 

//now to restrict the viewership of this page directly through the url we ca nuse:

// if(!isset($_SERVER['HTTP_REFERER'])){
//     //when someone try to access by url gets redirected to homepage-> index.php
//     header("Location: http://freshcery/");
// }
$db_user = $_ENV['DB_USER'];
$db_pass = $_ENV['DB_PASS']; 
$db_name = $_ENV['DB_NAME'];
$dsn = "mysql:host=localhost;dbname=$db_name;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo $e->getMessage();
}
?> 