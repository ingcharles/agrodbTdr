<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVehiculos.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';
	
try{
	$identificadorUsuarioRegistro = htmlspecialchars ($_POST['identificadorUsuarioRegistro'],ENT_NOQUOTES,'UTF-8');
		
	$datos = array(	'taller' => htmlspecialchars ($_POST['taller'],ENT_NOQUOTES,'UTF-8'),
					'direccion' => htmlspecialchars ($_POST['direccion'],ENT_NOQUOTES,'UTF-8'),
					'contacto' =>  htmlspecialchars ($_POST['contacto'],ENT_NOQUOTES,'UTF-8'), 
					'telefono' =>  htmlspecialchars ($_POST['telefono'],ENT_NOQUOTES,'UTF-8'),
					'observaciones' => htmlspecialchars ($_POST['observaciones'],ENT_NOQUOTES,'UTF-8'));
	try {
		$conexion = new Conexion();
		$cv = new ControladorVehiculos();
		
		if ($identificadorUsuarioRegistro != ''){
			$cv -> guardarNuevoTaller($conexion, $datos['taller'], $datos['direccion'],$datos['contacto'],$datos['telefono'],$datos['observaciones'],$_SESSION['nombreLocalizacion'], $identificadorUsuarioRegistro);
			
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = 'Los datos han sido creados satisfactoriamente';
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