<?php
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}

require_once('connection.php');

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <style type="text/css">
        body {
            padding: 10px;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <h2 class="text-center">Creación de tablas</h2>

            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $nombre = $_POST["nombre"];
                $apellidos = $_POST["apellidos"];
                $email = $_POST["email"];


                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    
                    $stmt_check = mysqli_prepare($conn, "SELECT id FROM personas WHERE nombre=? AND apellidos=? AND email=?");
                    mysqli_stmt_bind_param($stmt_check, "sss", $nombre, $apellidos, $email);
                    mysqli_stmt_execute($stmt_check);
                    mysqli_stmt_store_result($stmt_check);

                    if (mysqli_stmt_num_rows($stmt_check) == 0) {
                        $stmt_insert = mysqli_prepare($conn, "INSERT INTO personas (nombre, apellidos, email) VALUES (?, ?, ?)");
                        mysqli_stmt_bind_param($stmt_insert, "sss", $nombre, $apellidos, $email);
                        $result = mysqli_stmt_execute($stmt_insert);

                        if ($result) {
                            echo "<div class='alert alert-success' role='alert'>Nueva columna creada</div>";
                        } else {
                            echo "<div class='alert alert-danger' role='alert'>Error al crear la columna</div>";
                        }

                        mysqli_stmt_close($stmt_insert);
                    } else {
                        echo "<div class='alert alert-warning' role='alert'>El nombre, apellidos y correo ya existen</div>";
                    }

                    mysqli_stmt_close($stmt_check);
                } else {
                    echo "<div class='alert alert-danger' role='alert'>Formato de correo inválido</div>";
                }
            }
            ?>

            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" class="form-control" name="nombre" required>
                </div>
                <div class="form-group">
                    <label for="apellidos">Apellidos:</label>
                    <input type="text" class="form-control" name="apellidos" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="text" class="form-control" name="email" required>
                </div>
                <button type="submit" class="btn btn-primary">Crear columna</button>
            </form>

            <a href="panel.php" class="btn btn-default">Volver al panel</a>
        </div>
    </div>
</div>

</body>
</html>
