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
	$id_solicitud_efecto=intval($_POST['id_solicitud_efecto']);


	try {
		$conexion = new Conexion();
		$cp = new ControladorDossierPecuario();

		$cp->eliminarEfectosNoDeseados($conexion, $id_solicitud_efecto);
		$res=$cp->obtenerEfectosNoDeseados($conexion,$id_solicitud);
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'El efecto no deseado ha sido eliminado.';
		$mensaje['datos'] = $res;


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