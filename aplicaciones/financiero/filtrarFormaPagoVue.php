<?php
session_start();
?>

<header>
	<nav>
		<form id="listarOrdenesPagoComercioExterior" data-rutaAplicacion="financiero" data-opcion="listaFormaPagoVue" data-destino="tabla">
			<table class="filtro">
				<tr id="fcliente">
					<th id="lcliente">Número orden VUE:</th>
					<td>
						<input id="numeroOrdenVue" name="numeroOrdenVue" type="text" style="width: 100%;" />
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<button>Filtrar lista</button>
					</td>
				</tr>
			</table>
		</form>
	</nav>
</header>

<div id="tabla"></div>

<script type="text/javascript">


$(document).ready(function(){
	$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un item para visualizar.</div>');	
});

$("#listarOrdenesPagoComercioExterior").submit(function(event){
	event.preventDefault();

	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;
	
	if(!$.trim($("#numeroOrdenVue").val())){
		error = true;
		$("#numeroOrdenVue").addClass("alertaCombo");
	}

	if(!error){
		abrir($(this),event,false);				
	}	
});

</script>

