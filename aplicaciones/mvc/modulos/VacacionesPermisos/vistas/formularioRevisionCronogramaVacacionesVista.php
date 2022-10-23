<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>VacacionesPermisos' data-opcion='revisioncronogramavacaciones/guardar' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR" method="post">
	<fieldset>
		<legend>RevisionCronogramaVacaciones</legend>				

		<div data-linea="1">
			<label for="id_revision_cronograma_vacacion">id_revision_cronograma_vacacion </label>
			<input type="text" id="id_revision_cronograma_vacacion" name="id_revision_cronograma_vacacion" value="<?php echo $this->modeloRevisionCronogramaVacaciones->getIdRevisionCronogramaVacacion(); ?>"
			placeholder="Identificador unico de la tabla" required maxlength="8" />
		</div>				

		<div data-linea="2">
			<label for="id_cronograma_vacacion">id_cronograma_vacacion </label>
			<input type="text" id="id_cronograma_vacacion" name="id_cronograma_vacacion" value="<?php echo $this->modeloRevisionCronogramaVacaciones->getIdCronogramaVacacion(); ?>"
			placeholder="identificador unico de la tabla g_vacaciones.cronograma_vacacion" required maxlength="8" />
		</div>				

		<div data-linea="3">
			<label for="identicador_revisor">identicador_revisor </label>
			<input type="text" id="identicador_revisor" name="identicador_revisor" value="<?php echo $this->modeloRevisionCronogramaVacaciones->getIdenticadorRevisor(); ?>"
			placeholder="usuario que va aprobar el cronograma vacacion" required maxlength="13" />
		</div>				

		<div data-linea="4">
			<label for="id_area_revisor">id_area_revisor </label>
			<input type="text" id="id_area_revisor" name="id_area_revisor" value="<?php echo $this->modeloRevisionCronogramaVacaciones->getIdAreaRevisor(); ?>"
			placeholder="area del usuario que va aprobar el cronograma vacacion" required maxlength="8" />
		</div>				

		<div data-linea="5">
			<label for="estado_solicitud">estado_solicitud </label>
			<input type="text" id="estado_solicitud" name="estado_solicitud" value="<?php echo $this->modeloRevisionCronogramaVacaciones->getEstadoSolicitud(); ?>"
			placeholder="estado de la solicitud" required maxlength="16" />
		</div>				

		<div data-linea="6">
			<label for="fecha_creacion">fecha_creacion </label>
			<input type="text" id="fecha_creacion" name="fecha_creacion" value="<?php echo $this->modeloRevisionCronogramaVacaciones->getFechaCreacion(); ?>"
			placeholder="fecha de creacion del registro" required maxlength="8" />
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
