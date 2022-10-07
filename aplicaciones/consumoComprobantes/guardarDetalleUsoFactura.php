<?php
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorFinanciero.php';

	$mensaje = array();
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	
	$idPago = ($_POST['idPago']);
	$identificador = htmlspecialchars ($_POST['identificador'],ENT_NOQUOTES,'UTF-8');
	$provincia = htmlspecialchars ($_POST['provincia'],ENT_NOQUOTES,'UTF-8');
	$idArea = htmlspecialchars ($_POST['idArea'],ENT_NOQUOTES,'UTF-8');
	$observacion = htmlspecialchars ($_POST['observacion'],ENT_NOQUOTES,'UTF-8');
		
	try {
		$conexion = new Conexion();
		$cf = new ControladorFinanciero();
		
		$cf->guardarUsoDetalleFactura($conexion,$idPago, $identificador, $provincia, $idArea, $observacion);
		$cf->actualizarEstadoUsoFactura($conexion, $idPago);			
		
					
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


