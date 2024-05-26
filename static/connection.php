<?php
session_status();


$servername = $_SESSION["servername"];
$username = $_SESSION["username"];
$password = $_SESSION["password"];
$dbname = $_SESSION["db"];
// Crear conexion

try {
	$conn = mysqli_connect($servername, $username, $password, $dbname);

} catch (Exception $e) {
	die ('Ha habido algún error, con tu usuario o contraseña <br>
	<a href="cerrar_sesion.php">Volver al login</a>');
}
?>