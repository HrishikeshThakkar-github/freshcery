<?php


//now to restrict the viewership of this page directly through the url we ca nuse:

// if(!isset($_SERVER['HTTP_REFERER'])){
//     //when someone try to access by url gets redirected to homepage-> index.php
//     header("Location: http://freshcery/");
// }

$dsn = "mysql:host=localhost;dbname=freshcery;charset=utf8mb4";
$db_user = "root";  
$db_pass = "Simform@123";  

try {
    $pdo = new PDO($dsn, $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "connected";
} catch (PDOException $e) {
    echo $e->getMessage();
}

//to open in local server
//http://freshcery/




//display startup error
//error reporting
//display errpr to printout errors

//ini_set('display_startup_errors', 1);ini_set('display_errors', 1);error_reporting(-1);
?> 