<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAccidentesIncidentes.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

$identificadorRegistro = $_SESSION['usuario'];

try {
	$conexion = new Conexion();
	$cai = new ControladorAccidentesIndicentes();

	$conexion->ejecutarConsulta("begin;");
	$datos = array(
			'solicitud' =>  htmlspecialchars ($_POST['solicitud'],ENT_NOQUOTES,'UTF-8'),
			'identificadorAccidentado' => htmlspecialchars ($_POST['identificadorAccidentado'],ENT_NOQUOTES,'UTF-8'),
			'responPatron' => htmlspecialchars ($_POST['responPatron'],ENT_NOQUOTES,'UTF-8'),
			'resultado' => htmlspecialchars ( $_POST['resultado'],ENT_NOQUOTES,'UTF-8'));
	$cedulaPapeleta=$_POST['cedulaPapeleta'];
	$cedulaPapeletaRep=$_POST['cedulaPapeletaRep'];
	$infoReporte=$_POST['infoReporte'];
		
	$cai->actualizarDocumentosHabilitantes($conexion,$datos['solicitud'],$cedulaPapeleta,$cedulaPapeletaRep,$infoReporte);
	
	///----------------finalizar solicitud----------------------------------------------------------------
	$cai->actualizarRegistroSso($conexion,$datos['solicitud'],'Subsanado','',3);

	$conexion->ejecutarConsulta("commit;");
	$mensaje['estado'] = 'exito';
	$mensaje['mensaje'] = 'Los datos han sido actualizados satisfactoriamente';
	echo json_encode($mensaje);

} catch (Exception $e) {
	$conexion->ejecutarConsulta("rollback;");
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = "Error al ejecutar sentencia".$e;
	echo json_encode($mensaje);
} finally {
	$conexion->desconectar();
}

?>