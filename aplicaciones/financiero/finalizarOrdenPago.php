<?php
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorReportes.php';
	require_once '../../clases/ControladorFinanciero.php';
	require_once '../../clases/ControladorCertificados.php';
	require_once '../../clases/ControladorFitosanitario.php';
	require_once '../../clases/ControladorImportaciones.php';
	require_once '../../clases/ControladorFitosanitarioExportacion.php';
	require_once '../../clases/ControladorEtiquetas.php';
	require_once '../../clases/ControladorFinancieroAutomatico.php';
	
	$mensaje = array();
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	
	$datos = array( 'id_pago' => htmlspecialchars ($_POST['id_pago'],ENT_NOQUOTES,'UTF-8'),
			'idOperador' =>  htmlspecialchars ($_POST['idOperador'],ENT_NOQUOTES,'UTF-8'),
			'numeroFactura' => htmlspecialchars ($_POST['numeroFactura'],ENT_NOQUOTES,'UTF-8'));
	
	$identificador = $_POST['identificador'];
	$provincia = $_SESSION['nombreProvincia'];
	$idArea = $_SESSION['idArea'];
	
	//Datos pago orden
	$totalPagar = $_POST['totalPagar'];
	$idBanco = $_POST['idBanco'];
	$nombreBanco = $_POST['nombreBanco'];
	$papeletaBanco = $_POST['aPapeletaBanco'];
	$fechaDeposito = $_POST['fechaDeposito'];
	$valorDepositado = $_POST['valorDepositado'];
	$claveCertificado = $_POST['txtClaveCertificado'];
	$codigoAmbiente = '1'; //TODO: 1-> Pruebas , 2-> Producción
	$idNotaCredito = $_POST['aIdNotaCredito'];
	
	$formaPago = $_POST['formaPago'];
	$saldo = $_POST['saldo'];
	$saldoDisponible = $_POST['saldoDisponibleCLiente'];
	
	$idCuentaBanco = $_POST['idCuentaBanco'];
	$numeroCuentaBanco = $_POST['numeroCuentaBanco'];
	
	$errorValidacion = true;
			
	try {
		
		$conexion = new Conexion();
		$jru = new ControladorReportes();
		$cf = new ControladorFinanciero();
		$cc = new ControladorCertificados();
		$cfa = new ControladorFinancieroAutomatico();
		
		//Datos de la institucion
		$institucion = $cc -> listarDatosInstitucion($conexion,$identificador);
		$datosInstitucion = pg_fetch_assoc($institucion);
		
		$ordenPago = pg_fetch_assoc($cc->abrirOrdenPago($conexion, $datos['id_pago']));
		
		$iva = $ordenPago['porcentaje_iva'];
		
		$codigoIvaSRI = ($iva == 14 ? '3' : '2');
		$cantidadIvaSRI = ($iva == 14 ? '14.00' : '12.00');
		
		//Formato fecha
		$fecha = date('d').'/'.date('m').'/'.date('Y');
		$fechap = explode('/', $fecha);
		$fechaFactura = $fechap[0] . $fechap[1] . $fechap[2];
		
		//Datos Cliente
		$comprador = $cc -> listaComprador($conexion,$datos['idOperador']);
		$datosComprador = pg_fetch_assoc($comprador);
		
		//Valores Factura
		$valoresDetalle = $cc -> obtenerDatosDetalleFactura($conexion,$datos['id_pago']);
		$detalleValores =  pg_fetch_assoc($valoresDetalle);
		
	if($ordenPago['tipo_solicitud'] != 'recargaSaldo'){

		
		//Generando numero de factura
		if($datos['numeroFactura']==''){
			if(pg_num_rows($institucion)!= 0){
				$auxliarNumeroFactura = pg_fetch_assoc($cc->obtenerNumeroFactura($conexion, $datos['id_pago']));
				if($auxliarNumeroFactura['numero_factura'] == ''){
					$numero = pg_fetch_assoc($cc -> generarNumeroFactura($conexion, $datosInstitucion['ruc'], $datosInstitucion['numero_establecimiento'], $datosInstitucion['punto_emision']));
					$nFactura = ($numero['numero'] == ''? /*2001*/ 1 :$numero['numero']);
				}else{
				    $qVerificarSecuencialFactura = $cc->verificarSecuencialFacturaXNumeroEstablecimientoXRuc($conexion, $datos['id_pago'], $datosInstitucion['numero_establecimiento'], $datosInstitucion['punto_emision'], $datosInstitucion['ruc']);
				    if(pg_num_rows($qVerificarSecuencialFactura)==0){
				        $numero = pg_fetch_assoc($cc -> generarNumeroFactura($conexion, $datosInstitucion['ruc'], $datosInstitucion['numero_establecimiento'], $datosInstitucion['punto_emision']));
				        $nFactura = ($numero['numero'] == ''? 1 :$numero['numero']);
				    }else{
				        $nFactura = $auxliarNumeroFactura['numero_factura'];
				    }
				}
				$numeroSolicitud = str_pad($nFactura, 9, "0", STR_PAD_LEFT);
			}
				
		}else{
		    if($ordenPago['numero_establecimiento'] != $datosInstitucion['numero_establecimiento']){
		        $numero = pg_fetch_assoc($cc -> generarNumeroFactura($conexion, $datosInstitucion['ruc'], $datosInstitucion['numero_establecimiento'], $datosInstitucion['punto_emision']));
		        $nFactura = ($numero['numero'] == ''? 1 :$numero['numero']);
		        $numeroSolicitud = str_pad($nFactura, 9, "0", STR_PAD_LEFT);
		    }else{
		        $numeroSolicitud = $datos['numeroFactura'];
		    }
		}
		
		$qVerificarSecuencialFactura = $cc->verificarSecuencialFacturaXNumeroEstablecimientoXRucXNumeroSolicitud($conexion, $datos['id_pago'], $datosInstitucion['numero_establecimiento'], $datosInstitucion['punto_emision'], $datosInstitucion['ruc'], $numeroSolicitud);
		if(pg_num_rows($qVerificarSecuencialFactura)==0){
		    $numero = pg_fetch_assoc($cc -> generarNumeroFactura($conexion, $datosInstitucion['ruc'], $datosInstitucion['numero_establecimiento'], $datosInstitucion['punto_emision']));
		    $nFactura = ($numero['numero'] == ''? 1 :$numero['numero']);
		    $numeroSolicitud = str_pad($nFactura, 9, "0", STR_PAD_LEFT);
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
		
		///INICIO EJAR
		
		
		$sumaValorTotal = 0;
		$sumaEfectivo = 0;
		$sumaElectronico = 0;
		
		for ($a = 0; $a < count ($valorDepositado); $a++) {
										
			switch ($formaPago[$a]){

				case 'SaldoDisponible':
				case 'Deposito':
					$sumaDeposito += $valorDepositado[$a];
				break;
				
				case 'Efectivo':
					$sumaEfectivo += $valorDepositado[$a];
				break;

				case 'NotaCredito':
					$idPago = pg_fetch_result($cc->obtenerDatosNotaCredito($conexion, $idNotaCredito[$a]), 0, 'id_pago');
					$formaPagoIdPago = $cc->listaFormasPago($conexion, $idPago);
					
					$formaPagoNotaCredito = pg_fetch_assoc($formaPagoIdPago);
						
					if (strcmp($formaPagoNotaCredito['institucion_bancaria'], 'Pago en efectivo') == 0){
						$sumaEfectivo += $valorDepositado[$a];
					}else if (strcmp($formaPagoNotaCredito['institucion_bancaria'], 'Pago con saldo') == 0){
						$sumaDeposito += $valorDepositado[$a];
					}else if (strcmp($formaPagoNotaCredito['institucion_bancaria'], 'Dinero Electronico') == 0){
						$sumaElectronico += $valorDepositado[$a];
					}else{
						$sumaDeposito +=  $valorDepositado[$a];
					}			
				
				break;
			}	
		}
				
		$pagos = $xml->createElement('pagos');
		$pagos = $infoFactura->appendChild($pagos);
		
		$diferenciaPagos = $sumaEfectivo + $sumaDeposito + $sumaElectronico - $totalFactura;
		
		if($diferenciaPagos == 0){
			if($sumaEfectivo != 0) {
					
				$pago = $xml->createElement('pago');
				$pago = $pagos->appendChild($pago);
					
				$formaPagoSri = $xml->createElement('formaPago','01');
				$formaPagoSri = $pago->appendChild($formaPagoSri);
					
				$totalSri = $xml->createElement('total',$sumaEfectivo);
				$totalSri = $pago->appendChild($totalSri);
					
				$plazoSri = $xml->createElement('plazo','0');
				$plazoSri = $pago->appendChild($plazoSri);
					
				$diasSri = $xml->createElement('unidadTiempo','dias');
				$diasSri = $pago->appendChild($diasSri);
					
			}
			
			if($sumaDeposito != 0) {
			
				$pago = $xml->createElement('pago');
				$pago = $pagos->appendChild($pago);
			
				$formaPagoSri = $xml->createElement('formaPago','20');
				$formaPagoSri = $pago->appendChild($formaPagoSri);
			
				$totalSri = $xml->createElement('total',$sumaDeposito);
				$totalSri = $pago->appendChild($totalSri);
			
				$plazoSri = $xml->createElement('plazo','0');
				$plazoSri = $pago->appendChild($plazoSri);
			
				$diasSri = $xml->createElement('unidadTiempo','dias');
				$diasSri = $pago->appendChild($diasSri);
			
			}
			
			if($sumaElectronico != 0) {
			
				$pago = $xml->createElement('pago');
				$pago = $pagos->appendChild($pago);
			
				$formaPagoSri = $xml->createElement('formaPago','17');
				$formaPagoSri = $pago->appendChild($formaPagoSri);
			
				$totalSri = $xml->createElement('total',$sumaElectronico);
				$totalSri = $pago->appendChild($totalSri);
			
				$plazoSri = $xml->createElement('plazo','0');
				$plazoSri = $pago->appendChild($plazoSri);
			
				$diasSri = $xml->createElement('unidadTiempo','dias');
				$diasSri = $pago->appendChild($diasSri);
			
			}
		}else{
			
			$totalSumaDeposito =  $sumaDeposito - $saldo;
						
			if($sumaDeposito != 0 && $totalSumaDeposito != 0) {
									
				$pago = $xml->createElement('pago');
				$pago = $pagos->appendChild($pago);
					
				$formaPagoSri = $xml->createElement('formaPago','20');
				$formaPagoSri = $pago->appendChild($formaPagoSri);
					
				$totalSri = $xml->createElement('total',$totalSumaDeposito);
				$totalSri = $pago->appendChild($totalSri);
					
				$plazoSri = $xml->createElement('plazo','0');
				$plazoSri = $pago->appendChild($plazoSri);
					
				$diasSri = $xml->createElement('unidadTiempo','dias');
				$diasSri = $pago->appendChild($diasSri);
				
				//$totalDisponibleFactura = $totalFactura - $totalSumaDeposito;
					
			}			
			
			
			if($sumaEfectivo != 0 ) {
								
				$pago = $xml->createElement('pago');
				$pago = $pagos->appendChild($pago);
					
				$formaPagoSri = $xml->createElement('formaPago','01');
				$formaPagoSri = $pago->appendChild($formaPagoSri);
					
				$totalSri = $xml->createElement('total',$sumaEfectivo);
				$totalSri = $pago->appendChild($totalSri);
					
				$plazoSri = $xml->createElement('plazo','0');
				$plazoSri = $pago->appendChild($plazoSri);
					
				$diasSri = $xml->createElement('unidadTiempo','dias');
				$diasSri = $pago->appendChild($diasSri);
					
			}
				
			
				
			if($sumaElectronico != 0) {
					
				$pago = $xml->createElement('pago');
				$pago = $pagos->appendChild($pago);
					
				$formaPagoSri = $xml->createElement('formaPago','17');
				$formaPagoSri = $pago->appendChild($formaPagoSri);
					
				$totalSri = $xml->createElement('total',$sumaElectronico);
				$totalSri = $pago->appendChild($totalSri);
					
				$plazoSri = $xml->createElement('plazo','0');
				$plazoSri = $pago->appendChild($plazoSri);
					
				$diasSri = $xml->createElement('unidadTiempo','dias');
				$diasSri = $pago->appendChild($diasSri);
					
			}
		}
		
		
		
		
		
		///FIN EJAR
		
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
			
			case 'Fitosanitario':
				$cfi = new ControladorFitosanitario();
				
				$fitosanitario = pg_fetch_assoc($cfi->listarFitoExportacion($conexion, $ordenPago['id_solicitud']));
				
				$solicitudAtendida = $fitosanitario['id_vue'];
				
				$campoAdicional =$xml->createElement('campoAdicional',$solicitudAtendida);
				$campoAdicional =$infoAdicional->appendChild($campoAdicional);
				$atributo = $xml->createAttribute('nombre');
				$campoAdicional->appendChild($atributo);
				$atributo_valor = $xml->createTextNode('numeroSolicitud');
				$atributo->appendChild($atributo_valor);
				
			break;
			
			case 'FitosanitarioExportacion':
				$cfe = new ControladorFitosanitarioExportacion();
				
				$fitosanotarioExportacion = pg_fetch_assoc($cfe->obtenerCabeceraFitosanitarioExportacion($conexion, $ordenPago['id_solicitud']));
				
				$solicitudAtendida = $fitosanotarioExportacion['id_vue'];
				
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
			case 'Emisión de Etiquetas':
				
				$solicitudAtendida = $ordenPago['numero_solicitud'];
				
				$campoAdicional =$xml->createElement('campoAdicional',$solicitudAtendida);
				$campoAdicional =$infoAdicional->appendChild($campoAdicional);
				$atributo = $xml->createAttribute('nombre');
				$campoAdicional->appendChild($atributo);
				$atributo_valor = $xml->createTextNode('numeroSolicitud');
				$atributo->appendChild($atributo_valor);
			break;
			
			case 'mercanciasSinValorComercialImportacion':
			case 'mercanciasSinValorComercialExportacion':
				
				$solicitudAtendida = $ordenPago['numero_solicitud'];
				
				$campoAdicional =$xml->createElement('campoAdicional',$solicitudAtendida);
				$campoAdicional =$infoAdicional->appendChild($campoAdicional);
				$atributo = $xml->createAttribute('nombre');
				$campoAdicional->appendChild($atributo);
				$atributo_valor = $xml->createTextNode('numeroSolicitud');
				$atributo->appendChild($atributo_valor);
				
			break;
			
			case 'dossierFertilizantes':
			case 'dossierPlaguicida':
			case 'ensayoEficacia':
			    			    
			    $solicitudAtendida = $ordenPago['numero_solicitud'];
			    			    
			    $campoAdicional =$xml->createElement('campoAdicional',$solicitudAtendida);
			    $campoAdicional =$infoAdicional->appendChild($campoAdicional);
			    $atributo = $xml->createAttribute('nombre');
			    $campoAdicional->appendChild($atributo);
			    $atributo_valor = $xml->createTextNode('numeroSolicitud');
			    $atributo->appendChild($atributo_valor);
			    
			break;
			
			case 'certificacionBPA':
				
				$solicitudAtendida = $ordenPago['numero_solicitud'];
				
				$campoAdicional =$xml->createElement('campoAdicional',$solicitudAtendida);
				$campoAdicional =$infoAdicional->appendChild($campoAdicional);
				$atributo = $xml->createAttribute('nombre');
				$campoAdicional->appendChild($atributo);
				$atributo_valor = $xml->createTextNode('numeroSolicitud');
				$atributo->appendChild($atributo_valor);
				
			break;
			
			case 'certificadoFito':
			    
			    $solicitudAtendida = $ordenPago['numero_solicitud'];
			    
			    $campoAdicional =$xml->createElement('campoAdicional',$solicitudAtendida);
			    $campoAdicional =$infoAdicional->appendChild($campoAdicional);
			    $atributo = $xml->createAttribute('nombre');
			    $campoAdicional->appendChild($atributo);
			    $atributo_valor = $xml->createTextNode('numeroSolicitud');
			    $atributo->appendChild($atributo_valor);
			    
			break;
			
			case 'dossierPecuario':
			    
			    $solicitudAtendida = $ordenPago['numero_solicitud'];
			    
			    $campoAdicional =$xml->createElement('campoAdicional',$solicitudAtendida);
			    $campoAdicional =$infoAdicional->appendChild($campoAdicional);
			    $atributo = $xml->createAttribute('nombre');
			    $campoAdicional->appendChild($atributo);
			    $atributo_valor = $xml->createTextNode('numeroSolicitud');
			    $atributo->appendChild($atributo_valor);
			    
			break;
			
			case 'modificacionProductoRia':
			    
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
		
		$observacion = (strlen($ordenPago['observacion'])>100?(mb_substr($ordenPago['observacion'],0,96).'...'):($ordenPago['observacion']!=''?$ordenPago['observacion']:'Sin observación.'));
		
		$campoAdicional =$xml->createElement('campoAdicional',$observacion);
		$campoAdicional =$infoAdicional->appendChild($campoAdicional);
		$atributo = $xml->createAttribute('nombre');
		$campoAdicional->appendChild($atributo);
		$atributo_valor = $xml->createTextNode('observacion');
		$atributo->appendChild($atributo_valor);
		
		/*-----------------------------------------------------------------------------------------------------------------------------------------------------*/
		
		$datosDeposito = '';
		
		for ($j = 0; $j < count ($valorDepositado); $j++) {
			switch ($formaPago[$j]){
				case 'Deposito':
					$datosDeposito .= 'Deposito: '.$papeletaBanco[$j].', ';
				break;
				case 'Efectivo':
					$datosDeposito .= 'Efectivo, ';
				break;
				case 'NotaCredito':
					$numeroNotaCredito = pg_fetch_assoc($cc->abrirNotaCredito($conexion, $idNotaCredito[$j]));
					$datosDeposito .= 'Nota de credito: '.$numeroNotaCredito['numero_establecimiento'].'-'.$numeroNotaCredito['punto_emision'].'-'.$numeroNotaCredito['numero_nota_credito'].', ';
				break;
				case 'SaldoDisponible':
					$datosDeposito .= 'Saldo, ';
				break;
			}
		}
		
		$datosDeposito = rtrim($datosDeposito,', ');
		$datosDeposito = (strlen($datosDeposito)>80?(substr($datosDeposito,0,76).'...'):$datosDeposito);
		
		$campoAdicional =$xml->createElement('campoAdicional',$datosDeposito);
		$campoAdicional =$infoAdicional->appendChild($campoAdicional);
		$atributo = $xml->createAttribute('nombre');
		$campoAdicional->appendChild($atributo);
		$atributo_valor = $xml->createTextNode('datosDeposito');
		$atributo->appendChild($atributo_valor);
		
		$agenteRetencion = "SI";
		
		$campoAdicional =$xml->createElement('campoAdicional',$agenteRetencion);
		$campoAdicional =$infoAdicional->appendChild($campoAdicional);
		$atributo = $xml->createAttribute('nombre');
		$campoAdicional->appendChild($atributo);
		$atributo_valor = $xml->createTextNode('agenteRetencion');
		$atributo->appendChild($atributo_valor);
		
		$resolucion = "Resolución Nro. NAC-DNCRASC20-00000001";
		
		$campoAdicional =$xml->createElement('campoAdicional',$resolucion);
		$campoAdicional =$infoAdicional->appendChild($campoAdicional);
		$atributo = $xml->createAttribute('nombre');
		$campoAdicional->appendChild($atributo);
		$atributo_valor = $xml->createTextNode('resolucion');
		$atributo->appendChild($atributo_valor);
				
		$rutaFecha = date('Y').'/'.date('m').'/'.date('d');
		
		//Generando archivo pdf
		$fechap = time ();
		$fecha_partir1=date ( "h" , $fechap ) ;
		$fecha_partir2=date ( "i" , $fechap ) ;
		$fecha_partir4=date ( "s" , $fechap ) ;
		$fecha_partir3=$fecha_partir1-1;
		$reporte="ComprobanteFactura_";
		$filename = $reporte.$datos['id_pago']."_".date("Y-m-d")."_". $fecha_partir3.'_'.$fecha_partir2.'_'.$fecha_partir4.'.pdf';
		
		//Rutas Reporte Factura
		$ReporteJasper='/aplicaciones/financiero/reportes/comprobanteFactura.jrxml';
		$salidaReporte='/aplicaciones/financiero/documentos/facturas/'.$rutaFecha.'/'.$filename;
		$rutaArchivo='aplicaciones/financiero/documentos/facturas/'.$rutaFecha.'/'.$filename;
		
		if (!file_exists('documentos/facturas/'.$rutaFecha.'/')){
		    mkdir('documentos/facturas/'.$rutaFecha.'/', 0777,true);
		}

		$ivaSRI = $iva.'%';
		
		$parameters['parametrosReporte'] = array(
			'idpago'=> (int)$datos['id_pago'],
			'datosDeposito' => $datosDeposito,
			'solicitudAtendida' => $solicitudAtendida,
			'observacion' => $observacion,
			'compensacion' => $compensacion,
			'ivaSri' => $ivaSRI
		);
		
		$jru->generarReporteJasper($ReporteJasper,$parameters,$conexion,$salidaReporte,'facturacion');
		
		
		/*-----------------------------------------------------------------------------------------------------------------------------------------------------*/
		
		$xml->formatOutput = true;  //poner los string en la variable $strings_xml:
		$strings_xml = $xml->saveXML();
		
		//Finalmente, guardarlo en un directorio:  	$nombreArchivo = $digito .'.xml';
		$pathSalidaXml = $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/aplicaciones/financiero/archivoXml/generados/'.$rutaFecha.'/';
		$nombreArchivoXML = $codigoXml.$digitoVerificador.'.xml';
		
		if (!file_exists('archivoXml/generados/'.$rutaFecha.'/')){
		    mkdir('archivoXml/generados/'.$rutaFecha.'/', 0777,true);
		}
		
		$xml->save($pathSalidaXml.$nombreArchivoXML);
				
		//INICIO EJAR 2017-10-30		
		//if (!$xml->schemaValidate('../../aplicaciones/financiero/archivoXml/xsd/facturaxsdV1.1.0.xsd')) {
			/*libxml_clear_errors();
			libxml_use_internal_errors(true);
			$cc->libxml_display_errors();*/
			//$errorValidacion = false;
		//}
		//FIN EJAR 2017-10-30
					
		//-------------------------------------FIRMA ARCHIVO XML----------------------------------------------------------------------------------------------------
		if($errorValidacion){
					
			if($datosInstitucion['fecha_caducidad_pfx'] >= $fechaActualSistema){
			    
			    if (!file_exists('archivoXml/firmados/'.$rutaFecha.'/')){
			        mkdir('archivoXml/firmados/'.$rutaFecha.'/', 0777,true);
			    }
			
			    $resultadoFirma	= $cc->firmarXML($constg::RUTA_SERVIDOR_OPT, $constg::RUTA_APLICACION, $rutaFecha.'/'.$nombreArchivoXML, $datosInstitucion['ruta_firma'], $claveCertificado);
								
				if($resultadoFirma == 'Firmado'){
				    				    
				    $rutaArchivoAutorizado = $constg::RUTA_SERVIDOR_OPT."/".$constg::RUTA_APLICACION."/aplicaciones/financiero/archivoXml/firmados/".$rutaFecha."/".$nombreArchivoXML;
					$cc ->actualizarXmlComprobanteFactura($conexion,$datos['id_pago'],$rutaArchivoAutorizado,$codigoTipoEmision);
					
					//Detalle pago orden
					for ($i = 0; $i < count ($valorDepositado); $i++) {
						switch ($formaPago[$i]){
							case 'Deposito':
								$cc -> guardarPagoOrden($conexion, $datos['id_pago'], $fechaDeposito[$i],$idBanco[$i],$nombreBanco[$i],$papeletaBanco[$i],$valorDepositado[$i],0,$idCuentaBanco[$i],$numeroCuentaBanco[$i]);
							break;
							case 'Efectivo':
								$cc -> guardarPagoOrden($conexion, $datos['id_pago'], $fechaDeposito[$i],0,'Pago en efectivo','Efectivo',$valorDepositado[$i]);
							break;
							case 'SaldoSENAE':
								$cc -> guardarPagoOrden($conexion, $datos['id_pago'], $fechaDeposito[$i],0,'Saldo SENAE','Saldo SENAE',$valorDepositado[$i]);
								break;
							case 'NotaCredito':
								$cc -> guardarPagoOrden($conexion, $datos['id_pago'], 'now()',0,'Pago en nota de credito','Valor nota credito',$valorDepositado[$i],$idNotaCredito[$i]);
							break;
							case 'SaldoDisponible':
								$cc -> guardarPagoOrden($conexion, $datos['id_pago'], $fechaDeposito[$i],0,'Pago con saldo','Saldo disponible',$valorDepositado[$i]);
								
								$saldoActual = $saldoDisponible - $valorDepositado[$i];
								$cf->guardarNuevoSaldoOperadorEgreso($conexion, $datos['id_pago'], $valorDepositado[$i], $saldoActual, $datosComprador['identificador']);
							break;
						}
					}
					
					if($saldo > 0){
						
						$saldoDisponible = pg_fetch_assoc($cf->obtenerMaxSaldo($conexion,$datos['idOperador']));
						
						$saldoActual =  $saldoDisponible['saldo_disponible'] + $saldo;
						//$saldoActual = $saldoDisponible + $saldo;
						$cf->guardarNuevoSaldoOperadorIngreso($conexion, $datos['id_pago'], $saldo, $saldoActual, $datosComprador['identificador']);
					}
					
					$cc ->actualizarComprobanteFactura($conexion,$datos['id_pago'],'RECEPTOR',$rutaArchivo, $codigoXml.$digitoVerificador, $identificador);
					
					if($ordenPago['tipo_solicitud'] != 'Otros'){
						$cf->guardarUsoDetalleFactura($conexion,$datos['id_pago'], $identificador, $provincia, $idArea, 'Facturas consumida por un servicio automatizado de '.$ordenPago['tipo_solicitud'].'.');
						$cf->actualizarEstadoUsoFactura($conexion, $datos['id_pago']);
					}
					
					if($ordenPago['tipo_solicitud'] == 'certificadoFito'){
    					    $cfa->actualizarEstadoYFechaFacturaFinancieroAutomaticoCabeceraXIdPago($conexion, $datos['id_pago'], 'Atendida');
    					}
					
					$mensaje['estado'] = 'exito';
					$mensaje['mensaje'] = 'Documento XML firmado correctamente.';
				}else{
					$mensaje['estado'] = 'error';
					$mensaje['mensaje'] = 'Error al firmar el documento, clave incorrecta.-'.$numeroSolicitud;
				}
			}else{
				$mensaje['estado'] = 'error';
				$mensaje['mensaje'] = 'Error al firmar el documento, pfx caducado.-'.$numeroSolicitud;
			}
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = 'Error en la estructura del documento, verificar detalles de orden o totales de factura.-'.$numeroSolicitud;
		}
		
	}else if($ordenPago['tipo_solicitud'] == 'recargaSaldo'){
		    //echo "comprobanteX";		    
		    
    		    if($ordenPago['estado_sri'] == ''){
    		       
    		        $iva = $ordenPago['porcentaje_iva'];
    		        $solicitudAtendida = $ordenPago['numero_solicitud'];
    		        
    		        $auxliarNumeroFactura = pg_fetch_assoc($cc->obtenerNumeroFactura($conexion, $ordenPago['id_pago']));
    		        
    		        if($auxliarNumeroFactura['numero_factura'] == ''){
    		            $numero = pg_fetch_assoc($cc -> generarNumeroComprobanteFactura($conexion, $datosInstitucion['ruc'], $datosInstitucion['numero_establecimiento'], $datosInstitucion['punto_emision']));
    		            $nFactura = ($numero['numero'] == ''? 1 :$numero['numero']);
    		        }else{
    		            $nFactura = $auxliarNumeroFactura['numero_factura'];
    		        }
    		        
    		        $numeroSolicitud = str_pad($nFactura, 9, "0", STR_PAD_LEFT);
    		        
    		        $cc ->finalizarOrdenPago($conexion, $ordenPago['id_pago'], $ordenPago['total_pagar'], $datosInstitucion['ruc'], $numeroSolicitud, $datosInstitucion['numero_establecimiento'], $datosInstitucion['punto_emision']);
    		            		       		        
    		        $observacion = (strlen($ordenPago['observacion'])>100?(substr($ordenPago['observacion'],0,96).'...'):($ordenPago['observacion']!=''?$ordenPago['observacion']:'Sin observación.'));
    		        
    		        //TODO:AGREGAR SALDO AL OPERADOR		        
    		        		            
    	            $saldoDisponible = pg_fetch_assoc($cf->obtenerMaxSaldo($conexion,$datos['idOperador']));
    	            
    	            for ($k = 0; $k < count ($valorDepositado); $k++) {
    	            
    	            $saldoActual =  $saldoDisponible['saldo_disponible'] + $valorDepositado[$k];
    	          
    	            $cf->guardarNuevoSaldoOperadorIngreso($conexion, $datos['id_pago'], $valorDepositado[$k], $saldoActual, $datosComprador['identificador']);
    	        		 
    	            }
    	           
    		        //Generando archivo pdf
    		        $fechap = time ();
    		        $fecha_partir1=date ( "h" , $fechap ) ;
    		        $fecha_partir2=date ( "i" , $fechap ) ;
    		        $fecha_partir4=date ( "s" , $fechap ) ;
    		        $fecha_partir3=$fecha_partir1-1;
    		        $reporte="comprobante_";
    		        $filename = $reporte.$ordenPago['id_pago']."_".date("Y-m-d")."_". $fecha_partir3.'_'.$fecha_partir2.'_'.$fecha_partir4.'.pdf';
    		       
    		        $jru = new ControladorReportes();
    		        
    		        if (!file_exists('../../financiero/documentos/comprobante/'.$rutaFecha.'/')){
    		            mkdir('../../financiero/documentos/comprobante/'.$rutaFecha.'/', 0777,true);
    		        }
    		        
    		        //Rutas Reporte Factura
    		        $ReporteJasper='/aplicaciones/financiero/reportes/comprobante.jrxml';
    		        $salidaReporte='/aplicaciones/financiero/documentos/comprobante/'.$rutaFecha.'/'.$filename;
    		        $rutaArchivo='aplicaciones/financiero/documentos/comprobante/'.$rutaFecha.'/'.$filename;
    		        
    		        $parameters['parametrosReporte'] = array(
    		        	'idpago' => (int)$ordenPago['id_pago'],
    		        	'datosDeposito' => 'Depósito',
    		        	'solicitudAtendida' => $solicitudAtendida,
    		        	'observacion' => $observacion,
    		        	'ivaSri' => $iva
    		        );

    		        $jru->generarReporteJasper($ReporteJasper,$parameters,$conexion,$salidaReporte,'facturacion');
    		            		        
    		        //Actualiza estado del comprobante
    		        $cc->actualizarComprobanteFacturaVue($conexion, $ordenPago['id_pago'], $rutaArchivo, $ordenPago['identificador_usuario']);
    		        
    		        $mensaje['estado'] = 'exito';
    		        $mensaje['mensaje'] = 'Documento generado correctamente.';
    		    
    		}
    		
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