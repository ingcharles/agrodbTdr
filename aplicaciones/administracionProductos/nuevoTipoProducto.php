<?php 
session_start();
?>

<header>
	<h1>Nuevo tipo de producto</h1>
</header>

<div id="estado"></div>

<form id="nuevoTipoProducto" data-rutaAplicacion="administracionProductos" data-opcion="guardarNuevoTipoProducto" data-destino="detalleItem">
	<fieldset>
		<legend>Detalles del tipo de producto</legend>
		
		<div data-linea="1">
			<label for="area">Área</label>
			<select id="area" name="area">
					<option value="">Seleccione....</option>
					<option value="SA">Sanidad Animal</option>
					<option value="SV">Sanidad Vegetal</option>
					<option value="LT">Laboratorios</option>
					<option value="AI">Inocuidad de los alimentos</option>
					<!-- >option value="IAP">Inocuidad de los alimentos plaguicidas</option>
					<option value="IAV">Inocuidad de los alimentos veterinarios</option-->
			</select>
		</div>
		
		<div data-linea="2">
			<label for="nombreTipo">Nombre</label>
			<input id="nombreTipo" name="nombreTipo" type="text"/>
		</div>
		
	</fieldset>

	<button type="submit" class="guardar">Guardar Tipo Producto</button>
</form>
<script type="text/javascript">

	$("document").ready(function(){
		distribuirLineas();
	});

	$("#nuevoTipoProducto").submit(function(event){

		event.preventDefault();

		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if($("#area").val()==""){
			error = true;
			$("#area").addClass("alertaCombo");
		}

		if($.trim($("#nombreTipo").val())=="" ){
			error = true;
			$("#nombreTipo").addClass("alertaCombo");
		}
		
		if (!error){
			abrir($(this),event,false);
		}else{
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});

	
	
</script>
