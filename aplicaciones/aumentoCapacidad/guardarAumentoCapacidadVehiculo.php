<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorCatalogos.php';


$mensaje = array ();
$mensaje ['estado'] = 'error';
$mensaje ['mensaje'] = 'Ha ocurrido un error!';

try {

	$conexion = new Conexion();
	$cro = new ControladorRegistroOperador();
	$cc = new ControladorCatalogos();

	$idArea = $_POST['idArea'];
	$idOperacion = $_POST['idOperacion'];
	$idOperadorTipoOperacion = $_POST['idOperadorTipoOperacion'];

	$capacidadInstalada = $_POST['capacidadInstalada'];
	$codigoUnidadMedida = $_POST['unidadMedida'];

	try {
	
		$conexion->ejecutarConsulta("begin;");
				
		$operacion = pg_fetch_assoc($cro->abrirOperacionXid($conexion, $idOperacion));
		$idOperadorTipoOperacion = $operacion['id_operador_tipo_operacion'];
		$idHistorialOperacion = $operacion['id_historial_operacion'];
		$idTipoOperacion = $operacion['id_tipo_operacion'];
		
		$verificarDatosVehiculo = $cro-> verificarInformacionVehiculo($conexion, $idArea, $idTipoOperacion, $capacidadInstalada, $codigoUnidadMedida, $idOperadorTipoOperacion);
	
		if(pg_num_rows($verificarDatosVehiculo) == 0){	
				
		    $cro->actualizarInformacionVehiculo($conexion, $idArea, $idTipoOperacion, $capacidadInstalada, $codigoUnidadMedida, $idOperadorTipoOperacion);

			$idflujoOperacion = pg_fetch_assoc($cro->obtenerIdFlujoXOperacion($conexion, $idOperacion));
			$idFlujoActual = pg_fetch_assoc($cro->obtenerEstadoActualFlujoOperacion($conexion, $idflujoOperacion['id_flujo_operacion'], 'declararDVehiculo'));
			$estado = pg_fetch_assoc($cro->obtenerEstadoFlujoOperacion($conexion, $idflujoOperacion['id_flujo_operacion'], $idFlujoActual['predecesor']));
			$estado = $estado['estado'];			
			
			$qHistorialOperacion = $cro->obtenerMaximoIdentificadorHistoricoOperacion($conexion, $idOperadorTipoOperacion);
			$historialOperacion = pg_fetch_assoc($qHistorialOperacion);
			
			$cro->actualizarEstadoTipoOperacionPorIndentificadorSitio($conexion, $idOperadorTipoOperacion, $estado);
			$cro->actualizarEstadoAnteriorPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $idHistorialOperacion);
			$cro->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $idHistorialOperacion, $estado);			
			
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = "Los datos han sido guardado satisfactoriamente";
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = "No se ha modificado la información";
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
	
