<?php
$q = $_REQUEST["q"];

$nomLibo = "%".$q."%";

$servername = "localhost";
$username = "root";
$password = "";
$database = "biblioteca";

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Fallo al conectarse a la base de datos: " . mysqli_connect_error());
}

$consulta = "SELECT titulo
                FROM libro
            WHERE titulo LIKE '$nomLibo'
            GROUP BY titulo";

$resultado = $conn->query($consulta);

while($fila = $resultado->fetch_array()) { 
    echo $fila["titulo"]." - ";
}