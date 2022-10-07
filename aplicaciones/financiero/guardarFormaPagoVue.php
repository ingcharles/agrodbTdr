<?php

session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCertificados.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{	
	$idPago = pg_escape_string($_POST['id_pago']);
	$formaPago = pg_escape_string($_POST['fpago']);
	$idBanco = pg_escape_string($_POST['banco']);
	$nombreBanco = pg_escape_string($_POST['nombreBanco']);
	$idCuentaBancaria = pg_escape_string($_POST['cuentaBancaria']);
	$numeroCuenta = pg_escape_string($_POST['numeroCuenta']);
	$numeroTransaccion = pg_escape_string($_POST['papeletaBanco']);
	$fechaDeposito = pg_escape_string($_POST['fecha_deposito']);
	$valorDeposito = pg_escape_string($_POST['valor_depositado']);
	$identificador = pg_escape_string($_POST['identificador']);
			
	try {
		$conexion = new Conexion();
		$cc = new ControladorCertificados();
				
		$conexion->ejecutarConsulta("begin;");
		
		$formaPago = $cc->listaFormasPago($conexion, $idPago);
		
		if(pg_num_rows($formaPago)== 0){
			$cc -> guardarPagoOrden($conexion, $idPago, $fechaDeposito, $idBanco, $nombreBanco, $numeroTransaccion, $valorDeposito,0,$idCuentaBancaria,$numeroCuenta);
			$cc->guardarHistorialFinanciero($conexion, $idPago, 'Ingreso de forma de pago.', $identificador);
		}else{
			$cc->actualizarFormaPago($conexion, $idPago, $fechaDeposito, $idBanco, $nombreBanco, $numeroTransaccion, $valorDeposito,0,$idCuentaBancaria,$numeroCuenta);
			$cc->guardarHistorialFinanciero($conexion, $idPago, 'Actualización de forma de pago.', $identificador);
		}		
			
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