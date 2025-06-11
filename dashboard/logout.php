<?php
session_start();
session_unset();
session_destroy();
// Redirigir a la anterior página a la que se accedió
// o a la página de inicio
if (isset($_SERVER['HTTP_REFERER'])) {
    header('Location: ' . $_SERVER['HTTP_REFERER']);
} else {
    header('Location: login.php');
}

//header('Location: index.php');
exit;
?>