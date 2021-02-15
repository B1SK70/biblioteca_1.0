<?php
$u = $_REQUEST["u"];
$numUser = "'%".$u."%'";

$servername = "localhost";
$username = "root";
$password = "";
$database = "biblioteca";

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Fallo al conectarse a la base de datos: " . mysqli_connect_error());
}

$consulta = "SELECT nombreLogin
                FROM usuario
            WHERE nombreLogin LIKE ".$numUser;

$resultado = $conn->query($consulta);

while($fila = $resultado->fetch_array()) {
    echo $fila["nombreLogin"]."  ";
}