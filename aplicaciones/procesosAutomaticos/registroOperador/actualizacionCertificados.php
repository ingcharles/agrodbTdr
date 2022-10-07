<?php
if($_SERVER['REMOTE_ADDR'] == ''){
//if(1){
	
	require_once '../../../clases/Conexion.php';
	require_once '../../../clases/ControladorReportes.php';
	require_once '../../../clases/ControladorRegistroOperador.php';
	require_once '../../../clases/ControladorUsuarios.php';
	require_once '../../../clases/ControladorMonitoreo.php';
	require_once '../../../clases/ControladorCatalogos.php';
	require_once '../../../aplicaciones/general/PDFMerger.php';
	require_once '../../../clases/ControladorFirmaDocumentos.php';

	$conexion = new Conexion();
	$cr = new ControladorRegistroOperador();
	$jru = new ControladorReportes(false);
	$cu = new ControladorUsuarios();
	$cm = new ControladorMonitoreo();
	$cc = new ControladorCatalogos();
	$pdf = new PDFMerger(false);
	$cfd = new ControladorFirmaDocumentos();

	set_time_limit(86000);

	define('PRO_MSG', '<br/> ');
	define('IN_MSG','<br/> >>> ');
	$fecha = date("Y-m-d h:m:s");
	$numero = '1';
	
	$resultadoMonitoreo = $cm->obtenerCronPorCodigoEstado($conexion, 'CRON_ACTU_CERT_OPER');
	if($resultadoMonitoreo){
	//if(1){

    	echo IN_MSG .'<b>INICIO PROCESO DE ACTUALIZACIÓN DE CERTIFICADOS '.$fecha.'</b>';
    		
    	
    	$qOperaciones = $cr->obtenerOperacionesActualizarCertificadoPorEstado($conexion, 'SI');
    
    	while($operaciones = pg_fetch_assoc($qOperaciones)){ 	    
    
    	    echo IN_MSG. $numero++ . '.- Identificador operador: ' . $operaciones['identificador_operador'];
    		
    	    $idOperador = $operaciones['identificador_operador'];
    	    $idOperadorTipoOperacion = $operaciones['id_operador_tipo_operacion'];
    	    $idTipoOperacion = $operaciones['id_tipo_operacion'];
    	    $idArea = $operaciones['id_area'];
    	    $opcionArea = $operaciones['codigo'];
			 $idOperacion = $operaciones['id_operacion'];
    	    
    	    $generarDocumento = true;
    
    	    $qHistorialOperacion = $cr->obtenerMaximoIdentificadorHistoricoOperacion($conexion, $idOperadorTipoOperacion);
    	    $historialOperacion = pg_fetch_assoc($qHistorialOperacion);
    		
    	    $idHistorialOperacion = $historialOperacion['id_historial_operacion'];
    	    
    	    $qInspector = $cr->obtenerInspectorUltimaRevisionDocumental($conexion, $idOperadorTipoOperacion, $idHistorialOperacion);
    	    $inspector = pg_fetch_result($qInspector, 0 , 'identificador_inspector');
    	    
    	    echo IN_MSG. 'Actualizacion a registro: idOperadorTipoOperacion: ' . $idOperadorTipoOperacion . ' - idHistorialOperacion: ' . $idHistorialOperacion;
    	    
    	    switch ($idArea) {
    	        case 'AI':
    	            
    	            $cu = new ControladorUsuarios();
    	            
    	            //TODO:Buscar el último inspector que realizó revisión documental	            
    	            $nombreInspector = pg_fetch_assoc($cu->buscarUsuarioSistema($conexion, $inspector));
    	            $fechaCreacion = pg_fetch_result($cr->obtenerFechaCreacionRegistroOrganicos($conexion, $idOperador, $idTipoOperacion), 0, 'fecha_creacion');
    	            $fechaActualizacion = pg_fetch_result($cr->obtenerFechaCreacionRegistroOrganicos($conexion, $idOperador, $idTipoOperacion), 0, 'fecha_actualizacion');
    	            
    	            switch ($opcionArea) {
    	                case 'PRO':
    	                case 'REC':
    	                    
    	                    $qOperacionesXIdOperadorXIdTipoOperacion = $cr->obtenerOperacionesXOperadorXIdTipoOperacion($conexion, $idOperador, $idTipoOperacion);
    	                    
    	                    if (pg_num_rows($qOperacionesXIdOperadorXIdTipoOperacion) > 0) {
    	                        
    	                        $qBuscarCodigoPOA = $cr->buscarCodigoPoaOperador($conexion, $idOperador);
						  
								if(pg_num_rows($qBuscarCodigoPOA)==0){
    	                    		//Si no tiene registrado POA se crea codigo POA al operador
    	                    		$numero = pg_fetch_assoc($cr -> generarCodigoPoa($conexion));
    	                    		$tmp= explode("-",$numero['valor'],-1);
    	                    		$incremento = end($tmp)+1;
    	                    		$numeroPOA = str_pad($incremento, 4, "0", STR_PAD_LEFT);
    	                    		$codigoPOA = $cr->generarPOA($numeroPOA);
    	                    		$idCodigoPOA = pg_fetch_result($cr->guardarCodigoPoaOperador($conexion, $idOperador, $codigoPOA), 0, 'id_codigo_poa');
    	                    	}else{
    	                    		//Se busca codigo POA
    	                    		$buscarCodigoPOA = pg_fetch_assoc($qBuscarCodigoPOA);
    	                    		$idCodigoPOA = $buscarCodigoPOA['id_codigo_poa'];
    	                    		$codigoPOA = $buscarCodigoPOA['codigo_poa'];
    	                    	}

    	                    	// Se crea subcodigo POA
    	                    	$subcodigoPOA = $cr->generarSubcodigoPoa($codigoPOA, $opcionArea);
    	                    	$qIdSubcodigoPoa = $cr->buscarSubcodigoPoaOperador($conexion, $idCodigoPOA, $subcodigoPOA);
    	                    	if (pg_num_rows($qIdSubcodigoPoa) == 0) {
    	                    	    $idSubcodigoPOA = pg_fetch_result($cr->guardarSubcodigoPoaOperador($conexion, $idCodigoPOA, $subcodigoPOA, $idTipoOperacion, 'habilitado'), 0, 'id_subcodigo_poa');
    	                    	} else {
    	                    	    $idSubcodigoPOA = pg_fetch_result($qIdSubcodigoPoa, 0, 'id_subcodigo_poa');
    	                    	}
    	                    	
    	                    	$reporteUno = "";
    	                    	$reporteDos = "";
    	                    	$reporteTres = "";
    	                    	
    	                    	if ($opcionArea == "PRO") {
    	                    	    $reporteUno = "PRO1";
    	                    	    $reporteDos = "PRO2";
    	                    	    $reporteTres = "PRO3";
    	                    	    $codigoQr = 'http://181.112.155.173/' . $constg::RUTA_APLICACION . '/aplicaciones/registroOperador/certificados/certificadosPOA/PRO-' . $idOperador . '.pdf';
    	                    	    $salidaArchivoPoa = 'aplicaciones/registroOperador/certificados/certificadosPOA/PRO-'.$idOperador.'.pdf';
    	                    	} else if ($opcionArea == "REC") {
    	                    	    $reporteUno = "REC1";
    	                    	    $reporteDos = "REC2";
    	                    	    $reporteTres = "REC3";
    	                    	    $codigoQr = 'http://181.112.155.173/' . $constg::RUTA_APLICACION . '/aplicaciones/registroOperador/certificados/certificadosPOA/REC-' . $idOperador . '.pdf';
    	                    	    $salidaArchivoPoa = 'aplicaciones/registroOperador/certificados/certificadosPOA/REC-'.$idOperador.'.pdf';
    	                    	}
    	                    	
    	                    	$parameters['parametrosReporte'] = array(
    	                    	    'codigoPOA'=> $codigoPOA,
    	                    	    'subcodigoPOA' => $subcodigoPOA,
    	                    	    'identificadorOperador' => $idOperador,
    	                    	    'idTipoOperacion' => (int) $idTipoOperacion,
    	                    	    'nombreTecnico' => $nombreInspector['apellido'] . ' ' . $nombreInspector['nombre'],
    	                    	    'fechaRegistroCreacion' => $fechaCreacion,
    	                    	    'fechaRegistroActualizacion' => $fechaActualizacion,
    	                    	    'codigoQr'=> $codigoQr
    	                    	);
    	                    	
    	                    	$ReporteJasper = '/aplicaciones/registroOperador/reportes/organicos/certificadoRegistroOperadorOrganico.jrxml';
    	                    	$filenamePoaUno = $reporteUno . '-' . $idOperador . '.pdf';
    	                    	$rutaPoaUno = '/aplicaciones/registroOperador/certificados/certificadosPOA/' . $filenamePoaUno;
    	                    	$jru->generarReporteJasper($ReporteJasper, $parameters, $conexion, $rutaPoaUno, 'organicos');
								
								$parameters['parametrosReporte'] = array( 
									'identificadorOperador' => $idOperador,
    	                        	'idTipoOperacion' => (int) $idTipoOperacion,
    	                        	'nombreTecnico' => $nombreInspector['apellido'] . ' ' . $nombreInspector['nombre'],
    	                        	'fechaRegistroCreacion' => $fechaCreacion,
    	                        	'fechaRegistroActualizacion' => $fechaActualizacion
    	                        );
    	                        
    	                        $ReporteJasper = '/aplicaciones/registroOperador/reportes/organicos/reporteAgenciaCertificadora.jrxml';
    	                        $filenamePoaDos = $reporteDos . '-' . $idOperador . '.pdf';
    	                        $rutaPoaDos = '/aplicaciones/registroOperador/certificados/certificadosPOA/' . $filenamePoaDos;
    	                        $jru->generarReporteJasper($ReporteJasper, $parameters, $conexion, $rutaPoaDos, 'organicos');
								
								$parameters['parametrosReporte'] = array(
    	                        	'identificadorOperador' => $idOperador,
    	                        	'idTipoOperacion' => (int) $idTipoOperacion,
    	                        	'nombreTecnico' => $nombreInspector['apellido'] . ' ' . $nombreInspector['nombre'],
    	                        	'fechaRegistroCreacion' => $fechaCreacion,
    	                        	'fechaRegistroActualizacion' => $fechaActualizacion
    	                        );
    	                        
    	                        $ReporteJasper = '/aplicaciones/registroOperador/reportes/organicos/reporteMiembrosAsociacion.jrxml';
    	                        $filenamePoaTres = $reporteTres . '-' . $idOperador . '.pdf';
    	                        $rutaPoaTres = '/aplicaciones/registroOperador/certificados/certificadosPOA/' . $filenamePoaTres;
    	                        $jru->generarReporteJasper($ReporteJasper, $parameters, $conexion, $rutaPoaTres, 'organicosHorizontal');
    	                        
    	                        $cr->actualizarRutaPOA($conexion, $idSubcodigoPOA, $idTipoOperacion, $rutaPoaUno, $rutaPoaDos, $rutaPoaTres, $salidaArchivoPoa);
								
								 $qVerificarCertificadoOrganico = $cr->verificarCertificadoOrganico($conexion, $idOperador, $idTipoOperacion);
    	                        $verificarCertificadoOrganico = pg_fetch_assoc($qVerificarCertificadoOrganico);
    	                        
    	                        if($verificarCertificadoOrganico['estado'] == "noHabilitado"){
    	                            $estado = 'habilitado';
    	                            $idSubcodigoPOA = $verificarCertificadoOrganico['id_subcodigo_poa'];
    	                            $cr->actualizarEstadoCertificadoOrganico($conexion, $idSubcodigoPOA, $estado);
    	                        }
								
    	                        $pdf->addPDF('../../../' . $rutaPoaUno, 'all')->addPDF('../../../' . $rutaPoaDos, 'all')->addPDF('../../../'.$rutaPoaTres, 'all')->merge('file', $constg::RUTA_SERVIDOR_OPT . '/' . $constg::RUTA_APLICACION . '/' . $salidaArchivoPoa);
    	                        
    	                        //Tabla de firmas físicas
    	                        $firmaResponsable = pg_fetch_assoc($cc->obtenerFirmasResponsablePorProvincia($conexion, 'Coordinador', 'AI'));
    	                        
    	                        $rutaArchivo = $constg::RUTA_SERVIDOR_OPT . '/' . $constg::RUTA_APLICACION . '/' .$salidaArchivoPoa;
    	                        
    	                        //Firma Electrónica
    	                        $parametrosFirma = array(
    	                        	'archivo_entrada'=>$rutaArchivo,
    	                        	'archivo_salida'=>$rutaArchivo,
    	                        	'identificador'=>$firmaResponsable['identificador'],
    	                        	'razon_documento'=>'Certificado de productos organicos',
    	                        	'tabla_origen'=>'g_operadores.subcodigos_poa',
    	                        	'campo_origen'=>'ruta_poa',
    	                        	'id_origen'=>$idOperacion,
    	                        	'estado'=>'Por atender',
    	                        	'proceso_firmado'=>'NO'
    	                        );
    	                        
    	                        //Guardar registro para firma
    	                        $cfd->ingresoFirmaDocumento($conexion, $parametrosFirma);
								
    	                    }else{
    	                        
    	                        //TODO:Verificar si todas las operaciones se encuentran en estado noHabilitado e inhabilitar el certificado POA
    	                        
    	                        $estadosOperaciones = $cr->obtenerEstadosOperacionesPorOperadorPorIdTipoOperacion($conexion, $idOperador, $idTipoOperacion);
    	                        
    	                        if(pg_num_rows($estadosOperaciones) > 0){
    	                            
    	                            $arrayEstados = array();
    	                            
    	                            while ($fila = pg_fetch_assoc($estadosOperaciones)){
    	                                $arrayEstados[] = $fila['estado'];
    	                            }
    	                            
    	                            if(!in_array("registrado", $arrayEstados)) {
    	                                $qVerificarCertificadoOrganico = $cr->verificarCertificadoOrganico($conexion, $idOperador, $idTipoOperacion);
    	                                if(pg_num_rows($qVerificarCertificadoOrganico) > 0){
											$verificarCertificadoOrganico = pg_fetch_assoc($qVerificarCertificadoOrganico);
											$estado = "noHabilitado";
											
											if($verificarCertificadoOrganico['estado'] == "habilitado"){
												$idSubcodigoPOA = $verificarCertificadoOrganico['id_subcodigo_poa'];
												$cr->actualizarEstadoCertificadoOrganico($conexion, $idSubcodigoPOA, $estado);
											}
										}
    	                            }
    	                            
    	                        }
    	                    }
    	                    
    	                    break;
    	                    
    	                case 'PRC':
    	                case 'COM':
    	                    
    	                    $qOperacionesXIdOperadorXIdTipoOperacion = $cr->obtenerOperacionesXOperadorXIdTipoOperacion($conexion, $idOperador, $idTipoOperacion);
    	                    
    	                    if (pg_num_rows($qOperacionesXIdOperadorXIdTipoOperacion) > 0) {
    	                        $qBuscarCodigoPOA = $cr->buscarCodigoPoaOperador($conexion, $idOperador);
							 
    	                       if(pg_num_rows($qBuscarCodigoPOA)==0){
    	                        	//Si no tiene registrado POA se crea codigo POA al operador
    	                        	$numero = pg_fetch_assoc($cr -> generarCodigoPoa($conexion));
    	                        	$tmp= explode("-",$numero['valor'],-1);
    	                        	$incremento = end($tmp)+1;
    	                        	$numeroPOA = str_pad($incremento, 4, "0", STR_PAD_LEFT);
    	                        	$codigoPOA = $cr->generarPOA($numeroPOA);
    	                        	$idCodigoPOA = pg_fetch_result($cr->guardarCodigoPoaOperador($conexion, $idOperador, $codigoPOA), 0, 'id_codigo_poa');
    	                        }else{
    	                        	//Se busca codigo POA
    	                        	$buscarCodigoPOA = pg_fetch_assoc($qBuscarCodigoPOA);
    	                        	$idCodigoPOA = $buscarCodigoPOA['id_codigo_poa'];
    	                        	$codigoPOA = $buscarCodigoPOA['codigo_poa'];
    	                        }
								
    	                        // Se crea subcodigo POA
    	                        $subcodigoPOA = $cr->generarSubcodigoPoa($codigoPOA, $opcionArea);
    	                        
    	                        $qIdSubcodigoPoa = $cr->buscarSubcodigoPoaOperador($conexion, $idCodigoPOA, $subcodigoPOA);
    	                        
    	                        if (pg_num_rows($qIdSubcodigoPoa) == 0) {
    	                            $idSubcodigoPOA = pg_fetch_result($cr->guardarSubcodigoPoaOperador($conexion, $idCodigoPOA, $subcodigoPOA, $idTipoOperacion, 'habilitado'), 0, 'id_subcodigo_poa');
    	                        } else {
    	                            $idSubcodigoPOA = pg_fetch_result($qIdSubcodigoPoa, 0, 'id_subcodigo_poa');
    	                        }
    	                        
    	                        $reporteUno = "";
    	                        $reporteDos = "";
								$reporteTres = "";
								
    	                        if ($opcionArea == 'COM') {
    	                            $reporteUno = 'COM1';
    	                            $reporteDos = 'COM2';
    	                            $codigoQr = 'http://181.112.155.173/' . $constg::RUTA_APLICACION . '/aplicaciones/registroOperador/certificados/certificadosPOA/COM-' . $idOperador . '.pdf';
									$salidaArchivoPoa = 'aplicaciones/registroOperador/certificados/certificadosPOA/COM-'.$idOperador.'.pdf';
    	                        } else if ($opcionArea == 'PRC') {
    	                            $reporteUno = 'PRC1';
    	                            $reporteDos = 'PRC2';
									$reporteTres = "PRC3";
    	                            $codigoQr = 'http://181.112.155.173/' . $constg::RUTA_APLICACION . '/aplicaciones/registroOperador/certificados/certificadosPOA/PRC-' . $idOperador . '.pdf';
									$salidaArchivoPoa = 'aplicaciones/registroOperador/certificados/certificadosPOA/PRC-'.$idOperador.'.pdf';
    	                        }
    	                        
    	                         $parameters['parametrosReporte'] = array(
    	                        	'codigoPOA'=> $codigoPOA,
    	                        	'subcodigoPOA'=> $subcodigoPOA,
    	                        	'identificadorOperador'=> $idOperador,
    	                        	'idTipoOperacion'=> (int) $idTipoOperacion,
    	                        	'nombreTecnico'=> $nombreInspector['apellido'] . ' ' . $nombreInspector['nombre'],
    	                        	'fechaRegistroCreacion'=> $fechaCreacion,
    	                        	'fechaRegistroActualizacion'=> $fechaActualizacion,
    	                        	'codigoQr'=> $codigoQr
    	                        );
    	                        
    	                        $ReporteJasper = '/aplicaciones/registroOperador/reportes/organicos/certificadoRegistroOperadorOrganico.jrxml';
    	                        $filenamePoaUno = $reporteUno . '-' . $idOperador . '.pdf';
    	                        $rutaPoaUno = '/aplicaciones/registroOperador/certificados/certificadosPOA/' . $filenamePoaUno;
    	                        $jru->generarReporteJasper($ReporteJasper, $parameters, $conexion, $rutaPoaUno, 'organicos');
    	                        
    	                        if ($opcionArea == "PRC") {
									
										$parameters['parametrosReporte'] = array(
										'identificadorOperador'=> $idOperador,
										'idTipoOperacion'=> (int) $idTipoOperacion,
										'nombreTecnico'=> $nombreInspector['apellido'] . ' ' . $nombreInspector['nombre'],
										'fechaRegistroCreacion'=> $fechaCreacion,
										'fechaRegistroActualizacion'=> $fechaActualizacion
									);
									
    	                            $ReporteJasper = '/aplicaciones/registroOperador/reportes/organicos/proveedorProcesador.jrxml';
    	                            $filenamePoaDos = $reporteDos . '-' . $idOperador . '.pdf';
    	                            $rutaPoaDos = '/aplicaciones/registroOperador/certificados/certificadosPOA/' . $filenamePoaDos;
    	                            
    	                            $jru->generarReporteJasper($ReporteJasper, $parameters, $conexion, $rutaPoaDos, 'organicosHorizontal');
    	                            $ReporteJasper = '/aplicaciones/registroOperador/reportes/organicos/anexoProcesador.jrxml';
    	                            $filenamePoaTres = $reporteTres . '-' . $idOperador . '.pdf';
    	                            $rutaPoaTres = '/aplicaciones/registroOperador/certificados/certificadosPOA/' . $filenamePoaDos;
    	                            $jru->generarReporteJasper($ReporteJasper, $parameters, $conexion, $rutaPoaTres, 'organicosHorizontal');
									
    	                            $pdf->addPDF('../../../' . $rutaPoaUno, 'all')->addPDF('../../../' . $rutaPoaDos, 'all')->addPDF('../../../' . $rutaPoaTres, 'all')->merge('file',  $constg::RUTA_SERVIDOR_OPT . '/' . $constg::RUTA_APLICACION . '/' . $salidaArchivoPoa);
									
    	                        } else if ($opcionArea == "COM") {
    	                           
								   $parameters['parametrosReporte'] = array(
										'identificadorOperador'=> $idOperador,
										'idTipoOperacion'=> (int) $idTipoOperacion,
										'nombreTecnico'=> $nombreInspector['apellido'] . ' ' . $nombreInspector['nombre'],
										'fechaRegistroCreacion'=> $fechaCreacion,
										'fechaRegistroActualizacion'=> $fechaActualizacion
									);
									
    	                            $ReporteJasper = '/aplicaciones/registroOperador/reportes/organicos/anexoComercializador.jrxml';
    	                            $filenamePoaDos = $reporteDos . '-' . $idOperador . '.pdf';
    	                            $rutaPoaDos = '/aplicaciones/registroOperador/certificados/certificadosPOA/' . $filenamePoaDos;
    	                            $jru->generarReporteJasper($ReporteJasper, $parameters, $conexion, $rutaPoaDos, 'organicosHorizontal');
									
    	                            $pdf->addPDF('../../../' . $rutaPoaUno, 'all')->addPDF('../../../' . $rutaPoaDos, 'all')->merge('file',  $constg::RUTA_SERVIDOR_OPT . '/' . $constg::RUTA_APLICACION . '/' . $salidaArchivoPoa);
									
    	                        }
								
    	                        $cr->actualizarRutaPOA($conexion, $idSubcodigoPOA, $idTipoOperacion, $rutaPoaUno, $rutaPoaDos, $rutaPoaTres, $salidaArchivoPoa);
								
								 $qVerificarCertificadoOrganico = $cr->verificarCertificadoOrganico($conexion, $idOperador, $idTipoOperacion);
    	                        $verificarCertificadoOrganico = pg_fetch_assoc($qVerificarCertificadoOrganico);
    	                        
    	                        if($verificarCertificadoOrganico['estado'] == "noHabilitado"){
    	                            $estado = 'habilitado';
    	                            $idSubcodigoPOA = $verificarCertificadoOrganico['id_subcodigo_poa'];
    	                            $cr->actualizarEstadoCertificadoOrganico($conexion, $idSubcodigoPOA, $estado);
    	                        } 
								
								//Tabla de firmas físicas
    	                        $firmaResponsable = pg_fetch_assoc($cc->obtenerFirmasResponsablePorProvincia($conexion, 'Coordinador', 'AI'));
    	                        
    	                        $rutaArchivo = $constg::RUTA_SERVIDOR_OPT . '/' . $constg::RUTA_APLICACION . '/' .$salidaArchivoPoa;
    	                        
    	                        //Firma Electrónica
    	                        $parametrosFirma = array(
    	                        	'archivo_entrada'=>$rutaArchivo,
    	                        	'archivo_salida'=>$rutaArchivo,
    	                        	'identificador'=>$firmaResponsable['identificador'],
    	                        	'razon_documento'=>'Certificado de productos organicos',
    	                        	'tabla_origen'=>'g_operadores.subcodigos_poa',
    	                        	'campo_origen'=>'ruta_poa',
    	                        	'id_origen'=>$idOperacion,
    	                        	'estado'=>'Por atender',
    	                        	'proceso_firmado'=>'NO'
    	                        );
    	                        
    	                        //Guardar registro para firma
    	                        $cfd->ingresoFirmaDocumento($conexion, $parametrosFirma);
								
    	                    }else{
    	                        
    	                        //TODO:Verificar si todas las operaciones se encuentran en estado noHabilitado e inhabilitar el certificado POA
    	                        
    	                        $estadosOperaciones = $cr->obtenerEstadosOperacionesPorOperadorPorIdTipoOperacion($conexion, $idOperador, $idTipoOperacion);
    	                        
    	                        if(pg_num_rows($estadosOperaciones) > 0){
    	                            
    	                            $arrayEstados = array();
    	                            
    	                            while ($fila = pg_fetch_assoc($estadosOperaciones)){
    	                                $arrayEstados[] = $fila['estado'];
    	                            }
    	                            
    	                            if(!in_array("registrado", $arrayEstados)) {
    	                                $qVerificarCertificadoOrganico = $cr->verificarCertificadoOrganico($conexion, $idOperador, $idTipoOperacion);
    	                                if(pg_num_rows($qVerificarCertificadoOrganico) > 0){
											$verificarCertificadoOrganico = pg_fetch_assoc($qVerificarCertificadoOrganico);
											$estado = "noHabilitado";
											
											if($verificarCertificadoOrganico['estado'] == "habilitado"){
												$idSubcodigoPOA = $verificarCertificadoOrganico['id_subcodigo_poa'];
												$cr->actualizarEstadoCertificadoOrganico($conexion, $idSubcodigoPOA, $estado);
											}
										}
										
    	                            }
    	                            
    	                        }
    	                    }
    	                    
    	               break;
    	                    
    	            }
    	            
    	            break;
    	            
    	        case "SA":
    	            
    	            break;
    	            
    	        case "SV":
					 case 'PRP':
	    	        case 'VVE':
	    	        case 'ALM':
	    	        case 'MIM':
	    	        	
	    	        	$existenciaDocumento = $cr->obtenerDocumentoGeneradoInspeccionPorOperadorTipoOperacion($conexion, $idOperadorTipoOperacion);
	    	        	if(pg_num_rows($existenciaDocumento) == 0){
	    	        		$secuencial = pg_fetch_assoc($cr->obtenerMaximoDocumentoOperador($conexion, '', 'centroPropagacionViverista'));
	    	        		$secuencial = str_pad($secuencial['secuencial'], 5, '0', STR_PAD_LEFT);
	    	        	}else{
	    	        		$secuencial = str_pad(pg_fetch_result($existenciaDocumento, 0, 'secuencial'), 5, '0', STR_PAD_LEFT);
	    	        		$generarDocumento = false;
	    	        	}
	    	        	$codigo = $idOperador.'-'.$secuencial;
	    	        	
	    	        	$datosOperacion = $cr->abrirOperacion($conexion, $idOperador, $idOperacion);
	    	        	
	    	        	$firmaResponsable = pg_fetch_assoc($cc->obtenerFirmasResponsablePorProvincia($conexion, $datosOperacion[0]['provincia'], 'SV'));
	    	        	
	    	        	$ReporteJasper= '/aplicaciones/registroOperador/reportes/centroPropagacionViverista/centroPropagacionViverista.jrxml';
	    	        	$salidaReporte= '/aplicaciones/registroOperador/certificados/centroPropagacionViverista/'.$idOperador.'_'.$idOperadorTipoOperacion.'.pdf';
	    	        	$rutaArchivo= 'aplicaciones/registroOperador/certificados/centroPropagacionViverista/'.$idOperador.'_'.$idOperadorTipoOperacion.'.pdf';
	    	        	
	    	        	$parameters['parametrosReporte'] = array(
	    	        		'numeroCertificado' => $codigo,
	    	        		'idOperadorTipoOperacion' => (int)$idOperadorTipoOperacion,
	    	        		'nombreAutoridad' => $firmaResponsable['siglas_titulo'].' '.$firmaResponsable['nombre'],
	    	        		'cargoAutoridad'=> $firmaResponsable['cargo']
	    	        	);
	    	        	
	    	        	$jru->generarReporteJasper($ReporteJasper,$parameters,$conexion,$salidaReporte,'sanidadVegetal');
	    	        	
	    	        	if($generarDocumento){
	    	        		$cr->guardarDocumentoOperador($conexion, $idOperacion, $idOperadorTipoOperacion, $rutaArchivo, 'centroPropagacionViverista', $secuencial, $idOperador, 'Certificado de registro de centros de propagación de especies vegetales.');
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
	    	        		'id_origen'=>$idOperacion,
	    	        		'estado'=>'Por atender',
	    	        		'proceso_firmado'=>'NO'
	    	        	);
	    	        	
	    	        	//Guardar registro para firma
	    	        	$cfd->ingresoFirmaDocumento($conexion, $parametrosFirma);
	    	        	
	    	        	break;    	            
    	            break;
    	            
    	    }
    	    
    	    $cr->cambiarEstadoActualizarCertificado($conexion, $idOperadorTipoOperacion, $idHistorialOperacion, 'NO');
    		
        }
    
    	$fecha = date("Y-m-d h:m:s");
    
    	echo IN_MSG .'<b>FIN PROCESO DE ACTUALIZACIÓN DE CERTIFICADOS '.$fecha.'</b>';
	}
	
}else{
    $minutoS1=microtime(true);
    $minutoS2=microtime(true);
    $tiempo=$minutoS2-minutoS1;
    $xcadenota = "FECHA ".date("d/m/Y")." ".date("H:i:s");
    $xcadenota.= "; IP REMOTA ".$_SERVER['REMOTE_ADDR'];
    $xcadenota.= "; SERVIDOR HTTP ".$_SERVER['HTTP_REFERER'];
    $xcadenota.= "; SEGUNDOS ".$tiempo."\n";
    $arch = fopen("../../../aplicaciones/logs/cron/actualizar_certificado_".date("d-m-Y").".txt", "a+");
    fwrite($arch, $xcadenota);
    fclose($arch);
    
}
?>