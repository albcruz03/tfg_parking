<?php
session_start();

require_once('connection.php');

if(!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}

 echo "<br>";
 $sql = "CREATE TABLE personas (
	id INT (6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 	nombre VARCHAR(30) NOT NULL,
 	apellidos VARCHAR(30) NOT NULL,
 	email VARCHAR (50),
 	reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
 	)";

 if (mysqli_query($conn, $sql)) {
 	echo "Tabla personas creada correctamente";
    
    header("Location: panel.php");
    exit();
 } else {
 	echo "Error creando la tabla: " . mysqli_error (Sconn);
 }

 mysqli_close($conn);


?>