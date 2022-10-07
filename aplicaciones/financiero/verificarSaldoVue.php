<?php

session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorFinancieroAutomatico.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{	
	$idFinancieroCabecera = $_POST['idFinancieroCabecera'];
	$identificador = $_POST['identificador'];
		
	try {
		$conexion = new Conexion();
		$cfa = new ControladorFinancieroAutomatico();
				
		$conexion->ejecutarConsulta("begin;");
		
		$cfa->actualizarEstadoFacturaPorIdentificadorFinancieroAutomatico($conexion, $idFinancieroCabecera, 'Por atender');
		$cfa->guardarHistorialFinancieroAutomatico($conexion, $idFinancieroCabecera, 'Se ha realizado la confirmación del pago de saldo anticipado.', $identificador);
		
		$conexion->ejecutarConsulta("commit;");
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = "Los datos han sido guardado satisfactoriamente";
		
		} catch (Exception $ex) {
		$conexion->ejecutarConsulta("rollback;");
		$mensaje['mensaje'] = $ex->getMessage();
		$mensaje['error'] = $conexion->mensajeError;
	} finally {
		$conexion->desconectar();
	}
} catch (Exception $ex) {
	$mensaje['mensaje'] = $ex->getMessage();
	$mensaje['error'] = $conexion->mensajeError;
} finally {
	echo json_encode($mensaje);
}
?>