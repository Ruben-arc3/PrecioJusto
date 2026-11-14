<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PrecioJusto - Encuentra Los Mejores Precios</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!--script javascript-->
    <script src="../js/animaciones.js"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="../css/styles.css">
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
    <?php if (isset($_SESSION['usuario'])): ?>
        <div class="dropdown">
            <button class="btn btn-light text-success fw-bold dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown">
                <i class="fas fa-user-circle me-1"></i> <?= htmlspecialchars($_SESSION['usuario']['nombre']) ?>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
    <li><a class="dropdown-item" href="/ProyectoDes/app/views/perfil.php"><i class="fas fa-user me-2"></i> Mi Perfil</a></li>
                <li><a class="dropdown-item" href="mostrar_favoritos.php"><i class="fas fa-heart me-2"></i> Favoritos</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión</a></li>
            </ul>
        </div>
    <?php else: ?>
        <a href="login.php" class="btn btn-light text-success fw-bold">
            <i class="fas fa-user me-1"></i> Iniciar Sesión
        </a>
    <?php endif; ?>
</div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
  <header class="hero-section bg-light py-5">
    <div class="container text-center py-4">
        <h1 class="display-4 fw-bold text-success mb-3">Compara y Ahorra en tus Productos</h1>
        <p class="lead mb-4">Encuentra los mejores precios y la información de tus productos favoritos</p>
        
        <!-- Formulario de búsqueda con autocompletado -->
        <div class="row justify-content-center">
            <div class="col-md-8">
                <form method="GET" action="" class="input-group mb-3 shadow-lg rounded-pill">
                    <input 
                        type="text" 
                        name="busqueda"
                        class="form-control form-control-lg border-success rounded-pill-start" 
                        placeholder="Buscar Producto..."
                        value="<?= htmlspecialchars($_GET['busqueda'] ?? '') ?>"
                        list="sugerenciasProductos"
                        id="buscadorProductos"
                        autocomplete="off"
                    >
                    <datalist id="sugerenciasProductos">
                        <!-- Las sugerencias se llenarán con JavaScript -->
                    </datalist>
                    <button class="btn btn-success rounded-pill-end px-4" type="submit">
                        <i class="fas fa-search me-1"></i> Buscar
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

    <!-- Sección de productos -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5 fw-bold">Productos en Oferta</h2>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php foreach ($productos as $producto):
                $esFavorito = $usuarioAutenticado && in_array($producto['id_producto'], $favoritosUsuario);
                
                // Obtener todas las tiendas donde está disponible este producto
                $query_tiendas_producto = "SELECT t.nombre_tienda, t.url 
                                          FROM producto_tienda pt 
                                          JOIN tiendas t ON pt.id_tienda = t.id_tienda 
                                          WHERE pt.id_producto = :id_producto";
                $stmt_tiendas = $conn->prepare($query_tiendas_producto);
                $stmt_tiendas->bindParam(':id_producto', $producto['id_producto']);
                $stmt_tiendas->execute();
                $tiendas_producto = $stmt_tiendas->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <div class="col">
                <div class="card h-100 product-card-compact">
                    <div class="image-wrapper ratio ratio-1x1">
                        <img src="<?= htmlspecialchars($producto['Foto'] ?? 'https://via.placeholder.com/300x300?text=Producto') ?>"
                             class="card-img-top p-2"
                             alt="<?= htmlspecialchars($producto['nombre_producto']) ?>"
                             onerror="this.src='https://via.placeholder.com/300x300?text=Imagen+No+Disponible'">
                        <?php if(!empty($tiendas_producto)): ?>
                        
                        <?php endif; ?>
                    </div>

                    <div class="card-body pt-0">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="card-title mb-1"><?= htmlspecialchars($producto['nombre_producto']) ?></h5>
                                <p class="text-muted small mb-2"><?= htmlspecialchars($producto['marca']) ?></p>
                            </div>
                            <?php if ($usuarioAutenticado): ?>
                            <button
                                class="btn btn-sm btn-outline-secondary border-0 product-favorite"
                                data-id="<?= $producto['id_producto'] ?>"
                                data-accion="<?= $esFavorito ? 'quitar' : 'agregar' ?>"
                                title="<?= $esFavorito ? 'Quitar de favoritos' : 'Agregar a favoritos' ?>"
                            >
                                <i class="<?= $esFavorito ? 'fas' : 'far' ?> fa-heart text-danger"></i>
                            </button>
                            <?php else: ?>
                            <button class="btn btn-sm btn-outline-secondary border-0 product-favorite unauthenticated"
                                    title="Inicia sesión para agregar a favoritos">
                                <i class="far fa-heart"></i>
                            </button>
                            <?php endif; ?>
                        </div>

                        <div class="mb-2">
                            <div class="d-flex align-items-center">
                                <div class="text-warning small rating-stars" data-producto-id="<?= $producto['id_producto'] ?>">
                                    <?php
                                    $promedio = round($producto['promedio_valoracion'] ?? 0, 1);
                                    $cantidad = (int)($producto['cantidad_valoraciones'] ?? 0);
                                    ?>
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="fa-star <?= $promedio >= $i ? 'fas' : ($promedio >= $i - 0.5 ? 'fas fa-star-half-alt' : 'far') ?>"
                                           data-value="<?= $i ?>" role="button" style="cursor:pointer;"></i>
                                    <?php endfor; ?>
                                </div>
                                <span class="ms-1 small text-muted">(<?= $cantidad ?>)</span>
                            </div>
                        </div>

                        <div class="tiendas-disponibles mt-3">
                            <p class="small fw-bold mb-2"><i class="fas fa-store-alt me-1"></i> Disponible en:</p>
                            <?php if(!empty($tiendas_producto)): ?>
                                <div class="d-flex flex-wrap gap-2">
                                    <?php foreach($tiendas_producto as $tienda): ?>
                                        <a href="<?= htmlspecialchars($tienda['url']) ?>" class="badge bg-light text-dark text-decoration-none" target="_blank">
                                            <?= htmlspecialchars($tienda['nombre_tienda']) ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="small text-muted">Actualmente no disponible en tiendas</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="card-footer bg-white border-0 text-center">
                        <a href="detalles.php?id=<?= $producto['id_producto'] ?>" class="btn btn-outline-success btn-sm rounded-pill px-3 py-1">
                            <i class="fas fa-info-circle me-1"></i> Ver detalles completos
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-5">
            <a href="productos.php" class="btn btn-outline-success px-4">
                <i class="fas fa-arrow-down me-2"></i> Ver más productos
            </a>
        </div>
    </div>
