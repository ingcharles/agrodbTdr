<header>
<nav><?php echo $this->panelBusqueda; ?></nav>
<nav><?php echo $this->crearAccionBotones();?></nav></header>
<div id="paginacion" class="normal"></div>
<table id="tablaItems">
	<thead><tr>
		<th>#</th>
		<th>Identificador</th>
		<th>Nombre</th>
		<th>Dirección/Gestión</th>
		<th>Fecha creación</th>
		</tr></thead>
	<tbody></tbody>
</table>

<script>
	$(document).ready(function () {
		construirPaginacion($("#paginacion"),<?php print_r(json_encode($this->itemsFiltrados, JSON_UNESCAPED_UNICODE)); ?>);
		$("#listadoItems").removeClass("comunes"); 
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');
	});
		$("#_eliminar").click(function () {
		if ($("#cantidadItemsSeleccionados").text() > 1) {
			alert('Por favor seleccione un registro a la vez');
			return false;
		}
	});

	$("#btnFiltrar").click(function (event) {
		event.preventDefault();
		fn_filtrar();
	});

	// Función para filtrar

	function fn_filtrar() {
			//$("#paginacion").html("<div id='cargando'>Cargando...</div>");
			$.post("<?php echo URL ?>VacacionesPermisos/RevisionCronogramaVacaciones/filtrarInformacion",
					{
						identificadorFuncionarioInferior: $("#bIdentificadorFuncionario").val(),
						nombreFuncionario: $("#bNombreFuncionario").val(),
						fechaInicio: $("#bFechaInicio").val(),
						fechaFin: $("#bFechaFin").val()
			},
			function (data) {
				if(data.estado == 'EXITO'){
					$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');
					construirPaginacion($("#paginacion"), JSON.parse(data.contenido));
					mostrarMensaje('', "EXITO");
				}else{
					$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');
					construirPaginacion($("#paginacion"), JSON.parse(data.contenido));
					mostrarMensaje(data.mensaje, "FALLO");
				}
			}, 'json');
	}

	$("#bFechaInicio").datepicker({
		dateFormat: 'yy-mm-dd',
	});

	$("#bFechaFin").datepicker({
		dateFormat: 'yy-mm-dd',
	});

</script>
