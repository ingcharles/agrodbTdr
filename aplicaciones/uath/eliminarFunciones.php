<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha socurrido un error!';

try{

	$idPuesto = htmlspecialchars ($_POST['idPuesto'],ENT_NOQUOTES,'UTF-8');
	$idFuncion = htmlspecialchars ($_POST['idFuncion'],ENT_NOQUOTES,'UTF-8');

	try {
		$conexion = new Conexion();
		$cc = new ControladorCatalogos();
		
		$cc->quitarPuestosFunciones($conexion, $idPuesto, $idFuncion);

		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = $idPuesto.'-'.$idFuncion;

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
