<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

// Obtener productos favoritos del usuario
$id_usuario = $_SESSION['usuario']['id'];
$sql = "SELECT p.id_producto, p.nombre_producto, p.precio, p.Foto, p.marca, p.id_tienda, 
               t.nombre_tienda 
        FROM favoritos f
        JOIN productos p ON f.id_producto = p.id_producto
        LEFT JOIN tiendas t ON p.id_tienda = t.id_tienda
        WHERE f.id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id_usuario]);
$favoritos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Favoritos - PrecioJusto</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/estilosfavoritos.css">
    
 

    
</head>
<body>
    <!-- Navbar -->
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

    <div class="container py-3">
        <div class="row mb-3">
            <div class="col-12">
                <div class="favorites-header">
                    <h2 class="fw-bold mb-1"><i class="fas fa-heart text-danger me-2"></i>Mis Favoritos</h2>
                    <p class="favorites-count"><?= count($favoritos) ?> producto<?= count($favoritos) !== 1 ? 's' : '' ?> guardado<?= count($favoritos) !== 1 ? 's' : '' ?></p>
                </div>
            </div>
        </div>

        <?php if (empty($favoritos)): ?>
            <div class="empty-favorites">
                <i class="fas fa-heart-broken"></i>
                <h3>Tu lista de favoritos está vacía</h3>
                <p class="text-muted">Agrega productos a tus favoritos para verlos aquí</p>
                <a href="productos.php" class="btn btn-success explore-btn">
                    <i class="fas fa-store me-1"></i> Explorar productos
                </a>
            </div>
        <?php else: ?>
            <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 g-3">
                <?php foreach ($favoritos as $producto): ?>
                    <div class="col">
                        <div class="card product-card h-100">
                            <button class="btn-eliminar-favorito" data-id="<?= $producto['id_producto'] ?>" title="Eliminar de favoritos">
                                <i class="fas fa-times"></i>
                            </button>
                            <div class="card-img-container">
                                <img src="<?= htmlspecialchars($producto['Foto']) ?>" 
                                     class="card-img-top" 
                                     alt="<?= htmlspecialchars($producto['nombre_producto']) ?>"
                                     onerror="this.src='https://via.placeholder.com/300?text=Imagen+No+Disponible'">
                            </div>
                            <div class="card-body">
                                <h6 class="product-title"><?= htmlspecialchars($producto['nombre_producto']) ?></h6>
                                <p class="product-brand"><?= htmlspecialchars($producto['marca']) ?></p>
                                <p class="product-price">$<?= number_format($producto['precio'], 2) ?></p>
                                <p class="store-name">
                                    <i class="fas fa-store"></i> <?= htmlspecialchars($producto['nombre_tienda']) ?>
                                </p>
                            </div>
                            <div class="card-footer bg-white border-0 pt-0">
                                <a href="detalles.php?id=<?= $producto['id_producto'] ?>" class="btn btn-sm btn-success btn-details w-100">
                                    <i class="fas fa-info-circle me-1"></i> Detalles
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    $(document).ready(function() {
        // Eliminar de favoritos
        $('.btn-eliminar-favorito').click(function(e) {
            e.preventDefault();
            const productId = $(this).data('id');
            const card = $(this).closest('.col');
            
            if (confirm('¿Eliminar este producto de tus favoritos?')) {
                $.post('eliminar_favorito.php', {
                    id_producto: productId
                }, function(response) {
                    if (response === 'ok') {
                        card.fadeOut(300, function() {
                            $(this).remove();
                            // Actualizar contador
                            const count = $('.col').length;
                            const countText = count + ' producto' + (count !== 1 ? 's' : '') + ' guardado' + (count !== 1 ? 's' : '');
                            $('.favorites-count').text(countText);
                            
                            if (count === 0) {
                                location.reload(); // Recargar si no hay más favoritos
                            }
                        });
                    } else {
                        alert('Error al eliminar de favoritos');
                    }
                }).fail(function() {
                    alert('Error de conexión');
                });
            }
        });
    });
    </script>
</body>
</html>