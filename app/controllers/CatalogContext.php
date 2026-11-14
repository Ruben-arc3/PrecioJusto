<?php
session_start();
require_once __DIR__ . '/../models/connection.php';

$usuarioAutenticado = isset($_SESSION['usuario']);

// Gestiona agregar o quitar favoritos desde la página de inicio.
if ($usuarioAutenticado && isset($_GET['accion'], $_GET['producto_id'])) {
    $productoId = (int)$_GET['producto_id'];
    $usuarioId = (int)$_SESSION['usuario']['id'];
    $accion = $_GET['accion'];
    $esAjax = isset($_GET['ajax']) && $_GET['ajax'] === '1';
    $success = false;
    $message = '';

    if ($accion === 'agregar') {
        $stmt = $conn->prepare('INSERT IGNORE INTO favoritos (id_usuario, id_producto) VALUES (?, ?)');
        $success = $stmt->execute([$usuarioId, $productoId]);
        $message = 'Producto agregado a favoritos';
    } elseif ($accion === 'quitar') {
        $stmt = $conn->prepare('DELETE FROM favoritos WHERE id_usuario = ? AND id_producto = ?');
        $success = $stmt->execute([$usuarioId, $productoId]);
        $message = 'Producto eliminado de favoritos';
    }

    if ($esAjax) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'success' => $success,
            'action' => $accion === 'agregar' ? 'agregado' : 'quitado',
            'message' => $message,
        ]);
        exit;
    }

    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Guarda una calificación enviada por AJAX.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['accion'] ?? '') === 'valorar') {
    header('Content-Type: application/json; charset=utf-8');
    if (!$usuarioAutenticado) {
        echo json_encode(['success' => false, 'message' => 'Debes iniciar sesión para valorar']);
        exit;
    }

    $usuarioId = (int)$_SESSION['usuario']['id'];
    $productoId = (int)($_POST['producto_id'] ?? 0);
    $puntuacion = (int)($_POST['puntuacion'] ?? 0);
    if ($productoId <= 0 || $puntuacion < 1 || $puntuacion > 5) {
        echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
        exit;
    }

    $stmtCheck = $conn->prepare('SELECT id_valoracion FROM valoraciones WHERE id_usuario = ? AND id_producto = ?');
    $stmtCheck->execute([$usuarioId, $productoId]);
    $valoracionExistente = $stmtCheck->fetchColumn();

    if ($valoracionExistente) {
        $stmtUpdate = $conn->prepare('UPDATE valoraciones SET puntuacion = ?, fecha_creacion = NOW() WHERE id_valoracion = ?');
        $stmtUpdate->execute([$puntuacion, $valoracionExistente]);
    } else {
        $stmtInsert = $conn->prepare('INSERT INTO valoraciones (id_producto, id_usuario, puntuacion, fecha_creacion) VALUES (?, ?, ?, NOW())');
        $stmtInsert->execute([$productoId, $usuarioId, $puntuacion]);
    }

    echo json_encode(['success' => true, 'message' => 'Gracias por su valoración']);
    exit;
}

$favoritosUsuario = [];
if ($usuarioAutenticado) {
    $stmtFav = $conn->prepare('SELECT id_producto FROM favoritos WHERE id_usuario = ?');
    $stmtFav->execute([(int)$_SESSION['usuario']['id']]);
    $favoritosUsuario = $stmtFav->fetchAll(PDO::FETCH_COLUMN);
}

$query = "SELECT p.*,
            GROUP_CONCAT(DISTINCT t.nombre_tienda SEPARATOR '|') AS tiendas,
            GROUP_CONCAT(DISTINCT pt.precio SEPARATOR '|') AS precios,
            MIN(pt.precio) AS precio_minimo,
            ROUND(AVG(v.puntuacion), 1) AS promedio_valoracion,
            COUNT(DISTINCT v.id_usuario) AS cantidad_valoraciones
          FROM productos p
          LEFT JOIN producto_tienda pt ON p.id_producto = pt.id_producto
          LEFT JOIN tiendas t ON pt.id_tienda = t.id_tienda
          LEFT JOIN categorias c ON p.id_categoria = c.id_categoria
          LEFT JOIN valoraciones v ON p.id_producto = v.id_producto";

$parametros = [];
$whereClauses = [];
if (!empty($_GET['busqueda'])) {
    $whereClauses[] = '(p.nombre_producto LIKE :busqueda OR p.marca LIKE :busqueda OR t.nombre_tienda LIKE :busqueda OR c.nombre_categoria LIKE :busqueda)';
    $parametros[':busqueda'] = '%' . trim($_GET['busqueda']) . '%';
}
if ($whereClauses) {
    $query .= ' WHERE ' . implode(' AND ', $whereClauses);
}
$query .= ' GROUP BY p.id_producto, p.nombre_producto, p.Foto, p.marca, p.id_categoria LIMIT 6';

$stmt = $conn->prepare($query);
$stmt->execute($parametros);
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
$noResults = (!empty($_GET['busqueda']) && empty($productos));

// La vista recibe tiendas desde el controlador, sin consultar la base directamente.
$tiendasQuery = $conn->query('SELECT id_tienda, nombre_tienda, url FROM tiendas ORDER BY nombre_tienda');
$tiendas = $tiendasQuery->fetchAll(PDO::FETCH_ASSOC);