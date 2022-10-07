<?php 
session_start();
?>

<header>
	<h1>Tipo de Compra</h1>
</header>

<div id="estado"></div>

<form id="nuevoTipoCompra" data-rutaAplicacion="programacionAnualPresupuestaria" data-opcion="guardarNuevoTipoCompra" data-destino="detalleItem">
	<fieldset>
		<legend>Tipo de Compra</legend>
		
		<div data-linea="1">
			<label>Nombre:</label>
			<input type="text" id="nombreTipoCompra" name="nombreTipoCompra" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"/>
		</div>
		
	</fieldset>

	<button type="submit" class="guardar">Guardar</button>
</form>

<script type="text/javascript">

	$("document").ready(function(){
		distribuirLineas();
		construirValidador();
	});

	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}

	$("#nuevoTipoCompra").submit(function(event){

		event.preventDefault();

		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#nombreTipoCompra").val()) || !esCampoValido("#nombreTipoCompra")){
			error = true;
			$("#nombreTipoCompra").addClass("alertaCombo");
		}
		
		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			abrir($(this),event,false);
		}
	});
	
</script>