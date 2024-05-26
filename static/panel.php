<?php
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}

require_once('connection.php');
?>

<!DOCTYPE HTML>
<html>

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">  
    <style type="text/css">
    
        .wrapper{
            width: 650px;
            margin: 0 auto;
        }
        .page-header h2{
            margin-top: 20px;
        }
        table tr td:last-child a{
            margin-right: 15px;
        }

        .action-buttons {
            margin-left: 20px;
            padding: 10px;
            text-align: right;
        }
        .action-buttons-1 {
            text-align: left;
            
        }
        .action-buttons-center{
            text-align: center;
        }

    </style>
    <script type="text/javascript">
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
</head>

                      <body>
                       <div class="wrapper">
                           <div class="container-fluid">
                               <div class="row">
                                   <div class="col-md-12">
                                       <div class="page-header clearfix">
                                           <h3 class="pull-left">Tabla Personas</h3>
                                           
                                           <?php
                                           $checkTableQuery = "SHOW TABLES LIKE 'personas'";
                                           $checkTableResult = mysqli_query($conn, $checkTableQuery);
                   
                                           if ($checkTableResult && mysqli_num_rows($checkTableResult) > 0) {
                                               // La tabla existe, mostrar el botón "Añadir Empleado"
                                               echo '<div class="action-buttons">';
                                               echo '<a href="create.php" class="btn btn-success">Añadir Empleado</a>';
                                               echo '</div>';
                                           } else {
                                               // La tabla no existe, mostrar el botón "Crear Tabla Personas"
                                               echo '<div class="text-center">';
                                               echo '<div class="action-buttons">';
                                               echo '<a href="create_table.php" class="btn btn-primary">Crear Tabla Personas</a>';
                                               echo '</div>';
                                           }
                                           ?>
                                       </div>
                   
                        <?php
                                $sql = "SELECT * FROM personas";
                                if ($result = mysqli_query($conn, $sql)) {
                                    if (mysqli_num_rows($result) > 0) {
                                        echo "<table class='table table-bordered table-striped'>";
                                        echo "<thead>";
                                        echo "<tr>";
                                        echo "<th>#</th>";
                                        echo "<th>Nombre</th>";
                                        echo "<th>Apellidos</th>";
                                        echo "<th>Email</th>";
                                        echo "<th>Action</th>";
                                        echo "</tr>";
                                        echo "</thead>";
                                        echo "<tbody>";
                                        while ($row = mysqli_fetch_array($result)) {
                                            echo "<tr>";
                                            echo "<td>" . $row['id'] . "</td>";
                                            echo "<td>" . $row['nombre'] . "</td>";
                                            echo "<td>" . $row['apellidos'] . "</td>";
                                            echo "<td>" . $row['email'] . "</td>";
                                            echo "<td>";
                                            echo "<a href='update.php?id=" . $row['id'] . "' title='Actualizar Registro' data-toggle='tooltip'><span class='glyphicon glyphicon-pencil'></span></a>";
                                            echo "<a href='delete.php?id=" . $row['id'] . "' title='Eliminar Registro' data-toggle='tooltip'><span class='glyphicon glyphicon-trash'></span></a>";
                                            echo "</td>";
                                            echo "</tr>";
                                        }
                                        echo "</tbody>";
                                        echo "</table>";
                                        // Free result set
                                        mysqli_free_result($result);
                                    } else {     
                                        echo '<div class="center-block">';                              
                                        echo "<p class='text-center'><em>No se encontraron registros</em></p>";
                                        echo '</div>';
                                    }
                                } else {
                                    echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
                                }
                            // Close connection
                            mysqli_close($conn);
                            ?>
                        </div>
                    </div>
                    <div class="action-buttons-1">
                    <a href="cerrar_sesion.php" class="btn btn-danger">Cerrar Sesión</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

