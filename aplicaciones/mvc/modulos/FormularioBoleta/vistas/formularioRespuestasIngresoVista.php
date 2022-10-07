<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>FormularioBoleta' data-opcion='respuestasingreso/guardar' data-destino="detalleItem" data-accionEnExito ="ACTUALIZAR" method="post">
	<fieldset>
		<legend>RespuestasIngreso</legend>				

		<div data-linea="1">
			<label for="id_respuestas_ingreso">id_respuestas_ingreso </label>
			<input type="text" id="id_respuestas_ingreso" name="id_respuestas_ingreso" value="<?php echo $this->modeloRespuestasIngreso->getIdRespuestasIngreso(); ?>"
			placeholder="Llave primaria de tabla" required maxlength="8" />
		</div>				

		<div data-linea="2">
			<label for="id_preguntas_ingreso">id_preguntas_ingreso </label>
			<input type="text" id="id_preguntas_ingreso" name="id_preguntas_ingreso" value="<?php echo $this->modeloRespuestasIngreso->getIdPreguntasIngreso(); ?>"
			placeholder="Llave foránea  de la tabla preguntas_ingreso" required maxlength="8" />
		</div>				

		<div data-linea="3">
			<label for="id_datos_ingreso">id_datos_ingreso </label>
			<input type="text" id="id_datos_ingreso" name="id_datos_ingreso" value="<?php echo $this->modeloRespuestasIngreso->getIdDatosIngreso(); ?>"
			placeholder="Llave foránea de la tabla datos_ingreso" required maxlength="8" />
		</div>				

		<div data-linea="4">
			<label for="respuesta">respuesta </label>
			<input type="text" id="respuesta" name="respuesta" value="<?php echo $this->modeloRespuestasIngreso->getRespuesta(); ?>"
			placeholder="Respuesta de la pregunta" required maxlength="8" />
		</div>				

		<div data-linea="5">
			<label for="num_hombres">num_hombres </label>
			<input type="text" id="num_hombres" name="num_hombres" value="<?php echo $this->modeloRespuestasIngreso->getNumHombres(); ?>"
			placeholder="Número de hombres" required maxlength="8" />
		</div>				

		<div data-linea="6">
			<label for="num_mujeres">num_mujeres </label>
			<input type="text" id="num_mujeres" name="num_mujeres" value="<?php echo $this->modeloRespuestasIngreso->getNumMujeres(); ?>"
			placeholder="Número de mujeres" required maxlength="8" />
		</div>

		<div data-linea="7">
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
