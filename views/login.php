<?php
session_start();
require_once 'conexion.php';

$error = '';
$success = '';

// Procesar inicio de sesión
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $correo = trim($_POST['correo']);
    $contrasena = $_POST['contrasena'];

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE correo = ?");
    $stmt->execute([$correo]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($contrasena, $usuario['contrasena'])) {
        // Aquí aseguramos que el índice 'id' esté definido y corresponde a 'id_usuario' de la base
        $_SESSION['usuario'] = [
            'id' => $usuario['id_usuario'],  // clave 'id' para evitar warning Undefined index 'id'
            'nombre' => $usuario['nombre'],
            'correo' => $usuario['correo']
        ];
        // Redirigir a la página anterior o inicio
        if (isset($_SESSION['redirect_url'])) {
            $redirect_url = $_SESSION['redirect_url'];
            unset($_SESSION['redirect_url']);
            header('Location: ' . $redirect_url);
            exit;
        }
        // Si no hay redirect, ir a inicio o donde quieras
        header('Location: index.php');
        exit;
    } else {
        $error = 'Correo o contraseña incorrectos';
    }
}

// Procesar registro
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);
    $contrasena = $_POST['contrasena'];
    $confirmar_contrasena = $_POST['confirmar_contrasena'];

    // Validaciones
    if (empty($nombre) || empty($correo) || empty($contrasena)) {
        $error = 'Todos los campos son requeridos';
    } elseif ($contrasena !== $confirmar_contrasena) {
        $error = 'Las contraseñas no coinciden';
    } elseif (strlen($contrasena) < 8) {
        $error = 'La contraseña debe tener al menos 8 caracteres';
    } else {
        // Verificar si el correo ya existe
        $stmt = $conn->prepare("SELECT id_usuario FROM usuarios WHERE correo = ?");
        $stmt->execute([$correo]);
        
        if ($stmt->rowCount() > 0) {
            $error = 'Este correo ya está registrado';
        } else {
            // Hash de la contraseña
            $hash = password_hash($contrasena, PASSWORD_DEFAULT);
            
            // Insertar nuevo usuario
            $stmt = $conn->prepare("INSERT INTO usuarios (nombre, correo, contrasena) VALUES (?, ?, ?)");
            if ($stmt->execute([$nombre, $correo, $hash])) {
                $success = 'Registro exitoso. Ahora puedes iniciar sesión.';
            } else {
                $error = 'Error al registrar el usuario';
            }
        }
    }
}
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - PrecioJusto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <h2 class="text-success"><i class="fas fa-user-circle me-2"></i> Acceso a Cuenta</h2>
                            <p class="text-muted">Gestiona tus productos favoritos y comparaciones</p>
                        </div>

                        <?php if ($error): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>

                        <?php if ($success): ?>
                        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                        <?php endif; ?>

                        <!-- Pestañas Login/Registro -->
                        <ul class="nav nav-tabs nav-justified mb-4" id="authTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#login" type="button" role="tab">
                                    Iniciar Sesión
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="register-tab" data-bs-toggle="tab" data-bs-target="#register" type="button" role="tab">
                                    Crear Cuenta
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content" id="authTabsContent">
                            <!-- Formulario Login -->
                            <div class="tab-pane fade show active" id="login" role="tabpanel">
                                <form method="POST" action="">
                                    <div class="mb-3">
                                        <label for="login-correo" class="form-label">Correo Electrónico</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            <input type="email" class="form-control" id="login-correo" name="correo" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="login-contrasena" class="form-label">Contraseña</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                            <input type="password" class="form-control" id="login-contrasena" name="contrasena" required>
                                            <button class="btn btn-outline-secondary toggle-password" type="button">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="remember-me">
                                            <label class="form-check-label" for="remember-me">Recordarme</label>
                                        </div>
                                        <a href="#" class="small text-muted">¿Olvidaste tu contraseña?</a>
                                    </div>
                                    <button type="submit" name="login" class="btn btn-success w-100 py-2">
                                        <i class="fas fa-sign-in-alt me-2"></i> Iniciar Sesión
                                    </button>
                                </form>
                            </div>

                            <!-- Formulario Registro -->
                            <div class="tab-pane fade" id="register" role="tabpanel">
                                <form method="POST" action="">
                                    <div class="mb-3">
                                        <label for="register-nombre" class="form-label">Nombre Completo</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                            <input type="text" class="form-control" id="register-nombre" name="nombre" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="register-correo" class="form-label">Correo Electrónico</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            <input type="email" class="form-control" id="register-correo" name="correo" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="register-contrasena" class="form-label">Contraseña</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                            <input type="password" class="form-control" id="register-contrasena" name="contrasena" required>
                                            <button class="btn btn-outline-secondary toggle-password" type="button">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        <small class="text-muted">Mínimo 8 caracteres</small>
                                    </div>
                                    <div class="mb-3">
                                        <label for="register-confirmar" class="form-label">Confirmar Contraseña</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                            <input type="password" class="form-control" id="register-confirmar" name="confirmar_contrasena" required>
                                            <button class="btn btn-outline-secondary toggle-password" type="button">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="terms" required>
                                        <label class="form-check-label" for="terms">
                                            Acepto los <a href="#" class="text-success">Términos y Condiciones</a>
                                        </label>
                                    </div>
                                    <button type="submit" name="register" class="btn btn-success w-100 py-2">
                                        <i class="fas fa-user-plus me-2"></i> Registrarse
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Mostrar/ocultar contraseña
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const input = this.parentElement.querySelector('input');
                const icon = this.querySelector('i');
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.replace('fa-eye', 'fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.replace('fa-eye-slash', 'fa-eye');
                }
            });
        });

        // Cambiar a pestaña de registro si hay errores en el registro
        <?php if (isset($_POST['register']) && $error): ?>
        document.addEventListener('DOMContentLoaded', function() {
            const registerTab = new bootstrap.Tab(document.getElementById('register-tab'));
            registerTab.show();
        });
        <?php endif; ?>
    </script>
</body>
</html>