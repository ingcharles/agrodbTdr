<header>
	<h1>Administración de Ventanillas</h1>
</header>

<article id="0" class="item" data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>SeguimientoDocumental' data-opcion='ventanillas/listarAdministracionVentana' draggable="true"
	ondragstart="drag(event)" data-destino="listadoItems">
	<span>Administración de Ventanillas</span>
</article>


<article id="1" class="item" data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>SeguimientoDocumental' data-opcion='usuariosVentanilla/listarAdministracionUsuariosVentana' draggable="true"
	ondragstart="drag(event)" data-destino="listadoItems">
	<span>Asignación de Ventanillas</span>
</article>

<article id="2" class="item" data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>SeguimientoDocumental' data-opcion='tramites/listarAdministracionTramite' draggable="true"
	ondragstart="drag(event)" data-destino="listadoItems">
	<span>Administración de Trámites</span>
</article>

<script>
	$(document).ready(function(){
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');
		$("#listadoItems").addClass("comunes");
	});
</script>
