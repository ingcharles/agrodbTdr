<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>Catalogos' data-opcion='DeclaracionVenta/guardar' data-destino="detalleItem" method="post">
	<input type="hidden" id="id_declaracion_venta" name="id_declaracion_venta" value="<?php echo $this->modeloDeclaracionVenta->getIdDeclaracionVenta(); ?>" />
	
	<fieldset>
		<legend>Declaración de Venta</legend>				

		<div data-linea="1">
			<label for="declaracion_venta">Declaración de Venta </label>
			<input type="text" id="declaracion_venta" name="declaracion_venta" value="<?php echo $this->modeloDeclaracionVenta->getDeclaracionVenta(); ?>" />
		</div>				

		<div data-linea="2">
			<label for="estado_declaracion_venta">Estado: </label>
			<select id="estado_declaracion_venta" name="estado_declaracion_venta" required>
                <?php
                    echo $this->comboActivoInactivo($this->modeloDeclaracionVenta->getEstadoDeclaracionVenta());
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