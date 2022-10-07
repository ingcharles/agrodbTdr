<header><nav><?php 
	echo $this->panelBusqueda;
	?></nav><br/>
	</header>
<script src="<?php echo URL ?>modulos/ProcesosAdministrativosJuridico/vistas/js/juridico.js"></script>

<form id='formDescarga' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>ProcesosAdministrativosJuridico' data-opcion='procesoAdministrativo/descargaModeloJuridico' data-destino="rutaDescar"  method="post">
<input type="hidden" name="id" id="id">
<div id="rutaDescar"></div>
</form>
<script>
$(document).ready(function () {
	$("#listadoItems").removeClass("comunes"); });
    $("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');
	

	$("#btnFiltrar").click(function (event) {
		$(".alertaCombo").removeClass("alertaCombo");
		$('#estado').html('');
		var error= false;	
		event.preventDefault();
		if($("#fecha_desde").val() == ''){
			error= true;
			$("#fecha_desde").addClass("alertaCombo");
		}
		if($("#fecha_hasta").val() == ''){
			error= true;
			$("#fecha_hasta").addClass("alertaCombo");
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
		if($("#provinciab").val() == ''){
			var provincia = '';
		}else{
			var provincia = $("#provinciab option:selected").text();
		}
		  $.post("<?php echo URL ?>ProcesosAdministrativosJuridico/procesoAdministrativo/filtrarInformacionGeneral",
			    	{
			  fecha_desde: $("#fecha_desde").val(),
			  area_tecnica: areaTecnica,
			  fecha_hasta: $("#fecha_hasta").val(),
			  provincia: provincia
				  
	        },
	      	function (data) {
		      	if(data.estado == 'EXITO'){
                	$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');
                	$("#id").val(data.rutaArch);
    				abrir($("#formDescarga"),event,false);
                	mostrarMensaje('', "EXITO");
		      	}else{
		      		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');
		      		mostrarMensaje(data.mensaje, "FALLO");
		      	}
	        }, 'json');
	}
</script>
