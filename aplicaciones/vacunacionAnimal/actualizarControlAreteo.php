<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacunacionAnimal.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
		
	$datos = array('id_control_areteo' => htmlspecialchars ($_POST['id_control_areteo'],ENT_NOQUOTES,'UTF-8'),				   
				   'observacion' => htmlspecialchars ($_POST['observacion'],ENT_NOQUOTES,'UTF-8'),
				   'usuario_modificacion' => htmlspecialchars ($_POST['identificador'],ENT_NOQUOTES,'UTF-8'),
				   'estado' => htmlspecialchars ($_POST['estado'],ENT_NOQUOTES,'UTF-8')	
			);

	
	try {
		
		$conexion = new Conexion();
		$vdr = new ControladorVacunacionAnimal();

		$vdr-> actualizarControlAreteo($conexion, $datos['id_control_areteo'], $datos['observacion'], $datos['estado'], $datos['usuario_modificacion']);
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'Los datos han sido actualizados satisfactoriamente';
		
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