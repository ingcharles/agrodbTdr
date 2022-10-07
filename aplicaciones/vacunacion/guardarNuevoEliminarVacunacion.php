<?php
session_start ();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacunacion.php';

$mensaje = array ();
$mensaje ['estado'] = 'error';
$mensaje ['mensaje'] = 'Ha ocurrido un error!';

try {
	$conexion = new Conexion ();
	$va = new ControladorVacunacion ();
	
	$datos = array (
			
			'idVacunacion' => htmlspecialchars ( $_POST['idVacunacion'], ENT_NOQUOTES, 'UTF-8' ),
			'idEspecie' => htmlspecialchars ( $_POST['idEspecie'], ENT_NOQUOTES, 'UTF-8' ),
			'numeroCertificadoVacunacion' => htmlspecialchars ( $_POST['numeroCertificadoVacunacion'], ENT_NOQUOTES, 'UTF-8' ),
			'usuarioEliminacion' => htmlspecialchars ( $_SESSION['usuario'], ENT_NOQUOTES, 'UTF-8' )
	);
	try {
		$conexion->ejecutarConsulta("begin;");
		
		
		
		
		$qDetalleVacunacion=$va->abrirDetalleVacunacion($conexion, $datos['idVacunacion']);
		while($filaDetalleVacunaI=pg_fetch_assoc($qDetalleVacunacion)){
			$va->eliminarDetalleVacunacionConIdentificadores($conexion, $filaDetalleVacunaI['id_detalle_vacunacion']);		
		}
		
		$va->eliminarFiscalizacionVacunacion($conexion,$datos['idVacunacion']);
		$va->eliminarVacunacion($conexion, $datos['idVacunacion']);
		$va->actualizarEstadoCertificadoVacunacion($conexion, $datos['idEspecie'], $datos['numeroCertificadoVacunacion'], 'creado','Se eliminó y liberado para nueva vacunación',$datos['usuarioEliminacion']);
		
		$conexion->ejecutarConsulta("commit;");
		$mensaje ['estado'] = 'exito';
		$mensaje ['mensaje'] = 'Los datos han sido guardado satisfactoriamente.';
		
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
