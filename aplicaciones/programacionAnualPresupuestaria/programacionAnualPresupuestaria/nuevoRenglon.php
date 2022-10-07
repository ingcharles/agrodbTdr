<?php 
	session_start();
	require_once '../../clases/Conexion.php';
?>

<header>
	<h1>Renglón - Partida Presupuestaria</h1>
</header>
<form id="nuevoRenglon" data-rutaAplicacion="programacionAnualPresupuestaria" data-opcion="guardarNuevoRenglon" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
	<div id="estado"></div>

	<fieldset>
		<legend>Renglón</legend>

		<div data-linea="1">
			<label>Nombre:</label>
			<input type="text" id="nombreRenglon" name="nombreRenglon" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"/>
		</div>
		
		<div data-linea="2">
			<label>Ítem Presupuestario:</label>
			<input type="text" id="codigoRenglon" name="codigoRenglon" maxlength="16" data-er="^[0-9]+$" />
		</div>
	</fieldset>

	<button type="submit" class="guardar">Guardar</button>

</form>
<script type="text/javascript">

	$(document).ready(function(){
		distribuirLineas();
		construirValidador();
	});	

	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}

	function chequearCampos(form){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#nombreRenglon").val()) || !esCampoValido("#nombreRenglon")){
			error = true;
			$("#nombreRenglon").addClass("alertaCombo");
		}

		if(!$.trim($("#codigoRenglon").val()) || !esCampoValido("#codigoRenglon")){
			error = true;
			$("#codigoRenglon").addClass("alertaCombo");
		}
		
		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			ejecutarJson(form);
			$("#_actualizar").click();
		}
	}

	$("#nuevoRenglon").submit(function(event){
		 event.preventDefault();
		 chequearCampos(this);
	});
</script>