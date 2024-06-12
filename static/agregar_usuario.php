<?php
session_start();
include 'test/connection.php';

if (!isset($_SESSION['codUsuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $nombre_usuario = $_POST['nombre_usuario'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $sql = "INSERT INTO usuarios (nombre, apellidos, nombre_usuario, email, hashed_password, rol) VALUES (?, ?, ?, ?, ?, 'user')";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sssss", $nombre, $apellidos, $nombre_usuario, $email, $password);
        if ($stmt->execute()) {
            header("Location: admin.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}

$conn->close();
?>
