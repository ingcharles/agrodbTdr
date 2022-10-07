<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorTramitesInocuidad.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	$inspector = htmlspecialchars ($_POST['inspector'],ENT_NOQUOTES,'UTF-8');
	$idSolicitud = ($_POST['idSolicitud']);
	$estado = htmlspecialchars ($_POST['estado'],ENT_NOQUOTES,'UTF-8');
	
	try {
		$conexion = new Conexion();
		$cti = new ControladorTramitesInocuidad();
		
		$cti->actualizarEstadoTramite($conexion, $idSolicitud, $estado);
		$fechaDespacho = date('Y-m-d h:m:s');
		$cti-> guardarSeguimientoTramite($conexion, $idSolicitud, $inspector, $fechaDespacho, 'Tramite entregado al cliente.');
		
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'La operación se ha guardado satisfactoriamente';
		
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