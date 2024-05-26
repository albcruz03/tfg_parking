<?php
session_start();

$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'albert';
$DATABASE_PASS = '1234@';
$DATABASE_NAME = 'app';


$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
    
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}
    
if (!isset($_POST['username'], $_POST['password'])) {
    exit('Please fill both the username and password fields!');
}

if ($stmt = $con->prepare('SELECT id, password FROM accounts WHERE username = ?')) {
    $stmt->bind_param('s', $_POST['username']);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $password);
        $stmt->fetch();
      
        if (password_verify($_POST['password'], $password)) {
            session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['name'] = $_POST['username'];
            $_SESSION['id'] = $id;
            header("Location: welcome.php");
            die();
        } else {

            echo 'Incorrect username and/or password!';
        }
    } else {
        // Incorrect username
        echo 'Incorrect username and/or password!';
    }

    // Close the statement
    $stmt->close();
} else {
    // Failed to prepare the SQL statement
    echo 'Failed to prepare the SQL statement!';
}
?>
