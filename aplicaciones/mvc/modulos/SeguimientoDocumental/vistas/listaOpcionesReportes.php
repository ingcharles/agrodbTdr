<header>
	<h1>Reportes Administrativos</h1>
</header>

<article id="0" class="item" data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>SeguimientoDocumental' data-opcion='reportes/listarReporteTramitesAdm' draggable="true"
	ondragstart="drag(event)" data-destino="listadoItems">
	<span>Trámites Generados</span>
</article>


<article id="1" class="item" data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>SeguimientoDocumental' data-opcion='reportes/listarReporteValijasAdm' draggable="true"
	ondragstart="drag(event)" data-destino="listadoItems">
	<span>Valijas de Correo enviadas</span>
</article>

<article id="2" class="item" data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>SeguimientoDocumental' data-opcion='reportes/listarReportePorcentajeTramitesAdm' draggable="true"
	ondragstart="drag(event)" data-destino="listadoItems">
	<span>Porcentaje de Trámites</span>
</article>

<article id="3" class="item" data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>SeguimientoDocumental' data-opcion='reportes/listarReportePorcentajeValijasAdm' draggable="true"
	ondragstart="drag(event)" data-destino="listadoItems">
	<span>Porcentaje de Valijas</span>
</article>

<script>
	$(document).ready(function(){
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');
		$("#listadoItems").addClass("comunes");
	});
</script>