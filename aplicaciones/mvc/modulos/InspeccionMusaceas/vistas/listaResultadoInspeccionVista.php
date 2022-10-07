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
var validarOperador = <?php echo json_encode($this->validarOperador);?>;
var estado = <?php echo json_encode($this->estado);?>;
	$(document).ready(function () {
		construirPaginacion($("#paginacion"),<?php print_r(json_encode($this->itemsFiltrados, JSON_UNESCAPED_UNICODE)); ?>);
		$("#listadoItems").removeClass("comunes"); 
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');
		if(validarOperador == 'error'){
			mostrarMensaje("Debe actualizar sus datos en: Datos operador -> Actualizar mis datos..!", "FALLO");
		}
		if(validarOperador == 'errorProvincia'){
			mostrarMensaje("Error al validar la provincia, actualizar sus datos, cerrar la sessión en GUIA y volver a ingresar..!", "FALLO");
		}
		});

	 $("#fecha").datepicker({
	    	yearRange: "c:c",
	    	changeMonth: false,
	        changeYear: false,
	        dateFormat: 'yy-mm-dd',
	      });
		// Función para filtrar
     $("#fecha").click(function () {
    	 $(this).val('');
     });
	function fn_filtrar() {
		$("#paginacion").html("<div id='cargando'>Cargando...</div>");
		  $.post("<?php echo URL ?>InspeccionMusaceas/resultadoInspeccion/filtrarInfoAtender",
			    	{
			  numeroSolicitud: $("#numeroSolicitud").val(),
			  identificador: $("#identificador").val(),
			  fecha: $("#fecha").val(),
			  estadoSolicitud: $("#estadoSolicitud").val(),
			  estado:estado
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
