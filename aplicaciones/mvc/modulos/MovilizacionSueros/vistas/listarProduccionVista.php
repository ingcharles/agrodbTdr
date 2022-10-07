<head>
	<link rel="stylesheet" href="<?php echo URL ?>modulos/MovilizacionSueros/vistas/estilos/estiloapp.css"/>
</head>

<header>
<script src="<?php echo URL ?>modulos/MovilizacionSueros/vistas/js/movilizacionSuero.js"></script>
	<nav><?php
	echo $this->panelBusqueda;
	?></nav><br/>
	<nav><?php
	echo $this->crearAccionBotones();
	?></nav>
</header>
<div id="paginacion" class="normal"></div>
<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th>Fecha de Producción</th>
			<th>Cant. Leche(lt)</th>
			<th>Cant. Queso(u)</th>
			<th>Cant. Suero Total(lt)</th>
			<th>Cant. Suero Pendiente(lt)</th>
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

		function fn_filtrar() {
			$("#paginacion").html("<div id='cargando'>Cargando...</div>");
			  $.post("<?php echo URL ?>MovilizacionSueros/Produccion/listarProduccionFiltro",
				    	{
						  	fechaInicio: $("#fecha_inicio").val(),
						  	fechaFin: $("#fecha_fin").val()
		        },
		      	function (data) {
	                	$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');
	                	construirPaginacion($("#paginacion"), JSON.parse(data.contenido));
		        }, 'json');
		}
</script>
