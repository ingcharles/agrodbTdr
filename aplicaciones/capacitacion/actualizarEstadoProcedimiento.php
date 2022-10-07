<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCapacitacion.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{

	$idRequerimiento=$_POST['idRequerimiento'];
	$estadoReplica = $_POST['estadoReplicacion'];
	$identificadorReplicado = $_POST['identificadorReplicado'];
	$observacionReplica = $_POST['observacionReplica'];

	try {
		$conexion = new Conexion();
		$cc = new ControladorCapacitacion();
		$conexion->ejecutarConsulta("begin;");
		$cc->actualizarArchivoReplicaJefe($conexion, $idRequerimiento, $identificadorReplicado, $estadoReplica, $observacionReplica);

		if($estadoReplica == 'aprobado'){
			$cc->actualizarEstadoReplicacionProcedimiento($conexion, $identificadorReplicado, '2', $idRequerimiento);
				
			$resultado = pg_fetch_assoc($cc->verificarCambioEstadoReplica($conexion, $idRequerimiento));
				
			if($resultado['total'] == $resultado['estado']){
				$cc->actualizarEstadoRequerimiento($conexion, $idRequerimiento,'18');
				$cc->bloqueoAsistentes($conexion, $idRequerimiento, '0');
			}
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