</section>

<!-- Toast para iniciar sesión (favoritos) -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1100">
    <div id="toastLogin" class="toast align-items-center text-bg-info border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-info-circle me-2"></i> Debes <a href="login.php" class="text-white fw-bold text-decoration-underline">iniciar sesión</a> para agregar a favoritos.
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<!-- Toast para mensajes generales (valoraciones) -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1100; max-width: 320px;">
  <div id="toastMensaje" class="toast align-items-center text-bg-info border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body">
        <!-- Mensaje dinámico aquí -->
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  </div>
</div>


<script src="../js/inicio-favoritos.js" defer></script>

<!-- Sección de Tiendas -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5 fw-bold"><i class="fas fa-store-alt me-2"></i> Nuestras Tiendas</h2>
        
        
        
        <div class="row g-4">
            <?php foreach ($tiendas as $tienda): ?>
            <div class="col-md-6 col-lg-3">
                <a href="<?= htmlspecialchars($tienda['url'] ?? '#') ?>" class="text-decoration-none store-link">
                    <div class="card h-100 border-0 shadow-sm text-center p-4 store-card">
                        <div class="icon-circle bg-success text-white mx-auto mb-3">
                            <i class="fas fa-store"></i>
                        </div>
                        <h5 class="fw-bold"><?= htmlspecialchars($tienda['nombre_tienda']) ?></h5>
                        <div class="mt-2">
                            <span class="btn btn-sm btn-outline-success">
                                Visitar tienda <i class="fas fa-chevron-right ms-1"></i>
                            </span>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
        
        <?php if(count($tiendas) === 0): ?>
        <div class="text-center py-4">
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-circle me-2"></i> No hay tiendas registradas aún
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5 class="fw-bold mb-3"> PrecioJusto</h5>
                    <p>La mejor herramienta para comparar precios de tus alimentos favoritos.</p>
                </div>
                <div class="col-md-2 mb-4 mb-md-0">
                    <h5 class="fw-bold mb-3">Enlaces</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="index.php" class="text-white-50">Inicio</a></li>
                        <li class="mb-2"><a href="productos.php" class="text-white-50">Productos</a></li>
                        <li class="mb-2"><a href="index.php" class="text-white-50">Categorías</a></li>
                        <li class="mb-2"><a href="ofertas.php" class="text-white-50">Ofetas</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-4 mb-md-0">
                    <h5 class="fw-bold mb-3">Contacto</h5>
                    <ul class="list-unstyled text-white-50">
                        <li class="mb-2"><i class="fas fa-envelope me-2"></i> info@preciojusto.com</li>
                        <li class="mb-2"><i class="fas fa-phone me-2"></i> +57 321 879 828</li>
                        <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i> Monteria, Cordoba</li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5 class="fw-bold mb-3">Síguenos</h5>
                    <div class="social-icons">
                        <a href="#" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <hr class="my-4 bg-secondary">
            <div class="text-center text-white-50 small">
                <p class="mb-0">&copy; 2025 PrecioJusto. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <script src="../js/inicio-busqueda.js" defer></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Script personalizado -->
</body>
</html>