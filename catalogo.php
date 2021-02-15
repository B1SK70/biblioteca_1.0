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
var q;
function mostrarSugerencias(str){
	if(str.length == 0){ 	
		q = str;
        document.getElementById('buscando').innerHTML = '';
    } else {
	
		q = str;
	
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function(){
        
            if(this.readyState == 4 && this.status == 200){
                document.getElementById('sugerenciasBusqueda').innerHTML = this.response; 
                
            }
        }
        xmlhttp.open("GET", "sugerenciasBusqueda.php?q="+q, true);
        xmlhttp.send();
}
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



<div id="buscador">

<!--  Buscador ---------------  -->
<form action="" method="POST">
<p>¿Buscas algun libro en concreto?</p>

<input type="text" name="libroBuscado" oninput="mostrarSugerencias(this.value)" id="buscando">
<input type="submit" name="submit" value="Buscar"><br><br>

<a>¿Estas pensando en? </a><a id="sugerenciasBusqueda"></a>
</form>
<!-- --------------------------  -->

</div>

<?php 

$servername = "localhost";
$username = "root";
$password = "";
$database = "biblioteca";

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Fallo al conectarse a la base de datos: " . mysqli_connect_error());
}

//Consulta base en cas de no haber pulsate el botó
$consulta = "SELECT titulo,
            (select nombreAutor from autor where autor.IDAutor = libro.IDAutor),
            (select nombreEditorial from editorial where editorial.IDEditorial = libro.IDEditorial ),
            fechaSalida,
            IDusuario
                FROM libro
            ORDER BY titulo, IDusuario asc";

//En cas de haber utilitzat el buscador
if (isset($_POST['submit'])){
if($_POST['libroBuscado'] != "" ) {
    
    $nomLibro = "%".$_POST['libroBuscado']."%";
    
    $consulta = "SELECT titulo,
            (select nombreAutor from autor where autor.IDAutor = libro.IDAutor),
            (select nombreEditorial from editorial where editorial.IDEditorial = libro.IDEditorial ),
            fechaSalida,
            IDusuario
                FROM libro
            WHERE titulo LIKE '$nomLibro'
            ORDER BY titulo, IDusuario asc";
    
} else {
    $consulta = "SELECT titulo,
            (select nombreAutor from autor where autor.IDAutor = libro.IDAutor),
            (select nombreEditorial from editorial where editorial.IDEditorial = libro.IDEditorial ),
            fechaSalida,
            IDusuario
                FROM libro
            ORDER BY titulo, IDusuario asc";
} 
}

//Consulta del generes de cada llibre
$consultaGeneros = "SELECT titulo, GROUP_CONCAT(nombreGenero) generos
            from libro l 
            JOIN generoslibro gl on gl.IDCopia = l.IDCopia
            JOIN genero g on g.IDGenero = gl.IDGenero
            GROUP BY l.titulo;";


//RESULTADO QUERY LIBROS
$resultado = $conn->query($consulta);


//RESULTADO QUERY GENEROS
$consultaGeneros = $conn->query($consultaGeneros); 
$tablaGeneros = array();
while ( $row = mysqli_fetch_array($consultaGeneros, MYSQLI_ASSOC) ){
    $tablaGeneros[] = $row;
}


$skipPrint = false;
$libro_diferente = 0;
$last_title = "unset";
$disponibilidad = array(
        array( "Titulo","Copias","Disponibles")
);


//TABLA INFORMACION LIBROS
//
$genPrinted = false;
echo "<table id='tabla_libros'><tr> <td>TITULO</td> <td>AUTOR</td> <td>EDITORIAL</td> <td>FECHA SALIDA</td> <td>GENEROS</td> </tr>";
while($fila = $resultado->fetch_array()) {
    $skipPrint = false;

//Primera ejecucion 
    if ($fila[0] != $last_title) {
        $libro_diferente++;
        $disponibilidad[$libro_diferente][0] = $fila[0];
        $disponibilidad[$libro_diferente][1] = 1;
        $disponibilidad[$libro_diferente][2] = 0;
        
//Si ya existe registro anterior del libro
    } else {   
        $disponibilidad[$libro_diferente][1] = $disponibilidad[$libro_diferente][1] +1;
        $skipPrint = true;
    }

//comprueba copia está libre
    if ($fila["IDusuario"] == NULL ) {
        
        $disponibilidad[$libro_diferente][2] = $disponibilidad[$libro_diferente][2] +1;
    }
       
//Actualizar titulo_anterior    
    $last_title = $fila[0];
    
    
//Skip print si ya existe registro previo en la tabla
    if ($skipPrint) {
        continue;
    }
    
    
    echo "<tr>";
    echo "  <td>".$fila[0]."</td>
            <td>".$fila[1]."</td>
            <td>".$fila[2]."</td>
            <td>".$fila[3]."</td>";
    
    foreach ($tablaGeneros as $valor => $data ) {
        
        if ($data['titulo'] == $fila[0]) {
            echo "<td>".$data['generos']."</td></tr>";
            
        }
    }
}    
    echo "</table>  <table id='tabla_copias'  >";

    
    
    //TABLA DSPONIBILIDAD LIBROS
    //
    foreach ($disponibilidad as $titulo => $datos ) {
    echo "<tr>
            <td>".$datos[1]."</td><td>".$datos[2]."</td></tr>";
}
    echo "</table>";
?>
</div>
</body>
</html>