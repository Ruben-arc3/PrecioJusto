<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
            <div class="sidebar-brand-icon">
                <i class="fas fa-store"></i>
            </div>
            <div class="sidebar-brand-text mx-3">PrecioJusto</div>
        </a>
        <hr class="sidebar-divider">
        <div class="nav-item">
            <a class="nav-link" href="dashboard.php">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </div>
        <hr class="sidebar-divider">
        <div class="nav-item">
            <a class="nav-link" href="gestionar_productos.php">
                <i class="fas fa-fw fa-box"></i>
                <span>Productos</span>
            </a>
        </div>
        <div class="nav-item">
            <a class="nav-link" href="gestionar_usuarios.php">
                <i class="fas fa-fw fa-users"></i>
                <span>Usuarios</span>
            </a>
        </div>
        <div class="nav-item">
            <a class="nav-link" href="gestionar_tiendas.php">
                <i class="fas fa-fw fa-store"></i>
                <span>Tiendas</span>
            </a>
        </div>
        <hr class="sidebar-divider">
        <div class="nav-item">
            <a class="nav-link" href="configuracion.php">
                <i class="fas fa-fw fa-cog"></i>
                <span>Configuración</span>
            </a>
        </div>
    </div>

    <!-- Content Wrapper -->
    <div class="content">
        <!-- Topbar -->
        <nav class="navbar navbar-expand topbar mb-4 static-top shadow">
            <!-- Sidebar Toggle (Topbar) -->
            <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                <i class="fa fa-bars"></i>
            </button>

            <!-- Topbar Search -->
            <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                <div class="input-group">
                    <input type="text" class="form-control bg-light border-0 small" placeholder="Buscar..." aria-label="Search">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button">
                            <i class="fas fa-search fa-sm"></i>
                        </button>
                    </div>
                </div>
            </form>

            <!-- Topbar Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Nav Item - Alerts -->
                <li class="nav-item dropdown no-arrow mx-1">
                    <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-bell fa-fw"></i>
                        <!-- Counter - Alerts -->
                        <span class="badge badge-danger badge-counter">3+</span>
                    </a>
                </li>

                <!-- Nav Item - Messages -->
                <li class="nav-item dropdown no-arrow mx-1">
                    <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-envelope fa-fw"></i>
                        <!-- Counter - Messages -->
                        <span class="badge badge-danger badge-counter">7</span>
                    </a>
                </li>

                <div class="topbar-divider d-none d-sm-block"></div>

                <!-- Nav Item - User Information -->
                <li class="nav-item dropdown no-arrow">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                        <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= htmlspecialchars($_SESSION['usuario']['nombre'] ?? 'Admin') ?></span>
                        <img class="img-profile rounded-circle user-avatar" src="https://source.unsplash.com/QAB-WJcbgJk/60x60">
                    </a>
                    <!-- Dropdown - User Information -->
                    <div class="dropdown-menu dropdown-menu-right shadow">
                        <a class="dropdown-item" href="#">
                            <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                            Perfil
                        </a>
                        <a class="dropdown-item" href="#">
                            <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                            Configuración
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="logout.php">
                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                            Cerrar sesión
                        </a>
                    </div>
                </li>
            </ul>
        </nav>

        <!-- Begin Page Content -->
        <div class="container-fluid">
            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Panel de Control</h1>
                <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                    <i class="fas fa-download fa-sm text-white-50"></i> Generar Reporte
                </a>
            </div>

            <!-- Content Row -->
            <div class="row">
                <!-- Productos Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stat-card primary h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Productos</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $productos ?></div>
                                </div>
                                <div class="col-auto">
                                    <div class="card-icon bg-primary">
                                        <i class="fas fa-box"></i>
                                    </div>
                                </div>
                            </div>
                            <a href="gestionar_productos.php" class="btn btn-sm btn-outline-primary mt-2">Administrar <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Usuarios Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stat-card success h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Usuarios</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $usuarios ?></div>
                                </div>
                                <div class="col-auto">
                                    <div class="card-icon bg-success">
                                        <i class="fas fa-users"></i>
                                    </div>
                                </div>
                            </div>
                            <a href="gestionar_usuarios.php" class="btn btn-sm btn-outline-success mt-2">Administrar <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Tiendas Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stat-card warning h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Tiendas</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $tiendas ?></div>
                                </div>
                                <div class="col-auto">
                                    <div class="card-icon bg-warning">
                                        <i class="fas fa-store"></i>
                                    </div>
                                </div>
                            </div>
                            <a href="gestionar_tiendas.php" class="btn btn-sm btn-outline-warning mt-2">Administrar <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Ejemplo de cuarta tarjeta -->
              

            <!-- Content Row -->
            <div class="row">
                <!-- Gráfico de ejemplo -->
                <div class="col-xl-8 col-lg-7">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Resumen de actividad</h6>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="myAreaChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Últimas actividades -->
                <div class="col-xl-4 col-lg-5">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Últimas actividades</h6>
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-flush">
                                <a href="#" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Nuevo producto</h6>
                                        <small>Hace 5 minutos</small>
                                    </div>
                                    <p class="mb-1">Leche deslactosada agregada</p>
                                </a>
                                <a href="#" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Usuario registrado</h6>
                                        <small>Hace 1 hora</small>
                                    </div>
                                    <p class="mb-1">Nuevo usuario: juan.perez</p>
                                </a>
                                <a href="#" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Actualización de tienda</h6>
                                        <small>Ayer</small>
                                    </div>
                                    <p class="mb-1">Supermercado Los Andes actualizado</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Custom scripts -->
    
</body>
</html>