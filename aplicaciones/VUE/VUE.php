<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Sistema GUIA</title>
</head>
<body>
	<h1>Solicitudes pendientes por atender</h1>

	<?php

	//if($_SERVER['REMOTE_ADDR'] == ''){
	if(1){
	
		require_once '../../clases/ControladorVUE.php';
		require_once '../../clases/ControladorMonitoreo.php';
	
		$controladorVUE = new ControladorVUE();
		$cm = new ControladorMonitoreo();
		$conexionVUE = new Conexion();
	
		//$resultadoMonitoreo = $cm->obtenerCronPorCodigoEstado($conexionVUE, 'CRON_VUE_GATE');
		//$resultado = 1;
		
		if(true){
			
			//$usuario = array('id' => '101','nombre' => 'G.U.I.A.');
		
			$solicitudesPendientes = $controladorVUE->cargarSolicitudesPorAtenderVUE($conexionVUE);
		
			while ($solicitudPendiente = pg_fetch_assoc($solicitudesPendientes)){
				$formulario = $controladorVUE->instanciarFormulario($solicitudPendiente);
				$resultado = array();
				if ($formulario == null){
					echo PRO_MSG . 'formulario desconocido.';
					continue;
				}
				
				$estadoReverso = $controladorVUE->cargarSolicitudesPorAtenderVUEReversoPorIdentificadorVUE($conexionVUE, $solicitudPendiente['solicitud']);
				
				if(pg_num_rows($estadoReverso) == 0){
		
				echo '<p> <strong>INICIO SOLICITUD ' . $formulario . '</strong>' . IN_MSG . 'Instanciada';
				//revisión de CODIGO DE DISTRIBUICION DE DOCUMENTO (Pág. 13)
				/* echo '<pre>';
				 print_r($formulario);
				//print_r($solicitudesPendientes);
				echo '</pre>'; */
				//echo $formulario->obtenerCodigoDeProcesamiento();
				switch ($formulario->obtenerCodigoDeProcesamiento()){
		
					/****************************************************************************/
					case SOLICITUD_ENVIADA: //Inicia en vue, verificar 11
							
						switch ($solicitudPendiente['estado']){
							case 'Por atender':
								$controladorVUE->finalizar($formulario,'W');
								$controladorVUE->actualizarEstadoVUE($formulario,SOLICITUD_ENVIADA,FIN_TAREA, $resultado[1]);
								$controladorVUE->actualizarEstadoVUE($formulario,SOLICITUD_RECEPTADA,SOLICITUD_DE_EVIO_VUE, $resultado[1]);
								$controladorVUE->finalizar($formulario,'Solicitud receptada');
								echo OUT_MSG . 'Se ha finalizado la tarea de solicitud enviada.';
							break;
							/*case 'Confirmacion enviada':
							 $controladorVUE->finalizar($formulario,'W');
							$controladorVUE->actualizarEstadoVUE($formulario,SOLICITUD_RECEPTADA,SOLICITUD_DE_EVIO_VUE, $resultado[1]);
							$controladorVUE->finalizar($formulario,'Solicitud receptada');
							break;	*/
							case 'Solicitud receptada':
								
								$controladorVUE->finalizar($formulario,'W');
								
								switch (substr($solicitudPendiente['formulario'], 0,7)){
									case '101-001':
										$resultado = $formulario->validarDatosFormulario();
										switch ($resultado[0]){
											case SUBSANACION_REQUERIDA:
												//$controladorVUE->actualizarEstadoVUE($formulario,SUBSANACION_REQUERIDA,SOLICITUD_DE_EVIO_VUE, $resultado[1]);
												$controladorVUE->ingresarSolicitudesXatenderVUE($solicitudPendiente['formulario'], SUBSANACION_REQUERIDA, SOLICITUD_DE_EVIO_VUE, $solicitudPendiente['solicitud'], $resultado[1]);
												$controladorVUE->ingresarSolicitudesVerificacionTiempoRespuesta('Operadores', $solicitudPendiente['solicitud']);
												$controladorVUE->finalizar($formulario,'Atendida');
											break;
											case ERROR_TAREA:
												$controladorVUE->actualizarEstadoVUE($formulario,SOLICITUD_ENVIADA,ERROR_TAREA,$resultado[1]);
												$controladorVUE->finalizar($formulario,'Error en la solicitud');
											break;
											default:
												$formulario->insertarDatosEnGUIA();
												$controladorVUE->aprobarSolicitud($formulario, $resultado[1]);
												$controladorVUE->finalizar($formulario,'Atendida');
											break;
										}
									break;
											
									case '101-002':
										$resultado = $formulario->validarDatosFormulario();
										switch ($resultado[0]){
											case SUBSANACION_REQUERIDA:
												//$controladorVUE->actualizarEstadoVUE($formulario,SUBSANACION_REQUERIDA,SOLICITUD_DE_EVIO_VUE, $resultado[1]);
												$controladorVUE->ingresarSolicitudesXatenderVUE($solicitudPendiente['formulario'], SUBSANACION_REQUERIDA, SOLICITUD_DE_EVIO_VUE, $solicitudPendiente['solicitud'], $resultado[1]);
												$controladorVUE->ingresarSolicitudesVerificacionTiempoRespuesta('Importación', $solicitudPendiente['solicitud']);
												$controladorVUE->finalizar($formulario,'Atendida');
											break;
											case ERROR_TAREA:
												$controladorVUE->actualizarEstadoVUE($formulario,SOLICITUD_ENVIADA,ERROR_TAREA,$resultado[1]);
												$controladorVUE->finalizar($formulario,'Error en la solicitud');
											break;
											case SOLICITUD_NO_APROBADA:
												$controladorVUE->actualizarEstadoVUE($formulario,SOLICITUD_NO_APROBADA,SOLICITUD_DE_EVIO_VUE, $resultado[1]);
												$controladorVUE->finalizar($formulario,'Atendida');
												break;
											default:
												$formulario->insertarDatosEnGUIA();
												$documentos = $controladorVUE->obtenerDocumentoAdjuntos($solicitudPendiente['solicitud'], '110');
												$formulario->insertarDocumentosAdjuntosGUIA($documentos);
												$controladorVUE->finalizar($formulario,'Atendida');
											break;
										}
									break;
									case '101-024':
										$resultado = $formulario->validarDatosFormulario();
										switch ($resultado[0]){
											case SUBSANACION_REQUERIDA:
												//$controladorVUE->actualizarEstadoVUE($formulario,SUBSANACION_REQUERIDA,SOLICITUD_DE_EVIO_VUE, $resultado[1]);
												$controladorVUE->ingresarSolicitudesXatenderVUE($solicitudPendiente['formulario'], SUBSANACION_REQUERIDA, SOLICITUD_DE_EVIO_VUE, $solicitudPendiente['solicitud'], $resultado[1]);
												$controladorVUE->ingresarSolicitudesVerificacionTiempoRespuesta('DDA', $solicitudPendiente['solicitud']);
												$controladorVUE->finalizar($formulario,'Atendida');
											break;
											case ERROR_TAREA:
												$controladorVUE->actualizarEstadoVUE($formulario,SOLICITUD_ENVIADA,ERROR_TAREA,$resultado[1]);
												$controladorVUE->finalizar($formulario,'Error en la solicitud');
											break;
											default:
												$formulario->insertarDatosEnGUIA();
												$documentos = $controladorVUE->obtenerDocumentoAdjuntos($solicitudPendiente['solicitud'], '110');
												$formulario->insertarDocumentosAdjuntosGUIA($documentos);
												$controladorVUE->finalizar($formulario,'Atendida');
											break;
										}
										
									break;
									case '101-008':
										$resultado = $formulario->validarDatosFormulario();
										switch ($resultado[0]){
											case SUBSANACION_REQUERIDA:
												//$controladorVUE->actualizarEstadoVUE($formulario,SUBSANACION_REQUERIDA,SOLICITUD_DE_EVIO_VUE, $resultado[1]);
												$controladorVUE->ingresarSolicitudesXatenderVUE($solicitudPendiente['formulario'], SUBSANACION_REQUERIDA, SOLICITUD_DE_EVIO_VUE, $solicitudPendiente['solicitud'], $resultado[1]);
												$controladorVUE->ingresarSolicitudesVerificacionTiempoRespuesta('Zoosanitario', $solicitudPendiente['solicitud']);
												$controladorVUE->finalizar($formulario,'Atendida');
												break;
											case ERROR_TAREA:
												$controladorVUE->actualizarEstadoVUE($formulario,SOLICITUD_ENVIADA,ERROR_TAREA,$resultado[1]);
												$controladorVUE->finalizar($formulario,'Error en la solicitud');
												break;
											case SOLICITUD_NO_APROBADA:
												$controladorVUE->actualizarEstadoVUE($formulario,SOLICITUD_NO_APROBADA,SOLICITUD_DE_EVIO_VUE, $resultado[1]);
												$controladorVUE->finalizar($formulario,'Atendida');
												break;
											default:
												$formulario->insertarDatosEnGUIA();
												$documentos = $controladorVUE->obtenerDocumentoAdjuntos($solicitudPendiente['solicitud'], '110');
												$formulario->insertarDocumentosAdjuntosGUIA($documentos);
												$controladorVUE->finalizar($formulario,'Atendida');
												break;
										}
									break;
									case '101-031':
										$resultado = $formulario->validarDatosFormulario();
										switch ($resultado[0]){
											case SUBSANACION_REQUERIDA:
												//$controladorVUE->actualizarEstadoVUE($formulario,SUBSANACION_REQUERIDA,SOLICITUD_DE_EVIO_VUE, $resultado[1]);
												$controladorVUE->ingresarSolicitudesXatenderVUE($solicitudPendiente['formulario'], SUBSANACION_REQUERIDA, SOLICITUD_DE_EVIO_VUE, $solicitudPendiente['solicitud'], $resultado[1]);
												$controladorVUE->ingresarSolicitudesVerificacionTiempoRespuesta('Fitosanitario', $solicitudPendiente['solicitud']);
												$controladorVUE->finalizar($formulario,'Atendida');
												break;
											case ERROR_TAREA:
												$controladorVUE->actualizarEstadoVUE($formulario,SOLICITUD_ENVIADA,ERROR_TAREA,$resultado[1]);
												$controladorVUE->finalizar($formulario,'Error en la solicitud');
												break;
											case SOLICITUD_NO_APROBADA:
												$controladorVUE->actualizarEstadoVUE($formulario,SOLICITUD_NO_APROBADA,SOLICITUD_DE_EVIO_VUE, $resultado[1]);
												$controladorVUE->finalizar($formulario,'Atendida');
												break;
											default:
												$formulario->insertarDatosEnGUIA();
												$documentos = $controladorVUE->obtenerDocumentoAdjuntos($solicitudPendiente['solicitud'], '110');
												$formulario->insertarDocumentosAdjuntosGUIA($documentos);
												$controladorVUE->finalizar($formulario,'Atendida');
											break;
										}
									break;
									case '101-047':
										$resultado = $formulario->validarDatosFormulario();
										switch ($resultado[0]){
											case SUBSANACION_REQUERIDA:
												//$controladorVUE->actualizarEstadoVUE($formulario,SUBSANACION_REQUERIDA,SOLICITUD_DE_EVIO_VUE, $resultado[1]);
												$controladorVUE->ingresarSolicitudesXatenderVUE($solicitudPendiente['formulario'], SUBSANACION_REQUERIDA, SOLICITUD_DE_EVIO_VUE, $solicitudPendiente['solicitud'], $resultado[1]);
												$controladorVUE->ingresarSolicitudesVerificacionTiempoRespuesta('CLV', $solicitudPendiente['solicitud']);
												$controladorVUE->finalizar($formulario,'Atendida');
												break;
											case ERROR_TAREA:
												$controladorVUE->actualizarEstadoVUE($formulario,SOLICITUD_ENVIADA,ERROR_TAREA,$resultado[1]);
												$controladorVUE->finalizar($formulario,'Error en la solicitud');
												break;
											default:
												$formulario->insertarDatosEnGUIA();
												$documentos = $controladorVUE->obtenerDocumentoAdjuntos($solicitudPendiente['solicitud'], '110');
												$formulario->insertarDocumentosAdjuntosGUIA($documentos);
												$controladorVUE->finalizar($formulario,'Atendida');
											break;
										}
									break;
									
									case '101-034':
										//$controladorVUE->finalizar($formulario,'Solicitud receptada'); //TODO:Eliminar cuando este el desarrollo completo, para que no cambie de estado
										$resultado = $formulario->validarDatosFormulario();								
										switch ($resultado[0]){
											case SUBSANACION_REQUERIDA:
												$controladorVUE->actualizarEstadoVUE($formulario,SUBSANACION_REQUERIDA,SOLICITUD_DE_EVIO_VUE, $resultado[1]);
												$controladorVUE->finalizar($formulario,'Atendida');
												break;
											case ERROR_TAREA:
												$controladorVUE->actualizarEstadoVUE($formulario,SOLICITUD_ENVIADA,ERROR_TAREA,$resultado[1]);
												$controladorVUE->finalizar($formulario,'Error en la solicitud');
												break;
											case SOLICITUD_NO_APROBADA:
												$controladorVUE->actualizarEstadoVUE($formulario,SOLICITUD_NO_APROBADA,SOLICITUD_DE_EVIO_VUE, $resultado[1]);
												$controladorVUE->finalizar($formulario,'Atendida');
												break;
											default:
												$formulario->insertarDatosEnGUIA();
												$documentos = $controladorVUE->obtenerDocumentoAdjuntos($solicitudPendiente['solicitud'], '110');
												$formulario->insertarDocumentosAdjuntosGUIA($documentos);
												$controladorVUE->finalizar($formulario,'Atendida');
												break;
										}
										break;
									
									case '101-061':
									    $resultado = $formulario->validarDatosFormulario();
									    switch ($resultado[0]){
									        case SUBSANACION_REQUERIDA:
									            //$controladorVUE->actualizarEstadoVUE($formulario,SUBSANACION_REQUERIDA,SOLICITUD_DE_EVIO_VUE, $resultado[1]);
									            $controladorVUE->ingresarSolicitudesXatenderVUE($solicitudPendiente['formulario'], SUBSANACION_REQUERIDA, SOLICITUD_DE_EVIO_VUE, $solicitudPendiente['solicitud'], $resultado[1]);
									            $controladorVUE->ingresarSolicitudesVerificacionTiempoRespuesta('TránsitoInternacional', $solicitudPendiente['solicitud']);
									            $controladorVUE->finalizar($formulario,'Atendida');
									            break;
									        case ERROR_TAREA:
									            $controladorVUE->actualizarEstadoVUE($formulario,SOLICITUD_ENVIADA,ERROR_TAREA,$resultado[1]);
									            $controladorVUE->finalizar($formulario,'Error en la solicitud');
									            break;
									        case SOLICITUD_NO_APROBADA:
									            $controladorVUE->actualizarEstadoVUE($formulario,SOLICITUD_NO_APROBADA,SOLICITUD_DE_EVIO_VUE, $resultado[1]);
									            $controladorVUE->finalizar($formulario,'Atendida');
									            break;
									        default:
									            $formulario->insertarDatosEnGUIA();
									            $documentos = $controladorVUE->obtenerDocumentoAdjuntos($solicitudPendiente['solicitud'], '110');
									            $formulario->insertarDocumentosAdjuntosGUIA($documentos);
									            $controladorVUE->finalizar($formulario,'Atendida');
									            break;
									    }
									break;
								}
									
								break;
						}
						//echo OUT_MSG . 'Se ha finalizado la tarea de solicitud procesada.';
					break;
					
					case SUBSANACION_ENVIADA: //Inicia en VUE
						$controladorVUE->finalizar($formulario,'W');
						$controladorVUE->actualizarEstadoVUE($formulario,SUBSANACION_ENVIADA,FIN_TAREA, $resultado[1]);
						switch (substr($solicitudPendiente['formulario'], 0,7)){
							case '101-001':
								$resultado = $formulario->validarDatosFormulario();
									
								switch ($resultado[0]){
									case SUBSANACION_REQUERIDA:
										//$controladorVUE->actualizarEstadoVUE($formulario,SUBSANACION_REQUERIDA,SOLICITUD_DE_EVIO_VUE,$resultado[1]);
										$controladorVUE->ingresarSolicitudesXatenderVUE($solicitudPendiente['formulario'], SUBSANACION_REQUERIDA, SOLICITUD_DE_EVIO_VUE, $solicitudPendiente['solicitud'], $resultado[1]);
										$controladorVUE->ingresarSolicitudesVerificacionTiempoRespuesta('Operadores', $solicitudPendiente['solicitud']);
										$controladorVUE->finalizar($formulario,'Atendida');
									break;
									case ERROR_TAREA:
										$controladorVUE->actualizarEstadoVUE($formulario,SOLICITUD_ENVIADA,ERROR_TAREA,$resultado[1]);
										$controladorVUE->finalizar($formulario,'Error en la solicitud');
									break;
									default:
										$formulario->insertarDatosEnGUIA();
										$controladorVUE->aprobarSolicitud($formulario, 'Solicitud aprobada');
										$controladorVUE->ingresarSolicitudesVerificacionTiempoRespuesta('Operadores', $solicitudPendiente['solicitud'],'registrado');
										$controladorVUE->finalizar($formulario,'Atendida');
									break;
								}
							break;
							case '101-002':
								$resultado = $formulario->validarDatosFormulario();
								
								switch ($resultado[0]){
									case SUBSANACION_REQUERIDA:
										//$controladorVUE->actualizarEstadoVUE($formulario,SUBSANACION_REQUERIDA,SOLICITUD_DE_EVIO_VUE,$resultado[1]);
										$controladorVUE->ingresarSolicitudesXatenderVUE($solicitudPendiente['formulario'], SUBSANACION_REQUERIDA, SOLICITUD_DE_EVIO_VUE, $solicitudPendiente['solicitud'], $resultado[1]);
										$controladorVUE->ingresarSolicitudesVerificacionTiempoRespuesta('Importación', $solicitudPendiente['solicitud']);
										$controladorVUE->finalizar($formulario,'Atendida');
										break;
									case ERROR_TAREA:
										$controladorVUE->actualizarEstadoVUE($formulario,SOLICITUD_ENVIADA,ERROR_TAREA,$resultado[1]);
										$controladorVUE->finalizar($formulario,'Error en la solicitud');
										break;
									case SOLICITUD_NO_APROBADA:
										$controladorVUE->actualizarEstadoVUE($formulario,SOLICITUD_NO_APROBADA,SOLICITUD_DE_EVIO_VUE, $resultado[1]);
										$controladorVUE->finalizar($formulario,'Atendida');
										break;
									default:
										$formulario->insertarDatosEnGUIA();
										$documentos = $controladorVUE->obtenerDocumentoAdjuntos($solicitudPendiente['solicitud'], '420');
										$formulario->insertarDocumentosAdjuntosGUIA($documentos);
										$controladorVUE->finalizar($formulario,'Atendida');
									break;
								}
							break;
							case '101-024':
								$resultado = $formulario->validarDatosFormulario();
							
								switch ($resultado[0]){
									case SUBSANACION_REQUERIDA:
										//$controladorVUE->actualizarEstadoVUE($formulario,SUBSANACION_REQUERIDA,SOLICITUD_DE_EVIO_VUE,$resultado[1]);
										$controladorVUE->ingresarSolicitudesXatenderVUE($solicitudPendiente['formulario'], SUBSANACION_REQUERIDA, SOLICITUD_DE_EVIO_VUE, $solicitudPendiente['solicitud'], $resultado[1]);
										$controladorVUE->ingresarSolicitudesVerificacionTiempoRespuesta('DDA', $solicitudPendiente['solicitud']);
										$controladorVUE->finalizar($formulario,'Atendida');
										break;
									case ERROR_TAREA:
										$controladorVUE->actualizarEstadoVUE($formulario,SOLICITUD_ENVIADA,ERROR_TAREA,$resultado[1]);
										$controladorVUE->finalizar($formulario,'Error en la solicitud');
										break;
									default:
										$formulario->insertarDatosEnGUIA();
										$documentos = $controladorVUE->obtenerDocumentoAdjuntos($solicitudPendiente['solicitud'], '420');
										$formulario->insertarDocumentosAdjuntosGUIA($documentos);
										$controladorVUE->finalizar($formulario,'Atendida');
									break;
								}
							break;
							
							case '101-008':
								$resultado = $formulario->validarDatosFormulario();
							
								switch ($resultado[0]){
									case SUBSANACION_REQUERIDA:
										//$controladorVUE->actualizarEstadoVUE($formulario,SUBSANACION_REQUERIDA,SOLICITUD_DE_EVIO_VUE,$resultado[1]);
										$controladorVUE->ingresarSolicitudesXatenderVUE($solicitudPendiente['formulario'], SUBSANACION_REQUERIDA, SOLICITUD_DE_EVIO_VUE, $solicitudPendiente['solicitud'], $resultado[1]);
										$controladorVUE->ingresarSolicitudesVerificacionTiempoRespuesta('Zoosanitario', $solicitudPendiente['solicitud']);
										$controladorVUE->finalizar($formulario,'Atendida');
										break;
									case ERROR_TAREA:
										$controladorVUE->actualizarEstadoVUE($formulario,SOLICITUD_ENVIADA,ERROR_TAREA,$resultado[1]);
										$controladorVUE->finalizar($formulario,'Error en la solicitud');
										break;
									case SOLICITUD_NO_APROBADA:
										$controladorVUE->actualizarEstadoVUE($formulario,SOLICITUD_NO_APROBADA,SOLICITUD_DE_EVIO_VUE, $resultado[1]);
										$controladorVUE->finalizar($formulario,'Atendida');
										break;
									default:
										$formulario->insertarDatosEnGUIA();
										$documentos = $controladorVUE->obtenerDocumentoAdjuntos($solicitudPendiente['solicitud'], '420');
										$formulario->insertarDocumentosAdjuntosGUIA($documentos);
										$controladorVUE->finalizar($formulario,'Atendida');
									break;
								}
							break;
								
							case '101-031':
								$resultado = $formulario->validarDatosFormulario();
							
								switch ($resultado[0]){
									case SUBSANACION_REQUERIDA:
										//$controladorVUE->actualizarEstadoVUE($formulario,SUBSANACION_REQUERIDA,SOLICITUD_DE_EVIO_VUE,$resultado[1]);
										$controladorVUE->ingresarSolicitudesXatenderVUE($solicitudPendiente['formulario'], SUBSANACION_REQUERIDA, SOLICITUD_DE_EVIO_VUE, $solicitudPendiente['solicitud'], $resultado[1]);
										$controladorVUE->ingresarSolicitudesVerificacionTiempoRespuesta('Fitosanitario', $solicitudPendiente['solicitud']);
										$controladorVUE->finalizar($formulario,'Atendida');
										break;
									case ERROR_TAREA:
										$controladorVUE->actualizarEstadoVUE($formulario,SOLICITUD_ENVIADA,ERROR_TAREA,$resultado[1]);
										$controladorVUE->finalizar($formulario,'Error en la solicitud');
										break;
									case SOLICITUD_NO_APROBADA:
										$controladorVUE->actualizarEstadoVUE($formulario,SOLICITUD_NO_APROBADA,SOLICITUD_DE_EVIO_VUE, $resultado[1]);
										$controladorVUE->finalizar($formulario,'Atendida');
										break;
									default:
										$formulario->insertarDatosEnGUIA();
										$documentos = $controladorVUE->obtenerDocumentoAdjuntos($solicitudPendiente['solicitud'], '420');
										$formulario->insertarDocumentosAdjuntosGUIA($documentos);
										$controladorVUE->finalizar($formulario,'Atendida');
									break;
								}
							break;
									
							case '101-047':
								$resultado = $formulario->validarDatosFormulario();
							
								switch ($resultado[0]){
									case SUBSANACION_REQUERIDA:
										//$controladorVUE->actualizarEstadoVUE($formulario,SUBSANACION_REQUERIDA,SOLICITUD_DE_EVIO_VUE,$resultado[1]);
										$controladorVUE->ingresarSolicitudesXatenderVUE($solicitudPendiente['formulario'], SUBSANACION_REQUERIDA, SOLICITUD_DE_EVIO_VUE, $solicitudPendiente['solicitud'], $resultado[1]);
										$controladorVUE->ingresarSolicitudesVerificacionTiempoRespuesta('CLV', $solicitudPendiente['solicitud']);
										$controladorVUE->finalizar($formulario,'Atendida');
										break;
									case ERROR_TAREA:
										$controladorVUE->actualizarEstadoVUE($formulario,SOLICITUD_ENVIADA,ERROR_TAREA,$resultado[1]);
										$controladorVUE->finalizar($formulario,'Error en la solicitud');
										break;
									default:
										$formulario->insertarDatosEnGUIA();
										$documentos = $controladorVUE->obtenerDocumentoAdjuntos($solicitudPendiente['solicitud'], '420');
										$formulario->insertarDocumentosAdjuntosGUIA($documentos);
										$controladorVUE->finalizar($formulario,'Atendida');
									break;
								}
							break;	
							
							case '101-034':
								
								$resultado = $formulario->validarDatosFormulario();
									
								switch ($resultado[0]){
									case SUBSANACION_REQUERIDA:
										$controladorVUE->actualizarEstadoVUE($formulario,SUBSANACION_REQUERIDA,SOLICITUD_DE_EVIO_VUE,$resultado[1]);
										$controladorVUE->finalizar($formulario,'Atendida');
										break;
									case ERROR_TAREA:
										$controladorVUE->actualizarEstadoVUE($formulario,SOLICITUD_ENVIADA,ERROR_TAREA,$resultado[1]);
										$controladorVUE->finalizar($formulario,'Error en la solicitud');
										break;
									case SOLICITUD_NO_APROBADA:
										$controladorVUE->actualizarEstadoVUE($formulario,SOLICITUD_NO_APROBADA,SOLICITUD_DE_EVIO_VUE, $resultado[1]);
										$controladorVUE->finalizar($formulario,'Atendida');
										break;
									default:
										$formulario->insertarDatosEnGUIA();
										$documentos = $controladorVUE->obtenerDocumentoAdjuntos($solicitudPendiente['solicitud'], '420');
										$formulario->insertarDocumentosAdjuntosGUIA($documentos);
										$controladorVUE->finalizar($formulario,'Atendida');
										break;
								}
							break;
							
							case '101-061': /***************************************************************************************************************************************************/
							    $resultado = $formulario->validarDatosFormulario();
							    
							    switch ($resultado[0]){
							        case SUBSANACION_REQUERIDA:
							            //$controladorVUE->actualizarEstadoVUE($formulario,SUBSANACION_REQUERIDA,SOLICITUD_DE_EVIO_VUE,$resultado[1]);
							            $controladorVUE->ingresarSolicitudesXatenderVUE($solicitudPendiente['formulario'], SUBSANACION_REQUERIDA, SOLICITUD_DE_EVIO_VUE, $solicitudPendiente['solicitud'], $resultado[1]);
							            $controladorVUE->ingresarSolicitudesVerificacionTiempoRespuesta('TránsitoInternacional', $solicitudPendiente['solicitud']);
							            $controladorVUE->finalizar($formulario,'Atendida');
							            break;
							        case ERROR_TAREA:
							            $controladorVUE->actualizarEstadoVUE($formulario,SOLICITUD_ENVIADA,ERROR_TAREA,$resultado[1]);
							            $controladorVUE->finalizar($formulario,'Error en la solicitud');
							            break;
							        case SOLICITUD_NO_APROBADA:
							            $controladorVUE->actualizarEstadoVUE($formulario,SOLICITUD_NO_APROBADA,SOLICITUD_DE_EVIO_VUE, $resultado[1]);
							            $controladorVUE->finalizar($formulario,'Atendida');
							            break;
							        default:
							            $formulario->insertarDatosEnGUIA();
							            $documentos = $controladorVUE->obtenerDocumentoAdjuntos($solicitudPendiente['solicitud'], '420');
							            $formulario->insertarDocumentosAdjuntosGUIA($documentos);
							            $controladorVUE->finalizar($formulario,'Atendida');
							            break;
							    }
						  break;
						}	
					break;
							
					case CORRECCION_SOLICITADA:
						
						$controladorVUE->finalizar($formulario,'W');
		
						$controladorVUE->actualizarEstadoVUE($formulario,CORRECCION_SOLICITADA,FIN_TAREA,'Corrección receptada.');
						
						switch (substr($solicitudPendiente['formulario'], 0,7)){
							case '101-001':
								$resultado = $formulario->validarActualizacionDeDatos();
								switch ($resultado[0]){
									case SOLICITUD_DE_CORRECION_NO_APROBADA:
										$controladorVUE->solicitudCorreccionNoAprobada($formulario, $resultado[1]);
										$controladorVUE->finalizar($formulario,'Atendida');
									break;
									default:
										$formulario->actualizarDatosEnGUIA();
										$controladorVUE->solicitudCorreccionAprobada($formulario, 'Solicitud de corrección aprobada.');
										$controladorVUE->finalizar($formulario,'Atendida');
									break;
								}	
								
							break;
							case '101-002':
								$resultado = $formulario->validarActualizacionDeDatos();
								switch ($resultado[0]){
									case SOLICITUD_DE_CORRECION_NO_APROBADA:
										$controladorVUE->solicitudCorreccionNoAprobada($formulario, $resultado[1]);
										$controladorVUE->finalizar($formulario,'Atendida');
									break;
									case SOLICITUD_AMPLIACION:
										$formulario->actualizarDatosEnGUIA();
										$documentos = $controladorVUE->obtenerDocumentoAdjuntos($solicitudPendiente['solicitud'], '150');
										$formulario->insertarDocumentosAdjuntosGUIA($documentos);
										
										$controladorVUE->crearAnexoRequisitos($formulario);
										$controladorVUE->enviarDocumentoAdjuntos($formulario, '340', 'D:\attach\DEV\documentosGUIA\importacion\ ');
										//$controladorVUE->enviarDocumentoAdjuntos($formulario, '340', 'D:\attach\PROD\documentosGUIA\importacion\ ');
										
										$controladorVUE->actualizarAUCP($formulario, SOLICITUD_AMPLIADA);
										$controladorVUE->cambioEstadoAUCP($formulario, '35');
										$controladorVUE->modificarSolicitud($formulario, 'Solicitud ampliada');
										$controladorVUE->finalizar($formulario,'Atendida');
									break;
									default:
										$formulario->actualizarDatosEnGUIA();
										$controladorVUE->crearAnexoRequisitos($formulario);
										$controladorVUE->enviarDocumentoAdjuntos($formulario, '340', 'D:\attach\DEV\documentosGUIA\importacion\ ');
										//$controladorVUE->enviarDocumentoAdjuntos($formulario, '340', 'D:\attach\PROD\documentosGUIA\importacion\ ');
										$controladorVUE->solicitudCorreccionAprobada($formulario, 'Solicitud de corrección aprobada.');
										$controladorVUE->finalizar($formulario,'Atendida');
									break;
								}
							break; 
							
							case '101-024':
								$resultado = $formulario->validarActualizacionDeDatos();
								switch ($resultado[0]){
									case SOLICITUD_DE_CORRECION_NO_APROBADA:
										$controladorVUE->solicitudCorreccionNoAprobada($formulario, $resultado[1]);
										$controladorVUE->finalizar($formulario,'Atendida');
									break;
									default:
										$formulario->actualizarDatosEnGUIA();
										$controladorVUE->solicitudCorreccionAprobada($formulario, 'Solicitud de corrección aprobada.');
										$controladorVUE->finalizar($formulario,'Atendida');
									break;
								}
							break;
							case '101-008':
								$resultado = $formulario->validarActualizacionDeDatos();
								switch ($resultado[0]){
									case SOLICITUD_DE_CORRECION_NO_APROBADA:
										$controladorVUE->solicitudCorreccionNoAprobada($formulario, $resultado[1]);
										$controladorVUE->finalizar($formulario,'Atendida');
									break;
									default:
										$formulario->actualizarDatosEnGUIA();
										$controladorVUE->solicitudCorreccionAprobada($formulario, 'Solicitud de corrección aprobada.');
										$controladorVUE->finalizar($formulario,'Atendida');
									break;
								}
							break;
							
							case '101-031':
								
								$resultado = $formulario->validarDatosFormulario();
								switch ($resultado[0]){
									
									case SUBSANACION_REQUERIDA:
									case SOLICITUD_NO_APROBADA:
										$controladorVUE->solicitudCorreccionNoAprobada($formulario,$resultado[1]);
										$controladorVUE->finalizar($formulario,'Atendida');
									break;
									default:								
										$resultado = $formulario->validarActualizacionDeDatos();
										switch ($resultado[0]){
											case SOLICITUD_DE_CORRECION_NO_APROBADA:
												$controladorVUE->solicitudCorreccionNoAprobada($formulario, $resultado[1]);
												$controladorVUE->finalizar($formulario,'Atendida');
											break;
											default:
												$formulario->actualizarDatosEnGUIA();
												//$documentos = $controladorVUE->obtenerDocumentoAdjuntos($solicitudPendiente['solicitud'], '150');
												//$formulario->insertarDocumentosAdjuntosGUIA($documentos);
												$controladorVUE->crearAnexoRequisitos($formulario);
												$controladorVUE->enviarDocumentoAdjuntos($formulario, '340', 'D:\attach\DEV\documentosGUIA\fitosanitario\ ');
												//$controladorVUE->enviarDocumentoAdjuntos($formulario, '340', 'D:\attach\PROD\documentosGUIA\fitosanitario\ ');
												$controladorVUE->solicitudCorreccionAprobada($formulario, 'Solicitud de corrección aprobada.');
												$controladorVUE->finalizar($formulario,'Atendida');
											break;
										}			
									break;
								}
							break;
							
							case '101-047':
									$resultado = $formulario->validarActualizacionDeDatos();
									switch ($resultado[0]){
										case SOLICITUD_DE_CORRECION_NO_APROBADA:
											$controladorVUE->solicitudCorreccionNoAprobada($formulario, $resultado[1]);
											$controladorVUE->finalizar($formulario,'Atendida');
										break;
										default:
											$formulario->actualizarDatosEnGUIA();
											$controladorVUE->solicitudCorreccionAprobada($formulario, 'Solicitud de corrección aprobada.');
											$controladorVUE->finalizar($formulario,'Atendida');
										break;
									}
								break;
								
								case '101-034':
									$resultado = $formulario->validarActualizacionDeDatos();
									switch ($resultado[0]){
										case SOLICITUD_DE_CORRECION_NO_APROBADA:
											$controladorVUE->solicitudCorreccionNoAprobada($formulario, $resultado[1]);
											$controladorVUE->finalizar($formulario,'Atendida');
										break;
										default:
											$formulario->actualizarDatosEnGUIA();
											//$controladorVUE->crearAnexoRequisitos($formulario);
											//$controladorVUE->enviarDocumentoAdjuntos($formulario, '340', 'D:\attach\DEV\documentosGUIA\fitosanitarioExportacion\ ');
											//$controladorVUE->enviarDocumentoAdjuntos($formulario, '340', 'D:\attach\PROD\documentosGUIA\fitosanitarioExportacion\ ');
											$controladorVUE->solicitudCorreccionAprobada($formulario, 'Solicitud de corrección aprobada.');
											$controladorVUE->finalizar($formulario,'Atendida');
											break;
									}
									break;
							
								case '101-061':
								    $resultado = $formulario->validarActualizacionDeDatos();
								    switch ($resultado[0]){
								        case SOLICITUD_DE_CORRECION_NO_APROBADA:
								            $controladorVUE->solicitudCorreccionNoAprobada($formulario, $resultado[1]);
								            $controladorVUE->finalizar($formulario,'Atendida');
								            break;
								        default:
								            $formulario->actualizarDatosEnGUIA();
								            $controladorVUE->crearAnexoRequisitos($formulario);
								            $controladorVUE->enviarDocumentoAdjuntos($formulario, '340', 'D:\attach\DEV\documentosGUIA\TransitoInternacional\ ');
								            //$controladorVUE->enviarDocumentoAdjuntos($formulario, '340', 'D:\attach\PROD\documentosGUIA\importacion\ ');
								            $controladorVUE->solicitudCorreccionAprobada($formulario, 'Solicitud de corrección aprobada.');
								            $controladorVUE->finalizar($formulario,'Atendida');
								            break;
								    }
								    break; 
						}	
					break;
					
					case DESISTIMIENTO_SOLICITADO:
						
						$controladorVUE->finalizar($formulario,'W');
						
						$controladorVUE->actualizarEstadoVUE($formulario,DESISTIMIENTO_SOLICITADO, FIN_TAREA, 'Desestimiento receptado.');
						switch (substr($solicitudPendiente['formulario'], 0,7)){
							case '101-001':
								$formulario->cancelar();
								$controladorVUE->actualizarEstadoVUE($formulario,DESISTIMIENTO_APROBADO,SOLICITUD_DE_EVIO_VUE, 'Desestimiento aprobado.');
								$controladorVUE->finalizar($formulario,'Atendida');
							break;
							case '101-002':
								$formulario->cancelar();
								$controladorVUE->actualizarEstadoVUE($formulario,DESISTIMIENTO_APROBADO,SOLICITUD_DE_EVIO_VUE, 'Desestimiento aprobado.');
								$controladorVUE->finalizar($formulario,'Atendida');
							break;
							case '101-024':
								$formulario->cancelar();
								$controladorVUE->actualizarEstadoVUE($formulario,DESISTIMIENTO_APROBADO,SOLICITUD_DE_EVIO_VUE, 'Desestimiento aprobado.');
								$controladorVUE->finalizar($formulario,'Atendida');
							break;
							case '101-008':
								$formulario->cancelar();
								$controladorVUE->actualizarEstadoVUE($formulario,DESISTIMIENTO_APROBADO,SOLICITUD_DE_EVIO_VUE, 'Desestimiento aprobado.');
								$controladorVUE->finalizar($formulario,'Atendida');
							break;
							case '101-031':
								$formulario->cancelar();
								$controladorVUE->actualizarEstadoVUE($formulario,DESISTIMIENTO_APROBADO,SOLICITUD_DE_EVIO_VUE, 'Desestimiento aprobado.');
								$controladorVUE->finalizar($formulario,'Atendida');
							break;
							case '101-047':
								$formulario->cancelar();
								$controladorVUE->actualizarEstadoVUE($formulario,DESISTIMIENTO_APROBADO,SOLICITUD_DE_EVIO_VUE, 'Desestimiento aprobado.');
								$controladorVUE->finalizar($formulario,'Atendida');
							break;					
							case '101-034':
								$formulario->cancelar();
								$controladorVUE->actualizarEstadoVUE($formulario,DESISTIMIENTO_APROBADO,SOLICITUD_DE_EVIO_VUE, 'Desestimiento aprobado.');
								$controladorVUE->finalizar($formulario,'Atendida');
							break;
							case '101-061':
							    $formulario->cancelar();
							    $controladorVUE->actualizarEstadoVUE($formulario,DESISTIMIENTO_APROBADO,SOLICITUD_DE_EVIO_VUE, 'Desestimiento aprobado.');
							    $controladorVUE->finalizar($formulario,'Atendida');
							    break;
							default:
								echo "Desestimiento solicitado - desconocido";
							break;
						}
					break;
							
					case ANULACION_SOLICITADA:
						
						$controladorVUE->finalizar($formulario,'W');
						
						$controladorVUE->actualizarEstadoVUE($formulario,ANULACION_SOLICITADA,FIN_TAREA,'Fin de anulación');
						
						switch (substr($solicitudPendiente['formulario'], 0,7)){
							case '101-001':
								$formulario->anular();
								$controladorVUE->actualizarEstadoVUE($formulario,ANULACION_APROBADA,SOLICITUD_DE_EVIO_VUE, 'La anulacion a sido aprobada.');
								$controladorVUE->finalizar($formulario,'Atendida');
							break;
							case '101-002':
								$formulario->anular();
								$controladorVUE->actualizarEstadoVUE($formulario,ANULACION_APROBADA,SOLICITUD_DE_EVIO_VUE, 'La anulacion a sido aprobada.');
								$controladorVUE->cambioEstadoAUCP($formulario, '3');
								$controladorVUE->finalizar($formulario,'Atendida');
							break;
							case '101-024':
								$formulario->anular();
								$controladorVUE->actualizarEstadoVUE($formulario,ANULACION_APROBADA,SOLICITUD_DE_EVIO_VUE, 'La anulacion a sido aprobada.');
								$controladorVUE->cambioEstadoAUCP($formulario, '3');
								$controladorVUE->finalizar($formulario,'Atendida');
								break;
							case '101-008':
								$formulario->anular();
								$controladorVUE->actualizarEstadoVUE($formulario,ANULACION_APROBADA,SOLICITUD_DE_EVIO_VUE, 'La anulacion a sido aprobada.');
								$controladorVUE->finalizar($formulario,'Atendida');
							break;
							case '101-031':
								$formulario->anular();
								$controladorVUE->actualizarEstadoVUE($formulario,ANULACION_APROBADA,SOLICITUD_DE_EVIO_VUE, 'La anulacion a sido aprobada.');
								//$controladorVUE->verificarConsumoWebServicesBanano($formulario, 'Anulación');
								$controladorVUE->finalizar($formulario,'Atendida');
							break;
							case '101-047':
								$formulario->anular();
								$controladorVUE->actualizarEstadoVUE($formulario,ANULACION_APROBADA,SOLICITUD_DE_EVIO_VUE, 'La anulacion a sido aprobada.');
								$controladorVUE->finalizar($formulario,'Atendida');
							break;
							case '101-034':
								$formulario->anular();
								$controladorVUE->actualizarEstadoVUE($formulario,ANULACION_APROBADA,SOLICITUD_DE_EVIO_VUE, 'La anulacion a sido aprobada.');
								$controladorVUE->finalizar($formulario,'Atendida');
							break;
							case '101-061':
							    $formulario->anular();
							    $controladorVUE->actualizarEstadoVUE($formulario,ANULACION_APROBADA,SOLICITUD_DE_EVIO_VUE, 'La anulacion ha sido aprobada.');
							    $controladorVUE->cambioEstadoAUCP($formulario, '3');
							    $controladorVUE->finalizar($formulario,'Atendida');
							    break;							
							default:
								echo "Anulacion solicitado - desconocido";
							break;
						}
					break;
						/****************************************************************************/
					
					case SUBSANACION_REQUERIDA:
						
						$controladorVUE->finalizar($formulario,'W');
						
						switch (substr($solicitudPendiente['formulario'], 0,7)){
							case '101-001':
								$controladorVUE->actualizarEstadoVUE($formulario,SUBSANACION_REQUERIDA,SOLICITUD_DE_EVIO_VUE,$solicitudPendiente['observacion']);
								$controladorVUE->finalizar($formulario,'Atendida');
							break;
							case '101-002':
								$controladorVUE->actualizarEstadoVUE($formulario,SUBSANACION_REQUERIDA,SOLICITUD_DE_EVIO_VUE,$solicitudPendiente['observacion']);
								$controladorVUE->finalizar($formulario,'Atendida');
							break;
							case '101-024':
								$controladorVUE->actualizarEstadoVUE($formulario,SUBSANACION_REQUERIDA,SOLICITUD_DE_EVIO_VUE,$solicitudPendiente['observacion']);
								$controladorVUE->finalizar($formulario,'Atendida');
							break;
							case '101-008':
								$controladorVUE->actualizarEstadoVUE($formulario,SUBSANACION_REQUERIDA,SOLICITUD_DE_EVIO_VUE,$solicitudPendiente['observacion']);
								$controladorVUE->finalizar($formulario,'Atendida');
							break;
							
							case '101-031':
								$controladorVUE->actualizarEstadoVUE($formulario,SUBSANACION_REQUERIDA,SOLICITUD_DE_EVIO_VUE,$solicitudPendiente['observacion']);
								$controladorVUE->finalizar($formulario,'Atendida');
							break;
							
							case '101-047':
								$controladorVUE->actualizarEstadoVUE($formulario,SUBSANACION_REQUERIDA,SOLICITUD_DE_EVIO_VUE,$solicitudPendiente['observacion']);
								$controladorVUE->finalizar($formulario,'Atendida');
							break;
							
							case '101-034':
								$controladorVUE->actualizarEstadoVUE($formulario,SUBSANACION_REQUERIDA,SOLICITUD_DE_EVIO_VUE,$solicitudPendiente['observacion']);
								$controladorVUE->finalizar($formulario,'Atendida');
							break;
							
							case '101-061':
							    $controladorVUE->actualizarEstadoVUE($formulario,SUBSANACION_REQUERIDA,SOLICITUD_DE_EVIO_VUE,$solicitudPendiente['observacion']);
							    $controladorVUE->finalizar($formulario,'Atendida');
							break;
								
							default:
								echo "Subsanacion solicitado requerida - desconocido";
							break;
			
						}
					break;
					
		
					case PAGO_AUTORIZADO:
						
						$controladorVUE->finalizar($formulario,'W');
						
						switch (substr($solicitudPendiente['formulario'], 0,7)){
							case '101-002':
								$controladorVUE->guardarTasa($formulario);
								$controladorVUE->finalizar($formulario,'Atendida');
							break;
							case '101-024':
								$controladorVUE->guardarTasa($formulario);
								$controladorVUE->finalizar($formulario,'Atendida');
							break;
							case '101-008':
								$controladorVUE->guardarTasa($formulario);
								$controladorVUE->finalizar($formulario,'Atendida');
							break;
							case '101-031':
								$controladorVUE->guardarTasa($formulario);
								$controladorVUE->finalizar($formulario,'Atendida');
							break;
							case '101-047':
								$controladorVUE->guardarTasa($formulario);
								$controladorVUE->finalizar($formulario,'Atendida');
							break;
							case '101-034':
								$controladorVUE->guardarTasa($formulario);
								$controladorVUE->finalizar($formulario,'Atendida');
							break;
							default:
								echo "pago autorizado solicitado - desconocido";
							break;
						}
						
					break;
					
					case PAGO_CONFIRMADO:
						
						$controladorVUE->finalizar($formulario,'W');
						
						switch ($solicitudPendiente['estado']){
								
							case 'cicloDos':
								$controladorVUE->finalizar($formulario,'cicloUno');
							break;
							case 'cicloUno':
								$controladorVUE->finalizar($formulario,'Por atender');
							break;
							case 'Por atender':
								
								$controladorVUE->actualizarEstadoVUE($formulario,PAGO_CONFIRMADO,FIN_TAREA,'Fin de pago confirmado');
								$recaudacionTasas = $controladorVUE->obtenerDatosRecaudacionTasas($formulario);
								
								switch (substr($solicitudPendiente['formulario'], 0,7)){
									case '101-002':
								
										$resultado = $formulario->recaudacionTasa($recaudacionTasas);
											
										switch ($resultado[0]){
											case SOLICITUD_APROBADA:
												$controladorVUE->ingresarSolicitudesXatenderGUIA('101-002-REQ', '320', '21', $formulario->numeroDeSolicitud, 'Por atender', 'Solicitud aprobada.');
												$controladorVUE->finalizar($formulario,'Atendida');
											break;
											case ERROR_TAREA:
												$controladorVUE->actualizarObservacionSolicitud($conexionVUE, $resultado[1], $solicitudPendiente['id']);
												$controladorVUE->finalizar($formulario,'Por atender');
											break;
											case ERROR_DE_VALIDACION:
												$controladorVUE->actualizarObservacionSolicitud($conexionVUE, $resultado[1], $solicitudPendiente['id']);
											break;
										}
								
									break;
									case '101-008':
										$formulario->recaudacionTasa($recaudacionTasas);
										$controladorVUE->finalizar($formulario,'Atendida');
									break;
									case '101-031':
										$resultado = $formulario->recaudacionTasa($recaudacionTasas);
								
										switch ($resultado[0]){
											case SOLICITUD_APROBADA:
												$controladorVUE->ingresarSolicitudesXatenderGUIA('101-031-REQ', '320', '21', $formulario->numeroDeSolicitud, 'Por atender', 'Solicitud aprobada.');
												$controladorVUE->finalizar($formulario,'Atendida');
											break;
											case ERROR_TAREA:
												$controladorVUE->actualizarObservacionSolicitud($conexionVUE, $resultado[1], $solicitudPendiente['id']);
												$controladorVUE->finalizar($formulario,'Por atender');
											break;
											case ERROR_DE_VALIDACION:
												$controladorVUE->actualizarObservacionSolicitud($conexionVUE, $resultado[1], $solicitudPendiente['id']);
											break;
										}
								
										//$controladorVUE->aprobarSolicitud($formulario, 'Solicitud aprobada');
										//$controladorVUE->enviarDocumentoAdjuntos($formulario, '320', 'D:\attach\DEV\documentosGUIA\fitosanitario\ ');
										//$controladorVUE->verificarConsumoWebServicesBanano($formulario, 'Aprobación');
								
									break;
									case '101-047':
										$formulario->recaudacionTasa($recaudacionTasas);
										//$controladorVUE->aprobarSolicitud($formulario, 'Solicitud aprobada');
										$controladorVUE->finalizar($formulario,'Atendida');
									break;
									case '101-034':
										$resultado = $formulario->recaudacionTasa($recaudacionTasas);
								
										switch ($resultado[0]){
											case SOLICITUD_APROBADA:
												$controladorVUE->ingresarSolicitudesXatenderGUIA('101-034-REQ', '320', '21', $formulario->numeroDeSolicitud, 'Por atender', 'Solicitud aprobada.');
												$controladorVUE->finalizar($formulario,'Atendida');
											break;
											case ERROR_TAREA:
												$controladorVUE->actualizarObservacionSolicitud($conexionVUE, $resultado[1], $solicitudPendiente['id']);
												$controladorVUE->finalizar($formulario,'Por atender');
											break;
											case ERROR_DE_VALIDACION:
												$controladorVUE->actualizarObservacionSolicitud($conexionVUE, $resultado[1], $solicitudPendiente['id']);
											break;
										}
								
										//$controladorVUE->aprobarSolicitud($formulario, 'Solicitud aprobada');
									break;
									default:
										echo "Pago confirmado  - desconocido";
								}
							break;
									
						}
						
					break;
					
					case SOLICITUD_APROBADA:
						
						$controladorVUE->finalizar($formulario,'W');
						
						if($solicitudPendiente['codigo_verificacion'] == '21'){
							switch (substr($solicitudPendiente['formulario'], 0,7)){
								case '101-002':
									$controladorVUE->aprobarSolicitud($formulario, 'Solicitud aprobada');
									$controladorVUE->envioAUCP($formulario);
									$controladorVUE->crearAnexoRequisitos($formulario);
									$controladorVUE->enviarDocumentoAdjuntos($formulario, '320', 'D:\attach\DEV\documentosGUIA\importacion\ ');
									//$controladorVUE->enviarDocumentoAdjuntos($formulario, '320', 'D:\attach\PROD\documentosGUIA\importacion\ ');
									$controladorVUE->finalizar($formulario,'Atendida');
									break;
								case '101-024':
									$controladorVUE->aprobarSolicitud($formulario, 'Solicitud aprobada');
									$controladorVUE->envioAUCP($formulario);
									$controladorVUE->finalizar($formulario,'Atendida');
									break;
								case '101-008':
									$controladorVUE->aprobarSolicitud($formulario, 'Solicitud aprobada');
									$controladorVUE->crearAnexoRequisitos($formulario);
									$controladorVUE->enviarDocumentoAdjuntos($formulario, '320', 'D:\attach\DEV\documentosGUIA\zoosanitario\ ');
									//$controladorVUE->enviarDocumentoAdjuntos($formulario, '320', 'D:\attach\PROD\documentosGUIA\zoosanitario\ ');
									$controladorVUE->finalizar($formulario,'Atendida');
									break;
								case '101-031':
									$controladorVUE->aprobarSolicitud($formulario, 'Solicitud aprobada');
									$controladorVUE->crearAnexoRequisitos($formulario);
									$controladorVUE->enviarDocumentoAdjuntos($formulario, '320', 'D:\attach\DEV\documentosGUIA\fitosanitario\ ');
									//$controladorVUE->enviarDocumentoAdjuntos($formulario, '320', 'D:\attach\PROD\documentosGUIA\fitosanitario\ ');
									//$controladorVUE->verificarConsumoWebServicesBanano($formulario, 'Aprobación');
									$controladorVUE->finalizar($formulario,'Atendida');
							
									break;
								case '101-047':
									$controladorVUE->aprobarSolicitud($formulario, 'Solicitud aprobada');
									$controladorVUE->finalizar($formulario,'Atendida');
									break;
								case '101-034':
									$controladorVUE->aprobarSolicitud($formulario, 'Solicitud aprobada');
									//$controladorVUE->enviarDocumentoAdjuntos($formulario, '320', 'D:\attach\DEV\documentosGUIA\fitosanitarioExportacion\ ');
									//$controladorVUE->enviarDocumentoAdjuntos($formulario, '320', 'D:\attach\PROD\documentosGUIA\fitosanitarioExportacion\ ');
									//$controladorVUE->verificarConsumoWebServicesBanano($formulario, 'Aprobación');
									$controladorVUE->finalizar($formulario,'Atendida');
									break;
								case '101-061':
								    $controladorVUE->aprobarSolicitud($formulario, 'Solicitud aprobada');
								    $controladorVUE->envioAUCP($formulario);
								    $controladorVUE->crearAnexoRequisitos($formulario);
								    $controladorVUE->enviarDocumentoAdjuntos($formulario, '320', 'D:\attach\DEV\documentosGUIA\TransitoInternacional\ ');
								    //$controladorVUE->enviarDocumentoAdjuntos($formulario, '320', 'D:\attach\PROD\documentosGUIA\importacion\ ');
								    $controladorVUE->finalizar($formulario,'Atendida');
								    break;
								default:
									echo "Aprobación de solicitado requerida - desconocido";
							}
						}else if($solicitudPendiente['codigo_verificacion'] == '22'){
							switch (substr($solicitudPendiente['formulario'], 0,7)){
								case '101-002':
									$formulario->generarFacturaProcesoAutomatico();
									$controladorVUE->finalizar($formulario,'Atendida');
								break;
								case '101-031':
									$formulario->generarFacturaProcesoAutomatico();
									$controladorVUE->finalizar($formulario,'Atendida');
								break;
								case '101-034':
									$formulario->generarFacturaProcesoAutomatico();
									$controladorVUE->finalizar($formulario,'Atendida');
								break;
								default:
									echo "Aprobación de solicitado requerida - desconocido";
									
							}
						}
						
						
					break;
					
					case SOLICITUD_NO_APROBADA:
					
						$controladorVUE->finalizar($formulario,'W');
					
						switch (substr($solicitudPendiente['formulario'], 0,7)){
							case '101-001':
								$controladorVUE->actualizarEstadoVUE($formulario,SOLICITUD_NO_APROBADA,SOLICITUD_DE_EVIO_VUE, $solicitudPendiente['observacion']);
								$controladorVUE->finalizar($formulario,'Atendida');
							break;
							case '101-002':
								$controladorVUE->actualizarEstadoVUE($formulario,SOLICITUD_NO_APROBADA,SOLICITUD_DE_EVIO_VUE, $solicitudPendiente['observacion']);
								$controladorVUE->finalizar($formulario,'Atendida');
							break;
							case '101-024':
								$controladorVUE->actualizarEstadoVUE($formulario,SOLICITUD_NO_APROBADA,SOLICITUD_DE_EVIO_VUE, $solicitudPendiente['observacion']);
								$controladorVUE->finalizar($formulario,'Atendida');
							break;
							case '101-008':
								$controladorVUE->actualizarEstadoVUE($formulario,SOLICITUD_NO_APROBADA,SOLICITUD_DE_EVIO_VUE, $solicitudPendiente['observacion']);
								$controladorVUE->finalizar($formulario,'Atendida');
							break;
							case '101-031':
								$controladorVUE->actualizarEstadoVUE($formulario,SOLICITUD_NO_APROBADA,SOLICITUD_DE_EVIO_VUE, $solicitudPendiente['observacion']);
								$controladorVUE->finalizar($formulario,'Atendida');
							break;
							case '101-034':
								$controladorVUE->actualizarEstadoVUE($formulario,SOLICITUD_NO_APROBADA,SOLICITUD_DE_EVIO_VUE, $solicitudPendiente['observacion']);
								$controladorVUE->finalizar($formulario,'Atendida');
							break;
							case '101-047':
								$controladorVUE->actualizarEstadoVUE($formulario,SOLICITUD_NO_APROBADA,SOLICITUD_DE_EVIO_VUE, $solicitudPendiente['observacion']);
								$controladorVUE->finalizar($formulario,'Atendida');
							break;
							case '101-061':
							    $controladorVUE->actualizarEstadoVUE($formulario,SOLICITUD_NO_APROBADA,SOLICITUD_DE_EVIO_VUE, $solicitudPendiente['observacion']);
							    $controladorVUE->finalizar($formulario,'Atendida');
							break;
							default:
								echo "Solicitud no aprobada, formulario desconocido";
						}
						break;
						
					case SOLICITUD_DE_CORRECION_NO_APROBADA:
							
						$controladorVUE->finalizar($formulario,'W');
							
						switch (substr($solicitudPendiente['formulario'], 0,7)){
							case '101-002':
								$controladorVUE->actualizarEstadoVUE($formulario,SOLICITUD_DE_CORRECION_NO_APROBADA,SOLICITUD_DE_EVIO_VUE, $solicitudPendiente['observacion']);
								$controladorVUE->finalizar($formulario,'Atendida');
								break;
							case '101-061':
							    $controladorVUE->actualizarEstadoVUE($formulario,SOLICITUD_DE_CORRECION_NO_APROBADA,SOLICITUD_DE_EVIO_VUE, $solicitudPendiente['observacion']);
							    $controladorVUE->finalizar($formulario,'Atendida');
							break;
							default:
								echo "Solicitud no aprobada, formulario desconocido";
						}
						break;
						
					case SOLICITUD_DE_CORRECION_APROBADA:
					
						$controladorVUE->finalizar($formulario,'W');
					
						switch (substr($solicitudPendiente['formulario'], 0,7)){
							case '101-002':
									$controladorVUE->crearAnexoRequisitos($formulario);
									$controladorVUE->enviarDocumentoAdjuntos($formulario, '340', 'D:\attach\DEV\documentosGUIA\importacion\ ');
									//$controladorVUE->enviarDocumentoAdjuntos($formulario, '340', 'D:\attach\PROD\documentosGUIA\importacion\ ');
									$controladorVUE->modificarSolicitud($formulario, 'Solicitud ampliada');
									$controladorVUE->actualizarAUCP($formulario, SOLICITUD_AMPLIADA);
									$controladorVUE->cambioEstadoAUCP($formulario, '35');
									$controladorVUE->finalizar($formulario,'Atendida');
							break;
							
							case '101-061':
							    $controladorVUE->crearAnexoRequisitos($formulario);
							    $controladorVUE->enviarDocumentoAdjuntos($formulario, '340', 'D:\attach\DEV\documentosGUIA\TransitoInternacional\ ');
							    //$controladorVUE->enviarDocumentoAdjuntos($formulario, '340', 'D:\attach\PROD\documentosGUIA\importacion\ ');
							    $controladorVUE->actualizarAUCP($formulario, SOLICITUD_AMPLIADA);
							    $controladorVUE->cambioEstadoAUCP($formulario, '35');
							    $controladorVUE->finalizar($formulario,'Atendida');
							    break;

							default:
								echo "Solicitud no aprobada, formulario desconocido";
						}
					break;
					
					case RECTITICACION_REALIZADA:
					
						$controladorVUE->finalizar($formulario,'W');
						
					switch ($solicitudPendiente['estado']){
							case 'Por atender':
								switch (substr($solicitudPendiente['formulario'], 0,7)){
									case '101-002':
										$controladorVUE->rectificarSolicitud($formulario);
										$controladorVUE->finalizar($formulario,'Por rectificar');
									break;
									default:
										echo "Solicitud no rectificada, formulario desconocido";
								}
							break;
							case 'Por rectificar':
									switch (substr($solicitudPendiente['formulario'], 0,7)){
										case '101-002':
											$controladorVUE->actualizarAUCP($formulario, SOLICITUD_DE_CORRECION_APROBADA);
											$controladorVUE->actualizarAUCP($formulario, SOLICITUD_AMPLIADA);
											$controladorVUE->cambioEstadoAUCP($formulario, '34');
											$controladorVUE->crearAnexoRequisitos($formulario);
											//$controladorVUE->enviarDocumentoAdjuntos($formulario, '330', 'D:\attach\DEV\documentosGUIA\importacion\ ');
											$controladorVUE->enviarDocumentoAdjuntos($formulario, '330', 'D:\attach\PROD\documentosGUIA\importacion\ ');
											$controladorVUE->actualizarEstadoVUE($formulario, RECTITICACION_REALIZADA, SOLICITUD_DE_EVIO_VUE, 'Solicitud rectificada.');
											$controladorVUE->finalizar($formulario,'Atendida');
										break;
										default:
											echo "Solicitud no rectificada, formulario desconocido";
									}
							break;
						}
					break;
						
					case INSPECCION_PROGRAMADA:
						$controladorVUE->finalizar($formulario,'W');
						
						switch (substr($solicitudPendiente['formulario'], 0,7)){
							case '101-034':
								
								$controladorVUE->actualizarEstadoVUE($formulario,INSPECCION_PROGRAMADA,SOLICITUD_DE_EVIO_VUE, $solicitudPendiente['observacion']);
								$controladorVUE->finalizar($formulario,'Atendida');
								
							break;
							default:
								echo "Solicitud de inspeccion programada, formulario desconocido";
						}
						
					break;
					
					case INSPECCION_REALIZADA:
						$controladorVUE->finalizar($formulario,'W');
					
						switch (substr($solicitudPendiente['formulario'], 0,7)){
							case '101-034':
					
								$controladorVUE->actualizarEstadoVUE($formulario,INSPECCION_REALIZADA,SOLICITUD_DE_EVIO_VUE, $solicitudPendiente['observacion']);
								$controladorVUE->finalizar($formulario,'Atendida');
					
								break;
							default:
								echo "Solicitud de inspeccion programada, formulario desconocido";
						}
					
						break;
					
					case SOLICITUD_RECEPTADA:
					case DESISTIMIENTO_APROBADO:
					case ANULACION_APROBADA:
					case AUCP_ENVIADO_A_ADUANA://Inicia en guia, verificar error 23 ERROR_ENVIO_VUE
						//TODO: notificar error a guia
						break;
						//TODO: Modificación y AUCP
					default:
						echo "desconocido";
				}
				echo '<br/><strong>FIN</strong></p>';
			}else{
				$controladorVUE->actualizarObservacionSolicitud($conexionVUE, 'Solicitud no procesada por petición de reverso.', $solicitudPendiente['id']);
			}
			}
		}
	}else{
		
		$minutoS1=microtime(true);
		$minutoS2=microtime(true);
		$tiempo=$minutoS2-minutoS1;
		$xcadenota = "FECHA ".date("d/m/Y")." ".date("H:i:s");
		$xcadenota.= "; IP REMOTA ".$_SERVER['REMOTE_ADDR'];
		$xcadenota.= "; SERVIDOR HTTP ".$_SERVER['HTTP_REFERER'];
		$xcadenota.= "; SEGUNDOS ".$tiempo."\n";
		$arch = fopen("../../aplicaciones/logs/cron/proceso_vue_".date("d-m-Y").".txt", "a+");
		fwrite($arch, $xcadenota);
		fclose($arch);

	}

	?>

</body>
</html>
