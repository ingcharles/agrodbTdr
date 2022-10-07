<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorDossierPlaguicida.php';
require_once '../../clases/ControladorEnsayoEficacia.php';

require_once '../ensayoEficacia/clases/Transaccion.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';
try{
	$idUsuario= $_SESSION['usuario'];
	$id_solicitud = $_POST['id_solicitud'];

	$dato['id_solicitud'] = $id_solicitud;
	$dato['identificador'] = $idUsuario;
	$dato['normativa'] = htmlspecialchars ($_POST['normativa'],ENT_NOQUOTES,'UTF-8');
	$dato['motivo'] = htmlspecialchars ($_POST['motivo'],ENT_NOQUOTES,'UTF-8');

	$dato['clon_registro_madre'] = htmlspecialchars ($_POST['clon_registro_madre'],ENT_NOQUOTES,'UTF-8');
	$dato['protocolo'] = htmlspecialchars ($_POST['protocolo'],ENT_NOQUOTES,'UTF-8');
      $dato['id_categoria_toxicologica'] = intval ($_POST['id_categoria_toxicologica']);

	$dato['nivel']=intval($_POST['nivel']);

	try {
		$conexion = new Transaccion();
		$cg=new ControladorDossierPlaguicida();
		$ce = new ControladorEnsayoEficacia();
		$dato['es_clon'] = $ce->normalizarBoolean($_POST['es_clon']);
		//Reserva uso del ensayo de eficacia
		$conexion->Begin();
		if(($dato['es_clon']=='0') && ($dato['protocolo']!=null)){
			$items=$ce->obtenerProtocoloDesdeExpediente($conexion,$dato['protocolo']);
			$datoProtocolo['id_protocolo']=$items['id_protocolo'];
			$datoProtocolo['estado_dossier']='P';
			$ce->guardarProtocolo($conexion,$datoProtocolo);
		}
		$res=$cg->guardarSolicitud($conexion,$dato);
		if($res['tipo']=="insert")
			$idProtocolo = $res['resultado'][0]['id_solicitud'];
		else
			$fila=$res['resultado'];

		$mensaje['id'] = $idProtocolo;
		$mensaje['dato'] = $res['resultado'];
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'La solicitud ha sido actualizada';

		$conexion->Commit();
		$conexion->desconectar();

		echo json_encode($mensaje);

	}
	catch (Exception $ex){
		$conexion->Rollback();
		pg_close($conexion);
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = "Error al ejecutar sentencia";
		echo json_encode($mensaje);
	}
}
catch (Exception $ex) {
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}

?>

