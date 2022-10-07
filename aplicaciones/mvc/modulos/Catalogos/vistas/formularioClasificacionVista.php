<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>Catalogos' data-opcion='clasificacion/guardar' data-destino="detalleItem" method="post">
	<input type="hidden" id="id_clasificacion" name="id_clasificacion" value="<?php echo $this->modeloClasificacion->getIdClasificacion(); ?>"/>
				
	<fieldset>
		<legend>Clasificación</legend>

		<div data-linea="1">
			<label for="clasificacion">Clasificación: </label> 
			<input type="text" id="clasificacion" name="clasificacion" value="<?php echo $this->modeloClasificacion->getClasificacion(); ?>" required maxlength="256" />
		</div>

		<div data-linea="2">
			<label for="estado_clasificacion">Estado: </label>
			<select id="estado_clasificacion" name="estado_clasificacion" required>
                <?php
                    echo $this->comboActivoInactivo($this->modeloClasificacion->getEstadoClasificacion());
                ?>
            </select>
		</div>
		
		<div data-linea="3">
			<button type="submit" class="guardar">Guardar</button>
		</div>
	</fieldset>
</form>
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