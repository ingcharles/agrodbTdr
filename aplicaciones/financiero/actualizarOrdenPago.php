<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorReportes.php';
require_once '../../clases/ControladorUsuarios.php';
require_once '../../clases/ControladorCertificados.php';
require_once '../../clases/ControladorFinanciero.php';

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>

	<?php

	$idPago = $_POST['idPago'];
	$observacion = htmlspecialchars($_POST['observacion'],ENT_NOQUOTES,'UTF-8');
	$valorTotal = $_POST['valorTotal'];
	$idDeposito = ($_POST['idDeposito']);
	//$nombreDeposito = ($_POST['nombreDeposito']);
	$nombreDeposito = isset($_POST['nombreDeposito']) ? $_POST['nombreDeposito'] : '';
	$cantidad = ($_POST['cantidadItem']);
	$precioUnitario = ($_POST['precioUnitario']);
	$ivaIndividual = ($_POST['ivaIndividual']);
	$totalIndividual = ($_POST['totalIndividual']);
	$idCliente = htmlspecialchars ($_POST['idCliente'],ENT_NOQUOTES,'UTF-8');
	$descuento = ($_POST['descuentoUnidad']);
	$subsidio = ($_POST['subsidio']);
	$tipoCliente = htmlspecialchars ($_POST['tipoBusquedaCliente'],ENT_NOQUOTES,'UTF-8');
	$tipoIdentificacion = htmlspecialchars ($_POST['tipoIdentificacion'],ENT_NOQUOTES,'UTF-8');
	$varCliente = htmlspecialchars ($_POST['txtClienteBusqueda'],ENT_NOQUOTES,'UTF-8');
	//$razonSocial = htmlspecialchars ($_POST['razonSocial'],ENT_NOQUOTES,'UTF-8');
	$razonSocial = isset($_POST['razonSocial']) ? $_POST['razonSocial'] : '';
	//$direccion = htmlspecialchars ($_POST['direccion'],ENT_NOQUOTES,'UTF-8');
	$direccion = isset($_POST['direccion']) ? $_POST['direccion'] : '';
	//$telefono = htmlspecialchars ($_POST['telefono'],ENT_NOQUOTES,'UTF-8');
	$telefono = isset($_POST['telefono']) ? $_POST['telefono'] : '';
	//$correo = htmlspecialchars ($_POST['correo'],ENT_NOQUOTES,'UTF-8');
	$correo = isset($_POST['correo']) ? $_POST['correo'] : '';
	$identificadorUsuario = $_SESSION['usuario'];
	$ruc = htmlspecialchars ($_POST['ruc'],ENT_NOQUOTES,'UTF-8');

	$localizacionUsuario = $_SESSION['nombreLocalizacion'];

	$idSolicitud = '0';
	$tipoSolicitud = 'Otros';
	$idGrupoSolicitud = 0;

	$conexion = new Conexion();
	$cc = new ControladorCertificados();
	$cf = new ControladorFinanciero();
	$ccu = new ControladorUsuarios();
	
	#Orden de Pago

	$res = $ccu->obtenerProvincia($conexion, $identificadorUsuario);
	$provincia = pg_fetch_assoc($res);
	
	$institucion = $cc -> listarDatosInstitucion($conexion,$identificadorUsuario);
	$datosInstitucion = pg_fetch_assoc($institucion);

	$anioActual = date('Y');

	$res = $cc -> generarNumeroDocumento($conexion, '%AGR-'.$anioActual.'%');
	$documento = pg_fetch_assoc($res);
	$tmp= explode("-", $documento['numero']);
	$incremento = end($tmp)+1;
	$numeroSolicitud = 'AGR-'.$anioActual.'-'.str_pad($incremento, 9, "0", STR_PAD_LEFT);

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

	if(count($idDeposito)!= 0){
		//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------


		//$cc -> actualizarOrdenPago($conexion,$idPago,$idCliente,$valorTotal,$observacion,$_SESSION['nombreLocalizacion'], $identificadorUsuario);
		$cf->darBajaOrdenPago($conexion, $idPago, 'Orden de pago actualizada, se realiza el cambio de estado de la orden de pago por el usuario '.$identificadorUsuario.'.');

		//Detalle orden
		// $cc ->eliminarItemsOrdenPago($conexion,$idPago);

		$ordenPago = $cc -> guardarOrdenPago($conexion, $idCliente, $numeroSolicitud, $valorTotal, $observacion, $localizacionUsuario, $provincia['nombre'], $provincia['id_localizacion'], $identificadorUsuario, $idSolicitud, $tipoSolicitud, $idGrupoSolicitud);
		$fila =  pg_fetch_assoc($ordenPago);
		
		$cc->actualizarPorcentajeIvaOrdenPago($conexion, $fila['id_pago'], $datosInstitucion['iva']);

		//Detalle orden de pago
		for ($i = 0; $i < count ($idDeposito); $i++) {
			if($descuento[$i]=='')
				$descuento = 0;
			$concepto = pg_fetch_assoc($cc->abrirServicios($conexion, $idDeposito[$i]));			
			$cc -> guardarTotal($conexion, $fila['id_pago'], $idDeposito[$i], $concepto['concepto'],$cantidad[$i],$descuento[$i],$precioUnitario[$i],$ivaIndividual[$i],$totalIndividual[$i], $subsidio[$i]);
		}
		
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

		$ReporteJasper='/aplicaciones/financiero/reportes/reporteOrden.jrxml';
		$salidaReporte='/aplicaciones/financiero/documentos/ordenPago/'.$rutaFecha.'/'.$filename;
		$rutaArchivo = 'aplicaciones/financiero/documentos/ordenPago/'.$rutaFecha.'/'.$filename;
		
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

		if($orden['estado'] == 3){
		echo '<embed id="visor" src='.$rutaArchivo.' width="540" height="550">';
	}

	$cc -> guardarRutaOrdenPago($conexion,$fila['id_pago'],$rutaArchivo);

	//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------
	}else{
		echo 'Error en el sistema, por favor intente nuevamente.';
	}

	?>

</body>
<script type="text/javascript">	

		$(document).ready(function(){
			abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),null,true);
		});		

	</script>
</html>

