<!DOCTYPE html>
<?php session_start(); 

if ($_SESSION['LoggedIn'] != true) {
    header('Location: login.php');

}?>

<html>
<head>

<title>BOOKER</title>

<link rel="stylesheet" href="estilo.css">

<script>
function librodevuelto(bttn) {

	let trAReturn = bttn.parentNode.parentNode;
	let nodeList = trAReturn.childNodes;
	
	let codToDelete = nodeList[2].innerHTML;
	
	console.log(nodeList[2].innerHTML);
	
	location.href = "returned.php?r="+codToDelete;
	
}

function prestarlibro(bttn) {
	
	let trAReturn = bttn.parentNode.parentNode;
	let nodeList = trAReturn.childNodes;
	
	let codToLoan = nodeList[2].innerHTML;
	
	location.href = "prestarLibro.php?p="+codToLoan;
}

</script>

</head>
<body>

<header>
<img src="img/logo.png"><h1>BOOKER</h1>
<a class="saludo">Hola <?php echo $_SESSION["userName"]; ?></a>
<a class="mainMenu" href="mainMenu.php">back to menu</a>
</header>

<div id="catalogo">

<?php 

$servername = "localhost";
$username = "root";
$password = "";
$database = "biblioteca";

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Fallo al conectarse a la base de datos: " . mysqli_connect_error());
}


//Conseguir todos los prestamos
$consultaLibrosPrestado =
"SELECT titulo ttl,IDCopia id,nombreLogin nomLog,fechaPrestamo fecha
                    FROM libro 
                    JOIN usuario ON usuario.IDusuario = libro.IDusuario 
                    WHERE fechaPrestamo IS NOT NULL
                    ORDER BY fechaPrestamo asc";

$resultadoLibrosPrestados = $conn->query($consultaLibrosPrestado);


$librosPrestadosNum = 0;
$librosPrestados = array();
while ( $row = mysqli_fetch_array($resultadoLibrosPrestados, MYSQLI_ASSOC) ){
    
    array_push($librosPrestados, $row);
    $librosPrestadosNum++;
    
}

//PRINT LIBROS
if ( $librosPrestadosNum++ > 0 ) {
    
    echo "<h2>Libros prestados</h2>

            <table>
                <tr>
                    <td>TITULO</td> <td>CODIGO DE LA COPIA</td> <td>USUARIO</td> <td>FECHA DEL PRESTAMO</td>
                </tr>";
    foreach ($librosPrestados as $numLibro => $datosLibro) {
        
        echo "<tr>
            <td>".$datosLibro['ttl']."</td><td>".$datosLibro['id']."</td><td>".$datosLibro['nomLog']."</td><td>".$datosLibro['fecha']."</td>
            <td style='border:0px solid black'><button onclick='librodevuelto(this)'>DEVUELTO</button></td>
            </tr>"; 
    }
    echo  "<table>";
}


//Conseguir todos los libros disponibles
$consultaLibrosDisponibles =
                       "SELECT titulo ttl,IDCopia id,fechaPrestamo fecha 
                        FROM libro 
                        WHERE fechaPrestamo IS NULL 
                        ORDER BY ttl";

$resultadoLibrosDisponibles = $conn->query($consultaLibrosDisponibles);


$librosDisponiblesNum = 0;
$librosdisponibles = array();

while ( $row = mysqli_fetch_array($resultadoLibrosDisponibles, MYSQLI_ASSOC) ){
    
    array_push($librosdisponibles, $row);
    $librosDisponiblesNum++;
    
}

//PRINT LIBROS DISPONIBLES
if ( $librosDisponiblesNum++ > 0 ) {
    
    echo "<h2>Libros disponibles</h2>
            
            <table>
                <tr>
                    <td>TITULO</td> <td>CODIGO DE LA COPIA</td> 
                </tr>";
    foreach ($librosdisponibles as $numLibro => $datosLibro) {
        
        echo "<tr>
            <td>".$datosLibro['ttl']."</td><td>".$datosLibro['id']."</td>
            <td style='border:0px solid black'><button onclick='prestarlibro(this)'>PRESTAR</button></td>
            </tr>";
        
    }
    echo  "<table>";
    
}
?>
</div>
</body>
</html>