<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVUE.php';
require_once '../../clases/ControladorReportes.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorRevisionSolicitudesVUE.php';
require_once '../../clases/ControladorMercanciasSinValorComercial.php';
require_once '../../clases/ControladorEstructuraFuncionarios.php';
require_once '../../clases/Constantes.php';
require_once '../../clases/ControladorFirmaDocumentos.php';

//Controladores por solicitud
require_once '../../clases/ControladorUsuarios.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorImportaciones.php';
require_once '../../clases/ControladorFitosanitario.php';
require_once '../../clases/ControladorCertificacionBPA.php';
require_once '../../clases/ControladorCertificadoCalidad.php';
require_once '../../clases/ControladorDestinacionAduanera.php';
require_once '../../clases/ControladorFinancieroAutomatico.php';
require_once '../../clases/ControladorZoosanitarioExportacion.php';
require_once '../../clases/ControladorFitosanitarioExportacion.php';
require_once '../../clases/ControladorGestionAplicacionesPerfiles.php';
require_once '../../clases/ControladorVigenciaDocumentos.php';

function reemplazarCaracteres($cadena){
    $cadena = str_replace('á', 'a', $cadena);
    $cadena = str_replace('é', 'e', $cadena);
    $cadena = str_replace('í', 'i', $cadena);
    $cadena = str_replace('ó', 'o', $cadena);
    $cadena = str_replace('ú', 'u', $cadena);
    
	$cadena = str_replace('Á', 'A', $cadena);
	$cadena = str_replace('É', 'E', $cadena);
	$cadena = str_replace('Í', 'I', $cadena);
	$cadena = str_replace('Ó', 'O', $cadena);
	$cadena = str_replace('Ú', 'U', $cadena);
	
    return $cadena;
}

