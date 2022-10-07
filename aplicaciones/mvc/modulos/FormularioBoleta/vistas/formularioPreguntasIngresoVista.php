<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>FormularioBoleta' data-opcion='preguntasingreso/guardar' data-destino="detalleItem" data-accionEnExito ="ACTUALIZAR" method="post">
	<fieldset>
		<legend>PreguntasIngreso</legend>				

		<div data-linea="1">
			<label for="id_preguntas_ingreso">id_preguntas_ingreso </label>
			<input type="text" id="id_preguntas_ingreso" name="id_preguntas_ingreso" value="<?php echo $this->modeloPreguntasIngreso->getIdPreguntasIngreso(); ?>"
			placeholder="Llave primaria de la tabla" required maxlength="8" />
		</div>				

		<div data-linea="2">
			<label for="pregunta">pregunta </label>
			<input type="text" id="pregunta" name="pregunta" value="<?php echo $this->modeloPreguntasIngreso->getPregunta(); ?>"
			placeholder="Preguntas" required maxlength="100" />
		</div>				

		<div data-linea="3">
			<label for="estado">estado </label>
			<input type="text" id="estado" name="estado" value="<?php echo $this->modeloPreguntasIngreso->getEstado(); ?>"
			placeholder="Estado del registro" required maxlength="12" />
		</div>				

		<div data-linea="4">
			<label for="fecha_creacion">fecha_creacion </label>
			<input type="text" id="fecha_creacion" name="fecha_creacion" value="<?php echo $this->modeloPreguntasIngreso->getFechaCreacion(); ?>"
			placeholder="Fecha de creación del registro" required maxlength="8" />
		</div>

		<div data-linea="5">
			<button type="submit" class="guardar">Guardar</button>
		</div>
	</fieldset >
</form >
<script type ="text/javascript">
	$(document).ready(function() {
		construirValidador();
		distribuirLineas();
	 });

	$("#formulario").submit(function (event) {
		event.preventDefault();
		var error = false;
		if (!error) {
			abrir($(this), event, false);
			abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),"#listadoItems",true);
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});
</script>
