<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>EmisionCertificacionOrigen' data-opcion='subproductos/guardar' data-destino="detalleItem" data-accionEnExito ="ACTUALIZAR" method="post">
	<fieldset>
		<legend>Subproductos</legend>				

		<div data-linea="1">
			<label for="id_subproductos">id_subproductos </label>
			<input type="text" id="id_subproductos" name="id_subproductos" value="<?php echo $this->modeloSubproductos->getIdSubproductos(); ?>"
			placeholder="LLave primaria de la tabla" required maxlength="8" />
		</div>				

		<div data-linea="2">
			<label for="id_productos">id_productos </label>
			<input type="text" id="id_productos" name="id_productos" value="<?php echo $this->modeloSubproductos->getIdProductos(); ?>"
			placeholder="Llave foránea de la tabla productos" required maxlength="8" />
		</div>				

		<div data-linea="3">
			<label for="subproducto">subproducto </label>
			<input type="text" id="subproducto" name="subproducto" value="<?php echo $this->modeloSubproductos->getSubproducto(); ?>"
			placeholder="Subproducto agregado" required maxlength="64" />
		</div>				

		<div data-linea="4">
			<label for="cantidad">cantidad </label>
			<input type="text" id="cantidad" name="cantidad" value="<?php echo $this->modeloSubproductos->getCantidad(); ?>"
			placeholder="Cantidad de subproductos agregados" required maxlength="8" />
		</div>				

		<div data-linea="5">
			<label for="fecha_creacion">fecha_creacion </label>
			<input type="text" id="fecha_creacion" name="fecha_creacion" value="<?php echo $this->modeloSubproductos->getFechaCreacion(); ?>"
			placeholder="Fecha de creación del registro" required maxlength="8" />
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
