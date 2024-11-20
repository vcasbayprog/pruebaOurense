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
<title>Editor A4</title>
<link rel = 'stylesheet' href = 'style.css'>
<script src = 'https://cdn.jsdelivr.net/npm/konva@8.4.2/konva.min.js'></script>
<script src = 'https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js'></script>
<script src = 'https://code.jquery.com/jquery-3.6.0.min.js'></script>
</head>
<body>
<nav>
<ul>
<li><a href = 'index.php'>Inicio</a></li>
<li><a href = 'gestionar_usuarioss.php'>Gestión de Usuarios</a></li>
<li><a href = 'logout.php'>Cerrar sesión</a></li>
</ul>
</nav>
<main>
<h2>Editor en formato A4</h2>
<input type = 'text' id = 'textInput' placeholder = 'Escribe tu texto aquí' />
<button class = 'addBtn' onclick = 'addText()'>Añadir texto</button>
<button class = 'downloadBtn' onclick = 'downloadPDF()'>Descargar PDF</button>
<button class = 'downloadBtn' onclick = 'downloadJSON()'>Descargar JSON</button>
<div id = 'editorCanvas'></div>
</main>
<script src = 'editor.js'></script>
</body>
</html>
