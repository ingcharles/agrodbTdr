<?php

require_once '../../../clases/Conexion.php';
require_once '../../../clases/ControladorMonitoreo.php';
require_once '../../../clases/ControladorReportes.php';
require_once '../../../clases/ControladorFinanciero.php';
require_once '../../../clases/ControladorCertificados.php';
require_once '../../../clases/ControladorFinancieroAutomatico.php';
require_once '../../../clases/ControladorRevisionSolicitudesVUE.php';
require_once '../../../clases/ControladorFitosanitarioExportacion.php';
require_once '../../../clases/ControladorCertificadoFito.php';
require_once '../../../clases/ControladorModificacionProductoRia.php';
require_once '../../../clases/Constantes.php';

if($_SERVER['REMOTE_ADDR'] == ''){
//if(1){
	$conexion = new Conexion();
	$cm = new ControladorMonitoreo();
	$cf = new ControladorFinanciero();
	$cc = new ControladorCertificados();
	$cfa = new ControladorFinancieroAutomatico();
	$crs = new ControladorRevisionSolicitudesVUE();
	$cfe = new ControladorFitosanitarioExportacion();
	$ccf = new ControladorCertificadoFito();
	$cmp = new ControladorModificacionProductoRia();
	$jru = new ControladorReportes(false);

	$resultadoMonitoreo = $cm->obtenerCronPorCodigoEstado($conexion, 'CRON_FACT_GEN_ORDEN');

	if($resultadoMonitoreo){
    //if(1){
		define('IN_MSG','<br/> >>> ');

		$fecha = date("Y-m-d h:m:s");
		echo IN_MSG . '<p><strong> INICIO SOLICITUD '.$fecha.'</strong></p>';

		//OBTENER LOS REGISTROS A SER ENVIADOS AL ESQUEMA FINANCIERO PARA FACTURACION
		$datosPorGenerar = $cfa->obtenerDatosGenerarOrdenPagoAutomatico($conexion, " in ('Por atender', 'Por atender saldo')");
		$banderaGenerarOrden = true;
		$formaPago = "";
		
		$rutaFecha = date('Y').'/'.date('m').'/'.date('d');

		while ($fila = pg_fetch_assoc($datosPorGenerar)){
		    
			echo IN_MSG . $fila['id_vue'];				   
		    $idFinancieroCabecera = $fila['id_financiero_cabecera'];
		    $cfa->actualizarEstadoFinancieroAutomaticoCabecera($conexion, $idFinancieroCabecera, 'W');
		    
		    echo IN_MSG.'Número de solicitud: '. $idFinancieroCabecera;
		    
		    $detalleProducto = $cfa->obtenerDatosDetalleOrdenPagoAutomaticaPorIdentificador($conexion, $idFinancieroCabecera);
		    
		    //TODO: Revisar proceso para obtener código de provincia para financiero.
		    
		    switch ($fila['tipo_solicitud']){      
		        
		        case 'LABORATORIOS':
		            //TODO:Revisar como obtener provincia.
		            $datosRecaudador = pg_fetch_assoc($cf->obtenerDatosRecaudador($conexion, $fila['provincia_firmante']));
                    $identificacionCliente = $fila['identificador_operador'];
                    $identificadorTabla = $fila['id_solicitud_tabla'];
                    
                    $datosCliente = $cc->listaComprador($conexion,$identificacionCliente);
                    
                    ///////////////////////////////////////
                    $tipoFormulario = 'Laboratorios';
                    $tipoInspector = 'Financiero';
                    ///////////////////////////////////////
                    
                    $identificadorCliente = pg_fetch_result($datosCliente, 0, 'identificador');
		            
		        break;
		        
		        case 'FitosanitarioExportacion':
		            
		            $fitosanitarioExportacion = pg_fetch_assoc($cfe->buscarFitosanitarioExportacionVUE($conexion, $fila['id_vue']));
		            $datosRecaudador = pg_fetch_assoc($cf->obtenerDatosRecaudadorPorProvinciaEstadoFirma($conexion, /*$fitosanitarioExportacion['id_provincia_revision']*/ 259, 'SI'));
		            $identificacionCliente = $fitosanitarioExportacion['numero_identificacion_solicitante'];
		            $identificadorTabla = $fitosanitarioExportacion['id_fitosanitario_exportacion'];
		            
		            $datosCliente = $cc->listaComprador($conexion,$identificacionCliente);
		            
		            ///////////////////////////////////////
		            $tipoFormulario = 'FitosanitarioExportacion';
		            $tipoInspector = 'Financiero';
		            ///////////////////////////////////////
		            
		            if(pg_num_rows($datosCliente)==0){
		                
		                switch (strlen($fitosanitarioExportacion['numero_identificacion_solicitante'])){
		                    case '10':
		                        $tipoCliente = '05';
		                        break;
		                    case '13':
		                        $tipoCliente = '04';
		                        break;
		                }
		                
		                $cliente = $cc -> guardarNuevoCliente($conexion, $fitosanitarioExportacion['numero_identificacion_solicitante'], $tipoCliente, $fitosanitarioExportacion['razon_social_solicitante'], $fitosanitarioExportacion['direccion_solicitante'], $fitosanitarioExportacion['telefono_solicitante'], $fitosanitarioExportacion['correo_electronico_solicitante']);
		                $identificadorCliente = pg_fetch_result($cliente, 0, 'identificador');
		                
		            }else{
		                $identificadorCliente = pg_fetch_result($datosCliente, 0, 'identificador');
		            }
		            		                   
		        break;
		        
				case 'certificadoFito':
		            
		            $numeroSolicitud = $fila['numero_solicitud'];
		            $totalPagar = $fila['total_pagar'];
		            $formaPago = $fila['metodo_pago'];
		            $estadoFinanciero = 3;
		            		            
		            //Variable de recaudador que firmara las facturas automaticas
		            $identificadorFirmante = $constg::IDENTIFICADOR_RECAUDADOR;
		            $datosRecaudador = pg_fetch_assoc($cf->obtenerDatosRecaudador($conexion, $identificadorFirmante));
		            
		            $certificadoFitosanitario = pg_fetch_assoc($ccf->buscarCertificadoFitosanitario($conexion, $fila['id_vue']));
		            $identificadorCliente = $certificadoFitosanitario['identificador_solicitante'];
		            $idCertificadoFitosanitario = $certificadoFitosanitario['id_certificado_fitosanitario'];		            
		            
		            $tipoFormulario = 'certificadoFito';
		            $tipoInspector = 'Financiero';
		            $identificadorTabla = $certificadoFitosanitario['id_certificado_fitosanitario'];
		            		            
		            $listaCliente =  $cc->listaComprador($conexion, $identificadorCliente);
		            $datosCliente = pg_fetch_assoc($listaCliente);
		            
		            $correoCliente = $datosCliente['correo'];
		            
		            if($formaPago == "saldo"){
		            	
		            	$estadoFinanciero = 5;
		                
		                //Se obtiene el total de todas las ordenes generadas que esten "Por atender" y se resta del saldo actual
		                $qTotalOrdenesGeneradas = $cfa->obtenerTotalOrdenesGeneradasXIdentificadorOperador($conexion, $totalPagar, $identificadorCliente, 'saldoAgr');
		                $totalOrdenesGeneradas =  pg_fetch_assoc($qTotalOrdenesGeneradas);
		                
		                $saldoDisponibleOperador = $totalOrdenesGeneradas['saldo_disponible'];
		                                        
		                if ($saldoDisponibleOperador >= $totalPagar){
                            $banderaGenerarOrden = true;
                        }else{
                            $banderaGenerarOrden = false;                    
                            //TODO:Programar regreso de procesos EVRIFICAR SI SE BORRA DE LA TABLA
                            $cfa->actualizarEstadoFinancieroAutomaticoCabecera($conexion, $idFinancieroCabecera, 'Por atender saldo');
                        }

		            }
		            
		            $ccf->actualizarEstadoSolicitudXIdCertificadoFitosanitario($conexion, $identificadorTabla, 'verificacion');
		            //$ccf->actualizarEstadoExportadoresProductosXIdCertificadoFitosanitario($conexion,$identificadorTabla, 'verificacion');
					
			  
					 break;
				case 'modificacionProductoRia':

				    $formaPago = $fila['metodo_pago'];
				    $estadoFinanciero = 3;
				    
				    //Variable de recaudador que firmara las facturas automaticas
				    $identificadorFirmante = $constg::IDENTIFICADOR_RECAUDADOR;
				    $datosRecaudador = pg_fetch_assoc($cf->obtenerDatosRecaudador($conexion, $identificadorFirmante));
				    
				    $solicitudProducto = pg_fetch_assoc($cmp->abrirSolicitudPorNumeroSolicitud($conexion, $fila['id_vue']));
				    $identificadorCliente = $solicitudProducto['identificador_operador'];
				    $idSolicitudProducto = $solicitudProducto['id_solicitud_producto'];
				    
				    $tipoFormulario = 'modificacionProductoRia';
				    $tipoInspector = 'Financiero';
				    $identificadorTabla = $idSolicitudProducto;
				    
				    $cmp->actualizarEstadoSolicitudPorIdSolicitudProducto($conexion, $idSolicitudProducto, 'verificacion');

				break;
		        default:
		            echo 'Tipo de solicitud no definida.';
		        
		    }
		    
		    //-----------------------------------
		    
			if($banderaGenerarOrden){
				
		    $idGrupoAsignado= pg_fetch_assoc($crs->guardarNuevoInspector($conexion, $datosRecaudador['identificador_firmante'], $datosRecaudador['identificador_firmante'], $tipoFormulario, $tipoInspector));
		    $crs->guardarGrupo($conexion, $identificadorTabla, $idGrupoAsignado['id_grupo'], 'Financiero');
		    
		    $ordenPago = $crs->buscarSerialOrden($conexion, $idGrupoAsignado['id_grupo'], $tipoInspector);
		    
		    //Guarda inspector, monto y fecha para inspeccion financiera
		    $idFinanciero = $crs->asignarMontoSolicitud($conexion, $idGrupoAsignado['id_grupo'], $datosRecaudador['identificador_firmante'], $fila['total_pagar'], pg_fetch_result($ordenPago, 0, 'orden'));
		    echo IN_MSG .' Creación de grupo y seguimiento financiero.';
		    
		    $anioActual = date('Y');
		    
		    $documento = pg_fetch_assoc($cc -> generarNumeroDocumento($conexion, '%AGR-'.$anioActual.'%'));
		    $tmp= explode("-", $documento['numero']);
		    $incremento = end($tmp)+1;
		    $numeroSolicitud = 'AGR-'.$anioActual.'-'.str_pad($incremento, 9, "0", STR_PAD_LEFT);
		    
		    echo IN_MSG .' Creación de número de orden de pago.';
		    
		    $ordenPago = pg_fetch_assoc($cc -> guardarOrdenPago($conexion, $identificadorCliente, $numeroSolicitud, $fila['total_pagar'], 'Imposición automática por sistema GUIA.', $datosRecaudador['oficina'], $datosRecaudador['provincia'], $datosRecaudador['id_provincia'], $datosRecaudador['identificador_firmante'], $identificadorTabla, $tipoFormulario, $idGrupoAsignado['id_grupo'], $estadoFinanciero, $formaPago));
		    
		    $cc->actualizarPorcentajeIvaOrdenPago($conexion, $ordenPago['id_pago'], $datosRecaudador['iva']);
		    
		    echo IN_MSG .' Creación de cabecera de orden de pago número '.$ordenPago['id_pago'];
		    
		    while ($producto = pg_fetch_assoc($detalleProducto)){
		        $cc -> guardarTotal($conexion, $ordenPago['id_pago'], $producto['id_servicio'], $producto['concepto_orden'],$producto['cantidad'],$producto['descuento'],$producto['precio_unitario'],$producto['iva'],$producto['total']);
		    }
		    
		    echo IN_MSG .' Creación de detalle de orden de pago.';
		    
		    //Generando orden de pago
		    $fecha = time ();
		    $fecha_partir1=date ( "h" , $fecha ) ;
		    $fecha_partir2=date ( "i" , $fecha ) ;
		    $fecha_partir4=date ( "s" , $fecha ) ;
		    $fecha_partir3=$fecha_partir1-1;
		    $reporte="ReporteOrden";
		    $filename = $reporte."_".$identificadorCliente."_".date("Y-m-d")."_".$fecha_partir3.'_'.$fecha_partir2.'_'.$fecha_partir4.'.pdf';
		    $nombreArchivo = $reporte."_".$identificadorCliente."_".date("Y-m-d")."_".$fecha_partir3.'_'.$fecha_partir2.'_'.$fecha_partir4;
		    
		    if (!file_exists('../../financiero/documentos/ordenPago/'.$rutaFecha.'/')){
		        mkdir('../../financiero/documentos/ordenPago/'.$rutaFecha.'/', 0777,true);
		    }
		    
		    $ReporteJasper= '/aplicaciones/financiero/reportes/reporteOrden.jrxml';
		    $salidaReporte= '/aplicaciones/financiero/documentos/ordenPago/'.$rutaFecha.'/'.$filename;
		    $rutaArchivo= 'aplicaciones/financiero/documentos/ordenPago/'.$rutaFecha.'/'.$filename;
		    $compensacion = '';
		    
		    $parameters['parametrosReporte'] = array(
		    	'idpago' => (int)$ordenPago['id_pago'],
		    	'compensacion'=> $compensacion
		    );
		    
		    $jru->generarReporteJasper($ReporteJasper,$parameters,$conexion,$salidaReporte,'facturacion');
		    
		    $cc -> guardarRutaOrdenPago($conexion,$ordenPago['id_pago'],$rutaArchivo);
		    
		    echo IN_MSG .' Generación de archivo de orden de pago.';
		    
		    $cfa->actualizarIdOrdenPagoFinancieroAutomaticoCabecera($conexion, $idFinancieroCabecera, $ordenPago['id_pago']);
		    $cfa->actualizarEstadoFinancieroAutomaticoCabecera($conexion, $idFinancieroCabecera, 'Atendida');
		    
		    //-------------------------------------
		    
		    switch ($fila['tipo_solicitud']){
		        case 'LABORATORIOS':
		            
		        break;
		        case 'FitosanitarioExportacion':
		            $cfa->ingresarSolicitudesXatenderGUIA($conexion, '101-034-REQ', '120', '21', $fila['id_vue'], 'Por atender','Imposición automática por sistema GUIA.');
		        break;
				case 'certificadoFito';
				
					require_once '../../../clases/ControladorMail.php';
					$cMail = new ControladorMail();
						
							//Inicio envío de orden de pago al operador         		        
							if($formaPago == "saldo"){
						
								$asunto = 'Orden de pago AGROCALIDAD esquema OFFLINE';
								$cuerpoMensaje = 'Estimado Cliente: <br/><br/>AGROCALIDAD adjunta su orden de pago.' ;
								
								$destinatario = array();
								
								array_push($destinatario, $correoCliente);
								
								$adjuntos = array();
								
								array_push($adjuntos, $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.$salidaReporte);
								
								echo IN_MSG . 'Insertar registro de envío de correo electronico.';
								
								$codigoModulo = 'PRG_FINANCIERO';
								$tablaModulo = 'g_financiero.orden_pago';
								
								$qGuardarCorreo = $cMail->guardarCorreo($conexion, $asunto, $cuerpoMensaje, 'Por enviar', $codigoModulo, $tablaModulo, $ordenPago['id_pago']);
								$idCorreo = pg_fetch_result($qGuardarCorreo, 0, 'id_correo');
								
								$cMail->guardarDestinatario($conexion, $idCorreo, $destinatario);
								
								$cMail->guardarDocumentoAdjunto($conexion, $idCorreo, $adjuntos);
								
								//Fin envío orden de pago operador
								
								//Se guarda el detalle d pago
								$cc -> guardarPagoOrden($conexion, $ordenPago['id_pago'], 'now()', 0, 'Pago con saldo', 'Saldo disponible', $fila['total_pagar'], 0, 0, 0);
								
								//Se descuenta del saldo el valor de la orden
								$saldoActual = $saldoDisponibleOperador - $totalPagar;
								$cf->guardarNuevoSaldoOperadorEgreso($conexion, $ordenPago['id_pago'], $totalPagar, $saldoActual, $identificadorCliente);
								
								$cfa->actualizarEstadoYFechaFacturaFinancieroAutomaticoCabeceraXIdPago($conexion, $ordenPago['id_pago'], 'Por atender');
						   
								$ccf->actualizarEstadoSolicitudXIdCertificadoFitosanitario($conexion, $idCertificadoFitosanitario, 'verificacion');
								
							}
							
						break;
		        default:
		            echo 'Tipo de solicitud no definida.';
			}

			echo '<br/><strong>FIN</strong></p>';
			
			}
  
		}

	}

}else{

	$minutoS1=microtime(true);
	$minutoS2=microtime(true);
	$tiempo=$minutoS2-$minutoS1;
	$xcadenota = "FECHA ".date("d/m/Y")." ".date("H:i:s");
	$xcadenota.= "; IP REMOTA ".$_SERVER['REMOTE_ADDR'];
	$xcadenota.= "; SERVIDOR HTTP ".$_SERVER['HTTP_REFERER'];
	$xcadenota.= "; SEGUNDOS ".$tiempo."\n";
	$arch = fopen("../../../aplicaciones/logs/cron/automatico_financiero_orden".date("d-m-Y").".txt", "a+");
	fwrite($arch, $xcadenota);
	fclose($arch);

}


?>