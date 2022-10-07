<?php
	session_start();
	require_once '../../clases/Conexion.php';
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
	
	$datos = array( 'idPago' => htmlspecialchars ($_POST['id_pago'],ENT_NOQUOTES,'UTF-8'),
					'idNotaCredito' => htmlspecialchars ($_POST['idNotaCredito'],ENT_NOQUOTES,'UTF-8'),
					'tipoDocumento' =>  htmlspecialchars ($_POST['tipoDocumento'],ENT_NOQUOTES,'UTF-8'));
	
		$conexion = new Conexion();
		$cc = new ControladorCertificados();
					
		switch ($datos['tipoDocumento']){
			case 'factura':
			case 'comprobanteSaldo':
				$detalleFactura	= $cc->abrirOrdenPago($conexion,$datos['idPago']);
				$datosFactura = pg_fetch_assoc($detalleFactura);
				$archivo = $datosFactura['comprobante_factura'];
			break;
			
			case 'notaCredito':
				$detalleNotaCredito	= $cc->obtenerDatosNotaCredito($conexion,$datos['idNotaCredito']);
				$datosNotaCredito = pg_fetch_assoc($detalleNotaCredito);
				$archivo = $datosNotaCredito['comprobante_nota_credito'];
				
			break;
			
			default:
				echo 'Documento desconocido';
		}
		
		
?>

<embed id="visor" src="<?php echo  $archivo; ?>" width="540" height="550">


</body>

	<script type="text/javascript">

	var tipoSolicitud = <?php  echo json_encode($datos['tipoDocumento']);?>;

	if(tipoSolicitud == 'factura' || tipoSolicitud == 'comprobanteSaldo'){
		$("#filtrarSolicitudes").click();
	}else{
		$("#_actualizar").click();
	}
	

	</script>

</html>
