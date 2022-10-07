<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAccidentesIncidentes.php';
require_once '../../clases/ControladorVacaciones.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try {
	$conexion = new Conexion();
	$cv = new ControladorVacaciones();
	$cai = new ControladorAccidentesIndicentes();
	$conexion->ejecutarConsulta("begin;");
	
	$datos = array(
			'resultado' =>  htmlspecialchars ($_POST['resultado'],ENT_NOQUOTES,'UTF-8'),
			'solicitud' => htmlspecialchars ($_POST['opcion'],ENT_NOQUOTES,'UTF-8'),
			'observacion' => htmlspecialchars ($_POST['observacion'],ENT_NOQUOTES,'UTF-8'),
			'tipo' => htmlspecialchars ($_POST['tipoSso'],ENT_NOQUOTES,'UTF-8'));
	
	if($datos['tipo']=='incidente'){
		if($datos['resultado']=='Aprobado')$prioridad=4;
		if($datos['resultado']=='Subsanar')$prioridad=1;
		if($datos['resultado']=='Rechazado')$prioridad=5;
	}
	if($datos['tipo']=='accidente'){
		if($datos['resultado']=='Aprobado'){$prioridad=2; $datos['resultado']='creado';}
		if($datos['resultado']=='Subsanar')$prioridad=1;
		if($datos['resultado']=='Rechazado')$prioridad=5;
	}
	
	$cai->actualizarRegistroSso($conexion,$datos['solicitud'],$datos['resultado'],$datos['observacion'],$prioridad);
	
	$conexion->ejecutarConsulta("commit;");
	$mensaje['estado'] = 'exito';
	$mensaje['mensaje'] = 'Los datos han sido actualizados satisfactoriamente';
	echo json_encode($mensaje);

} catch (Exception $e) {
	$conexion->ejecutarConsulta("rollback;");
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = "Error al ejecutar sentencia".$datos['tipo'];
	echo json_encode($mensaje);
} finally {
	$conexion->desconectar();
}

?>