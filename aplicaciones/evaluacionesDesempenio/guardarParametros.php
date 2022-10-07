<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEvaluacionesDesempenio.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
		$datos = array(
				'nombreParametro' =>  htmlspecialchars ($_POST['nombreParametro'],ENT_NOQUOTES,'UTF-8'),
				'anio' => htmlspecialchars ($_POST['anio'],ENT_NOQUOTES,'UTF-8'),
				'periodo' => htmlspecialchars ( $_POST['periodo'],ENT_NOQUOTES,'UTF-8'),
				'semestre' => htmlspecialchars ( $_POST['semestre'],ENT_NOQUOTES,'UTF-8'),
				'numDias' => htmlspecialchars ( $_POST['numDias'],ENT_NOQUOTES,'UTF-8'),
				'fechaInicio' => htmlspecialchars ($_POST['fechaInicio'],ENT_NOQUOTES,'UTF-8'),
				'fechaFin' =>  htmlspecialchars ($_POST['fechaFin'],ENT_NOQUOTES,'UTF-8'),
				'evaluacionArea' => htmlspecialchars ($_POST['evaluacionArea'],ENT_NOQUOTES,'UTF-8'),
				'calculoResultado' => htmlspecialchars ($_POST['calculoResultado'],ENT_NOQUOTES,'UTF-8'),
				'envioNotificacion' => htmlspecialchars ($_POST['envioNotificacion'],ENT_NOQUOTES,'UTF-8'),
				'mesIni' => htmlspecialchars ($_POST['mesIni'],ENT_NOQUOTES,'UTF-8'),
				'mesFin' => htmlspecialchars ($_POST['mesFin'],ENT_NOQUOTES,'UTF-8'),
				'notificacion' => htmlspecialchars ($_POST['notificacion'],ENT_NOQUOTES,'UTF-8'));
	try {
		$conexion = new Conexion();
		$ced = new ControladorEvaluacionesDesempenio();

		$conexion->ejecutarConsulta("begin;");
		$qEvaluacion = $ced->guardarParametros($conexion, $_SESSION['usuario'], $datos['nombreParametro'], $datos['anio'],$datos['periodo'],$datos['semestre'],$datos['numDias'],$datos['fechaInicio'],$datos['fechaFin'],$datos['evaluacionArea'],$datos['calculoResultado'],$datos['envioNotificacion'],$datos['notificacion'],$datos['mesIni'],$datos['mesFin']);
		
		
		$conexion->ejecutarConsulta("commit;");
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'Parámetros guardados satisfactoriamente.';
		echo json_encode($mensaje);
	} catch (Exception $ex){
		$conexion->ejecutarConsulta("rollback;");
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = "Error al ejecutar sentencia";
		echo json_encode($mensaje);
	} finally {
	$conexion->desconectar();
	}
} catch (Exception $ex) {
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}
?>