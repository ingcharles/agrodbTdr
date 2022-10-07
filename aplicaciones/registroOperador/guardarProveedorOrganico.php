<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
    
        $conexion = new Conexion();
        $cro = new ControladorRegistroOperador();
        
        $idOperacion = $_POST['idOperacion'];        
                     
	try {
		
		$conexion->ejecutarConsulta("begin;");		

		$operacion = pg_fetch_assoc($cro->abrirOperacionXid($conexion, $idOperacion));
		$idOperadorTipoOperacion = $operacion['id_operador_tipo_operacion'];
		
		$qHistorialOperacion = $cro->obtenerMaximoIdentificadorHistoricoOperacion($conexion, $idOperadorTipoOperacion);
		$historialOperacion = pg_fetch_assoc($qHistorialOperacion);

		$idflujoOperacion = pg_fetch_assoc($cro->obtenerIdFlujoXOperacion($conexion, $idOperacion));
		$idFlujoActual = pg_fetch_assoc($cro->obtenerEstadoActualFlujoOperacion($conexion, $idflujoOperacion['id_flujo_operacion'], 'declararProveedor'));
		$estado = pg_fetch_assoc($cro->obtenerEstadoFlujoOperacion($conexion, $idflujoOperacion['id_flujo_operacion'], $idFlujoActual['predecesor']));
		
	    $cro->actualizarEstadoAnteriorPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion']);
	    		    
	    switch ($estado['estado']){
	        
	        case 'cargarAdjunto':
	            $cro->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion'], $estado['estado']);
	        break;
	        case 'inspeccion':
	            $cro->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion'], $estado['estado']);
	        break;
	        case 'pago':
	            $cro->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion'], $estado['estado']);
	        break;
	        case 'cargarRendimiento':
	            $cro->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion'], $estado['estado']);
	        break;
	        case 'declararProveedor':
	            $cro->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion'], $estado['estado']);
	        break;
	        case 'cargarProducto':
	            $cro->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion'], $estado['estado']);
	       break;
	        case'registrado':
	            $fechaActual = date('Y-m-d H-i-s');
	            $cro->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion'], $estado['estado'], 'Solicitud aprobada '.$fechaActual);
	            $cro->actualizarFechaAprobacionOperaciones($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion']);
	            $cro->cambiarEstadoAreaOperacionPorPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion']);
	        break;
	    }

		$cro-> actualizarEstadoTipoOperacionPorIndentificadorSitio($conexion, $operacion['id_operador_tipo_operacion'], $estado['estado']);
				
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'Los proveedores ha sido registrados.';
        
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
