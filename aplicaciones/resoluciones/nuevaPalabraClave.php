<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorResoluciones.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	
	$resolucion = htmlspecialchars ($_POST['resolucion'],ENT_NOQUOTES,'UTF-8');
	$palabraClave = htmlspecialchars ($_POST['palabraClave'],ENT_NOQUOTES,'UTF-8');

	
	try {
		$conexion = new Conexion();
		$cr = new ControladorResoluciones();
		
		$idPalabraClave = pg_fetch_row($cr->ingresarNuevaPalabraClave($conexion, $resolucion, $palabraClave));
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = $cr->imprimirLineaPalabrasClave($idPalabraClave[0], $palabraClave, $resolucion);
		$conexion->desconectar();
		echo json_encode($mensaje);
	} catch (Exception $ex){
		pg_close($conexion);
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = "Error al ejecutar sentencia";
		echo json_encode($mensaje);
	}
} catch (Exception $ex) {
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}
?>