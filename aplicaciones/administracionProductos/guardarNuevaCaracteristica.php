<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAdministrarCaracteristicas.php';
$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	$conexion = new Conexion();
	$cac = new ControladorAdministrarCaracteristicas();

	try {
		
		$dtxtEtiqueta= $_POST['dtxtEtiqueta'];
		$dtxtFormulario = $_POST['dtxtFormulario'];
		$dtxtCatalogo = $_POST['dtxtCatalogo'];
		$dtxtTipoElemento= $_POST['dtxtTipoElemento'];
		$dtxtProducto= $_POST['dtxtProducto'];		
		
		$guardarDetalle="";
		for($i=0; $i<count($dtxtEtiqueta);$i++){
			$guardarDetalle.= "('".$dtxtEtiqueta[$i] ."','".$dtxtTipoElemento[$i] ."','".$dtxtFormulario[$i]."','".$dtxtCatalogo[$i]."','".$dtxtProducto[$i]."'),";
		}
		
		$trim = rtrim($guardarDetalle,",");
		
		$conexion->ejecutarConsulta("begin;");
	
		$idCatalogo=pg_fetch_row($cac->guardarCaracteristica($conexion,$trim));
		
		$conexion->ejecutarConsulta("commit;");

		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = "Los datos han sido guardados satisfactoriamente.";

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