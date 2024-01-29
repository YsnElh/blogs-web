<?php

$host = 'localhost';
$dbname = 'blogs_app';
$port = '3306';
$dbusername = 'root';
$dbpassword = '';

try {

    $pdo = new PDO("mysql:host=$host;dbname=$dbname;port=$port", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection Failed: " . $e->getMessage());
}
