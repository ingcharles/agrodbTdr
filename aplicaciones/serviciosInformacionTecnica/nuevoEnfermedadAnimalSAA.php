<?php 
	session_start();
	$usuarioResponsable=$_SESSION['usuario'];
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>
	<header>
		<h1>Enfermedades Animales</h1>
	</header>
	<div id="estado"></div>
	
	<form id="nuevoEnfermedadesAnimales" data-rutaAplicacion="serviciosInformacionTecnica" data-opcion="guardarEnfermedadAnimalSAA" data-destino="detalleItem">
		<input type="hidden" id="usuarioResponsable" name="usuarioResponsable" value="<?php echo $usuarioResponsable;?>" />
		<fieldset>
			<legend>Información de Enfermedades Animales</legend>	
			
			
				<div data-linea="1">
					<label>Nombre:</label> 
						<input type="text" id="nombreEnfermedad" name="nombreEnfermedad" maxlength="512" /> 
				</div>
			
				<div data-linea="2">
					<label>Descripción:</label>
				</div>
				
				<div data-linea="3">
					<textarea rows="4" cols="50" id="descripcion" name="descripcion" maxlength="512" ></textarea>
				</div>
				
				<div data-linea="4">
					<label>Observaciones:</label> 
				</div>
				
				<div data-linea="5">
					<textarea rows="4" cols="50" id="observacion" name="observacion" maxlength="512" ></textarea>
				</div>
				
				
		</fieldset>

		<button type="submit" id="btnGuardar"  name="btnGuardar" class="guardar" >Guardar</button>
		
	</form>
</body>
<script type="text/javascript">

	$(document).ready(function(){
		distribuirLineas();
		construirValidador();
	});


	$("#nuevoEnfermedadesAnimales").submit(function(event){
		event.preventDefault();
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#nombreEnfermedad").val())){
			error = true;
			$("#nombreEnfermedad").addClass("alertaCombo");
			$("#estado").html('Por favor ingrese el nombre de la enfermedad.').addClass("alerta");
		}
		
	
		if (!error){
			abrir($("#nuevoEnfermedadesAnimales"),event,false);
		}
	});
</script>