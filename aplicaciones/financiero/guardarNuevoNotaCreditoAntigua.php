<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorReportes.php';
require_once '../../clases/ControladorCertificados.php';
require_once '../../clases/ControladorFinanciero.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{

	$tipoCliente = htmlspecialchars ($_POST['tipoBusquedaCliente'],ENT_NOQUOTES,'UTF-8');
	$varCliente = htmlspecialchars ($_POST['txtClienteBusqueda'],ENT_NOQUOTES,'UTF-8');
	$tipoDocumento = htmlspecialchars ($_POST['tipoBusquedaDocumento'],ENT_NOQUOTES,'UTF-8');
	$varDocumento = htmlspecialchars ($_POST['txtDocumentoBusqueda'],ENT_NOQUOTES,'UTF-8');
	$motivoNotaCredito = htmlspecialchars($_POST['motivo'],ENT_NOQUOTES,'UTF-8');
	$idCliente = htmlspecialchars ($_POST['idCliente'],ENT_NOQUOTES,'UTF-8');

	$razonSocial = $_POST['razonSocial'];
	$direccion = htmlspecialchars ($_POST['direccion'],ENT_NOQUOTES,'UTF-8');
	$telefono = htmlspecialchars ($_POST['telefono'],ENT_NOQUOTES,'UTF-8');
	$correo = htmlspecialchars ($_POST['correo'],ENT_NOQUOTES,'UTF-8');


	$idPago = ($_POST['idPago']);
	$idDeposito = ($_POST['idDeposito']);
	$nombreDeposito = ($_POST['nombreDeposito']);
	$cantidad = ($_POST['cantidad']);
	$descuento = ($_POST['descuentoUnidad']);
	$precioUnitario = ($_POST['precioUnitario']);
	$ivaIndividual = ($_POST['ivaIndividual']);
	$totalIndividual = ($_POST['totalIndividual']);
	$valorTotal = $_POST['valorTotal'];
	$provincia = $_SESSION['nombreProvincia'];
	$identificadorUsuario = $_SESSION['usuario'];
	$claveCertificado = $_POST['txtClaveCertificado'];
	$idNotaCredito = $_POST['idNotaCredito'];
	$codigoAmbiente = '1'; // 1-> Pruebas , 2-> Producción
	
	$errorValidacion = true;
	
	try{
			
		$conexion = new Conexion();
		$cc = new ControladorCertificados();
		$cf = new ControladorFinanciero();
		$jru = new ControladorReportes();
		
		$rutaFecha = date('Y').'/'.date('m').'/'.date('d');
			
		//Datos de la institucion
		$institucion = $cc -> listarDatosInstitucionAntiguo($conexion,$identificadorUsuario);
		$datosInstitucion = pg_fetch_assoc($institucion);
							
			//Datos cliente
			$listaCliente =  pg_fetch_assoc($cc->listaComprador($conexion,$idCliente));

			if ($idCliente != ''){

				if($razonSocial == '') $razonSocial=$listaCliente['razon_social'];
				if($direccion == '') $direccion=$listaCliente['direccion'];
				if($telefono == '') $telefono=$listaCliente['telefono'];
				if($correo == '') $correo=$listaCliente['correo'];
					
				$cc -> actualizarCliente($conexion,$idCliente,$tipoCliente,$razonSocial,$direccion,$telefono,$correo);

					
			}else {

				if($tipoCliente == '01'){

					$varCliente = $ruc;
						
					$tipoCliente = (strlen($varCliente) == '13' ? '04': '05' );
						
					$listaCliente =  $cc->listaComprador($conexion,$varCliente);

					if(pg_num_rows($listaCliente)==0){

						$cliente = $cc -> guardarNuevoCliente($conexion,$varCliente,$tipoCliente,$razonSocial,$direccion,$telefono,$correo);
						$idCliente = pg_fetch_result($cliente, 0, 'identificador');

					}else{
						if($razonSocial == '') $razonSocial=$listaCliente['razon_social'];
						if($direccion == '') $direccion=$listaCliente['direccion'];
						if($telefono == '') $telefono=$listaCliente['telefono'];
						if($correo == '') $correo=$listaCliente['correo'];

						$tipoCliente = (strlen($varCliente) == '13' ? '04': '05' );
							
						$cliente =   $cc -> actualizarCliente($conexion,$varCliente,$tipoCliente,$razonSocial,$direccion,$telefono,$correo);
						$idCliente = $ruc;
					}

				}else{
					$cliente = $cc -> guardarNuevoCliente($conexion,$varCliente,$tipoCliente,$razonSocial,$direccion,$telefono,$correo);
					$idCliente = pg_fetch_result($cliente, 0, 'identificador');
				}
					
			}
				
			$datosComprador = pg_fetch_assoc($cc -> listaComprador($conexion,$idCliente));
				
			if($idNotaCredito==""){

				//Valores nota credito
				$numero = pg_fetch_assoc($cc -> generarNumeroNotaCredito($conexion, $datosInstitucion['ruc'], $datosInstitucion['numero_establecimiento'], $datosInstitucion['punto_emision']));
				$nNotaCredito = ($numero['numero'] == ''? '1' :$numero['numero']);
				$numeroNotaCredito = str_pad($nNotaCredito, 9, "0", STR_PAD_LEFT);

				//Guardar datos de cabecera de nota de credito
				$notaCredito = $cc -> guardarNotaCredito($conexion,$idPago,$idCliente,$numeroNotaCredito,$valorTotal,$motivoNotaCredito,$_SESSION['nombreLocalizacion'], $datosInstitucion['ruc'],$identificadorUsuario, $datosInstitucion['numero_establecimiento'], $datosInstitucion['punto_emision'], $datosInstitucion['id_provincia'],$datosInstitucion['provincia']);
				//$fila =  pg_fetch_assoc($notaCredito);
				$idNotaCredito = pg_fetch_result($notaCredito, 0, 'id_nota_credito');
				
				

				//Detalle Nota de credito
				for ($i = 0; $i < count ($idDeposito); $i++) {
					if($descuento[$i]=='') $descuento = 0;
					$concepto = pg_fetch_assoc($cc->abrirServicios($conexion, $idDeposito[$i]));
					$cc -> guardarTotalNotaCredito($conexion, $idNotaCredito, $idDeposito[$i], $concepto['concepto'],$cantidad[$i],$descuento[$i],$precioUnitario[$i],$ivaIndividual[$i],$totalIndividual[$i]);
				}
			}
				
				
			//Datos de factura
			$datosFacturaModificada = pg_fetch_assoc($cc->abrirOrdenPago($conexion, $idPago));
			
			$cc->actualizarPorcentajeIvaNotaCredito($conexion, $idNotaCredito, $datosFacturaModificada['porcentaje_iva']);
			
			$iva = $datosFacturaModificada['porcentaje_iva'];
			
			$codigoIvaSRI = ($iva == 14 ? '3' : '2');
			$cantidadIvaSRI = ($iva == 14 ? '14.00' : '12.00');
				
			//Obtener datos nota de credito
			$valoresNotaCredito = $cc -> obtenerDatosNotaCredito($conexion,$idNotaCredito);
			$notaCreditoValores =  pg_fetch_assoc($valoresNotaCredito);
				
			$numeroNotaCredito = ($numeroNotaCredito==''?$notaCreditoValores['numero_nota_credito']:$numeroNotaCredito);
				

			$fechaNC = date('d').'/'.date('m').'/'.date('Y');
			$fechap = explode('/', $fechaNC);
			$fechaNotaCredito = $fechap[0] . $fechap[1] . $fechap[2];

			//Valores nota de credito
			$valoresDetalleNCredito = $cc->obtenerDatosDetalleNotaCredito($conexion,$notaCreditoValores['id_nota_credito']);
			$detalleNCValores =  pg_fetch_assoc($valoresDetalleNCredito);

			//Consultar detalle nota de credito
			$detalleNotaCredito	  = $cc->abrirDetalleNotaCredito ($conexion,$idNotaCredito);

			//Generando pdf nota de credito
			$fecha = time ();
			$fecha_partir1=date ( "h" , $fecha );
			$fecha_partir2=date ( "i" , $fecha );
			$fecha_partir4=date ( "s" , $fecha );
			$fecha_partir3=$fecha_partir1-1;
			$reporte="NotaCredito_";
			$filename = $reporte."_".$idCliente."_".date("Y-m-d")."_".$fecha_partir3.'_'.$fecha_partir2.'_'.$fecha_partir4.'.pdf';
			$nombreArchivo = $reporte."_".$idCliente."_".date("Y-m-d")."_".$fecha_partir3.'_'.$fecha_partir2.'_'.$fecha_partir4;

			if (!file_exists('documentos/notaCredito/'.$rutaFecha.'/')){
			    mkdir('documentos/notaCredito/'.$rutaFecha.'/', 0777,true);
			}

			//Rutas Nota Credito
			$ReporteJasper='/aplicaciones/financiero/reportes/comprobanteNotaCredito.jrxml';
			$salidaReporte = '/aplicaciones/financiero/documentos/notaCredito/'.$rutaFecha.'/'.$filename;
			$rutaArchivo = 'aplicaciones/financiero/documentos/notaCredito/'.$rutaFecha.'/'.$filename;
			
			$ivaSRI = $iva.'%';
			
			$parameters['parametrosReporte'] = array(
				'idnotaCredito' => (int)$idNotaCredito,
				'ivaSri' => $ivaSRI
			);

			$jru->generarReporteJasper($ReporteJasper,$parameters,$conexion,$salidaReporte,'facturacion');

			//Consulta si la oficina lleva contabilidad
			if($datosInstitucion['obligado_llevar_contabilidad'] == 't'){
				$contabilidad = 'SI';
			}else{
				$contabilidad = 'NO';
			}

			$fechaNC = date('d').'/'.date('m').'/'.date('Y');
			$fechap = explode('/', $fechaNC);
			$fechaEmisionFactura = $fechap[0].'/'. $fechap[1].'/'. $fechap[2];
				
				
			$fechaVigente = pg_fetch_assoc($cf->obtenerFechasContigenciaVigentes($conexion));
			$fechaActualSistema = date('Y-m-d H:i:s');
				
			$fechaContingenciaDesde = date('Y-m-d H:i:s', strtotime($fechaVigente['fecha_desde']));
			$fechaContingenciaHasta = date('Y-m-d H:i:s', strtotime($fechaVigente['fecha_hasta']));
				
				
			if($fechaActualSistema >= $fechaContingenciaDesde && $fechaActualSistema <= $fechaContingenciaHasta ){
				$codigoTipoEmision = '2'; // 2-> Emisión por indisponibilidad del sistema
					
				$verificarClaveContingencia = $cf->obtenerClaveContigenciaPorIdComprobante($conexion, $idNotaCredito, 'notaCredito');
					
				if(pg_num_rows($verificarClaveContingencia)==0){
						
					$nuevaClaveContingencia = pg_fetch_assoc($cf->obtenerClaveContingencia($conexion));
					$cf->actualizarEstadoClaveContingencia($conexion, $nuevaClaveContingencia['id_clave_contingencia'], $idNotaCredito, 'notaCredito');
						
					$claveContingencia = $nuevaClaveContingencia['clave'];
						
				}else{
					$claveContingencia = pg_fetch_result($verificarClaveContingencia, 0, 'clave');
				}
					
					
				$codigoXml = $fechaNotaCredito.'04'.$claveContingencia.$codigoTipoEmision;
				$digitoVerificador =  $cc->calcularDigito($codigoXml);
					
			}else{
				$codigoTipoEmision = '1'; // 1-> Emisión normal

				//Clave acceso
				$auxCodigoNumerico = $fechaNotaCredito.'04'.$datosInstitucion['ruc'].$codigoAmbiente.str_pad($datosInstitucion['numero_establecimiento'], 3, "0", STR_PAD_LEFT).str_pad($datosInstitucion['punto_emision'], 3, "0", STR_PAD_LEFT).$numeroNotaCredito.$notaCreditoValores['id_nota_credito'];
				$codigoXml = $fechaNotaCredito.'04'.$datosInstitucion['ruc'].$codigoAmbiente.str_pad($datosInstitucion['numero_establecimiento'], 3, "0", STR_PAD_LEFT).str_pad($datosInstitucion['punto_emision'], 3, "0", STR_PAD_LEFT).$numeroNotaCredito;
				$codigoNumerico = $cc->calcularDigito($auxCodigoNumerico);
				$codigoXml = $codigoXml.str_pad($codigoNumerico, 8, "0", STR_PAD_LEFT).$codigoTipoEmision;
				$digitoVerificador =  $cc->calcularDigito($codigoXml);
					
			}
				
			//Generar archivo xml
			$xml = new DomDocument('1.0', 'UTF-8');

			//Nodo principal
			$root = $xml->createElement('notaCredito');
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

			$claveAcceso=$xml->createElement('claveAcceso',$codigoXml.$digitoVerificador);
			$claveAcceso =$infoTributaria ->appendChild($claveAcceso);
			$codDoc=$xml->createElement('codDoc','04');
			$codDoc =$infoTributaria ->appendChild($codDoc);
			$estab=$xml->createElement('estab',str_pad($datosInstitucion['numero_establecimiento'], 3, "0", STR_PAD_LEFT));
			$estab =$infoTributaria ->appendChild($estab);
			$ptoEmi=$xml->createElement('ptoEmi',str_pad($datosInstitucion['punto_emision'], 3, "0", STR_PAD_LEFT));
			$ptoEmi =$infoTributaria ->appendChild($ptoEmi);
			$secuencial=$xml->createElement('secuencial',$numeroNotaCredito);
			$secuencial =$infoTributaria ->appendChild($secuencial);
			$dirMatriz=$xml->createElement('dirMatriz',$datosInstitucion['direccion']);
			$dirMatriz =$infoTributaria ->appendChild($dirMatriz);

			$infoNotaCredito=$xml->createElement('infoNotaCredito');
			$infoNotaCredito =$root->appendChild($infoNotaCredito);

			$fechaEmision=$xml->createElement('fechaEmision',$fechaNC);
			$fechaEmision =$infoNotaCredito->appendChild($fechaEmision);
			$tipoIdentificacionComprador=$xml->createElement('tipoIdentificacionComprador',$datosComprador['tipo_identificacion']);
			$tipoIdentificacionComprador =$infoNotaCredito ->appendChild($tipoIdentificacionComprador);
			$razonSocialComprador=$xml->createElement('razonSocialComprador', htmlspecialchars($datosComprador['razon_social']));
			$razonSocialComprador =$infoNotaCredito ->appendChild($razonSocialComprador);
			$identificacionComprador=$xml->createElement('identificacionComprador',$datosComprador['identificador']);
			$identificacionComprador =$infoNotaCredito ->appendChild($identificacionComprador);
			$obligadoContabilidad=$xml->createElement('obligadoContabilidad',$contabilidad);
			$obligadoContabilidad =$infoNotaCredito ->appendChild($obligadoContabilidad);
			$codDocModificado=$xml->createElement('codDocModificado','01');
			$codDocModificado =$infoNotaCredito ->appendChild($codDocModificado);
			$numFacturaModificado = str_pad($datosFacturaModificada['numero_establecimiento'], 3, "0", STR_PAD_LEFT).'-'.str_pad($datosFacturaModificada['punto_emision'], 3, "0", STR_PAD_LEFT).'-'.$datosFacturaModificada['numero_factura'];
			$numDocModificado=$xml->createElement('numDocModificado',$numFacturaModificado);
			$numDocModificado =$infoNotaCredito ->appendChild($numDocModificado);
			$fechaEmisionDocSustento=$xml->createElement('fechaEmisionDocSustento',$fechaEmisionFactura);
			$fechaEmisionDocSustento =$infoNotaCredito ->appendChild($fechaEmisionDocSustento);
			$totalSinImpuestos=$xml->createElement('totalSinImpuestos',$detalleNCValores['total_sin_iva']+ $detalleNCValores['total_con_iva']);
			$totalSinImpuestos =$infoNotaCredito ->appendChild($totalSinImpuestos);
			$valorModificacion=$xml->createElement('valorModificacion',$notaCreditoValores['total_pagar']);
			$valorModificacion =$infoNotaCredito ->appendChild($valorModificacion);
			$moneda=$xml->createElement('moneda','DOLAR');
			$moneda =$infoNotaCredito ->appendChild($moneda);

			$totalConImpuestos=$xml->createElement('totalConImpuestos');
			$totalConImpuestos =$infoNotaCredito->appendChild($totalConImpuestos);

			if($detalleNCValores['total_sin_iva'] != 0){

				$totalImpuesto=$xml->createElement('totalImpuesto');
				$totalImpuesto =$totalConImpuestos->appendChild($totalImpuesto);
				$codigo=$xml->createElement('codigo','2');
				$codigo =$totalImpuesto->appendChild($codigo);
				$codigoPorcentaje=$xml->createElement('codigoPorcentaje','0');
				$codigoPorcentaje =$totalImpuesto->appendChild($codigoPorcentaje);
				$baseImponible=$xml->createElement('baseImponible',$detalleNCValores['total_sin_iva']);
				$baseImponible =$totalImpuesto->appendChild($baseImponible);
				$valor=$xml->createElement('valor','0.00');
				$valor =$totalImpuesto->appendChild($valor);
					
			}

			if($detalleNCValores['total_con_iva'] != 0){

				$totalImpuesto=$xml->createElement('totalImpuesto');
				$totalImpuesto =$totalConImpuestos->appendChild($totalImpuesto);
				$codigo=$xml->createElement('codigo','2');
				$codigo =$totalImpuesto->appendChild($codigo);
				//$codigoPorcentaje=$xml->createElement('codigoPorcentaje','2');
				//$codigoPorcentaje=$xml->createElement('codigoPorcentaje','3');
				$codigoPorcentaje=$xml->createElement('codigoPorcentaje',$codigoIvaSRI);
				$codigoPorcentaje =$totalImpuesto->appendChild($codigoPorcentaje);
				$baseImponible=$xml->createElement('baseImponible',$detalleNCValores['total_con_iva']);
				$baseImponible =$totalImpuesto->appendChild($baseImponible);
				$valor=$xml->createElement('valor',$detalleNCValores['suma_iva']);
				$valor =$totalImpuesto->appendChild($valor);

			}

			$motivo=$xml->createElement('motivo',$motivoNotaCredito);
			$motivo =$infoNotaCredito->appendChild($motivo);

			$detalles=$xml->createElement('detalles');
			$detalles =$root->appendChild($detalles);

			for ($i = 0; $i < count($detalleNotaCredito); $i++) {
				$detalle=$xml->createElement('detalle');
				$detalle =$detalles->appendChild($detalle);

				$codigoInterno=$xml->createElement('codigoInterno',$detalleNotaCredito[$i]['idServicio']);
				$codigoInterno =$detalle->appendChild($codigoInterno);

				$descripcion=$xml->createElement('descripcion',$detalleNotaCredito[$i]['concepto']);
				$descripcion =$detalle->appendChild($descripcion);

				$cantidad=$xml->createElement('cantidad',$detalleNotaCredito[$i]['cantidad']);
				$cantidad =$detalle->appendChild($cantidad);

				$precioUnitario=$xml->createElement('precioUnitario',$detalleNotaCredito[$i]['precioUnitario']);
				$precioUnitario =$detalle->appendChild($precioUnitario);

				$descuento=$xml->createElement('descuento',$detalleNotaCredito[$i]['descuento']);
				$descuento =$detalle->appendChild($descuento);

				$precioTotalSinImpuesto=$xml->createElement('precioTotalSinImpuesto',round(($detalleNotaCredito[$i]['cantidad']*$detalleNotaCredito[$i]['precioUnitario'])-$detalleNotaCredito[$i]['descuento'],2));
				$precioTotalSinImpuesto =$detalle->appendChild($precioTotalSinImpuesto);

				$impuestos=$xml->createElement('impuestos');
				$impuestos =$detalle->appendChild($impuestos);

				$impuesto=$xml->createElement('impuesto');
				$impuesto =$impuestos->appendChild($impuesto);

				$codigo=$xml->createElement('codigo','2');
				$codigo =$impuesto->appendChild($codigo);

				//$codigoPorcentaje=$xml->createElement('codigoPorcentaje',(($detalleNotaCredito[$i]['iva'] == '0')?'0':'2'));
				//$codigoPorcentaje=$xml->createElement('codigoPorcentaje',(($detalleNotaCredito[$i]['iva'] == '0')?'0':'3'));
				$codigoPorcentaje=$xml->createElement('codigoPorcentaje',(($detalleNotaCredito[$i]['iva'] == '0')?'0':$codigoIvaSRI));
				$codigoPorcentaje =$impuesto->appendChild($codigoPorcentaje);

				//$tarifa=$xml->createElement('tarifa',(($detalleNotaCredito[$i]['iva'] == '0')?'0.00':'12.00'));
				//$tarifa=$xml->createElement('tarifa',(($detalleNotaCredito[$i]['iva'] == '0')?'0.00':'14.00'))
				$tarifa=$xml->createElement('tarifa',(($detalleNotaCredito[$i]['iva'] == '0')?'0.00':$cantidadIvaSRI));
				$tarifa =$impuesto->appendChild($tarifa);

				$baseImponible=$xml->createElement('baseImponible',round(($detalleNotaCredito[$i]['cantidad']*$detalleNotaCredito[$i]['precioUnitario'])-$detalleNotaCredito[$i]['descuento'],2));
				$baseImponible =$impuesto->appendChild($baseImponible);

				$valor=$xml->createElement('valor',(($detalleNotaCredito[$i]['iva'] == '0')?'0.00':$detalleNotaCredito[$i]['iva']));
				$valor =$impuesto->appendChild($valor);
			} 

			$infoAdicional=$xml->createElement('infoAdicional');
			$infoAdicional =$root->appendChild($infoAdicional);


			$campoAdicional =$xml->createElement('campoAdicional',$datosComprador['direccion']);
			$campoAdicional =$infoAdicional->appendChild($campoAdicional);
			$atributo = $xml->createAttribute('nombre');
			$campoAdicional->appendChild($atributo);
			$atributo_valor = $xml->createTextNode('Dirección');
			$atributo->appendChild($atributo_valor);

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

			$xml->formatOutput = true;  //poner los string en la variable $strings_xml:
			$strings_xml = $xml->saveXML();

			if (!file_exists('archivoXml/generados/'.$rutaFecha.'/')){
			    mkdir('archivoXml/generados/'.$rutaFecha.'/', 0777,true);
			}
			
			//Finalmente, guardarlo en un directorio:  	$nombreArchivo = $digito .'.xml';
			$pathSalidaXml = $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/aplicaciones/financiero/archivoXml/generados/'.$rutaFecha.'/';
			$nombreArchivoXML = $codigoXml.$digitoVerificador.'.xml';

			$xml->save($pathSalidaXml.$nombreArchivoXML);
			
			//INICIO EJAR 2017-10-30
			if (!$xml->schemaValidate('../../aplicaciones/financiero/archivoXml/xsd/notaCreditoxsdV1.1.0.xsd')) {
				$errorValidacion = false;
			}
			//FIN EJAR 2017-10-30

			//-------------------------------------FIRMA ARCHIVO XML----------------------------------------------------------------------------------------------------
			
			if($errorValidacion){
			    $resultadoFirma	= $cc->firmarXML($constg::RUTA_SERVIDOR_OPT, $constg::RUTA_APLICACION, $rutaFecha.'/'.$nombreArchivoXML, $datosInstitucion['ruta_firma'], $claveCertificado);
	
				//print_r($resultadoFirma);
				if($datosInstitucion['fecha_caducidad_pfx'] >= $fechaActualSistema){
	
					if($resultadoFirma == 'Firmado'){
					
						if (!file_exists('archivoXml/firmados/'.$rutaFecha.'/')){
					        mkdir('archivoXml/firmados/'.$rutaFecha.'/', 0777,true);
					    }
					    
					    $rutaArchivoAutorizado = $constg::RUTA_SERVIDOR_OPT."/".$constg::RUTA_APLICACION."/aplicaciones/financiero/archivoXml/firmados/".$rutaFecha."/".$nombreArchivoXML;
						$cc ->actualizarXmlNotaCredito($conexion,$idNotaCredito,$rutaArchivoAutorizado, $codigoTipoEmision);
		
						$cc ->actualizarComprobanteNotaCredito($conexion,$idNotaCredito,'RECEPTOR',$rutaArchivo, $codigoXml.$digitoVerificador);
							
						$mensaje['estado'] = 'exito';
						$mensaje['mensaje'] = 'Documento XML firmado correctamente.-'.$idNotaCredito;
					}else{
						$mensaje['estado'] = 'error';
						$mensaje['mensaje'] = 'Error al firmar el documento, clave incorrecta.-'.$idNotaCredito;
		
						//borrame todo
					}
				}else{
					$mensaje['estado'] = 'error';
					$mensaje['mensaje'] = 'Error al firmar el documento, pfx caducado.-'.$idNotaCredito;
				}
			}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = 'Error en la estructura del documento, verificar detalles de orden o totales de factura.-'.$idNotaCredito;
		}

		$conexion->desconectar();
		echo json_encode($mensaje);
	}catch (Exception $ex){
		pg_close($conexion);
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = "Error al ejecutar sentencia";
		echo json_encode($mensaje);
	}
}catch (Exception $ex) {
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}
?>