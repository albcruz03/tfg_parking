<?php
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}

require_once('connection.php');

if(isset($_GET['id'])) {
    $id = $_GET['id'];

    // Retrieve the record to be updated
    $query = "SELECT * FROM personas WHERE id = $id";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);

        // Handle form submission for updating the record
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $newNombre = $_POST["nombre"];
            $newApellidos = $_POST["apellidos"];
            $newEmail = $_POST["email"];

            $updateQuery = "UPDATE personas SET nombre='$newNombre', apellidos='$newApellidos', email='$newEmail' WHERE id=$id";

            if (mysqli_query($conn, $updateQuery)) {
                header("Location: panel.php");
                exit();
            } else {
                echo "Error updating record: " . mysqli_error($conn);
            }
        }
    } else {
        echo "No record found with ID: $id";
        exit();
    }
} else {
    echo "ID parameter is missing.";
    exit();
}
?>

<!DOCTYPE HTML>
<html>

<head>
    <meta charset="UTF-8">
    <title>Actualizar Registro</title>
    <head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        
        .wrapper {
            width: 650px;
            margin: 0 auto;
        }

        .page-header h2 {
            margin-top: 20px;
        }

        table tr td:last-child a {
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

        .action-buttons-center {
            text-align: center;
        }
    </style>



    <script type="text/javascript">
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
</head>

</head>

<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Actualizar registro</h2>
                    </div>

                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=$id"); ?>" method="post">
                        <div class="form-group">
                            <label>Nombre:</label>
                            <input type="text" name="nombre" class="form-control" value="<?php echo $row['nombre']; ?>">
                        </div>
                        <div class="form-group">
                            <label>Apellidos:</label>
                            <input type="text" name="apellidos" class="form-control" value="<?php echo $row['apellidos']; ?>">
                        </div>
                        <div class="form-group">
                            <label>Email:</label>
                            <input type="email" name="email" class="form-control" value="<?php echo $row['email']; ?>">
                        </div>
                        <input type="submit" class="btn btn-primary" value="Update">
                        <a href="panel.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
