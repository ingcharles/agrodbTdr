<?php 
	session_start();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>
	<header>
		<h1>Tipos de Requerimiento</h1>
	</header>
	<div id="estado"></div>
	<form id="nuevoSeguimiento" data-rutaAplicacion="serviciosInformacionTecnica" data-opcion="guardarRequerimientoSAA" data-destino="detalleItem">
		<input type="hidden" id="usuarioResponsable" name="usuarioResponsable" value="<?php echo $_SESSION['usuario'] ?>" /> 
		<fieldset>
			<legend>Tipo de Requerimiento</legend>	
				<div data-linea="1">
					<label>Nombre:</label> 
						<input type="text" id="nombreRequerimiento" name="nombreRequerimiento" maxlength="512" /> 
				</div>
				<div data-linea="2">
					<label>Descripción:</label> 
				</div>
				<div data-linea="3">
					<textarea rows="4" cols="50" id="descripcion" name="descripcion" maxlength="512" ></textarea>
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

	$("#nuevoSeguimiento").submit(function(event){
		event.preventDefault();
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;
		
		if(!$.trim($("#nombreRequerimiento").val())){
			error = true;
			$("#nombreRequerimiento").addClass("alertaCombo");
			$("#estado").html('Por favor ingrese el nombre del tipo de requerimiento.').addClass("alerta");
		}
	
		if (!error){
			abrir($("#nuevoSeguimiento"),event,false);
		}
	});
</script>