<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacunacionAnimal.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{	
	$datos = array('id_vacunador' => htmlspecialchars ($_POST['id_vacunador'],ENT_NOQUOTES,'UTF-8'),
				   'tipo_identificacion' =>  htmlspecialchars ($_POST['tipo_identificacion'],ENT_NOQUOTES,'UTF-8'),
				   'identificador' => htmlspecialchars ($_POST['identificador'],ENT_NOQUOTES,'UTF-8'),
				   'nombre' => htmlspecialchars ($_POST['nombre'],ENT_NOQUOTES,'UTF-8'),
				   'apellido' => htmlspecialchars ($_POST['apellido'],ENT_NOQUOTES,'UTF-8'),
				   'telefono' => htmlspecialchars ($_POST['telefono'],ENT_NOQUOTES,'UTF-8'),
				   'celular' => htmlspecialchars ($_POST['celular'],ENT_NOQUOTES,'UTF-8'),
				   'correo' => htmlspecialchars ($_POST['correo'],ENT_NOQUOTES,'UTF-8'),
				   'estado' => htmlspecialchars ($_POST['estado'],ENT_NOQUOTES,'UTF-8')	
			);

	
	try {
		$conexion = new Conexion();
		$vdr = new ControladorVacunacionAnimal();
		
		$vdr->actualizarDatosVacunador($conexion, $datos['id_vacunador'], $datos['tipo_identificacion'],  $datos['identificador'],  $datos['nombre'],  $datos['apellido'],  $datos['telefono'],  $datos['celular'],  $datos['correo'],  $datos['estado']);
		
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