<?php
session_start();
require_once '../views/conexion.php';

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$stmt = $conn->query("SELECT * FROM tiendas");
$tiendas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administrar Tiendas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="mb-4">Administrar Tiendas</h2>
    <a href="crear_tienda.php" class="btn btn-success mb-3">Nueva Tienda</a>
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>URL</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tiendas as $tienda): ?>
                <tr>
                    <td><?= $tienda['id_tienda'] ?></td>
                    <td><?= htmlspecialchars($tienda['nombre_tienda']) ?></td>
                    <td><a href="<?= htmlspecialchars($tienda['url']) ?>" target="_blank"><?= htmlspecialchars($tienda['url']) ?></a></td>
                    <td>
                        <a href="editar_tienda.php?id=<?= $tienda['id_tienda'] ?>" class="btn btn-sm btn-primary">Editar</a>
                        <a href="eliminar_tienda.php?id=<?= $tienda['id_tienda'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar tienda?')">Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>

