<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorDossierPecuario.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	$identificador= $_SESSION['usuario'];
	$id_solicitud=intval($_POST['id_solicitud']);
	$datoProtocolo['id_solicitud'] = $id_solicitud;
	$datoProtocolo['nivel']=intval($_POST['nivel']);


	try {
		$conexion = new Conexion();
		$cp = new ControladorDossierPecuario();

		$res=$cp ->guardarSolicitud($conexion,$datoProtocolo);
		if($res['tipo']=="insert")
			$idProtocolo = $res['resultado'][0]['id_solicitud'];

		$mensaje['id'] = $idProtocolo;
		$mensaje['dato'] = $res['resultado'];
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'Esta fase ha sido actualizada.';


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