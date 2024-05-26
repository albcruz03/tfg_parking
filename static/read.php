<?php
session_start();

if(!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}

require_once('connection.php');

?>
<!DOCTYPE html>l
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create</title>
</head>
<body>

<h2>Ver tablas</h2>

<?php
 $sql = "SELECT * FROM personas";
 $result = mysqli_query($conn, $sql);

 if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_array($result)) {
        echo "id: " . $row[0]. " - Nombre: " . $row["nombre"]. " " . $row["apellidos"]. " email:" .$row["email"]. "<br>";
    }
 } else {
    echo "0 results";
 }

?>

<a href="panel.php">Volver al panel</a>




</body>
</html>