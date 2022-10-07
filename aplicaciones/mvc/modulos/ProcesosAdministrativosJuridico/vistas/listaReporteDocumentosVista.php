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
	$("#listadoItems").removeClass("comunes"); });
    $("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');
	

	$("#btnFiltrar").click(function (event) {
		$(".alertaCombo").removeClass("alertaCombo");
		$('#estado').html('');
		var error= false;	
		event.preventDefault();
		if($("#numero_proceso").val() == ''){
			error= true;
			$("#numero_proceso").addClass("alertaCombo");
		}
		if($("#area_tecnica").val() == ''){
			error= true;
			$("#area_tecnica").addClass("alertaCombo");
		}
		if($("#fecha_creacion").val() == ''){
			error= true;
			$("#fecha_creacion").addClass("alertaCombo");
		}
		if($("#provinciab").val() == ''){
			error= true;
			$("#provinciab").addClass("alertaCombo");
		}
		if(!error){
			fn_filtrar()
			
		}else{
			mostrarMensaje("Revisar los campos obligatorios...!!", "FALLO");
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
		  $.post("<?php echo URL ?>ProcesosAdministrativosJuridico/procesoAdministrativo/filtrarInformacionReporte",
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
