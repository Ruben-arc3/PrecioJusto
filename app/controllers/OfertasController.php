<?php
session_start();
require_once __DIR__ . '/../models/connection.php';

// Parámetros
$busqueda = isset($_GET['busqueda']) ? trim($_GET['busqueda']) : '';
$tienda = isset($_GET['tienda']) ? intval($_GET['tienda']) : 0;
$orden = $_GET['orden'] ?? 'recientes';
$pagina = max(1, intval($_GET['pagina'] ?? 1));
$porPagina = 12;

// Tiendas
$tiendas = $conn->query("SELECT id_tienda, nombre_tienda FROM tiendas ORDER BY nombre_tienda")->fetchAll(PDO::FETCH_ASSOC);

// SQL base - Obtener productos en oferta con la tienda que ofrece el precio más bajo
$sql = "SELECT 
            p.id_producto, 
            p.nombre_producto, 
            MIN(pt.precio) as precio_minimo,
            p.Foto, 
            p.marca,
            (SELECT t.nombre_tienda 
             FROM producto_tienda pt2 
             JOIN tiendas t ON pt2.id_tienda = t.id_tienda 
             WHERE pt2.id_producto = p.id_producto 
             ORDER BY pt2.precio ASC 
             LIMIT 1) as tienda_oferta,
            GROUP_CONCAT(DISTINCT t.nombre_tienda ORDER BY t.nombre_tienda SEPARATOR ', ') as tiendas_disponibles
        FROM productos p
        JOIN producto_tienda pt ON pt.id_producto = p.id_producto
        JOIN oferta o ON o.id_producto = p.id_producto
        JOIN tiendas t ON pt.id_tienda = t.id_tienda";

$params = [];

// Filtro búsqueda
if (!empty($busqueda)) {
    $where[] = "(p.nombre_producto LIKE ? OR p.marca LIKE ? OR t.nombre_tienda LIKE ?)";
    $params = array_fill(0, 3, "%$busqueda%");
}

// Filtro tienda
if ($tienda > 0) {
    $where[] = "t.id_tienda = ?";
    $params[] = $tienda;
}

if (!empty($where)) {
    $sql .= " WHERE " . implode(" AND ", $where);
}

// Agrupamos por producto
$sql .= " GROUP BY p.id_producto, p.nombre_producto, p.Foto, p.marca";

// Ordenamiento
switch ($orden) {
    case 'precio_asc': $sql .= " ORDER BY precio_minimo ASC"; break;
    case 'precio_desc': $sql .= " ORDER BY precio_minimo DESC"; break;
    case 'nombre_asc': $sql .= " ORDER BY p.nombre_producto ASC"; break;
    case 'nombre_desc': $sql .= " ORDER BY p.nombre_producto DESC"; break;
}

// Paginación
$sqlCount = "SELECT COUNT(DISTINCT p.id_producto) 
              FROM productos p
              JOIN oferta o ON o.id_producto = p.id_producto
              JOIN producto_tienda pt ON pt.id_producto = p.id_producto
              JOIN tiendas t ON pt.id_tienda = t.id_tienda";

if (!empty($where)) {
    $sqlCount .= " WHERE " . implode(" AND ", $where);
}

$stmtTotal = $conn->prepare($sqlCount);
$stmtTotal->execute($params);
$totalProductos = $stmtTotal->fetchColumn();
$totalPaginas = ceil($totalProductos / $porPagina);

// Aplicamos límite para la paginación
$sql .= " LIMIT " . (($pagina - 1) * $porPagina) . ", $porPagina";

// Obtener productos
$stmt = $conn->prepare($sql);
$stmt->execute($params);
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

require __DIR__ . '/../views/pages/ofertas.php';
