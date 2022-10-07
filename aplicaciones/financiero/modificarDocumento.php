<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorFinanciero.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{

	$idDocumento = ($_POST['idDocumento']);
	$nombreDocumento = htmlspecialchars ($_POST['nombreDocumento'],ENT_NOQUOTES,'UTF-8');
	
	
	try {
		$conexion = new Conexion();
		$cf = new ControladorFinanciero();

		$cf->actualizarDocumento($conexion, $idDocumento, $nombreDocumento);
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'Los datos fuerón actualizados';

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