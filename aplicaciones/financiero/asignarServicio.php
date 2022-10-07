<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorFinanciero.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	
	$identificador = htmlspecialchars ($_POST['identificador'],ENT_NOQUOTES,'UTF-8');
	$tipoServicio = htmlspecialchars ($_POST['tipoServicio'],ENT_NOQUOTES,'UTF-8');
	$nombreServicio = htmlspecialchars ($_POST['nombreServicio'],ENT_NOQUOTES,'UTF-8');
	
	try {
		$conexion = new Conexion();
		$cf = new ControladorFinanciero();
		
		if(pg_num_rows($cf->buscarServicioAsignado($conexion, $identificador, $tipoServicio)) == 0){
			
			$idDescuentoCupo = pg_fetch_result($cf->guardarNuevoServicioAsignado($conexion, $identificador, $tipoServicio, 'activo'), 0, 'id_descuento_cupo');
			
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cf->imprimirLineaServicio($idDescuentoCupo, $tipoServicio, $nombreServicio, 'activo');
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = 'El servicio elegido ya ha sido asignado.';
		}

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