<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorWebServicesEphyto.php';
require_once '../../clases/ControladorFitosanitarioExportacion.php';
require_once '../../clases/ControladorReportes.php';
require_once '../../clases/ControladorCatalogos.php';
//require_once("http://localhost:8081/JavaBridge/java/Java.inc");

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try {

	$conexion = new Conexion();
	$cws = new ControladorWebServicesEphyto();
	$cfe = new ControladorFitosanitarioExportacion();
	$jru = new ControladorReportes();
	$cc = new ControladorCatalogos();
	
	try {

	    $textoValidacion = '';
	    $validacion = false;
	    
		$cliente = $cws->conexionWebServiceHub();

		$resultadoCertificado = $cliente->GetImportEnvelopeHeaders();
		$arrayResultadoCertificado = $cfe->obj2array($resultadoCertificado);

		if(count($resultadoCertificado->GetImportEnvelopeHeadersResult->EnvelopeHeader)!=0){
			if(count($resultadoCertificado->GetImportEnvelopeHeadersResult->EnvelopeHeader) == 1) {
				$numeroCertificado = $arrayResultadoCertificado['GetImportEnvelopeHeadersResult']['EnvelopeHeader']['hubDeliveryNumber'];
			}else{
				$numeroCertificado = $arrayResultadoCertificado['GetImportEnvelopeHeadersResult']['EnvelopeHeader']['0']['hubDeliveryNumber'];
			}

			$datosCertificado=$cliente->PULLSingleImportEnvelope(array('hubTrackingNumber' => $numeroCertificado));
			$resultadoCertificadoSinFirma = $datosCertificado->PULLSingleImportEnvelopeResult->Content;
			
			$rutaArchivo = 'recibidosEphyto/'.$numeroCertificado.'.xml';
			$file=fopen($rutaArchivo,"w");
			fwrite($file,$resultadoCertificadoSinFirma);
			fclose($file);

			$arrayIndividual = $cfe->xml2array2($resultadoCertificadoSinFirma);
			
			$nombreCertificado='Ninguno';
			$nombrePuertoEntrada='Ninguno';
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
			
			$nombreAgencia =  $arrayIndividual['rsm:SPSCertificate']['rsm:SPSExchangedDocument']['ram:IssuerSPSParty']['ram:Name']['value'];
			$nombreCertificado = $arrayIndividual['rsm:SPSCertificate']['rsm:SPSExchangedDocument']['ram:Name']['value'];
			
			if($nombreCertificado == 'NULL') {
			    $nombreCertificado='PHYTOSANITARY CERTIFICATE';
			}
			
			$numeroCertificadoPdf = $arrayIndividual['rsm:SPSCertificate']['rsm:SPSExchangedDocument']['ram:ID']['value'];

			$paisExportador = $arrayIndividual['rsm:SPSCertificate']['rsm:SPSConsignment']['ram:ExportSPSCountry']['ram:ID']['value'];
			$paisExportador=strtoupper(pg_fetch_result($cc->obtenerCodigoLocalizacion($conexion, $paisExportador), 0, 'nombre'));
			
			$paisImportador = $arrayIndividual['rsm:SPSCertificate']['rsm:SPSConsignment']['ram:ImportSPSCountry']['ram:ID']['value'];
			$paisImportador=strtoupper(pg_fetch_result($cc->obtenerCodigoLocalizacion($conexion, $paisImportador), 0, 'nombre'));
			
			$informacionAdicional = $arrayIndividual['rsm:SPSCertificate']['rsm:SPSExchangedDocument']['ram:IncludedSPSNote'];

			foreach ($informacionAdicional as $item){
			    if($item['ram:Subject']['value']=='ADAOEDL'){
			        $textoLeyenda = trim(preg_replace('/\s+/', ' ', $item['ram:Content']['value']));
			        break;
			    }
			}
			
			$nombreExportador=$arrayIndividual['rsm:SPSCertificate']['rsm:SPSConsignment']['ram:ConsignorSPSParty']['ram:Name']['value'];
			$direccionExportador=$arrayIndividual['rsm:SPSCertificate']['rsm:SPSConsignment']['ram:ConsignorSPSParty']['ram:SpecifiedSPSAddress']['ram:LineOne']['value'];
			
			$nombreImportador=$arrayIndividual['rsm:SPSCertificate']['rsm:SPSConsignment']['ram:ConsigneeSPSParty']['ram:Name']['value'];
			$direccionImportador=$arrayIndividual['rsm:SPSCertificate']['rsm:SPSConsignment']['ram:ConsigneeSPSParty']['ram:SpecifiedSPSAddress']['ram:LineOne']['value'];
			
			$nombreMedioTransporte=$cfe->obtenerNombreMedioTransporteHub($arrayIndividual['rsm:SPSCertificate']['rsm:SPSConsignment']['ram:MainCarriageSPSTransportMovement']['ram:ModeCode']['value']);
			
			$nombrePuertoEntrada=$arrayIndividual['rsm:SPSCertificate']['rsm:SPSConsignment']['ram:UnloadingBaseportSPSLocation']['ram:Name']['value'];
			
			$arrayProductos = $arrayIndividual['rsm:SPSCertificate']['rsm:SPSConsignment']['ram:IncludedSPSConsignmentItem'];
			
			$cantidadProductos = count($arrayProductos);
			
			foreach ($arrayProductos as $item){
			    
			    if($cantidadProductos == 1){
			        $nombreProducto = $item['ram:CommonName']['value'];
			        $nombreBotanico = $item['ram:ScientificName']['value'];
			        $peso = $item['ram:NetWeightMeasure']['value'];
			        $unidadPeso = $item['ram:NetWeightMeasure']['attr']['unitCode'];
			        $lugarOrigen = $item['ram:OriginSPSCountry']['ram:ID']['value'];
			        $datosProducto = $item['ram:AdditionalInformationSPSNote'];
			        $datosTratamiento = $item['ram:AppliedSPSProcess']['ram:ApplicableSPSProcessCharacteristic']['ram:Description']['1']['value'];
			    }else{
			        $nombreProducto = $item['ram:IncludedSPSTradeLineItem']['ram:CommonName']['value'];
			        $nombreBotanico = $item['ram:IncludedSPSTradeLineItem']['ram:ScientificName']['value'];
			        $peso = $item['ram:IncludedSPSTradeLineItem']['ram:NetWeightMeasure']['value'];
			        $unidadPeso = $item['ram:IncludedSPSTradeLineItem']['ram:NetWeightMeasure']['attr']['unitCode'];
			        $lugarOrigen = $item['ram:IncludedSPSTradeLineItem']['ram:OriginSPSCountry']['ram:ID']['value'];
			        $datosProducto = $item['ram:IncludedSPSTradeLineItem']['ram:AdditionalInformationSPSNote'];
			        $datosTratamiento = $item['ram:IncludedSPSTradeLineItem']['ram:AppliedSPSProcess']['ram:ApplicableSPSProcessCharacteristic']['ram:Description']['1']['value'];
			    }
			    
			    if(strlen($peso) == 0){
			        $textoValidacion .= ' No se dispone del peso del producto. ';
			        $validacion = true;
			    }
			    
			    foreach ($datosProducto as $datos){
			        if($datos['ram:Subject']['value'] == 'OQV'){
			            $cantidad = $datos['ram:Content']['value'];
			        }
			        
			        if($datos['ram:Subject']['value'] == 'OQU'){
			            $unidadCantidad = $datos['ram:Content']['value'];
			        }
			        
			        if($datos['ram:Subject']['value'] == 'DMTLIL' ||$datos['ram:Subject']['value'] == 'DMCL'){
			            $marcas = $datos['ram:Content']['value'];
			        }
			    }

			    $arrayListaProductos->add( '- '. $nombreProducto.' '.$cantidad.' '. $unidadCantidad);
			    $arrayListaPesoNeto->add('- Peso Neto '.  $peso .' '.$unidadPeso);
			    $arrayListaNombreBotanicoProducto->add('- '.  $nombreBotanico );
			    $arrayListaNombreLugarOrigen->add('- '.  $lugarOrigen );
			    $arrayMarcasDistintivas->add('- '.  $marcas );
			    $arrayDescripcionTratamiento->add('- '.  $datosTratamiento );
			}
			
			$tecnicoAprobador = $arrayIndividual['rsm:SPSCertificate']['rsm:SPSExchangedDocument']['ram:SignatorySPSAuthentication']['ram:ProviderSPSParty']['ram:SpecifiedSPSPerson']['ram:Name']['value'];
			//$fechaExpedicion = $arrayIndividual['rsm:SPSCertificate']['rsm:SPSExchangedDocument']['ram:SignatorySPSAuthentication']['ram:ActualDateTime']['udt:DateTimeString']['value'];
			$fechaExpedicion = $arrayIndividual['rsm:SPSCertificate']['rsm:SPSExchangedDocument']['ram:IssueDateTime']['udt:DateTimeString']['value'];
			$lugarEmision = $arrayIndividual['rsm:SPSCertificate']['rsm:SPSExchangedDocument']['ram:SignatorySPSAuthentication']['ram:IssueSPSLocation']['ram:Name']['value'];

			$ReporteJasperTicket='aplicaciones/fitosanitarioExportacion/reportes/reporteEphytoHolanda.jrxml';
			$parametersCertificado= new java('java.util.HashMap');

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

			if($nombreCertificado=='Ninguno' || $nombreCertificado=='NULL') {
				$nombreCertificado='PHYTOSANITARY CERTIFICATE';
			}

			$parametersCertificado->put('rutaLogo', $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/aplicaciones/general/img/Membrete.jpg');
			$parametersCertificado->put('nombreCertificado',$nombreCertificado);
			$parametersCertificado->put('numeroCertificado',$numeroCertificadoPdf);
			$parametersCertificado->put('paisExportador',$paisExportador);
			$parametersCertificado->put('paisImportador',$paisImportador);
			$parametersCertificado->put('textoLeyenda',$textoLeyenda);
			$parametersCertificado->put('nombreExportador',$nombreExportador);
			$parametersCertificado->put('direccionExportador',$direccionExportador);
			$parametersCertificado->put('nombreImportador',$nombreImportador);
			$parametersCertificado->put('direccionImportador',$direccionImportador);
			$parametersCertificado->put('nombreMedioTransporte',$nombreMedioTransporte);
			$parametersCertificado->put('nombrePuertoEntrada',$nombrePuertoEntrada);

			setlocale(LC_ALL, 'es_ES');
				
			if($fechaExpedicion != 'Ninguno') {
				$date = new DateTime($fechaExpedicion);
				$fechaExpedicionF=iconv('ISO-8859-1', 'UTF-8', strftime('%A %d de %B de %Y', $date->getTimestamp()));
			}else{
				$fechaExpedicionF='Ninguno';
			}

			$parametersCertificado->put('fechaExpedicion',$fechaExpedicionF);
			$parametersCertificado->put('lugarEmision',$lugarEmision);
			$parametersCertificado->put('tecnicoAprobador',$tecnicoAprobador);
				
			$rutaCertificado='aplicaciones/fitosanitarioExportacion/recibidosEphyto/pdf/'.$numeroCertificado.'.pdf';

			$conn = new Java("net.sf.jasperreports.engine.JREmptyDataSource");
				
			$jru->generarReporteJasper($ReporteJasperTicket,$parametersCertificado,$conn,$rutaCertificado,'ninguno');

			$validacionNumeroCertificado = $cfe->obtenerFitosanitarioExportacionRecibidosPorNumeroCertificado($conexion, $numeroCertificadoPdf);
			
			if(pg_num_rows($validacionNumeroCertificado)!=0){
			    $textoValidacion .= 'El número de cértificado ya se encuentra registrado en la solicitud con Hub Tracking Number '. pg_fetch_result($validacionNumeroCertificado, 0, 'codigo');
			    $validacion = true;
			}
			
			$cfe->guardarFitosanitarioExportacionRecibidos($conexion, $numeroCertificado, 'aplicaciones/fitosanitarioExportacion/'.$rutaArchivo,$rutaCertificado, 'RECIBIDO', 'HUB', $numeroCertificadoPdf);

			if($validacion){
			    $cliente->AdvancedAcknowledgeEnvelopeReceipt(array('hubTrackingNumber' => $numeroCertificado, 'warningMessage' => $textoValidacion));
			}else{
			    $cliente->AcknowledgeEnvelopeReceipt(array('hubTrackingNumber' => $numeroCertificado));
			}

			$cfe->confirmacionRecepcionFitosanitarioExportacion($conexion, $numeroCertificado, 'CONFIRMADO', 'HUB');

			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $resultadoCertificadoSinFirma;
		}else{
			$mensaje['mensaje'] = 'No existen documentos';
		}
		echo json_encode($mensaje);
	} catch (Exception $ex) {
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = "Error al ejecutar sentencia";
		echo json_encode($mensaje);
	}
} catch (Exception $ex) {
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}