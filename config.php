<?php
// Configuración para PDO
$servername = 'localhost';

$username = 'root';

$password = '';

$dbname = 'ourense';

try {
    // Crear la conexión
    $conn = new PDO( "mysql:host=$servername;dbname=$dbname", $username, $password );

    $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
} catch ( PDOException $e ) {

    die( 'Conexión fallida: ' . $e->getMessage() );
}

?>
