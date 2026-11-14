<?php
require_once __DIR__ . '/../models/connection.php';
require_once __DIR__ . '/CatalogContext.php';
require_once __DIR__ . '/../models/ProductModel.php';

// Verificar si el parámetro id existe, no está vacío y es numérico
if (!isset($_GET['id']) || empty($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    header("Location: login.php");
    exit();
}

$id_producto = (int)$_GET['id'];

// Obtener información del producto (sin precio ni URL de tienda)
$producto = (new ProductModel($conn))->findWithRating($id_producto);

if (!$producto) {
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    header("Location: productos.php?error=producto_no_encontrado");
    exit();
}

// Obtener las tiendas, precios y URLs para este producto
$stmtTiendas = $conn->prepare("
    SELECT pt.precio, pt.url AS url_producto, t.nombre_tienda, t.url AS url_tienda
    FROM producto_tienda pt
    JOIN tiendas t ON pt.id_tienda = t.id_tienda
    WHERE pt.id_producto = ?
    ORDER BY pt.precio ASC
");
$stmtTiendas->execute([$id_producto]);
$tiendasDisponibles = $stmtTiendas->fetchAll(PDO::FETCH_ASSOC);

// Resto de tu lógica (favoritos, productos relacionados) se mantiene igual
// Obtener favoritos del usuario si está logueado
$favoritosUsuario = [];
if (isset($_SESSION['usuario']['id'])) {
    $stmtFav = $conn->prepare("SELECT id_producto FROM favoritos WHERE id_usuario = ?");
    $stmtFav->execute([$_SESSION['usuario']['id']]);
    $favoritosUsuario = $stmtFav->fetchAll(PDO::FETCH_COLUMN);
}

// Obtener productos relacionados únicos
$relacionados = [];
$ids_agregados = [$id_producto];

function agregarProductos(&$relacionados, $productos, &$ids_agregados, $limite = 6) {
    foreach ($productos as $p) {
        if (count($relacionados) >= $limite) break;
        if (!in_array($p['id_producto'], $ids_agregados)) {
            $relacionados[] = $p;
            $ids_agregados[] = $p['id_producto'];
        }
    }
}

// Mismos tienda
$stmtRelacionados = $conn->prepare("
    SELECT p.id_producto, p.nombre_producto, p.Foto, p.marca 
    FROM productos p
    JOIN producto_tienda pt ON p.id_producto = pt.id_producto
    WHERE pt.id_tienda IN (
        SELECT id_tienda FROM producto_tienda WHERE id_producto = ?
    ) AND p.id_producto != ?
    LIMIT 10
");
$stmtRelacionados->execute([$id_producto, $id_producto]);
agregarProductos($relacionados, $stmtRelacionados->fetchAll(PDO::FETCH_ASSOC), $ids_agregados);

// Misma categoría
if (count($relacionados) < 6 && !empty($producto['categoria'])) {
    $stmtCategoria = $conn->prepare("
        SELECT id_producto, nombre_producto, Foto, marca 
        FROM productos 
        WHERE categoria = ? AND id_producto != ?
        LIMIT 10
    ");
    $stmtCategoria->execute([$producto['categoria'], $id_producto]);
    agregarProductos($relacionados, $stmtCategoria->fetchAll(PDO::FETCH_ASSOC), $ids_agregados);
}

// Aleatorios
if (count($relacionados) < 6) {
    $stmtRandom = $conn->prepare("
        SELECT id_producto, nombre_producto, Foto, marca 
        FROM productos 
        WHERE id_producto != ? 
        ORDER BY RAND() 
        LIMIT 10
    ");
    $stmtRandom->execute([$id_producto]);
    agregarProductos($relacionados, $stmtRandom->fetchAll(PDO::FETCH_ASSOC), $ids_agregados);
}

require __DIR__ . '/../views/pages/detalles.php';
