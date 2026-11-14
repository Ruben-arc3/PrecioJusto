<?php
/** Controla el inicio de sesión y el registro de usuarios. */
final class AuthController
{
    public function __construct(private UserModel $users)
    {
    }

    public function handle(): array
    {
        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
            $email = trim($_POST['correo'] ?? '');
            $password = $_POST['contrasena'] ?? '';
            $user = $this->users->findByEmail($email);

            if ($user && password_verify($password, $user['contrasena'])) {
                session_regenerate_id(true);
                $_SESSION['usuario'] = [
                    'id' => (int)$user['id_usuario'],
                    'nombre' => $user['nombre'],
                    'correo' => $user['correo'],
                    'tipo' => $user['tipo'],
                ];

                $destination = $_SESSION['redirect_url'] ?? 'index.php';
                unset($_SESSION['redirect_url']);
                header('Location: ' . $destination);
                exit;
            }

            $error = 'Correo o contraseña incorrectos';
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
            $name = trim($_POST['nombre'] ?? '');
            $email = trim($_POST['correo'] ?? '');
            $password = $_POST['contrasena'] ?? '';
            $confirmation = $_POST['confirmar_contrasena'] ?? '';

            if ($name === '' || $email === '' || $password === '') {
                $error = 'Todos los campos son requeridos';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Escribe un correo válido';
            } elseif ($password !== $confirmation) {
                $error = 'Las contraseñas no coinciden';
            } elseif (strlen($password) < 8) {
                $error = 'La contraseña debe tener al menos 8 caracteres';
            } elseif ($this->users->findByEmail($email)) {
                $error = 'Este correo ya está registrado';
            } elseif ($this->users->create($name, $email, password_hash($password, PASSWORD_DEFAULT))) {
                $success = 'Registro exitoso. Ahora puedes iniciar sesión.';
            } else {
                $error = 'Error al registrar el usuario';
            }
        }

        return compact('error', 'success');
    }
}