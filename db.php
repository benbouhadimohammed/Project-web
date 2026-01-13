<?php
$host = 'localhost';
$db   = 'ecommerce_db';
$user = 'root';
$pass = '';

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("connection problem: " . mysqli_connect_error());
}