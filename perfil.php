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

<h2>Pagina no crucial para el correcto funcionamiento de la pagina</h2>
<h4>Actualmente en desarrollo</h4>

</body>
</html>
