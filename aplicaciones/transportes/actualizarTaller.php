<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVehiculos.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	$identificadorUsuarioRegistro = htmlspecialchars ($_POST['identificadorUsuarioRegistro'],ENT_NOQUOTES,'UTF-8');
	
	$datos = array('id_taller' => htmlspecialchars ($_POST['id_taller'],ENT_NOQUOTES,'UTF-8'),
					'taller' =>  htmlspecialchars ($_POST['nombre'],ENT_NOQUOTES,'UTF-8'),
					'direccion' => htmlspecialchars ($_POST['direccion'],ENT_NOQUOTES,'UTF-8'),
					'telefono' => htmlspecialchars ($_POST['telefono'],ENT_NOQUOTES,'UTF-8'),
					'contacto' => htmlspecialchars ($_POST['contacto'],ENT_NOQUOTES,'UTF-8'),
					'observaciones' => htmlspecialchars ($_POST['observaciones'],ENT_NOQUOTES,'UTF-8'));

	
	try {
		$conexion = new Conexion();
		$cv = new ControladorVehiculos();
		
		if ($identificadorUsuarioRegistro != ''){
			$cv->actualizarTaller($conexion, $datos['id_taller'], $datos['taller'], $datos['direccion'], $datos['telefono'], $datos['contacto'], $datos['observaciones'], $identificadorUsuarioRegistro);
			
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = 'Los datos han sido actualizados satisfactoriamente';
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = "Su sesión expiró, por favor ingrese nuevamente al sistema";
		}
			
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