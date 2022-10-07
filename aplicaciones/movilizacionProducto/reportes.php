<?php
session_start();
?>
<header>
	<h1>Reportes Catastro</h1>
</header>
<article style="height:140px;" id="0" class="item" data-rutaAplicacion="movilizacionProducto"	data-opcion="reporteCertificadosMovilizacion" draggable="true" data-destino="listadoItems">
	<div></div>
	<span>Reporte de Certificados de Movilización de productos agropecuarios</span>
	<span class="ordinal">1</span>
	<aside></aside>
</article>	
<article style="height:140px;" id="1" class="item" data-rutaAplicacion="movilizacionProducto"	data-opcion="reporteFiscalizacionesMovilizacion" draggable="true" data-destino="listadoItems">
	<div></div>
	<span>Reporte de Fiscalizaciones de certificados de movilización</span>
	<span class="ordinal">2</span>
	<aside></aside>
</article>


<script>
$(document).ready(function(event){
	$("#listadoItems").removeClass("programas");
	$("#listadoItems").addClass("comunes");
	$("#detalleItem").html('<div class="mensajeInicial">Seleccione un reporte para visualizar.</div>');
});
</script>