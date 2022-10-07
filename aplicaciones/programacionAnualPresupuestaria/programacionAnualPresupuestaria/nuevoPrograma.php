<?php 
session_start();
?>

<header>
	<h1>Programa</h1>
</header>

<div id="estado"></div>

<form id="nuevoPrograma" data-rutaAplicacion="programacionAnualPresupuestaria" data-opcion="guardarNuevoPrograma" data-destino="detalleItem">
	<fieldset>
		<legend>Programa</legend>
		
		<div data-linea="1">
			<label>Nombre:</label>
			<input type="text" id="nombrePrograma" name="nombrePrograma" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"/>
		</div>
		<div data-linea="2">
		<label>Código:</label>
			<input type="text" id="codigoPrograma" name="codigoPrograma" maxlength="2" data-er="^[0-9]+$" />
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

	$("#nuevoPrograma").submit(function(event){

		event.preventDefault();

		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#nombrePrograma").val()) || !esCampoValido("#nombrePrograma")){
			error = true;
			$("#nombrePrograma").addClass("alertaCombo");
		}

		if(!$.trim($("#codigoPrograma").val()) || !esCampoValido("#codigoPrograma")){
			error = true;
			$("#codigoPrograma").addClass("alertaCombo");
		}
		
		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			abrir($(this),event,false);
		}
	});
	
</script>