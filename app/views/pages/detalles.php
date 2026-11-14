<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($producto['nombre_producto']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="../js/animaciones.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/productos.css">
    <link rel="stylesheet" href="../css/detalles.css">
</head>
<body data-product-id="<?= (int)$id_producto ?>">

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

<div class="container py-5">
    <div class="row">
        <div class="col-md-5">
            <img src="<?= htmlspecialchars($producto['Foto']) ?>" alt="<?= htmlspecialchars($producto['nombre_producto']) ?>"
                 class="img-fluid rounded border shadow-sm"
                 onerror="this.src='https://via.placeholder.com/500x500?text=Imagen+No+Disponible'">
        </div>
        <div class="col-md-7">
            <h2 class="fw-bold"><?= htmlspecialchars($producto['nombre_producto']) ?></h2>
            <p class="text-muted"><?= htmlspecialchars($producto['marca']) ?></p>
           
            <!-- Sección modificada para mostrar tiendas y precios -->
            <?php if (count($tiendasDisponibles) > 0): ?>
                <div class="mb-3">
                    <h5><i class="fas fa-store me-1"></i> Disponible en:</h5>
                    <?php foreach ($tiendasDisponibles as $tienda): ?>
                        <div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-light rounded">
                            <div>
                                <strong><?= htmlspecialchars($tienda['nombre_tienda']) ?></strong>
                                <?php if (!empty($tienda['url_tienda'])): ?>
                                    <small class="d-block text-muted">
                                        <a href="<?= htmlspecialchars($tienda['url_tienda']) ?>" target="_blank" class="text-decoration-none">
                                            <?= parse_url($tienda['url_tienda'], PHP_URL_HOST) ?>
                                        </a>
                                    </small>
                                <?php endif; ?>
                            </div>
                            <div class="text-end">
                                <span class="text-success fw-bold">$<?= number_format($tienda['precio'], 2) ?></span>
                                <?php if (!empty($tienda['url_producto'])): ?>
                                    <a href="<?= htmlspecialchars($tienda['url_producto']) ?>" target="_blank" class="btn btn-sm btn-success mt-1">
                                        Comprar <i class="fas fa-external-link-alt ms-1"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="alert alert-warning">
                    Actualmente no disponible en ninguna tienda
                </div>
            <?php endif; ?>

            <!-- El resto de tu HTML (valoraciones, favoritos, productos relacionados) se mantiene igual -->
            <div class="mb-2">
                <div class="d-flex align-items-center">
                    <div class="text-warning small rating-stars" data-producto-id="<?= $producto['id_producto'] ?>">
                        <?php
                        $promedio = round($producto['promedio_valoracion'] ?? 0, 1);
                        $cantidad = (int)($producto['cantidad_valoraciones'] ?? 0);
                        ?>
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <i class="fa-star <?= $promedio >= $i ? 'fas' : ($promedio >= $i - 0.5 ? 'fas fa-star-half-alt' : 'far') ?> me-1"
                               data-value="<?= $i ?>" role="button" style="cursor:pointer;"></i>
                        <?php endfor; ?>
                    </div>
                    <span class="ms-1 small text-muted">(<?= $cantidad ?>)</span>
                </div>
            </div>

            <?php if (isset($_SESSION['usuario'])): ?>
                <?php
                $esFavorito = in_array($producto['id_producto'], $favoritosUsuario);
                $accion = $esFavorito ? 'quitar' : 'agregar';
                $iconClass = $esFavorito ? 'fas' : 'far';
                $btnText = $esFavorito ? 'En favoritos' : 'Agregar a favoritos';
                ?>
                <button 
                    class="btn btn-sm product-favorite mt-3 border-0 shadow-sm"
                    data-id="<?= $producto['id_producto'] ?>"
                    data-accion="<?= $accion ?>"
                    title="<?= $btnText ?>"
                    style="background-color: <?= $esFavorito ? '#dc3545' : 'white' ?>; color: <?= $esFavorito ? 'white' : '#dc3545' ?>;"
                    onmouseover="this.style.backgroundColor='<?= $esFavorito ? '#c82333' : '#ffecec' ?>'"
                    onmouseout="this.style.backgroundColor='<?= $esFavorito ? '#dc3545' : 'white' ?>'">
                    <i class="<?= $iconClass ?> fa-heart me-2" style="color: <?= $esFavorito ? 'white' : '#dc3545' ?>"></i>
                    <span><?= $btnText ?></span>
                </button>
            <?php else: ?>
                <div class="alert alert-light mt-3 d-flex align-items-center border shadow-sm" style="border-color: #dc3545 !important;">
                    <i class="fas fa-info-circle me-2" style="color: #dc3545"></i>
                    <small class="text-muted">Inicia sesión para guardar favoritos</small>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- El resto de tu HTML (productos relacionados, comentarios) se mantiene igual -->
    <?php if ($relacionados): ?>
        <hr class="my-5">
        <h4 class="mb-4"><i class="fas fa-thumbs-up me-2 text-success"></i>También te puede interesar</h4>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4 mb-5">
            <?php foreach ($relacionados as $rel): ?>
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="position-relative">
                            <img src="<?= htmlspecialchars($rel['Foto']) ?>"
                                 class="card-img-top rounded-top"
                                 alt="<?= htmlspecialchars($rel['nombre_producto']) ?>"
                                 style="object-fit: cover; height: 220px;"
                                 onerror="this.src='https://via.placeholder.com/300?text=Imagen+No+Disponible'">
                        </div>
                        <div class="card-body">
                            <h6 class="fw-bold text-truncate" title="<?= htmlspecialchars($rel['nombre_producto']) ?>">
                                <?= htmlspecialchars($rel['nombre_producto']) ?>
                            </h6>
                            <p class="text-muted small mb-1"><?= htmlspecialchars($rel['marca']) ?></p>
                        </div>
                        <div class="card-footer bg-transparent border-0 pt-0">
                            <a href="detalles.php?id=<?= $rel['id_producto'] ?>" class="btn btn-outline-success w-100">
                                <i class="fas fa-info-circle me-1"></i> Ver Detalles
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>


<!-- Sección de comentarios -->
<hr class="my-5">
<h4 class="mb-4"><i class="fas fa-comments me-2 text-primary"></i>Comentarios del producto</h4>
<div class="row">
    <div class="col-md-6">
        <!-- Caja del formulario de comentario -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <?php if (isset($_SESSION['usuario'])): ?>
                    <form id="form-comentario">
                        <input type="hidden" name="id_producto" value="<?= $id_producto ?>">
                        <div class="mb-3">
                            <label for="comentario" class="form-label fw-semibold">Escribe un comentario</label>
                            <textarea name="comentario" id="comentario" class="form-control rounded-3 shadow-sm" rows="3"
                                      placeholder="Tu opinión nos importa..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-1"></i> Publicar comentario
                        </button>
                    </form>
                <?php else: ?>
                    <div class="alert alert-info d-flex align-items-center shadow-sm mb-0">
                        <i class="fas fa-info-circle me-2 text-primary"></i>
                        <div>
                            <a href="login.php" class="btn btn-sm btn-primary me-2">Inicia sesión</a> para dejar tu comentario.
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Caja con scroll para comentarios -->
        <div id="comentarios-container" class="card shadow-sm border-0" style="max-height: 300px; overflow-y: auto;">
            <div class="card-body p-3">
                <!-- Comentarios se cargarán aquí vía AJAX -->
                <!-- Ejemplo estático (elimina esto si cargas dinámicamente): -->
                <!--
                <div class="mb-3 pb-2 border-bottom">
                    <div class="fw-semibold mb-1">Usuario Demo <span class="text-muted small">• hace 1 día</span></div>
                    <div class="text-muted">Muy buen producto, lo volvería a comprar.</div>
                </div>
                -->
            </div>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../js/detalles-comentarios-dinamicos.js" defer></script>


<script src="../js/detalles-valoraciones.js" defer></script>

<script src="../js/detalles-favoritos.js" defer></script>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Script personalizado -->


</body>
</html>
