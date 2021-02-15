<!DOCTYPE html>
<?php session_start();
if ($_SESSION['LoggedIn'] != true) { header('Location: login.php'); }?>

<html>
<head>

<title>BOOKER</title>

<link rel="stylesheet" href="estilo.css">

</head>
<body>

<header>
<img src="img/logo.png"><h1>BOOKER</h1>
<a class="saludo">Hola <?php echo $_SESSION["userName"]; ?></a>
<a class="mainMenu" href="mainMenu.php">back to menu</a>
</header>

<div id="misPrestamos">
<?php 

$servername = "localhost";
$username = "root";
$password = "";
$database = "biblioteca";

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Fallo al conectarse a la base de datos: " . mysqli_connect_error());
}

//Obetener el ID de usuario a partir del nombre del login
$logedInName = $_SESSION["userName"];
$consultaUserID =  "SELECT IDusuario id
                    FROM usuario 
                    WHERE nombreLogin = '$logedInName'";

$resultadoConsultaID = $conn->query($consultaUserID);

while ( $row = mysqli_fetch_array($resultadoConsultaID, MYSQLI_ASSOC) ){
    $userID = $row['id'];
}


//Consulta de los libros prestado al usuario
$consultaLibrosPrestado =  
                    "SELECT titulo ttl,IDCopia id,fechaPrestamo fecha
                    FROM libro
                    WHERE IDusuario = '$userID'
                    ORDER BY fechaPrestamo asc";

$resultadoLibrosPrestados = $conn->query($consultaLibrosPrestado);

$librosPrestadosNum = 0;
$librosPrestados = array();
while ( $row = mysqli_fetch_array($resultadoLibrosPrestados, MYSQLI_ASSOC) ){
    
    array_push($librosPrestados, $row);
    $librosPrestadosNum++;
    
}
    echo "<h3>Tienes actualmente ".$librosPrestadosNum." libros prestados en tu posesion</h3>";

    
    //Print libros que tiene el usuario
    if ( $librosPrestadosNum++ > 0 ) {
        echo "<table>
                <tr>    
                    <td>TITULO</td> <td>CODIGO DE LA COPIA</td> <td>FECHA DEL PRESTAMO</td>
                </tr>";
        foreach ($librosPrestados as $numLibro => $datosLibro) {
            echo "<tr>
            <td>".$datosLibro['ttl']."</td> <td>".$datosLibro['id']."</td> <td>".$datosLibro['fecha']."</td>
            </tr>";
        }
           echo  "<table>";
    }
?>
</div>
</body>
</html>