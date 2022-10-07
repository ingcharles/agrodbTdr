<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorFinanciero.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';
	
try{
			
	$datos = array(	'identificador' => htmlspecialchars ($_POST['identificadorUsuario'],ENT_NOQUOTES,'UTF-8'),
					'fechaDesde' => htmlspecialchars ($_POST['fechaDesde'],ENT_NOQUOTES,'UTF-8'),
					'horaDesde' => htmlspecialchars ($_POST['horaDesde'],ENT_NOQUOTES,'UTF-8'),
					'fechaHasta' => htmlspecialchars ($_POST['fechaHasta'],ENT_NOQUOTES,'UTF-8'),
					'horaHasta' => htmlspecialchars ($_POST['horaHasta'],ENT_NOQUOTES,'UTF-8'),
					'observacion' =>  htmlspecialchars ($_POST['observacion'],ENT_NOQUOTES,'UTF-8'),
					'idActualClaveContingencia' =>  htmlspecialchars ($_POST['idActualClaveContingencia'],ENT_NOQUOTES,'UTF-8'));
	
	
	
	$fechaInicio = $datos['fechaDesde'].' '.$datos['horaDesde'];
	$fechaSalida =  $datos['fechaHasta'].' '.$datos['horaHasta'];
	
	try {
		$conexion = new Conexion();
		$cf = new ControladorFinanciero();
			
		$cf -> guardarNuevoClaveContingencia($conexion, $fechaInicio, $fechaSalida,$datos['observacion'], $datos['identificador']);
		
		if($datos['idActualClaveContingencia']!='')
			$cf -> actualizarEstadoAperturaContingencia($conexion, $datos['idActualClaveContingencia'], 'inactivo');
			
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'Los datos han sido creados satisfactoriamente';
		
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