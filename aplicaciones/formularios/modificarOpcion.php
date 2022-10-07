<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorFormularios.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{

	$idOpcion = htmlspecialchars ($_POST['idOpcion'],ENT_NOQUOTES,'UTF-8');
	$opcion = htmlspecialchars ($_POST['opcion'],ENT_NOQUOTES,'UTF-8');
	try {
		$conexion = new Conexion();
		$cf = new ControladorFormularios();

		$cf->actualizarOpcion($conexion, $idOpcion, $opcion);

		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'Los datos fueron actualizados';

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