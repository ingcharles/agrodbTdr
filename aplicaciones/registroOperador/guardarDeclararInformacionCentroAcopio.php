<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorGestionAplicacionesPerfiles.php';
require_once '../../clases/ControladorAplicaciones.php';


$mensaje = array ();
$mensaje ['estado'] = 'error';
$mensaje ['mensaje'] = 'Ha ocurrido un error!';

try {

	$conexion = new Conexion();
	$cro = new ControladorRegistroOperador();
	
	$idArea = $_POST['idArea'];
	$idOperacion = $_POST['idOperacion'];
	
	$capacidadInstalada = $_POST['capacidadInstalada'];
	$codigoUnidadMedida = $_POST['unidadMedida'];
	$numeroTrabajadores = $_POST['numeroTrabajadores'];
	$idLaboratorio = $_POST['laboratorio'];
	$numeroProveedores = $_POST['numeroProveedores'];
	
	$perteneceMag = $_POST['perteneceMag'];
	$horaRecoleccionManiana = $_POST['horaRecoleccionManiana'];
	$horaRecoleccionTarde = $_POST['horaRecoleccionTarde'];

	try {
	
		$conexion->ejecutarConsulta("begin;");	
	
		$operacion = pg_fetch_assoc($cro->abrirOperacionXid($conexion, $idOperacion));
		$idOperadorTipoOperacion = $operacion['id_operador_tipo_operacion'];
		$idHistorialOperacion = $operacion['id_historial_operacion'];
		$idTipoOperacion = $operacion['id_tipo_operacion'];
		
		$idflujoOperacion = pg_fetch_assoc($cro->obtenerIdFlujoXOperacion($conexion, $idOperacion));
		$idFlujoActual = pg_fetch_assoc($cro->obtenerEstadoActualFlujoOperacion($conexion, $idflujoOperacion['id_flujo_operacion'], 'declararICentroAcopio'));
		$estado = pg_fetch_assoc($cro->obtenerEstadoFlujoOperacion($conexion, $idflujoOperacion['id_flujo_operacion'], $idFlujoActual['predecesor']));
		
		$cro->guardarInformacionCentroAcopio($conexion, $idArea, $idTipoOperacion, $capacidadInstalada, $codigoUnidadMedida, $numeroTrabajadores, $idLaboratorio, $numeroProveedores, $idOperadorTipoOperacion, $horaRecoleccionManiana, $horaRecoleccionTarde, $perteneceMag);
		
		//TODO: Preguntar Eddy
		if($operacion['modulo_provee'] == 'moduloExterno' && $estado['estado'] == 'cargarProducto'){
			$estado = pg_fetch_assoc($cro->obtenerEstadoFlujoOperacion($conexion, $idflujoOperacion['id_flujo_operacion'], $idFlujoActual['predecesor']+1));
		}
		
		$cro->actualizarEstadoAnteriorPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $idHistorialOperacion);
		
		//TODO:Preguntar Eddy
		
			switch ($estado['estado']){
				 
				case 'pago':
					$cro->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $idHistorialOperacion, $estado['estado']);
				break;
				case 'inspeccion':
					$cro->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $idHistorialOperacion, $estado['estado']);
				break;
				case 'cargarAdjunto':
					$cro->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $idHistorialOperacion, $estado['estado']);
				break;
				case 'cargarProducto':
					$cro->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $idHistorialOperacion, $estado['estado']);
				break;
				case 'documental':
					$cro->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $idHistorialOperacion, $estado['estado']);
				break;
				
			}
			
		$cro->actualizarEstadoTipoOperacionPorIndentificadorSitio($conexion, $idOperadorTipoOperacion, $estado['estado']);
			
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = "Los datos han sido guardado satisfactoriamente";
		
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