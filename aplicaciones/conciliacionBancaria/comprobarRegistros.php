<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorConciliacionBancaria.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();
$cb = new ControladorConciliacionBancaria();


$nombreCampoCabeceraTrama = $_POST['nombreCampoCabeceraTrama'];
$posicionInicialCampoCabeceraTrama = $_POST['posicionInicialCampoCabeceraTrama'];
$posicionFinalCampoCabeceraTrama = $_POST['posicionFinalCampoCabeceraTrama'];

//$cb -> comprobarCamposCabeceraTrama($conexion, $nombreCampoCabeceraTrama, $posicionInicialCampoCabeceraTrama, $posicionFinalCampoCabeceraTrama);

//$cb -> comprobarBancos(); 
?>

