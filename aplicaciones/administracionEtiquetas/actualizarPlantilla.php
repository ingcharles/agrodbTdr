<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorLotes.php';
$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	$conexion = new Conexion();
	$cac = new ControladorLotes();

	try {
		$producto= htmlspecialchars ($_POST['idProducto'],ENT_NOQUOTES,'UTF-8');
		$plantilla = $_POST['cbPlantilla'];
		$idPlantilla = $_POST['id'];
		$hoja= $_POST['cbTamanio'];
		$cantidad= $_POST['cbEtiquetaPorHoja'];
		$orientacion= $_POST['cbOrientacion'];
		$nombre= $_POST['txtNombreImpresion'];
		
		$conexion->ejecutarConsulta("begin;");	
	
		$cac->actualizarPlantilla($conexion,$idPlantilla,$plantilla,$hoja,$cantidad,$orientacion,$nombre);
		
		$cac->actualizarPlantillaProducto($conexion, $plantilla, $producto);
		
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