<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorImportaciones.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	//ids de importaciones
	$importacion = array_keys(array_count_values($_POST['id'])); 
	$idCoordinador = $_POST['idCoordinador'];
	$identificadorInspector = $_POST['inspector'];
	$tipoSolicitud = $_POST['tipoSolicitud'];
	$tipoInspector = $_POST['tipoInspector'];
	
	try {
		$conexion = new Conexion();
		$ci = new ControladorImportaciones();
		
		//Importaciones - Inspectores
		for ($i = 0; $i < count ($importacion); $i++) {
			$res= $ci->guardarNuevoInspector($conexion, $importacion[$i], $identificadorInspector, $idCoordinador, $tipoSolicitud, $tipoInspector);
			$res= $ci->enviarImportacion($conexion, $importacion[$i], 'asignado');
		}
		
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