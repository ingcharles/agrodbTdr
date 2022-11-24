<header><nav><?php echo $this->crearAccionBotones();?></nav></header>
<div id="paginacion" class="normal"></div>
<table id="tablaItems">
	<thead><tr>
		<th>#</th>
		<th>Columna1</th>
		<th>Columna2</th>
		<th>Columna3</th>
		<th>Columna4</th>
		</tr></thead>
	<tbody></tbody>
</table>

<script>
	$(document).ready(function () {
		construirPaginacion($("#paginacion"),<?php print_r(json_encode($this->itemsFiltrados, JSON_UNESCAPED_UNICODE)); ?>);
		$("#listadoItems").removeClass("comunes");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');
	});
		
	$("#tablaItems").click(function () {});
</script>
