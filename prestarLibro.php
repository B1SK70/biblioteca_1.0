<?php session_start();

if ($_SESSION['LoggedIn'] != true) {
    header('Location: login.php');
}

$p = $_REQUEST["p"];
?>

<html>
<head>

<title>BOOKER</title>
<link rel="stylesheet" href="estilo.css">
<script>
function mostrarSugerencias(urss) {

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function(){
        
            if(this.readyState == 4 && this.status == 200){
                document.getElementById('userSugests').innerHTML = this.response;
            }
        }
        xmlhttp.open("GET", "sugerenciasUsuario.php?u="+urss, true);
        xmlhttp.send();
}


</script>
</head>
<body>

<header>
<img src="img/logo.png"><h1>BOOKER</h1>
<a class="saludo">Hola <?php echo $_SESSION["userName"]; ?></a>
<a class="mainMenu" href="mainMenu.php">back to menu</a>
</header>

<?php 
$servername = "localhost";
$username = "root";
$password = "";
$database = "biblioteca";

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Fallo al conectarse a la base de datos: " . mysqli_connect_error());
}


//OBTENER EL LIBRO QUE ESTAMOS PRESTANDO
$consultaLibroPrestado =
                   "SELECT titulo ttl,IDCopia id
                    FROM libro
                    WHERE IDCopia = '$p'";

$resultadoLibrosPrestados = $conn->query($consultaLibroPrestado);
$row = mysqli_fetch_array($resultadoLibrosPrestados, MYSQLI_ASSOC);


//PRINT LIBRO QUE ESTAMOS PRESTANDO
    echo   "<table>
                <tr>
                    <td>TITULO</td> <td>CODIGO DE LA COPIA</td>
                </tr>
                <tr>
                    <td>".$row['ttl']."</td> <td>".$row['id']."
                </tr>
            <table>";
    
?>


<!--  INTRODUCIR USUARIO AL QUE ESTAMOS PRESTANDO  -->
<form method="POST" action="">
	Usuario: <input type="text" name="userNameToLoan" oninput="mostrarSugerencias(this.value)">
	<pre id="userSugests"></pre>

	<br>Fecha del prestamo 
	<?php   
	$date = new DateTime();
    echo $date->format('Y-m-d');
    $prestamoDate = $date->format('Y-m-d');
    ?>       

	<br><br><input type="submit" name="prestar" value="PRESTAR"> 	

</form>

<?php 
if (isset($_POST['prestar']) )  {
    
    //Obtener ID del user a partir del nombre de login
    $logedInName = $_POST["userNameToLoan"];
    $consultaUserID =  "SELECT IDusuario id
                    FROM usuario
                    WHERE nombreLogin = '$logedInName'";
    
    $resultadoConsultaID = $conn->query($consultaUserID);
    
    while ( $row = mysqli_fetch_array($resultadoConsultaID, MYSQLI_ASSOC) ){
        $userID = $row['id'];
    }
    
    //Consulta guardar libro como prestado
    $prestarLibro =  "UPDATE `libro`
                    SET `fechaPrestamo` = '$prestamoDate', `IDusuario` = '$userID'
                    WHERE `libro`.`IDCopia` = '$p';";
    
    $conn->query($prestarLibro);
    
    
    header('Location: administrar.php');
}
?>
</body>
</html>
