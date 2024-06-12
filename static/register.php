<?php
session_start();
include 'test/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'] ?? '';
    $apellidos = $_POST['apellidos'] ?? '';
    $nombre_usuario = $_POST['nombre_usuario'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $errors = [];

    if (preg_match('/[^a-zA-Z\s]/', $nombre)) {
        $errors[] = "El nombre contiene caracteres inválidos.";
    }
    if (preg_match('/[^a-zA-Z\s]/', $apellidos)) {
        $errors[] = "Los apellidos contienen caracteres inválidos.";
    }

    if (strlen($nombre_usuario) < 4) {
        $errors[] = "El nombre de usuario debe tener al menos 4 caracteres.";
    }

    if (strlen($password) < 8) {
        $errors[] = "La contraseña debe tener al menos 8 caracteres.";
    }
    if (!preg_match('/[A-Z]/', $password)) {
        $errors[] = "La contraseña debe tener al menos una letra mayúscula.";
    }
    if (!preg_match('/[\W]/', $password)) {
        $errors[] = "La contraseña debe tener al menos un símbolo especial.";
    }

    if (empty($nombre) || empty($apellidos) || empty($nombre_usuario) || empty($email) || empty($password)) {
        $errors[] = "Todos los campos son obligatorios.";
    }

    if (!empty($errors)) {
        $errorString = implode(", ", $errors);
        header("Location: index.html?errors=" . urlencode($errorString));
        exit();
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $check_user_sql = "SELECT * FROM usuarios WHERE nombre_usuario = ? OR email = ?";
        $stmt = $conn->prepare($check_user_sql);
        $stmt->bind_param("ss", $nombre_usuario, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            header("Location: index.html?errors=" . urlencode("El nombre de usuario o el correo electrónico ya están en uso."));
        } else {
            $sql = "INSERT INTO usuarios (nombre, apellidos, nombre_usuario, email, hashed_password, rol) VALUES (?, ?, ?, ?, ?, 'user')";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $nombre, $apellidos, $nombre_usuario, $email, $hashed_password);

            if ($stmt->execute()) {
                header("Location: index.html?success=1");
            } else {
                header("Location: index.html?errors=" . urlencode("Error: " . $conn->error));
            }
        }

        $stmt->close();
    }
    $conn->close();
}
?>
