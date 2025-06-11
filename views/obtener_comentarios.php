<?php
session_start();
require_once 'conexion.php';

if (!isset($_GET['id_producto']) || !is_numeric($_GET['id_producto'])) {
    die("ID de producto no válido");
}

$id_producto = (int)$_GET['id_producto'];

$stmt = $conn->prepare("
    SELECT c.id_comentario, c.contenido, c.fecha_creacion, u.nombre, u.id_usuario 
    FROM comentarios c
    JOIN usuarios u ON c.id_usuario = u.id_usuario
    WHERE c.id_producto = ?
    ORDER BY c.fecha_creacion DESC
    LIMIT 10
");
$stmt->execute([$id_producto]);
$comentarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($comentarios)) {
    echo '<p class="text-muted">No hay comentarios aún. Sé el primero en comentar.</p>';
    exit;
}

foreach ($comentarios as $comentario) {
    $esPropietario = isset($_SESSION['usuario']['id']) && $_SESSION['usuario']['id'] == $comentario['id_usuario'];
    
    echo '
    <div class="comentario-box p-3 mb-3 shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <strong>'.htmlspecialchars($comentario['nombre']).'</strong>
            <small class="text-muted">'.date("d/m/Y H:i", strtotime($comentario['fecha_creacion'])).'</small>
        </div>
        <p class="mb-2">'.htmlspecialchars($comentario['contenido']).'</p>';
    
    if ($esPropietario) {
        echo '
        <div class="btn-group btn-group-sm">
            <button class="btn btn-outline-primary btn-editar-comentario" 
                    data-id="'.$comentario['id_comentario'].'">
                <i class="fas fa-edit"></i> Editar
            </button>
            <button class="btn btn-outline-danger btn-eliminar-comentario" 
                    data-id="'.$comentario['id_comentario'].'">
                <i class="fas fa-trash"></i> Eliminar
            </button>
        </div>';
    }
    
    echo '</div>';
}
?>