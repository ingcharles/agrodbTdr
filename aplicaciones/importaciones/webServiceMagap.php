<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorImportaciones.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();

$licenciaMagap = htmlspecialchars ($_POST['licenciaMagap'],ENT_NOQUOTES,'UTF-8');

//Conexion con web service
$qProducto = $cc -> listarProductosLicencia($conexion, 'Si');

while ($fila = pg_fetch_assoc($qProducto)){
	$productosLicencia[] = array(idProducto=>$fila['id_producto'], nombreProducto => $fila['nombre_comun']);
}
?>