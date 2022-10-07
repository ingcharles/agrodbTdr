<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorFinanciero.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	$datos = array( 'idClave' => htmlspecialchars ($_POST['idClaveContingencia'],ENT_NOQUOTES,'UTF-8'),
					'identificador' => htmlspecialchars ($_POST['identificadorUsuario'],ENT_NOQUOTES,'UTF-8'),
					'fechaDesde' =>  htmlspecialchars ($_POST['fechaDesde'],ENT_NOQUOTES,'UTF-8'),
					'horaDesde' => htmlspecialchars ($_POST['horaDesde'],ENT_NOQUOTES,'UTF-8'),
					'fechaHasta' => htmlspecialchars ($_POST['fechaHasta'],ENT_NOQUOTES,'UTF-8'),
					'horaHasta' => htmlspecialchars ($_POST['horaHasta'],ENT_NOQUOTES,'UTF-8'),
					'observacion' => htmlspecialchars ($_POST['observacion'],ENT_NOQUOTES,'UTF-8'));
	
	$fechaInicio = $datos['fechaDesde'].' '.$datos['horaDesde'];
	$fechaSalida =  $datos['fechaHasta'].' '.$datos['horaHasta'];

	
	try {
		$conexion = new Conexion();
		$cf = new ControladorFinanciero();
		
			$cf->actualizarClaveContingencia($conexion, $datos['idClave'], $fechaInicio, $fechaSalida, $datos['observacion'], $datos['identificador']);
			
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