<header>
	<nav><?php echo $this->panelBusquedaUsuario;?></nav>
</header>
<div id="paginacion" class="normal"></div>
<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th>Identificador</th>
			<th>Nombre funcionario</th>
			<th>Mes</th>
		</tr>
	</thead>
	<tbody></tbody>
</table>

<script>
	$(document).ready(function () {
		//construirPaginacion($("#paginacion"),< ?php print_r(json_encode($this->itemsFiltrados, JSON_UNESCAPED_UNICODE)); ?>);
		$("#listadoItems").removeClass("comunes");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');
	});

	//$("#_eliminar").click(function () {
		//if ($("#cantidadItemsSeleccionados").text() > 1) {
			//alert('Por favor seleccione un registro a la vez');
			//return false;
		//}
	//});

	//$("#tablaItems").click(function () {});
	
	$("#btnFiltrar").click(function (event) {
		event.preventDefault();
		fn_filtrar();
	});

	function fn_filtrar() {
		event.preventDefault();
		fn_limpiar();

		var error = false;

		numeroIngreso = 0;
		$('.validacion').each(function(i, obj) {
			if(!$.trim($(this).val())){
				error = true;
				$(this).addClass("alertaCombo");
				numeroIngreso = numeroIngreso + 1;
			}
		});

		if(numeroIngreso <= 4){
			error = false;
			fn_limpiar();
		}
        
		if (!error) {
			$("#paginacion").html("<div id='cargando'>Cargando...</div>");
			  $.post("<?php echo URL ?>JornadaLaboral/JornadaLaboral/listarJornadaLaboralFuncionario",
		    	{
				  	identificador: $("#identificador").val(),
				  	estado: $("#estado_registro").val(),
				  	apellido: $("#apellido").val(),
				    nombre: $("#nombre").val(),
				    area: $("#area").val(),
				    administrador: 'NO'
		        },
		      	function (data) {
		        	if (data.estado === 'FALLO') {
		                mostrarMensaje(data.mensaje, "FALLO");
	                } else {
	                	$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');
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
