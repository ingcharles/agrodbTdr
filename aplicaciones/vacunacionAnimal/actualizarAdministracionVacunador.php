<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacunacionAnimal.php';

//print_r($_POST);

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
		
	$datos = array('id_administrador_vacunador' => htmlspecialchars ($_POST['id_administrador_vacunador'],ENT_NOQUOTES,'UTF-8'),				   
				   'estado' => htmlspecialchars ($_POST['estado'],ENT_NOQUOTES,'UTF-8')	
			);
	
	try {
		
		$conexion = new Conexion();
		$vdr = new ControladorVacunacionAnimal();

		$vdr-> actualizarDatosVacunadorVacunacion($conexion, $datos['id_administrador_vacunador'], $datos['estado']);
				
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