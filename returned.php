<?php
$r = $_REQUEST["r"];
echo $r;

$servername = "localhost";
$username = "root";
$password = "";
$database = "biblioteca";

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Fallo al conectarse a la base de datos: " . mysqli_connect_error());
}

$consultaUserID =  "UPDATE `libro` 
                    SET `fechaPrestamo` = NULL, `IDusuario` = NULL 
                    WHERE `libro`.`IDCopia` = '$r';";

$conn->query($consultaUserID);
header('Location: administrar.php');