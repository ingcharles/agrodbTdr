<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>NotificacionesFitosanitarias' data-opcion='notificacionporpaisafectado/guardar' data-destino="detalleItem" data-accionEnExito ="ACTUALIZAR" method="post">
	<fieldset>
		<legend>NotificacionPorPaisAfectado</legend>				

		<div data-linea="1">
			<label for="id_notificacion_por_producto">id_notificacion_por_producto </label>
			<input type="text" id="id_notificacion_por_producto" name="id_notificacion_por_producto" value="<?php echo $this->modeloNotificacionPorPaisAfectado->getIdNotificacionPorProducto(); ?>"
			placeholder="Identificador de la tabla" required maxlength="8" />
		</div>				

		<div data-linea="2">
			<label for="id_notificacion">id_notificacion </label>
			<input type="text" id="id_notificacion" name="id_notificacion" value="<?php echo $this->modeloNotificacionPorPaisAfectado->getIdNotificacion(); ?>"
			placeholder="Identificador de la notificación" required maxlength="8" />
		</div>				

		<div data-linea="3">
			<label for="id_localizacion">id_localizacion </label>
			<input type="text" id="id_localizacion" name="id_localizacion" value="<?php echo $this->modeloNotificacionPorPaisAfectado->getIdLocalizacion(); ?>"
			placeholder="Identificador del país afectado" required maxlength="8" />
		</div>				

		<div data-linea="4">
			<label for="nombre_pais">nombre_pais </label>
			<input type="text" id="nombre_pais" name="nombre_pais" value="<?php echo $this->modeloNotificacionPorPaisAfectado->getNombrePais(); ?>"
			placeholder="Nombre del país afectado" required maxlength="64" />
		</div>

		<div data-linea="5">
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
