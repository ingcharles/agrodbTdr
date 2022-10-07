<?php
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorCatalogos.php';
	require_once '../../clases/ControladorFinanciero.php';

	$mensaje = array();
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	
	$idServicioProducto = $_POST['idServicioProducto'];
				
	try {
		$conexion = new Conexion();
		$cf = new ControladorFinanciero();
		
		$cf->eliminarServicioProductoPorIdentificadorServicio($conexion, $idServicioProducto);
			
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = $idServicioProducto;
			
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


