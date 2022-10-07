<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
<form id='formulario'
	data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>JornadaLaboral' data-opcion='AsistenciaEmpleado/guardar' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR" method="post">
	<fieldset>
		<legend>Registro asistencia</legend>

		<label for="tipo_registro">Tipo de marcación:</label>
		<hr />
		<div data-linea="10" id="datos_registro">
			<div data-linea="1">
				<label>
					<input type="radio" id="tr1" name="tipo_registro" value="1" /> Ingreso de la jornada laboral
				</label>
			</div>
			<div data-linea="2">
				<label>
					<input type="radio" id="tr2" name="tipo_registro" value="2" /> Inicio del receso
				</label>
			</div>
			<div data-linea="3">
				<label>
					<input type="radio" id="tr3" name="tipo_registro" value="3" /> Fin del receso
				</label>
			</div>
			<div data-linea="4">
				<label>
					<input type="radio" id="tr4" name="tipo_registro" value="4" /> Fin de la jornada laboral
				</label>
			</div>
		</div>
		<hr />
		<div data-linea="7">
			<button type="submit" class="guardar">Guardar</button>
		</div>
	</fieldset>
</form>
<script type="text/javascript">
	$(document).ready(function() {
		distribuirLineas();
		mostrarMensaje("","EXITO");
	 });

	$("#formulario").submit(function (event) {
		event.preventDefault();
		var error = false;

		$(".alertaCombo").removeClass("alertaCombo");

		if($('input:radio[name=tipo_registro]:checked').length==""){
			error = true;
			$("#datos_registro").addClass("alertaCombo");
		}
		
		if (!error) {
			JSON.parse(ejecutarJson($("#formulario")).responseText);
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});
</script>
