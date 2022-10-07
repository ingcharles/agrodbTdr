<header><nav><?php echo $this->crearAccionBotones();?></nav></header>
<div id="paginacion" class="normal"></div>
<table id="tablaItems">
	<thead><tr>
		<th>#</th>
		<th>Id solicitud</th>
		<th>Tipo de solicitud</th>
		<th>Tipo de operación</th>
		<th>Estado</th>
		</tr></thead>
	<tbody></tbody>
</table>

<script>

	$(document).ready(function () {
		construirPaginacion($("#paginacion"),<?php print_r(json_encode($this->itemsFiltrados, JSON_UNESCAPED_UNICODE)); ?>);
		$("#listadoItems").removeClass("comunes"); 
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí registro para editar.</div>');
	});

</script>
