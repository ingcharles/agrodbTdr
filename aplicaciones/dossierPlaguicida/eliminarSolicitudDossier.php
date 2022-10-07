<?php
session_start();

require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEnsayoEficacia.php';
require_once '../../clases/ControladorDossierPlaguicida.php';

require_once '../ensayoEficacia/clases/Transaccion.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{

	$id_solicitud = $_POST['id_solicitud'];
	$idUsuario= $_SESSION['usuario'];			//Es el usuario logeado en la solicitud

	try {
		$conexion = new Transaccion();
		$ce=new ControladorEnsayoEficacia();
		$cg=new ControladorDossierPlaguicida();
		$dato=$cg->obtenerSolicitud($conexion,$id_solicitud);
		$conexion->Begin();
		//Libera el ensayo de eficacia reservado
		if(($dato['es_clon']=='f') && ($dato['protocolo']!=null)){
			$items=$ce->obtenerProtocoloDesdeExpediente($conexion,$dato['protocolo']);
			$datoProtocolo['id_protocolo']=$items['id_protocolo'];
			$datoProtocolo['estado_dossier']=null;
			$ce->guardarProtocolo($conexion,$datoProtocolo);
		}
		$cg->eliminarSolicitud($conexion,$id_solicitud);

		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'Solicitud eliminada';

		$conexion->Commit();
		$conexion->desconectar();
		echo json_encode($mensaje);
	} catch (Exception $ex){
		$conexion->Rollback();
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