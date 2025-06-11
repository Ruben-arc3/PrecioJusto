<?php
session_start();
require_once '../views/conexion.php';

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$stmt = $conn->query("SELECT * FROM productos");
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administrar Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="mb-4">Administrar Productos</h2>
    <a href="crear_producto.php" class="btn btn-success mb-3">Nuevo Producto</a>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Marca</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $producto): ?>
                <tr>
                    <td><?= $producto['id_producto'] ?></td>
                    <td><?= htmlspecialchars($producto['nombre_producto']) ?></td>
                    <td>$<?= number_format($producto['precio'], 2) ?></td>
                    <td><?= htmlspecialchars($producto['marca']) ?></td>
                    <td>
                        <a href="editar_producto.php?id=<?= $producto['id_producto'] ?>" class="btn btn-sm btn-primary">Editar</a>
                        <a href="eliminar_producto.php?id=<?= $producto['id_producto'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar producto?')">Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
