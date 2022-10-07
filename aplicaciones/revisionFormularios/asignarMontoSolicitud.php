<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVUE.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorCertificadoCalidad.php';
require_once '../../clases/ControladorRevisionSolicitudesVUE.php';
require_once '../../clases/ControladorMercanciasSinValorComercial.php';


//Creación de documento de requerimientos por productos para importacion
require_once '../general/crearReporteRequisitos.php';
require_once '../general/administrarArchivoFTP.php';

//Controladores por solicitud

require_once '../../clases/ControladorClv.php';
require_once '../../clases/ControladorFinanciero.php';
require_once '../../clases/ControladorImportaciones.php';
require_once '../../clases/ControladorFitosanitario.php';
require_once '../../clases/ControladorCertificacionBPA.php';
require_once '../../clases/ControladorDestinacionAduanera.php';
require_once '../../clases/ControladorZoosanitarioExportacion.php';
require_once '../../clases/ControladorFitosanitarioExportacion.php';
require_once '../../clases/ControladorCertificadoFito.php';
require_once '../../clases/ControladorDossierPecuario.php';

try{
	$inspector = htmlspecialchars ($_POST['inspector'],ENT_NOQUOTES,'UTF-8');
	$idSolicitud= $_POST['idSolicitud'];
	$idSolicitudGrupo = explode(",",$_POST['idSolicitud']);
	$tipoSolicitud = ($_POST['tipoSolicitud']);
	$tipoInspector = ($_POST['tipoInspector']);
	$monto = htmlspecialchars ($_POST['valorTotal'],ENT_NOQUOTES,'UTF-8');
	$estado = htmlspecialchars ($_POST['estado'],ENT_NOQUOTES,'UTF-8');
	$idVue = htmlspecialchars ($_POST['idVue'],ENT_NOQUOTES,'UTF-8');
	$idOperador = htmlspecialchars ($_POST['idOperador'],ENT_NOQUOTES,'UTF-8');
	$idOperadorTipoOperacion = htmlspecialchars($_POST['idOperadorTipoOperacion'],ENT_NOQUOTES,'UTF-8');
	$idHistoricoOperacion = htmlspecialchars($_POST['idHistoricoOperacion'],ENT_NOQUOTES,'UTF-8');
		
	$envioVue = htmlspecialchars ($_POST['envioVue'],ENT_NOQUOTES,'UTF-8');
	
	$idOperadorTipoOperacion = ($idOperadorTipoOperacion == '' ? 0:$idOperadorTipoOperacion);
	$idHistoricoOperacion = ($idHistoricoOperacion == '' ? 0: $idHistoricoOperacion);
		
	try {
		$conexion = new Conexion();
		$crs = new ControladorRevisionSolicitudesVUE();
		$cVUE = new ControladorVUE();
		$cfn = new ControladorFinanciero();
		
		if($envioVue != 'tarifarioAntiguo'){
			//Verifica si la operación está asignada a un inspector, caso contrario se asigna a la persona que realiza la inspección
			//$idGrupoAsignado = $crs->buscarInspectorAsignado($conexion, $idSolicitud, $inspector, $tipoSolicitud, $tipoInspector);
			
			//if(pg_num_rows($idGrupoAsignado)==0){
				$idGrupoAsignado= $crs->guardarNuevoInspector($conexion, $inspector, $inspector, $tipoSolicitud, $tipoInspector, $idOperadorTipoOperacion, $idHistoricoOperacion);
					
				foreach ($idSolicitudGrupo as $solicitud){
					$crs->guardarGrupo($conexion, $solicitud,pg_fetch_result($idGrupoAsignado, 0, 'id_grupo'), 'Financiero');
				}
			//}
			
			$ordenPago = $crs->buscarSerialOrden($conexion, pg_fetch_result($idGrupoAsignado, 0, 'id_grupo'), $tipoInspector);
			
			//Guarda inspector, monto y fecha para inspeccion financiera
			$idFinanciero = $crs->asignarMontoSolicitud($conexion, pg_fetch_result($idGrupoAsignado, 0, 'id_grupo'), $inspector, $monto, pg_fetch_result($ordenPago, 0, 'orden'));
			
			
			if($monto == '0'){
				$crs->guardarInspeccionFinanciero($conexion, pg_fetch_result($idFinanciero, 0, 'id_financiero'), $inspector, 'aprobado', 'Verificación de pago automatica por asignación de valor de pago con valor 0', '0', $monto, 'Recaudación ventanilla','000000000');
			}
		}
		
		
		
		//Guardar resultado solicitud (cambio de estado)
		switch ($tipoSolicitud){
		
			case 'Operadores' :
				
				$cr = new ControladorRegistroOperador();
				
				if($envioVue != 'tarifarioAntiguo'){
															
					foreach ($idSolicitudGrupo as $solicitud){
						
						if($monto == '0'){
							$idflujoOperacion = pg_fetch_assoc($cr->obtenerIdFlujoXOperacion($conexion, $solicitud));
							$idFlujoActual = pg_fetch_assoc($cr->obtenerEstadoActualFlujoOperacion($conexion, $idflujoOperacion['id_flujo_operacion'], 'verificacion'));
							$estado = pg_fetch_assoc($cr->obtenerEstadoFlujoOperacion($conexion, $idflujoOperacion['id_flujo_operacion'], $idFlujoActual['predecesor']));
							$estado = $estado['estado'];
						}
						//$cr->enviarOperacion($conexion, $solicitud, $estado);
						$operacion = pg_fetch_assoc($cr->abrirOperacionXid($conexion, $solicitud));
						$idOperadorTipoOperacion = $operacion['id_operador_tipo_operacion'];
						
						$qHistorialOperacion = $cr->obtenerMaximoIdentificadorHistoricoOperacion($conexion, $idOperadorTipoOperacion);
						$historialOperacion = pg_fetch_assoc($qHistorialOperacion);
						
						$cr->actualizarEstadoTipoOperacionPorIndentificadorSitio($conexion, $idOperadorTipoOperacion, $estado);
						$cr->actualizarEstadoAnteriorPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion']);
						$cr->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion'], $estado);
					}

				}/*else if($envioVue == 'tarifarioAntiguo'){
					
					if($monto == '0'){
						$estado = 'inspeccion';
						
						foreach ($idSolicitudGrupo as $solicitud){
							//$cr->enviarOperacion($conexion, $solicitud, $estado);
							$operacion = pg_fetch_assoc($cr->abrirOperacionXid($conexion, $solicitud));
							$idOperadorTipoOperacion = $operacion['id_operador_tipo_operacion'];
							
							$qHistorialOperacion = $cr->obtenerMaximoIdentificadorHistoricoOperacion($conexion, $idOperadorTipoOperacion);
							$historialOperacion = pg_fetch_assoc($qHistorialOperacion);
							
							
							$cr->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion'], $estado);
						}
					
					}
				
				}*/
				
				
			break;
			
		
			case 'Importación' : 
				$ci = new ControladorImportaciones();
				
				if($monto == '0'){
					
					$documento = $ci->abrirImportacionesArchivoIndividual($conexion, $idSolicitud, 'PEDIDO DE AMPLIACION');
					$importacion = pg_fetch_assoc($ci->obtenerImportacion($conexion, $idSolicitud));
					
					if(pg_num_rows($documento)!=0  && $importacion['fecha_inicio']!= ''){
							
						//Obtener datos de importación
						$qImportacion = $ci->obtenerImportacion($conexion, $idSolicitud);
						$importacion = pg_fetch_assoc($qImportacion);
							
						//Asigna el resultado de revisión de pago de solicitud de importacion
						$ci->enviarImportacion($conexion, $idSolicitud, 'ampliado');
							
						//Asignar estado a productos de solicitud
						$ci->enviarProductosImportacion($conexion, $idSolicitud, 'ampliado');
							
						//Asignar fecha de vigencia de solicitud
							
						$fechaAmpliacion = date ('Y-m-j', strtotime("+30 days", strtotime( $importacion['fecha_vigencia'] )));
							
						$ci->enviarFechaVigenciaAmpliacion($conexion, $idSolicitud, $fechaAmpliacion);
							
						if($idVue != ''){
							$cVUE->ingresarSolicitudesXatenderGUIA('101-002-REQ','340','21',$idVue, 'Por atender');
						}
							
					}else{
							
						//Asigna el resultado de revisión de pago de solicitud de importacion
						$ci->enviarImportacion($conexion, $idSolicitud, 'aprobado');
							
						//Asignar estado a productos de solicitud
						$ci->enviarProductosImportacion($conexion, $idSolicitud, 'aprobado');
							
						//Asignar fecha de vigencia de solicitud
						$ci->enviarFechaVigenciaImportacion($conexion, $idSolicitud ,$importacion['id_area']);
							
						if($idVue != ''){
							$cVUE->ingresarSolicitudesXatenderGUIA('101-002-REQ','320','21',$idVue, 'Por atender');
						}
							
					}
				}else{
					if($envioVue != 'tarifarioAntiguo'){
						$ci->enviarImportacion($conexion, $idSolicitud, $estado);
						if($idVue != '' ){
							$cVUE->ingresarSolicitudesXatenderGUIA('101-002-REQ','120','21',$idVue, 'Por atender');
						}
					}
				}

				///////////////////////////////////////////////////////////////////////////////////////////
				
				/*$pdf = new PDF();
				$pdf->AliasNbPages();
				$pdf->AddPage();
				$pdf->Body($idSolicitud, $tipoSolicitud);
				$pdf->SetFont('Times','',12);
				$pdf->Output("../importaciones/archivosRequisitos/".$idOperador."-".$idSolicitud.".pdf");
					
				$informeRequisitos = "aplicaciones/importaciones/archivosRequisitos/".$idOperador."-".$idSolicitud.".pdf";
					
				//Actualizar registro
				$ci->asignarDocumentoRequisitosImportacion($conexion, $idSolicitud, $informeRequisitos);*/
				
				///////////////////////////////////////////////////////////////////////////////////////////
												
			break;
			
		
			case 'Fitosanitario' :
				$cf = new ControladorFitosanitario();
			
				//CREACION DEL PDF CON REQUISITOS POR PRODUCTO
				/*$pdf = new PDF();
				$pdf->AliasNbPages();
				$pdf->AddPage();
				$pdf->Detalle($idSolicitud,$tipoSolicitud);
				$pdf->SetFont('Times','',12);
				$pdf->Output("../exportacionFitosanitario/archivosRequisitos/".$idSolicitud.".pdf");
					
				$informeRequisitos = "aplicaciones/exportacionFitosanitario/archivosRequisitos/".$idSolicitud.".pdf";
					
				//Actualizar registro
				$cf->asignarDocumentoRequisitosFitosanitario($conexion, $idSolicitud, $informeRequisitos);
								
				if($idVue != '' && $envioVue != 'tarifarioAntiguo'){
					$cFTP = new administrarArchivoFTP();
					$cFTP->enviarArchivo($informeRequisitos, $idSolicitud.'.pdf', 'fitosanitario');
				}*/
				
				if($monto == '0'){
					
					$cf->enviarFito($conexion, $idSolicitud, 'aprobado');
					$cf->evaluarProductosFito($conexion, $idSolicitud, 'aprobado');
					$cf->enviarFechaVigenciaFito($conexion, $idSolicitud);
						
					if($idVue != ''){
						$cVUE->ingresarSolicitudesXatenderGUIA('101-031-REQ','320','21',$idVue, 'Por atender');
					}
					
				}else{
					if($envioVue != 'tarifarioAntiguo'){
						$cf->enviarFito($conexion, $idSolicitud, $estado);
						if($idVue != '' ){
							$cVUE->ingresarSolicitudesXatenderGUIA('101-031-REQ','120','21',$idVue, 'Por atender');
						}
					}
				}
				
			break;
			
		
			case 'Zoosanitario' :
				$cz = new ControladorZoosanitarioExportacion();
				
				
				//CREACION DEL PDF CON REQUISITOS POR PRODUCTO
				$pdf = new PDF();
				$pdf->AliasNbPages();
				$pdf->AddPage();
				$pdf->Detalle($idSolicitud,$tipoSolicitud);
				$pdf->SetFont('Times','',12);
				$pdf->Output("../exportacionZoosanitario/archivosRequisitos/".$idOperador.'-'.$idSolicitud.".pdf");
					
				$informeRequisitos = "aplicaciones/exportacionZoosanitario/archivosRequisitos/".$idOperador.'-'.$idSolicitud.".pdf";
					
				//Actualizar registro
				$cz->asignarDocumentoRequisitosZoosanitario($conexion, $idSolicitud, $informeRequisitos);
				
				if($idVue != '' && $envioVue != 'tarifarioAntiguo'){
					$cVUE->ingresarSolicitudesXatenderGUIA('101-008-REQ','120','21',$idVue, 'Por atender');
					$cFTP = new administrarArchivoFTP();
					$cFTP->enviarArchivo($informeRequisitos, $idOperador.'-'.$idSolicitud.'.pdf', 'zoosanitario');
				}
				
				$cz->enviarZoo($conexion, $idSolicitud, $estado);
				
			break;
			
			
			case 'CLV' :
				$clv = new ControladorClv();
				$clv->enviarClv($conexion, $idSolicitud, $estado);
				if($idVue != '' && $envioVue != 'tarifarioAntiguo'){
					$cVUE->ingresarSolicitudesXatenderGUIA('101-047-REQ','120','21',$idVue, 'Por atender');
				}
			break;
			
			case 'certificadoCalidad' :
				
				if($envioVue != 'tarifarioAntiguo'){
					$cc = new ControladorCertificadoCalidad();
					foreach ($idSolicitudGrupo as $solicitud){
						$cc->actualizarEstadoLote($conexion, $solicitud, $estado);
					}	
				}
				
			break;
			
			case 'FitosanitarioExportacion':
				
				$cfe = new ControladorFitosanitarioExportacion();
				
				if($envioVue != 'tarifarioAntiguo'){
					$cfe->actualizarEstadoFitosanitarioExportacion($conexion, $idSolicitud, $estado, 'pago', 'Imposición de pago');
					if($idVue != '' ){
						$cVUE->ingresarSolicitudesXatenderGUIA('101-034-REQ','120','21',$idVue, 'Por atender');
					}
				}
			
			break;
			
			case 'mercanciasSinValorComercialImportacion':
			case 'mercanciasSinValorComercialExportacion':
				
				$cme = new ControladorMercanciasSinValorComercial();
				
				if($monto == '0'){					
					$cme->actualizarEstadoMercanciaSV($conexion, 'aprobado', $idSolicitud);
				}else{
					$cme->actualizarEstadoMercanciaSV($conexion, 'verificacion', $idSolicitud);
				}
				
			break;
			
			case 'certificacionBPA':
				
				$ccb = new ControladorCertificacionBPA();
				
				if($monto == '0'){
					$ccb->actualizarEstadoSolicitud($conexion, $idSolicitud, 'inspeccion');
				}else{
					$ccb->actualizarEstadoSolicitud($conexion, $idSolicitud, 'verificacion');
				}
			break;
			
			case 'certificadoFito':
			    
			    $ccf = new ControladorCertificadoFito();
			    
			    $solicitud = pg_fetch_assoc($ccf->abrirSolicitud($conexion, $idSolicitud));
			    $ccf->actualizarEstadoCertificado($conexion, 'verificacion', $idSolicitud, $_SESSION['usuario']);
			    $ccf->actualizarEstadoExportadoresProductos($conexion, 'verificacion', $idSolicitud);
			    
			break;
			
			case 'dossierPecuario':
			    
			    $cdpmvc = new ControladorDossierPecuario();
			    
			    $solicitud = pg_fetch_assoc($cdpmvc->abrirSolicitud($conexion, $idSolicitud));
			    
			    $cdpmvc->actualizarEstadoSolicitud($conexion, 'verificacion', $idSolicitud, $_SESSION['usuario'], $_SESSION['idProvincia'], 'Proceso de Asignación de Tasa');
			    
			    $cdpmvc->ingresarHistoricoEstados($conexion, 'verificacion', $idSolicitud, $_SESSION['usuario'], 'Técnico financiero realiza asignación de tasa');
			    
			break;
				
			default :
			break;
		}
			
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'La operación se ha guardado satisfactoriamente.-'.pg_fetch_result($idGrupoAsignado, 0, 'id_grupo');
		
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