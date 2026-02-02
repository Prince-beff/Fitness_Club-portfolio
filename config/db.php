<?php

$host = "localhost";
$db   = "np03cs4a240180";
$user = "np03cs4a240180";
$pass = "bNjx1r4U2O";

// $host = "localhost";
// $db   = "fitness_club";
// $user = "root";
// $pass = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e){
    die("DB Connection Failed");
}
?>
