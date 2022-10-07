<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{

	$idOperadorTipoOperacion = $_POST['idOperadorTipoOperacion'];
	$idHistorialOperacion = $_POST['idHistorialOperacion'];
	$idOperacion = $_POST['operacionInicial'];

	try{
		$conexion = new Conexion();
		$cr = new ControladorRegistroOperador();

		$conexion->ejecutarConsulta("begin;");

		if (isset($_POST['aceptarCondicion'])){
			$operacion = pg_fetch_assoc($cr->abrirOperacionXid($conexion, $idOperacion));
			$idflujoOperacion = pg_fetch_assoc($cr->obtenerIdFlujoXOperacion($conexion, $idOperacion));
			$idFlujoActual = pg_fetch_assoc($cr->obtenerEstadoActualFlujoOperacion($conexion, $idflujoOperacion['id_flujo_operacion'], 'declararIMercanciaPecuaria'));
			$estado = pg_fetch_assoc($cr->obtenerEstadoFlujoOperacion($conexion, $idflujoOperacion['id_flujo_operacion'], $idFlujoActual['predecesor']));

			if ($operacion['modulo_provee'] == 'moduloExterno' && $estado['estado'] == 'cargarProducto'){
				$estado = pg_fetch_assoc($cr->obtenerEstadoFlujoOperacion($conexion, $idflujoOperacion['id_flujo_operacion'], $idFlujoActual['predecesor'] + 1));
			}

			$cr->actualizarEstadoAnteriorPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $idHistorialOperacion);

			switch ($estado['estado']) {

				case 'pago':
					$cr->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $idHistorialOperacion, $estado['estado']);
				break;
				case 'documental':
					$cr->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $idHistorialOperacion, $estado['estado']);
				break;
				case 'inspeccion':
					$cr->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $idHistorialOperacion, $estado['estado']);
				break;
				case 'cargarAdjunto':
					$cr->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $idHistorialOperacion, $estado['estado']);
				break;
				case 'cargarProducto':
					$cr->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $idHistorialOperacion, $estado['estado']);
				break;
			}

			$cr->actualizarEstadoTipoOperacionPorIndentificadorSitio($conexion, $idOperadorTipoOperacion, $estado['estado']);
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = "Por favor aceptar la declaración de información.";
		}

		$conexion->ejecutarConsulta("commit;");

		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = "Los datos han sido guardado satisfactoriamente";
	}catch (Exception $ex){
		$conexion->ejecutarConsulta("rollback;");
		$mensaje['mensaje'] = $ex->getMessage();
		$mensaje['error'] = $conexion->mensajeError.$ex;
	}finally {
		$conexion->desconectar();
	}
}catch (Exception $ex){
	$mensaje['mensaje'] = $ex->getMessage();
	$mensaje['error'] = $conexion->mensajeError;
}finally {
	echo json_encode($mensaje);
}
?>