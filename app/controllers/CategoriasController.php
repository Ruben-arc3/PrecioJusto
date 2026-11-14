<?php
session_start();
require_once __DIR__ . '/../models/connection.php';

$id_categoria = isset($_GET['id']) ? intval($_GET['id']) : 0;
$busqueda = $_GET['busqueda'] ?? '';
$orden = $_GET['orden'] ?? 'nombre_producto ASC';

// Obtener nombre de la categoría
$stmt = $conn->prepare("SELECT nombre_categoria FROM categorias WHERE id_categoria = ?");
$stmt->execute([$id_categoria]);
$categoria = $stmt->fetchColumn();

if (!$categoria) {
    die("Categoría no encontrada.");
}

// Obtener productos de esa categoría con el precio más bajo y las tiendas
$sql = "SELECT p.*, 
               (SELECT MIN(pt.precio) 
                FROM producto_tienda pt 
                WHERE pt.id_producto = p.id_producto) as precio_minimo,
               GROUP_CONCAT(DISTINCT t.nombre_tienda SEPARATOR ', ') as tiendas
        FROM productos p
        LEFT JOIN producto_tienda pt ON p.id_producto = pt.id_producto
        LEFT JOIN tiendas t ON pt.id_tienda = t.id_tienda
        WHERE p.id_categoria = ? 
        AND p.nombre_producto LIKE ?
        GROUP BY p.id_producto
        ORDER BY $orden";
$stmt = $conn->prepare($sql);
$stmt->execute([$id_categoria, "%$busqueda%"]);
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

require __DIR__ . '/../views/pages/categorias.php';
