<?php
	require_once '../../../clases/Conexion.php';
	require_once '../../../clases/ControladorReportes.php';
	require_once '../../../clases/ControladorFinanciero.php';
	require_once '../../../clases/ControladorCertificados.php';
	require_once '../../../clases/ControladorImportaciones.php';	
	require_once '../../../clases/ControladorDineroElectronico.php';
	require_once 'http://localhost:8081/JavaBridge/java/Java.inc';
	
	require_once '../../general/recaptchalib.php';
//--------VERIFICADOR RECAPTCHA------------------------------------------------------------------------------------------
	//$secret = "6Lf3gSITAAAAAJbfK0KIGXGzqc54lx0q2pw9q4p4"; //--pruebas
	$secret = "6Ldcl_wSAAAAAMqC0kkrElbB2t5VAbHV5gyulJYZ"; //--porduccion
	$response=null;
	$reCaptcha = new ReCaptcha($secret);
	if ($_POST["g-recaptcha-response"]) {
		$response = $reCaptcha->verifyResponse(
				$_SERVER["REMOTE_ADDR"],
				$_POST["g-recaptcha-response"]
		);
	}

	$de = new ControladorDineroElectronico();

	$mensaje = array();
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Ha ocurrido un error!';
	echo '<header>
			<h1>Pago con Efectivo desde mi Celular</h1>
		 </header>
 <div id="estado"></div>';
	
