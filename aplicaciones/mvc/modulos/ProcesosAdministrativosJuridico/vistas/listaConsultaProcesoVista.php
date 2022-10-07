<header><nav><?php 
	echo $this->panelBusqueda;
	?></nav><br/>
	<nav><?php
	echo $this->crearAccionBotones();
	?></nav></header>
	<script src="<?php echo URL ?>modulos/ProcesosAdministrativosJuridico/vistas/js/juridico.js"></script>
<div id="paginacion" class="normal"></div>
<table id="tablaItems">
	<thead><tr>
	<th>No. Proceso</th>
		<th>Área Técnica</th>
		<th>Fecha de Creación</th>
		<th>Provincia</th>
		</tr></thead>
	<tbody></tbody>
</table>

<script>
	$(document).ready(function () {
		construirPaginacion($("#paginacion"),<?php print_r(json_encode($this->itemsFiltrados, JSON_UNESCAPED_UNICODE)); ?>);
		$("#listadoItems").removeClass("comunes"); });
	    $("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');
		$("#_eliminar").click(function () {
		if ($("#cantidadItemsSeleccionados").text() > 1) {
			alert('Por favor seleccione un registro a la vez');
			return false;
		}
	});

		$("#btnFiltrar").click(function (event) {
			$(".alertaCombo").removeClass("alertaCombo");
			$('#estado').html('');
				
			event.preventDefault();
			if($("#provinciab").val() != ''){
				fn_filtrar();
			}else{
				$("#provinciab").addClass("alertaCombo");
				mostrarMensaje("Debe seleccionar la provincia...!!", "FALLO");
			}
		});
		// Función para filtrar

		function fn_filtrar() {
			$("#paginacion").html("<div id='cargando'>Cargando...</div>");
			var areaTecnica = $("#area_tecnica option:selected").text();
			if($("#area_tecnica").val() == ''){
				var areaTecnica = '';
			}else{
				var areaTecnica = $("#area_tecnica option:selected").text();
			}
			  $.post("<?php echo URL ?>ProcesosAdministrativosJuridico/procesoAdministrativo/filtrarInformacionConsulta",
				    	{
				  numero_proceso: $("#numero_proceso").val(),
				  area_tecnica: areaTecnica,
				  fecha_creacion: $("#fecha_creacion").val(),
				  provincia: $("#provinciab option:selected").text()
					  
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

</script>
