<?php
session_start();
require_once 'conexion.php';

// Parámetros
$busqueda = isset($_GET['busqueda']) ? trim($_GET['busqueda']) : '';
$tienda = isset($_GET['tienda']) ? intval($_GET['tienda']) : 0;
$orden = $_GET['orden'] ?? 'recientes';
$pagina = max(1, intval($_GET['pagina'] ?? 1));
$porPagina = 12;

// Tiendas
$tiendas = $conn->query("SELECT id_tienda, nombre_tienda FROM tiendas ORDER BY nombre_tienda")->fetchAll(PDO::FETCH_ASSOC);

// SQL base
$sql = "SELECT p.id_producto, p.nombre_producto, p.precio, p.Foto, p.marca, t.nombre_tienda
        FROM productos p
        JOIN oferta o ON o.id_producto = p.id_producto
        JOIN tiendas t ON p.id_tienda = t.id_tienda";

$where = [];
$params = [];

// Filtro búsqueda
if (!empty($busqueda)) {
    $where[] = "(p.nombre_producto LIKE ? OR p.marca LIKE ? OR t.nombre_tienda LIKE ?)";
    $params = array_fill(0, 3, "%$busqueda%");
}

// Filtro tienda
if ($tienda > 0) {
    $where[] = "p.id_tienda = ?";
    $params[] = $tienda;
}

if (!empty($where)) {
    $sql .= " WHERE " . implode(" AND ", $where);
}

// Ordenamiento
switch ($orden) {
    case 'precio_asc': $sql .= " ORDER BY p.precio ASC"; break;
    case 'precio_desc': $sql .= " ORDER BY p.precio DESC"; break;
    case 'nombre_asc': $sql .= " ORDER BY p.nombre_producto ASC"; break;
    case 'nombre_desc': $sql .= " ORDER BY p.nombre_producto DESC"; break;
    default: $sql .= " ORDER BY p.id_producto DESC";
}

// Paginación
$stmtTotal = $conn->prepare("SELECT COUNT(*) FROM (" . str_replace("p.id_producto, p.nombre_producto, p.precio, p.Foto, p.marca, t.nombre_tienda", "p.id_producto", $sql) . ") AS total");
$stmtTotal->execute($params);
$totalProductos = $stmtTotal->fetchColumn();
$totalPaginas = ceil($totalProductos / $porPagina);

$sql .= " LIMIT " . (($pagina - 1) * $porPagina) . ", $porPagina";

// Obtener productos
$stmt = $conn->prepare($sql);
$stmt->execute($params);
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Ofertas | PrecioJusto</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <!-- css -->
    <link rel="stylesheet" href="../css/styles.css">
  <link rel="stylesheet" href="../css/productos.css">

</head>
<body>

<!-- ✅ NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-success sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php">PrecioJusto</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="index.php"><i class="fas fa-home me-1"></i> Inicio</a></li>
        <li class="nav-item"><a class="nav-link" href="productos.php"><i class="fas fa-list me-1"></i> Productos</a></li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown"><i class="fas fa-tags me-1"></i> Categorías</a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="categorias.php?id=6">🍎 Frutas y Verduras</a></li>
            <li><a class="dropdown-item" href="categorias.php?id=4">🧀 Lácteos y Huevos</a></li>
            <li><a class="dropdown-item" href="categorias.php?id=8">🍞 Panadería</a></li>
            <li><a class="dropdown-item" href="categorias.php?id=5">🥤 Bebidas</a></li>
            <li><a class="dropdown-item" href="categorias.php?id=7">🌱 Orgánicos</a></li>
          </ul>
        </li>
        <li class="nav-item"><a class="nav-link active" href="ofertas.php"><i class="fas fa-tag"></i> Ofertas</a></li>
      </ul>
      <div class="d-flex">
        <?php if (isset($_SESSION['usuario'])): ?>
          <div class="dropdown">
            <button class="btn btn-light text-success fw-bold dropdown-toggle" type="button" data-bs-toggle="dropdown">
              <i class="fas fa-user-circle me-1"></i> <?= htmlspecialchars($_SESSION['usuario']['nombre']) ?>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
    <li><a class="dropdown-item" href="/ProyectoDes/app/views/perfil.php"><i class="fas fa-user me-2"></i> Mi Perfil</a></li>

              <li><a class="dropdown-item" href="mostrar_favoritos.php"><i class="fas fa-heart me-2"></i> Favoritos</a></li>
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

