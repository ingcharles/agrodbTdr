<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>RegistroControlDocumentos' data-opcion='detallesocializacion/guardar' data-destino="detalleItem" data-accionEnExito ="ACTUALIZAR" method="post">
	<fieldset>
		<legend>DetalleSocializacion</legend>				

		<div data-linea="1">
			<label for="id_detalle_socializacion">id_detalle_socializacion </label>
			<input type="text" id="id_detalle_socializacion" name="id_detalle_socializacion" value="<?php echo $this->modeloDetalleSocializacion->getIdDetalleSocializacion(); ?>"
			placeholder="Llave primaria de la tabla" required maxlength="8" />
		</div>				

		<div data-linea="2">
			<label for="id_registro_sgc">id_registro_sgc </label>
			<input type="text" id="id_registro_sgc" name="id_registro_sgc" value="<?php echo $this->modeloDetalleSocializacion->getIdRegistroSgc(); ?>"
			placeholder="Llave foránea de la tabla registro_sgc" required maxlength="8" />
		</div>				

		<div data-linea="3">
			<label for="estado_socializar">estado_socializar </label>
			<input type="text" id="estado_socializar" name="estado_socializar" value="<?php echo $this->modeloDetalleSocializacion->getEstadoSocializar(); ?>"
			placeholder="Estado del registro de socialización" required maxlength="16" />
		</div>				

		<div data-linea="4">
			<label for="fecha_socializacion">fecha_socializacion </label>
			<input type="text" id="fecha_socializacion" name="fecha_socializacion" value="<?php echo $this->modeloDetalleSocializacion->getFechaSocializacion(); ?>"
			placeholder="Fecha socializacion" required maxlength="8" />
		</div>				

		<div data-linea="5">
			<label for="nombre_socializar">nombre_socializar </label>
			<input type="text" id="nombre_socializar" name="nombre_socializar" value="<?php echo $this->modeloDetalleSocializacion->getNombreSocializar(); ?>"
			placeholder="Nombre socializar" required maxlength="64" />
		</div>				

		<div data-linea="6">
			<label for="provincia">provincia </label>
			<input type="text" id="provincia" name="provincia" value="<?php echo $this->modeloDetalleSocializacion->getProvincia(); ?>"
			placeholder="Provincia socializar" required maxlength="128" />
		</div>				

		<div data-linea="7">
			<label for="oficina">oficina </label>
			<input type="text" id="oficina" name="oficina" value="<?php echo $this->modeloDetalleSocializacion->getOficina(); ?>"
			placeholder="Oficina" required maxlength="128" />
		</div>				

		<div data-linea="8">
			<label for="coordinacion">coordinacion </label>
			<input type="text" id="coordinacion" name="coordinacion" value="<?php echo $this->modeloDetalleSocializacion->getCoordinacion(); ?>"
			placeholder="Coordinación" required maxlength="128" />
		</div>				

		<div data-linea="9">
			<label for="direccion">direccion </label>
			<input type="text" id="direccion" name="direccion" value="<?php echo $this->modeloDetalleSocializacion->getDireccion(); ?>"
			placeholder="Dirección" required maxlength="128" />
		</div>				

		<div data-linea="10">
			<label for="documento_socializar">documento_socializar </label>
			<input type="text" id="documento_socializar" name="documento_socializar" value="<?php echo $this->modeloDetalleSocializacion->getDocumentoSocializar(); ?>"
			placeholder="Documento socializar" required maxlength="1024" />
		</div>				

		<div data-linea="11">
			<label for="fecha_creacion">fecha_creacion </label>
			<input type="text" id="fecha_creacion" name="fecha_creacion" value="<?php echo $this->modeloDetalleSocializacion->getFechaCreacion(); ?>"
			placeholder="Fecha de creación del registro" required maxlength="8" />
		</div>				

		<div data-linea="12">
			<label for="estado">estado </label>
			<input type="text" id="estado" name="estado" value="<?php echo $this->modeloDetalleSocializacion->getEstado(); ?>"
			placeholder="Estado del registro" required maxlength="12" />
		</div>

		<div data-linea="13">
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
