<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorCatalogos.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	
	$idSolicitud = htmlspecialchars ($_POST['idSolicitud'],ENT_NOQUOTES,'UTF-8');
	$idParamatroLaboratorio = htmlspecialchars ($_POST['idParamatroLaboratorio']);
	
	try {
		$conexion = new Conexion();
		$cr = new ControladorRegistroOperador();
		$cc = new ControladorCatalogos();
				
		$operacion = pg_fetch_assoc($cr->abrirOperacionXid($conexion, $idSolicitud));
		$datosOperacionTipoOperador = pg_fetch_assoc($cr->obtenerOperadorTipoOperacionPorIdentificador($conexion, $operacion['id_operador_tipo_operacion']));
		
		$validacionEliminar = $cr->obtenerEstadoOperacionHistorico($conexion, $operacion['id_operador_tipo_operacion'], 'registrado');
		
		$qCodigoArea = $cc->obtenerCodigoTipoOperacion($conexion, $idSolicitud);
		$codigoArea = pg_fetch_assoc($qCodigoArea);
		$opcionArea = $codigoArea['codigo'];
		$idArea = $codigoArea['id_area'];
							
		switch ($idArea){
			
			case 'LT':
			
				switch ($opcionArea){
					
					case 'LDA':
					case 'LDE':
					case 'LDI':					
						$cr->eliminarProductoLaboratorio($conexion, $idSolicitud,$idParamatroLaboratorio);					
					break;
					
				}
				
			break;
			
		}
		
		if(pg_num_rows($validacionEliminar) == 0){
			if($datosOperacionTipoOperador['id_operacion'] == $idSolicitud){
				$cr->actualizarProductoOperacion($conexion, $idSolicitud, 'null', 'null');
			}else{
				$cr->eliminarDatosOperacion($conexion, $idSolicitud);
			}
		}else{
			$fechaActual = date('Y-m-d H-i-s');
			if($datosOperacionTipoOperador['id_operacion'] == $idSolicitud){
				$cr->actualizarProductoOperacion($conexion, $idSolicitud, 'null', 'null');
			}else{
				$cr->enviarOperacion($conexion, $idSolicitud, 'noHabilitado', 'Inactivación realizada por parte del usuario en eliminación de producto '. $fechaActual);
			}
		}
		
		

		//$cr->eliminarDatosHistoricoOperacion($conexion, $operacion['id_operador_tipo_operacion'], $operacion['id_historial_operacion']);
		//$cr->eliminarDatosTipoOperacionPorIndentificadorSitio($conexion, $operacion['id_operador_tipo_operacion']);
				
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = $idSolicitud;
		
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