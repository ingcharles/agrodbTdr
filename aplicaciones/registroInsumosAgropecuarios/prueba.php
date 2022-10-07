<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRIA.php';
require_once '../../clases/ControladorCatalogos.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{

	//DECLARACIÓN DE VARIABLES, FUNCIONES DE MANEJO DEL NEGOCIO.
	
	try {

		$conexion->ejecutarConsulta("begin;");

		//SENTENCIAS DE CRUD A BASE DE DATOS

		$conexion->ejecutarConsulta("commit;");

		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = "producto guardado";
		$mensaje['idComposicion'] = $idComposicion;
		$mensaje['idProducto'] = $idProducto[0];
		$mensaje['secuencia'] = $idProducto[1];
		$mensaje['ingredientes'] = $ingredientes;

		$usos = $cr->listarUsosPorComposicion($conexion, $idComposicion);
		$mensaje['usos'] = $usos['array_to_json'];

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