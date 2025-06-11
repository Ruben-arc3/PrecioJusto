<?php
session_start();
require_once 'conexion.php';

$usuarioAutenticado = isset($_SESSION['usuario']);

// Manejar acción de favoritos (AJAX o normal)
if ($usuarioAutenticado && isset($_GET['accion']) && isset($_GET['producto_id'])) {
    $productoId = (int)$_GET['producto_id'];
    $usuarioId = $_SESSION['usuario']['id'];
    $accion = $_GET['accion'];
    $esAjax = isset($_GET['ajax']) && $_GET['ajax'] === '1';

    $success = false;
    $message = '';

    if ($accion === 'agregar') {
        $stmt = $conn->prepare("INSERT IGNORE INTO favoritos (id_usuario, id_producto) VALUES (?, ?)");
        $success = $stmt->execute([$usuarioId, $productoId]);
        $message = 'Producto agregado a favoritos';
    } elseif ($accion === 'quitar') {
        $stmt = $conn->prepare("DELETE FROM favoritos WHERE id_usuario = ? AND id_producto = ?");
        $success = $stmt->execute([$usuarioId, $productoId]);
        $message = 'Producto eliminado de favoritos';
    }

    if ($esAjax) {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => $success,
            'action' => $accion === 'agregar' ? 'agregado' : 'quitado',
            'message' => $message
        ]);
        exit;
    } else {
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Manejar AJAX para valorar (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'valorar') {
    header('Content-Type: application/json');
    if (!$usuarioAutenticado) {
        echo json_encode(['success' => false, 'message' => 'Debes iniciar sesión para valorar']);
        exit;
    }

    $usuarioId = $_SESSION['usuario']['id'];
    $productoId = isset($_POST['producto_id']) ? (int)$_POST['producto_id'] : 0;
    $puntuacion = isset($_POST['puntuacion']) ? (int)$_POST['puntuacion'] : 0;

    if ($productoId <= 0 || $puntuacion < 1 || $puntuacion > 5) {
        echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
        exit;
    }

    $stmtCheck = $conn->prepare("SELECT id_valoracion FROM valoraciones WHERE id_usuario = ? AND id_producto = ?");
    $stmtCheck->execute([$usuarioId, $productoId]);
    $valoracionExistente = $stmtCheck->fetchColumn();

    if ($valoracionExistente) {
        $stmtUpdate = $conn->prepare("UPDATE valoraciones SET puntuacion = ?, fecha_creacion = NOW() WHERE id_valoracion = ?");
        $stmtUpdate->execute([$puntuacion, $valoracionExistente]);
    } else {
        $stmtInsert = $conn->prepare("INSERT INTO valoraciones (id_producto, id_usuario, puntuacion, fecha_creacion) VALUES (?, ?, ?, NOW())");
        $stmtInsert->execute([$productoId, $usuarioId, $puntuacion]);
    }

    echo json_encode(['success' => true, 'message' => 'Gracias por su valoración']);
    exit;
}

// Obtener favoritos del usuario autenticado
$favoritosUsuario = [];
if ($usuarioAutenticado) {
    $queryFav = "SELECT id_producto FROM favoritos WHERE id_usuario = ?";
    $stmtFav = $conn->prepare($queryFav);
    $stmtFav->execute([$_SESSION['usuario']['id']]);
    $favoritosUsuario = $stmtFav->fetchAll(PDO::FETCH_COLUMN);
}

// Consulta de productos (con o sin búsqueda)
$query = "SELECT 
            p.*, 
            t.nombre_tienda, 
            ROUND(AVG(v.puntuacion), 1) AS promedio_valoracion,
            COUNT(DISTINCT v.id_usuario) AS cantidad_valoraciones
          FROM productos p
          LEFT JOIN tiendas t ON p.id_tienda = t.id_tienda
          LEFT JOIN categoria_productos cp ON p.id_producto = cp.id_producto
          LEFT JOIN categorias c ON cp.id_categoria = c.id_categoria
          LEFT JOIN valoraciones v ON p.id_producto = v.id_producto";

$parametros = [];
$whereClauses = [];

if (!empty($_GET['busqueda'])) {
    $whereClauses[] = "(p.nombre_producto LIKE :busqueda
                       OR p.marca LIKE :busqueda
                       OR t.nombre_tienda LIKE :busqueda
                       OR c.nombre_categoria LIKE :busqueda)";
    $parametros[':busqueda'] = '%' . $_GET['busqueda'] . '%';
}

if (!empty($whereClauses)) {
    $query .= " WHERE " . implode(' AND ', $whereClauses);
}

$query .= " GROUP BY p.id_producto, t.nombre_tienda";
$query .= " LIMIT 6";

$stmt = $conn->prepare($query);
$stmt->execute($parametros);
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$noResults = (!empty($_GET['busqueda']) && empty($productos));
?>
