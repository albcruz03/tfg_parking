<?php
session_start();
include 'test/connection.php';

if (!isset($_SESSION['codUsuario'])) {
    header("Location: index.html");
    exit();
}

$codUsuario = $_SESSION['codUsuario'];
$rol = $_SESSION['rol']; 


if ($rol === 'admin') {
    header("Location: admin.php");
    exit();
}

$sql = "SELECT t3.nombre_usuario, t1.ubicacion, t1.total_plazas, t1.disponibles_plazas, t1.coordenadas
        FROM usuarios t3
        LEFT JOIN permisos t2 ON t3.codUsuario = t2.codUsuario
        LEFT JOIN parking t1 ON t2.codParking = t1.codParking
        WHERE t3.codUsuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $codUsuario);
$stmt->execute();
$result = $stmt->get_result();


$user_info = $result->fetch_assoc();
$nombre_usuario = $user_info['nombre_usuario'];
$result->data_seek(0); 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" >
    <title>Bienvenido</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="icon" type="image/x-icon" href="/images/favicon.ico">
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
            height: 100vh;
            margin: 0;
        }

        .container {
            text-align: center;
        }

        h1, h2 {
            margin-bottom: 1rem;
        }

        .card {
            color: white;
            border: none;
            margin-bottom: 1rem;
        }

        .card-header {
            cursor: pointer;
            padding: 1rem;
        }

        .card-body {
            display: none;
            padding: 1rem;
        }

        .btn-primary {
            background-color: #000000;
            border: none;
            margin-top: 1rem;
        }

        .btn-primary:hover {
            background-color: #333333;
        }

        .green {
            background-color: #379634;
        }

        .yellow {
            background-color: #F18F01;
            color: black;
        }

        .red {
            background-color: #DF2935;
        }

        .a {
            color:white;
        }
        .buttons{
            background-color:#E6E8E6; 
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Bienvenido, <?php echo htmlspecialchars($nombre_usuario); ?></h1>
        <h2>Tus parkings disponibles</h2>
        <div id="parking-list">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    if ($row['ubicacion']) {
                        $total_plazas = $row['total_plazas'];
                        $disponibles_plazas = $row['disponibles_plazas'];
                        $coordenadas = $row['coordenadas'];
                        $color_class = '';

                        if ($disponibles_plazas >= $total_plazas / 2) {
                            $color_class = 'green';
                        } elseif ($disponibles_plazas > 0 && $disponibles_plazas < $total_plazas / 2) {
                            $color_class = 'yellow';
                        } else {
                            $color_class = 'red';
                        }

                        echo '<div class="card ' . $color_class . '">
                                <div class="card-header" onclick="toggleCardBody(this)">
                                    ' . htmlspecialchars($row['ubicacion']) . '
                                </div>
                                <div class="card-body">
                                    <p>Total de plazas: ' . htmlspecialchars($total_plazas) . '</p>
                                    <p>Plazas disponibles: ' . htmlspecialchars($disponibles_plazas) . '</p>
                                    <p><a href="https://www.google.com/maps/search/?api=1&query='. htmlspecialchars($coordenadas) . '" style="color:white;" target="_blank">📍 Ir ahora 📍</a></p>
                              </div>
                              </div>';
                    }
                }
            } else {
                echo '<p>No tienes permisos para ningún parking. Por favor, contacta con el administrador.</p>';
            }
            ?>
        </div>

       
    </div>
    <br>
    <div class=buttons><a href="javascript:location.reload()" class="btn btn-primary">Actualizar</a>
    <a href="cerrar_sesion.php" class="btn btn-primary">Cerrar sesión</a>
</div>
    
    <script>
        function toggleCardBody(element) {
            var cardBody = element.nextElementSibling;
            if (cardBody.style.display === "none" || cardBody.style.display === "") {
                cardBody.style.display = "block";
            } else {
                cardBody.style.display = "none";
            }
        }
    </script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
