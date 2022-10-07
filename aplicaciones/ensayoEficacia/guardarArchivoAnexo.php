<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEnsayoEficacia.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	$identificador= $_SESSION['usuario'];
	$idProtocolo=intval($_POST['id_protocolo']);
	$archivo = htmlspecialchars ($_POST['rutaArchivo'],ENT_NOQUOTES,'UTF-8');
	
	$referencia = htmlspecialchars ($_POST['referencia'],ENT_NOQUOTES,'UTF-8');
	$fase = htmlspecialchars ($_POST['fase'],ENT_NOQUOTES,'UTF-8');
	$tipoArchivo=htmlspecialchars ($_POST['tipoArchivo'],ENT_NOQUOTES,'UTF-8');

	try {
		$conexion = new Conexion();
		$ce = new ControladorEnsayoEficacia();

		$ce->agregarArchivoAnexo($conexion, $idProtocolo,$archivo,$referencia,$fase,$identificador,$tipoArchivo);
		$mensaje['datos']=$ce->listarArchivosAnexos($conexion, $idProtocolo);
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'El documento ha sido cargado.';


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