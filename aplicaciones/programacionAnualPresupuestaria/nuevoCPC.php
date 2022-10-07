<?php 
	session_start();
	require_once '../../clases/Conexion.php';
?>

<header>
	<h1>Unidad Medida SERCOP</h1>
</header>
<form id="nuevoCPC" data-rutaAplicacion="programacionAnualPresupuestaria" data-opcion="guardarNuevoCPC" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
	<div id="estado"></div>

	<fieldset>
		<legend>CPC</legend>

		<div data-linea="1">
			<label>Nombre:</label>
			<input type="text" id="nombreCPC" name="nombreCPC" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"/>
		</div>
		
		<div data-linea="2">
			<label>Código:</label>
			<input type="text" id="codigoCPC" name="codigoCPC" maxlength="11" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
		</div>
		<div data-linea="2">
			<label>Nivel:</label>
			<input type="text" id="nivelCPC" name="nivelCPC" maxlength="9" data-er="^[0-9]+$" />
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

		if(!$.trim($("#nombreCPC").val()) || !esCampoValido("#nombreCPC")){
			error = true;
			$("#nombreCPC").addClass("alertaCombo");
		}

		if(!$.trim($("#codigoCPC").val()) || !esCampoValido("#codigoCPC")){
			error = true;
			$("#codigoCPC").addClass("alertaCombo");
		}

		if(!$.trim($("#nivelCPC").val()) || !esCampoValido("#nivelCPC")){
			error = true;
			$("#nivelCPC").addClass("alertaCombo");
		}
		
		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			ejecutarJson(form);
			$("#_actualizar").click();
		}
	}

	$("#nuevoCPC").submit(function(event){
		 event.preventDefault();
		 chequearCampos(this);
	});
</script>