//----------------------------recapcha--------------------------------------------------------------------
	if ($response != null && $response->success) {
//--------------------------------------------------------------------------------------------------------------

//-----------------------------verificar conectividad------------------------------------------------------------------------
	//$opcion= $de->validarConexionBce();
//	if (true) {
//--------------------------------------------------------------------------------------------------------------
	
 $cobroPre = $de->cobroDineroElectronicoPre($_POST['totalPagar'], $_POST['cedula'], $_POST['celular'],1);
 if($cobroPre['codigo']==1){
	$resultTransac=$de->cobroDineroElectronicoConfirm($_POST['totalPagar'], $_POST['cedula'], $_POST['celular'],1);
	if($resultTransac['codigo']==1){

try{
	$datos = array( 'id_pago' => htmlspecialchars ($_POST['id_pago'],ENT_NOQUOTES,'UTF-8'),
			'idOperador' =>  htmlspecialchars ($_POST['idOperador'],ENT_NOQUOTES,'UTF-8'),
			'numeroFactura' => htmlspecialchars ($_POST['numeroFactura'],ENT_NOQUOTES,'UTF-8'));
	
	$identificador = $_POST['identificador'];
	
	//-----------------------Datos pago orden------------------------------------
	$totalPagar = $_POST['totalPagar'];//datos de la transaccion
	$papeletaBanco =$resultTransac['transaccion'];//datos de la transaccion
	$fechaDeposito = $resultTransac['timestamp'];//datos de la transaccion
	$valorDepositado = $_POST['totalPagar'];//datos de la transaccion
	$codigoAmbiente = '1'; // 1-> Pruebas , 2-> Producción
	
	$rutaFecha = date('Y').'/'.date('m').'/'.date('d');
		
	try {		
		$conexion = new Conexion();
		$jru = new ControladorReportes();
		$cf = new ControladorFinanciero();
		$cc = new ControladorCertificados();
		//----------------------datos de provincia y area--------------------------------------------------------------
		$provArea= $de->devolverProvinciaArea($conexion, $identificador);//identificador del usuario creo orden pago
		$datosProvArea = pg_fetch_assoc($provArea);	
		if(pg_num_rows($provArea)!= 0){
			$provincia = $datosProvArea['nombreprovincia'];
			$idArea = $datosProvArea['idarea'];	
			$clavepfx = $datosProvArea['clavecertificado'];//certificado
			//-----------obtener clave del certificado----------
			$scr = crc32($identificador);
			$key = hash('sha512', $scr);
	        $claveCertificado= Encrypter::decrypt($clavepfx, $key);
		}

		//----------------------datos del banco-------------------------------------------------------------------------
		$entidad='Dinero Electronico';
		$banco= $de->devolverDatosBanco($conexion,$entidad); 
		$datosBanco = pg_fetch_assoc($banco);
		if(pg_num_rows($banco)!= 0){
			$idBanco = $datosBanco['idbanco'];
			$nombreBanco = $datosBanco['nombrebanco'];
			$idCuentaBanco = $datosBanco['idcuentabanco'];
			$numeroCuentaBanco = $datosBanco['numerocuentabanco'];
		}	
		//--------------------------------------------------------------------------------------------------------------

		$ordenPago = pg_fetch_assoc($cc->abrirOrdenPago($conexion, $datos['id_pago']));

		//Datos de la institucion
		$institucion = $cc -> listarDatosInstitucion($conexion,$identificador);//identificador del usuario creo orden pago
		$datosInstitucion = pg_fetch_assoc($institucion); 
				
		$iva = $ordenPago['porcentaje_iva'];
		
		$codigoIvaSRI = ($iva == 14 ? '3' : '2');
		$cantidadIvaSRI = ($iva == 14 ? '14.00' : '12.00');
		
		//Formato fecha
		$fecha = date('d').'/'.date('m').'/'.date('Y');
		$fechap = explode('/', $fecha);
		$fechaFactura = $fechap[0] . $fechap[1] . $fechap[2];
		
		//Datos Cliente
		$comprador = $cc -> listaComprador($conexion,$datos['idOperador']); //informacion de la registrada con l numero de orden
		$datosComprador = pg_fetch_assoc($comprador);
		
		//Valores Factura
		$valoresDetalle = $cc -> obtenerDatosDetalleFactura($conexion,$datos['id_pago']);
		$detalleValores =  pg_fetch_assoc($valoresDetalle);
				
		//Generando numero de factura
		if($datos['numeroFactura']==''){
			if(pg_num_rows($institucion)!= 0){
				$auxliarNumeroFactura = pg_fetch_assoc($cc->obtenerNumeroFactura($conexion, $datos['id_pago']));
				if($auxliarNumeroFactura['numero_factura'] == ''){
					$numero = pg_fetch_assoc($cc -> generarNumeroFactura($conexion, $datosInstitucion['ruc'], $datosInstitucion['numero_establecimiento'], $datosInstitucion['punto_emision']));
					$nFactura = ($numero['numero'] == ''? /*2001*/ 1 :$numero['numero']);
				}else{
					$nFactura = $auxliarNumeroFactura['numero_factura'];
				}
				
				/*if($nFactura < 2001){
					$nFactura = 2001;
				}*/
					
				$numeroSolicitud = str_pad($nFactura, 9, "0", STR_PAD_LEFT);			
			}
				
		}else{
			$numeroSolicitud = $datos['numeroFactura'];
		}
		
		$cc ->finalizarOrdenPago($conexion, $datos['id_pago'], $totalPagar, $datosInstitucion['ruc'], $numeroSolicitud, $datosInstitucion['numero_establecimiento'], $datosInstitucion['punto_emision']);
				
		//Consultar detalle factura
		$detalleFactura	  = $cc->abrirDetalleFactura($conexion,$datos['id_pago']);
		
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
			
			$verificarClaveContingencia = $cf->obtenerClaveContigenciaPorIdComprobante($conexion, $_POST['id_pago'], 'factura');
			
			if(pg_num_rows($verificarClaveContingencia)==0){
				
				$nuevaClaveContingencia = pg_fetch_assoc($cf->obtenerClaveContingencia($conexion));
				$cf->actualizarEstadoClaveContingencia($conexion, $nuevaClaveContingencia['id_clave_contingencia'], $_POST['id_pago'], 'factura');
				
				$claveContingencia = $nuevaClaveContingencia['clave'];
		
			}else{
				$claveContingencia = pg_fetch_result($verificarClaveContingencia, 0, 'clave');
			}
			
						
			$codigoXml = $fechaFactura.'01'.$claveContingencia.$codigoTipoEmision;
			$digitoVerificador =  $cc->calcularDigito($codigoXml);
			
		}else{
			$codigoTipoEmision = '1'; // 1-> Emisión normal 
			
			//Clave acceso
			$auxCodigoNumerico = $fechaFactura.'01'.$datosInstitucion['ruc'].$codigoAmbiente.str_pad($datosInstitucion['numero_establecimiento'], 3, "0", STR_PAD_LEFT).str_pad($datosInstitucion['punto_emision'], 3, "0", STR_PAD_LEFT).$numeroSolicitud.$_POST['id_pago'];
			$codigoXml = $fechaFactura.'01'.$datosInstitucion['ruc'].$codigoAmbiente.str_pad($datosInstitucion['numero_establecimiento'], 3, "0", STR_PAD_LEFT).str_pad($datosInstitucion['punto_emision'], 3, "0", STR_PAD_LEFT).$numeroSolicitud;
			$codigoNumerico = $cc->calcularDigito($auxCodigoNumerico);
			$codigoXml = $codigoXml.str_pad($codigoNumerico, 8, "0", STR_PAD_LEFT).$codigoTipoEmision;
			$digitoVerificador =  $cc->calcularDigito($codigoXml);
		}
		
		
		
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
		
		/*-------------------------------------------------------------------------------------------------------------
		if($detalleValores['subsidio']!= ''){
			$totalSubsidio = $xml->createElement('totalSubsidio',$detalleValores['subsidio']);
			$totalSubsidio = $infoFactura ->appendChild($totalSubsidio);
		}
		-------------------------------------------------------------------------------------------------------------*/
		
		$totalDescuento=$xml->createElement('totalDescuento',$detalleValores['descuento_sin_iva'] + $detalleValores['descuento_con_iva']);
		$totalDescuento =$infoFactura ->appendChild($totalDescuento);			
		
		$totalConImpuestos=$xml->createElement('totalConImpuestos');
		$totalConImpuestos =$infoFactura->appendChild($totalConImpuestos);
				
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
		
		$sumaTotalFactura=$detalleValores['total_sin_iva']+$detalleValores['total_con_iva']+$detalleValores['suma_iva'];
		$propina=$xml->createElement('propina','0.00');
		$propina =$infoFactura->appendChild($propina);
		$importeTotal=$xml->createElement('importeTotal',$sumaTotalFactura);
		$importeTotal =$infoFactura->appendChild($importeTotal);
		$moneda=$xml->createElement('moneda','DOLAR');
		$moneda =$infoFactura->appendChild($moneda);
		
		$pagos = $xml->createElement('pagos');
		$pagos = $infoFactura->appendChild($pagos);
		
		$pago = $xml->createElement('pago');
		$pago = $pagos->appendChild($pago);
		 
		$formaPagoSri = $xml->createElement('formaPago','17');
		$formaPagoSri = $pago->appendChild($formaPagoSri);
		 
		$totalSri = $xml->createElement('total',$sumaTotalFactura);
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
			
			/*-------------------------------------------------------------------------------------------------------------
			if($detalleFactura[$i]['subsidio']!= 0){
				$subsidioUnitario = $xml->createElement('precioSinSubsidio',($detalleFactura[$i]['subsidio']/$detalleFactura[$i]['cantidad'])+$detalleFactura[$i]['precioUnitario']);
				$subsidioUnitario = $detalle->appendChild($subsidioUnitario);
			}			
			-------------------------------------------------------------------------------------------------------------*/
		
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
		
		/*-----------------------------------------------------------------------------------------------------------------------------------------------------*/
				
		switch ($ordenPago['tipo_solicitud']){
			case 'Importación':
				$ci = new ControladorImportaciones();
				
				$importacion = pg_fetch_assoc($ci->obtenerImportacion($conexion, $ordenPago['id_solicitud']));
				
				$solicitudAtendida = $importacion['id_vue'];
				
				$campoAdicional =$xml->createElement('campoAdicional',$solicitudAtendida);
				$campoAdicional =$infoAdicional->appendChild($campoAdicional);
				$atributo = $xml->createAttribute('nombre');
				$campoAdicional->appendChild($atributo);
				$atributo_valor = $xml->createTextNode('numeroSolicitud');
				$atributo->appendChild($atributo_valor);
				
				
			break;
			
			case 'Operadores':
				
				$solicitudAtendida = (strlen($ordenPago['id_solicitud'])>36?(substr($ordenPago['id_solicitud'],0,36).'...'):$ordenPago['id_solicitud']);
				
				$campoAdicional =$xml->createElement('campoAdicional',$solicitudAtendida);
				$campoAdicional =$infoAdicional->appendChild($campoAdicional);
				$atributo = $xml->createAttribute('nombre');
				$campoAdicional->appendChild($atributo);
				$atributo_valor = $xml->createTextNode('numeroSolicitud');
				$atributo->appendChild($atributo_valor);
				
			break;
				
			case 'Otros':
				
				$solicitudAtendida = $ordenPago['numero_solicitud'];
				
				$campoAdicional =$xml->createElement('campoAdicional',$solicitudAtendida);
				$campoAdicional =$infoAdicional->appendChild($campoAdicional);
				$atributo = $xml->createAttribute('nombre');
				$campoAdicional->appendChild($atributo);
				$atributo_valor = $xml->createTextNode('numeroSolicitud');
				$atributo->appendChild($atributo_valor);
				
			break;			
			
		}
		
		/*-----------------------------------------------------------------------------------------------------------------------------------------------------*/
		
		$observacion = (strlen($ordenPago['observacion'])>100?(substr($ordenPago['observacion'],0,96).'...'):($ordenPago['observacion']!=''?$ordenPago['observacion']:'Sin observación.'));
		
		$campoAdicional =$xml->createElement('campoAdicional',$observacion);
		$campoAdicional =$infoAdicional->appendChild($campoAdicional);
		$atributo = $xml->createAttribute('nombre');
		$campoAdicional->appendChild($atributo);
		$atributo_valor = $xml->createTextNode('observacion');
		$atributo->appendChild($atributo_valor);
		
		/*-----------------------------------------------------------------------------------------------------------------------------------------------------*/
		
		//$datosDeposito = '';
		$datosDeposito .= 'Efectivo celular: '.$papeletaBanco.', ';
		
		$datosDeposito = rtrim($datosDeposito,', ');
		$datosDeposito = (strlen($datosDeposito)>80?(substr($datosDeposito,0,76).'...'):$datosDeposito);
		
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
		$filename = $reporte.$datos['id_pago']."_".date("Y-m-d")."_". $fecha_partir3.'_'.$fecha_partir2.'_'.$fecha_partir4.'.pdf';
		
		if (!file_exists('../../financiero/documentos/facturas/'.$rutaFecha.'/')){
		    mkdir('../../financiero/documentos/facturas/'.$rutaFecha.'/', 0777,true);
		}
		
		//Rutas Reporte Factura
		$ReporteJasper='/aplicaciones/financiero/reportes/comprobanteFactura.jrxml';
		$salidaReporte='/aplicaciones/financiero/documentos/facturas/'.$rutaFecha.'/'.$filename;
		$rutaArchivo='aplicaciones/financiero/documentos/facturas/'.$rutaFecha.'/'.$filename;
		
		$parameters = new java('java.util.HashMap');
		$parameters ->put('idpago',(int)$datos['id_pago']);
		$parameters ->put('datosDeposito',$datosDeposito);
		$parameters ->put('solicitudAtendida',$solicitudAtendida);
		$parameters ->put('observacion',$observacion);
		//$parameters ->put('rutaSubsidio', RUTA_SERVIDOR_OPT_OPT.'/'.RUTA_APLICACION.'/aplicaciones/general/img/subsidio.jpg');
		
		$ivaSRI = iva.'%';
				
		$parameters ->put('compensacion',$compensacion);
		$parameters ->put('ivaSri',$ivaSRI);
				
		$jru->generarReporteJasper($ReporteJasper,$parameters,$conexion->getConnection(),$salidaReporte,'facturacion');
				
		/*-----------------------------------------------------------------------------------------------------------------------------------------------------*/
		
		$xml->formatOutput = true;  //poner los string en la variable $strings_xml:
		$strings_xml = $xml->saveXML();
		
		if (!file_exists('../../financiero/archivoXml/generados/'.$rutaFecha.'/')){
		    mkdir('../../financiero/archivoXml/generados/'.$rutaFecha.'/', 0777,true);
		}
		
		//Finalmente, guardarlo en un directorio:  	$nombreArchivo = $digito .'.xml';
		$pathSalidaXml = $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/aplicaciones/financiero/archivoXml/generados/'.$rutaFecha.'/';
		$nombreArchivoXML = $codigoXml.$digitoVerificador.'.xml';
		$nombreArchivoXML;
		$xml->save($pathSalidaXml.$nombreArchivoXML);
		$ban=1;
		//----verificar xml generado--------------------------------------------------
		libxml_clear_errors();
		// Enable user error handling
		libxml_use_internal_errors(true);
		$xml= new DOMDocument();
		$xml->load('../../../aplicaciones/financiero/archivoXml/generados/'.$nombreArchivoXML);
		if (!$xml->schemaValidate('../../../aplicaciones/financiero/archivoXml/xsd/facturaxsdV1.1.0.xsd')) {
			//print '<b>DOMDocument::schemaValidate() Generated Errors!</b>';
			//$de->libxml_display_errors();
			$ban=0;
		}
//-------------------------------------FIRMA ARCHIVO XML----------------------------------------------------------------------------------------------------
		if($ban==1){
		    $resultadoFirma	= $cc->firmarXML($constg::RUTA_SERVIDOR_OPT, $constg::RUTA_APLICACION, $nombreArchivoXML, $datosInstitucion['ruta_firma'], $claveCertificado);
						
		if($resultadoFirma == 'Firmado'){
		    $rutaArchivoAutorizado = $constg::RUTA_SERVIDOR_OPT."/".$constg::RUTA_APLICACION."/aplicaciones/financiero/archivoXml/firmados/".$nombreArchivoXML;
			$cc ->actualizarXmlComprobanteFactura($conexion,$datos['id_pago'],$rutaArchivoAutorizado,$codigoTipoEmision);
			//Detalle pago orden
			$cc -> guardarPagoOrden($conexion, $datos['id_pago'], $fechaDeposito,$idBanco,$nombreBanco,$papeletaBanco,$valorDepositado,0,$idCuentaBanco,$numeroCuentaBanco);
			
			$cc ->actualizarComprobanteFactura($conexion,$datos['id_pago'],'RECEPTOR',$rutaArchivo, $codigoXml.$digitoVerificador, $identificador);
			
			if($ordenPago['tipo_solicitud'] != 'Otros'){
				$cf->guardarUsoDetalleFactura($conexion,$datos['id_pago'], $identificador, $provincia, $idArea, 'Facturas consumida por un servicio automatizado de '.$ordenPago['tipo_solicitud'].'.');
				$cf->actualizarEstadoUsoFactura($conexion, $datos['id_pago']);
			}
			//--------validar xml generado---------------------------------------------			
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = 'Documento XML firmado correctamente.';

		    echo '<form data-rutaAplicacion="../../../publico/pagoEfectivoCelular" data-opcion="pagoEfectivoCelular" ><fieldset>		
					<table id="tablaOrdenPago">
						<thead>
							</br>
								PAGO REALIZADO CORRECTAMENTE, LA FACTURA SERA ENVIADA A SU CORREO ELECTRÓNICO.
							</br>
						</thead>
					</table>
				  </fieldset>';
//-------------------------------------------------------------------------------------------------------------------------------
			$url='https://guia.agrocalidad.gob.ec/agrodb'.$salidaReporte;
//-------------------------------------------------------------------------------------------------------------------------------

			echo '<fieldset></br></br><embed id="visor" src='.$url.' width="640" height="550">';
			echo '</br></br><hr />
						<button id="cobrar2" class="botonTama">SALIR</button>			
				 </fieldset></form>';
			$numTramite='EXITO'.$papeletaBanco;	
			$de->notificarOrdenPago($conexion,$numTramite,$_POST['numeroSolicitud']); //----actualizar estado de orden de pago exito			
		  }else{
		  	$numTramite='FIRMA'.$papeletaBanco;
		  	$de->notificarOrdenPago($conexion,$numTramite,$_POST['numeroSolicitud']); //----informar error al firmar documento
		  	$mensaje['estado'] = 'error';
		  	$mensaje['mensaje'] = 'ERROR AL FIRMAR EL DOCUMENTO, CLAVE INCORRECTA.-'.$numeroSolicitud.'</br>CONSULTE CON EL ADMINISTRADOR DEL SISTEMA PARA GENERAR LA FACTURA...!!';
		  	echo '<form data-rutaAplicacion="../../../publico/pagoEfectivoCelular" data-opcion="pagoEfectivoCelular" ><fieldset>
		  			<table id="tablaOrdenPago">
		  				<thead>'.
		  					$mensaje['mensaje'].'</br></br><hr />
		  					<button id="cobrar2" class="botonTama">SALIR</button>
		  				</thead>
		  			</table>
		  		  </fieldset></form>';
		  }
		}else {
				$numTramite='XML'.$papeletaBanco;
			    $de->notificarOrdenPago($conexion,$numTramite,$_POST['numeroSolicitud']); //----informa de error en en generar xml
				echo '<form data-rutaAplicacion="../../../publico/pagoEfectivoCelular" data-opcion="pagoEfectivoCelular" ><fieldset>
					<table id="tablaOrdenPago">
						<thead>
							ERROR AL GENERAR XML, CONSULTE CON EL ADMINISTRADOR DEL SISTEMA PARA GENERAR LA FACTURA...!!</br></br><hr />				
						<button id="cobrar2" class="botonTama">SALIR</button>
						</thead>
					</table>
					</fieldset></form>';	
		}					
		$conexion->desconectar();

	} catch (Exception $ex){
		pg_close($conexion);
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = "Error al ejecutar sentencia";
		echo $ex;
		echo json_encode($mensaje);
	}
   } catch (Exception $ex) {
	 $mensaje['estado'] = 'error';
	 $mensaje['mensaje'] = 'Error de conexión a la base de datos';
	 echo json_encode($mensaje);
  }

}else echo '<form data-rutaAplicacion="../../../publico/pagoEfectivoCelular" data-opcion="pagoEfectivoCelular" >
<fieldset>
	<table id="tablaOrdenPago">
		<thead>Banco Central del Ecuador</br>'.
	 		$resultTransac['text']
			.'</br></br></br><hr />
			<button id="cobrar2" class="botonTama">SALIR</button>			
		</thead>
	</table>
	</fieldset></form>';
}else {
	$mensaje = $cobroPre['text'];
	if($cobroPre['codigoId']=='104')$mensaje="Banco Central del Ecuador</br>Número de cédula no se encuentra registrado";
	if($cobroPre['codigoId']=='034')$mensaje="Banco Central del Ecuador</br>Número de móvil no se encuentra registrado";
	echo '<form data-rutaAplicacion="../../../publico/pagoEfectivoCelular" data-opcion="pagoEfectivoCelular" ><fieldset>
			<table id="tablaOrdenPago">
				<thead>'.
					 $mensaje
					.'</br></br><hr />
			<button id="cobrar2" class="botonTama">SALIR</button>
			</thead>
	</table>
	</fieldset></form>';
 }
 //----------------------------------------------------------------------------------------------------------------------------
 /*}else{
 	echo '<form data-rutaAplicacion="../../../publico/pagoEfectivoCelular" data-opcion="pagoEfectivoCelular" >
 		<fieldset>
 					<legend>ALERTA</legend>
 					<table id="tablaOrdenPago">
 						<thead>
 							Error de conexion con el Banco Central del Ecuador</br>Intentelo
 			                mas tarde. Gracias por utilizar nuestro servicio.</br></br><hr />
 							<button id="cobrar2" class="botonTama">SALIR</button>
 						</thead>
 					</table>
 		</fieldset>
 	</form>';
 }*/
//-----------------------------------------------------------------------------------------------------------------------------
}else{
	echo '<form data-rutaAplicacion="../../../publico/pagoEfectivoCelular" data-opcion="pagoEfectivoCelular" >
	<fieldset>
		<legend>ALERTA</legend>
			<table id="tablaOrdenPago">
			<thead>
				ERROR DE VERIFICACIÓN DE RECAPTCHA .....!!</br></br><hr />
				<button id="cobrar2" class="botonTama">SALIR</button>
			</thead>
			</table>
		</fieldset>
	</form>';
 }
//-----------------------------------------------------------------------------------------------------------------------------
?>