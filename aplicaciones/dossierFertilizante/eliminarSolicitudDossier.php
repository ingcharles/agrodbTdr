<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorDossierPecuario.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{

	$id_solicitud = $_POST['id_solicitud'];
	$idUsuario= $_SESSION['usuario'];			//Es el usuario logeado en la solicitud

	try {
		$conexion = new Conexion();
		$cp=new ControladorDossierPecuario();
		$cp->eliminarSolicitud($conexion,$id_solicitud);

		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'Solicitud eliminada';

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