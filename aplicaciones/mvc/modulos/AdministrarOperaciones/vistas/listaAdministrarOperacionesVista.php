<header>
	<nav><?php echo $this->panelBusqueda; ?></nav>
</header>

<div id="paginacion" class="normal"></div>

<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th>Código</th>
			<th>Provincia</th>
			<th>Operación</th>
			<th>Área</th>
			<th>Estado</th>
		</tr>
	</thead>
	<tbody></tbody>
</table>

<script>
	var perfil=<?php echo json_encode($this->perfilUsuario);?>;
	var area =<?php echo json_encode($this->area);?>;
	
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
		$(".alertaCombo").removeClass("alertaCombo");
		event.preventDefault();
		var error = false;

		if($("#identificadorFiltro").val() == ''  &&  ($("#identificadorFiltro").val().length > 13 || $("#identificadorFiltro").val().length <= 0)){
			$("#identificadorFiltro").addClass("alertaCombo");

			if ($("#razonSocialFiltro").val() == '' ){
    			$("#razonSocialFiltro").addClass("alertaCombo");
    			error = true;
    		}
		}

		if ($("#provinciaFiltro").val() == '' ){
			$("#provinciaFiltro").addClass("alertaCombo");
			error = true;
		}

		/*if ($("#tipoOperacionFiltro").val() == '' ){
			$("#tipoOperacionFiltro").addClass("alertaCombo");
			error = true;
		}*/

		/*if ($("#estadoFiltro").val() == '' ){
			$("#estadoFiltro").addClass("alertaCombo");
			error = true;
		}*/
	        
		//$("#paginacion").html("<div id='cargando'>Cargando...</div>");

		if (!error) {
			$("#paginacion").html("<div id='cargando'>Cargando...</div>");
			  $.post("<?php echo URL ?>AdministrarOperaciones/AdministrarOperaciones/filtrarOperacionesOperador",
		    	{
				  	identificadorOperador : $("#identificadorFiltro").val(),
		    		razonSocial : $("#razonSocialFiltro").val(),
		    		provincia : $("#provinciaFiltro option:selected").text(),
		    		area : area,
		    		tipoOperacion : $("#tipoOperacionFiltro option:selected").val()
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
	
</script>