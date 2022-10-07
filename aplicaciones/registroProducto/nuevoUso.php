<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorRequisitos.php';
	require_once '../../clases/ControladorCatalogos.php';
	
	$conexion = new Conexion();
	$cr = new ControladorRequisitos();	
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>
	<header>
		<h1>Nuevo uso</h1>
	</header>

	<div id="estado"></div>
		<form id="nuevoUso" data-rutaAplicacion="registroProducto" data-opcion="guardarNuevoUsoInocuidad" data-accionEnExito = 'ACTUALIZAR'>
					
			<fieldset>
				<legend>Uso</legend>	
				
				<div data-linea="1">
					<label for="area">Área</label>
						<select id="area" name="area">
								<option value="" selected="selected">Seleccione una dirección...</option>
								<option value="IAP">Registro de insumos agrícolas</option>
								<!-- option value="IAV">Registro de insumos pecuarios</option-->
								<option value="IAF">Registro de insumos fertilizantes</option>
								<option value="IAPA">Registro de insumos para plantas de autoconsumo</option>
						</select>
				</div>
				
				<label id= lNombreComun>Plaga Nombre común</label>					
				<div data-linea="3">
						<textarea id="nombreComun" name="nombreComun" placeholder="Ej: Nombre común..." rows="2"></textarea>
				</div>
				
				<label id= lNombreCientifico>Plaga Nombre científico</label>					
				<div data-linea="2">
						<textarea id="nombreCientifico" name="nombreCientifico" placeholder="Ej: Nombre científico..." rows="2"></textarea>
				</div>
				
			</fieldset>
			
			<div>
				<button type="submit" class="guardar">Guardar</button>
			</div>
			
		</form>
</body>
<script>
	$('document').ready(function(){
		distribuirLineas();				
	});
	
	$("#nuevoUso").submit(function(event){

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
</html>