<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: index.html");
    die();
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="header">
        <p>¡Bienvenido!</p>
    </div>

    <div>
        <h3>CÓDIGO PARKING</h3>
    </div>
    <div class="PARKING">
        <form action="">
            <input type="text" id="code" name="code" value="_ _ _ _ _"><br>
          </form> 
          
    </div>

</body>
</html>