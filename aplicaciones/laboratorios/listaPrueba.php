<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicaciones.php';

$conexion = new Conexion();
$ca = new ControladorAplicaciones('laboratorios');
$opcion = htmlspecialchars($_POST['opcion'], ENT_NOQUOTES, 'UTF-8');
$usuario = htmlspecialchars($_SESSION['usuario'], ENT_NOQUOTES, 'UTF-8');

echo '<header>
		<h1>Solicitudes</h1>';
	echo $ca->imprimirMenuDeAcciones($conexion, $opcion, $usuario);
echo '</header>';

