<header><nav><?php echo $this->botones;?></nav></header>
<br>
<div >
		<h1><?php echo $this->detalleFormulario; ?></h1>
		<div class="elementos"></div>
	</div>
<div id="paginacion" class="normal"></div>
<table id="tablaItems">
	<thead><tr>
	<th>#</th>
		<th>Estado</th>
		<th>Fecha</th>
		<th>Código formulario</th>
		</tr></thead>
	<tbody></tbody>
</table>

<script>
	$(document).ready(function () {
		construirPaginacion($("#paginacion"),<?php print_r(json_encode($this->itemsFiltrados, JSON_UNESCAPED_UNICODE)); ?>);
		$("#listadoItems").removeClass("comunes"); });
		$("#_eliminar").click(function () {
		if ($("#cantidadItemsSeleccionados").text() > 1) {
			alert('Por favor seleccione un registro a la vez');
			return false;
		}
	});

	$("#tablaItems").click(function () {});
</script>
