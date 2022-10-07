<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>RegistroControlDocumentos' data-opcion='detalleregistrosgc/guardar' data-destino="detalleItem" data-accionEnExito ="ACTUALIZAR" method="post">
	<fieldset>
		<legend>DetalleRegistroSgc</legend>				

		<div data-linea="1">
			<label for="id_detalle_registro_sgc">id_detalle_registro_sgc </label>
			<input type="text" id="id_detalle_registro_sgc" name="id_detalle_registro_sgc" value="<?php echo $this->modeloDetalleRegistroSgc->getIdDetalleRegistroSgc(); ?>"
			placeholder="Llave primaria de la tabla" required maxlength="8" />
		</div>				

		<div data-linea="2">
			<label for="id_registro_sgc">id_registro_sgc </label>
			<input type="text" id="id_registro_sgc" name="id_registro_sgc" value="<?php echo $this->modeloDetalleRegistroSgc->getIdRegistroSgc(); ?>"
			placeholder="LLave foránea de la tabla registro SGC" required maxlength="8" />
		</div>				

		<div data-linea="3">
			<label for="numedo_glpi">numedo_glpi </label>
			<input type="text" id="numedo_glpi" name="numedo_glpi" value="<?php echo $this->modeloDetalleRegistroSgc->getNumedoGlpi(); ?>"
			placeholder="Número de GLPI" required maxlength="12" />
		</div>				

		<div data-linea="4">
			<label for="asunto">asunto </label>
			<input type="text" id="asunto" name="asunto" value="<?php echo $this->modeloDetalleRegistroSgc->getAsunto(); ?>"
			placeholder="Asunto" required maxlength="128" />
		</div>				

		<div data-linea="5">
			<label for="fecha">fecha </label>
			<input type="text" id="fecha" name="fecha" value="<?php echo $this->modeloDetalleRegistroSgc->getFecha(); ?>"
			placeholder="Fecha" required maxlength="8" />
		</div>				

		<div data-linea="6">
			<label for="estado">estado </label>
			<input type="text" id="estado" name="estado" value="<?php echo $this->modeloDetalleRegistroSgc->getEstado(); ?>"
			placeholder="Estado del registro" required maxlength="12" />
		</div>				

		<div data-linea="7">
			<label for="fecha_creacion">fecha_creacion </label>
			<input type="text" id="fecha_creacion" name="fecha_creacion" value="<?php echo $this->modeloDetalleRegistroSgc->getFechaCreacion(); ?>"
			placeholder="Fecha de creación del registro" required maxlength="8" />
		</div>

		<div data-linea="8">
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
