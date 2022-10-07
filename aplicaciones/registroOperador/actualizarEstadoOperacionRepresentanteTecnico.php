<?php

session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorRegistroOperador.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{

	$idAreaOperacion = $_POST['idAreaOperacion'];
	$idOperacion = $_POST['idOperacion'];
	$idOperadorTipoOperacion = $_POST['idOperadorTipoOperacion'];
	$idHistorialOperacion = $_POST['idHistorialOperacion'];

	try {
		$conexion = new Conexion();
		$cr = new ControladorRegistroOperador();
		$cc = new ControladorCatalogos();
		
		$conexion->ejecutarConsulta("begin;");
		
		$cr->actualizarEstadoRepresentanteTecnicoPorIdOperacionIdOperadorTipoOperacionHistorico($conexion, $idOperadorTipoOperacion, $idHistorialOperacion, $idOperacion, $idAreaOperacion);

		$operacion = pg_fetch_assoc($cr->abrirOperacionXid($conexion, $idOperacion));
		$idflujoOperacion = pg_fetch_assoc($cr->obtenerIdFlujoXOperacion($conexion, $idOperacion));
		$idFlujoActual = pg_fetch_assoc($cr->obtenerEstadoActualFlujoOperacion($conexion, $idflujoOperacion['id_flujo_operacion'], 'representanteTecnico'));
		$estado = pg_fetch_assoc($cr->obtenerEstadoFlujoOperacion($conexion, $idflujoOperacion['id_flujo_operacion'], $idFlujoActual['predecesor']));

		if($operacion['modulo_provee'] == 'moduloExterno' && $estado['estado'] == 'cargarProducto'){
			$estado = pg_fetch_assoc($cr->obtenerEstadoFlujoOperacion($conexion, $idflujoOperacion['id_flujo_operacion'], $idFlujoActual['predecesor']+1));
		}

		$cr->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $idHistorialOperacion, 'representanteTecnico');
		
		if($operacion['estado'] == 'subsanacionRepresentanteTecnico'){
		    switch ($estado['estado']){
		        case 'cargarAdjunto':
		            $estado['estado'] = 'subsanacion';
		        break;
		        case 'cargarProducto':
		            $estado['estado'] = 'subsanacionProducto';
		        break;
		        default:
		            $estado['estado'] = $operacion['estado_anterior'];
		    }
			
		}else{
			$cr->actualizarEstadoAnteriorPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $idHistorialOperacion);
		}

		switch ($estado['estado']){

			case 'pago':
				$cr->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $idHistorialOperacion, $estado['estado']);
			break;
			case 'documental':
				$cr->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $idHistorialOperacion, $estado['estado']);
			break;
			case 'subsanacion':
				$cr->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $idHistorialOperacion, $estado['estado'], $operacion['observacion']);
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
			case 'subsanacionProducto':
			    $cr->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $idHistorialOperacion, $estado['estado'], $operacion['observacion']);
			break;
		}

		$cr->actualizarEstadoTipoOperacionPorIndentificadorSitio($conexion, $idOperadorTipoOperacion, $estado['estado']);

		$conexion->ejecutarConsulta("commit;");

		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = "Los datos han sido guardado satisfactoriamente";

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