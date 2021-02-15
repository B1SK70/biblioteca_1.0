<!DOCTYPE html>
<?php session_start(); 

if ($_SESSION['LoggedIn'] != true) {
    header('Location: login.php');
}?>

<html>
<head>

<title>BOOKER</title>

<link rel="stylesheet" href="estilo.css">

</head>
<body>

<header>
<img src="img/logo.png"><h1>BOOKER</h1>
<a class="saludo">Hola <?php echo $_SESSION["userName"]; ?></a>
</header>

<div id="mainMenu">

<div  onclick="location.href='catalogo.php'">				<h3>Catalogo</h3></div>
<div  onclick="location.href='perfil.php'" class="rght">	<h3>Perfil</h3></div>
<div  onclick="location.href='pedidos.php'">				<h3>Tus Pedidos</h3></div>
<div  onclick="location.href='logOut.php'" class="rght">	<h3>Salir</h3></div>

<?php 

$servername = "localhost";
$username = "root";
$password = "";
$database = "biblioteca";

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Fallo al conectarse a la base de datos: " . mysqli_connect_error());
}



//Comprobacion si usuario es tipo admin
$logedInName = $_SESSION["userName"];
$consultaUserID =  "SELECT codigoTipoUsuario cod
                    FROM usuario
                    WHERE nombreLogin = '$logedInName'";

$resultadoConsultaID = $conn->query($consultaUserID);

while ( $row = mysqli_fetch_array($resultadoConsultaID, MYSQLI_ASSOC) ){
    $userCod = $row['cod'];
}


//Print de la opcion adicional
if ($userCod == "1") {
    echo  "<div onclick=\"location.href='administrar.php'\">	<h3>Administrar</h3> </div>";
    
}
?>




</div>

</body>
</html>