<!-- ✅ CONTENIDO -->
<div class="container py-4">
  <div class="row mb-4 align-items-center">
    <div class="col-md-6">
      <h2 class="fw-bold">Productos en Oferta</h2>
      <p class="text-muted">Mostrando <?= count($productos) ?> de <?= $totalProductos ?> productos</p>
    </div>
    <div class="col-md-6">
      <form class="d-flex" method="GET">
        <input type="text" name="busqueda" class="form-control me-2" placeholder="Buscar..." value="<?= htmlspecialchars($busqueda) ?>">
        <input type="hidden" name="tienda" value="<?= $tienda ?>">
        <button class="btn btn-success"><i class="fas fa-search"></i></button>
      </form>
    </div>
  </div>

  <div class="row">
    <div class="col-md-3 mb-4">
      <div class="card">
        <div class="card-header bg-success text-white">Filtros</div>
        <div class="card-body">
          <label>Tiendas</label>
          <select class="form-select mb-3" onchange="location = this.value;">
            <option value="ofertas.php?<?= http_build_query(array_merge($_GET, ['tienda' => 0, 'pagina' => 1])) ?>" <?= $tienda == 0 ? 'selected' : '' ?>>Todas</option>
            <?php foreach ($tiendas as $t): ?>
              <option value="ofertas.php?<?= http_build_query(array_merge($_GET, ['tienda' => $t['id_tienda'], 'pagina' => 1])) ?>" <?= $tienda == $t['id_tienda'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($t['nombre_tienda']) ?>
              </option>
            <?php endforeach; ?>
          </select>

          <label>Ordenar por</label>
          <div class="d-grid gap-2">
            <?php
            $ordenes = [
              'recientes' => 'Más recientes',
              'precio_asc' => 'Precio menor a mayor',
              'precio_desc' => 'Precio mayor a menor',
              'nombre_asc' => 'Nombre A-Z',
              'nombre_desc' => 'Nombre Z-A'
            ];
            foreach ($ordenes as $clave => $texto): ?>
              <a href="ofertas.php?<?= http_build_query(array_merge($_GET, ['orden' => $clave, 'pagina' => 1])) ?>"
                 class="btn btn-sm btn-outline-success <?= $orden === $clave ? 'active' : '' ?>"><?= $texto ?></a>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-9">
      <?php if (empty($productos)): ?>
        <div class="alert alert-info text-center">No hay productos en oferta.</div>
      <?php else: ?>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
          <?php foreach ($productos as $p): ?>
            <div class="col">
              <div class="card h-100">
                <img src="<?= htmlspecialchars($p['Foto']) ?>" class="card-img-top" alt="Producto" style="height: 200px; object-fit: contain;" onerror="this.src='https://via.placeholder.com/300?text=Sin+imagen'">
                <div class="card-body">
                  <h6 class="card-title"><?= htmlspecialchars($p['nombre_producto']) ?></h6>
                  <p class="text-muted mb-1"><?= htmlspecialchars($p['marca']) ?> | <?= htmlspecialchars($p['nombre_tienda']) ?></p>
                  <p class="fw-bold text-success mb-0">$<?= number_format($p['precio'], 0, ',', '.') ?></p>
                </div>
                <div class="card-footer bg-white border-top-0">
                  <a href="detalles.php?id=<?= $p['id_producto'] ?>" class="btn btn-sm btn-success w-100">
                    <i class="fas fa-shopping-cart me-1"></i> Ver Detalles
                  </a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>
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
                        <li class="mb-2"><a href="ofertas.php" class="text-white-50">Ofertas</a></li>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
