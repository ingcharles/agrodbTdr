<?php
session_start ();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorSeguridadOcupacional.php';

$mensaje = array ();
$mensaje ['estado'] = 'error';
$mensaje ['mensaje'] = 'Ha ocurrido un error!';

try {
	$conexion = new Conexion ();
	$so = new ControladorSeguridadOcupacional ();

	$opcion = htmlspecialchars ( $_POST['opcion'], ENT_NOQUOTES, 'UTF-8' );
	$idGuiaMaterialPeligroso = htmlspecialchars ( $_POST ['idGuiaMaterialPeligroso'], ENT_NOQUOTES, 'UTF-8' );
	$nombreGuiaMaterialPeligroso = htmlspecialchars ( $_POST ['nombreGuiaUno'], ENT_NOQUOTES, 'UTF-8' );
	$numeroGuiaMaterialPeligroso = htmlspecialchars ( $_POST['numeroGuiaUno'], ENT_NOQUOTES, 'UTF-8' );
	$rutaGuiaMaterialPeligroso = htmlspecialchars ( $_POST['archivo'], ENT_NOQUOTES, 'UTF-8' );
		
	try {
		$conexion->ejecutarConsulta("begin;");
		
		if($opcion=='Nuevo'){
			$so->guardarGuiaMaterialPeligroso($conexion, $numeroGuiaMaterialPeligroso, mb_strtoupper($nombreGuiaMaterialPeligroso), $rutaGuiaMaterialPeligroso);
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = 'Los datos han sido ingresados satisfactoriamente';
		}
		
		if($opcion=='Actualizar'){
			$so->actualizarGuiaMaterialPeligroso($conexion, $idGuiaMaterialPeligroso, $numeroGuiaMaterialPeligroso, mb_strtoupper($nombreGuiaMaterialPeligroso), $rutaGuiaMaterialPeligroso);
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = 'Los datos han sido actualizados satisfactoriamente';
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
?>