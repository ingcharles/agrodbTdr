<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastro.php';
require_once '../../clases/ControladorCapacitacion.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	$conexion = new Conexion();
	$cc = new ControladorCapacitacion();
	$catastro = new ControladorCatastro();
	try {
		
		$idRequerimiento=$_POST['idRequerimiento'];
		$conexion->ejecutarConsulta("begin;");
		$cc->bloqueoAsistentes($conexion,$idRequerimiento,'0');
		$cc->actualizarEstadoRequerimiento($conexion, $idRequerimiento, '18');
		$res=$cc->obtenerRequerimientosUsuario($conexion, '','','', '', '', '', '', $idRequerimiento);

		$cc->bloqueoAsistentes($conexion, $idRequerimiento, '0');

		while($fila = pg_fetch_assoc($res)){
			$catastro->crearDatosCapacitacion($conexion, $fila['funcionario'], $fila['nombre_evento'], $fila['empresa_capacitadora'], $fila['pais'], '', 'Ingresado', $fila['horas'], '', $fila['fecha_inicio'], $fila['fecha_fin']);
		}

		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'Los datos han sido ingresados satisfactoriamente';
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
