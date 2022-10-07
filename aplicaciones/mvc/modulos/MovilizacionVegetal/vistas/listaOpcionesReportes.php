<header>
	<h1>Reportes</h1>
</header>

<article id="0" class="item" data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>MovilizacionVegetal' data-opcion='reportes/listarReporteMovilizacion' draggable="true"
	ondragstart="drag(event)" data-destino="listadoItems">
	<span>Reporte de permisos de Movilización de productos sanidad vegetal</span>
</article>


<article id="1" class="item" data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>MovilizacionVegetal' data-opcion='reportes/listarReporteFiscalizacion' draggable="true"
	ondragstart="drag(event)" data-destino="listadoItems">
	<span>Reporte de Fiscalización de permisos de movilización</span>
</article>

<script>
	$(document).ready(function(){
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');
		$("#listadoItems").addClass("comunes");
	});
</script>
