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
			'identificador_vacunador' => htmlspecialchars ( $_POST ['identificacionVacunador'], ENT_NOQUOTES, 'UTF-8' ),
			'identificador_distribuidor' => htmlspecialchars ( $_POST ['identificacionDistribuidor'], ENT_NOQUOTES, 'UTF-8' ),
			'id_tipo_vacuna' => htmlspecialchars ( $_POST ['tipoVacuna'], ENT_NOQUOTES, 'UTF-8' ),
			'usuario_modificacion' => htmlspecialchars ( $_SESSION ['usuario'], ENT_NOQUOTES, 'UTF-8' ),
			'id_lote_vacuna' => htmlspecialchars ( $_POST ['loteVacuna'], ENT_NOQUOTES, 'UTF-8' ),
			'fecha_vacunacion' => htmlspecialchars ( $_POST ['fechaVacunacion'], ENT_NOQUOTES, 'UTF-8' ),
			'fecha_vencimiento' => htmlspecialchars ( $_POST ['fechaVencimiento'], ENT_NOQUOTES, 'UTF-8' ),
			'id_vacunacion' => htmlspecialchars ( $_POST ['idVacunacion'], ENT_NOQUOTES, 'UTF-8' )
	);
	
	try {
		$conexion->ejecutarConsulta("begin;");
		$va->actualizarVacunacion($conexion, $datos ['id_vacunacion'], $datos ['identificador_distribuidor'],$datos ['identificador_vacunador'], $datos ['id_tipo_vacuna'], $datos ['id_lote_vacuna'], $datos ['fecha_vacunacion'], $datos ['fecha_vencimiento'], $datos ['usuario_modificacion']);
		$conexion->ejecutarConsulta("commit;");
		$mensaje ['estado'] = 'exito';
		$mensaje ['mensaje'] = 'Los datos han sido actualizados satisfactoriamente';
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