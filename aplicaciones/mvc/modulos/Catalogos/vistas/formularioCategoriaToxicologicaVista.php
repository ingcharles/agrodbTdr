<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>Catalogos' data-opcion='CategoriaToxicologica/guardar' data-destino="detalleItem" method="post">
	<input type="hidden" id="id_categoria_toxicologica" name="id_categoria_toxicologica" value="<?php echo $this->modeloCategoriaToxicologica->getIdCategoriaToxicologica(); ?>" />

	<fieldset>
		<legend>Categoría Toxicológica</legend>				

		<div data-linea="1">
			<label for="categoria_toxicologica">Categoría Toxicológica: </label>
			<input type="text" id="categoria_toxicologica" name="categoria_toxicologica" value="<?php echo $this->modeloCategoriaToxicologica->getCategoriaToxicologica(); ?>" required maxlength="1024" />
		</div>				

		<div data-linea="2">
			<label for="periodo_reingreso">Período de Reingreso: </label>
			<input type="text" id="periodo_reingreso" name="periodo_reingreso" value="<?php echo $this->modeloCategoriaToxicologica->getPeriodoReingreso(); ?>" maxlength="8" />
		</div>				

		<div data-linea="3">
			<label for="id_area">Área: </label>
			<select id="id_area" name="id_area" required>
                <?php
                    echo $this->comboAreasRegistroInsumosAgropecuarios($this->modeloCategoriaToxicologica->getIdArea());
                ?>
            </select>
		</div>				

		<div data-linea="4">
			<label for="estado_categoria_toxicologica">Estado: </label>
			<select id="estado_categoria_toxicologica" name="estado_categoria_toxicologica" required>
                <?php
                    echo $this->comboActivoInactivo($this->modeloCategoriaToxicologica->getEstadoCategoriaToxicologica());
                ?>
            </select>
		</div>

		<div data-linea="5">
			<button type="submit" class="guardar">Guardar</button>
		</div>
	</fieldset >
</form >
<script type="text/javascript">
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