<?php
session_start();
require_once __DIR__ . '/../models/connection.php';

$correo = trim($_POST['correo'] ?? '');
$contrasena = $_POST['contrasena'] ?? '';

if ($correo === '' || $contrasena === '') {
    $_SESSION['error'] = 'Escribe tu correo y contraseña.';
    header('Location: login.php');
    exit;
}

$stmt = $conn->prepare('SELECT id_usuario, nombre, correo, contrasena, tipo FROM usuarios WHERE correo = ? LIMIT 1');
$stmt->execute([$correo]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario || !password_verify($contrasena, $usuario['contrasena'])) {
    $_SESSION['error'] = 'Credenciales incorrectas.';
    header('Location: login.php');
    exit;
}

if ($usuario['tipo'] !== 'admin') {
    $_SESSION['error'] = 'Esta cuenta no tiene acceso al panel administrativo.';
    header('Location: login.php');
    exit;
}

session_regenerate_id(true);
$_SESSION['usuario'] = [
    'id' => (int)$usuario['id_usuario'],
    'nombre' => $usuario['nombre'],
    'correo' => $usuario['correo'],
    'tipo' => $usuario['tipo'],
];

header('Location: admin.php');
exit;
