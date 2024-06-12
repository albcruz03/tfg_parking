<?php
session_start();
include 'test/connection.php';

if (!isset($_SESSION['codUsuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.html");
    exit();
}
//---------------------------------------
$sql_users = "SELECT codUsuario, nombre_usuario FROM usuarios";
$stmt_users = $conn->prepare($sql_users);
$stmt_users->execute();
$result_users = $stmt_users->get_result();
// ----------------------------------------
$sql_parkings = "SELECT codParking, ubicacion FROM parking";
$stmt_parkings = $conn->prepare($sql_parkings);
$stmt_parkings->execute();
$result_parkings = $stmt_parkings->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" >
    <title>Panel de Administrador</title>
    <link rel="icon" type="image/x-icon" href="/images/favicon.ico">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        @font-face {
            font-family: 'Bakso Sapi';
            src: url('BaksoSapi.otf');
        }

        body {
            background-color: #E6E8E6;
            color: #3F403F;
            font-family: 'Bakso Sapi', sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin: 0;
        }

        .container {
            margin-top: 10rem;
            text-align: center;
        }

        h1, h2 {
            margin-bottom: 1rem;
        }

        .card {
            background-color: #475841;
            color: #CED0CE;
            border: none;
            margin-bottom: 1rem;
            padding: 0.1rem;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .card-body {
            display: none;
        }

        .btn-primary {
            background-color: #3F403F;
            border: none;
            margin-top: 1rem;
        }


    </style>
</head>
<body>
    <div class="container">
        <h1>Panel de Administrador</h1>
        <div class="card">
            <div class="card-header" data-toggle="card">
                <h2>Agregar Usuario</h2>
            </div>
            <div class="card-body" id="addUser">
                <form action="agregar_usuario.php" method="post">
                <div class="form-group">
                        <label for="nuevo_nombre">Nombre:</label>
                        <input type="text" id="nuevo_nombre" name="nombre" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="nuevo_apellido">Apellidos:</label>
                        <input type="text" id="nuevo_apellido" name="apellidos" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="nuevo_usuario">Nombre de Usuario:</label>
                        <input type="text" id="nuevo_usuario" name="nombre_usuario" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="nuevo_email">Email:</label>
                        <input type="email" id="nuevo_email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="nuevo_password">Contraseña:</label>
                        <input type="password" id="nuevo_password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Agregar Usuario</button>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-header" data-toggle="card">
                <h2>Eliminar Usuario</h2>
            </div>
            <div class="card-body" id="deleteUser">
                <form action="eliminar_usuario.php" method="post">
                    <div class="form-group">
                        <label for="usuario_eliminar">Seleccionar Usuario:</label>
                        <select id="usuario_eliminar" name="codUsuario" class="form-control" required>
                            <?php 
                            $result_users->data_seek(0);
                            while ($user = $result_users->fetch_assoc()) {
                                echo '<option value="' . $user['codUsuario'] . '">' . htmlspecialchars($user['nombre_usuario']) . '</option>';
                            } ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Eliminar Usuario</button>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-header" data-toggle="card">
                <h2>Configurar Usuario</h2>
            </div>
            <div class="card-body" id="editUser">
                <form action="configurar_usuario.php" method="post">
                    <div class="form-group">
                        <label for="usuario_editar">Seleccionar Usuario:</label>
                        <select id="usuario_editar" name="codUsuario" class="form-control" required>
                            <?php 
                            $result_users->data_seek(0); // Reset the result pointer
                            while ($user = $result_users->fetch_assoc()) {
                                echo '<option value="' . $user['codUsuario'] . '">' . htmlspecialchars($user['nombre_usuario']) . '</option>';
                            } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="nuevo_nombre_usuario">Nuevo Nombre de Usuario:</label>
                        <input type="text" id="nuevo_nombre_usuario" name="nombre_usuario" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="nuevo_email">Nuevo Email:</label>
                        <input type="email" id="nuevo_email" name="email" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="nuevo_password">Nueva Contraseña:</label>
                        <input type="password" id="nuevo_password" name="password" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-header" data-toggle="card">
                <h2>Asignar Permisos</h2>
            </div>
            <div class="card-body" id="assignPermissions">
                <form action="asignar_permisos.php" method="post">
                    <div class="form-group">
                        <label for="usuario_permiso">Usuario:</label>
                        <select id="usuario_permiso" name="codUsuario" class="form-control" required>
                            <?php 
                            $result_users->data_seek(0); // Reset the result pointer
                            while ($user = $result_users->fetch_assoc()) {
                                echo '<option value="' . $user['codUsuario'] . '">' . htmlspecialchars($user['nombre_usuario']) . '</option>';
                            } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="parking_permiso">Parking:</label>
                        <select id="parking_permiso" name="codParking" class="form-control" required>
                            <?php 
                            $result_parkings->data_seek(0); // Reset the result pointer
                            while ($parking = $result_parkings->fetch_assoc()) {
                                echo '<option value="' . $parking['codParking'] . '">' . htmlspecialchars($parking['ubicacion']) . '</option>';
                            } ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Asignar Permiso</button>
                </form>
            </div>
        </div>
        <a href="cerrar_sesion.php" class="btn btn-primary">Cerrar sesión</a>
    </div>

    <script>
        document.querySelectorAll('.card-header').forEach(header => {
            header.addEventListener('click', function() {
                var cardBody = this.nextElementSibling;
                if (cardBody.style.display === "none" || cardBody.style.display === "") {
                    cardBody.style.display = "block";
                } else {
                    cardBody.style.display = "none";
                }
            });
        });
    </script>
</body>
</html>

<?php
$stmt_users->close();
$stmt_parkings->close();
$conn->close();
?>
