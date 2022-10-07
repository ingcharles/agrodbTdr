<?php
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorReportes.php';
	require_once '../../clases/ControladorUsuarios.php';
	require_once '../../clases/ControladorFinanciero.php';
	require_once '../../clases/ControladorCertificados.php';
	require_once '../../clases/Constantes.php';
	
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>

<?php
	
	$observacion = htmlspecialchars($_POST['observacion'],ENT_NOQUOTES,'UTF-8');
	$valorTotal = $_POST['valorTotal'];
	$idDeposito = ($_POST['idDeposito']);
	$cantidad = ($_POST['cantidad']);
	$precioUnitario = ($_POST['precioUnitario']);
	$totalIndividual = ($_POST['totalIndividual']);
	$idCliente = htmlspecialchars ($_POST['idCliente'],ENT_NOQUOTES,'UTF-8');
	$tipoCliente = htmlspecialchars ($_POST['tipoBusquedaCliente'],ENT_NOQUOTES,'UTF-8');
	$varCliente = htmlspecialchars ($_POST['txtClienteBusqueda'],ENT_NOQUOTES,'UTF-8');
	$ruc = htmlspecialchars ($_POST['ruc'],ENT_NOQUOTES,'UTF-8');
	$razonSocial = htmlspecialchars ($_POST['razonSocial'],ENT_NOQUOTES,'UTF-8');
	$direccion = htmlspecialchars ($_POST['direccion'],ENT_NOQUOTES,'UTF-8');
	$telefono = htmlspecialchars ($_POST['telefono'],ENT_NOQUOTES,'UTF-8');
	$correo = htmlspecialchars ($_POST['correo'],ENT_NOQUOTES,'UTF-8');
	$identificadorUsuario = $_SESSION['usuario'];
	
	$tipoSolicitud = ($_POST['tipoSolicitud']);
	$idSolicitud = '0';
	$idGrupoSolicitud = 0;
	
	$idOrdenPago = htmlspecialchars ($_POST['idOrdenPago'],ENT_NOQUOTES,'UTF-8');
	
	$localizacionUsuario = $_SESSION['nombreLocalizacion'];
					
	$conexion = new Conexion();
	$cc = new ControladorCertificados();
	$cf = new ControladorFinanciero();
	$ccu = new ControladorUsuarios();
	
	$rutaFecha = date('Y').'/'.date('m').'/'.date('d');
	
	$datosRecaudador = $cf ->obtenerDatosRecaudador($conexion, $identificadorUsuario);
	$recaudador = pg_fetch_assoc($datosRecaudador);
	
	$res = $ccu->obtenerProvincia($conexion, $identificadorUsuario);
	$provincia = pg_fetch_assoc($res);
			
	$anioActual = date('Y');
	
	$res = $cc -> generarNumeroDocumento($conexion, '%AGR-'.$anioActual.'%');
	$documento = pg_fetch_assoc($res);
	$tmp= explode("-", $documento['numero']);
	$incremento = end($tmp)+1;
	$numeroSolicitud = 'AGR-'.$anioActual.'-'.str_pad($incremento, 9, "0", STR_PAD_LEFT);
	
	#Cabecera Ingreso caja
	$listaCliente =  pg_fetch_assoc($cc->listaComprador($conexion,$idCliente));
	
	if ($idCliente != ''){
		if($razonSocial == '') $razonSocial=$listaCliente['razon_social'];
		if($direccion == '') $direccion=$listaCliente['direccion'];
		if($telefono == '') $telefono=$listaCliente['telefono'];
		if($correo == '') $correo=$listaCliente['correo'];
		
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
	
	if($idOrdenPago != ''){
		$cf->darBajaOrdenPago($conexion, $idOrdenPago, 'El Ingreso de caja actualizada, se realiza el cambio de estado por parte del usuario '.$identificadorUsuario.'.');
	}
										
	$ordenPago = $cc -> guardarOrdenPago($conexion, $idCliente, $numeroSolicitud, $valorTotal, $observacion, $localizacionUsuario, $provincia['nombre'], $provincia['id_localizacion'], $identificadorUsuario, $idSolicitud, $tipoSolicitud, $idGrupoSolicitud);
	$fila =  pg_fetch_assoc($ordenPago);
	
	$institucion = $cc -> listarDatosInstitucion($conexion,$identificadorUsuario);
	$datosInstitucion = pg_fetch_assoc($institucion);
	
	$cc->actualizarPorcentajeIvaOrdenPago($conexion, $fila['id_pago'], $datosInstitucion['iva']);
	
	
	//Detalle Ingreso caja
	for ($i = 0; $i < count ($idDeposito); $i++) {
			$concepto = pg_fetch_assoc($cc->abrirServicios($conexion, $idDeposito[$i]));
			$cc -> guardarTotal($conexion, $fila['id_pago'], $idDeposito[$i], $concepto['concepto'],$cantidad[$i],0,$precioUnitario[$i],0,$totalIndividual[$i]);
	}
	
	//Generando pdf Ingreso caja
	$fecha = time ();
	$fecha_partir1=date ( "h" , $fecha ) ;
	$fecha_partir2=date ( "i" , $fecha ) ;
	$fecha_partir4=date ( "s" , $fecha ) ;
	$fecha_partir3=$fecha_partir1-1;
	$reporte="ReporteIngresoCaja";
	$filename = $reporte."_".$idCliente."_".date("Y-m-d")."_".$fecha_partir3.'_'.$fecha_partir2.'_'.$fecha_partir4.'.pdf';
	
	//Ruta del reporte compilado por Jasper y generado por IReports
	$jru = new ControladorReportes();
	
	if (!file_exists('documentos/ingresoCaja/'.$rutaFecha.'/')){
	    mkdir('documentos/ingresoCaja/'.$rutaFecha.'/', 0777,true);
	}
		
	$ReporteJasper= '/aplicaciones/financiero/reportes/reporteIngresoCaja.jrxml';
	$salidaReporte= '/aplicaciones/financiero/documentos/ingresoCaja/'.$rutaFecha.'/'.$filename;
	$rutaArchivo= 'aplicaciones/financiero/documentos/ingresoCaja/'.$rutaFecha.'/'.$filename;
	
	$parameters['parametrosReporte'] = array(
		'idpago'=> (int)$fila['id_pago']
	);
	
	$jru->generarReporteJasper($ReporteJasper,$parameters,$conexion,$salidaReporte,'facturacion');
	
	$ordenPago = $cc->abrirOrdenPago($conexion,$fila['id_pago']);
	$orden = pg_fetch_assoc($ordenPago);
	
	if($orden['estado'] == 3){
		echo '<embed id="visor" src='.$rutaArchivo.' width="540" height="550">';
	}
	
	$cc -> guardarRutaOrdenPago($conexion,$fila['id_pago'],$rutaArchivo);
	
?>

</body>
	<script type="text/javascript">
		$(document).ready(function(){
		abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),null,true);
		});		
	</script>
</html>
