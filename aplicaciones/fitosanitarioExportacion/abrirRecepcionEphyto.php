<?php 
	session_start();
	
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorFitosanitarioExportacion.php';
	
	$conexion = new Conexion();
	$cfe = new ControladorFitosanitarioExportacion();
	
	$codigoSolicitud = $_POST['id'];
	
	$solicitud = pg_fetch_assoc($cfe->obtenerFitosanitarioExportacionRecibidosPorCodigo($conexion, $codigoSolicitud));
?>

<fieldset>
	<legend>Certificados fitosanitarios de exportación</legend>		
		
		<div data-linea="1">
			<label>Número de solicitud: </label> <?php echo $solicitud['codigo'];?>
		</div>
		
		<div data-linea="2">
			<label>Docuemnto XML Certificado Fitosanitario: </label><a href=<?php echo $solicitud['ruta_xml']?> target= "_blank">Descargar XML</a>
		</div>
		
		<div data-linea="3">
			<label>Documento PDF Certificado Fitosanitario: </label><a href=<?php echo $solicitud['ruta_pdf']?> target= "_blank">Descargar PDF</a>
		</div>
</fieldset>	

<script type="text/javascript">

	$(document).ready(function(){
		distribuirLineas();
	});



</script>