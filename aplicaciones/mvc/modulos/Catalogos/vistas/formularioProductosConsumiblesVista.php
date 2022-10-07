<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>Catalogos' data-opcion='ProductosConsumibles/guardar' data-destino="detalleItem" method="post">
	<input type="hidden" id="id_producto_consumible" name="id_producto_consumible" value="<?php echo $this->modeloProductosConsumibles->getIdProductoConsumible(); ?>" />

	<fieldset>
		<legend>Productos Consumibles</legend>				

		<div data-linea="1">
			<label for="producto_consumible">Producto Consumible: </label>
			<input type="text" id="producto_consumible" name="producto_consumible" value="<?php echo $this->modeloProductosConsumibles->getProductoConsumible(); ?>" required maxlength="32" />
		</div>				

		<div data-linea="2">
			<label for="estado_producto_consumible">Estado: </label>
			<select id="estado_producto_consumible" name="estado_producto_consumible" required>
                <?php
                    echo $this->comboActivoInactivo($this->modeloProductosConsumibles->getEstadoProductoConsumible());
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