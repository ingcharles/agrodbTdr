<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorPAPP.php';
?>

<header>
	<h1>Nuevo indicador</h1>
</header>
<form id="nuevoIndicador" data-rutaAplicacion="poa" data-opcion="guardarNuevoIndicador" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
	<div id="estado"></div>
	
	<fieldset>
		<legend>Descripción del nuevo indicador</legend>
			<div data-linea="1">
				<input type="text" id="descripcion" name="descripcion" maxlength="512" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
			</div>
	</fieldset>
	
	<button type="submit" class="guardar
">Generar Indicador</button>

</form>
<script type="text/javascript">
	$(document).ready(function(){
		distribuirLineas();
		construirValidador();
	});	

	$("#nuevoIndicador").submit(function(event){
		event.preventDefault();
		chequearCampos(this);  
	});

	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}

	function chequearCampos(form){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#descripcion").val()) || !esCampoValido("#descripcion")){
			error = true;
			$("#descripcion").addClass("alertaCombo");
		}
		
		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			ejecutarJson(form);
		}
	}
	
</script>
