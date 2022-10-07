<header>
	<nav><?php echo $this->crearPanelBusqueda(); ?></nav>
</header>

<header>
	<nav><?php echo $this->crearAccionBotones(); ?></nav>
</header>

<div id="paginacion" class="normal"></div>

<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th>Nombre</th>
			<th>Fecha</th>
			<th>Estado</th>
		</tr>
	</thead>

	<tbody></tbody>

</table>

<script>
	$(document).ready(function() {
		construirValidador();
		construirPaginacion($("#paginacion"), <?php print_r(json_encode($this->itemsFiltrados, JSON_UNESCAPED_UNICODE)); ?>);
		$("#listadoItems").removeClass("comunes");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un item para revisarlo.</div>');
	});

	$("#btnFiltrar").click(function(event) {
		event.preventDefault();
		$(".alertaCombo").removeClass("alertaCombo");
		$('#estado').html('');

		var error = false;

		if (!$.trim($("#fechaInicio").val())) {
			error = true;
			$("#fechaInicio").addClass("alertaCombo");
		}

		if (!$.trim($("#fechaFin").val())) {
			error = true;
			$("#fechaFin").addClass("alertaCombo");
		}

		if (!error) {
			$("#paginacion").html("<div id='cargando'>Cargando...</div>");
			$.post("<?php echo URL ?>AplicacionMovilExternos/Eventos/listarEventosFiltradas", {
					descripcion: $("#nombreEvento").val(),
					fechaInicio: $("#fechaInicio").val(),
					fechaFin: $("#fechaFin").val(),
					estado: $("#estadoEvento").val()
				},
				function(data) {
					if (data.estado === 'FALLO') {
						mostrarMensaje(data.mensaje, "FALLO");
					} else {
						$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un item para revisarlo.</div>');
						construirPaginacion($("#paginacion"), JSON.parse(data.contenido));
					}
				}, 'json');
		} else {
			$("#estado").html();
			mostrarMensaje("Por favor revise los campos obligatorios.", "FALLO");
		}
	});

	$("#fechaInicio").datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: 'yy-mm-dd',
		onSelect: function(dateText, inst) {
			var fecha = new Date($('#fechaInicio').datepicker('getDate'));
		}
	});

	$("#fechaFin").datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: 'yy-mm-dd',
		onSelect: function(dateText, inst) {
			var fecha = new Date($('#fechaInicio').datepicker('getDate'));
		}
	});
</script>