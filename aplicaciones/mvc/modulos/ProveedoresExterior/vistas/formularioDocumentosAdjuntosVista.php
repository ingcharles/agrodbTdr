<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
<form id='formulario'
	data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>ProveedoresExterior'
	data-opcion='documentosadjuntos/guardar' data-destino="detalleItem"
	data-accionEnExito="ACTUALIZAR" method="post">
	<fieldset>
		<legend>DocumentosAdjuntos</legend>

		<div data-linea="1">
			<label for="id_documento_adjunto">id_documento_adjunto </label> <input
				type="text" id="id_documento_adjunto" name="id_documento_adjunto"
				value="<?php echo $this->modeloDocumentosAdjuntos->getIdDocumentoAdjunto(); ?>"
				placeholder="Identificador unico de la tabla" required maxlength="8" />
		</div>

		<div data-linea="2">
			<label for="id_proveedor_exterior">id_proveedor_exterior </label> <input
				type="text" id="id_proveedor_exterior" name="id_proveedor_exterior"
				value="<?php echo $this->modeloDocumentosAdjuntos->getIdProveedorExterior(); ?>"
				placeholder="Identificador unico de la tabla g_proveedores_exterior.proveedor_exterior (llave foranea)"
				required maxlength="8" />
		</div>

		<div data-linea="3">
			<label for="tipo_adjunto">tipo_adjunto </label> <input type="text"
				id="tipo_adjunto" name="tipo_adjunto"
				value="<?php echo $this->modeloDocumentosAdjuntos->getTipoAdjunto(); ?>"
				placeholder="Campo que almacena el tipo de documento adjunto cargado por el operador"
				required maxlength="64" />
		</div>

		<div data-linea="4">
			<label for="ruta_adjunto">ruta_adjunto </label> <input type="text"
				id="ruta_adjunto" name="ruta_adjunto"
				value="<?php echo $this->modeloDocumentosAdjuntos->getRutaAdjunto(); ?>"
				placeholder="Campo que almacena la ruta del documento adjunto cargado por el operador"
				required maxlength="128" />
		</div>

		<div data-linea="5">
			<label for="estado_adjunto">estado_adjunto </label> <input
				type="text" id="estado_adjunto" name="estado_adjunto"
				value="<?php echo $this->modeloDocumentosAdjuntos->getEstadoAdjunto(); ?>"
				placeholder="Estado del documento adjunto cargado por el operador"
				required maxlength="8" />
		</div>

		<div data-linea="6">
			<label for="fecha_creacion_documento_adjunto">fecha_creacion_documento_adjunto
			</label> <input type="text" id="fecha_creacion_documento_adjunto"
				name="fecha_creacion_documento_adjunto"
				value="<?php echo $this->modeloDocumentosAdjuntos->getFechaCreacionDocumentoAdjunto(); ?>"
				placeholder="" required maxlength="8" />
		</div>

		<div data-linea="7">
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
