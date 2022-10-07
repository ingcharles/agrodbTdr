<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>ProcesosAdministrativosJuridico' data-opcion='tipodocumento/guardar' data-destino="detalleItem" data-accionEnExito ="ACTUALIZAR" method="post">
	<fieldset>
		<legend>TipoDocumento</legend>				

		<div data-linea="1">
			<label for="id_tipo_documento">id_tipo_documento </label>
			<input type="text" id="id_tipo_documento" name="id_tipo_documento" value="<?php echo $this->modeloTipoDocumento->getIdTipoDocumento(); ?>"
			placeholder="Llave primaria de la tabla" required maxlength="8" />
		</div>				

		<div data-linea="2">
			<label for="id_proceso_administrativo">id_proceso_administrativo </label>
			<input type="text" id="id_proceso_administrativo" name="id_proceso_administrativo" value="<?php echo $this->modeloTipoDocumento->getIdProcesoAdministrativo(); ?>"
			placeholder="Llave foránea de la tabla proceso_administrativo" required maxlength="8" />
		</div>				

		<div data-linea="3">
			<label for="fecha_creacion">fecha_creacion </label>
			<input type="text" id="fecha_creacion" name="fecha_creacion" value="<?php echo date('j/n/Y',strtotime($this->modeloTipoDocumento->getFechaCreacion())) ; ?>"
			placeholder="Fecha de creación del registro" required maxlength="8" />
		</div>				

		<div data-linea="4">
			<label for="estado">estado </label>
			<input type="text" id="estado" name="estado" value="<?php echo $this->modeloTipoDocumento->getEstado(); ?>"
			placeholder="Estado del registro" required maxlength="12" />
		</div>				

		<div data-linea="5">
			<label for="id_modelo_administrativo">id_modelo_administrativo </label>
			<input type="text" id="id_modelo_administrativo" name="id_modelo_administrativo" value="<?php echo $this->modeloTipoDocumento->getIdModeloAdministrativo(); ?>"
			placeholder="Llave foránea de la tabla modelo_administrativo" required maxlength="8" />
		</div>				

		<div data-linea="6">
			<label for="ruta_documento">ruta_documento </label>
			<input type="text" id="ruta_documento" name="ruta_documento" value="<?php echo $this->modeloTipoDocumento->getRutaDocumento(); ?>"
			placeholder="Ruta del archivo adjunto" required maxlength="1024" />
		</div>				

		<div data-linea="7">
			<label for="identificador_registro">identificador_registro </label>
			<input type="text" id="identificador_registro" name="identificador_registro" value="<?php echo $this->modeloTipoDocumento->getIdentificadorRegistro(); ?>"
			placeholder="Identificador de quien realizo el registro" required maxlength="13" />
		</div>

		<div data-linea="8">
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
