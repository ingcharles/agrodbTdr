<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>PlagasLaboratorio' data-opcion='cultivos/guardar' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
	<fieldset>
		<legend>Cultivo</legend>

		<div data-linea="2">
			<label for="nombre_comun">Nombre común</label> 
			<input type="text" id="nombre_comun" name="nombre_comun" placeholder="Nombre común del cultivo" maxlength="256" class="validacion"/>
		</div>

		<div data-linea="3">
			<label for="nombre_cientifico">Nombre científico</label> 
			<input type="text" id="nombre_cientifico" name="nombre_cientifico" placeholder="Nombre científico del cultivo"  maxlength="256" class="validacion"/>
		</div>

	</fieldset>
	
	<button type="submit" class="guardar">Guardar</button>
	
</form>
<script type="text/javascript">
	$(document).ready(function() {
		construirValidador();
		distribuirLineas();
		mostrarMensaje("","EXITO");
	 });

	$("#formulario").submit(function (event) {
		event.preventDefault();
		var error = false;
		$(".alertaCombo").removeClass("alertaCombo");

		$('.validacion').each(function(i, obj) {
			if(!$.trim($(this).val())){
				error = true;
				$(this).addClass("alertaCombo");
			}
		});

		if (!error) {
				JSON.parse(ejecutarJson($("#formulario")).responseText);
				filtrarFormulario('refrescar');
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});
</script>
