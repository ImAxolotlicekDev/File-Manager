<?php
$host = '127.0.0.1';
$db = 'honza-deploy'; 
$user = 'honza'; 
$pass = 'janevimasihesloxd';  

try {
    $pdo = new PDO("mysql:host=$host;port=3306;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>

