<?php
session_start();
include 'test/connection.php';


if (!isset($_POST['nombre_usuario'], $_POST['password'])) {
    header("Location: index.html");
    exit();
}

if ($stmt = $conn->prepare('SELECT codUsuario, hashed_password, rol FROM usuarios WHERE nombre_usuario = ?')) {
    $stmt->bind_param('s', $_POST['nombre_usuario']);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($codUsuario, $hashed_password, $rol);
        $stmt->fetch();
        if (password_verify($_POST['password'], $hashed_password)) {
            session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['name'] = $_POST['nombre_usuario'];
            $_SESSION['codUsuario'] = $codUsuario;
            $_SESSION['rol'] = $rol; 
            header("Location: welcome.php");
            exit();
        } else {
            header("Location: index.html?error=1");
            exit();
        }
    } else {
        header("Location: index.html?error=1");
        exit();
    }

    $stmt->close();
} else {
    echo 'Falló la conexión con la BBDD.';
}
?>
