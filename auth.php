<?php
require 'config.php';
header( 'Content-Type: application/json; charset=utf-8' );
header( 'Cache-Control: no-cache, must-revalidate' );
header( 'Expires: 0' );

ini_set( 'display_errors', 1 );
error_reporting( E_ALL );

if ( $_SERVER[ 'REQUEST_METHOD' ] === 'POST' ) {
    $action = $_POST[ 'action' ] ?? '';

    switch ( $action ) {
        case 'register':
        handle_register( $conn );
        break;
        case 'login':
        handle_login( $conn );
        break;
        default:
        send_response( 'error', 'Acción no válida.' );
    }
}

function handle_register( $conn ) {
    $name = trim( $_POST[ 'name' ] ?? '' );
    $email = trim( $_POST[ 'email' ] ?? '' );
    $password = $_POST[ 'password' ] ?? '';

    if ( !$name || !$email || !$password ) {
        send_response( 'error', 'Todos los campos son obligatorios.' );
    }

    if ( strlen( $name ) > 100 ) {
        send_response( 'error', 'El nombre no puede exceder los 100 caracteres.' );
    }
    if ( strlen( $email ) > 100 || !filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
        send_response( 'error', 'Correo inválido.' );
    }
    if ( strlen( $password ) < 8 ) {
        send_response( 'error', 'La contraseña debe tener al menos 8 caracteres.' );
    }

    $hashed_password = password_hash( $password, PASSWORD_BCRYPT );

    try {
        $stmt = $conn->prepare( 'INSERT INTO users (name, email, password) VALUES (:name, :email, :password)' );
        $stmt->bindParam( ':name', $name );
        $stmt->bindParam( ':email', $email );
        $stmt->bindParam( ':password', $hashed_password );
        $stmt->execute();
        send_response( 'success', 'Usuario registrado correctamente.' );
    } catch ( PDOException $e ) {
        if ( $e->getCode() === '23000' ) {
            send_response( 'error', 'El correo ya está en uso.' );
        }
        error_log( 'Error en registro: ' . $e->getMessage() );
        send_response( 'error', 'Error al registrar usuario en la base de datos.' );
    }
}

function handle_login( $conn ) {
    $email = trim( $_POST[ 'email' ] ?? '' );
    $password = $_POST[ 'password' ] ?? '';

    if ( !$email || !$password ) {
        send_response( 'error', 'Correo y contraseña son obligatorios.' );
    }

    try {
        $stmt = $conn->prepare( 'SELECT * FROM users WHERE email = :email' );
        $stmt->bindParam( ':email', $email );
        $stmt->execute();
        $user = $stmt->fetch( PDO::FETCH_ASSOC );

        if ( $user && password_verify( $password, $user[ 'password' ] ) ) {
            session_start();
            $_SESSION[ 'user_id' ] = $user[ 'id' ];

            send_response( 'success', 'Inicio de sesión exitoso. Bienvenido, ' . $user[ 'name' ] . '.' );
        } else {
            send_response( 'error', 'Credenciales incorrectas.' );
        }

    } catch ( Exception $e ) {
        error_log( 'Error en login: ' . $e->getMessage() );
        send_response( 'error', 'Error al intentar iniciar sesión.' );
    }
}

function send_response( $status, $message, $data = [] ) {
    echo json_encode( array_merge( [ 'status' => $status, 'message' => $message ], $data ) );
    exit();
}
?>
