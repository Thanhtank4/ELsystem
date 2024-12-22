<?php
$host = 'localhost';
$dbname = 'english';
$username = 'root';
$password = '';

// Kết nối MySQLi
$db = new mysqli($host, $username, $password, $dbname);

// Kiểm tra kết nối MySQLi
if ($db->connect_error) {
    die("MySQLi Connection failed: " . $db->connect_error);
}

// Kết nối PDO
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("PDO Connection failed: " . $e->getMessage());
}
?>
