<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorPAPP.php';
?>

<header>
	<h1>Nuevo ítem presupuestario</h1>
</header>
<form id="nuevoItemPresupuestario" data-rutaAplicacion="poa" data-opcion="guardarNuevoItemPresupuestario" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
	<div id="estado"></div>
	
	<fieldset>
		<legend>Información del item presupuestario</legend>
		
			<div data-linea="1">
				<label>Código:</label>
					<input type="text" name="codigo" id="codigo" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü.#-/°0-9 ]+$"/>
			</div>
			
			<div data-linea="2">
				<label>Descripción:</label>
					<input type="text" id="descripcion" name="descripcion" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü.#-/°0-9 ]+$" />
			</div>
	</fieldset>
	
	<button type="submit" class="guardar">Generar item presupuestario</button>

</form>
<script type="text/javascript">

	$("#nuevoItemPresupuestario").submit(function(event){
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

		if(!$.trim($("#codigo").val()) || !esCampoValido("#codigo")){
			error = true;
			$("#codigo").addClass("alertaCombo");
		}
		
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

	$(document).ready(function(){
		distribuirLineas();
		construirValidador();
	});		
	
</script>