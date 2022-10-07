<?php
require_once '../../../clases/Conexion.php';
require_once '../../../clases/ControladorAreas.php';
require_once '../../../clases/ControladorMonitoreo.php';
require_once '../../../clases/ControladorReportes.php';
require_once '../../../clases/ControladorCatalogos.php';
require_once '../../../clases/ControladorFinanciero.php';
require_once '../../../clases/ControladorCertificados.php';
require_once '../../../clases/ControladorImportaciones.php';
require_once '../../../clases/ControladorFinancieroAutomatico.php';
require_once '../../../clases/ControladorRevisionSolicitudesVUE.php';
require_once '../../../clases/ControladorFitosanitarioExportacion.php';
require_once '../../../clases/ControladorFitosanitario.php';
require_once '../../../clases/ControladorCertificadoFito.php';

if($_SERVER['REMOTE_ADDR'] == ''){
//if(1){
	$conexion = new Conexion();
	$cm = new ControladorMonitoreo();
	$cf = new ControladorFinanciero();
	$cc = new ControladorCertificados();
	$cfa = new ControladorFinancieroAutomatico();
	$crs = new ControladorRevisionSolicitudesVUE();
	$cfe = new ControladorFitosanitarioExportacion();
	$jru = new ControladorReportes(false);

	define('IN_MSG','<br/> >>> ');

	$resultadoMonitoreo = $cm->obtenerCronPorCodigoEstado($conexion, 'CRON_FACT_PG_ELECTRO');

	if($resultadoMonitoreo){
	//if(1){
		$fecha = date("Y-m-d h:m:s");
		echo IN_MSG . '<p><strong> INICIO REGISTRO SOLICITUDES '.$fecha.'</strong></p>';


		//GENERAR FACTURA
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$datosFactura = $cfa->obtenerDatosGenerarFacturaAutomatico($conexion, " in ('Por atender', 'Por atender saldo')");				   
		$procesoFactura = true;
		
		while ($listaFactura = pg_fetch_assoc($datosFactura)){
			
			$cabeceraOrdenPago = pg_fetch_assoc($cc->abrirOrdenPago($conexion, $listaFactura['id_orden_pago']));
			
			switch ($cabeceraOrdenPago['tipo_solicitud']){
			    
			    case 'certificadoFito':			        
			        
			        $ccf = new ControladorCertificadoFito();
			        $totalPagar = $cabeceraOrdenPago['total_pagar'];
			        $formaPago = $cabeceraOrdenPago['metodo_pago'];

			        $certificadoFitosanitario = pg_fetch_assoc($ccf->buscarCertificadoFitosanitario($conexion, $listaFactura['id_vue']));
			        $idCertificadoFitosanitario = $certificadoFitosanitario['id_certificado_fitosanitario'];
			        $identificadorCliente = $certificadoFitosanitario['identificador_solicitante'];
			        $tipoCertificado = $certificadoFitosanitario['tipo_certificado'];
			        
			        if($formaPago == "saldo" && $tipoCertificado == "otros"){			            
			            //Se obtiene el total de todas las ordenes generadas que esten "Por atender" y se resta del saldo actual
			            $qTotalOrdenesGeneradas = $cfa->obtenerTotalOrdenesGeneradasXIdentificadorOperador($conexion, $totalPagar, $identificadorCliente, 'saldoAgr');
			            $totalOrdenesGeneradas =  pg_fetch_assoc($qTotalOrdenesGeneradas);
			            
			            $saldoDisponibleOperador = $totalOrdenesGeneradas['saldo_disponible'];
			            
                        if ($saldoDisponibleOperador >= $totalPagar){		                    
                            //Se guarda el detalle de pago
                            $cc->guardarPagoOrden($conexion, $cabeceraOrdenPago['id_pago'], 'now()', 0, 'Pago con saldo', 'Saldo disponible', $cabeceraOrdenPago['total_pagar'], 0, 0, 0);
                            
                            //Se descuenta del saldo el valor de la orden
                            $saldoActual = $saldoDisponibleOperador - $totalPagar;
                            $cf->guardarNuevoSaldoOperadorEgreso($conexion, $cabeceraOrdenPago['id_pago'], $totalPagar, $saldoActual, $identificadorCliente);			              
                                                        
                        }else{
                            $procesoFactura = false;
                            $cfa->actualizarEstadoFacturaPorIdentificadorFinancieroAutomatico($conexion, $listaFactura['id_financiero_cabecera'], 'Por atender saldo');
                        }			            
			        }
			        
			    break;			        
			        
			}
			
			if($procesoFactura){

			echo IN_MSG . $listaFactura['id_financiero_cabecera'];
			
			$rutaFecha = date('Y').'/'.date('m').'/'.date('d');

			if($listaFactura['tipo_proceso'] == 'factura'){

				echo IN_MSG .' Generación de factura.';

				$idFinancieroCabecera = $listaFactura['id_financiero_cabecera'];
				$cfa->actualizarEstadoFacturaPorIdentificadorFinancieroAutomatico($conexion, $listaFactura['id_financiero_cabecera'], 'W');

				$codigoAmbiente = '1'; // 1-> Pruebas , 2-> Producción

				//$cabeceraOrdenPago = pg_fetch_assoc($cc->abrirOrdenPago($conexion, $listaFactura['id_orden_pago']));

				if($cabeceraOrdenPago['estado_sri'] == ''){

					$cc->actualizarTipoProcesoOrdenPago($conexion, $cabeceraOrdenPago['id_pago'], 'factura');

					$institucion = $cc -> listarDatosInstitucion($conexion,$cabeceraOrdenPago['identificador_usuario']);
					$datosInstitucion = pg_fetch_assoc($institucion);

					$iva = $cabeceraOrdenPago['porcentaje_iva'];

					$codigoIvaSRI = ($iva == 14 ? '3' : '2');
					$cantidadIvaSRI = ($iva == 14 ? '14.00' : '12.00');

					//Formato fecha
					$fecha = date('d').'/'.date('m').'/'.date('Y');
					$fechap = explode('/', $fecha);
					$fechaFactura = $fechap[0] . $fechap[1] . $fechap[2];

					//Datos Cliente
					$comprador = $cc -> listaComprador($conexion,$cabeceraOrdenPago['identificador_operador']);
					$datosComprador = pg_fetch_assoc($comprador);

					//Valores Factura
					$valoresDetalle = $cc -> obtenerDatosDetalleFactura($conexion,$cabeceraOrdenPago['id_pago']);
					$detalleValores =  pg_fetch_assoc($valoresDetalle);


					$auxliarNumeroFactura = pg_fetch_assoc($cc->obtenerNumeroFactura($conexion, $cabeceraOrdenPago['id_pago']));
					if($auxliarNumeroFactura['numero_factura'] == ''){
						$numero = pg_fetch_assoc($cc -> generarNumeroFactura($conexion, $datosInstitucion['ruc'], $datosInstitucion['numero_establecimiento'], $datosInstitucion['punto_emision']));
						$nFactura = ($numero['numero'] == ''? /*2001*/ 1 :$numero['numero']);
					}else{
						$nFactura = $auxliarNumeroFactura['numero_factura'];
					}

					$numeroSolicitud = str_pad($nFactura, 9, "0", STR_PAD_LEFT);

					$cc ->finalizarOrdenPago($conexion, $cabeceraOrdenPago['id_pago'], $cabeceraOrdenPago['total_pagar'], $datosInstitucion['ruc'], $numeroSolicitud, $datosInstitucion['numero_establecimiento'], $datosInstitucion['punto_emision']);

					echo IN_MSG .' Finalización de asignación de número de factura.';

					//Consultar detalle factura
					$detalleFactura	  = $cc->abrirDetalleFactura($conexion,$cabeceraOrdenPago['id_pago']);

					//Consulta si la oficina lleva contabilidad
					if($datosInstitucion['obligado_llevar_contabilidad'] == 't'){
						$contabilidad = 'SI';
					}else{
						$contabilidad = 'NO';
					}

					$fechaVigente = pg_fetch_assoc($cf->obtenerFechasContigenciaVigentes($conexion));
					$fechaActualSistema = date('Y-m-d H:i:s');

					$fechaContingenciaDesde = date('Y-m-d H:i:s', strtotime($fechaVigente['fecha_desde']));
					$fechaContingenciaHasta = date('Y-m-d H:i:s', strtotime($fechaVigente['fecha_hasta']));


					if($fechaActualSistema >= $fechaContingenciaDesde && $fechaActualSistema <= $fechaContingenciaHasta ){
						$codigoTipoEmision = '2'; // 2-> Emisión por indisponibilidad del sistema

						$verificarClaveContingencia = $cf->obtenerClaveContigenciaPorIdComprobante($conexion, $cabeceraOrdenPago['id_pago'], 'factura');

						if(pg_num_rows($verificarClaveContingencia)==0){

							$nuevaClaveContingencia = pg_fetch_assoc($cf->obtenerClaveContingencia($conexion));
							$cf->actualizarEstadoClaveContingencia($conexion, $nuevaClaveContingencia['id_clave_contingencia'], $cabeceraOrdenPago['id_pago'], 'factura');

							$claveContingencia = $nuevaClaveContingencia['clave'];

						}else{
							$claveContingencia = pg_fetch_result($verificarClaveContingencia, 0, 'clave');
						}


						$codigoXml = $fechaFactura.'01'.$claveContingencia.$codigoTipoEmision;
						$digitoVerificador =  $cc->calcularDigito($codigoXml);

					}else{
						$codigoTipoEmision = '1'; // 1-> Emisión normal

						//Clave acceso
						$auxCodigoNumerico = $fechaFactura.'01'.$datosInstitucion['ruc'].$codigoAmbiente.str_pad($datosInstitucion['numero_establecimiento'], 3, "0", STR_PAD_LEFT).str_pad($datosInstitucion['punto_emision'], 3, "0", STR_PAD_LEFT).$numeroSolicitud.$cabeceraOrdenPago['id_pago'];
						$codigoXml = $fechaFactura.'01'.$datosInstitucion['ruc'].$codigoAmbiente.str_pad($datosInstitucion['numero_establecimiento'], 3, "0", STR_PAD_LEFT).str_pad($datosInstitucion['punto_emision'], 3, "0", STR_PAD_LEFT).$numeroSolicitud;
						$codigoNumerico = $cc->calcularDigito($auxCodigoNumerico);
						$codigoXml = $codigoXml.str_pad($codigoNumerico, 8, "0", STR_PAD_LEFT).$codigoTipoEmision;
						$digitoVerificador =  $cc->calcularDigito($codigoXml);
					}

					echo IN_MSG .' Genración de XML factura.';
					//Generar archivo xml
					$xml = new DomDocument('1.0', 'UTF-8');
						
					//Nodo principal
					$root = $xml->createElement('factura');
					$root = $xml->appendChild($root);

					$atributo = $xml->createAttribute('id');
					$root->appendChild($atributo);
					$atributo_valor = $xml->createTextNode('comprobante');
					$atributo->appendChild($atributo_valor);

					$atributo = $xml->createAttribute('version');
					$root->appendChild($atributo);
					$atributo_valor = $xml->createTextNode('1.1.0');
					$atributo->appendChild($atributo_valor);

					$infoTributaria=$xml->createElement('infoTributaria');
					$infoTributaria =$root->appendChild($infoTributaria);

					$ambiente=$xml->createElement('ambiente',$codigoAmbiente);
					$ambiente =$infoTributaria->appendChild($ambiente);
					$tipoEmision=$xml->createElement('tipoEmision',$codigoTipoEmision);
					$tipoEmision =$infoTributaria ->appendChild($tipoEmision);
					$razonSocial=$xml->createElement('razonSocial',$datosInstitucion['razon_social']);
					$razonSocial =$infoTributaria ->appendChild($razonSocial);
					$nombreComercial=$xml->createElement('nombreComercial',$datosInstitucion['nombre_comercial']);
					$nombreComercial =$infoTributaria ->appendChild($nombreComercial);
					$ruc=$xml->createElement('ruc',$datosInstitucion['ruc']);
					$ruc =$infoTributaria ->appendChild($ruc);

					////////////////////

					$claveAcceso=$xml->createElement('claveAcceso',$codigoXml.$digitoVerificador);
					$claveAcceso =$infoTributaria ->appendChild($claveAcceso);
					$codDoc=$xml->createElement('codDoc','01');
					$codDoc =$infoTributaria ->appendChild($codDoc);
					$estab=$xml->createElement('estab',str_pad($datosInstitucion['numero_establecimiento'], 3, "0", STR_PAD_LEFT));
					$estab =$infoTributaria ->appendChild($estab);
					$ptoEmi=$xml->createElement('ptoEmi',str_pad($datosInstitucion['punto_emision'], 3, "0", STR_PAD_LEFT));
					$ptoEmi =$infoTributaria ->appendChild($ptoEmi);
					$secuencial=$xml->createElement('secuencial',$numeroSolicitud);
					$secuencial =$infoTributaria ->appendChild($secuencial);
					$dirMatriz=$xml->createElement('dirMatriz',$datosInstitucion['direccion']);
					$dirMatriz =$infoTributaria ->appendChild($dirMatriz);

					$infoFactura=$xml->createElement('infoFactura');
					$infoFactura =$root->appendChild($infoFactura);

					$fechaEmision=$xml->createElement('fechaEmision',$fecha);
					$fechaEmision =$infoFactura->appendChild($fechaEmision);
					
					//$contribuyenteEspecial=$xml->createElement('contribuyenteEspecial','1308');
					//$contribuyenteEspecial =$infoFactura ->appendChild($contribuyenteEspecial);

					$obligadoContabilidad=$xml->createElement('obligadoContabilidad',$contabilidad);
					$obligadoContabilidad =$infoFactura ->appendChild($obligadoContabilidad);

					$tipoIdentificacionComprador=$xml->createElement('tipoIdentificacionComprador',$datosComprador['tipo_identificacion']);
					$tipoIdentificacionComprador =$infoFactura ->appendChild($tipoIdentificacionComprador);
					$razonSocialComprador=$xml->createElement('razonSocialComprador', htmlspecialchars($datosComprador['razon_social']));
					$razonSocialComprador =$infoFactura ->appendChild($razonSocialComprador);
					$identificacionComprador=$xml->createElement('identificacionComprador',$datosComprador['identificador']);
					$identificacionComprador =$infoFactura ->appendChild($identificacionComprador);
					/*-------------------------------------------------------------------------------------------------------------*/
					$direccionComprador=$xml->createElement('direccionComprador',$datosComprador['direccion']);
					$direccionComprador =$infoFactura ->appendChild($direccionComprador);
					/*-------------------------------------------------------------------------------------------------------------*/
					$totalSinImpuestos=$xml->createElement('totalSinImpuestos',$detalleValores['total_sin_iva']+ $detalleValores['total_con_iva']);
					$totalSinImpuestos =$infoFactura ->appendChild($totalSinImpuestos);
					/*-------------------------------------------------------------------------------------------------------------*/
					if($detalleValores['subsidio']!= ''){
						$totalSubsidio = $xml->createElement('totalSubsidio',$detalleValores['subsidio']);
						$totalSubsidio = $infoFactura ->appendChild($totalSubsidio);
					}
					/*-------------------------------------------------------------------------------------------------------------*/
					$totalDescuento=$xml->createElement('totalDescuento',$detalleValores['descuento_sin_iva'] + $detalleValores['descuento_con_iva']);
					$totalDescuento =$infoFactura ->appendChild($totalDescuento);

					$totalConImpuestos=$xml->createElement('totalConImpuestos');
					$totalConImpuestos =$infoFactura->appendChild($totalConImpuestos);

					//////////////////

					if($detalleValores['total_sin_iva'] != 0){

						$totalImpuesto=$xml->createElement('totalImpuesto');
						$totalImpuesto =$totalConImpuestos->appendChild($totalImpuesto);
						$codigo=$xml->createElement('codigo','2');
						$codigo =$totalImpuesto->appendChild($codigo);
						$codigoPorcentaje=$xml->createElement('codigoPorcentaje','0');
						$codigoPorcentaje =$totalImpuesto->appendChild($codigoPorcentaje);
						$baseImponible=$xml->createElement('baseImponible',$detalleValores['total_sin_iva']);
						$baseImponible =$totalImpuesto->appendChild($baseImponible);
						$valor=$xml->createElement('valor','0.00');
						$valor =$totalImpuesto->appendChild($valor);

					}

					if($detalleValores['total_con_iva'] != 0){

						$totalImpuesto=$xml->createElement('totalImpuesto');
						$totalImpuesto =$totalConImpuestos->appendChild($totalImpuesto);
						$codigo=$xml->createElement('codigo','2');
						$codigo =$totalImpuesto->appendChild($codigo);
						//$codigoPorcentaje=$xml->createElement('codigoPorcentaje','2');
						//$codigoPorcentaje=$xml->createElement('codigoPorcentaje','3');
						$codigoPorcentaje=$xml->createElement('codigoPorcentaje',$codigoIvaSRI);
						$codigoPorcentaje =$totalImpuesto->appendChild($codigoPorcentaje);
						$baseImponible=$xml->createElement('baseImponible',$detalleValores['total_con_iva']);
						$baseImponible =$totalImpuesto->appendChild($baseImponible);
						$valor=$xml->createElement('valor',$detalleValores['suma_iva']);
						$valor =$totalImpuesto->appendChild($valor);

					}

					$totalFactura = $detalleValores['total_sin_iva']+$detalleValores['total_con_iva']+$detalleValores['suma_iva'];
					$propina=$xml->createElement('propina','0.00');
					$propina =$infoFactura->appendChild($propina);
					$importeTotal=$xml->createElement('importeTotal',$totalFactura);
					$importeTotal =$infoFactura->appendChild($importeTotal);
					$moneda=$xml->createElement('moneda','DOLAR');
					$moneda =$infoFactura->appendChild($moneda);

					$pagos = $xml->createElement('pagos');
					$pagos = $infoFactura->appendChild($pagos);

					//TODO:Verificar la forma de pago que se desea enviar

					$pago = $xml->createElement('pago');
					$pago = $pagos->appendChild($pago);
						
					$formaPagoSri = $xml->createElement('formaPago','20');//actualizado de 01 -> Sin utilización de sistema Financiero a 20 -> otros con utilización del sistema financiero.
					$formaPagoSri = $pago->appendChild($formaPagoSri);
						
					$totalSri = $xml->createElement('total',$totalFactura);
					$totalSri = $pago->appendChild($totalSri);
						
					$plazoSri = $xml->createElement('plazo','0');
					$plazoSri = $pago->appendChild($plazoSri);
						
					$diasSri = $xml->createElement('unidadTiempo','dias');
					$diasSri = $pago->appendChild($diasSri);

					$detalles=$xml->createElement('detalles');
					$detalles =$root->appendChild($detalles);

					for ($i = 0; $i < count($detalleFactura); $i++) {
						$detalle=$xml->createElement('detalle');
						$detalle =$detalles->appendChild($detalle);

						$codigoPrincipal=$xml->createElement('codigoPrincipal',$detalleFactura[$i]['idServicio']);
						$codigoPrincipal =$detalle->appendChild($codigoPrincipal);

						$descripcion=$xml->createElement('descripcion',$detalleFactura[$i]['concepto']);
						$descripcion =$detalle->appendChild($descripcion);

						$cantidad=$xml->createElement('cantidad',$detalleFactura[$i]['cantidad']);
						$cantidad =$detalle->appendChild($cantidad);

						$precioUnitario=$xml->createElement('precioUnitario',$detalleFactura[$i]['precioUnitario']);
						$precioUnitario =$detalle->appendChild($precioUnitario);
							
						/*-------------------------------------------------------------------------------------------------------------*/
						if($detalleFactura[$i]['subsidio']!= 0){
							$subsidioUnitario = $xml->createElement('precioSinSubsidio',round(($detalleFactura[$i]['subsidio']/$detalleFactura[$i]['cantidad'])+$detalleFactura[$i]['precioUnitario'],6));
							$subsidioUnitario = $detalle->appendChild($subsidioUnitario);
						}
						/*-------------------------------------------------------------------------------------------------------------*/

						$descuento=$xml->createElement('descuento',$detalleFactura[$i]['descuento']);
						$descuento =$detalle->appendChild($descuento);

						$precioTotalSinImpuesto=$xml->createElement('precioTotalSinImpuesto',round(($detalleFactura[$i]['cantidad']*$detalleFactura[$i]['precioUnitario'])-$detalleFactura[$i]['descuento'],2));
						$precioTotalSinImpuesto =$detalle->appendChild($precioTotalSinImpuesto);

						$impuestos=$xml->createElement('impuestos');
						$impuestos =$detalle->appendChild($impuestos);

						$impuesto=$xml->createElement('impuesto');
						$impuesto =$impuestos->appendChild($impuesto);

						$codigo=$xml->createElement('codigo','2');
						$codigo =$impuesto->appendChild($codigo);

						//$codigoPorcentaje=$xml->createElement('codigoPorcentaje',(($detalleFactura[$i]['iva'] == '0')?'0':'2'));
						//$codigoPorcentaje=$xml->createElement('codigoPorcentaje',(($detalleFactura[$i]['iva'] == '0')?'0':'3'));
						$codigoPorcentaje=$xml->createElement('codigoPorcentaje',(($detalleFactura[$i]['iva'] == '0')?'0':$codigoIvaSRI));
						$codigoPorcentaje =$impuesto->appendChild($codigoPorcentaje);

						//$tarifa=$xml->createElement('tarifa',(($detalleFactura[$i]['iva'] == '0')?'0.00':'12.00'));
						//$tarifa=$xml->createElement('tarifa',(($detalleFactura[$i]['iva'] == '0')?'0.00':'14.00'));
						$tarifa=$xml->createElement('tarifa',(($detalleFactura[$i]['iva'] == '0')?'0.00':$cantidadIvaSRI));
						$tarifa =$impuesto->appendChild($tarifa);

						$baseImponible=$xml->createElement('baseImponible',round(($detalleFactura[$i]['cantidad']*$detalleFactura[$i]['precioUnitario'])-$detalleFactura[$i]['descuento'],2));
						$baseImponible =$impuesto->appendChild($baseImponible);

						$valor=$xml->createElement('valor',(($detalleFactura[$i]['iva'] == '0')?'0.00':$detalleFactura[$i]['iva']));
						$valor =$impuesto->appendChild($valor);

					}
						
					$infoAdicional=$xml->createElement('infoAdicional');
					$infoAdicional =$root->appendChild($infoAdicional);
						
					$campoAdicional =$xml->createElement('campoAdicional',$datosComprador['telefono']);
					$campoAdicional =$infoAdicional->appendChild($campoAdicional);
					$atributo = $xml->createAttribute('nombre');
					$campoAdicional->appendChild($atributo);
					$atributo_valor = $xml->createTextNode('Teléfono');
					$atributo->appendChild($atributo_valor);

					$campoAdicional =$xml->createElement('campoAdicional',$datosComprador['correo']);
					$campoAdicional =$infoAdicional->appendChild($campoAdicional);
					$atributo = $xml->createAttribute('nombre');
					$campoAdicional->appendChild($atributo);
					$atributo_valor = $xml->createTextNode('Email');
					$atributo->appendChild($atributo_valor);

					switch ($cabeceraOrdenPago['tipo_solicitud']){

						case 'Importación':

							$ci = new ControladorImportaciones();

							$importacion = pg_fetch_assoc($ci->obtenerCabeceraImportacion($conexion, $cabeceraOrdenPago['id_solicitud']));

							$solicitudAtendida = $importacion['id_vue'];

							$campoAdicional =$xml->createElement('campoAdicional',$solicitudAtendida);
							$campoAdicional =$infoAdicional->appendChild($campoAdicional);
							$atributo = $xml->createAttribute('nombre');
							$campoAdicional->appendChild($atributo);
							$atributo_valor = $xml->createTextNode('numeroSolicitud');
							$atributo->appendChild($atributo_valor);

							$datosDeposito = 'Pago electrónico VUE.';

							break;
								
						case 'FitosanitarioExportacion':
							$cfe = new ControladorFitosanitarioExportacion();

							$fitosanotarioExportacion = pg_fetch_assoc($cfe->obtenerCabeceraFitosanitarioExportacion($conexion, $cabeceraOrdenPago['id_solicitud']));

							$solicitudAtendida = $fitosanotarioExportacion['id_vue'];

							$campoAdicional =$xml->createElement('campoAdicional',$solicitudAtendida);
							$campoAdicional =$infoAdicional->appendChild($campoAdicional);
							$atributo = $xml->createAttribute('nombre');
							$campoAdicional->appendChild($atributo);
							$atributo_valor = $xml->createTextNode('numeroSolicitud');
							$atributo->appendChild($atributo_valor);

							$datosDeposito = 'Pago electrónico VUE.';

							break;
								
						case 'Fitosanitario':
							$cfi = new ControladorFitosanitario();
								
							$fitosanotario = pg_fetch_assoc($cfi->listarFitoExportacion($conexion, $cabeceraOrdenPago['id_solicitud']));
								
							$solicitudAtendida = $fitosanotario['id_vue'];
								
							$campoAdicional =$xml->createElement('campoAdicional',$solicitudAtendida);
							$campoAdicional =$infoAdicional->appendChild($campoAdicional);
							$atributo = $xml->createAttribute('nombre');
							$campoAdicional->appendChild($atributo);
							$atributo_valor = $xml->createTextNode('numeroSolicitud');
							$atributo->appendChild($atributo_valor);

							$datosDeposito = 'Pago electrónico VUE.';
								
							break;
							
						case 'Laboratorios':
						    
						    $identificadorLaboratorio = pg_fetch_assoc($cfa->obtenerCodigoLaboratorioPorIdentificador($conexion, $listaFactura['id_solicitud_tabla']));
						    $solicitudAtendida = $identificadorLaboratorio['codigo'];
						    
						    $campoAdicional =$xml->createElement('campoAdicional',$solicitudAtendida);
						    $campoAdicional =$infoAdicional->appendChild($campoAdicional);
						    $atributo = $xml->createAttribute('nombre');
						    $campoAdicional->appendChild($atributo);
						    $atributo_valor = $xml->createTextNode('numeroSolicitud');
						    $atributo->appendChild($atributo_valor);
						    
						    $numeroDesposito =  $cc->listaFormasPago($conexion, $listaFactura['id_orden_pago']);
						    
						    $datosDeposito = '';
						    
						    while ($papeletaBanco = pg_fetch_assoc($numeroDesposito)){
						        $datosDeposito .= 'Deposito: '.$papeletaBanco['transaccion'].', ';	
						    }
						    
						    $datosDeposito = rtrim($datosDeposito,', ');
						    $datosDeposito = (strlen($datosDeposito)>80?(substr($datosDeposito,0,76).'...'):$datosDeposito);
						    
						break;
								
						case 'saldoVue':
							$solicitudAtendida = $cabeceraOrdenPago['id_solicitud'];

							$campoAdicional =$xml->createElement('campoAdicional',$solicitudAtendida);
							$campoAdicional =$infoAdicional->appendChild($campoAdicional);
							$atributo = $xml->createAttribute('nombre');
							$campoAdicional->appendChild($atributo);
							$atributo_valor = $xml->createTextNode('numeroSolicitud');
							$atributo->appendChild($atributo_valor);

							$datosDeposito = 'Saldo Vue.';
						break;
							
						case 'certificadoFito':
						    $solicitudAtendida = $cabeceraOrdenPago['id_solicitud'];
						    
						    $campoAdicional =$xml->createElement('campoAdicional',$solicitudAtendida);
						    $campoAdicional =$infoAdicional->appendChild($campoAdicional);
						    $atributo = $xml->createAttribute('nombre');
						    $campoAdicional->appendChild($atributo);
						    $atributo_valor = $xml->createTextNode('numeroSolicitud');
						    $atributo->appendChild($atributo_valor);
						    
						    $datosDeposito = 'Saldo';
						    
						break;
								
					}

					$observacion = (strlen($cabeceraOrdenPago['observacion'])>100?(substr($cabeceraOrdenPago['observacion'],0,96).'...'):($cabeceraOrdenPago['observacion']!=''?$cabeceraOrdenPago['observacion']:'Sin observación.'));

					$campoAdicional =$xml->createElement('campoAdicional',$observacion);
					$campoAdicional =$infoAdicional->appendChild($campoAdicional);
					$atributo = $xml->createAttribute('nombre');
					$campoAdicional->appendChild($atributo);
					$atributo_valor = $xml->createTextNode('observacion');
					$atributo->appendChild($atributo_valor);

					$campoAdicional =$xml->createElement('campoAdicional',$datosDeposito);
					$campoAdicional =$infoAdicional->appendChild($campoAdicional);
					$atributo = $xml->createAttribute('nombre');
					$campoAdicional->appendChild($atributo);
					$atributo_valor = $xml->createTextNode('datosDeposito');
					$atributo->appendChild($atributo_valor);

					//Generando archivo pdf
					$fechap = time ();
					$fecha_partir1=date ( "h" , $fechap ) ;
					$fecha_partir2=date ( "i" , $fechap ) ;
					$fecha_partir4=date ( "s" , $fechap ) ;
					$fecha_partir3=$fecha_partir1-1;
					$reporte="ComprobanteFactura_";
					$filename = $reporte.$cabeceraOrdenPago['id_pago']."_".date("Y-m-d")."_". $fecha_partir3.'_'.$fecha_partir2.'_'.$fecha_partir4.'.pdf';

					if (!file_exists('../../financiero/documentos/facturas/'.$rutaFecha.'/')){
					    mkdir('../../financiero/documentos/facturas/'.$rutaFecha.'/', 0777,true);
					}

					//Rutas Reporte Factura
					$ReporteJasper='/aplicaciones/financiero/reportes/comprobanteFactura.jrxml';
					$salidaReporte='/aplicaciones/financiero/documentos/facturas/'.$rutaFecha.'/'.$filename;
					$rutaArchivo='aplicaciones/financiero/documentos/facturas/'.$rutaFecha.'/'.$filename;

					$ivaSRI = $iva.'%';
					
					$parameters['parametrosReporte'] = array(
						'idpago'=> (int)$cabeceraOrdenPago['id_pago'],
						'datosDeposito'=> $datosDeposito,
						'solicitudAtendida'=> $solicitudAtendida,
						'observacion' => $observacion,
						'compensacion'=> $compensacion,
						'ivaSri'=> $ivaSRI
					);

					$jru->generarReporteJasper($ReporteJasper,$parameters,$conexion,$salidaReporte,'facturacion');

					echo IN_MSG .' Genración de PDF factura.';

					$xml->formatOutput = true;  //poner los string en la variable $strings_xml:
					$strings_xml = $xml->saveXML();
					
					if (!file_exists('../../financiero/archivoXml/generados/'.$rutaFecha.'/')){
					    mkdir('../../financiero/archivoXml/generados/'.$rutaFecha.'/', 0777,true);
					}

					$pathSalidaXml = $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/aplicaciones/financiero/archivoXml/generados/'.$rutaFecha.'/';
					$nombreArchivoXML = $codigoXml.$digitoVerificador.'.xml';

					$xml->save($pathSalidaXml.$nombreArchivoXML);

					//-------------------------------------FIRMA ARCHIVO XML----------------------------------------------------------------------------------------------------

					if($datosInstitucion['fecha_caducidad_pfx'] >= $fechaActualSistema){

							echo IN_MSG .' Firma XML.';

							$ccat = new ControladorCatalogos();
							$care = new ControladorAreas();

							//$datosEntidadBancaria = pg_fetch_assoc($ccat->obtenerDatosBancarioPorNombre($conexion, 'Saldo SENAE'));
							$areaIdentificadorFirmante = pg_fetch_assoc($care->areaUsuario($conexion, $cabeceraOrdenPago['identificador_usuario']));
							
							$scr = crc32($cabeceraOrdenPago['identificador_usuario']);
							$key = hash('sha256', $scr);

							$claveCertificado= $cf->desencriptarClaveUsuario($datosInstitucion['clave_pfx'], $key);

							$resultadoFirma	= $cc->firmarXML($constg::RUTA_SERVIDOR_OPT, $constg::RUTA_APLICACION, $rutaFecha.'/'.$nombreArchivoXML, $datosInstitucion['ruta_firma'], $claveCertificado);

							if($resultadoFirma == 'Firmado'){
								
								if (!file_exists('../../financiero/archivoXml/firmados/'.$rutaFecha.'/')){
									mkdir('../../financiero/archivoXml/firmados/'.$rutaFecha.'/', 0777,true);
								}
								
								$rutaArchivoAutorizado = $constg::RUTA_SERVIDOR_OPT."/".$constg::RUTA_APLICACION."/aplicaciones/financiero/archivoXml/firmados/".$rutaFecha."/".$nombreArchivoXML;
								$cc ->actualizarXmlComprobanteFactura($conexion,$cabeceraOrdenPago['id_pago'],$rutaArchivoAutorizado,$codigoTipoEmision);

								//$cc -> guardarPagoOrden($conexion, $cabeceraOrdenPago['id_pago'], 'now()',$datosEntidadBancaria['id_banco'],$datosEntidadBancaria['nombre'],'Saldo SENAE',$cabeceraOrdenPago['total_pagar'],0,$datosEntidadBancaria['id_cuenta_bancaria'],$datosEntidadBancaria['numero_cuenta']);
								//$cc -> guardarPagoOrden($conexion, $cabeceraOrdenPago['id_pago'], 'now()',0,'Saldo SENAE','Saldo SENAE',$cabeceraOrdenPago['total_pagar'],0,0,0);

								$cc ->actualizarComprobanteFactura($conexion,$cabeceraOrdenPago['id_pago'],'RECEPTOR',$rutaArchivo, $codigoXml.$digitoVerificador, $cabeceraOrdenPago['identificador_usuario']);

								if($cabeceraOrdenPago['tipo_solicitud'] != 'Otros'){
									$cf->guardarUsoDetalleFactura($conexion,$cabeceraOrdenPago['id_pago'], $cabeceraOrdenPago['identificador_usuario'], $datosInstitucion['provincia'], $areaIdentificadorFirmante['id_area'], 'Facturas consumida por un servicio automatizado de '.$cabeceraOrdenPago['tipo_solicitud'].'.');
									$cf->actualizarEstadoUsoFactura($conexion, $cabeceraOrdenPago['id_pago']);
								}

								if($cabeceraOrdenPago['tipo_solicitud'] == 'saldoVue'){
										
									$saldoDisponible = pg_fetch_assoc($cf->obtenerMaxSaldo($conexion,$cabeceraOrdenPago['identificador_operador'], 'saldoVue'));
									$saldoActual =  $saldoDisponible['saldo_disponible'] + $cabeceraOrdenPago['total_pagar'];
									$cf->guardarNuevoSaldoOperadorIngreso($conexion, $cabeceraOrdenPago['id_pago'], $cabeceraOrdenPago['total_pagar'], $saldoActual, $cabeceraOrdenPago['identificador_operador'], 'saldoVue');
								}
								
								if($cabeceraOrdenPago['tipo_solicitud'] == 'certificadoFito'){
								  
									$ccf = new ControladorCertificadoFito();
				   
									$ccf->actualizarEstadoCertificado($conexion, 'Aprobado', $cabeceraOrdenPago['id_solicitud'], $cabeceraOrdenPago['identificador_usuario']);
									$ccf->actualizarEstadoExportadoresProductos($conexion, 'Aprobado', $cabeceraOrdenPago['id_solicitud']);
									$ccf->actualizarFechaAprobacionCertificado($conexion, 'now()', $idCertificadoFitosanitario);
																													  
								}

								//TODO:Guardar datos a esquemas de revision formulario.

								//$qFinanciero = $crs->buscarIdImposicionTasa($conexion, $cabeceraOrdenPago['id_grupo_solicitud'], $cabeceraOrdenPago['tipo_solicitud'], 'Financiero');
								//$crs->guardarInspeccionFinanciero($conexion, pg_fetch_result($qFinanciero, 0, 'id_financiero'), $cabeceraOrdenPago['identificador_usuario'], 'aprobado', 'Aprobación automática por sistema GUIA.', $datosEntidadBancaria['id_cuenta_bancaria'], $cabeceraOrdenPago['total_pagar'], $datosEntidadBancaria['nombre'],$numeroSolicitud);

								$cfa->actualizarEstadoFacturaPorIdentificadorFinancieroAutomatico($conexion, $listaFactura['id_financiero_cabecera'], 'Atendida');
								//$cfe->actualizarFechasAprobacionFitosanitarioExportacion($conexion, $cabeceraOrdenPago['id_solicitud']);
								//$cfe->actualizarEstadoFitosanitarioExportacion($conexion, $cabeceraOrdenPago['id_solicitud'], 'aprobado', 'verificacionAutomatica', 'Aprobación en base a facturación automática.');

								//TODO:Quitar cuando se cambie la manera de envio del estado.
								//$cfa->ingresarSolicitudesXatenderGUIA($conexion, '101-034-REQ', '320', '21', $listaFactura['id_vue'], 'Por atender','Aprobación automática por sistema GUIA.');
								
								switch ($cabeceraOrdenPago['tipo_solicitud']){
									case 'Laboratorios':
										$numeroFactura = str_pad($datosInstitucion['numero_establecimiento'], 3, "0", STR_PAD_LEFT).'-'. str_pad($datosInstitucion['punto_emision'], 3, "0", STR_PAD_LEFT).'-'.$numeroSolicitud;
										$cfa->ActualizarNumeroFacturaLaboratorioPorIdentificador($conexion, $listaFactura['id_solicitud_tabla'], $numeroFactura);
									break;
								}

								echo IN_MSG .' Factura generada y firmada, envío al SRI.';
								echo IN_MSG .' Envío de solicitud aprobada a VUE.';

							}else{
								echo IN_MSG. 'Error al firmar el documento, clave incorrecta.-'.$numeroSolicitud;
								$cfa->actualizarObservacionFacturaAutomatico($conexion, $idFinancieroCabecera, 'Error al firmar el documento, clave incorrecta.-'.$numeroSolicitud);
							}
						}else{
							echo IN_MSG. 'Error al firmar el documento, pfx caducado.-'.$numeroSolicitud;
							$cfa->actualizarObservacionFacturaAutomatico($conexion, $idFinancieroCabecera, 'Error al firmar el documento, pfx caducado.-'.$numeroSolicitud);
						}

					}else{
						$cfa->actualizarObservacionFacturaAutomatico($conexion, $idFinancieroCabecera, 'Error proceso de facturación duplicado');
					}

				}else if($listaFactura['tipo_proceso'] == 'comprobanteFactura'){

					echo IN_MSG .' Generación de comprobante de factura.';

					$idFinancieroCabecera = $listaFactura['id_financiero_cabecera'];
					$cfa->actualizarEstadoFacturaPorIdentificadorFinancieroAutomatico($conexion, $listaFactura['id_financiero_cabecera'], 'W');

					//$cabeceraOrdenPago = pg_fetch_assoc($cc->abrirOrdenPago($conexion, $listaFactura['id_orden_pago']));

					if($cabeceraOrdenPago['estado_sri'] == ''){

						$cc->actualizarTipoProcesoOrdenPago($conexion, $cabeceraOrdenPago['id_pago'], 'comprobanteFactura');

						$institucion = $cc -> listarDatosInstitucion($conexion,$cabeceraOrdenPago['identificador_usuario']);
						$datosInstitucion = pg_fetch_assoc($institucion);

						$iva = $datosInstitucion['iva'];

						$auxliarNumeroFactura = pg_fetch_assoc($cc->obtenerNumeroFactura($conexion, $cabeceraOrdenPago['id_pago']));

						if($auxliarNumeroFactura['numero_factura'] == ''){
							$numero = pg_fetch_assoc($cc -> generarNumeroComprobanteFactura($conexion, $datosInstitucion['ruc'], $datosInstitucion['numero_establecimiento'], $datosInstitucion['punto_emision']));
							$nFactura = ($numero['numero'] == ''? 1 :$numero['numero']);
						}else{
							$nFactura = $auxliarNumeroFactura['numero_factura'];
						}

						$numeroSolicitud = str_pad($nFactura, 9, "0", STR_PAD_LEFT);

						$cc ->finalizarOrdenPago($conexion, $cabeceraOrdenPago['id_pago'], $cabeceraOrdenPago['total_pagar'], $datosInstitucion['ruc'], $numeroSolicitud, $datosInstitucion['numero_establecimiento'], $datosInstitucion['punto_emision']);

						echo IN_MSG .' Finalización de número de comprobante de factura.';

						switch ($cabeceraOrdenPago['tipo_solicitud']){

							case 'Importación':
									
								$ci = new ControladorImportaciones();
								$importacion = pg_fetch_assoc($ci->obtenerCabeceraImportacion($conexion, $cabeceraOrdenPago['id_solicitud']));
								$solicitudAtendida = $importacion['id_vue'];
									
								break;

							case 'FitosanitarioExportacion':
								$cfe = new ControladorFitosanitarioExportacion();
								$fitosanotarioExportacion = pg_fetch_assoc($cfe->obtenerCabeceraFitosanitarioExportacion($conexion, $cabeceraOrdenPago['id_solicitud']));
								$solicitudAtendida = $fitosanotarioExportacion['id_vue'];
									
								break;

							case 'Fitosanitario':
								$cfi = new ControladorFitosanitario();
								$fitosanotario = pg_fetch_assoc($cfi->obtenerCabeceraFitosanitario($conexion, $cabeceraOrdenPago['id_solicitud']));
								$solicitudAtendida = $fitosanotario['id_vue'];
									
								break;

						}
							
						$observacion = (strlen($cabeceraOrdenPago['observacion'])>100?(substr($cabeceraOrdenPago['observacion'],0,96).'...'):($cabeceraOrdenPago['observacion']!=''?$cabeceraOrdenPago['observacion']:'Sin observación.'));

						$saldoActual = pg_fetch_assoc($cf->obtenerMaxSaldo($conexion,$cabeceraOrdenPago['identificador_operador'], 'saldoVue'));

						$saldoDisponible = $saldoActual['saldo_disponible'] - $cabeceraOrdenPago['total_pagar'];

						//Generando archivo pdf
						$fechap = time ();
						$fecha_partir1=date ( "h" , $fechap ) ;
						$fecha_partir2=date ( "i" , $fechap ) ;
						$fecha_partir4=date ( "s" , $fechap ) ;
						$fecha_partir3=$fecha_partir1-1;
						$reporte="comprobanteFacturaVue_";
						$filename = $reporte.$cabeceraOrdenPago['id_pago']."_".date("Y-m-d")."_". $fecha_partir3.'_'.$fecha_partir2.'_'.$fecha_partir4.'.pdf';
						
						if (!file_exists('../../financiero/documentos/comprobanteFacturaVue/'.$rutaFecha.'/')){
							mkdir('../../financiero/documentos/comprobanteFacturaVue/'.$rutaFecha.'/', 0777,true);
						}

						//Rutas Reporte Factura
						$ReporteJasper='/aplicaciones/financiero/reportes/comprobanteFacturaVue.jrxml';
						$salidaReporte='/aplicaciones/financiero/documentos/comprobanteFacturaVue/'.$rutaFecha.'/'.$filename;
						$rutaArchivo='aplicaciones/financiero/documentos/comprobanteFacturaVue/'.$rutaFecha.'/'.$filename;
						
						$parameters['parametrosReporte'] = array(
							'idpago' => (int)$cabeceraOrdenPago['id_pago'],
							'datosDeposito' => 'Saldo disponible',
							'solicitudAtendida' => $solicitudAtendida,
							'observacion'=> $observacion,
							'ivaSri'=> $iva,
							'saldoDisponible'=> $saldoDisponible
						);
							
						$jru->generarReporteJasper($ReporteJasper,$parameters,$conexion,$salidaReporte,'facturacion');
							
						echo IN_MSG .' Genración de PDF de comprobante de factura de vue.';

						$cc -> guardarPagoOrden($conexion, $cabeceraOrdenPago['id_pago'], 'now()',0,'saldoVue','Saldo VUE',$cabeceraOrdenPago['total_pagar']);

						$cc->actualizarComprobanteFacturaVue($conexion, $cabeceraOrdenPago['id_pago'], $rutaArchivo, $cabeceraOrdenPago['identificador_usuario']);

						$cf->guardarNuevoSaldoOperadorEgreso($conexion, $cabeceraOrdenPago['id_pago'], $cabeceraOrdenPago['total_pagar'], $saldoDisponible, $cabeceraOrdenPago['identificador_operador'], 'saldoVue');

						$care = new ControladorAreas();

						$areaIdentificadorFirmante = pg_fetch_assoc($care->areaUsuario($conexion, $cabeceraOrdenPago['identificador_usuario']));

						if($cabeceraOrdenPago['tipo_solicitud'] != 'Otros'){
							$cf->guardarUsoDetalleFactura($conexion,$cabeceraOrdenPago['id_pago'], $cabeceraOrdenPago['identificador_usuario'], $datosInstitucion['provincia'], $areaIdentificadorFirmante['id_area'], 'Facturas consumida por un servicio automatizado de '.$cabeceraOrdenPago['tipo_solicitud'].'.');
							$cf->actualizarEstadoUsoFactura($conexion, $cabeceraOrdenPago['id_pago']);
						}

						//TODO: Enviar el archivo al mail del destinatario.

						$qFinanciero = $crs->buscarIdImposicionTasa($conexion, $cabeceraOrdenPago['id_grupo_solicitud'], $cabeceraOrdenPago['tipo_solicitud'], 'Financiero');
						$crs->guardarInspeccionFinanciero($conexion, pg_fetch_result($qFinanciero, 0, 'id_financiero'), $cabeceraOrdenPago['identificador_usuario'], 'aprobado', 'Finalización automática por sistema GUIA.', 0, $cabeceraOrdenPago['total_pagar'], 'Saldo Vue',$numeroSolicitud);
							
						$cfa->actualizarEstadoFacturaPorIdentificadorFinancieroAutomatico($conexion, $listaFactura['id_financiero_cabecera'], 'Atendida');
							
						echo IN_MSG .' Factura generada y firmada, envío al SRI.';
						echo IN_MSG .' Envío de solicitud aprobada a VUE.';

					}else{
						$cfa->actualizarObservacionFacturaAutomatico($conexion, $idFinancieroCabecera, 'Error proceso de comprobante de facturación duplicado');
					}

				}
				
			}

			echo '<br/><strong>FIN</strong></p>';

		}
		
		echo IN_MSG . '<p><strong> FIN REGISTRO SOLICITUDES '.$fecha.'</strong></p>';
	}

}else{

	$minutoS1=microtime(true);
	$minutoS2=microtime(true);
	$tiempo=$minutoS2-minutoS1;
	$xcadenota = "FECHA ".date("d/m/Y")." ".date("H:i:s");
	$xcadenota.= "; IP REMOTA ".$_SERVER['REMOTE_ADDR'];
	$xcadenota.= "; SERVIDOR HTTP ".$_SERVER['HTTP_REFERER'];
	$xcadenota.= "; SEGUNDOS ".$tiempo."\n";
	$arch = fopen("../../../aplicaciones/logs/cron/automatico_financiero_factura".date("d-m-Y").".txt", "a+");
	fwrite($arch, $xcadenota);
	fclose($arch);

}
?>