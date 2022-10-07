<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVehiculos.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!'.$_POST['boton'];


try{
	
	$datos = array('id_mantenimiento' => htmlspecialchars ($_POST['id_mantenimiento'],ENT_NOQUOTES,'UTF-8'),
			'motivo' => htmlspecialchars ($_POST['motivo'],ENT_NOQUOTES,'UTF-8'),
			'taller' => htmlspecialchars ($_POST['taller'],ENT_NOQUOTES,'UTF-8'),
			'conductor' => htmlspecialchars ($_POST['responsable'],ENT_NOQUOTES,'UTF-8'));
	
	try {
		$conexion = new Conexion();
		$cv = new ControladorVehiculos();
	
			$cv->actualizarDatosMantenimiento($conexion, $datos['id_mantenimiento'], $datos['motivo'], $datos['taller'], $datos['conductor']);
			
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = 'Los datos han sido actualizados satisfactoriamente.';

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