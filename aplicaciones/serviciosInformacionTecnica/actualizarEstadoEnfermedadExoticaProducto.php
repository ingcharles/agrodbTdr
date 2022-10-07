<?php
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorServiciosInformacionTecnica.php';

$mensaje = array ();
$mensaje ['estado'] = 'error';
$mensaje ['mensaje'] = 'Ha ocurrido un error!';
try {
	$conexion = new Conexion ();
	$csit = new ControladorServiciosInformacionTecnica();
	try {
		$idEnfermedadProducto = $_POST['idEnfermedadProducto'];
		$idEnfermedadExotica = $_POST['idEnfermedadExotica'];
		$estado=$_POST['estadoRequisito'];
		$usuarioResponsable=$_POST['usuarioResponsable'];
		$cambioEstado=$_POST['cambioEstado'];
		$conexion->ejecutarConsulta("begin;");
		if($cambioEstado=='si'){
			$csit->actualizarCambioEstadoEnfermedadExoticaSinProducto($conexion, $idEnfermedadExotica, $usuarioResponsable);
		}else{
			$csit->actualizarEstadoEnfermedadExoticaProductoSAA($conexion, $idEnfermedadProducto,$estado,$usuarioResponsable);
		}
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = $idEnfermedadProducto;
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