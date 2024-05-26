<?php
session_start();

if(!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}

require_once('connection.php');


if(isset($_POST["id"]) && !empty($_POST["id"])){
    require_once 'connection.php';

    $sql = "DELETE FROM personas WHERE id = ?";
    if($stmt = mysqli_prepare($conn, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        $param_id = trim($_POST["id"]);
        if(mysqli_stmt_execute($stmt)){
            $sql2 = "ALTER TABLE personas AUTO_INCREMENT = 1";
            if (mysqli_query($conn, $sql2)){
                header("location: index.php");
                exit();
            }
        } else{
            echo "Oops! Algo ha ido mal. Vuelve a intentarlo de nuevo.";
        }
    }
    mysqli_stmt_close($stmt);
} else {
    if(empty(trim($_GET["id"]))){
        header("location: error.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ver Registros</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h1>Eliminar Registro</h1>
                    </div>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="alert alert-danger fade in">
                            <input type="hidden" name="id" value="<?php echo trim($_GET["id"]); ?>"/>
                            <p>¿Estas seguro?</p><br>
                            <p>
                                <input type="submit" value="Yes" class="btn btn-danger">
                                <a href="index.php" class="btn btn-default">No</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>