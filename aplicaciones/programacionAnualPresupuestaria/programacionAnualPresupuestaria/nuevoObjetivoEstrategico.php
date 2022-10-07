<?php 
session_start();
?>

<header>
	<h1>Objetivo Estratégico</h1>
</header>

<div id="estado"></div>

<form id="nuevoObjetivoEstrategico" data-rutaAplicacion="programacionAnualPresupuestaria" data-opcion="guardarNuevoObjetivoEstrategico" data-destino="detalleItem">
	<fieldset>
		<legend>Objetivo Estratégico</legend>
		
		<div data-linea="1">
			<label>Nombre:</label>
			<input type="text" id="nombreObjetivoEstrategico" name="nombreObjetivoEstrategico" maxlength="1024" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü,. ]+$"/>
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

	$("#nuevoObjetivoEstrategico").submit(function(event){

		event.preventDefault();

		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#nombreObjetivoEstrategico").val()) || !esCampoValido("#nombreObjetivoEstrategico")){
			error = true;
			$("#nombreObjetivoEstrategico").addClass("alertaCombo");
		}
		
		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			abrir($(this),event,false);
		}
	});
	
</script>