<?php
session_start();
include 'test/connection.php';

if (!isset($_SESSION['codUsuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codUsuario = $_POST['codUsuario'];
    $codParking = $_POST['codParking'];
    $sql = "INSERT INTO permisos (codUsuario, codParking) VALUES (?, ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ii", $codUsuario, $codParking);
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
