<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>Catalogos' data-opcion='formulacion/guardar' data-destino="detalleItem" method="post">
	<input type="hidden" id="id_formulacion" name="id_formulacion" value="<?php echo $this->modeloFormulacion->getIdFormulacion(); ?>" />
	
	<fieldset>
		<legend>Forma Física, Farmacéutica, Cosmética (Formulación)</legend>				

		<div data-linea="1">
			<label for="formulacion">Forma Física, Farmacéutica, Cosmética: </label>
			<input type="text" id="formulacion" name="formulacion" value="<?php echo $this->modeloFormulacion->getFormulacion(); ?>" required maxlength="1024" />
		</div>				

		<div data-linea="2">
			<label for="id_area">Área: </label>
			<select id="id_area" name="id_area" required>
                <?php
                echo $this->comboAreasRegistroInsumosAgropecuarios($this->modeloFormulacion->getIdArea());
                ?>
            </select>
		</div>				

		<div data-linea="3">
			<label for="estado_formulacion">Estado: </label>
			<select id="estado_formulacion" name="estado_formulacion" required>
                <?php
                    echo $this->comboActivoInactivo($this->modeloFormulacion->getEstadoFormulacion());
                ?>
            </select>
		</div>

		<div data-linea="4">
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
			var respuesta = JSON.parse(ejecutarJson($(this)).responseText);
	       	if (respuesta.estado == 'exito'){
	            fn_filtrar();
	       	}
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});
</script>