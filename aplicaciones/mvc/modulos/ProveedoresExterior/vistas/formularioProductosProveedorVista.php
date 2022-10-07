<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
<form id='formulario'
	data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>ProveedoresExterior'
	data-opcion='productosproveedor/guardar' data-destino="detalleItem"
	data-accionEnExito="ACTUALIZAR" method="post">
	<fieldset>
		<legend>ProductosProveedor</legend>

		<div data-linea="1">
			<label for="id_producto_proveedor">id_producto_proveedor </label> <input
				type="text" id="id_producto_proveedor" name="id_producto_proveedor"
				value="<?php echo $this->modeloProductosProveedor->getIdProductoProveedor(); ?>"
				placeholder="Identificador unico de la tabla" required maxlength="8" />
		</div>

		<div data-linea="2">
			<label for="id_proveedor_exterior">id_proveedor_exterior </label> <input
				type="text" id="id_proveedor_exterior" name="id_proveedor_exterior"
				value="<?php echo $this->modeloProductosProveedor->getIdProveedorExterior(); ?>"
				placeholder="Identificador unico de la tabla g_proveedores_exterior.proveedor_exportador, llave foranea"
				required maxlength="8" />
		</div>

		<div data-linea="3">
			<label for="id_tipo_producto">id_tipo_producto </label> <input
				type="text" id="id_tipo_producto" name="id_tipo_producto"
				value="<?php echo $this->modeloProductosProveedor->getIdSubtipoProducto(); ?>"
				placeholder="Identificador unico de la tabla g_catalogos.tipos_producto, llave foranea, producto de IAV"
				required maxlength="8" />
		</div>

		<div data-linea="4">
			<label for="nombre_tipo_producto">nombre_tipo_producto </label> <input
				type="text" id="nombre_tipo_producto" name="nombre_tipo_producto"
				value="<?php echo $this->modeloProductosProveedor->getNombreSubtipoProducto() ?>"
				placeholder="Campo que almacena el nombre del tipo de producto de IAV"
				required maxlength="8" />
		</div>

		<div data-linea="5">
			<label for="fecha_creacion_producto_proveedor">fecha_creacion_producto_proveedor
			</label> <input type="text" id="fecha_creacion_producto_proveedor"
				name="fecha_creacion_producto_proveedor"
				value="<?php echo $this->modeloProductosProveedor->getFechaCreacionProductoProveedor(); ?>"
				placeholder="Fecha en la que se registra el producto del proveedor del exportador"
				required maxlength="8" />
		</div>

		<div data-linea="6">
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
			abrir($(this), event, false);
			abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),"#listadoItems",true);
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});
</script>
