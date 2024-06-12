<?php
session_start();
include 'test/connection.php';

if (!isset($_SESSION['codUsuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codUsuario = $_POST['codUsuario'];
    $nombre_usuario = $_POST['nombre_usuario'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "UPDATE usuarios SET nombre_usuario = ?, email = ?";
    $params = [$nombre_usuario, $email];
    $types = "ss";

    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql .= ", hashed_password = ?";
        $params[] = $hashed_password;
        $types .= "s";
    }

    $sql .= " WHERE codUsuario = ?";
    $params[] = $codUsuario;
    $types .= "i";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param($types, ...$params);
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
