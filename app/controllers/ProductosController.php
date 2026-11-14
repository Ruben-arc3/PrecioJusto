<?php
session_start();
require_once __DIR__ . '/../models/connection.php';

// Parámetros de filtrado
$busqueda = isset($_GET['busqueda']) ? trim($_GET['busqueda']) : '';
$tienda = isset($_GET['tienda']) ? intval($_GET['tienda']) : 0;
$orden = isset($_GET['orden']) ? $_GET['orden'] : 'recientes';
$pagina = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
$porPagina = 12;

// Obtener lista de tiendas para el filtro
$tiendas = $conn->query("SELECT id_tienda, nombre_tienda FROM tiendas ORDER BY nombre_tienda")->fetchAll(PDO::FETCH_ASSOC);

// Construir consulta SQL básica modificada
$sql = "SELECT p.id_producto, p.nombre_producto, p.Foto, p.marca, 
               MIN(pt.precio) AS precio_minimo,
               GROUP_CONCAT(DISTINCT t.nombre_tienda SEPARATOR ', ') AS tiendas_disponibles
        FROM productos p
        JOIN producto_tienda pt ON p.id_producto = pt.id_producto
        JOIN tiendas t ON pt.id_tienda = t.id_tienda";

$where = [];
$params = [];

// Filtro de búsqueda
if (!empty($busqueda)) {
    $where[] = "(p.nombre_producto LIKE ? OR p.marca LIKE ? OR t.nombre_tienda LIKE ?)";
    $params = array_merge($params, ["%$busqueda%", "%$busqueda%", "%$busqueda%"]);
}

// Filtro por tienda
if ($tienda > 0) {
    $where[] = "pt.id_tienda = ?";
    $params[] = $tienda;
}

if (!empty($where)) {
    $sql .= " WHERE " . implode(" AND ", $where);
}

// Agrupar por producto
$sql .= " GROUP BY p.id_producto";

// Ordenamiento
switch ($orden) {
    case 'precio_asc':
        $sql .= " ORDER BY precio_minimo ASC";
        break;
    case 'precio_desc':
        $sql .= " ORDER BY precio_minimo DESC";
        break;
    case 'nombre_asc':
        $sql .= " ORDER BY p.nombre_producto ASC";
        break;
    case 'nombre_desc':
        $sql .= " ORDER BY p.nombre_producto DESC";
        break;
    case 'tienda_asc':
        $sql .= " ORDER BY tiendas_disponibles ASC";
        break;
    case 'tienda_desc':
        $sql .= " ORDER BY tiendas_disponibles DESC";
        break;
    default:
        $sql .= " ORDER BY p.id_producto DESC";
}

// Paginación
$stmtTotal = $conn->prepare("SELECT COUNT(*) FROM (" . str_replace('p.id_producto, p.nombre_producto, p.Foto, p.marca, MIN(pt.precio) AS precio_minimo, GROUP_CONCAT(DISTINCT t.nombre_tienda SEPARATOR \', \') AS tiendas_disponibles', 'DISTINCT p.id_producto', $sql) . ") AS total");
$stmtTotal->execute($params);
$totalProductos = $stmtTotal->fetchColumn();
$totalPaginas = ceil($totalProductos / $porPagina);

$sql .= " LIMIT " . (($pagina - 1) * $porPagina) . ", $porPagina";

// Obtener productos
$stmt = $conn->prepare($sql);
$stmt->execute($params);
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

require __DIR__ . '/../views/pages/productos.php';
