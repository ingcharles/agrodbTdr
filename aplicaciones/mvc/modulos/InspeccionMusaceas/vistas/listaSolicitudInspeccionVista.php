<header>
<nav><?php 
	echo $this->panelBusqueda;
	?></nav><br/>
	<nav><?php
	echo $this->crearAccionBotones();
	?></nav>
</header>
<script src="<?php echo URL ?>modulos/InspeccionMusaceas/vistas/js/funcionIM.js"></script>
<div id="paginacion" class="normal"></div>
<table id="tablaItems">
	<thead><tr>
	<th>Num Solicitud</th>
		<th>Exportador</th>
		<th>Estado</th>
		<th>País destino</th>
		<th>Fecha</th>
		<th>Provincia</th>
		</tr></thead>
	<tbody></tbody>
</table>

<script>
	$(document).ready(function () {
		construirPaginacion($("#paginacion"),<?php print_r(json_encode($this->itemsFiltrados, JSON_UNESCAPED_UNICODE)); ?>);
		$("#listadoItems").removeClass("comunes"); 
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');
		$(".alertaCombo").removeClass("alertaCombo");
		$('#estado').html('');
		});

	$("#_eliminar").click(function () {
		if ($("#cantidadItemsSeleccionados").text() > 1) {
			alert('Por favor seleccione un registro a la vez');
			return false;
		}
	});

		// Función para filtrar

		function fn_filtrar() {
			$("#paginacion").html("<div id='cargando'>Cargando...</div>");
			  $.post("<?php echo URL ?>InspeccionMusaceas/solicitudInspeccion/filtrarInformacion",
				    	{
				  numeroSolicitud: $("#numeroSolicitud").val(),
				  estadoSolicitud: $("#estadoSolicitud").val(),
				  fecha: $("#fecha").val()
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

		 $("#fecha").datepicker({
		    	yearRange: "c:c",
		    	changeMonth: false,
		        changeYear: false,
		        dateFormat: 'yy-mm-dd',
		      });
		 $("#fecha").click(function () {
	    	 $(this).val('');
	     });
</script>
