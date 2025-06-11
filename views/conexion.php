<?php
$host = 'localhost';
$dbname = 'Proyecto';
$username = 'root'; // Cambiar por tu usuario de MySQL
$password = ''; // Cambiar por tu contraseña de MySQL

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->exec("SET NAMES 'utf8'");
} catch(PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>