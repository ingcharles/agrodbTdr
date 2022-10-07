<?php
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorReportes.php';
	require_once '../../clases/ControladorFinanciero.php';
	require_once '../../clases/ControladorCertificados.php';
	
	$mensaje = array();
	$mensaje['estado'] = 'error'; 
	$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	
	$datos = array( 'id_pago' => htmlspecialchars ($_POST['id_pago'],ENT_NOQUOTES,'UTF-8'),
			'idOperador' =>  htmlspecialchars ($_POST['idOperador'],ENT_NOQUOTES,'UTF-8'),
			'numeroFactura' => htmlspecialchars ($_POST['numeroFactura'],ENT_NOQUOTES,'UTF-8'));
	
	$identificador = $_POST['identificador'];
	$provincia = $_SESSION['nombreProvincia'];
	
	//Datos pago Ingreso caja
	$totalPagar = $_POST['totalPagar'];
	$idBanco = $_POST['idBanco'];
	$nombreBanco = $_POST['nombreBanco'];
	$papeletaBanco = $_POST['aPapeletaBanco'];
	$fechaDeposito = $_POST['fechaDeposito'];
	$valorDepositado = $_POST['valorDepositado'];
	$formaPago = $_POST['formaPago'];
	
	$saldo = $_POST['saldo'];
	$saldoDisponible = $_POST['saldoDisponibleCLiente'];
	
	$idCuentaBanco = $_POST['idCuentaBanco'];
	$numeroCuentaBanco = $_POST['numeroCuentaBanco'];

	try {
		
		$conexion = new Conexion();
		$jru = new ControladorReportes();
		$cf = new ControladorFinanciero();
		$cc = new ControladorCertificados();
		
		$rutaFecha = date('Y').'/'.date('m').'/'.date('d');
		
		//Datos de la institucion
		$institucion = $cc -> listarDatosInstitucion($conexion,$identificador);
		$datosInstitucion = pg_fetch_assoc($institucion);
		
		//Datos Cliente
		$comprador = $cc -> listaComprador($conexion,$datos['idOperador']);
		$datosComprador = pg_fetch_assoc($comprador);
		
		//Generando numero de factura
		if($datos['numeroFactura']==''){
			if(pg_num_rows($institucion)!= 0){
				$numero = pg_fetch_assoc($cc -> generarNumeroFacturaIngresoCaja($conexion, $datosInstitucion['ruc'], $datosInstitucion['numero_establecimiento'], $datosInstitucion['punto_emision']));
				$nFactura = ($numero['numero'] == ''? 1 :$numero['numero']);
				
				//if($nFactura > 1){
				//	$nFactura = 1;
				//}
				
				$numeroSolicitud = str_pad($nFactura, 9, "0", STR_PAD_LEFT);
			}
				
		}else{
			$numeroSolicitud = $datos['numeroFactura'];
		}
		
		$cc ->finalizarOrdenIngresoCaja($conexion, $datos['id_pago'], $totalPagar, $datosInstitucion['ruc'], $numeroSolicitud, $datosInstitucion['numero_establecimiento'], $datosInstitucion['punto_emision']);
		
		//Generando archivo pdf
		$fechap = time ();
		$fecha_partir1=date ( "h" , $fechap ) ;
		$fecha_partir2=date ( "i" , $fechap ) ;
		$fecha_partir4=date ( "s" , $fechap ) ;
		$fecha_partir3=$fecha_partir1-1;
		$reporte="IngresoCaja_";
		$filename = $reporte.$datos['id_pago']."_".date("Y-m-d")."_". $fecha_partir3.'_'.$fecha_partir2.'_'.$fecha_partir4.'.pdf';

		$datosDeposito = '';
		
		for ($j = 0; $j < count ($valorDepositado); $j++) {
			switch ($formaPago[$j]){
				case 'Deposito':
					$datosDeposito .= 'Deposito: '.$papeletaBanco[$j].', ';
				break;
				case 'Efectivo':
					$datosDeposito .= 'Efectivo, ';
				break;
				case 'SaldoDisponible':
					$datosDeposito .= 'Saldo, ';
				break;
			}
		}
		
		$datosDeposito = rtrim($datosDeposito,', ');
		$datosDeposito = (strlen($datosDeposito)>80?(substr($datosDeposito,0,76).'...'):$datosDeposito);
		
		$ordenPago = pg_fetch_assoc($cc->abrirOrdenPago($conexion, $datos['id_pago']));
		$observacion = (strlen($ordenPago['observacion'])>100?(substr($ordenPago['observacion'],0,96).'...'):($ordenPago['observacion']!=''?$ordenPago['observacion']:'Sin observación.'));
		
		$solicitudAtendida = $ordenPago['numero_solicitud'];
		
		if (!file_exists('documentos/ingresoCaja/'.$rutaFecha.'/')){
		    mkdir('documentos/ingresoCaja/'.$rutaFecha.'/', 0777,true);
		}
				
		//Rutas Reporte Factura
		$ReporteJasper='/aplicaciones/financiero/reportes/ingresoCaja.jrxml';
		$salidaReporte='/aplicaciones/financiero/documentos/ingresoCaja/'.$rutaFecha.'/'.$filename;
		$rutaArchivo='aplicaciones/financiero/documentos/ingresoCaja/'.$rutaFecha.'/'.$filename;
		
		$parameters['parametrosReporte'] = array(
			'idpago' => (int)$datos['id_pago'],
			'datosDeposito' => $datosDeposito,
			'solicitudAtendida' => $solicitudAtendida,
			'observacion' => $observacion
		);
		
		$jru->generarReporteJasper($ReporteJasper,$parameters,$conexion,$salidaReporte,'facturacion');

		//Detalle pago Ingreso caja
			for ($i = 0; $i < count ($valorDepositado); $i++) {
				switch ($formaPago[$i]){
					case 'Deposito':
						$cc -> guardarPagoOrden($conexion, $datos['id_pago'], $fechaDeposito[$i],$idBanco[$i],$nombreBanco[$i],$papeletaBanco[$i],$valorDepositado[$i],0,$idCuentaBanco[$i],$numeroCuentaBanco[$i]);
					break;
					case 'Efectivo':
						$cc -> guardarPagoOrden($conexion, $datos['id_pago'], $fechaDeposito[$i],0,'Pago en efectivo','Efectivo',$valorDepositado[$i]);
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
			
			$cc ->actualizarIngresoCaja($conexion,$datos['id_pago'], $rutaArchivo, $identificador);
			
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = 'Documento generado correctamente.';

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

