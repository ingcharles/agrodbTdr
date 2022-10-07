<?php 
	session_start();		
?>

<form id='nuevoObtenerCertificado' data-rutaAplicacion='fitosanitarioExportacion' data-opcion='guardarNuevoRecepcionEphytoHub' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
	<fieldset>
		<legend>Certificados fitosanitarios de exportación</legend>		
			<button id="bGuardar" type="submit" class="guardar">Obtener certificados</button>
	</fieldset>	
</form>

<script type="text/javascript">

$("#nuevoObtenerCertificado").submit(function(event){
	event.preventDefault(event);
	ejecutarJson($(this));
});

</script>