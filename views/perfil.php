<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

$idUsuario = $_SESSION['usuario']['id'];
$mensaje = '';
$tipoMensaje = '';

// Actualizar datos si se envía el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);
    $contrasena = $_POST['contrasena'];
    $confirmar = $_POST['confirmar'];

    // Validaciones básicas
    if (empty($nombre) || empty($correo)) {
        $mensaje = "Nombre y correo no pueden estar vacíos.";
        $tipoMensaje = 'danger';
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $mensaje = "Correo inválido.";
        $tipoMensaje = 'danger';
    } elseif (!empty($contrasena) && $contrasena !== $confirmar) {
        $mensaje = "Las contraseñas no coinciden.";
        $tipoMensaje = 'danger';
    } else {
        // Verificar si el correo está en uso por otro usuario
        $stmt = $conn->prepare("SELECT id_usuario FROM usuarios WHERE correo = ? AND id_usuario != ?");
        $stmt->execute([$correo, $idUsuario]);
        if ($stmt->fetch()) {
            $mensaje = "El correo ya está en uso por otro usuario.";
            $tipoMensaje = 'danger';
        } else {
            // Actualizar usuario
            if (!empty($contrasena)) {
                $hash = password_hash($contrasena, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE usuarios SET nombre = ?, correo = ?, contrasena = ? WHERE id_usuario = ?");
                $stmt->execute([$nombre, $correo, $hash, $idUsuario]);
            } else {
                $stmt = $conn->prepare("UPDATE usuarios SET nombre = ?, correo = ? WHERE id_usuario = ?");
                $stmt->execute([$nombre, $correo, $idUsuario]);
            }

            // Actualizar sesión
            $_SESSION['usuario']['nombre'] = $nombre;
            $_SESSION['usuario']['correo'] = $correo;

            $mensaje = "Perfil actualizado correctamente.";
            $tipoMensaje = 'success';
        }
    }
}

// Cargar datos actuales del usuario
$stmt = $conn->prepare("SELECT nombre, correo FROM usuarios WHERE id_usuario = ?");
$stmt->execute([$idUsuario]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Perfil | PrecioJusto</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-success sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
               PrecioJusto
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php"><i class="fas fa-home me-1"></i> Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="productos.php"><i class="fas fa-list me-1"></i> Productos</a>
                    </li>
                   <li class="nav-item dropdown">
  <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
    <i class="fas fa-tags"></i> Categorías
  </a>
  <ul class="dropdown-menu">
    <li><a class="dropdown-item" href="categorias.php?id=6">🍎 Frutas y Verduras</a></li>
    <li><a class="dropdown-item" href="categorias.php?id=4">🧀 Lácteos y Huevos</a></li>
    <li><a class="dropdown-item" href="categorias.php?id=8">🍞 Panadería</a></li>
    <li><a class="dropdown-item" href="categorias.php?id=5">🥤 Bebidas</a></li>
    <li><a class="dropdown-item" href="categorias.php?id=7">🌱 Orgánicos</a></li>
  </ul>
</li>



                    <li class="nav-item"><a class="nav-link" href="ofertas.php"><i class="fas fa-tag"></i> Ofertas</a></li>
      </ul>
      <div class="d-flex">
        <div class="dropdown">
          <button class="btn btn-light text-success fw-bold dropdown-toggle" type="button" data-bs-toggle="dropdown">
            <i class="fas fa-user-circle me-1"></i> <?= htmlspecialchars($_SESSION['usuario']['nombre']) ?>
          </button>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="perfil.php"><i class="fas fa-user me-2"></i> Mi Perfil</a></li>
            <li><a class="dropdown-item" href="mostrar_favoritos.php"><i class="fas fa-heart me-2"></i> Favoritos</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</nav>

<!-- ✅ FORMULARIO -->
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <h2 class="mb-4 text-success text-center"><i class="fas fa-user-circle"></i> Mi Perfil</h2>

            <?php if ($mensaje): ?>
                <div class="alert alert-<?= $tipoMensaje ?>"><?= $mensaje ?></div>
            <?php endif; ?>

            <form method="POST" action="perfil.php">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="correo" class="form-label">Correo electrónico</label>
                    <input type="email" name="correo" class="form-control" value="<?= htmlspecialchars($usuario['correo']) ?>" required>
                </div>
                <hr>
                <div class="mb-3">
                    <label for="contrasena" class="form-label">Nueva contraseña (opcional)</label>
                    <input type="password" name="contrasena" class="form-control" placeholder="Solo si deseas cambiarla">
                </div>
                <div class="mb-3">
                    <label for="confirmar" class="form-label">Confirmar nueva contraseña</label>
                    <input type="password" name="confirmar" class="form-control" placeholder="Repetir contraseña">
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i> Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
