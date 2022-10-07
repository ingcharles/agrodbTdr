<?php

session_start();

require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();

$mensaje = array();
$mensaje['estado'] = 'NO';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{	
	$nombreProducto = htmlspecialchars ($_POST['nombreProducto'],ENT_NOQUOTES,'UTF-8');
	$query=$cc->obtenerIdProducto ($conexion, $nombreProducto);
	$res=array();
	while ($fila = pg_fetch_assoc($query)){
		$res[] = $fila;
	}	
	if(count($res)>0)
	{
		$mensaje['mensaje'] = $res[0];	
		$mensaje['estado'] = 'OK';
	}	
}
catch(Exception $ex ){}

echo json_encode($mensaje);

?>