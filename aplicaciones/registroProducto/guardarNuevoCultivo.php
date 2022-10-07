<?php
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorCatalogos.php';

	$mensaje = array();
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Ha ocurrido un error!';
	
try{
    $idArea = htmlspecialchars ($_POST['area'],ENT_NOQUOTES,'UTF-8');
	$nombreComun = htmlspecialchars ($_POST['nombreComun'],ENT_NOQUOTES,'UTF-8');
	$nombreCientifico = ($_POST['nombreCientifico']);
	
	try {
		$conexion = new Conexion();
		$cc = new ControladorCatalogos();
		
		$cc -> guardarNuevoCultivo($conexion, $idArea, $nombreComun, $nombreCientifico);
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'Los datos han sido creados satisfactoriamente';
	    		   
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