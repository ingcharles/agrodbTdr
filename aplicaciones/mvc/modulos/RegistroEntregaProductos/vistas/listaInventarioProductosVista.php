<header>
	<nav><?php echo $this->panelBusquedaInventario;?></nav>
	<nav><?php echo $this->crearAccionBotones();?></nav>
</header>
<div id="paginacion" class="normal"></div>
<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th>Producto</th>
			<th>Cantidad</th>
			<th>Tipo de Registro</th>
		</tr>
	</thead>
	<tbody></tbody>
</table>

<script>
	$(document).ready(function () {
		construirPaginacion($("#paginacion"),<?php print_r(json_encode($this->itemsFiltrados, JSON_UNESCAPED_UNICODE)); ?>);
		$("#listadoItems").removeClass("comunes");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');
	});

	$("#btnFiltrar").click(function (event) {
		event.preventDefault();
		fn_filtrar();
	});
	
	function fn_filtrar() {
		event.preventDefault();
		fn_limpiar();

		var error = false;

		if (!error) {
			$("#paginacion").html("<div id='cargando'>Cargando...</div>");
			  $.post("<?php echo URL ?>RegistroEntregaProductos/InventarioProductos/listarInventariosFiltrados",
		    	{
				  	idProductoInventario: $("#idProductoInventario").val(),
				  	estadoInventario: $("#estadoInventario").val(),
				  	tipoRegistroInventario: $("#tipoRegistroInventario").val()
		        },
		      	function (data) {
		        	if (data.estado === 'FALLO') {
	                	mostrarMensaje(data.mensaje, "FALLO");
	                } else {
	                	construirPaginacion($("#paginacion"), JSON.parse(data.contenido));
	                }
		        }, 'json');
		} else {
			$("#estado").html();
			mostrarMensaje("Por favor revise los campos obligatorios.", "FALLO");
		}

	}

	function fn_limpiar() {
		$(".alertaCombo").removeClass("alertaCombo");
		$('#estado').html('');
	}
</script>