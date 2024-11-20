<?php
session_start();

// Destruir la sesión para cerrar el acceso
session_unset();
session_destroy();

// Redirigir al login después de cerrar sesión
header('Location: login.html');
exit();
?>
