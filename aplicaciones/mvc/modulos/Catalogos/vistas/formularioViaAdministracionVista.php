<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>Catalogos' data-opcion='viaAdministracion/guardar' data-destino="detalleItem" method="post">
	<input type="hidden" id="id_via_administracion" name="id_via_administracion" value="<?php echo $this->modeloViaAdministracion->getIdViaAdministracion(); ?>" />

	<fieldset>
		<legend>Vía de Administración</legend>				

		<div data-linea="1">
			<label for="via_administracion">Vía de Administración: </label>
			<input type="text" id="via_administracion" name="via_administracion" value="<?php echo $this->modeloViaAdministracion->getViaAdministracion(); ?>" required maxlength="128" />
		</div>				

		<div data-linea="2">
			<label for="estado_via_administracion">Estado: </label>
			<select id="estado_via_administracion" name="estado_via_administracion" required>
                <?php
                    echo $this->comboActivoInactivo($this->modeloViaAdministracion->getEstadoViaAdministracion());
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