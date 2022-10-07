<header>
	<h1>Reportes</h1>
</header>

<article id="0" class="item"
	data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>ProveedoresExterior'
	data-opcion='ReportesProveedorExterior/listarReporteEstadoSolicitudes'
	draggable="true" ondragstart="drag(event)" data-destino="listadoItems">
	<span>Estado de solicitud de habilitación</span>
</article>


<article id="1" class="item"
	data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>ProveedoresExterior'
	data-opcion='ReportesProveedorExterior/listarReporteSolicitudesHabilitadas'
	draggable="true" ondragstart="drag(event)" data-destino="listadoItems">
	<span>Proveedores en el exterior habilitados</span>
</article>

<script>
	$(document).ready(function(){
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');
		$("#listadoItems").addClass("comunes");
	});
</script>
