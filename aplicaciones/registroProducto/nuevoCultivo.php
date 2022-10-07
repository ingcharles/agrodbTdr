<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	
	$conexion = new Conexion();	
?>

	<header>
		<h1>Nuevo cultivo</h1>
	</header>

	<div id="estado"></div>
		<form id="nuevoCultivo" data-rutaAplicacion="registroProducto" data-opcion="guardarNuevoCultivo" data-accionEnExito = 'ACTUALIZAR'>
					
			<fieldset>
				<legend>Cultivo</legend>	
				
				<div data-linea="1">
					<label for="area">Área</label>
						<select id="area" name="area" required>
								<option value="" selected="selected">Seleccione una dirección...</option>
								<option value="IAP">Registro de insumos agrícolas</option>
								<option value="IAV">Registro de insumos pecuarios</option>
								<option value="IAF">Registro de insumos fertilizantes</option>
								<option value="IAPA">Registro de insumos para plantas de autoconsumo</option>
						</select>
				</div>
				
				<label id= lNombreComun>Cultivo Nombre común</label>					
				<div data-linea="3">
					<textarea id="nombreComun" name="nombreComun" placeholder="Ej: Nombre común..." rows="2" required="required"></textarea>
				</div>
					
				<label id= lNombreCientifico>Cultivo Nombre científico:</label>					
				<div data-linea="2">
					<textarea id="nombreCientifico" name="nombreCientifico" placeholder="Ej: Nombre científico..." rows="2" required="required"></textarea>
				</div>
				
			</fieldset>
			
			<div>
				<button type="submit" class="guardar">Guardar</button>
			</div>
			
		</form>

<script>
	$('document').ready(function(){
		distribuirLineas();				
	});
	
	$("#nuevoCultivo").submit(function(event){

		event.preventDefault();

		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if($("#area").val()==""){
			error = true;
			$("#area").addClass("alertaCombo");
		}

		if($("#area").val() != 'IAV'){
			if($.trim($("#nombreComun").val())==""){
				error = true;
				$("#nombreComun").addClass("alertaCombo");
			}
		}

		if($.trim($("#nombreCientifico").val())==""){
			error = true;
			$("#nombreCientifico").addClass("alertaCombo");
		}

		if (!error){
			ejecutarJson($(this));
		}else{
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}		
		
	});
	
</script>