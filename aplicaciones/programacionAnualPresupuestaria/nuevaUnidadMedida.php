<?php 
	session_start();
	require_once '../../clases/Conexion.php';
?>

<header>
	<h1>Unidad Medida SERCOP</h1>
</header>
<form id="nuevaUnidadMedida" data-rutaAplicacion="programacionAnualPresupuestaria" data-opcion="guardarNuevaUnidadMedida" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
	<div id="estado"></div>

	<fieldset>
		<legend>Unidad Medida</legend>

		<div data-linea="1">
			<label>Nombre:</label>
			<input type="text" id="nombreUnidadMedida" name="nombreUnidadMedida" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"/>
		</div>
		
		<div data-linea="2">
			<label>Nomenclatura:</label>
			<input type="text" id="codigoUnidadMedida" name="codigoUnidadMedida" maxlength="8" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
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

		if(!$.trim($("#nombreUnidadMedida").val()) || !esCampoValido("#nombreUnidadMedida")){
			error = true;
			$("#nombreUnidadMedida").addClass("alertaCombo");
		}

		if(!$.trim($("#codigoUnidadMedida").val()) || !esCampoValido("#codigoUnidadMedida")){
			error = true;
			$("#codigoUnidadMedida").addClass("alertaCombo");
		}
		
		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			ejecutarJson(form);
			$("#_actualizar").click();
		}
	}

	$("#nuevaUnidadMedida").submit(function(event){
		 event.preventDefault();
		 chequearCampos(this);
	});
</script>