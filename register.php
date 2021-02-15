<!DOCTYPE html>
<?php session_start(); 
if (isset($_POST['goToLogin']) ) {
    header('Location: login.php');
}
?>
<html>
<head>

<title>Registrate</title>

<link rel="stylesheet" href="estilo.css">

</head>
<body>

<header><img src="img/logo.png"><h1>BOOKER</h1></header>

<div id="logIn">

<form method="POST" action="">

<h4>Crear cuenta</h4>

<p>Usuario:</p>
<input type="text" name="usuario">


<p>Contraseña:</p>
<input type="password" name="contrasenyaA">

<p>Repetir contraseña:</p>
<input type="password" name="contrasenyaB">


<br><br>
<input type="submit" name="register" value="Crear cuenta"><br>

<?php 

if (isset($_POST['register']) )  {
    
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "biblioteca";
    
    $conn = mysqli_connect($servername, $username, $password, $database);
    
    if (!$conn) {
        die("Fallo al conectarse a la base de datos: " . mysqli_connect_error());
    }
    
    $registerEjecutable = true;
    
    //COMPROBAR DISPONIBILIDAD NOMBRE USUARIO///////////////////
    
    $userUser = $_POST['usuario'];
    
    $query = "SELECT * FROM usuario WHERE nombreLogin = '$userUser'";
    
    $sql = $conn->query($query);
    $row = mysqli_fetch_array($sql,MYSQLI_ASSOC);
    
    if ($row['nombreLogin'] == $userUser) {
        echo "Nombre de usuario en uso";
        $registerEjecutable = false;
        
    }
    
    
    //COMPROBAR SI LAS CONTRASEÑAS COINCIDEN///////////////////
    if (($_POST['contrasenyaA'] == $_POST['contrasenyaB']) && ($_POST['contrasenyaA'] != "")) {
        $userPasswd = $_POST['contrasenyaA'];
    } else {
        echo "Las contraseñas no coinciden";
        $registerEjecutable = false;
    }
        
    
    //Realizando el registro ////////////////////////////
    
    if ( $registerEjecutable ) {
        
    $hashUsrPasswd = password_hash($userPasswd,PASSWORD_DEFAULT);    
    
    $query = "INSERT INTO `usuario` (`nombreLogin`, `contrasenyaLogin`) VALUES ('$userUser', '$hashUsrPasswd')";
    $conn->query($query);
   echo "Registro completado";
 
    } 
}
?>

<br><br>
<h4>¿Ya tienes una cuenta?</h4>
<input type="submit" name="goToLogin" value="Iniciar Sesion">

</form>
</div>
</body>
</html>