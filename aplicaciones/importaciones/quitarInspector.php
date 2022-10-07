<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorImportaciones.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	$idOperacion = $_POST['idOperacion'];
	$idInspector = $_POST['idInspector'];
	$tipoSolicitud= $_POST['tipoSolicitud'];
	$tipoInspector= $_POST['tipoInspector'];
	
	try {
		$conexion = new Conexion();
		$ci = new ControladorImportaciones();
		
		$ci->eliminarInspectorAsignado($conexion, $idOperacion, $idInspector, $tipoSolicitud, $tipoInspector);
		
		$res = pg_num_rows($ci->listarInspectoresAsignados($conexion, $idOperacion, $tipoSolicitud, $tipoInspector));
		
		if ($res==0){
			$res= $ci->enviarImportacion($conexion, $idOperacion, 'enviado');

			$mensaje['mensaje'] = 'Debe asignar la solicitud a un nuevo inspector.';
		}else{
			$mensaje['mensaje'] = 'El inspector ha sido eliminado satisfactoriamente.';
		}
		$mensaje['estado'] = 'exito';
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
