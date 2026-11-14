<?php
session_start();
require_once __DIR__ . '/../models/connection.php';

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

require __DIR__ . '/../views/pages/perfil.php';
