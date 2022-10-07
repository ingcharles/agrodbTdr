<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>InspeccionAntePostMortemCF' data-opcion='formulariopostmortem/guardar' data-destino="detalleItem" data-accionEnExito ="ACTUALIZAR" method="post">
	<fieldset>
		<legend>FormularioPostMortem</legend>				

		<div data-linea="1">
			<label for="id_formulario_post_mortem">id_formulario_post_mortem </label>
			<input type="text" id="id_formulario_post_mortem" name="id_formulario_post_mortem" value="<?php echo $this->modeloFormularioPostMortem->getIdFormularioPostMortem(); ?>"
			placeholder="Llave primaria de la tabla"  maxlength="8" />
		</div>				

		<div data-linea="2">
			<label for="identificador">identificador </label>
			<input type="text" id="identificador" name="identificador" value="<?php echo $this->modeloFormularioPostMortem->getIdentificador(); ?>"
			placeholder="Identificador del registro"  maxlength="13" />
		</div>				

		<div data-linea="3">
			<label for="estado">estado </label>
			<input type="text" id="estado" name="estado" value="<?php echo $this->modeloFormularioPostMortem->getEstado(); ?>"
			placeholder="Estado del registro"  maxlength="13" />
		</div>				

		<div data-linea="4">
			<label for="fecha_creacion">fecha_creacion </label>
			<input type="text" id="fecha_creacion" name="fecha_creacion" value="<?php echo $this->modeloFormularioPostMortem->getFechaCreacion(); ?>"
			placeholder="Fecha de creacion del registro"  maxlength="8" />
		</div>				

		<div data-linea="5">
			<label for="codigo_formulario">codigo_formulario </label>
			<input type="text" id="codigo_formulario" name="codigo_formulario" value="<?php echo $this->modeloFormularioPostMortem->getCodigoFormulario(); ?>"
			placeholder="Código del formulario"  maxlength="32" />
		</div>

		<div data-linea="6">
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