try{
	$inspector = htmlspecialchars($_POST['inspector'],ENT_NOQUOTES,'UTF-8');
	$idSolicitud = htmlspecialchars($_POST['idSolicitud'],ENT_NOQUOTES,'UTF-8');
	$tipoSolicitud = htmlspecialchars ($_POST['tipoSolicitud'],ENT_NOQUOTES,'UTF-8');
	$tipoInspector = htmlspecialchars ($_POST['tipoInspector'],ENT_NOQUOTES,'UTF-8');
	$resultado = htmlspecialchars ($_POST['resultado'],ENT_NOQUOTES,'UTF-8');
	$observaciones = htmlspecialchars ($_POST['observacion'],ENT_NOQUOTES,'UTF-8');
	$idVue = htmlspecialchars ($_POST['idVue'],ENT_NOQUOTES,'UTF-8');
	$tipoElemento = htmlspecialchars ($_POST['tipoElemento'],ENT_NOQUOTES,'UTF-8');
	$idOperadorTipoOperacion = htmlspecialchars($_POST['idOperadorTipoOperacion'], ENT_NOQUOTES, 'UTF-8'); 
	$idHistoricoOperacion = htmlspecialchars($_POST['idHistoricoOperacion'], ENT_NOQUOTES, 'UTF-8');
	$codigoProvinciaSitio = htmlspecialchars($_POST['codigoProvinciaSitio'], ENT_NOQUOTES, 'UTF-8');
	$idSitio = htmlspecialchars($_POST['idSitio'], ENT_NOQUOTES, 'UTF-8');
	$provinciaSitio = htmlspecialchars($_POST['provinciaSitio'], ENT_NOQUOTES, 'UTF-8');
	$fechaInicio = $_POST['fechaInicio'];

	$idOperadorTipoOperacion = ($idOperadorTipoOperacion == '' ? 0:$idOperadorTipoOperacion);
	$idHistoricoOperacion = ($idHistoricoOperacion == '' ? 0: $idHistoricoOperacion);

	$listaElementos =  $_POST['listaElementos'];
	$observacionesAreas = $_POST['observacionAreas'];
	$idAreas = $_POST['idAreas'];
	$idOperaciones = $_POST['idOperaciones'];
	$idGrupoSolicitudes = explode(",",$idSolicitud);
	
	$archivo = htmlspecialchars ($_POST['archivo'],ENT_NOQUOTES,'UTF-8');
	$idOperador = htmlspecialchars ($_POST['identificadorOperador'],ENT_NOQUOTES,'UTF-8');
	$tecnicoInspeccion = htmlspecialchars ($_POST['tecnicoInspeccion'],ENT_NOQUOTES,'UTF-8');

	$aprobado = 0;
	$rechazado = 0;
	$subsanacion = 0;
	$blanco = 0;
	$tipoTiempoVigencia = null;
	$generarDocumento = true;
	$generarReporteAlmacenes = false;
	$generarReporteEmpresas = false;

	//$rutaInspeccion = $_POST['hRutaInspeccion'];
	
	$nombreOpcion = $_POST['nombreOpcion'];
	$fechaActual = date("Y-m-d h:m:s");
	$actualizacionFechas = true;
	
	$arrayResultados = array('noHabilitado','subsanacion', 'subsanacionRepresentanteTecnico','subsanacionProducto');//Verificar si existe subsanacion

	$jru = new ControladorReportes();
	$cef = new ControladorEstructuraFuncionarios();
	
	
	try {
		$conexion = new Conexion();
		$cu = new ControladorUsuarios();
		$cc = new ControladorCatalogos();
		$ca = new ControladorAplicaciones();
		$crs = new ControladorRevisionSolicitudesVUE();
		$cVUE = new ControladorVUE();
		$cvd = new ControladorVigenciaDocumentos();
		//nuevo
		$cgap= new ControladorGestionAplicacionesPerfiles();
		$cfd = new ControladorFirmaDocumentos();
		
		//if(count($listaElementos) != 0){

		//Verifica si la operación está asignada a un inspector, caso contrario se asigna a la persona que realiza la inspección
		//$inspectorAsignado = $crs->buscarInspectorAsignado($conexion, $idSolicitud, $inspector, $tipoSolicitud, $tipoInspector);
			
		//if(pg_num_rows($inspectorAsignado)==0){

		$inspectorAsignado= $crs->guardarNuevoInspector($conexion, $inspector, $inspector, $tipoSolicitud, $tipoInspector, $idOperadorTipoOperacion, $idHistoricoOperacion);
	
		foreach ($idGrupoSolicitudes as $solicitud){
			$crs->guardarGrupo($conexion, $solicitud,pg_fetch_result($inspectorAsignado, 0, 'id_grupo'), $tipoInspector);
		}

		$ordenInspeccion = $crs->buscarSerialOrden($conexion, pg_fetch_result($inspectorAsignado, 0, 'id_grupo'), $tipoInspector);

		//Guardar resultado solicitud (cambio de estado)
		switch ($tipoSolicitud){
			case 'Operadores' :{
				$cr = new ControladorRegistroOperador();

				if(count($idAreas)>0){

					if (!in_array($resultado, $arrayResultados)) {
						
						if($resultado == 'registrado'){
							$idVigenciaDeclarada = null;
						}else{
							$idVigenciaDeclarada = $resultado;
							$resultado = 'registrado';
						}
					}
					//Guarda inspector, calificación y fecha
					//$crs->guardarDatosInspeccionElementos($conexion, pg_fetch_result($inspectorAsignado, 0, 'id_grupo'), $inspector, pg_fetch_result($inspectorAsignado, 0, 'id_grupo'), $archivo, $resultado, $observaciones, $tipoElemento, pg_fetch_result($ordenInspeccion, 0, 'orden'));

					$idInspeccion =  $crs->guardarDatosInspeccionElementos($conexion, pg_fetch_result($inspectorAsignado, 0, 'id_grupo'), $inspector, $archivo, $resultado, pg_fetch_result($ordenInspeccion, 0, 'orden'), $observaciones);

					for ($i=0; $i<count($idAreas);$i++){
						//Guarda inspector, calificación y fecha
						//$crs->guardarDatosInspeccionElementos($conexion, pg_fetch_result($inspectorAsignado, 0, 'id_grupo'), $inspector, $idAreas[$i], $archivo, $resultado, $idOperaciones[$i].' '.$observacionesAreas[$i], $tipoElemento, pg_fetch_result($ordenInspeccion, 0, 'orden'));
						$crs->guardarDatosInspeccionObservaciones($conexion, pg_fetch_result($idInspeccion, 0, 'id_inspeccion'), $idAreas[$i], $observacionesAreas[$i], $tipoElemento,  $idOperaciones[$i]);
						//Guarda estado de elementos a evaluar
						$cr->evaluarAreasOperacion($conexion, $idOperaciones[$i], $idAreas[$i], $resultado, $observacionesAreas[$i], $archivo);
					}
					
					$modulosAgregados="";
					$perfilesAgregados="";
					
					
					
					if($resultado == 'registrado'){
						foreach ($idGrupoSolicitudes as $solicitud){
							//$cr->enviarOperacion($conexion, $solicitud, $resultado, $observaciones);
	
							$operacion = pg_fetch_assoc($cr->abrirOperacionXid($conexion, $solicitud));
							$idOperadorTipoOperacion = $operacion['id_operador_tipo_operacion'];
	
							$qHistorialOperacion = $cr->obtenerMaximoIdentificadorHistoricoOperacion($conexion, $idOperadorTipoOperacion);
							$historialOperacion = pg_fetch_assoc($qHistorialOperacion);
	
							$idflujoOPeracion = pg_fetch_assoc($cr->obtenerIdFlujoXOperacion($conexion, $solicitud));
							$idFlujoActual = pg_fetch_assoc($cr->obtenerEstadoActualFlujoOperacion($conexion, $idflujoOPeracion['id_flujo_operacion'], 'inspeccion'));
							$estado = pg_fetch_assoc($cr->obtenerEstadoFlujoOperacion($conexion, $idflujoOPeracion['id_flujo_operacion'], $idFlujoActual['predecesor']));
							
							if($operacion['modulo_provee'] == 'moduloExterno' && $estado['estado'] == 'cargarProducto'){
							    $estado = pg_fetch_assoc($cr->obtenerEstadoFlujoOperacion($conexion, $idflujoOPeracion['id_flujo_operacion'], $idFlujoActual['predecesor']+1));
							}
						
							$idVigenciaDocumento = null;
							if($operacion['proceso_modificacion'] != 't'){								
								$existenciaOperacion = $cr->verificarExistenciaOperaciones($conexion, $operacion['identificador_operador'], $operacion['id_tipo_operacion'], $idAreas[0], 'porCaducar', $idVigenciaDocumento);//TODO:CAMBIADO
								
								if(pg_num_rows($existenciaOperacion) == 0){
									$cr->actualizarEstadoAnteriorPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion']);
								}else{									
									$datosOperacion = pg_fetch_assoc($existenciaOperacion);	
									$cr->actualizarEstadoAnteriorPorOperadorTipoOperacionHistorial($conexion, $datosOperacion['id_operador_tipo_operacion'], $datosOperacion['id_historial_operacion'], $idVigenciaDocumento);//TODO:CAMBIADO
									if($idVigenciaDocumento != null){										
										$cr->actualizarFechaFinalizacionOperacionesAntiguos($conexion, $idOperadorTipoOperacion, $operacion['id_historial_operacion'], $datosOperacion['fecha_finalizacion'], $valorVigencia, $tipoTiempoVigencia, $idVigenciaDocumento);//TODO:CAMBIADO
									}
									$cr->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $datosOperacion['id_operador_tipo_operacion'], $datosOperacion['id_historial_operacion'],'noHabilitado', 'Cambio de estado no habilitado por registro de nueva operación '.$fechaActual, $idVigenciaDocumento);//TODO:CAMBIADO
									$cr->cambiarEstadoAreaOperacionPorPorOperadorTipoOperacionHistorial($conexion, $datosOperacion['id_operador_tipo_operacion'], $datosOperacion['id_historial_operacion'], $idVigenciaDocumento);
									$cr->actualizarEstadoTipoOperacionPorIndentificadorSitio($conexion, $datosOperacion['id_operador_tipo_operacion'], 'noHabilitado');
								}
							}else {								
								$actualizacionFechas = false;
							}
	
							if($idVigenciaDeclarada != null){	
									
								$qVigenciaDeclarada = $cvd->obtenerVigenciaDeclaradaPorIdVigenciaDeclarada($conexion, $idVigenciaDeclarada);									
								$vigenciaDeclarada = pg_fetch_assoc($qVigenciaDeclarada);									
								$valorVigencia = $vigenciaDeclarada['valor_tiempo_vigencia_declarada'];						
								$idVigenciaDocumento = $vigenciaDeclarada['id_vigencia_documento'];									
								$tipoTiempoVigencia = $cvd->transformarvalorTipoVigencia($vigenciaDeclarada['tipo_tiempo_vigencia_declarada']);							
							}

							if($idVigenciaDocumento != null){								
								$cr->actualizarFechaFinalizacionOperacionesNuevos($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion'], $valorVigencia, $tipoTiempoVigencia, $idVigenciaDocumento);//TODO:CAMBIADO
							}
							
							$qcodigoTipoOperacion= $cc->obtenerCodigoTipoOperacion($conexion, $solicitud);
							$opcionArea=  pg_fetch_result($qcodigoTipoOperacion, 0, 'codigo');
							$idArea=  pg_fetch_result($qcodigoTipoOperacion, 0, 'id_area');
							
							switch ($idArea){
								case 'SV':
									switch ($opcionArea){
										case 'PRP':
										case 'VVE':
										case 'ALM':
										case 'MIM':
											$existenciaDocumento = $cr->obtenerDocumentoGeneradoInspeccionPorOperadorTipoOperacion($conexion, $idOperadorTipoOperacion);
											if(pg_num_rows($existenciaDocumento) == 0){
												$secuencial = pg_fetch_assoc($cr->obtenerMaximoDocumentoOperador($conexion, '', 'centroPropagacionViverista'));
												$secuencial = str_pad($secuencial['secuencial'], 5, '0', STR_PAD_LEFT);
												$actualizacionFechas = true;
											}else{
												$generarDocumento = false;
												$secuencial = str_pad(pg_fetch_result($existenciaDocumento, 0, 'secuencial'), 5, '0', STR_PAD_LEFT);
												$actualizacionFechas = false;
											}
											$codigo = $idOperador.'-'.$secuencial;
										break;
									}
								break;
								case 'SA':
								    switch ($opcionArea){				   
								        case 'CUA':
								            $existenciaDocumento = $cr->obtenerDocumentoGeneradoInspeccionPorOperadorTipoOperacion($conexion, $idOperadorTipoOperacion);
								            if(pg_num_rows($existenciaDocumento) == 0){
								                $secuencial = pg_fetch_assoc($cr->obtenerMaximoDocumentoOperador($conexion, $idOperador, 'cuarentena'));
								                $secuencial = str_pad($secuencial['secuencial'], 8, '0', STR_PAD_LEFT);
												$actualizacionFechas = true;
								            }else{
								                $secuencial = str_pad(pg_fetch_result($existenciaDocumento, 0, 'secuencial'), 8, '0', STR_PAD_LEFT);
												$actualizacionFechas = false;
								            }
								        break;
								        case 'PRA':
								        case 'SEA':
								        case 'POA':
								            $existenciaDocumento = $cr->obtenerDocumentoGeneradoInspeccionPorOperadorTipoOperacion($conexion, $idOperadorTipoOperacion);
								            if(pg_num_rows($existenciaDocumento) == 0){
								                $secuencial = pg_fetch_assoc($cr->obtenerMaximoDocumentoOperador($conexion, '', date('Y').'-explotacionApicola'));
								                $secuencial = str_pad($secuencial['secuencial'], 3, '0', STR_PAD_LEFT);
												$actualizacionFechas = true;
								            }else{
								                $generarDocumento = false;
								                $secuencial = str_pad(pg_fetch_result($existenciaDocumento, 0, 'secuencial'), 3, '0', STR_PAD_LEFT);
												$actualizacionFechas = false;
								            }
								            $codigo = $codigoProvinciaSitio.'-'.'AGR'.'-'.($opcionArea == 'PRA'?'DP':($opcionArea == 'SEA'?'SC':'PZ')).'-'.$secuencial;
								        break;
								        case 'COM':
								        case 'IND':
								        case 'PRO':
								            $existenciaDocumento = $cr->obtenerDocumentoGeneradoInspeccionPorOperadorTipoOperacion($conexion, $idOperadorTipoOperacion);
								            if(pg_num_rows($existenciaDocumento) == 0){
								                $secuencial = pg_fetch_assoc($cr->obtenerMaximoDocumentoOperador($conexion, '', $codigoProvinciaSitio.'-'.date('Y').'-centroPecuarioExportacion'));
								                $secuencial = str_pad($secuencial['secuencial'], 3, '0', STR_PAD_LEFT);
												$actualizacionFechas = true;
								            }else{
								                $generarDocumento = false;
								                $secuencial = str_pad(pg_fetch_result($existenciaDocumento, 0, 'secuencial'), 3, '0', STR_PAD_LEFT);
												$actualizacionFechas = false;
								            }
								            $codigo = $codigoProvinciaSitio.'-'.'AGR'.'-DCZ-'.$secuencial;
								        break;
								        case 'CPE':
								        case 'EPE':
								            $existenciaDocumento = $cr->obtenerDocumentoGeneradoInspeccionPorOperadorTipoOperacion($conexion, $idOperadorTipoOperacion);
								            if(pg_num_rows($existenciaDocumento) == 0){
								                $secuencial = pg_fetch_assoc($cr->obtenerMaximoDocumentoOperador($conexion, '', $codigoProvinciaSitio.'-'.date('Y').'-centroMercanciaPecuaria'));
								                $secuencial = str_pad($secuencial['secuencial'], 3, '0', STR_PAD_LEFT);
												$actualizacionFechas = true;
								            }else{
								                $generarDocumento = false;
								                $secuencial = str_pad(pg_fetch_result($existenciaDocumento, 0, 'secuencial'), 3, '0', STR_PAD_LEFT);
												$actualizacionFechas = false;
								            }
								            $codigo = $codigoProvinciaSitio.'-'.'AGR'.'-DCZ-'.$secuencial;
								        break;
								        case 'PMR':
								        case 'CPM':
								        case 'DMR':
								        case 'AMR':
								            $existenciaDocumento = $cr->obtenerDocumentoGeneradoInspeccionPorOperadorTipoOperacion($conexion, $idOperadorTipoOperacion);
								            if(pg_num_rows($existenciaDocumento) == 0){
								                $verificarExistenciaOperacion = $cr->obtenerIdOperadorTipoOperacionPorTipoOperacionAreaEstado($conexion, $idOperador, "('porCaducar', 'noHabilitado')", $operacion['id_tipo_operacion'], $idAreas[0]);
								                if(pg_num_rows($verificarExistenciaOperacion) == 0){
								                    $secuencial = pg_fetch_assoc($cr->obtenerMaximoDocumentoOperador($conexion, '', $codigoProvinciaSitio.'-'.'centroReproduccionAnimal'));
								                    $secuencial = str_pad($secuencial['secuencial'], 3, '0', STR_PAD_LEFT);
													$actualizacionFechas = true;
								                }else{
								                    $existenciaDocumento = $cr->obtenerDocumentoGeneradoInspeccionPorOperadorTipoOperacion($conexion, pg_fetch_result($verificarExistenciaOperacion, 0, 'id_operador_tipo_operacion'));
								                    if(pg_num_rows($existenciaDocumento) != 0){
								                        $generarDocumento = false;
								                        $secuencial = str_pad(pg_fetch_result($existenciaDocumento, 0, 'secuencial'), 3, '0', STR_PAD_LEFT);
														$actualizacionFechas = false;
								                    }else{
								                        $secuencial = pg_fetch_assoc($cr->obtenerMaximoDocumentoOperador($conexion, '', $codigoProvinciaSitio.'-centroReproduccionAnimal'));
								                        $secuencial = str_pad($secuencial['secuencial'], 3, '0', STR_PAD_LEFT);
														$actualizacionFechas = true;
								                    }
								                }
								            }else{
								                $generarDocumento = false;
								                $secuencial = str_pad(pg_fetch_result($existenciaDocumento, 0, 'secuencial'), 3, '0', STR_PAD_LEFT);
												$actualizacionFechas = false;
								            }
								            $codigo = 'CMR-'.($opcionArea == 'PMR'?'CCPD':($opcionArea == 'CPM'?'UMCP':($opcionArea == 'DMR'?'CADC':'UAIA'))).'-'.str_pad($codigoProvinciaSitio, 3, '0', STR_PAD_LEFT).'-'.$secuencial;
								        break;
								        case 'MPA':
								        case 'ATM':
								        case 'FEA':
								        case 'FER':
								            $existenciaDocumento = $cr->obtenerDocumentoGeneradoInspeccionPorOperadorTipoOperacion($conexion, $idOperadorTipoOperacion);
								            if(pg_num_rows($existenciaDocumento) == 0){
								                $secuencial = pg_fetch_assoc($cr->obtenerMaximoDocumentoOperador($conexion, '', date('Y').'-concentracionAnimal'));
								                $secuencial = str_pad($secuencial['secuencial'], 4, '0', STR_PAD_LEFT);
												$actualizacionFechas = true;
								            }else{
								                $generarDocumento = false;
								                $secuencial = str_pad(pg_fetch_result($existenciaDocumento, 0, 'secuencial'), 4, '0', STR_PAD_LEFT);
												$actualizacionFechas = false;
								            }
								            $codigo = $codigoProvinciaSitio.'-'.'AGC'.'-'.($opcionArea == 'MPA'?'CAB`s':($opcionArea == 'ATM'?'CH':($opcionArea == 'FEA'?'FE':'FC'))).'-'.$secuencial;
								        break;
										case 'OEC':
								            $existenciaDocumento = $cr->obtenerDocumentoGeneradoInspeccionPorOperadorTipoOperacion($conexion, $idOperadorTipoOperacion);
								            if(pg_num_rows($existenciaDocumento) == 0){
								                $verificarExistenciaOperacion = $cr->obtenerIdOperadorTipoOperacionPorTipoOperacionAreaEstado($conexion, $idOperador, "('porCaducar', 'noHabilitado')", $operacion['id_tipo_operacion'], $idAreas[0]);
								                if(pg_num_rows($verificarExistenciaOperacion) == 0){
								                    $secuencial = pg_fetch_assoc($cr->obtenerMaximoDocumentoOperador($conexion, '', date('Y').'-'.'organizacionEcuestre'));
								                    $secuencial = str_pad($secuencial['secuencial'], 3, '0', STR_PAD_LEFT);
								                    $actualizacionFechas = true;
								                }else{
								                    $existenciaDocumento = $cr->obtenerDocumentoGeneradoInspeccionPorOperadorTipoOperacion($conexion, pg_fetch_result($verificarExistenciaOperacion, 0, 'id_operador_tipo_operacion'));
								                    if(pg_num_rows($existenciaDocumento) != 0){
								                        $generarDocumento = false;
								                        $secuencial = str_pad(pg_fetch_result($existenciaDocumento, 0, 'secuencial'), 3, '0', STR_PAD_LEFT);
								                        $actualizacionFechas = false;
								                    }else{
								                        $secuencial = pg_fetch_assoc($cr->obtenerMaximoDocumentoOperador($conexion, '', date('Y').'-organizacionEcuestre'));
								                        $secuencial = str_pad($secuencial['secuencial'], 3, '0', STR_PAD_LEFT);
								                        $actualizacionFechas = true;
								                    }
								                }
								            }else{
								                $generarDocumento = false;
								                $secuencial = str_pad(pg_fetch_result($existenciaDocumento, 0, 'secuencial'), 3, '0', STR_PAD_LEFT);
								                $actualizacionFechas = false;
								            }
								            $codigo = 'CPZPMPE-'.str_pad(date('Y'), 3, '0', STR_PAD_LEFT).'-'.$secuencial;
								        break;
								    }
								break;
							}
	
							switch ($estado['estado']){

								case 'cargarProducto':
									$cr->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion'], $estado['estado']);
									switch ($idArea){
										case 'IAV':
											switch ($opcionArea){
												case 'DIS':
												case 'FOR':
												case 'FRA':
													$qOperaciones=$cr->buscarOperacionesPorCodigoyAreaOperacion($conexion, $idOperador,"('$opcionArea')","('$idArea')", 'cargarProducto');
													
													if(pg_num_rows($qOperaciones)>0){
														$modulosAgregados.="('PRG_DOS_PEC_MVC'),";
														$perfilesAgregados.="('PFL_USR_DOS_PEC'),";
													}
													
													$generarReporteEmpresas = true;
												break;
												case 'ALM':
													$generarReporteAlmacenes = true;
												break;
												default:
													$generarReporteEmpresas = true;
											}
										break;
										case 'IAP':
										 	switch ($opcionArea){
										 		case 'DIS':
										 		case 'ENV':
										 		case 'FOR':
										 		case 'FRA':
										 			$qOperaciones=$cr->buscarOperacionesPorCodigoyAreaOperacion($conexion, $idOperador,"('$opcionArea')","('$idArea')");
										 			if(pg_num_rows($qOperaciones)>0){
										 				$modulosAgregados.="('PRG_ENSA_EFI_MVC'),('PRG_DOSSIER_PLA'),"; //Para ensayo y dossier plaguicida
                                                        $perfilesAgregados.="('PFL_OPE_ENSA_EFI'),";
										 			}
										 			
										 			$generarReporteEmpresas = true;
										 		break;
										 		case 'ALM':
										 			$generarReporteAlmacenes = true;
										 		break;
										 		default:
										 			$generarReporteEmpresas = true;
										 	}
										break;
										case 'IAF':
											switch ($opcionArea){
												case 'DIS':
												case 'FED':
													$qOperaciones=$cr->buscarOperacionesPorCodigoyAreaOperacion($conexion, $idOperador,"('$opcionArea')","('$idArea')");
													
													if(pg_num_rows($qOperaciones)>0){
														$modulosAgregados.="('PRG_DOSSIER_FER'),";
													}
													
													$generarReporteEmpresas = true;
												break;
												case 'ALM':
													$generarReporteAlmacenes = true;
												break;
												default:
													$generarReporteEmpresas = true;
												}
										break;
										case 'CGRIA':
											switch ($opcionArea){
												case 'ALM':
													$generarReporteAlmacenes = true;
												break;
												default:
													$generarReporteEmpresas = true;
											}
										break;
									}
									
									if($generarReporteEmpresas){

										$ReporteJasper= '/aplicaciones/registroOperador/reportes/registroEmpresa/riaEmpresas.jrxml';
										$salidaReporte= '/aplicaciones/registroOperador/certificados/registroEmpresa/'.$idOperador.'.pdf';
										$rutaArchivo= 'aplicaciones/registroOperador/certificados/registroEmpresa/'.$idOperador.'.pdf';
										$rutaArchivoCodigoQr = 'http://181.112.155.173/'.$constg::RUTA_APLICACION.$salidaReporte;

										$parameters['parametrosReporte'] = array(
											'identificadorOperador'=> $idOperador,
											'rutaCertificado'=> $rutaArchivoCodigoQr,
											'fechaInicio'=> $fechaInicio
										);
										
										$jru->generarReporteJasper($ReporteJasper,$parameters,$conexion,$salidaReporte,'ria');
										
										$existenciaDocumento = $cr->obtenerDocumentoGeneradoPorSecuencialIdentificadorOperadorTipoDocumento($conexion, '1', $idOperador, 'riaEmpresas');
										if(pg_num_rows($existenciaDocumento) == 0){
											$cr->guardarDocumentoOperador($conexion, $solicitud, $idOperadorTipoOperacion, $rutaArchivo, 'riaEmpresas', '1', $idOperador, 'Certificación de registro de empresa.');
										}
										
										//Tabla de firmas físicas
										$firmaResponsable = pg_fetch_assoc($cc->obtenerFirmasResponsablePorProvincia($conexion, 'Coordinador', 'RIA'));
										
										$rutaArchivo = $constg::RUTA_SERVIDOR_OPT . '/' . $constg::RUTA_APLICACION . '/' .$rutaArchivo;
										
										//Firma Electrónica
										$parametrosFirma = array(
											'archivo_entrada'=>$rutaArchivo,
											'archivo_salida'=>$rutaArchivo,
											'identificador'=>$firmaResponsable['identificador'],
											'razon_documento'=>'Certificación de registro de empresa.',
											'tabla_origen'=>'g_operadores.documentos_operador',
											'campo_origen'=>'ruta_archivo',
											'id_origen'=>$solicitud,
											'estado'=>'Por atender',
											'proceso_firmado'=>'NO'
										);
										
										//Guardar registro para firma
										$cfd->ingresoFirmaDocumento($conexion, $parametrosFirma);
									}
									
									if($generarReporteAlmacenes){

										$ReporteJasper= '/aplicaciones/registroOperador/reportes/registroAlmacen/riaAlmacenes.jrxml';
										$salidaReporte= '/aplicaciones/registroOperador/certificados/registroAlmacen/'.$idOperador.'_'.$idSitio.'.pdf';
										$rutaArchivo= 'aplicaciones/registroOperador/certificados/registroAlmacen/'.$idOperador.'_'.$idSitio.'.pdf';
										$rutaArchivoCodigoQr = 'http://181.112.155.173/'.$constg::RUTA_APLICACION.$salidaReporte;
										
										$parameters['parametrosReporte'] = array(
											'idSitio'=> (int)$idSitio,
											'rutaCertificado'=> $rutaArchivoCodigoQr,
											'fechaInicio'=> $fechaInicio
										);
										
										$jru->generarReporteJasper($ReporteJasper,$parameters,$conexion,$salidaReporte,'ria');
										
										$existenciaDocumento = $cr->obtenerDocumentoGeneradoPorSecuencialIdentificadorOperadorTipoDocumento($conexion, $idSitio, $idOperador, 'riaAlmacenistas');
										if(pg_num_rows($existenciaDocumento) == 0){
											$cr->guardarDocumentoOperador($conexion, $solicitud, $idOperadorTipoOperacion, $rutaArchivo, 'riaAlmacenistas', $idSitio, $idOperador, 'Certificación de registro de almacén de expendio.');
										}
										
										//Tabla de firmas físicas
										$firmaResponsable = pg_fetch_assoc($cc->obtenerFirmasResponsablePorProvincia($conexion, 'Coordinador', 'RIA'));
										
										$rutaArchivo = $constg::RUTA_SERVIDOR_OPT . '/' . $constg::RUTA_APLICACION . '/' .$rutaArchivo;
										
										//Firma Electrónica
										$parametrosFirma = array(
											'archivo_entrada'=>$rutaArchivo,
											'archivo_salida'=>$rutaArchivo,
											'identificador'=>$firmaResponsable['identificador'],
											'razon_documento'=>'Certificación de registro de almacén de expendio.',
											'tabla_origen'=>'g_operadores.documentos_operador',
											'campo_origen'=>'ruta_archivo',
											'id_origen'=>$solicitud,
											'estado'=>'Por atender',
											'proceso_firmado'=>'NO'
										);
										
										//Guardar registro para firma
										$cfd->ingresoFirmaDocumento($conexion, $parametrosFirma);
									}
								break;

								case 'registrado':
									$cr->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion'], $estado['estado'], 'Solicitud aprobada '.$fechaActual);
									if($actualizacionFechas){
									$cr->actualizarFechaAprobacionOperaciones($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion'], $idVigenciaDocumento );
									}else{
										$cr->actualizarFechaAprobacionOperacionesProcesoModificacion($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion'], $idVigenciaDocumento);
									}
									$cr->cambiarEstadoAreaOperacionPorPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion'], $idVigenciaDocumento);
									$cr->actualizarProcesoActualizacionOperacion($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion']);

									switch ($idArea){
										
										case 'SV':
											switch ($opcionArea){
												case 'PRP':
												case 'VVE':
												case 'ALM':
												case 'MIM':
													$firmaResponsable = pg_fetch_assoc($cc->obtenerFirmasResponsablePorProvincia($conexion, $provinciaSitio, 'SV'));
													
													$ReporteJasper= '/aplicaciones/registroOperador/reportes/centroPropagacionViverista/centroPropagacionViverista.jrxml';
													$salidaReporte= '/aplicaciones/registroOperador/certificados/centroPropagacionViverista/'.$idOperador.'_'.$idOperadorTipoOperacion.'.pdf';
													$rutaArchivo= 'aplicaciones/registroOperador/certificados/centroPropagacionViverista/'.$idOperador.'_'.$idOperadorTipoOperacion.'.pdf';
													
													$parameters['parametrosReporte'] = array(
														'numeroCertificado'=> $codigo,
														'idOperadorTipoOperacion'=> (int)$idOperadorTipoOperacion
													);
													
													$jru->generarReporteJasper($ReporteJasper,$parameters,$conexion,$salidaReporte,'sanidadVegetal');
													
													if($generarDocumento){
														$cr->guardarDocumentoOperador($conexion, $solicitud, $idOperadorTipoOperacion, $rutaArchivo, 'centroPropagacionViverista', $secuencial, $idOperador, 'Certificado de registro de centros de propagación de especies vegetales.');
													}
													
													$rutaArchivo = $constg::RUTA_SERVIDOR_OPT . '/' . $constg::RUTA_APLICACION . '/' .$rutaArchivo;
													
													//Firma Electrónica
													$parametrosFirma = array(
														'archivo_entrada'=>$rutaArchivo,
														'archivo_salida'=>$rutaArchivo,
														'identificador'=>$firmaResponsable['identificador'],
														'razon_documento'=>'Certificado de registro de centros de propagación de especies vegetales.',
														'tabla_origen'=>'g_operadores.documentos_operador',
														'campo_origen'=>'ruta_archivo',
														'id_origen'=>$solicitud,
														'estado'=>'Por atender',
														'proceso_firmado'=>'NO'
													);
													
													//Guardar registro para firma
													$cfd->ingresoFirmaDocumento($conexion, $parametrosFirma);
													
												break;
											}
										break;
									
										case 'SA':
											switch ($opcionArea){
												case 'MVB':
												case 'MVC':
												case 'MVE':
													$qOperaciones=$cr->buscarOperacionesPorCodigoyAreaOperacion($conexion, $idOperador,"('$opcionArea')","('$idArea')");
														
													if(pg_num_rows($qOperaciones)>0){
														$modulosAgregados.="('PRG_NOTIF_ENF'),";
														$perfilesAgregados.="('PFL_NOTIF_ENF'),";
													}														
												break;

												case 'CUA':
													
													$empleador = pg_fetch_assoc($cu->buscarUsuarioSistema($conexion, $inspector));
													$nombreEmpleado = $empleador['apellido'].' '.$empleador['nombre'];
																							
													$ReporteJasper= '/aplicaciones/registroOperador/reportes/cuarentena/certificadoCuarentenario.jrxml';
													$salidaReporte= '/aplicaciones/registroOperador/certificadoCuarentena/'.$idOperador.'_'.$idOperadorTipoOperacion.'.pdf';
													$rutaArchivo= 'aplicaciones/registroOperador/certificadoCuarentena/'.$idOperador.'_'.$idOperadorTipoOperacion.'.pdf';
																							
													$parameters['parametrosReporte'] = array(
														'id_operador_tipo_operacion'=> (int)$idOperadorTipoOperacion,
														'nombreTecnicoInspeccion'=> $nombreEmpleado
													);
													
													$jru->generarReporteJasper($ReporteJasper,$parameters,$conexion,$salidaReporte,'cuarentena');
													
													$existenciaDocumento = $cr->obtenerDocumentoGeneradoPorSecuencialIdentificadorOperadorTipoDocumento($conexion, $secuencial, $idOperador, 'cuarentena');
													if(pg_num_rows($existenciaDocumento) == 0){
														$cr->guardarDocumentoOperador($conexion, $solicitud, $idOperadorTipoOperacion, $rutaArchivo, 'cuarentena', $secuencial, $idOperador, 'Certificación de predio de cuarentena.');
													}
													
													//Tabla de firmas físicas
													$firmaResponsable = pg_fetch_assoc($cc->obtenerFirmasResponsablePorProvincia($conexion, $provinciaSitio, 'SA'));
													
													$rutaArchivo = $constg::RUTA_SERVIDOR_OPT . '/' . $constg::RUTA_APLICACION . '/' .$rutaArchivo;
													
													//Firma Electrónica
													$parametrosFirma = array(
														'archivo_entrada'=>$rutaArchivo,
														'archivo_salida'=>$rutaArchivo,
														'identificador'=>$firmaResponsable['identificador'],
														'razon_documento'=>'Certificación de predio de cuarentena.',
														'tabla_origen'=>'g_operadores.documentos_operador',
														'campo_origen'=>'ruta_archivo',
														'id_origen'=>$solicitud,
														'estado'=>'Por atender',
														'proceso_firmado'=>'NO'
													);
													
													//Guardar registro para firma
													$cfd->ingresoFirmaDocumento($conexion, $parametrosFirma);
			 
												break;

												case 'PRA':
												case 'SEA':
												case 'POA':
													$firmaResponsable = pg_fetch_assoc($cc->obtenerFirmasResponsablePorProvincia($conexion, $provinciaSitio));
													
													$ReporteJasper= '/aplicaciones/registroOperador/reportes/productorApicola/productorApicola.jrxml';
													$salidaReporte= '/aplicaciones/registroOperador/certificados/productorApicola/'.$idOperador.'_'.$idOperadorTipoOperacion.'.pdf';
													$rutaArchivo= 'aplicaciones/registroOperador/certificados/productorApicola/'.$idOperador.'_'.$idOperadorTipoOperacion.'.pdf';
													$rutaArchivoCodigoQr = 'http://181.112.155.173/'.$constg::RUTA_APLICACION.$salidaReporte;
													
													$parameters['parametrosReporte'] = array(
														'identificadorOperador'=> $idOperador,
														'rutaCertificado'=> $rutaArchivoCodigoQr,
														'fechaInicio'=> $fechaInicio
													);
													
													$jru->generarReporteJasper($ReporteJasper,$parameters,$conexion,$salidaReporte,'sanidadAnimal');
													
													if($generarDocumento){
														$cr->guardarDocumentoOperador($conexion, $solicitud, $idOperadorTipoOperacion, $rutaArchivo, date('Y').'-explotacionApicola', $secuencial, $idOperador, 'Certificado Zoosanitario de Producción Y Movilidad - Explotaciones Apícolas.');
													}
													
													if($opcionArea == 'PRA'){
														$modulosAgregados.="('PRG_CERT_BPA'),";
														$perfilesAgregados.="('PFL_USR_CERT_BPA'),";
													}
													
													$rutaArchivo = $constg::RUTA_SERVIDOR_OPT . '/' . $constg::RUTA_APLICACION . '/' .$rutaArchivo;
													
													//Firma Electrónica
													$parametrosFirma = array(
														'archivo_entrada'=>$rutaArchivo,
														'archivo_salida'=>$rutaArchivo,
														'identificador'=>$firmaResponsable['identificador'],
														'razon_documento'=>'Certificado Zoosanitario de Producción Y Movilidad - Explotaciones Apícolas.',
														'tabla_origen'=>'g_operadores.documentos_operador',
														'campo_origen'=>'ruta_archivo',
														'id_origen'=>$solicitud,
														'estado'=>'Por atender',
														'proceso_firmado'=>'NO'
													);
													
													//Guardar registro para firma
													$cfd->ingresoFirmaDocumento($conexion, $parametrosFirma);
													
												break;
												
												case 'COM':
												case 'IND':
												case 'PRO':
													
													$tipoSitio = htmlspecialchars($_POST['tipoSitio'], ENT_NOQUOTES, 'UTF-8');
													$coordenadax = htmlspecialchars($_POST['coordenadax'], ENT_NOQUOTES, 'UTF-8');
													$coordenaday = htmlspecialchars($_POST['coordenaday'], ENT_NOQUOTES, 'UTF-8');
													$zona = htmlspecialchars($_POST['zona'], ENT_NOQUOTES, 'UTF-8');
													$validacionSubtipoProducto = unserialize($_POST['validacionSubtipoProducto']);
													
													if (in_array(null, $validacionSubtipoProducto)) {
														
														$cr->actualizarCoordenadasTipoSitio($conexion, $tipoSitio, $coordenaday, $coordenadax, $zona, $idSitio);
														
														$firmaResponsable = pg_fetch_assoc($cc->obtenerFirmasResponsablePorProvincia($conexion, $provinciaSitio));

														$ReporteJasper= '/aplicaciones/registroOperador/reportes/centroPecuarioExportacion/centroPecuarioExportacion.jrxml';
														$salidaReporte= '/aplicaciones/registroOperador/certificados/centroPecuarioExportacion/'.$idOperador.'_'.$idOperadorTipoOperacion.'.pdf';
														$rutaArchivo= 'aplicaciones/registroOperador/certificados/centroPecuarioExportacion/'.$idOperador.'_'.$idOperadorTipoOperacion.'.pdf';
														
														$parameters['parametrosReporte'] = array(
															'idOperadorTipoOperacion'=> (int)$idOperadorTipoOperacion,
															'numeroCertificado'=> $codigo
														);
														
														$jru->generarReporteJasper($ReporteJasper,$parameters,$conexion,$salidaReporte,'centroPecuarioExportacion');
														
														if($generarDocumento){
															$cr->guardarDocumentoOperador($conexion, $solicitud, $idOperadorTipoOperacion, $rutaArchivo, $codigoProvinciaSitio.'-'.date('Y').'-centroPecuarioExportacion', $secuencial, $idOperador, 'Certificación de registro de mercancías pecuarias.');													
														}
													}
													
													$rutaArchivo = $constg::RUTA_SERVIDOR_OPT . '/' . $constg::RUTA_APLICACION . '/' .$rutaArchivo;
													
													//Firma Electrónica
													$parametrosFirma = array(
														'archivo_entrada'=>$rutaArchivo,
														'archivo_salida'=>$rutaArchivo,
														'identificador'=>$firmaResponsable['identificador'],
														'razon_documento'=>'Certificación de registro de mercancías pecuarias.',
														'tabla_origen'=>'g_operadores.documentos_operador',
														'campo_origen'=>'ruta_archivo',
														'id_origen'=>$solicitud,
														'estado'=>'Por atender',
														'proceso_firmado'=>'NO'
													);
													
													//Guardar registro para firma
													$cfd->ingresoFirmaDocumento($conexion, $parametrosFirma);
													
												break;
												
												case 'CPE':
												case 'EPE':
													$coordenadax = htmlspecialchars($_POST['coordenadax'], ENT_NOQUOTES, 'UTF-8');
													$coordenaday = htmlspecialchars($_POST['coordenaday'], ENT_NOQUOTES, 'UTF-8');
													$zona = htmlspecialchars($_POST['zona'], ENT_NOQUOTES, 'UTF-8');
													
													$cr->actualizarCoordenadasTipoSitio($conexion, $tipoSitio, $coordenaday, $coordenadax, $zona, $idSitio);
													
													$firmaResponsable = pg_fetch_assoc($cc->obtenerFirmasResponsablePorProvincia($conexion, $provinciaSitio));
													
													$ReporteJasper= '/aplicaciones/registroOperador/reportes/centroMercanciaPecuaria/centroMercanciaPecuaria.jrxml';
													$salidaReporte= '/aplicaciones/registroOperador/certificados/centroMercanciaPecuaria/'.$idOperador.'_'.$idOperadorTipoOperacion.'.pdf';
													$rutaArchivo= 'aplicaciones/registroOperador/certificados/centroMercanciaPecuaria/'.$idOperador.'_'.$idOperadorTipoOperacion.'.pdf';
													
													$parameters['parametrosReporte'] = array(
														'idOperadorTipoOperacion'=>(int)$idOperadorTipoOperacion,
														'numeroCertificado'=> $codigo
													);
													
													$jru->generarReporteJasper($ReporteJasper,$parameters,$conexion,$salidaReporte,'centroMercanciaPecuaria');
													
													if($generarDocumento){
														$cr->guardarDocumentoOperador($conexion, $solicitud, $idOperadorTipoOperacion, $rutaArchivo, $codigoProvinciaSitio.'-'.date('Y').'-centroMercanciaPecuaria', $secuencial, $idOperador, 'Certificación de registro de exportador de mercancías pecuarias.');
													}
																									$rutaArchivo = $constg::RUTA_SERVIDOR_OPT . '/' . $constg::RUTA_APLICACION . '/' .$rutaArchivo;
													
													//Firma Electrónica
													$parametrosFirma = array(
														'archivo_entrada'=>$rutaArchivo,
														'archivo_salida'=>$rutaArchivo,
														'identificador'=>$firmaResponsable['identificador'],
														'razon_documento'=>'Certificación de registro de exportador de mercancías pecuarias.',
														'tabla_origen'=>'g_operadores.documentos_operador',
														'campo_origen'=>'ruta_archivo',
														'id_origen'=>$solicitud,
														'estado'=>'Por atender',
														'proceso_firmado'=>'NO'
													);
													
													//Guardar registro para firma
													$cfd->ingresoFirmaDocumento($conexion, $parametrosFirma);
													
												break;
													
												case 'PMR':
												case 'CPM':
												case 'DMR':
												case 'AMR':
												
													// control de cambio material reproductivo
													$cr->actualizarTecnicoPlanificacionInspeccion($conexion,$tecnicoInspeccion,$idOperadorTipoOperacion);
													$cr->guardarResultadoRevision($conexion,$inspector,$idOperadorTipoOperacion,$resultado,$valorVigencia);
													$cr->quitarFechaFinalizacion($conexion, $idOperadorTipoOperacion);
													// fin control de cambio material reproductivo
													
													$firmaResponsable = pg_fetch_assoc($cc->obtenerFirmasResponsablePorProvincia($conexion, $provinciaSitio));

													$ReporteJasper= '/aplicaciones/registroOperador/reportes/centroReproduccionAnimal/centroReproduccionAnimal.jrxml';
													$salidaReporte= '/aplicaciones/registroOperador/certificados/centroReproduccionAnimal/'.$idOperador.'_'.$idOperadorTipoOperacion.'.pdf';
													$rutaArchivo= 'aplicaciones/registroOperador/certificados/centroReproduccionAnimal/'.$idOperador.'_'.$idOperadorTipoOperacion.'.pdf';

													$parameters['parametrosReporte'] = array(
														'idOperadorTipoOperacion'=> (int)$idOperadorTipoOperacion,
														'numeroCertificado'=> $codigo,
														'rutaCertificado'=> $constg::RUTA_DOMINIO.'/'.$constg::RUTA_APLICACION.'/'.$rutaArchivo
													);
													
													$jru->generarReporteJasper($ReporteJasper,$parameters,$conexion,$salidaReporte,'centroReproduccionAnimal');
													
													if($generarDocumento){
														$cr->guardarDocumentoOperador($conexion, $solicitud, $idOperadorTipoOperacion, $rutaArchivo, $codigoProvinciaSitio.'-centroReproduccionAnimal', $secuencial, $idOperador, 'Certificado zoosanitario de producción y movilidad para centros de material reproductivo.');
													}
													
													$rutaArchivo = $constg::RUTA_SERVIDOR_OPT . '/' . $constg::RUTA_APLICACION . '/' .$rutaArchivo;
													
													//Firma Electrónica
													$parametrosFirma = array(
														'archivo_entrada'=>$rutaArchivo,
														'archivo_salida'=>$rutaArchivo,
														'identificador'=>$firmaResponsable['identificador'],
														'razon_documento'=>'Certificado zoosanitario de producción y movilidad para centros de material reproductivo.',
														'tabla_origen'=>'g_operadores.documentos_operador',
														'campo_origen'=>'ruta_archivo',
														'id_origen'=>$solicitud,
														'estado'=>'Por atender',
														'proceso_firmado'=>'NO'
													);
													
													//Guardar registro para firma
													$cfd->ingresoFirmaDocumento($conexion, $parametrosFirma);
													
												break;
												
												case 'MPA':
												case 'ATM':
												case 'FEA':
												case 'FER':
													if($opcionArea == 'FEA'){
														
														$cantidadDias = $_POST['cantidadDias'];
														$fechaEvento = $_POST['fechaEvento'];

														$cr->actualizarFechaFinalizacionOperacionesAntiguos ($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion'], $fechaEvento, $cantidadDias, 'day', 0);
														$cr->guardarOperacionFechaEvento($conexion, $idOperadorTipoOperacion, $fechaEvento, $cantidadDias);
													}
													
													if($opcionArea == 'FER'){
														$qOperaciones=$cr->buscarOperacionesPorCodigoyAreaOperacion($conexion, $idOperador,"('$opcionArea')","('$idArea')");
														
														if(pg_num_rows($qOperaciones)>0){
															$modulosAgregados.="('PRG_MOVIL_PRODU'),";
															$perfilesAgregados.="('PFL_FISCA_MOVIL'),";
														}
													}
													
													$firmaResponsable = pg_fetch_assoc($cc->obtenerFirmasResponsablePorProvincia($conexion, $provinciaSitio));
													
													$ReporteJasper= '/aplicaciones/registroOperador/reportes/centroConcentracionAnimal/centroConcentracionAnimal.jrxml';
													$salidaReporte= '/aplicaciones/registroOperador/certificados/centroConcentracionAnimal/'.$idOperador.'_'.$idOperadorTipoOperacion.'.pdf';
													$rutaArchivo= 'aplicaciones/registroOperador/certificados/centroConcentracionAnimal/'.$idOperador.'_'.$idOperadorTipoOperacion.'.pdf';
													
													$parameters['parametrosReporte'] = array(
														'idOperacion'=> (int)$solicitud,
														'numeroCertificado'=> $codigo
													);
													
													$jru->generarReporteJasper($ReporteJasper,$parameters,$conexion,$salidaReporte,'sanidadAnimal');
													
													if($generarDocumento){
														$cr->guardarDocumentoOperador($conexion, $solicitud, $idOperadorTipoOperacion, $rutaArchivo, date('Y').'-concentracionAnimal', $secuencial, $idOperador, 'Permiso de concentración animal.');
													}
													
													$rutaArchivo = $constg::RUTA_SERVIDOR_OPT . '/' . $constg::RUTA_APLICACION . '/' .$rutaArchivo;
													
													//Firma Electrónica
													$parametrosFirma = array(
														'archivo_entrada'=>$rutaArchivo,
														'archivo_salida'=>$rutaArchivo,
														'identificador'=>$firmaResponsable['identificador'],
														'razon_documento'=>'Permiso de concentración animal.',
														'tabla_origen'=>'g_operadores.documentos_operador',
														'campo_origen'=>'ruta_archivo',
														'id_origen'=>$solicitud,
														'estado'=>'Por atender',
														'proceso_firmado'=>'NO'
													);
													
													//Guardar registro para firma
													$cfd->ingresoFirmaDocumento($conexion, $parametrosFirma);
													
												break;
												case 'OCC':
													require_once '../../clases/ControladorCatastroProducto.php';
													$ccp = new ControladorCatastroProducto();
													
													$qOperadorModificacionIdentificador = $ccp->buscarOperadorModificacionIdentificador($conexion, $idOperador);
													
													if(pg_num_rows($qOperadorModificacionIdentificador) == 0){
														$ccp->insertarOperadorModificacionIdentificador($conexion, $idOperador);
													}
												break;
												case 'OEC':
												    
												    $modulosAgregados.="('PRG_PAS_EQUI'),"; //Módulo pasaporte equino
												    $perfilesAgregados.="('PFL_USR_PAS_EQUI'),";
												    
												    $ReporteJasper= '/aplicaciones/registroOperador/reportes/organizacionEcuestre/organizacionEcuestre.jrxml';
												    $salidaReporte= '/aplicaciones/registroOperador/certificados/organizacionEcuestre/'.$idOperador.'_'.$idOperadorTipoOperacion.'.pdf';
												    $rutaArchivo= 'aplicaciones/registroOperador/certificados/organizacionEcuestre/'.$idOperador.'_'.$idOperadorTipoOperacion.'.pdf';
												    
												    
												    //Tabla de firmas físicas
												    $firmaResponsable = pg_fetch_assoc($cc->obtenerFirmasResponsablePorProvincia($conexion, $provinciaSitio));
												    
												    $parameters['parametrosReporte'] = array(
												        'idSolicitud'=>(int)$idSolicitud
												    );
												    
												    $jru->generarReporteJasper($ReporteJasper,$parameters,$conexion,$salidaReporte,'organizacionEcuestre');
												    
												    if($generarDocumento){
												        $cr->guardarDocumentoOperador($conexion, $solicitud, $idOperadorTipoOperacion, $rutaArchivo, $codigoProvinciaSitio.'-'.'organizacionEcuestre', $secuencial, $idOperador, 'Permiso zoosanitario de producción y movilidad para el diligenciamiento del pasaporte equino.');
												    }
												    
												    $rutaArchivo = $constg::RUTA_SERVIDOR_OPT . '/' . $constg::RUTA_APLICACION . '/' .$rutaArchivo;
													
													//Firma Electrónica
												        $parametrosFirma = array(
												            'archivo_entrada'=>$rutaArchivo,
												            'archivo_salida'=>$rutaArchivo,
												            'identificador'=>$firmaResponsable['identificador'],
												            'razon_documento'=>'Permiso zoosanitario para pasaporte equino',
												            'tabla_origen'=>'g_operadores.documentos_operador',
												            'campo_origen'=>'ruta_archivo',
												            'id_origen'=>$idSolicitud,
												            'estado'=>'Por atender',
												            'proceso_firmado'=>'NO'
												        );
												        
												        //Guardar registro para firma
												        $cfd->ingresoFirmaDocumento($conexion, $parametrosFirma);
												    												    
												 break;
											}
											break;

											//TODO:REVISAR PARA QUE SE HABILITE EL MÓDULO
											case 'AI':
												
												$resultadoResponsable = $cef->devolverResponsable($conexion, $inspector);
												
												$nombreInspector = pg_fetch_assoc($cu->buscarUsuarioSistema($conexion, $inspector));

												if($resultadoResponsable['usuario']!='' && $resultadoResponsable['nombreArea']!=''){
													
													$responsable = $resultadoResponsable['usuario'];
													$areaResponsable = $resultadoResponsable['nombreArea'];
													
												}
												
												$identificadorArea = pg_fetch_result($cr->obtenerAreaXIdOperacion($conexion, $solicitud), 0, 'id_area');
												switch ($opcionArea){
												
													case 'ACO':
														
														$modulosAgregados.="('PRG_AUM_CAP_INST'),";
														$perfilesAgregados.="('PFL_AUM_CAP_INST'),";
															
														//GENERAR PDF															
														$secuencial = pg_fetch_assoc($cr->obtenerMaximoDocumentoOperador($conexion, $idOperador, 'registroOperadorLeche'));
														$secuencial = $secuencial['secuencial'];

														$codigoCertificadoLeche = $cr->crearCodigoOperadorLeche($conexion, $opcionArea, $codigoProvinciaSitio, $identificadorArea, $idOperador);
														
														$ReporteJasper= '/aplicaciones/registroOperador/reportes/registroOperadorLeche/reporteRegistroOperadorLeche.jrxml';
														$salidaReporte= '/aplicaciones/registroOperador/certificadoRegistroOperadorLeche/'.$idOperador.'_'.$idOperadorTipoOperacion.'_'.date("Y-m-d-h-m-s").'.pdf';
														$rutaArchivo= 'aplicaciones/registroOperador/certificadoRegistroOperadorLeche/'.$idOperador.'_'.$idOperadorTipoOperacion.'_'.date("Y-m-d-h-m-s").'.pdf';
															
														$parameters['parametrosReporte'] = array(
															'idOperadorTipoOperacion'=> (int)$idOperadorTipoOperacion,
															'codigoCertificadoLeche'=> $codigoCertificadoLeche,
															'nombreTecnico'=> $nombreInspector['apellido'].' '.$nombreInspector['nombre']
														);
														
														$jru->generarReporteJasper($ReporteJasper,$parameters,$conexion,$salidaReporte,'operadorLeche');
														
														$cr->actualizarEstadoDocumentoOperador($conexion, $idOperador, $idOperadorTipoOperacion, 'inactivo');
														$cr->guardarDocumentoOperador($conexion, $solicitud, $idOperadorTipoOperacion, $rutaArchivo, 'registroOperadorLeche', $secuencial, $idOperador, 'Certificación de registro de operador de leche');
																												
														$cr->actualizarCentrosAcopioInspeccion($conexion, $idOperadorTipoOperacion, $inspector, 'sistemaGUIA', 'generado');
														//Tabla de firmas físicas
														$firmaResponsable = pg_fetch_assoc($cc->obtenerFirmasResponsablePorProvincia($conexion, $provinciaSitio, 'AI'));
														
														$rutaArchivo = $constg::RUTA_SERVIDOR_OPT . '/' . $constg::RUTA_APLICACION . '/' .$rutaArchivo;
														
														//Firma Electrónica
														$parametrosFirma = array(
															'archivo_entrada'=>$rutaArchivo,
															'archivo_salida'=>$rutaArchivo,
															'identificador'=>$firmaResponsable['identificador'],
															'razon_documento'=>'Certificación de registro de operador de leche',
															'tabla_origen'=>'g_operadores.documentos_operador',
															'campo_origen'=>'ruta_archivo',
															'id_origen'=>$solicitud,
															'estado'=>'Por atender',
															'proceso_firmado'=>'NO'
														);
														
														//Guardar registro para firma
														$cfd->ingresoFirmaDocumento($conexion, $parametrosFirma);
														
													break;
													
													case 'MDT':	
													
														$modulosAgregados.="('PRG_AUM_CAP_INST'),";
														$perfilesAgregados.="('PFL_AUM_CAP_INST'),";
														
														//GENERAR PDF
														$secuencial = pg_fetch_assoc($cr->obtenerMaximoDocumentoOperador($conexion, $idOperador, 'registroOperadorLecheVehiculo'));
														$secuencial = $secuencial['secuencial'];
														
														$codigoCertificadoLeche = $cr->crearCodigoOperadorLeche($conexion, $opcionArea, $codigoProvinciaSitio, $identificadorArea, $idOperador);	
														
														$ReporteJasper= '/aplicaciones/registroOperador/reportes/registroOperadorLeche/reporteRegistroOperadorLecheVehiculo.jrxml';
														$salidaReporte= '/aplicaciones/registroOperador/certificadoRegistroOperadorLeche/'.$idOperador.'_'.$idOperadorTipoOperacion.'_'.date("Y-m-d-h-m-s").'.pdf';
														$rutaArchivo= 'aplicaciones/registroOperador/certificadoRegistroOperadorLeche/'.$idOperador.'_'.$idOperadorTipoOperacion.'_'.date("Y-m-d-h-m-s").'.pdf';
															
														$parameters['parametrosReporte'] = array(
															'idOperadorTipoOperacion'=> (int)$idOperadorTipoOperacion,
															'codigoCertificadoLeche'=> $codigoCertificadoLeche,
															'nombreTecnico'=> $nombreInspector['apellido'].' '.$nombreInspector['nombre']
														);
														
														$jru->generarReporteJasper($ReporteJasper,$parameters,$conexion,$salidaReporte,'operadorLeche');
																				  
														$cr->actualizarEstadoDocumentoOperador($conexion, $idOperador, $idOperadorTipoOperacion, 'inactivo');
														$cr->guardarDocumentoOperador($conexion, $solicitud, $idOperadorTipoOperacion, $rutaArchivo, 'registroOperadorLecheVehiculo', $secuencial, $idOperador, 'Certificación de registro de operador de leche vehículo');
													
														$cr->inactivarVehiculoRecolectorXAreaXIdOperadorTipoOperacion($conexion, $identificadorArea, $idOperadorTipoOperacion);
														
														$cr->actualizarDatosVehiculoInspeccion($conexion, $idOperadorTipoOperacion, $inspector, 'sistemaGUIA', 'generado');
														
														//Tabla de firmas físicas
														$firmaResponsable = pg_fetch_assoc($cc->obtenerFirmasResponsablePorProvincia($conexion, $provinciaSitio, 'AI'));
														
														$rutaArchivo = $constg::RUTA_SERVIDOR_OPT . '/' . $constg::RUTA_APLICACION . '/' .$rutaArchivo;
														
														//Firma Electrónica
														$parametrosFirma = array(
															'archivo_entrada'=>$rutaArchivo,
															'archivo_salida'=>$rutaArchivo,
															'identificador'=>$firmaResponsable['identificador'],
															'razon_documento'=>'Certificación de registro de operador de leche vehículo',
															'tabla_origen'=>'g_operadores.documentos_operador',
															'campo_origen'=>'ruta_archivo',
															'id_origen'=>$solicitud,
															'estado'=>'Por atender',
															'proceso_firmado'=>'NO'
														);
														
														//Guardar registro para firma
														$cfd->ingresoFirmaDocumento($conexion, $parametrosFirma);
														
													break;
													case 'MDC':
													    
													    $modulosAgregados.="(''),";
													    $perfilesAgregados.="(''),";
													    
													    //GENERAR PDF
													    $secuencial = pg_fetch_assoc($cr->obtenerMaximoDocumentoOperador($conexion, $idOperador, 'registroOperadorTransporteCarnicosEstadoPrimario'));
													    $secuencial = $secuencial['secuencial'];
													    $codigoCertificado = 'AGR-MT-PSCEP-'.$codigoProvinciaSitio.'-'.str_pad($secuencial, 4, "0", STR_PAD_LEFT).'-'.date("Y");
													    $ReporteJasper= '/aplicaciones/registroOperador/reportes/transporteCarnicosEstadoPrimario/transporteCarnicosEstadoPrimario.jrxml';
													    $salidaReporte= '/aplicaciones/registroOperador/certificadoTransporteCarnicosEstadoPrimario/'.$idOperador.'_'.$idOperadorTipoOperacion.'_'.date("Y-m-d-h-m-s").'.pdf';
													    $rutaArchivo= 'aplicaciones/registroOperador/certificadoTransporteCarnicosEstadoPrimario/'.$idOperador.'_'.$idOperadorTipoOperacion.'_'.date("Y-m-d-h-m-s").'.pdf';
													    
														$rutaQR = $constg::RUTA_DOMINIO.'/'.$constg::RUTA_APLICACION.'/'.$rutaArchivo;
														
													   $parameters['parametrosReporte'] = array(
															'idOperadorTipoOperacion'=> (int)$idOperadorTipoOperacion,
															'codigoCertificado'=> $codigoCertificado,
															'rutaArchivoPdf'=> $rutaQR
														);
														
													    $jru->generarReporteJasper($ReporteJasper,$parameters,$conexion,$salidaReporte,'transporteCarnicos');
																		   
													    $cr->actualizarEstadoDocumentoOperador($conexion, $idOperador, $idOperadorTipoOperacion, 'inactivo');
													    $cr->guardarDocumentoOperador($conexion, $solicitud, $idOperadorTipoOperacion, $rutaArchivo, 'registroOperadorTransporteCarnicosEstadoPrimario', $secuencial, $idOperador, 'Certificación de Transporte Cárnicos en Estado Primario');
													    
													    $cr->inactivarVehiculoRecolectorXAreaXIdOperadorTipoOperacion($conexion, $identificadorArea, $idOperadorTipoOperacion);
													    
														//Tabla de firmas físicas
													    $firmaResponsable = pg_fetch_assoc($cc->obtenerFirmasResponsablePorProvincia($conexion, $provinciaSitio, 'AI'));
													    
													    $rutaArchivo = $constg::RUTA_SERVIDOR_OPT . '/' . $constg::RUTA_APLICACION . '/' .$rutaArchivo;
													    
													    //Firma Electrónica
													    $parametrosFirma = array(
													    	'archivo_entrada'=>$rutaArchivo,
													    	'archivo_salida'=>$rutaArchivo,
													    	'identificador'=>$firmaResponsable['identificador'],
													    	'razon_documento'=>'Certificación de Transporte Cárnicos en Estado Primario',
													    	'tabla_origen'=>'g_operadores.documentos_operador',
													    	'campo_origen'=>'ruta_archivo',
													    	'id_origen'=>$solicitud,
													    	'estado'=>'Por atender',
													    	'proceso_firmado'=>'NO'
													    );
													    
													    //Guardar registro para firma
													    $cfd->ingresoFirmaDocumento($conexion, $parametrosFirma);
														
													break;
												}
											break;
									}
								break;
							}
						}

						$cr->actualizarEstadoTipoOperacionPorIndentificadorSitio($conexion, $idOperadorTipoOperacion, $estado['estado']);
						
						if(strlen($modulosAgregados)==0){
							$modulosAgregados="''";
						}
						
						if(strlen($perfilesAgregados)==0){
						    $perfilesAgregados="''";
						}

						$qGrupoAplicacion=$cgap->obtenerGrupoAplicacion($conexion,'('.rtrim($modulosAgregados,',').')' );
						if(pg_num_rows($qGrupoAplicacion)>0){
							
							while($filaAplicacion=pg_fetch_assoc($qGrupoAplicacion)){							
								if(pg_num_rows($ca->obtenerAplicacionPerfil($conexion, $filaAplicacion['id_aplicacion'] , $idOperador))==0){
									$qAplicacionVacunacion=$cgap->guardarGestionAplicacion($conexion, $idOperador,$filaAplicacion['codificacion_aplicacion']);
									$qGrupoPerfiles=$cgap->obtenerGrupoPerfilXAplicacion($conexion, $filaAplicacion['id_aplicacion'], '('.rtrim($perfilesAgregados,',').')' );
									while($filaPerfil=pg_fetch_assoc($qGrupoPerfiles)){
										$cgap->guardarGestionPerfil($conexion, $idOperador,$filaPerfil['codificacion_perfil']);
									}
								}else{
									$qGrupoPerfiles=$cgap->obtenerGrupoPerfilXAplicacion($conexion, $filaAplicacion['id_aplicacion'], '('.rtrim($perfilesAgregados,',').')' );
									while($filaPerfil=pg_fetch_assoc($qGrupoPerfiles)){
										$qPerfil = $cu-> obtenerPerfilUsuario($conexion, $filaPerfil['id_perfil'], $idOperador);
										if (pg_num_rows($qPerfil) == 0)
											$cgap->guardarGestionPerfil($conexion, $idOperador,$filaPerfil['codificacion_perfil']);
									}
								}
							}
						}
					}else{
						
						foreach ($idGrupoSolicitudes as $solicitud){
							$operacion = pg_fetch_assoc($cr->abrirOperacionXid($conexion, $solicitud));
							$idOperadorTipoOperacion = $operacion['id_operador_tipo_operacion'];
							
							$qHistorialOperacion = $cr->obtenerMaximoIdentificadorHistoricoOperacion($conexion, $idOperadorTipoOperacion);
							$historialOperacion = pg_fetch_assoc($qHistorialOperacion);
							
							$cr->actualizarEstadoAnteriorPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion']);

							$cr->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion'], $resultado, $observaciones);
							
							$cr->actualizarEstadoTipoOperacionPorIndentificadorSitio($conexion, $idOperadorTipoOperacion, $resultado);


							// material reproductivo

							$resultado = htmlspecialchars ($_POST['resultado'],ENT_NOQUOTES,'UTF-8');	
							$qcodigoTipoOperacion = $cc->obtenerCodigoTipoOperacion($conexion, $solicitud);
							$opcionArea =  pg_fetch_result($qcodigoTipoOperacion, 0, 'codigo');
							$idArea =  pg_fetch_result($qcodigoTipoOperacion, 0, 'id_area');						
							
							if($resultado == 'subsanacionRepresentanteTecnico'){

								if($idArea=='SA') {
									switch ($opcionArea){
										case 'PMR':
										case 'CPM':
										case 'DMR':
										case 'AMR':

											$requiereAprobacion = $_POST['requiereAprobacion'];

											if($requiereAprobacion == 'si'){		
												$generarDocumento = true;
												
												$existenciaDocumento = $cr->obtenerDocumentoGeneradoInspeccionPorOperadorTipoOperacion($conexion, $idOperadorTipoOperacion);
												if(pg_num_rows($existenciaDocumento) == 0){
													$verificarExistenciaOperacion = $cr->obtenerIdOperadorTipoOperacionPorTipoOperacionAreaEstado($conexion, $idOperador, "('porCaducar', 'noHabilitado')", $operacion['id_tipo_operacion'], $idAreas[0]);
													if(pg_num_rows($verificarExistenciaOperacion) == 0){
														$secuencial = pg_fetch_assoc($cr->obtenerMaximoDocumentoOperador($conexion, '', $codigoProvinciaSitio.'-'.'centroReproduccionAnimal'));
														$secuencial = str_pad($secuencial['secuencial'], 3, '0', STR_PAD_LEFT);														
													}else{
														$existenciaDocumento = $cr->obtenerDocumentoGeneradoInspeccionPorOperadorTipoOperacion($conexion, pg_fetch_result($verificarExistenciaOperacion, 0, 'id_operador_tipo_operacion'));
														if(pg_num_rows($existenciaDocumento) != 0){
															$generarDocumento = false;
															$secuencial = str_pad(pg_fetch_result($existenciaDocumento, 0, 'secuencial'), 3, '0', STR_PAD_LEFT);															
														}else{
															$secuencial = pg_fetch_assoc($cr->obtenerMaximoDocumentoOperador($conexion, '', $codigoProvinciaSitio.'-centroReproduccionAnimal'));
															$secuencial = str_pad($secuencial['secuencial'], 3, '0', STR_PAD_LEFT);															
														}
													}
												}else{
													$generarDocumento = false;
													$secuencial = str_pad(pg_fetch_result($existenciaDocumento, 0, 'secuencial'), 3, '0', STR_PAD_LEFT);													
												}

												$valorVigencia= $_POST['tiempoAprobacion'];
												$qHistorialOperacion = $cr->obtenerMaximoIdentificadorHistoricoOperacion($conexion, $idOperadorTipoOperacion);
												$historialOperacion = pg_fetch_assoc($qHistorialOperacion);
												$cr->actualizarFechaFinalizacionOperacionesNuevos($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion'], $valorVigencia, 'month');//TODO:CAMBIADO
												
												$secuencial = pg_fetch_assoc($cr->obtenerMaximoDocumentoOperador($conexion, '', $codigoProvinciaSitio.'-'.'centroReproduccionAnimal'));
												$secuencial = str_pad($secuencial['secuencial'], 3, '0', STR_PAD_LEFT);
												$codigo = 'CMR-'.($opcionArea == 'PMR'?'CCPD':($opcionArea == 'CPM'?'UMCP':($opcionArea == 'DMR'?'CADC':'UAIA'))).'-'.str_pad($codigoProvinciaSitio, 3, '0', STR_PAD_LEFT).'-'.$secuencial;

												$tiempoAprobacion= $_POST['tiempoAprobacion'];

												$firmaResponsable = pg_fetch_assoc($cc->obtenerFirmasResponsablePorProvincia($conexion, $provinciaSitio));												

												$ReporteJasper= '/aplicaciones/registroOperador/reportes/centroReproduccionAnimal/centroReproduccionAnimal.jrxml';
												$salidaReporte= '/aplicaciones/registroOperador/certificados/centroReproduccionAnimal/'.$idOperador.'_'.$idOperadorTipoOperacion.'.pdf';
												$rutaArchivo= 'aplicaciones/registroOperador/certificados/centroReproduccionAnimal/'.$idOperador.'_'.$idOperadorTipoOperacion.'.pdf';		
												//$rutaSubreporte = $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/aplicaciones/registroOperador/reportes/centroReproduccionAnimal/';										

			
												$parameters['parametrosReporte'] = array(
													'idOperadorTipoOperacion'=> (int)$idOperadorTipoOperacion,
													'numeroCertificado'=> $codigo,
													'rutaCertificado'=> $constg::RUTA_DOMINIO.'/'.$constg::RUTA_APLICACION.'/'.$rutaArchivo
												);
												
												$jru->generarReporteJasper($ReporteJasper,$parameters,$conexion,$salidaReporte,'centroReproduccionAnimal');

												if($generarDocumento){													
													$cr->guardarDocumentoOperador($conexion, $solicitud, $idOperadorTipoOperacion, $rutaArchivo, $codigoProvinciaSitio.'-centroReproduccionAnimal', $secuencial, $idOperador, 'Certificado zoosanitario de producción y movilidad para centros de material reproductivo.');
												}
												
												$rutaArchivo = $constg::RUTA_SERVIDOR_OPT . '/' . $constg::RUTA_APLICACION . '/' .$rutaArchivo;
												
												//Firma Electrónica
												$parametrosFirma = array(
													'archivo_entrada'=>$rutaArchivo,
													'archivo_salida'=>$rutaArchivo,
													'identificador'=>$firmaResponsable['identificador'],
													'razon_documento'=>'Certificado zoosanitario de producción y movilidad para centros de material reproductivo.',
													'tabla_origen'=>'g_operadores.documentos_operador',
													'campo_origen'=>'ruta_archivo',
													'id_origen'=>$solicitud,
													'estado'=>'Por atender',
													'proceso_firmado'=>'NO'
												);
												
												//Guardar registro para firma
												$cfd->ingresoFirmaDocumento($conexion, $parametrosFirma);
												
												}

											$cr->actualizarTecnicoPlanificacionInspeccion($conexion,$tecnicoInspeccion,$idOperadorTipoOperacion);
											$cr->guardarResultadoRevision($conexion,$inspector,$idOperadorTipoOperacion,$resultado,$valorVigencia);
										break;
									}
								}
							}
							// fin material reproductivo
							
						}
						if(($opcionArea ='MDT' || $opcionArea ='MDC') && ($resultado=='noHabilitado')){	
							$cro = new ControladorRegistroOperador();
							$cro->inactivarVehiculo($conexion, $idOperadorTipoOperacion);
						}
					}
										
					$mensaje['estado'] = 'exito';
					$mensaje['mensaje'] = 'La operación se ha guardado satisfactoriamente';
						
				}else{
					$mensaje['estado'] = 'error';
					$mensaje['mensaje'] = 'Debe seleccionar por lo menos un elemento.';
				}
					
				break;
			}
				
			case 'DDA' :
				$cd = new ControladorDestinacionAduanera();
					
				/*$fechaEmbarque = htmlspecialchars ($_POST['fechaEmbarque'],ENT_NOQUOTES,'UTF-8');
				$fechaArribo= htmlspecialchars ($_POST['fechaArribo'],ENT_NOQUOTES,'UTF-8');
				$numeroContenedores = htmlspecialchars ($_POST['numeroContenedores'],ENT_NOQUOTES,'UTF-8');*/
				$pesoProductos =  $_POST['pesoProducto'];
				$sumaPeso = array_sum($pesoProductos);
					
				//$numeroContenedores = $numeroContenedores == '' ? 'null' : $numeroContenedores;
					
				//$cd->actualizarDatosInspeccionDDA($conexion, $idSolicitud, $fechaEmbarque, $fechaArribo, $numeroContenedores,$sumaPeso);
				$cd->actualizarPesoInspeccionDDA($conexion, $idSolicitud, $sumaPeso);
					
				$idInspeccion =  $crs->guardarDatosInspeccionElementos($conexion, pg_fetch_result($inspectorAsignado, 0, 'id_grupo'), $inspector, $archivo, $resultado, pg_fetch_result($ordenInspeccion, 0, 'orden'), $observaciones);
					
				if(count($listaElementos)>0){
					for ($i=0; $i<count($listaElementos);$i++){
						//Guarda inspector, calificación y fecha
						//$crs->guardarDatosInspeccionElementos($conexion, pg_fetch_result($inspectorAsignado, 0, 'id_grupo'), $inspector, $listaElementos[$i], $archivo, $resultado, $observaciones, $tipoElemento, pg_fetch_result($ordenInspeccion, 0, 'orden'));
						$crs->guardarDatosInspeccionObservaciones($conexion, pg_fetch_result($idInspeccion, 0, 'id_inspeccion'), $listaElementos[$i], $observaciones, $tipoElemento, $idSolicitud);
						//Guarda estado de elementos a evaluar
						$cd->evaluarProductosDDA($conexion, $idSolicitud, $listaElementos[$i], $resultado, $observaciones, $archivo, $pesoProductos[$i]);
					}
						
					//Consulta el estado de los productos evaluados
					$productos = $cd->abrirProductosDDA($conexion, $idSolicitud);

					$observacionProducto = '';
					foreach ($productos as $producto){
						if($producto['estado']=='aprobado'){
							$aprobado ++;
						}else if($producto['estado']=='rechazado' || $producto['estado']=='subsanacion'){
							$observacionTemporal[] = $producto['observacion'];
						}
					}

					if(count($observacionTemporal)!=0){
						$observacionProducto = array_unique($observacionTemporal);
						$observaciones = implode(", ", $observacionProducto);
					}

						
					if($aprobado == count($productos) && $resultado == 'aprobado'){
						//Asigna el resultado de inspeccion de solicitud de importacion
						$cd->enviarDDA($conexion, $idSolicitud, 'aprobado');
						$cd->enviarFechaVigenciaDDA($conexion, $idSolicitud);
						if($idVue!=''){
							$cVUE->ingresarSolicitudesXatenderGUIA('101-024-REQ','320','21',$idVue, 'Por atender', $observaciones);
						}
						
						if($_POST['tipoCertificado']=='ANIMAL' && $_POST['requiereSeguimientoCuarentenario']=='t'){
							$cd->actualizarSeguimientoCuarentenario($conexion, $idSolicitud,$_POST['provincia']);
						}
							
						//Guarda numero de revision
						//$cd->evaluarProductosDDA($conexion, $idSolicitud, $listaElementos[$i], $resultado, $observaciones, $archivo);
					}
						
					if($resultado == 'subsanacion'){
						//Asigna el resultado de inspeccion de solicitud de importacion
						$cd->enviarDDA($conexion, $idSolicitud, 'subsanacion');
						if($idVue!=''){
							$cVUE->ingresarSolicitudesXatenderGUIA('101-024-REQ','410','21',$idVue, 'Por atender', $observaciones);
							$cd->actualizarContadorInspeccionDDA($conexion, $idSolicitud, 2);
						}
					}


					if($resultado == 'rechazado'){
						//Asigna el resultado de inspeccion de solicitud de importacion
						$cd->enviarDDA($conexion, $idSolicitud, 'rechazado');
						if($idVue!=''){
							$cVUE->ingresarSolicitudesXatenderGUIA('101-024-REQ','310','21',$idVue, 'Por atender', $observaciones);
						}
					}

					$mensaje['estado'] = 'exito';
					$mensaje['mensaje'] = 'La operación se ha guardado satisfactoriamente';
						
						
				}else{
					$mensaje['estado'] = 'error';
					$mensaje['mensaje'] = 'Debe seleccionar por lo menos un elemento.';
				}
					
				break;
					
			case 'Zoosanitario' :{
					
				$codigoSitio = htmlspecialchars ($_POST['codigoSitio'],ENT_NOQUOTES,'UTF-8');
				$fechaInspeccion = htmlspecialchars ($_POST['fechaInspeccion'],ENT_NOQUOTES,'UTF-8');
				$listaAreas = ($_POST['listaAreas']);
					
					
				$cz = new ControladorZoosanitarioExportacion();
				$cr = new ControladorRegistroOperador();
					
				$qSitio = $cr->buscarSitios($conexion, $idOperador, $codigoSitio);
					
				if(count($listaElementos)>0){

					for ($i=0; $i<count($listaAreas);$i++){
						//Guarda estado de productos y áreas a evaluar
						$cz->evaluarProductosAreasZoo($conexion, $idSolicitud, pg_fetch_result($qSitio, 0, 'id_sitio'), $listaAreas[$i], $listaElementos[$i], $resultado, $observaciones);
					}

					$idInspeccion =  $crs->guardarDatosInspeccionElementos($conexion, pg_fetch_result($inspectorAsignado, 0, 'id_grupo'), $inspector, $archivo, $resultado, pg_fetch_result($ordenInspeccion, 0, 'orden'), $observaciones);

					$elementosFiltrados = array_unique($listaElementos);

					for ($i=0; $i<count($elementosFiltrados);$i++){
						//Guarda inspector, calificación y fecha
						//$crs->guardarDatosInspeccionElementos($conexion, pg_fetch_result($inspectorAsignado, 0, 'id_grupo'), $inspector, $elementosFiltrados[$i], $archivo, $resultado, $observaciones, $tipoElemento, pg_fetch_result($ordenInspeccion, 0, 'orden'));
						$crs->guardarDatosInspeccionObservaciones($conexion, pg_fetch_result($idInspeccion, 0, 'id_inspeccion'), $elementosFiltrados[$i], $observaciones, $tipoElemento, $idSolicitud);
						//Guarda estado de elementos a evaluar
						$cz->evaluarProductosZoo($conexion, $idSolicitud, $elementosFiltrados[$i], $resultado, $observaciones, $archivo);
					}

					$cz->actualizarFechaInspeccion($conexion, $idSolicitud, $fechaInspeccion, $observaciones);
						
					//Consulta el estado de los productos evaluados
					$productos = $cz->abrirProductosZoo($conexion, $idSolicitud);
						
					foreach ($productos as $producto){
						if($producto['estado']=='aprobado'){
							$aprobado ++;
						}else if($producto['estado']=='rechazado'){
							$rechazado ++;
						}else if($producto['estado']=='subsanacion'){
							$subsanacion ++;
						}else if($producto['estado']==''){
							$blanco ++;
						}
					}
						
					if($aprobado == count($productos)){
						//Asigna el resultado de inspeccion de solicitud zoosanitario
						$cz->enviarZoo($conexion, $idSolicitud, 'aprobado');
						$cz->actualizarFechaZoosanitario($conexion, $idSolicitud);
						if($idVue!=''){
							$cVUE->ingresarSolicitudesXatenderGUIA('101-008-REQ','320','21',$idVue, 'Por atender', $observaciones);
						}
					}

					if($subsanacion != 0){
						//Asigna el resultado de inspeccion de solicitud zoosanitario
						$cz->enviarZoo($conexion, $idSolicitud, 'subsanacion');
						if($idVue!=''){
							$cVUE->ingresarSolicitudesXatenderGUIA('101-008-REQ','410','21',$idVue, 'Por atender', $observaciones);
						}
					}

					if(($rechazado != 0) && ($blanco == 0) && ($subsanacion == 0)){
						//Asigna el resultado de inspeccion de solicitud de importacion
						$cz->enviarZoo($conexion, $idSolicitud, 'rechazado');
						if($idVue!=''){
							$cVUE->ingresarSolicitudesXatenderGUIA('101-008-REQ','310','21',$idVue, 'Por atender', $observaciones);
						}
					}

					$mensaje['estado'] = 'exito';
					$mensaje['mensaje'] = 'La operación se ha guardado satisfactoriamente';
						
				}else if($resultado == 'subsanacion'){
						
					//Asigna el resultado de inspeccion de solicitud zoosanitario
					$cz->enviarZoo($conexion, $idSolicitud, 'subsanacion');
					if($idVue!=''){
						$cVUE->ingresarSolicitudesXatenderGUIA('101-008-REQ','410','21',$idVue, 'Por atender', $observaciones);
					}

					$mensaje['estado'] = 'exito';
					$mensaje['mensaje'] = 'La operación se ha guardado satisfactoriamente';

				}else{
					$mensaje['estado'] = 'error';
					$mensaje['mensaje'] = 'Debe seleccionar por lo menos un producto y un área de inspección.';
				}
				break;
			}

			case 'certificadoCalidad':
					
				$cca = new ControladorCertificadoCalidad();
					
				$idInspeccion =  $crs->guardarDatosInspeccionElementos($conexion, pg_fetch_result($inspectorAsignado, 0, 'id_grupo'), $inspector, '0', $resultado, pg_fetch_result($ordenInspeccion, 0, 'orden'), $observaciones);
					
				foreach ($idGrupoSolicitudes as $solicitud){
					//$crs->guardarDatosInspeccionElementos($conexion, pg_fetch_result($inspectorAsignado, 0, 'id_grupo'), $inspector, $solicitud, '', $resultado, $observaciones, $tipoElemento, pg_fetch_result($ordenInspeccion, 0, 'orden'));
					$crs->guardarDatosInspeccionObservaciones($conexion, pg_fetch_result($idInspeccion, 0, 'id_inspeccion'), $solicitud, $observaciones, $tipoElemento);
					$cca->actualizarEstadoLoteInspector($conexion, $solicitud, $resultado);
				}
					
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = 'La operación se ha guardado satisfactoriamente';
					
				break;

			case 'FitosanitarioExportacion':
				$cfe = new ControladorFitosanitarioExportacion();

				$idInspeccion =  $crs->guardarDatosInspeccionElementos($conexion, pg_fetch_result($inspectorAsignado, 0, 'id_grupo'), $inspector, $archivo, $resultado, pg_fetch_result($ordenInspeccion, 0, 'orden'), $observaciones);
				$crs->guardarDatosInspeccionObservaciones($conexion, pg_fetch_result($idInspeccion, 0, 'id_inspeccion'), $idSolicitud, $observaciones, $tipoElemento, $idSolicitud);
					
				$nombreInspector = pg_fetch_assoc($cu->buscarUsuarioSistema($conexion, $inspector));
					
				$cfe->actualizarArchivoInspeccionFitosanitarioExportacion($conexion, $idSolicitud, $archivo, $nombreInspector['apellido'].' '.$nombreInspector['nombre'], 'Inspector fitosanotario de exportación.', $observaciones);
				////////////////////////////////////////////////////////////////////////
					
				if($resultado =='pago' && $idVue!=''){

					$cVUE->ingresarSolicitudesXatenderGUIA('101-034-REQ','240','21',$idVue, 'Por atender', $observaciones);
						
					$producto = $cfe->obtenerProgramaProductosPorIdentificadorFitosanitarioExportacion($conexion, $idSolicitud);
						
					$arrayProductoFinanciero = array();
					$arrayProductoExoneracion = array();
					$productoMusaceas = true;
					$productoOrnamentales = true;
					$productoMango = false;
					$banderaFito = false;
						
					while ($fila = pg_fetch_assoc($producto)){
							
						$partidaArancelaria =  $fila['partida_arancelaria'];
							
						if($productoMusaceas && ($partidaArancelaria == '0803101000' || $partidaArancelaria == '0803901110' || $partidaArancelaria == '0803901200' || $partidaArancelaria == '0803901900' || $partidaArancelaria == '0803901190')){
							$productoMusaceas = false;
							$banderaFito = true;
							$arrayProductoFinanciero[] = array(idProducto => $fila['id_producto'] , nombreProducto => $fila['nombre_comun'], cantidadCobro=> 1, unidadCobro => $fila['unidad_cobro'], exoneracion=> $fila['exoneracion']);
						}else if($productoOrnamentales && ($partidaArancelaria == '0603110000' || $partidaArancelaria == '0603121000' || $partidaArancelaria == '0603129000' || $partidaArancelaria == '0603130000'
								|| $partidaArancelaria == '0603141000' || $partidaArancelaria == '0603149000' || $partidaArancelaria == '0603150000' || $partidaArancelaria == '0603191000'
								|| $partidaArancelaria == '0603192000' || $partidaArancelaria == '0603193000' || $partidaArancelaria == '0603194000' || $partidaArancelaria == '0603199010'
								|| $partidaArancelaria == '0603199090' || $partidaArancelaria == '0603900000' || $partidaArancelaria == '0604200000' || $partidaArancelaria == '0604900000')){
							$productoOrnamentales = false;
							$banderaFito = true;
							$arrayProductoFinanciero[] = array(idProducto => $fila['id_producto'] , nombreProducto => $fila['nombre_comun'], cantidadCobro=> 1, unidadCobro => $fila['unidad_cobro'], exoneracion=> $fila['exoneracion']);
						}else if($partidaArancelaria == '0804502000'){
							$productoMango = true;
							$arrayProductoFinanciero[] = array(idProducto => $fila['id_producto'] , nombreProducto => $fila['nombre_comun'], cantidadCobro=> $fila['cantidad_cobro'], unidadCobro => $fila['unidad_cobro'], exoneracion=> $fila['exoneracion']);
						}else{
							if(!$banderaFito)
								$arrayProductoFinanciero[] = array(idProducto => $fila['id_producto'] , nombreProducto => $fila['nombre_comun'], cantidadCobro=> $fila['cantidad_cobro'], unidadCobro => $fila['unidad_cobro'], exoneracion=> $fila['exoneracion']);
						}
							
						$arrayProductoExoneracion[] = $fila['exoneracion'];
					}

					$arrayProductoExoneracionSinRepetidos = array_unique($arrayProductoExoneracion);
						
					$qFitosanitarioExportacion = $cfe->obtenerCabeceraFitosanitarioExportacion($conexion, $idSolicitud);
					$fitosanitarioExportacion = pg_fetch_assoc($qFitosanitarioExportacion);

					if(count($arrayProductoExoneracionSinRepetidos) == 1){
						if($arrayProductoExoneracionSinRepetidos[0] == 'SI'){
							$procesoPago = false;
						}else{
							$procesoPago = true;
						}
					}else{
						$procesoPago = true;
					}
						
					if($fitosanitarioExportacion['descuento'] == 'SI' || $productoMango){
						if($procesoPago){
							$cfe->actualizarEstadoFitosanitarioExportacion($conexion, $idSolicitud, 'pago', 'inspeccion', $observaciones);
						}else{
							$cfe->actualizarEstadoFitosanitarioExportacion($conexion, $idSolicitud, 'aprobado', 'inspeccion', 'Aprobado');
							$cfe->actualizarFechasAprobacionFitosanitarioExportacion($conexion, $idSolicitud);
							$cVUE->ingresarSolicitudesXatenderGUIA('101-034-REQ','320','21',$idVue, 'Pendiente', 'Aprobación automatica por exoneración.');
						}
							
					}else{
							
						if($procesoPago){

							$cff = new ControladorFinanciero();
							$cfa = new ControladorFinancieroAutomatico();
							$itemTarifario = array();
							$totalOrden = 0;
								
							foreach ($arrayProductoFinanciero as $productoFinanciero){

								$productoUnidadCobro = pg_fetch_assoc($cff->obtenerUnidadMedidaCobro($conexion, $productoFinanciero['idProducto'], 'Fitosanitario'));

								$cantidadProducto = $productoFinanciero['cantidadCobro'];
								$cantidadProductoTarifario = $productoUnidadCobro['unidad'];
								$valorCantidadProductoTarifario = $productoUnidadCobro['valor'];

								if($productoUnidadCobro['cobro_exceso'] == 'No'){
									$valorProductoSinExceso = $cantidadProducto * $valorCantidadProductoTarifario;
									$valorProductoSinExceso = $productoFinanciero['exoneracion'] == 'NO'? $valorProductoSinExceso: 0;
								}else{
									$valorProductoSinExceso  = $valorCantidadProductoTarifario;
									$valorProductoSinExceso = $productoFinanciero['exoneracion'] == 'NO'? $valorProductoSinExceso: 0;

									$cantidadProductoExceso = $cantidadProducto - $cantidadProductoTarifario;

									if($cantidadProductoExceso != 0){
										$itemExceso = pg_fetch_assoc($cff->obtenerServicio($conexion, $productoUnidadCobro['id_servicio_exceso']));
										$valorProdutoConExceso = round(($cantidadProductoExceso*$itemExceso['valor'])/$itemExceso['unidad'],2);
										$valorProdutoConExceso = $productoFinanciero['exoneracion'] == 'NO'? $valorProdutoConExceso: 0;
										$itemTarifario[] = array(idServicio => $itemExceso['id_servicio'], conceptoServicio => $itemExceso['concepto'], cantidad => $cantidadProductoExceso, valorUnitario => $itemExceso['valor'], descuento => '0', iva => '0', total => $valorProdutoConExceso);
									}
								}

								$itemTarifario[] = array(idServicio => $productoUnidadCobro['id_servicio'], conceptoServicio => $productoUnidadCobro['concepto'], cantidad => $cantidadProductoTarifario, valorUnitario => $valorCantidadProductoTarifario, descuento => '0', iva => '0', total => $valorProductoSinExceso);

								$totalOrden += $valorProductoSinExceso + $valorProdutoConExceso;
							}
								
							$idFinancieroCabecera = pg_fetch_result($cfa->guardarFinancieroAutomaticoCabecera($conexion, $totalOrden, $fitosanitarioExportacion['id_vue'], 'FitosanitarioExportacion'), 0, 'id_financiero_cabecera');
								
							foreach ($itemTarifario as $item){
								$cfa->guardarFinancieroAutomaticoDetalle($conexion, $idFinancieroCabecera, $item['idServicio'], $item['conceptoServicio'], $item['cantidad'], $item['valorUnitario'], $item['descuento'], $item['iva'], $item['total']);
							}
								
							$cfa->actualizarEstadoFinancieroAutomaticoCabecera($conexion, $idFinancieroCabecera, 'Por atender');
							$cfe->actualizarEstadoFitosanitarioExportacion($conexion, $idSolicitud, 'verificacionAutomatica', 'inspeccion', $observaciones);

						}else{
							$cfe->actualizarEstadoFitosanitarioExportacion($conexion, $idSolicitud, 'aprobado', 'inspeccion', 'Aprobado');
							$cfe->actualizarFechasAprobacionFitosanitarioExportacion($conexion, $idSolicitud);
							$cVUE->ingresarSolicitudesXatenderGUIA('101-034-REQ','320','21',$idVue, 'Pendiente', 'Aprobación automatica por exoneración.');
						}
					}
				}else if($resultado =='subsanacion' && $idVue!=''){
					$cVUE->ingresarSolicitudesXatenderGUIA('101-034-REQ','410','21',$idVue, 'Por atender', $observaciones);
				}else if($resultado =='rechazado' && $idVue!=''){
					$cVUE->ingresarSolicitudesXatenderGUIA('101-034-REQ','310','21',$idVue, 'Por atender', $observaciones);
				}
					
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = 'La operación se ha guardado satisfactoriamente';

			break;
			
			case 'certificacionBPA':
				
				$ccb = new ControladorCertificacionBPA();
				
				$checklist = '0';
				
				$solicitudBPA = pg_fetch_assoc($ccb->abrirSolicitud($conexion, $idSolicitud));
				
				$ccb->actualizarEstadoSolicitud($conexion, $idSolicitud, $resultado);
				$ccb->actualizarObservacionRevision($conexion, $idSolicitud, $observaciones);
				$ccb->actualizarDatosRevision($conexion, $idSolicitud, $tipoInspector);
				
				//Verificar si hay checklist sino 0
				if(isset($_POST['ruta_checklist'])){
				    $checklist = $_POST['ruta_checklist'];
				}
				
				$crs->guardarDatosInspeccionElementos($conexion, pg_fetch_result($inspectorAsignado, 0, 'id_grupo'), $inspector, $checklist, $resultado, pg_fetch_result($ordenInspeccion, 0, 'orden'), $observaciones);
				
				if($_POST['tipoInspector'] == 'Técnico'){
				    //revisar si hay datos en la auditoria realizada, sino guardar en el
				    //campo de auditoria complementaria,
				    //*verificando si entre las auditorias solicitadas hay pedido para una complementaria
				    
				    if($solicitudBPA['fecha_auditoria'] == null){
				         $ccb->actualizarFechaAuditoriaEjecutadaSolicitud($conexion, $idSolicitud, $_POST['fechaAuditoriaRealizada']);
				    }else{
				         $auditoriaComplementaria = $ccb->buscarAuditoriasSolicitadas($conexion, $idSolicitud, 'Complementaria');
				     
    				     if(pg_num_rows($auditoriaComplementaria) > 0){
    				        $ccb->actualizarFechaAuditoriaComplementariaSolicitud($conexion, $idSolicitud, $_POST['fechaAuditoriaRealizada']);
    				     }/*else{
    				         $ccb->actualizarFechaAuditoriaEjecutadaSolicitud($conexion, $idSolicitud, $_POST['fechaAuditoriaRealizada']);
    				     }*/
				    }
				    
				    $ccb->actualizarPorcentajeAuditoria($conexion, $idSolicitud, $_POST['porcentajeAuditoria']);
				
    				if($_POST['ruta_plan'] != '' || $_POST['ruta_plan'] != '0'){
    					$ccb->actualizarRutaFormatoPlan($conexion, $idSolicitud, $_POST['ruta_plan']);
    				}
    				
    				if($_POST['ruta_checklist'] != '' || $_POST['ruta_checklist'] != '0'){
    				    $ccb->actualizarRutaChecklist($conexion, $idSolicitud, $_POST['ruta_checklist']);
    				}
    				
    				//Registra la fecha máxima en la que el usuario debe dar respuesta a la subsanación solicitada
    				if($resultado == 'subsanacion'){
    				    $fechaMaxRespuesta = $ccb->sumaDiaSemana(date("Y-m-d"),15);
    				    $ccb->actualizarFechaMaxRespuesta($conexion, $idSolicitud, $fechaMaxRespuesta);
    				}
				}
				
				//Generación del certificado
				if($resultado == 'Aprobado'){					
					$solicitudBPA = $ccb->abrirSolicitud($conexion, $idSolicitud);
				    
				    $fechaAuditoriaReal = pg_fetch_result($solicitudBPA, 0, 'fecha_auditoria');
				    $fechaAuditoriaComplementaria = pg_fetch_result($solicitudBPA, 0, 'fecha_auditoria_complementaria');
				    
				    if($fechaAuditoriaComplementaria != null){
				        $fechaAuditoria = $fechaAuditoriaComplementaria;
				    }else{
				        $fechaAuditoria = $fechaAuditoriaReal;
				    }
					
				    //Cambiar de estado a los sitios de la solicitud
				    $ccb->actualizarEstadoSitiosSolicitud($conexion, $idSolicitud, $resultado);
				    
				    //poner las fechas de aprobacion de inicio y fin (3 años)
				    $ccb->generarFechasVigencia($conexion, $idSolicitud, $_POST['tipo_solicitud'], $fechaAuditoria);
				    
				    //crear el numero de certificado y guardar en el registro (crear funcion de numero certificado y de actualizar en registro
				    $certificado = '';
				    
					
				    $tipoExplotacion = pg_fetch_result($solicitudBPA, 0, 'tipo_explotacion');
					$identificador = pg_fetch_result($solicitudBPA, 0, 'identificador_operador');
				    
				    switch($tipoExplotacion){
				        case 'SA':
				            $area= 'PP';
				            break;
				        case 'SV':
				            $area= 'PA';
				            break;
				        case 'AI':
				            $area= 'PO';
				            break;
				    }
				    
				    //verificar la provincia del tecnico y buscar en localizacion el nombre de la provincia y ubicar el numero de zona
				    //aumentar a dos digitos con ceros
				    $localizacion = pg_fetch_result($cc->obtenerZonaLocalizacion($conexion, $_SESSION['idProvincia'], 1), 0, 'zona');
				    $codigoLocalizacion = str_pad($localizacion, 2, "0", STR_PAD_LEFT);
				    
				    //buscar la combinacion del codigo hasta antes de la provincia y ver el numero para crear un secuencial
				    $anio = date("Y");
				    $formato = 'AGRO-CBPA-'.$area.'-'.$identificador;//.$anio.'-'
				    $secuencial = $ccb->generarNumeroCertificado($conexion, $formato);
				    
				    //generarNumeroCertificado
				    //AGRO-CBPA-PO-2020-05-00001
				    //$certificado = $formato . $codigoLocalizacion . '-' . $secuencial;
					$certificado = $formato;
				    
				    //guardar el código del certificado y el secuencial
				    $ccb->actualizarSecuencialCertificado($conexion, $idSolicitud, $secuencial, $certificado);
				    
				    //Creación de Certificado PDF
				    $jru = new ControladorReportes();
				    
				    //mandar las rutas al mvc para jrxml
				    $ReporteJasper= '/aplicaciones/mvc/modulos/CertificacionBPA/vistas/reportes/CertificadoNacional.jrxml';
				    $salidaReporte= '/aplicaciones/mvc/modulos/CertificacionBPA/archivos/certificados/bpa_'.$idSolicitud.'.pdf';
				    $rutaArchivo= 'aplicaciones/mvc/modulos/CertificacionBPA/archivos/certificados/bpa_'.$idSolicitud.'.pdf';
				    
				    $firmaResponsable = pg_fetch_assoc($cc->obtenerFirmasResponsablePorProvincia($conexion, $_SESSION['nombreProvincia'], 'AI'));
				    
				    $parameters['parametrosReporte'] = array(
				        'idSolicitud'=>(int)$idSolicitud,
				        'identificador'=>$firmaResponsable['identificador']
				    );
				    
				    $jru->generarReporteJasper($ReporteJasper,$parameters,$conexion,$salidaReporte,'CertificacionBPA');
				    $ccb->guardarRutaCertificado($conexion, $idSolicitud, $rutaArchivo);
				    
				    $rutaArchivo = $constg::RUTA_SERVIDOR_OPT . '/' . $constg::RUTA_APLICACION . '/' .$rutaArchivo;
				    
				    //Firma Electrónica
				    $parametrosFirma = array(
				        'archivo_entrada'=>$rutaArchivo,
				        'archivo_salida'=>$rutaArchivo,
				        'identificador'=>$firmaResponsable['identificador'],
				        'razon_documento'=>'Certificado BPA',
				        'tabla_origen'=>'g_certificacion_bpa.solicitudes',
				        'campo_origen'=>'ruta_certificado',
				        'id_origen'=>$idSolicitud,
				        'estado'=>'Por atender',
				        'proceso_firmado'=>'NO'
				    );
				    
				    //Guardar registro para firma
				    $cfd->ingresoFirmaDocumento($conexion, $parametrosFirma);
				
				}else if($resultado == 'Rechazado'){
				    //Cambiar de estado a los sitios de la solicitud
				    $ccb->actualizarEstadoSitiosSolicitud($conexion, $idSolicitud, $resultado);
				}
				
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = 'La operación se ha guardado satisfactoriamente';
				
			break;
			
			default :
			break;
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