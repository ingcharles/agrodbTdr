<header>
	<h1>Tipos de registros</h1>
</header>


<article id="1" class="item"
	data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>IngresoCuvsAretes\IngresoCuvsAretes'
	data-opcion='nuevo'
	draggable="true" ondragstart="drag(event)" data-destino="detalleItem">
	<span>Insertar Certificados Únicos de Vacunación</span>
</article>


<article id="2" class="item"
	data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>IngresoCuvsAretes\IngresoCuvsAretes'
	data-opcion='nuevo'
	draggable="true" ondragstart="drag(event)" data-destino="detalleItem">
	<span>Insertar Aretes</span>
</article>

<script>
	$(document).ready(function(){
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');
		$("#listadoItems").addClass("comunes");
	});
</script>
