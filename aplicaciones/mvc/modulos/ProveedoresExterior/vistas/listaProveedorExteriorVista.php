<header>
	<nav><?php echo $this->crearAccionBotones();?></nav>
</header>

<div id="article"><?php echo $this->article; ?></div>

<script>
	$(document).ready(function () {
		$("#listadoItems").addClass("comunes");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí una solicitud para revisarla.</div>');	

		$("#_modificarSolicitud").click(function(){
			if($("#cantidadItemsSeleccionados").text() == 0){
				$("#detalleItem").html('<div class="mensajeInicial">Seleccione una solicitud en estado aprobada y presione el botón Modificar.</div>');	
				return false;
			}else if($("#cantidadItemsSeleccionados").text() > 1){
				$("#detalleItem").html('<div class="mensajeInicial">Por favor seleccione una solicitud a la vez.</div>');	
				return false;
			}
		});			
	});
</script>