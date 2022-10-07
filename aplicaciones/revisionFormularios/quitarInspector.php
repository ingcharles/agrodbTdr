<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorRevisionSolicitudesVUE.php';

//Controladores por solicitud
require_once '../../clases/ControladorClv.php';
require_once '../../clases/ControladorFitosanitario.php';
require_once '../../clases/ControladorImportaciones.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorTramitesInocuidad.php';
require_once '../../clases/ControladorDestinacionAduanera.php';
require_once '../../clases/ControladorZoosanitarioExportacion.php';
require_once '../../clases/ControladorImportacionesFertilizantes.php';
require_once '../../clases/ControladorMercanciasSinValorComercial.php';
require_once '../../clases/ControladorTransitoInternacional.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	$idSolicitud = $_POST['idSolicitud'];
	$idInspector = $_POST['idInspector'];
	$tipoSolicitud = $_POST['tipoSolicitud'];
	$procesoRevision = $_POST['procesoRevision'];
	$formularioGeneral = $_POST['formularioGeneral'];
	
	try {
		$conexion = new Conexion();
		$crs = new ControladorRevisionSolicitudesVUE();
		
		switch ($procesoRevision){		
			
			case 'Documental' :
				$estado = 'enviado';
			break;
			
			case 'Financiero' : 
				$estado = 'pago';
			break;
		
			case 'Técnico' :
				$estado = 'inspeccion';
				break;
		}
		
		$crs->eliminarInspectorAsignado($conexion, $idSolicitud, $idInspector, $formularioGeneral, $procesoRevision);
		
		$res = pg_num_rows($crs->listarInspectoresAsignados($conexion, $idSolicitud, $formularioGeneral, $procesoRevision));
			
			if ($res==0){
				
				//Cambio de estado
				switch ($tipoSolicitud){
				
					case 'Operadores' :
					case 'operadoresSV':
					case 'operadoresSA':
					case 'operadoresAGR':
					case 'operadoresFER':
					case 'operadoresPEC':
					case 'operadoresLT':
					case 'operadoresAI':
					case 'operadoresALM':
										
						$cr = new ControladorRegistroOperador();
						
						$estado = ($estado == 'enviado'? 'documental' : $estado);
				
						$areaTipoOperacion = pg_fetch_assoc($cr->buscarOperacionTipoOperacionXIdOperacion($conexion, $idSolicitud));
						$areaOperacion = $areaTipoOperacion['id_area'];
						$codigoOperacion = $areaTipoOperacion['codigo'];
						$idOperadorTipoOperacion = $areaTipoOperacion['id_operador_tipo_operacion'];
						
						$qHistorialOperacion = $cr->obtenerMaximoIdentificadorHistoricoOperacion($conexion, $idOperadorTipoOperacion);
						$historialOperacion = pg_fetch_assoc($qHistorialOperacion);
				
						switch($areaOperacion){
								
							case 'AI':
							    if($codigoOperacion == 'PRO' || $codigoOperacion == 'PRC' || $codigoOperacion == 'COM'){
								    $estado = 'documental';						
							    }else{
							        $estado = 'inspeccion';
							    }
							break;
						
						}
						
						//$cr->enviarOperacion($conexion, $idSolicitud, $estado);
						$cr->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion'], $estado);
						
					break;
									
					case 'Importación' : 
						$ci = new ControladorImportaciones();
						$ci->enviarImportacion($conexion, $idSolicitud, $estado);
				
					break;
				
					case 'importacionMuestras' :
						$cif = new ControladorImportacionesFertilizantes();
						$cif->enviarImportacionFertilizantes($conexion, $idSolicitud, $estado);
						
					break;
					
					case 'DDA' :
						$cd = new ControladorDestinacionAduanera();
						$cd->enviarDDA($conexion, $idSolicitud, $estado);
				
					break;
				
					case 'Fitosanitario' :
						$cf = new ControladorFitosanitario();
						$cf->enviarFito($conexion, $idSolicitud, $estado);
				
					break;
					
					case 'Zoosanitario' :
						$cz = new ControladorZoosanitarioExportacion();
						$cz->enviarZoo($conexion, $idSolicitud, $estado);
				
					break;
								
					case 'CLV' :
						$cl = new ControladorClv();
						$cl->enviarClv($conexion, $idSolicitud, $estado);
						
					break;
					
					case 'tramitesInocuidad' :
						$cti = new ControladorTramitesInocuidad();
						$cti->actualizarEstadoTramite($conexion, $idSolicitud, $estado);
					break;
					
					case 'mercanciasSinValorComercialExportacion':
					case 'mercanciasSinValorComercialImportacion':
						$cme = new ControladorMercanciasSinValorComercial();
						$cme->actualizarEstadoMercanciaSV($conexion, $estado, $idSolicitud);
					break;
					
					case 'transitoInternacional' :
					    $cti = new ControladorTransitoInternacional();
					    $cti->enviarTransitoInternacional($conexion, $idSolicitud, $estado);
					    
					    break;
				
					default :
						echo 'Formulario desconocido';
				}
				
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = 'Debe asignar la solicitud a un nuevo inspector.';
				
			}else{
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = 'El inspector ha sido eliminado satisfactoriamente.';
			}
			
		
		
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
