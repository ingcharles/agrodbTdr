<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCapacitacion.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{

	$conexion = new Conexion();
	$cc = new ControladorCapacitacion();

	try {

		$idRequerimiento=$_POST['idRequerimiento'];
		$archivoReplica = $_POST['archivo'];
		$identificador = $_POST['identificador'];

		$conexion->ejecutarConsulta("begin;");
		$resCapacitacion = $cc->obtenerRequerimientos($conexion,'','','',$idRequerimiento,'','','','');
		$capacitacion = pg_fetch_assoc($resCapacitacion);

		if($capacitacion['modo_replica'] == 'individual'){
			$cc->actualizarArchivoReplicaIndividual($conexion, $idRequerimiento, $archivoReplica, $identificador, '1', 'cargado');
		}else{
			$cc->actualizarArchivoReplicaGrupal($conexion, $idRequerimiento, $archivoReplica, '1', 'cargado');
		}

		$resultado = pg_fetch_assoc($cc->verificarCambioEstadoReplica($conexion, $idRequerimiento));

		if($resultado['total'] == $resultado['estado']){
			$cc->actualizarEstadoRequerimiento($conexion, $idRequerimiento,'20');
		}
			
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'Los datos han sido ingresados satisfactoriamente';

		$conexion->ejecutarConsulta("commit;");

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

