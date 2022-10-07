<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorWebServicesEphyto.php';
require_once '../../clases/ControladorFitosanitarioExportacion.php';
require_once '../../clases/ControladorReportes.php';
//require_once("http://localhost:8081/JavaBridge/java/Java.inc");

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try {

	$conexion = new Conexion();
	$cws = new ControladorWebServicesEphyto();
	$cfe = new ControladorFitosanitarioExportacion();
	$jru = new ControladorReportes();


	$pagina = '';
	$fecha_desde = '';
	$fecha_hasta = '';
	$status = 'APPROVED';

	try {

		$cliente = $cws->conexionWebServicesHolanda();

		$resultadoCertificado = $cliente->find_certificates_by_update_date_and_status($pagina, $fecha_desde, $fecha_hasta, $status);

		if(count($resultadoCertificado)!=0){
			$numeroCertificado = $resultadoCertificado[0];

			$resultadoCertificadoSinFirma = $cliente->get_official_certificate_xml($numeroCertificado);
			
			$rutaArchivo = 'recibidosEphyto/'.$numeroCertificado.'.xml';
			$file=fopen($rutaArchivo,"w");
			fwrite($file,$resultadoCertificadoSinFirma);
			fclose($file);
		
			$arrayIndividual = $cfe->xml2array($resultadoCertificadoSinFirma);
				
			$nombrePuertoEntrada='Ninguno';
			$marcasDistintivas='Ninguno';
			$tecnicoAprobador='Ninguno';
			$fechaExpedicion='Ninguno';
			$textoLeyenda='Ninguno';
			$arrayListaProductos = new java( 'java.util.ArrayList' );
			$arrayListaNombreBotanicoProducto = new java( 'java.util.ArrayList' );
			$arrayListaPesoNeto = new java( 'java.util.ArrayList' );
			$arrayListaNombreLugarOrigen = new java( 'java.util.ArrayList' );
			$arrayDescripcionTratamiento = new java( 'java.util.ArrayList' );
			$arrayMarcasDistintivas = new java( 'java.util.ArrayList' );
			$arrayInformacionAdicional = new java( 'java.util.ArrayList' );
			$contadorDocumento=0;
			foreach($arrayIndividual as $subItem1 => $key1) {

				if(is_numeric($subItem1)) {

					foreach($arrayIndividual[$subItem1] as $subItem2 => $key2) {

						foreach($key2 as $subItem3 => $key3) {

							if($subItem3 == 'value') {

								//1 nombre certificado
								if( $subItem1 == 0 && $subItem2 == 0 ) {
									$nombreCertificado=strtoupper($key3);
								}

								//2 número certificado
								if( $subItem1 == 0 && $subItem2 == 1 ) {
									$numeroCertificadoPDF=$key3;
								}

							}


							if($subItem3 == 'tag' && $key3 == 'RAM:INCLUDEDSPSCONSIGNMENTITEM') {

								//10 numero y descripcion de bultos
								$descripcionBultos='Ninguno';

								//$nombreProducto='';
								$nombreProducto='Ninguno';

							}


							if($subItem3 == 'tag' && $key3 == 'RAM:REFERENCESPSREFERENCEDDOCUMENT') {
								$contadorDocumento++;
							}

							foreach($key3 as $subItem4 => $key4) {

								if($subItem4 == 'value') {

									//5 nombre importador
									if( $subItem1 == 1 && $subItem2 == 0 && $subItem3 == 1 ) {
										$nombreExportador=$key4;
									}

									//6 nombre importador
									if( $subItem1 == 1 && $subItem2 == 1 && $subItem3 == 1 ) {
										$nombreImportador=$key4;
									}

									//4 país exportador
									if( $subItem1 == 1 && $subItem2 == 3 && $subItem3 == 1 ) {
										$paisExportador=$key4;
									}

									//7 país importador
									if( $subItem1 == 1 && $subItem2 == 4 && $subItem3 == 1 ) {
										$paisImportador=$key4;
									}

									//8 medio transporte
									if( $subItem1 == 1 && $subItem2 == 7 && $subItem3 == 0) {
										$nombreMedioTransporte=$cfe->obtenerNombreMedioTransporteHub($key4);
									}

									//9  puerto de destino (punto entrada declarado)
									if( $subItem1 == 1 && $subItem2 == 5 && $subItem3 == 1) {
										$nombrePuertoEntrada=$key4;
									}

									//24 fecha expedicion
									if( $subItem1 == 0 && $subItem2 == (9+$contadorDocumento) && $subItem3 == 0) {
										$fechaExpedicion=$key4;
									}

								}

								foreach($key4 as $subItem5 => $key5) {

									if($subItem5 == 'value') {

										//5 dirección exportador
										if( $subItem1 == 1 && $subItem2 == 0 && $subItem3 == 2 && $subItem4 == 0) {
											$direccionExportador=$key5;
										}
										//6 dirección importador
										if( $subItem1 == 1 && $subItem2 == 1 && $subItem3 == 2 && $subItem4 == 0) {
											$direccionImportador=$key5;
										}

										// 13 nombre del producto $subItem2 == 9 es por cada porducto;

										if( $subItem1 == 1 && $subItem3 == 0 && $subItem4 == 2) {
											//$arrayListaProductos->add(') '. $key5 );
											$nombreProducto= $key5;
										}

										if( $subItem1 == 1 && $subItem3 == 0 && $subItem4 == 3) {
											$arrayListaNombreBotanicoProducto->add('- '.  $key5 );
										}

										// 12 nombre del producto $subItem2 == 9 es por cada porducto;
										if( $subItem1 == 1 && $subItem3 == 0 && $subItem4 == 5) {
											//$pesoNeto=$key5;
											$arrayListaPesoNeto->add('- Peso Neto '.  $key5 );
										}

										if( $subItem1 == 0 && $subItem3 == 3 && $subItem4 == 1) {
											$textoLeyenda=$key5;
										}

										//24 Fecha emision certificado $fechaEmision
										if( $subItem1 == 0 && $subItem2 == 10 && $subItem3 == 1 && $subItem4 == 1) {
											$fechaEmision=$key5;
										}

										//24 Lugar emision certificado $fechaEmision
										if( $subItem1 == 0 && $subItem2 == 11 && $subItem3 == 1 && $subItem4 == 1) {
											$lugarEmision=$key5;
										}
									}

									foreach($key5 as $subItem6 => $key6) {

										if($subItem6 == 'value') {
											//11 cantidad de bultos
											if($subItem1 == 1 && $subItem3 == 0 && $subItem4 == 17 && $subItem5 == 2) {

												$arrayListaProductos->add( '- '. $key6.' '.$nombreProducto );
											}

											if($subItem1 == 0 && $subItem2 == 10 && $subItem3 == 2 && $subItem4 == 2 && $subItem5 == 0) {
												$tecnicoAprobador=$key6;
											}

										}

										foreach($key6 as $subItem7 => $key7) {

											if($subItem7  ==  'value') {

												if($subItem1 == 1 && $subItem3 == 0 && $subItem4 == 18 && $subItem5 == 2 && $subItem6 == 1) {
													$arrayListaNombreLugarOrigen->add('- '.  $key7 );
												}
											}
										}
									}
								}
							}
						}
					}
				}
			}

			//echo $contadorDocumento;


			$ReporteJasperTicket='aplicaciones/fitosanitarioExportacion/reportes/reporteEphytoHolanda.jrxml';
			$parametersCertificado= new java('java.util.HashMap');
			$parametersCertificado->put('rutaLogo', $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/aplicaciones/general/img/Membrete.jpg');
			$parametersCertificado->put('nombreCertificado',$nombreCertificado);
			$parametersCertificado->put('numeroCertificado',$numeroCertificadoPDF);
			$parametersCertificado->put('paisExportador',$paisExportador);
			$parametersCertificado->put('paisImportador',$paisImportador);
			$parametersCertificado->put('textoLeyenda',$textoLeyenda);
			$parametersCertificado->put('nombreExportador',$nombreExportador);
			$parametersCertificado->put('direccionExportador',$direccionExportador);
			$parametersCertificado->put('nombreImportador',$nombreImportador);
			$parametersCertificado->put('direccionImportador',$direccionImportador);
			$parametersCertificado->put('nombreMedioTransporte',$nombreMedioTransporte);
			$parametersCertificado->put('nombrePuertoEntrada',$nombrePuertoEntrada);

			if($arrayListaProductos->size() == '0') {
				$arrayListaProductos->add('Ninguno');
			}

			$parametersCertificado->put('nombreProducto',$arrayListaProductos);
			if($arrayListaPesoNeto->size() == '0') {
				$arrayListaPesoNeto->add('Ninguno');
			}

			$parametersCertificado->put('pesoNeto',$arrayListaPesoNeto);
			if($arrayListaNombreBotanicoProducto->size() == '0') {
				$arrayListaNombreBotanicoProducto->add('Ninguno');
			}

			$parametersCertificado->put('nombreBotanicoProducto',$arrayListaNombreBotanicoProducto);
			if($arrayListaNombreLugarOrigen->size() == '0') {
				$arrayListaNombreLugarOrigen->add('Ninguno');
			}
			$parametersCertificado->put('nombreLugarOrigen',$arrayListaNombreLugarOrigen);

			if($arrayDescripcionTratamiento->size() == '0') {
				$arrayDescripcionTratamiento->add('Ninguno');
			}
			$parametersCertificado->put('tratamientoProducto',$arrayDescripcionTratamiento);

			if($arrayInformacionAdicional->size() == '0') {
				$arrayInformacionAdicional->add('Ninguno');
			}
			$parametersCertificado->put('informacionAdicional',$arrayInformacionAdicional);


			if($arrayMarcasDistintivas->size() == '0') {
				$arrayMarcasDistintivas->add('Ninguno');
			}
			$parametersCertificado->put('marcasDistintivas',$arrayMarcasDistintivas);

			setlocale(LC_ALL, 'es_ES');

			if($fechaExpedicion!='Ninguno') {
				$date = new DateTime($fechaExpedicion);
				$fechaExpedicionF=iconv('ISO-8859-1', 'UTF-8', strftime('%A %d de %B de %Y', $date->getTimestamp()));
			}else{
				$fechaExpedicionF='Ninguno';
			}

			$parametersCertificado->put('fechaExpedicion',$fechaExpedicionF);
			$parametersCertificado->put('lugarEmision',$lugarEmision);
			$parametersCertificado->put('tecnicoAprobador',$tecnicoAprobador);

			$rutaCertificado='aplicaciones/fitosanitarioExportacion/recibidosEphyto/pdf/'.$numeroCertificado.'.pdf';

			/**
			 * JREmptyDataSource
			 * Una implementación de fuente de datos simple que simula una fuente de datos con una cantidad dada de
			 * registros virtuales dentro. Se denomina fuente de datos vacía porque, aunque tiene uno o más registros
			 * en su interior, todos los campos del informe son nulos para todos los registros virtuales de la fuente de datos.
			 */

			$cfe->guardarFitosanitarioExportacionRecibidos($conexion, $numeroCertificado, 'aplicaciones/fitosanitarioExportacion/'.$rutaArchivo,$rutaCertificado);

			$cliente->acknowledge_certificate($numeroCertificado);

			$cfe->confirmacionRecepcionFitosanitarioExportacion($conexion, $numeroCertificado);

			$conn = new Java("net.sf.jasperreports.engine.JREmptyDataSource");

			$jru->generarReporteJasper($ReporteJasperTicket,$parametersCertificado,$conn,$rutaCertificado,'ninguno');

			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $resultadoCertificadoSinFirma;
		}else{
			$mensaje['mensaje'] = 'No existen documentos';
		}
		echo json_encode($mensaje);
	} catch (Exception $ex) {
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = "Error al ejecutar sentencia".$ex;
		echo json_encode($mensaje);
	}
} catch (Exception $ex) {
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}