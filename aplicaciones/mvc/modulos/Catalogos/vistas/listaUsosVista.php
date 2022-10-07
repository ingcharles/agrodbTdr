<header>
	<nav><?php echo $this->panelBusquedaUsos;?></nav> <br/>
	<nav><?php echo $this->listaBotones;?></nav>
</header>

<div id="paginacion" class="normal"></div>

<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th>Área</th>
			<th>Nombre científico / Uso pecuario</th>
			<th>Nombre común</th>
			<th>Estado</th>
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

		var error = false;
		
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');
		$("#paginacion").html("<div id='cargando'>Cargando...</div>");

		if (!error) {
			$("#paginacion").html("<div id='cargando'>Cargando...</div>");
			  $.post("<?php echo URL ?>Catalogos/Usos/listarUsosFiltrados",
		    	{
				  	idArea: $("#idArea").val(),
				  	estadoIA: $("#estadoIA").val()
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

		
	    /*$.post("< ?php echo URL ?>Catalogos/Usos/actualizarUsos",
	      	function (data) {
	            construirPaginacion($("#paginacion"), JSON.parse(data));
	        });*/
	}
</script>