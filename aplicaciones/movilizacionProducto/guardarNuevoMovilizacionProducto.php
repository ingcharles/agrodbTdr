<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorMovilizacionProductos.php';
require_once '../../clases/ControladorReportes.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';
$mensaje['rutaCertificado'] = '';

try {
	$conexion = new Conexion();
	$cmp = new ControladorMovilizacionProductos();
	$jru = new ControladorReportes();
	set_time_limit(2000);
	
	// DATOS CABECERA MOVILIZACION
	$identificadorOperadorOrigen = $_POST['identificacionOperadorOrigen'];
	$identificadorOperadorDestino = $_POST['identificacionOperadorDestino'];
	$identificadorSolicitante = $_POST['identificadorSolicitante'] != "" ? $_POST['identificadorSolicitante'] : $identificadorOperadorOrigen;
	$usuarioResponsable = htmlspecialchars($_POST['identificadorResponsable'], ENT_NOQUOTES, 'UTF-8');
	$tipoSolicitud = htmlspecialchars($_POST['tipoSolicitud'], ENT_NOQUOTES, 'UTF-8');
	$tipoSolicitudMovilizacion = 'Zoosanitario';
	$lugarEmision = htmlspecialchars($_POST['nombreProvinciaEmision'], ENT_NOQUOTES, 'UTF-8');
	$oficinaEmision = htmlspecialchars($_POST['oficinaEmision'], ENT_NOQUOTES, 'UTF-8');
	$sitioOrigen = htmlspecialchars($_POST['sitioOrigen'], ENT_NOQUOTES, 'UTF-8');
	$sitioDestino = htmlspecialchars($_POST['sitioDestino'], ENT_NOQUOTES, 'UTF-8');
	$medioTransporte = htmlspecialchars($_POST['medioTransporte'], ENT_NOQUOTES, 'UTF-8');
	$placaTransporte = htmlspecialchars($_POST['placaTransporte'], ENT_NOQUOTES, 'UTF-8');
	$nombrePropietario = htmlspecialchars($_POST['nombrePropietario'], ENT_NOQUOTES, 'UTF-8');
	$identificadorConductor = htmlspecialchars($_POST['identificadorConductor'], ENT_NOQUOTES, 'UTF-8');
	$nombreConductor = htmlspecialchars($_POST['nombreConductor'], ENT_NOQUOTES, 'UTF-8');
	$observacion = htmlspecialchars($_POST['observacion'], ENT_NOQUOTES, 'UTF-8');
	$estadoMovilizacion = 'creado';
	$codigoProvinciaOrigen = htmlspecialchars($_POST['codigoProvinciaOrigen'], ENT_NOQUOTES, 'UTF-8');
	$codigoProvinciaDestino = htmlspecialchars($_POST['codigoProvinciaDestino'], ENT_NOQUOTES, 'UTF-8');
	$fechaMovilizacion = htmlspecialchars($_POST['fechaInicioMovilizacion'], ENT_NOQUOTES, 'UTF-8');
	$horaMovilizacion = htmlspecialchars($_POST['horaMovilizacion'], ENT_NOQUOTES, 'UTF-8');
	$fechaInicioVigencia = $fechaMovilizacion . " " . $horaMovilizacion;
	
	$banderaDobleGuia = $_POST['banderaDobleGuia'];
	$banderaTicket = $_POST['banderaTicket'];
	
	$tipoMovilizacion = $_POST['tipoMovilizacion'];
	
	
	try {
		
		//Verificar sitio destino feria
		
		if($tipoMovilizacion == "lote"){
			$areaDestino = $_POST['areaDestino'];
		}else if($tipoMovilizacion == "identificador"){
			$areaDestino = implode("', '", $_POST['hIdAreaDestino']);
		}
		
		$qResultadoSitio = $cmp->verificarSitioDestino($conexion, $sitioDestino, " in ('$areaDestino')");
		
		if(pg_num_rows($qResultadoSitio) > 0 ){
			$banderaTicket = 'SI';
		}else{
			$banderaTicket = 'NO';
		}
		
		//Fin vertificar sitio feria
		
		
		// Fecha de vigencia 14 horas X 60 minutos=840
		$MinutosLimites = 840; // Transformar a minutos
		
		$fechaInicio = explode(" ", str_replace("/", " ", $fechaMovilizacion));
		$horaInicio = explode(" ", str_replace(":", " ", $horaMovilizacion));
		
		$Ano = $fechaInicio[2];
		$Mes = $fechaInicio[1];
		$Dia = $fechaInicio[0];
		
		$Horas = $horaInicio[0];
		$Minutos = $horaInicio[1];
		$Segundos = $horaInicio[2];
		
		$MinutosFin = ((int) $Minutos) + ((int) $MinutosLimites);
		$fechaFinVigencia = date("Y-m-d H:i:s", mktime($Horas, $MinutosFin, $Segundos, $Mes, $Dia, $Ano));
		
		$time = time();
		
		if (date("Y/m/d H:i", mktime($Horas, $Minutos, $Segundos, $Mes, $Dia, $Ano)) < date("Y/m/d H:i", $time + 200)){
			$estadoMovilizacion = 'caducado';
		}
		
		$fechaRegistroMovilizacion = date('Y-m-d H:i:s');
		$anio = date('Y');
		$mes = date('m');
		$dia = date('d');
		
		//$conexion->ejecutarConsulta("begin; LOCK TABLE g_movilizacion_producto.movilizacion IN SHARE ROW EXCLUSIVE MODE;");
		$conexion->ejecutarConsulta("begin;");
		$secuencialAutogenerado = $cmp->autogenerarNumerosCertificadosMovilizacion($conexion, $codigoProvinciaOrigen, $codigoProvinciaDestino);
		$secuencialCertificado = str_pad($secuencialAutogenerado, 5, "0", STR_PAD_LEFT);
		$aleatorio = str_pad(rand(1, 99), 2, "0", STR_PAD_LEFT);
		$numeroCertificado = $codigoProvinciaOrigen . $codigoProvinciaDestino . $secuencialCertificado . date('dmy').$aleatorio;
			
		$fechaRegistro = date('d-m-Y H.i.s');
		$fileNameNuevo = $tipoSolicitudMovilizacion . " " . $numeroCertificado . " " . $fechaRegistro;
		$fileNameNuevo = str_replace(" ", "_", $fileNameNuevo);
		
		define('RUTA_GUIAS', 'aplicaciones/movilizacionProducto/documentos/guias/' . $anio . '/' . $mes . '/' . $dia . '/');
		define('RUTA_TICKETS', 'aplicaciones/movilizacionProducto/documentos/ticket/' . $anio . '/' . $mes . '/' . $dia . '/');
		
		$archivoGuias = str_replace('aplicaciones/movilizacionProducto/', '', RUTA_GUIAS);
		$archivoTicket = str_replace('aplicaciones/movilizacionProducto/', '', RUTA_TICKETS);
		
		if (! file_exists($archivoGuias)){
			mkdir($archivoGuias, 0777, true);
		}
		
		if (! file_exists($archivoTicket)){
			mkdir($archivoTicket, 0777, true);
		}
		
		$rutaCertificado = RUTA_GUIAS . $fileNameNuevo . '.pdf'; //ESTA ES LA RUTA GIUIA
		$rutaCertificado1 = RUTA_TICKETS . 'Ticket_' . $fileNameNuevo . '.pdf';
		
		$mensaje['rutaCertificado'] = $rutaCertificado;
		
		if($banderaDobleGuia == 'SI'){
			
			//siempre de vigencia va a ser 72 horas X 60 minutos=4320
			
			$secuencialAutogeneradoDobleGuia=$cmp->autogenerarNumerosCertificadosMovilizacion($conexion,$codigoProvinciaDestino,$codigoProvinciaOrigen);
			$secuencialCertificadoDobleGuia = str_pad($secuencialAutogeneradoDobleGuia, 5, "0", STR_PAD_LEFT);
			$aleatorio = str_pad(rand(1, 99), 2, "0", STR_PAD_LEFT);
			$numeroCertificadoDobleGuia= $codigoProvinciaDestino.$codigoProvinciaOrigen.$secuencialCertificadoDobleGuia.date('dmy').$aleatorio;
			
			$fileNameDobleGuiaNuevo = $tipoSolicitudMovilizacion." ".$numeroCertificadoDobleGuia." ".$fechaRegistro;
			$fileNameDobleGuiaNuevo =str_replace(" ","_",$fileNameDobleGuiaNuevo);
			$rutaCertificadoDobleGuia = RUTA_GUIAS.$fileNameDobleGuiaNuevo.'.pdf';
			
			//$cmp->actualizarFechaCertificadoMovilizacion($conexion, $idMovilizacion, $idMovilizacionDobleGuia, $fechaFinVigenciaDobleGuia); TODO: tener pendiente
			
			$datoBanderaDobleGuia = $numeroCertificadoDobleGuia;
			
		}
		
		$banderaCicloCerrado = $_POST['banderaCicloCerrado'];
		
		if($tipoMovilizacion == "lote"){
			
			$numeroLote = $_POST['lotesProducto'];
			$idAreaDestino = $_POST['areaDestino'];
			$idAreaOrigen = $_POST['areaOrigen'];
			$idOperacionOrigen = $_POST['operacionOrigen'];
			$idProducto = $_POST['gProducto'];
			$cantidad = $_POST['cantidad'];
			$idUnidadComercial = $_POST['unidadComercial'];
			//$banderaLote = $_POST['banderaLote'];
			$tipoDestino = $_POST['tipoDestino'];
			
			$identificadoresProducto = "";
			$arrayIdentificadoresProducto = array();
			
			$areasDestino = "";
			$arrayAreasDestino = array();
			
			$qProductosActosMovilizar = $cmp->buscarProductosConVacuna($conexion, 'vacunacion', $idProducto);
			
			if(pg_num_rows($qProductosActosMovilizar) == 0){
				
			    $qCatastroProducto = $cmp->obtenerProductosSinVacunacionPorLote($conexion, $idAreaOrigen, $idProducto, $idOperacionOrigen, $idUnidadComercial, $numeroLote, $cantidad);
				
				while($fila = pg_fetch_assoc($qCatastroProducto)){
					$identificadoresProducto .= $fila['identificador_producto'].",";
				}
				
				$identificadoresProducto = rtrim($identificadoresProducto, ",");
				$arrayIdentificadoresProducto = explode(",", $identificadoresProducto);
				
			}else{
				
			   
			    $qCatastroProducto = $cmp->obtenerProductosConVacunacionPorLote($conexion, $idAreaOrigen, $idProducto, $idOperacionOrigen, $idUnidadComercial, $numeroLote, $cantidad, $tipoDestino, $banderaCicloCerrado);
				
				while($fila = pg_fetch_assoc($qCatastroProducto)){
					$identificadoresProducto .= $fila['identificador_producto'].",";
				}
				
				$identificadoresProducto = rtrim($identificadoresProducto, ",");
				$arrayIdentificadoresProducto = explode(",", $identificadoresProducto);
				
			}
			
			foreach($arrayIdentificadoresProducto as $llave){
				$areasDestino .= $idAreaDestino.",";
			}
			
			$areasDestino = rtrim($areasDestino, ",");
			$arrayAreasDestino = explode(",", $areasDestino);
			
			$totalProductos = count($arrayIdentificadoresProducto);
			
			$arrayCabeceraMovilizacion = array('numeroCertificado' => $numeroCertificado, 'provinciaEmision' => $lugarEmision, 'sitioOrigen' => $sitioOrigen,
				'sitioDestino' => $sitioDestino, 'placaTransporte' => $placaTransporte, 'identificadorConductor' => $identificadorConductor, 'usuarioResponsable' => $usuarioResponsable, 'estadoMovilizacion' => $estadoMovilizacion,
				'rutaCertificado' => $rutaCertificado, 'fechaRegistroMovilizacion' => $fechaRegistroMovilizacion, 'fechaInicioVigencia' => $fechaInicioVigencia, 'fechaFinVigencia' => $fechaFinVigencia, 'codigoProvinciaOrigen' => $codigoProvinciaOrigen, 'codigoProvinciaDestino' => $codigoProvinciaDestino,
				'secuencialCertificado' => $secuencialCertificado, 'nombreConductor' => $nombreConductor, 'medioTransporte' => $medioTransporte, 'tipoSolicitud' => $tipoSolicitud, 'observacion' => $observacion, 'rutaTicket' => '', 'oficinaEmision' => $oficinaEmision,
				'identificadorSolicitante' => $identificadorSolicitante, 'totalProductos' => $totalProductos, 'estadoFiscalizacion' => 'No fiscalizado', 'numeroCertificadoDobleGuia' => $numeroCertificadoDobleGuia, 'identificadorOperadorDestino' => $identificadorOperadorDestino, 'rutaDobleGuia' => $rutaCertificadoDobleGuia, 'nombrePropietario' => $nombrePropietario);
						
			$datoBanderaDobleGuia = ($datoBanderaDobleGuia == "null" || $datoBanderaDobleGuia == "" || $datoBanderaDobleGuia == "NO" ? $datoBanderaDobleGuia = "" : $datoBanderaDobleGuia);
						
			$cabecera = "array['".implode("', '", $arrayCabeceraMovilizacion)."']";
			
			$identificadores = "array['".implode("', '", $arrayIdentificadoresProducto)."']";
			
			$areasDestino = "array['".implode("', '", $arrayAreasDestino)."']";
			
			$sentencia = "SELECT g_movilizacion_producto.proceso_guardarmovilizacion(".$cabecera.", ". $identificadores .", ". $areasDestino . ", '" . $datoBanderaDobleGuia. "', '" . $banderaTicket . "');";
			
			$resultadoFuncion = pg_fetch_assoc($cmp -> guardarMovilizacionProceso($conexion, $sentencia));//Esta es la función que realiza el preceso de movilizacion
			
			$cabecera = explode('-', $resultadoFuncion['proceso_guardarmovilizacion']);
			
			$conexion->ejecutarConsulta("commit;");
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = 'Los datos han sido actualizados satisfactoriamente';
			
		}else if($tipoMovilizacion == "identificador"){
			
			$idAreaDestino = $_POST['hIdAreaDestino'];
			$identificadorProducto = $_POST['hIdentificadoresValidar'];
			
			$totalProductos = count($identificadorProducto);
			
			$arrayCabeceraMovilizacion = array('numeroCertificado' => $numeroCertificado, 'provinciaEmision' => $lugarEmision, 'sitioOrigen' => $sitioOrigen,
				'sitioDestino' => $sitioDestino, 'placaTransporte' => $placaTransporte, 'identificadorConductor' => $identificadorConductor, 'usuarioResponsable' => $usuarioResponsable, 'estadoMovilizacion' => $estadoMovilizacion,
				'rutaCertificado' => $rutaCertificado, 'fechaRegistroMovilizacion' => $fechaRegistroMovilizacion, 'fechaInicioVigencia' => $fechaInicioVigencia, 'fechaFinVigencia' => $fechaFinVigencia, 'codigoProvinciaOrigen' => $codigoProvinciaOrigen, 'codigoProvinciaDestino' => $codigoProvinciaDestino,
				'secuencialCertificado' => $secuencialCertificado, 'nombreConductor' => $nombreConductor, 'medioTransporte' => $medioTransporte, 'tipoSolicitud' => $tipoSolicitud, 'observacion' => $observacion, 'rutaTicket' => '', 'oficinaEmision' => $oficinaEmision,
				'identificadorSolicitante' => $identificadorSolicitante, 'totalProductos' => $totalProductos, 'estadoFiscalizacion' => 'No fiscalizado', 'numeroCertificadoDobleGuia' => $numeroCertificadoDobleGuia, 'identificadorOperadorDestino' => $identificadorOperadorDestino, 'rutaDobleGuia' => $rutaCertificadoDobleGuia, 'nombrePropietario' => $nombrePropietario);
			
			
			/*echo '<pre>';
			 print_r($arrayCabeceraMovilizacion);
			 echo '<pre>';*/
			
			$datoBanderaDobleGuia = ($datoBanderaDobleGuia == "null" || $datoBanderaDobleGuia == "" || $datoBanderaDobleGuia == "NO" ? $datoBanderaDobleGuia = "" : $datoBanderaDobleGuia);
			
			$cabecera = "array['".implode("', '", $arrayCabeceraMovilizacion)."']";
			
			$identificadores = "array['".implode("', '", $identificadorProducto)."']";
			
			$areasDestino = "array['".implode("', '", $idAreaDestino)."']";
			
			$sentencia = "SELECT g_movilizacion_producto.proceso_guardarmovilizacion(".$cabecera.", ". $identificadores .", ". $areasDestino . ", '" . $datoBanderaDobleGuia. "', '" . $banderaTicket . "');";
			
			$resultadoFuncion = pg_fetch_assoc($cmp -> guardarMovilizacionProceso($conexion, $sentencia));//Esta es la función que realiza el preceso de movilizacion
			
			$cabecera = explode('-', $resultadoFuncion['proceso_guardarmovilizacion']);
			
			
			//echo $cabecera[0];  echo '----' ;       echo $cabecera[1];
			
			
			$conexion->ejecutarConsulta("commit;");
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = 'Los datos han sido actualizados satisfactoriamente';
			
		}
		
		$ReporteJasper = 'aplicaciones/movilizacionProducto/reportes/reporteMovilizacion.jrxml';
		
		$parameters['parametrosReporte'] = array(
			'id_movilizacion'=> (int) $cabecera[0]
		);
		
		$jru->generarReporteJasper($ReporteJasper, $parameters, $conexion, $rutaCertificado, 'logoMovilizacion');
				
		if ($banderaTicket == 'SI') {
			
			$cmp -> guardarRutaTicketMovilizacion($conexion, $cabecera[0], $rutaCertificado1);
			
			$ReporteJasperTicket = 'aplicaciones/movilizacionProducto/reportes/reporteTicket.jrxml';
			
			$parametersTicket['parametrosReporte'] = array(
				'id_movilizacion'=> (int) $cabecera[0]
			);
			
			$jru->generarReporteJasper($ReporteJasperTicket, $parametersTicket, $conexion, $rutaCertificado1, 'logoMovilizacionTicket');
			
		}
		
		if ($banderaDobleGuia == 'SI') {
			
			$parameters2['parametrosReporte'] = array(
				'id_movilizacion'=> (int) $cabecera[1]
			);
			
			$jru->generarReporteJasper($ReporteJasper,$parameters2,$conexion,$rutaCertificadoDobleGuia,'logoMovilizacion');
			
			include '../general/PDFMerger.php';
			
			$tempFileName=$fileNameNuevo.'temp';
			copy($archivoGuias.$fileNameNuevo.'.pdf',$archivoGuias.$tempFileName.'.pdf');
			
			unlink($archivoGuias.$fileNameNuevo.'.pdf');
			
			$rutaArchivoNuevoDobleGuia=$constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/'.RUTA_GUIAS.'/'.$fileNameNuevo.'.pdf';
			$pdf = new PDFMerger();
			$pdf->addPDF($archivoGuias.$tempFileName.'.pdf', 'all');
			$pdf->addPDF($archivoGuias.$fileNameDobleGuiaNuevo.'.pdf', 'all');
			$pdf->merge('file', $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/'.RUTA_GUIAS.'/'.$fileNameNuevo.'.pdf');
			
			unlink($archivoGuias.$tempFileName.'.pdf');
			unlink($archivoGuias.$fileNameDobleGuiaNuevo.'.pdf');
			$rutaArchivoNuevoDobleGuiaDos=$constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/'.RUTA_GUIAS.'/'.$fileNameDobleGuiaNuevo.'.pdf';
			copy($rutaArchivoNuevoDobleGuia, $rutaArchivoNuevoDobleGuiaDos);
			
		}
		
		
	} catch (Exception $ex) {
		$conexion->ejecutarConsulta("rollback;");
		$mensaje['mensaje'] = $ex->getMessage();
		$mensaje['error'] = $conexion->mensajeError . $ex;
		$err = preg_replace("/\r|\n/", " ", $mensaje['error']);
		$conexion->ejecutarLogsTryCatch($ex . '---ERROR:' . $err);
	} finally {
		$conexion->desconectar();
	}
} catch (Exception $ex) {
	$mensaje['mensaje'] = $ex->getMessage();
	$mensaje['error'] = $conexion->mensajeError;
	$err = preg_replace("/\r|\n/", " ", $mensaje['error']);
	$conexion->ejecutarLogsTryCatch($ex . '---ERROR:' . $err);
} finally {
	echo json_encode($mensaje);
}
