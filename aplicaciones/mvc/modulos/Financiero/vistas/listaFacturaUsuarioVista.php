<header>
	<h1>Facturas</h1>
	<nav>
		<table class="filtro" style="width:500px">
			<tr>
				<th colspan="2">Buscar:</th>
			</tr>
			<tr id='idTipoSolicitud'>
				<th>Solicitud</th>
				<td colspan="3">
					<select id="tipoSolicitud" name="tipoSolicitud" style="width: 100%;">
						<?php echo $this->comboTipoSolicitudFinanciero(); ?>
					</select>
				</td>
			</tr>
			<tr id="filaFactura">
				<th>Número factura</th>
				<td>
					<input id="numeroFactura" type="text" maxlength="9" style="width: 100%" />
				</td>
				<th id="ordenVue">Orden GUIA</th>
				<td>
					<input id="numeroOrdenGuia" name="numeroOrdenGuia" type="text" maxlength="21" style="width: 100%" />
				</td>
			</tr>
			<tr id='filaSolicitud'>
				<th id="idSolicitud">Número solicitud</th>
				<td>
					<input id="numeroSolicitud" name="numeroSolicitud" type="text" maxlength="21" style="width: 100%" />
				</td>
				<th id="ordenVue">Orden VUE</th>
				<td>
					<input id="numeroOrdenVue" name="numeroOrdenVue" type="text" maxlength="21" style="width: 100%" />
				</td>
			</tr>
			<tr>
				<th>Fecha inicio:</th>
				<td colspan="3">
					<input id="fechaInicio" type="text" name="fechaInicio" style="width: 100%" required="true" class="camposRequeridos" onChange="calcularFechas(this),10">
				</td>
			</tr>
			<tr>
				<th>Fecha fin:</th>
				<td colspan="3">
					<input id="fechaFin" type="text" name="fechaFin" style="width: 100%" required="true" class="camposRequeridos">
				</td>
			</tr>
			<tr>
				<td colspan="5">
					<button id="btnFiltrar">Filtrar lista</button>
				</td>
			</tr>
		</table>
	</nav>
</header>

<div id="resultadoFacturas"></div>

<div id="paginacion" class="normal"></div>
<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th>Orden de Pago</th>
			<th>Factura</th>
			<th>Monto</th>
			<th>Fecha</th>
		</tr>
	</thead>
	<tbody></tbody>
</table>

<script>
	$(document).ready(function() {
		$("#listadoItems").removeClass("comunes");
		$("#filaFactura").hide();
		$("#filaSolicitud").hide();
	});

	$("#fechaInicio").datepicker({
		changeMonth: true,
		changeYear: true,
		maxDate: "0",
		onSelect: function(dateText, inst) {
			var fecha = new Date($('#fechaInicio').datepicker('getDate'));
			fecha.setDate(fecha.getDate() + 180);
			$('#fechaFin').datepicker('option', 'minDate', $("#fechaInicio").val());
			$('#fechaFin').datepicker('option', 'maxDate', fecha);
		}
	});

	$("#fechaFin").datepicker({
		changeMonth: true,
		changeYear: true,
		maxDate: "0",
		onSelect: function(dateText, inst) {
			var fecha = new Date($('#fechaInicio').datepicker('getDate'));
		}
	});

	$("#tipoSolicitud").change(function(e) {

		if ($("#tipoSolicitud option:selected").val() == "Importación" || $("#tipoSolicitud option:selected").val() == "Fitosanitario") {
			$("#filaSolicitud").show();
			$("#filaFactura").hide();
			$("#numeroFactura").val("");
			$("#numeroOrdenGuia").val("");
		} else {
			if ($("#tipoSolicitud option:selected").val() == "") {
				$("#filaFactura").hide();
				$("#filaSolicitud").hide();
				$("#numeroFactura").val("");
				$("#numeroOrdenGuia").val("");
				$("#numeroSolicitud").val("");
				$("#numeroOrdenGuia").val("");
			} else {
				$("#filaFactura").show();
				$("#filaSolicitud").hide();
				$("#numeroSolicitud").val("");
				$("#numeroOrdenVue").val("");
			}
		}

	});


	$("#_eliminar").click(function() {
		if ($("#cantidadItemsSeleccionados").text() > 1) {
			alert('Por favor seleccione un registro a la vez');
			return false;
		}
	});

	//Cuando se presiona en Filtrar lista, debe cargar los datos
	$("#btnFiltrar").click(function() {
		fn_filtrar();
	});

	// Función para filtrar
	function fn_filtrar() {
		$(".alertaCombo").removeClass("alertaCombo");
		mostrarMensaje("", "EXITO");
		var error = false;

		if (!$.trim($("#fechaInicio").val()) || !esCampoValido("#fechaInicio")) {
			error = true;
			$("#fechaInicio").addClass("alertaCombo");
		}

		if (!$.trim($("#fechaFin").val()) || !esCampoValido("#fechaFin")) {
			error = true;
			$("#fechaFin").addClass("alertaCombo");
		}

		if (!error) {
			$("#paginacion").html("<div id='cargando'>Cargando...</div>");
			$.post("<?php echo URL ?>Financiero/OrdenPago/listarFacturasUsuarios", {
					tipoSolicitud: $("#tipoSolicitud").val(),
					numeroFactura: $("#numeroFactura").val(),
					numeroOrdenGuia: $("#numeroOrdenGuia").val(),
					numeroSolicitud: $("#numeroSolicitud").val(),
					numeroOrdenVue: $("#numeroOrdenVue").val(),
					fechaInicio: $("#fechaInicio").val(),
					fechaFin: $("#fechaFin").val()
				},
				function(data) {
					construirPaginacion($("#paginacion"), JSON.parse(data));
				});
		} else {
			mostrarMensaje("Por favor revise los campos obligatorios.", "FALLO");
		}
	}

	//$("#tablaItems").click(function () {});
</script>