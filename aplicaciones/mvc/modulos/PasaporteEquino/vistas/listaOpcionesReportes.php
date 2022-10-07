<header>
	<h1>Reportes</h1>
</header>

<article id="0" class="item" data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>PasaporteEquino' data-opcion='reportes/listarReportePasaporte' draggable="true"
	ondragstart="drag(event)" data-destino="listadoItems">
	<span>Reporte de Pasaportes emitidos</span>
</article>

<article id="1" class="item" data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>PasaporteEquino' data-opcion='reportes/listarReporteMovilizacion' draggable="true"
	ondragstart="drag(event)" data-destino="listadoItems">
	<span>Reporte de Movilizaciones</span>
</article>


<article id="2" class="item" data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>PasaporteEquino' data-opcion='reportes/listarReporteFiscalizacion' draggable="true"
	ondragstart="drag(event)" data-destino="listadoItems">
	<span>Reporte de Fiscalizaciones</span>
</article>

<script>
	$(document).ready(function(){
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un ítem para revisarlo.</div>');
		$("#listadoItems").addClass("comunes");
	});
</script>
