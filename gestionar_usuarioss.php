<?php
session_start();
if ( !isset( $_SESSION[ 'user_id' ] ) ) {
    header( 'Location: login.html' );
    exit();
}
?>
<!DOCTYPE html>
<html lang = 'es'>
<head>
<meta charset = 'UTF-8'>
<meta name = 'viewport' content = 'width=device-width, initial-scale=1.0'>
<title>Gestión de Usuarios</title>
<link rel = 'stylesheet' href = 'style.css'>
<script src = 'https://code.jquery.com/jquery-3.6.0.min.js'></script>
</head>
<body>
<nav>
<ul>
<li><a href = 'index.php'>Inicio</a></li>
<li><a href = 'gestionar_usuarios.php'>Gestión de Usuarios</a></li>
<li><a href = 'logout.php'>Cerrar sesión</a></li>
</ul>
</nav>
<h2>Gestión de Usuarios</h2>
<button id = 'openAddUserModalBtn' class = 'addBtn'>+</button>
<div id = 'addUserModal' style = 'display:none;'>
<h3>Registrar Nuevo Usuario</h3>
<form id = 'addUserForm'>
<label for = 'name'>Nombre:</label>
<input type = 'text' id = 'name' required><br>
<label for = 'email'>Correo:</label>
<input type = 'email' id = 'email' required><br>
<label for = 'password'>Contraseña:</label>
<input type = 'password' id = 'password' required><br>
<button type = 'button' id = 'addUserBtn'>Registrar</button>
<button type = 'button' onclick = "$('#addUserModal').hide();">Cancelar</button>
</form>
</div>
<h3>Usuarios Registrados</h3>
<table id = 'usersTable' border = '1'>
<thead>
<tr>
<th>ID</th>
<th>Nombre</th>
<th>Correo</th>
<th>Acciones</th>
</tr>
</thead>
<tbody>

</tbody>
</table>
<div id = 'editUserModal' style = 'display:none;'>
<h3>Editar Usuario</h3>
<form id = 'editUserForm'>
<input type = 'hidden' id = 'editUserId'>
Nombre: <input type = 'text' id = 'editName' required><br>
Correo: <input type = 'email' id = 'editEmail' required><br>
<button type = 'button' id = 'updateUserBtn'>Actualizar</button>
<button type = 'button' onclick = "$('#editUserModal').hide();">Cancelar</button>
</form>
</div>
<script src = 'gestionar_usuarios.js'></script>
</body>
</html>
