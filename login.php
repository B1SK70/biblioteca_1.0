<!DOCTYPE html>
<?php session_start(); ?>
<html>
<head>

<title>Inicia sesion</title>

<link rel="stylesheet" href="estilo.css">

</head>
<body>

<header><img src="img/logo.png"><h1>BOOKER</h1></header>

<div id="logIn">

<form method="POST" action="">

<h4>Incia sesión</h4>

<p>Usuario:</p>
<input type="text" name="usuario">


<p>Contraseña:</p>
<input type="password" name="contrasenya">

<br><br>
<input type="submit" name="login" value="Iniciar Sesion">


<?php 

if (isset($_POST['goToRegister']) ) {
    header('Location: register.php');
}

if (isset($_POST['login']) )  {
    
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "biblioteca";
    
    $conn = mysqli_connect($servername, $username, $password, $database);
    
    if (!$conn) {
        die("Fallo al conectarse a la base de datos: " . mysqli_connect_error());
    }
    
    $userUser = $_POST['usuario'];
    
    //Obtener contraseña del usuario introducido
    $query = "SELECT * FROM usuario WHERE nombreLogin = '$userUser'";
    $sql = $conn->query($query);
    $row = mysqli_fetch_array($sql,MYSQLI_ASSOC);
    
    
    
    //Comprobar hash de la contraseña
    if (password_verify( $_POST['contrasenya'], $row['contrasenyaLogin'] ) ) { 
        $_SESSION['LoggedIn'] = true;
        $_SESSION['userName'] = $_POST['usuario'];
        
        header('Location: mainMenu.php');
        
    } else {
        echo "<p>Aixo no va be!</p>";
    } 
}

?>

<!--  Redirect a register.php  -->
<br><br>
<h4>¿No tienes una cuenta?</h4>
<input type="submit" name="goToRegister" value="Crear cuenta">

</form>
</div>
</body>
</html>