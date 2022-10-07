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
		<h1>Zonas</h1>
	</header>
	<div id="estado"></div>
	<form id="nuevoZonasPaises" data-rutaAplicacion="serviciosInformacionTecnica" data-opcion="guardarZonaSAA" data-destino="detalleItem">
		<input type="hidden" id="usuarioResponsable" name="usuarioResponsable" value="<?php echo $usuarioResponsable;?>" />
		<fieldset>
			<legend>Información de Zona</legend>	
				<div data-linea="1">
					<label>Nombre:</label> 
					<input type="text" id="nombreZona" name="nombreZona" maxlength="512" /> 
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

	$("#nuevoZonasPaises").submit(function(event){
		event.preventDefault();
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#nombreZona").val())){
			error = true;
			$("#nombreZona").addClass("alertaCombo");
			$("#estado").html('Por favor ingrese el nombre de la zona.').addClass("alerta");
		}
	
		if (!error){
			abrir($("#nuevoZonasPaises"),event,false);
		}
	});
</script>