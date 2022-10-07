<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAccidentesIncidentes.php';
require_once '../../clases/ControladorVacaciones.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

$identificadorRegistro = $_SESSION['usuario'];

try {
	$conexion = new Conexion();
	$cv = new ControladorVacaciones();
	$cai = new ControladorAccidentesIndicentes();

	$conexion->ejecutarConsulta("begin;");
	$datos = array(
			'solicitud' =>  htmlspecialchars ($_POST['solicitud'],ENT_NOQUOTES,'UTF-8'),
			'identificadorAccidentado' => htmlspecialchars ($_POST['identificadorAccidentado'],ENT_NOQUOTES,'UTF-8'),
			'responPatron' => htmlspecialchars ($_POST['responPatron'],ENT_NOQUOTES,'UTF-8'),
			'resultado' => htmlspecialchars ( $_POST['resultado'],ENT_NOQUOTES,'UTF-8'),
			'observacion' => htmlspecialchars ( $_POST['observacion'],ENT_NOQUOTES,'UTF-8'));
	
	///----------------finalizar solicitud----------------------------------------------------------------
	
	if($datos['resultado'] == 'Aprobado')
		$cai->actualizarRegistroSso($conexion,$datos['solicitud'],'creado',$datos['observacion'],4);
	else 
		$cai->actualizarRegistroSso($conexion,$datos['solicitud'],$datos['resultado'],$datos['observacion'],3);

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