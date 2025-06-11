<?php
session_start();
require_once '../views/conexion.php'; 

$correo = $_POST['correo'];
$clave = $_POST['contrasena'];

$stmt = $conn->prepare("SELECT * FROM usuarios WHERE correo = ?");
$stmt->execute([$correo]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if ($usuario && password_verify($clave, $usuario['contrasena'])) {
    $_SESSION['usuario'] = [
        'id' => $usuario['id_usuario'],
        'nombre' => $usuario['nombre'],
        'tipo' => $usuario['tipo']
    ];

    // Redirige según el tipo de usuario
    if ($usuario['tipo'] === 'admin') {
        header('Location: admin.php');
    } else {
        header('Location: admin.php'); 
    }
    exit;
} else {
    $_SESSION['error'] = 'Credenciales incorrectas';
    header('Location: login.php');
    exit;
}
