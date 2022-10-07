<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>AdministrarOperacionesGuia' data-opcion='productosareasoperacion/guardar' data-destino="detalleItem" data-accionEnExito ="ACTUALIZAR" method="post">
	<fieldset>
		<legend>ProductosAreasOperacion</legend>				

		<div data-linea="1">
			<label for="id_producto_area_operacion">id_producto_area_operacion </label>
			<input type="text" id="id_producto_area_operacion" name="id_producto_area_operacion" value="<?php echo $this->modeloProductosAreasOperacion->getIdProductoAreaOperacion(); ?>"
			placeholder="" required maxlength="8" />
		</div>				

		<div data-linea="2">
			<label for="id_area">id_area </label>
			<input type="text" id="id_area" name="id_area" value="<?php echo $this->modeloProductosAreasOperacion->getIdArea(); ?>"
			placeholder="" required maxlength="8" />
		</div>				

		<div data-linea="3">
			<label for="id_operacion">id_operacion </label>
			<input type="text" id="id_operacion" name="id_operacion" value="<?php echo $this->modeloProductosAreasOperacion->getIdOperacion(); ?>"
			placeholder="" required maxlength="8" />
		</div>				

		<div data-linea="4">
			<label for="estado">estado </label>
			<input type="text" id="estado" name="estado" value="<?php echo $this->modeloProductosAreasOperacion->getEstado(); ?>"
			placeholder="" required maxlength="32" />
		</div>				

		<div data-linea="5">
			<label for="observacion">observacion </label>
			<input type="text" id="observacion" name="observacion" value="<?php echo $this->modeloProductosAreasOperacion->getObservacion(); ?>"
			placeholder="" required maxlength="512" />
		</div>				

		<div data-linea="6">
			<label for="ruta_archivo">ruta_archivo </label>
			<input type="text" id="ruta_archivo" name="ruta_archivo" value="<?php echo $this->modeloProductosAreasOperacion->getRutaArchivo(); ?>"
			placeholder="" required maxlength="256" />
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
