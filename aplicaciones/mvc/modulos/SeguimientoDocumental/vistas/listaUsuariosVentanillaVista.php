<header>
	<nav><?php echo $this->panelBusquedaUsuariosVentanilla;?></nav>
	<nav><?php echo $this->listaBotones;?></nav>
</header>
<div id="estado"></div>
<div id="paginacion" class="normal"></div>

<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th>Ventanilla</th>
			<th>Funcionario</th>
			<th>Perfil</th>
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

		$("#btnFiltrar").click(function () {
			fn_filtrar();
		});


		function fn_filtrar() {
			$("#paginacion").html("<div id='cargando'>Cargando...</div>");
			$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');
			  $.post("<?php echo URL ?>SeguimientoDocumental/UsuariosVentanilla/listarUsuariosVentanilla",
		    	{
		        	idVentanilla: $("#idVentanilla").val()
		        },
		      	function (data) {
		        	if (data.estado === 'FALLO') {
	                    construirPaginacion($("#paginacion"), JSON.parse(data.contenido));
	                    mostrarMensaje(data.mensaje, "FALLO");
	                } else {
	                	construirPaginacion($("#paginacion"), JSON.parse(data.contenido));
	                }
		        }, 'json');
		}

	
</script>
