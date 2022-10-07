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
		$producto= htmlspecialchars ($_POST['cbProducto'],ENT_NOQUOTES,'UTF-8');
		$plantilla = $_POST['cbPlantilla'];
		$hoja= $_POST['cbTamanio'];
		$cantidad= $_POST['cbEtiquetaPorHoja'];
		$orientacion= $_POST['cbOrientacion'];
		$nombre= $_POST['txtNombreImpresion'];
		
		$conexion->ejecutarConsulta("begin;");
		
		$valor=pg_num_rows($cac->obtenerPlantillasXidProductoHoja($conexion, $producto, $hoja, $estado));
		
		if($valor==0){		
	
		$cac->guardarPlantilla($conexion,$producto,$plantilla,$hoja,$cantidad,$orientacion,$nombre);
		
		

		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = "Los datos han sido guardados satisfactoriamente.";
		
		} else{
		    $mensaje['estado'] = 'vacio';
		    $mensaje['mensaje'] = "Ya se encuentra registrada una etiqueta con el mismo tamaño de papel para el producto seleccionado.";
		}
		
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