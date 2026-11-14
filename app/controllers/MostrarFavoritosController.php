<?php
session_start();
require_once __DIR__ . '/../models/connection.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

// Obtener productos favoritos del usuario con información de tiendas y precios
$id_usuario = $_SESSION['usuario']['id'];
$sql = "SELECT p.id_producto, p.nombre_producto, p.Foto, p.marca,
               MIN(pt.precio) AS precio_minimo,
               GROUP_CONCAT(DISTINCT t.nombre_tienda SEPARATOR ', ') AS tiendas_disponibles
        FROM favoritos f
        JOIN productos p ON f.id_producto = p.id_producto
        JOIN producto_tienda pt ON p.id_producto = pt.id_producto
        JOIN tiendas t ON pt.id_tienda = t.id_tienda
        WHERE f.id_usuario = ?
        GROUP BY p.id_producto";
$stmt = $conn->prepare($sql);
$stmt->execute([$id_usuario]);
$favoritos = $stmt->fetchAll(PDO::FETCH_ASSOC);

require __DIR__ . '/../views/pages/mostrar_favoritos.php';
