<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorReportes.php';
require_once '../../clases/ControladorUsuarios.php';
require_once '../../clases/ControladorFinanciero.php';
require_once '../../clases/ControladorCertificados.php';
require_once '../../clases/ControladorFinancieroAutomatico.php';
require_once '../../clases/ControladorEtiquetas.php';
require_once '../../clases/ControladorModificacionProductoRia.php';

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>

	<?php
	
	try{

		$observacion = htmlspecialchars($_POST['observacion'],ENT_NOQUOTES,'UTF-8');
		$valorTotal = $_POST['valorTotal'];

		$idDeposito = ($_POST['idDeposito']);
		$cantidad = ($_POST['cantidadItem']);
		$precioUnitario = ($_POST['precioUnitario']);
		$ivaIndividual = ($_POST['ivaIndividual']);
		$totalIndividual = ($_POST['totalIndividual']);
		$idCliente = htmlspecialchars ($_POST['idCliente'],ENT_NOQUOTES,'UTF-8');
		$descuento = ($_POST['descuentoUnidad']);
		$subsidio = ($_POST['subsidio']);

		$tipoCliente = htmlspecialchars ($_POST['tipoBusquedaCliente'],ENT_NOQUOTES,'UTF-8');
		$varCliente = htmlspecialchars ($_POST['txtClienteBusqueda'],ENT_NOQUOTES,'UTF-8');
		$ruc = htmlspecialchars ($_POST['ruc'],ENT_NOQUOTES,'UTF-8');
		$razonSocial = $_POST['razonSocial'];
		$direccion = htmlspecialchars ($_POST['direccion'],ENT_NOQUOTES,'UTF-8');
		$telefono = htmlspecialchars ($_POST['telefono'],ENT_NOQUOTES,'UTF-8');
		$correo = htmlspecialchars ($_POST['correo'],ENT_NOQUOTES,'UTF-8');
		$identificadorUsuario = $_SESSION['usuario'];

		$idSolicitud= $_POST['idSolicitud'];
		$tipoSolicitud = ($_POST['tipoSolicitud']);
		$idGrupoSolicitud = ($_POST['idGupoSolicitudes']);
		$metodoPago = ($_POST['metodoPago']);

		$idOrdenPago = htmlspecialchars ($_POST['idOrdenPago'],ENT_NOQUOTES,'UTF-8');

		$localizacionUsuario = $_SESSION['nombreLocalizacion'];
				
		$conexion = new Conexion();
		$cc = new ControladorCertificados();
		$cf = new ControladorFinanciero();
		$ccu = new ControladorUsuarios();
		$cfa = new ControladorFinancieroAutomatico();
		
		$res = $ccu->obtenerProvincia($conexion, $identificadorUsuario);
		$provincia = pg_fetch_assoc($res);

		$anioActual = date('Y');

		$res = $cc -> generarNumeroDocumento($conexion, '%AGR-'.$anioActual.'%');
		$documento = pg_fetch_assoc($res);
		$tmp= explode("-", $documento['numero']);
		$incremento = end($tmp)+1;
		$numeroSolicitud = 'AGR-'.$anioActual.'-'.str_pad($incremento, 9, "0", STR_PAD_LEFT);
		
		switch ($tipoSolicitud){
			case 'mercanciasSinValorComercialImportacion':
			case 'mercanciasSinValorComercialExportacion':
				$idCliente = $ruc;
			break;
		}

		#Cabecera orden de Pago
		$listaCliente =  pg_fetch_assoc($cc->listaComprador($conexion,$idCliente));

		if ($idCliente != ''){
			if($razonSocial == '') $razonSocial=$listaCliente['razon_social'];
			if($direccion == '') $direccion=$listaCliente['direccion'];
			if($telefono == '') $telefono=$listaCliente['telefono'];
			if($correo == '') $correo=$listaCliente['correo'];
			
			if($tipoSolicitud == 'mercanciasSinValorComercialImportacion' || $tipoSolicitud == 'mercanciasSinValorComercialExportacion'){
				$tipoCliente = $listaCliente['tipo_identificacion'];
			}

			$cliente =   $cc -> actualizarCliente($conexion,$idCliente,$tipoCliente,$razonSocial,$direccion,$telefono,$correo);
		}else{

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

		if(count($idDeposito)!= 0){
			//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------

			if($idOrdenPago != ''){
				$cf->darBajaOrdenPago($conexion, $idOrdenPago, 'Orden de pago actualizada, se realiza el cambio de estado de la orden de pago por el usuario '.$identificadorUsuario.'.');
			}
				
			$ordenPago = $cc -> guardarOrdenPago($conexion, $idCliente, $numeroSolicitud, $valorTotal, $observacion, $localizacionUsuario, $provincia['nombre'], $provincia['id_localizacion'], $identificadorUsuario, $idSolicitud, $tipoSolicitud, $idGrupoSolicitud);
			$fila =  pg_fetch_assoc($ordenPago);
			
			$institucion = $cc -> listarDatosInstitucion($conexion,$identificadorUsuario);
			$datosInstitucion = pg_fetch_assoc($institucion);
			
			$cc->actualizarPorcentajeIvaOrdenPago($conexion, $fila['id_pago'], $datosInstitucion['iva']);

			//Detalle orden de pago
			for ($i = 0; $i < count ($idDeposito); $i++) {
				if($descuento[$i]=='')
					$descuento = 0;
				$concepto = pg_fetch_assoc($cc->abrirServicios($conexion, $idDeposito[$i]));
				$cc -> guardarTotal($conexion, $fila['id_pago'], $idDeposito[$i], $concepto['concepto'],$cantidad[$i],$descuento[$i],$precioUnitario[$i],$ivaIndividual[$i],$totalIndividual[$i], $subsidio[$i]);
			}

			if($valorTotal == '0'){
				$cc ->actualizarXmlComprobanteFactura($conexion,$fila['id_pago'],'Ningún archivo disponible.','1');
				$cc ->actualizarComprobanteFactura($conexion,$fila['id_pago'],'FINALIZADO','NINGUNA', 'Ninguna', $identificadorUsuario);
			}
			
			if($valorTotal != '0' && ($tipoSolicitud=="FitosanitarioExportacion" || $tipoSolicitud=="Importación" || $tipoSolicitud == "Fitosanitario")){
			    
				$idVue= $_POST['idVue'];
				
				$cfa->eliminarOrdenFinancieroAutomatico($conexion, $idVue);
				
				$idFinancieroCabecera = pg_fetch_result($cfa->guardarFinancieroAutomaticoCabecera($conexion, $valorTotal, $idVue, $tipoSolicitud), 0, 'id_financiero_cabecera');
				
				for ($i = 0; $i < count ($idDeposito); $i++) {
				
					if($descuento[$i]=='')
						$descuento = 0;
					$concepto = pg_fetch_assoc($cc->abrirServicios($conexion, $idDeposito[$i]));
				
					$cfa->guardarFinancieroAutomaticoDetalle($conexion, $idFinancieroCabecera, $idDeposito[$i], $concepto['concepto'], $cantidad[$i], $precioUnitario[$i], $descuento[$i], $ivaIndividual[$i], $totalIndividual[$i]);
				
				}
				
				$cfa->actualizarIdOrdenPagoFinancieroAutomaticoCabecera($conexion, $idFinancieroCabecera, $fila['id_pago']);
				$cfa->actualizarEstadoFinancieroAutomaticoCabecera($conexion, $idFinancieroCabecera, 'Atendida');
				
			}else if($tipoSolicitud=="Emisión de Etiquetas"){
				
				if($valorTotal == '0'){
					$estadoEtiqueta = 'Aprobado';
				}else{
					$estadoEtiqueta ='Por Pagar';
				}
			    
			    $ce = new ControladorEtiquetas();
				$ce->actualizarDatosSolicitudEtiqueta($conexion,'estado',$estadoEtiqueta,$idSolicitud);
				
			}else if($tipoSolicitud=="dossierPecuario"){
			    
			     require_once '../../clases/ControladorDossierPecuario.php';
			     $cdp = new ControladorDossierPecuario();
			     
			     $datosDocumento=array();
			     
			     if($valorTotal == '0'){
			         $estado='Recibido';
			         $mensaje = 'Financiero remitió la solicitud a Registros';
			     }else{
			         $estado='verificacion';
			         $mensaje = 'Proceso de Asignación de Tasa';
			     }
			      
			     $cdp -> actualizarEstadoSolicitud($conexion, $estado, $idSolicitud, $_SESSION['usuario'], $_SESSION['idProvincia'], $mensaje);
															
			}else if($tipoSolicitud == "dossierFertilizantes"){
			    
			    require_once '../../clases/ControladorDossierFertilizante.php';			    
			    $cdf = new ControladorDossierFertilizante();
			    
			    $datosDocumento=array();
			    
			    if($valorTotal == '0'){
			        $datosDocumento['estado']='asignarTecnico';
			    }else{
			        $datosDocumento['estado']='verificacion';
			    }
			    
			    $datosDocumento['id_solicitud'] = $idSolicitud;
			    $cdf -> guardarSolicitud($conexion,$datosDocumento);
			    
			}else if($tipoSolicitud == "dossierPlaguicida"){
			    
			    require_once '../../clases/ControladorDossierPlaguicida.php';
			    $cep = new ControladorDossierPlaguicida();
			    
			    $datosDocumento=array();
			    
			    if($valorTotal == '0'){
			        $datosDocumento['estado']='asignarTecnico';
			    }else{
			        $datosDocumento['estado']='verificacion';
			    }
			    
			    $datosDocumento['id_solicitud'] = $idSolicitud;
			    $cep -> guardarSolicitud($conexion,$datosDocumento);
			    
			}else if($tipoSolicitud == "ensayoEficacia"){
			    
			    require_once '../../clases/ControladorEnsayoEficacia.php';
			    $cee = new ControladorEnsayoEficacia();
			    
			    $datosDocumento=array();
			    
			    if($valorTotal == '0'){
			        $datosDocumento['estado']='verificacionProtocolo';
			    }else{
			        $datosDocumento['estado']='verificacion';
			    }
			    
			    $datosDocumento['id_protocolo'] = $idSolicitud;
			    $cee -> guardarProtocolo($conexion,$datosDocumento);
			    
			}else if($tipoSolicitud == "certificadoFito"){
			    
			    require_once '../../clases/ControladorCertificadoFito.php';
			    $ccf = new ControladorCertificadoFito();
			    
			    $cc->actualizarMetodoPagoPorIdPago($conexion, $fila['id_pago'], $metodoPago);
			    
			    $qCertificadoFito = $ccf->abrirSolicitud($conexion, $idSolicitud);
			    $certificadoFito = pg_fetch_assoc($qCertificadoFito);
			    
			    $codigoCertificado = $certificadoFito['codigo_certificado'];
			    
			    $cfa->eliminarOrdenFinancieroAutomatico($conexion, $codigoCertificado);
			    
			    $idFinancieroCabecera = $cfa->guardarFinancieroAutomaticoCabecera($conexion, $valorTotal, $codigoCertificado, $tipoSolicitud, $metodoPago);
			    $idFinancieroCabecera = pg_fetch_result($idFinancieroCabecera, 0, 'id_financiero_cabecera');
			    
			    $cfa->actualizarTipoProcesoFacturaFinancieroAutomaticoCabeceraPorIdentificador($conexion, $idFinancieroCabecera, 'factura');
			    
			    for ($i = 0; $i < count ($idDeposito); $i++) {
			        
			        if($descuento[$i]=='')
			            $descuento = 0;
			            $concepto = pg_fetch_assoc($cc->abrirServicios($conexion, $idDeposito[$i]));
			            
			            $cfa->guardarFinancieroAutomaticoDetalle($conexion, $idFinancieroCabecera, $idDeposito[$i], $concepto['concepto'], $cantidad[$i], $precioUnitario[$i], $descuento[$i], $ivaIndividual[$i], $totalIndividual[$i]);
			            
			    }
			    
			    $cfa->actualizarIdOrdenPagoFinancieroAutomaticoCabecera($conexion, $idFinancieroCabecera, $fila['id_pago']);
			    $cfa->actualizarEstadoFinancieroAutomaticoCabecera($conexion, $idFinancieroCabecera, 'Atendida');
			     
			     if($metodoPago == 'saldo'){
			        $cfa->actualizarEstadoYFechaFacturaFinancieroAutomaticoCabeceraXIdPago($conexion, $fila['id_pago'], 'Por atender');
			        $cfa->actualizarMetodoPagoPorIdPago($conexion, $fila['id_pago'], $metodoPago);	
			    }
			}else if($tipoSolicitud=="modificacionProductoRia"){
			    
			    if($valorTotal == '0'){
			        $estadoEtiqueta = 'inspeccion';
			    }else{
			        $estadoEtiqueta ='verificacion';
			    }
			    
			    $cmp = new ControladorModificacionProductoRia();
			    $cmp->actualizarEstadoSolicitudPorIdSolicitudProducto($conexion, $idSolicitud, 'verificacion');
			    
			}
			
			//INICIO EJAR
			$valoresDetalle = $cc -> obtenerDatosDetalleFactura($conexion,$fila['id_pago']);
			$detalleValores =  pg_fetch_assoc($valoresDetalle);
			//FIN EJAR

			//Generando orden de pago
			$fecha = time ();
			$fecha_partir1=date ( "h" , $fecha ) ;
			$fecha_partir2=date ( "i" , $fecha ) ;
			$fecha_partir4=date ( "s" , $fecha ) ;
			$fecha_partir3=$fecha_partir1-1;
			$reporte="ReporteOrden";
			$filename = $reporte."_".$idCliente."_".date("Y-m-d")."_".$fecha_partir3.'_'.$fecha_partir2.'_'.$fecha_partir4.'.pdf';
			$nombreArchivo = $reporte."_".$idCliente."_".date("Y-m-d")."_".$fecha_partir3.'_'.$fecha_partir2.'_'.$fecha_partir4;

			//Ruta del reporte compilado por Jasper y generado por IReports
			$jru = new ControladorReportes();
						
			$rutaFecha = date('Y').'/'.date('m').'/'.date('d');

			$ReporteJasper= '/aplicaciones/financiero/reportes/reporteOrden.jrxml';
			$salidaReporte= '/aplicaciones/financiero/documentos/ordenPago/'.$rutaFecha.'/'.$filename;
			$rutaArchivo= 'aplicaciones/financiero/documentos/ordenPago/'.$rutaFecha.'/'.$filename;
			
			if (!file_exists('documentos/ordenPago/'.$rutaFecha.'/')){
			    mkdir('documentos/ordenPago/'.$rutaFecha.'/', 0777,true);
			}
			
			$sumaSubsidio = array_sum($subsidio);

			$parameters['parametrosReporte'] = array(
				'idpago' => (int)$fila['id_pago'],
				'totalSubsidio' => (double)$sumaSubsidio
			);
			
			$jru->generarReporteJasper($ReporteJasper,$parameters,$conexion,$salidaReporte,'facturacion');

			$ordenPago = $cc->abrirOrdenPago($conexion,$fila['id_pago']);
			$orden = pg_fetch_assoc($ordenPago);

			if($orden['estado'] == 3 || $orden['estado'] == 5){
			 echo '<embed id="visor" src='.$rutaArchivo.' width="540" height="550">';
		}

		$cc -> guardarRutaOrdenPago($conexion,$fila['id_pago'],$rutaArchivo);

		//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------
		}else{
			echo 'Error en el sistema, por favor intente nuevamente.';
		}
	} catch (Exception $ex){			
		$err = preg_replace( "/\r|\n/", " ", $conexion->mensajeError);
		$conexion->ejecutarLogsTryCatch($ex.'---ERROR:'.$err);
	}		
	?>

</body>
<script type="text/javascript">
		$(document).ready(function(){
			abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),null,true);
		});		
	</script>
</html>
