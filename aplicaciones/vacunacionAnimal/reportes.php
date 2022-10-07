<?php
session_start();
?>
	<header>
		<h1>Reportes Vehículos</h1>
	</header>
	<article id="0" class="item" data-rutaAplicacion="vacunacionAnimal"	data-opcion="reporteVacunaAnimal" draggable="true" data-destino="listadoItems">
		<div></div>
		<span>Reporte Certificados de Vacunación Usuarios Externos</span>
		<span class="ordinal">1</span>
		<aside></aside>
	</article>
	<article id="1" class="item" data-rutaAplicacion="vacunacionAnimal"	data-opcion="reporteVacunaAnimalUI" draggable="true" data-destino="listadoItems">
		<div></div>
		<span>Reporte Certificados de Vacunación Usuarios Internos</span>
		<span class="ordinal">2</span>
		<aside></aside>
	</article>
	<article id="2" class="item" data-rutaAplicacion="vacunacionAnimal"	data-opcion="reporteCatastros" draggable="true" data-destino="listadoItems">
		<div></div>
		<span>Reporte Registros de Catastros</span>
		<span class="ordinal">3</span>
		<aside></aside>
	</article>
	
	<article id="3" class="item" data-rutaAplicacion="vacunacionAnimal"	data-opcion="reporteSitiosProduccion" draggable="true" data-destino="listadoItems">
		<div></div>
		<span>Reporte Sitios</span>
		<span class="ordinal">4</span>
		<aside></aside>
	</article>

	<script>
	$(document).ready(function(){
		$("#listadoItems").removeClass("programas");
		$("#listadoItems").addClass("comunes");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un reporte para visualizar.</div>');
	});
	</script>