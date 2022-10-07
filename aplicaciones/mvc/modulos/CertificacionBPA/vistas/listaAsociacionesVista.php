<header>
	<nav><?php echo $this->crearAccionBotones();?></nav>
</header>

<div id="paginacion" class="normal"></div>

<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th>RUC</th>
			<th>Razón Social</th>
			<th>Fecha de Registro</th>
		</tr>
	</thead>
	<tbody></tbody>
</table>

<script>
$(document).ready(function () {
	construirValidador();
	construirPaginacion($("#paginacion"),<?php print_r(json_encode($this->itemsFiltrados, JSON_UNESCAPED_UNICODE)); ?>);
	$("#listadoItems").removeClass("comunes");
	$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');
});
</script>