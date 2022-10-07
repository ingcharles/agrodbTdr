<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>Catalogos' data-opcion='efectosBiologicos/guardar' data-destino="detalleItem" method="post">
	<input type="hidden" id="id_efecto_biologico" name="id_efecto_biologico" value="<?php echo $this->modeloEfectosBiologicos->getIdEfectoBiologico(); ?>" />
	
	<fieldset>
		<legend>EfectosBiologicos</legend>				

		<div data-linea="1">
			<label for="efecto_biologico">Efectos Biológicos no deseados: </label>
			<input type="text" id="efecto_biologico" name="efecto_biologico" value="<?php echo $this->modeloEfectosBiologicos->getEfectoBiologico(); ?>" required maxlength="128" />
		</div>				

		<div data-linea="2">
			<label for="estado_efecto_biologico">Estado: </label>
			<select id="estado_efecto_biologico" name="estado_efecto_biologico" required>
                <?php
                    echo $this->comboActivoInactivo($this->modeloEfectosBiologicos->getEstadoEfectoBiologico());
                ?>
            </select>
		</div>

		<div data-linea="3">
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