<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorProtocolos.php';
require_once '../../clases/ControladorAuditoria.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{

	$idProtocoloComercio = htmlspecialchars ($_POST['idProtocoloComercio'],ENT_NOQUOTES,'UTF-8');
	$declaracion = htmlspecialchars ($_POST['declaracion'],ENT_NOQUOTES,'UTF-8');
	$numeroResolucion = htmlspecialchars ($_POST['numeroResolucion'],ENT_NOQUOTES,'UTF-8');	
	$observacion = htmlspecialchars ($_POST['observacion'],ENT_NOQUOTES,'UTF-8');
	$archivo = htmlspecialchars ($_POST['archivo'],ENT_NOQUOTES,'UTF-8');
	$fecha = htmlspecialchars ($_POST['fecha'],ENT_NOQUOTES,'UTF-8');
	$identificadorModificacionProtocoloComercio = $_SESSION['usuario'];
	//$tipo_aplicacion = ($_SESSION['idAplicacion']);

	try {
		$conexion = new Conexion();
		$cp = new ControladorProtocolos();
		$ca = new ControladorAuditoria();

		$cp->actualizarProtocoloComercio($conexion, $idProtocoloComercio, $declaracion, $numeroResolucion, $observacion, $archivo, $fecha, $identificadorModificacionProtocoloComercio);

		/*AUDOTORIA*/
		/*	
		$qTransaccion = $ca -> buscarTransaccion($conexion, $idRequisitoComercio,  $_SESSION['idAplicacion']);
		$transaccion = pg_fetch_assoc($qTransaccion);
			
		if($transaccion['id_transaccion'] == ''){
			$qLog = $ca -> guardarLog($conexion,$tipo_aplicacion);
			$qTransaccion = $ca ->guardarTransaccion($conexion, $idRequisitoComercio, pg_fetch_result($qLog, 0, 'id_log'));
		}
			
		$ca ->guardarUpdate($conexion, pg_fetch_result($qTransaccion, 0, 'id_transaccion'),$_SESSION['usuario'],'El usuario <b>' . $_SESSION['datosUsuario'] . '</b> ha modificado el requisito de comercio con código '.$idRequisitoComercio);
		*/
		/*FIN AUDITORIA*/
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'Los datos fueron actualizados';

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