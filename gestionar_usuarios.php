<?php
session_start();
include 'config.php';

header( 'Content-Type: application/json; charset=utf-8' );

ini_set( 'display_errors', 1 );
error_reporting( E_ALL );

// Funciones

function get_all_users() {
    global $conn;
    $stmt = $conn->query( 'SELECT * FROM users' );
    return $stmt->fetchAll( PDO::FETCH_ASSOC );
}

function delete_user( $user_id ) {
    global $conn;
    $stmt = $conn->prepare( 'DELETE FROM users WHERE id = ?' );
    return $stmt->execute( [ $user_id ] );
}

function update_user( $user_id, $name, $email ) {
    global $conn;
    $stmt = $conn->prepare( 'UPDATE users SET name = ?, email = ? WHERE id = ?' );
    return $stmt->execute( [ $name, $email, $user_id ] );
}

function register_user( $name, $email, $password ) {
    global $conn;

    $stmt = $conn->prepare( 'SELECT id FROM users WHERE email = ?' );
    $stmt->execute( [ $email ] );

    if ( $stmt->rowCount() > 0 ) {
        return [ 'success' => false, 'message' => 'El correo ya está registrado.' ];
    }

    $hashed_password = password_hash( $password, PASSWORD_DEFAULT );
    $stmt = $conn->prepare( 'INSERT INTO users (name, email, password) VALUES (?, ?, ?)' );

    if ( $stmt->execute( [ $name, $email, $hashed_password ] ) ) {
        return [ 'success' => true, 'message' => 'Usuario registrado correctamente.' ];
    } else {
        return [ 'success' => false, 'message' => 'Error al registrar el usuario.' ];
    }
}

// Manejo de solicitudes
if ( $_SERVER[ 'REQUEST_METHOD' ] === 'POST' ) {
    // Registro
    if ( isset( $_POST[ 'register_user' ] ) ) {
        $name = $_POST[ 'name' ];
        $email = $_POST[ 'email' ];
        $password = $_POST[ 'password' ];

        $response = register_user( $name, $email, $password );
        echo json_encode( $response );
        exit();
    }

    // Eliminar
    if ( isset( $_POST[ 'delete_user_id' ] ) ) {
        if ( delete_user( $_POST[ 'delete_user_id' ] ) ) {
            echo json_encode( [ 'success' => true, 'message' => 'Usuario eliminado correctamente.' ] );
        } else {
            echo json_encode( [ 'success' => false, 'message' => 'Error al eliminar el usuario.' ] );
        }
        exit();
    }

    // Actualizar
    if ( isset( $_POST[ 'update_user_id' ] ) ) {
        $name = $_POST[ 'name' ];
        $email = $_POST[ 'email' ];
        $user_id = $_POST[ 'update_user_id' ];

        if ( update_user( $user_id, $name, $email ) ) {
            echo json_encode( [ 'success' => true, 'message' => 'Usuario actualizado correctamente.' ] );
        } else {
            echo json_encode( [ 'success' => false, 'message' => 'Error al actualizar el usuario.' ] );
        }
        exit();
    }
}

// Obtener usuarios
if ( $_SERVER[ 'REQUEST_METHOD' ] === 'GET' ) {
    $users = get_all_users();
    echo json_encode( [ 'users' => $users ] );
    exit();
}
?>
