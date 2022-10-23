<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>VacacionesPermisos' data-opcion='cronogramavacaciones/guardar' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR" method="post">
	<fieldset>
		<legend>CronogramaVacaciones</legend>				

		<div data-linea="1">
			<label for="id_cronograma_vacacion">id_cronograma_vacacion </label>
			<input type="text" id="id_cronograma_vacacion" name="id_cronograma_vacacion" value="<?php echo $this->modeloCronogramaVacaciones->getIdCronogramaVacacion(); ?>"
			placeholder="Identificador unico de la tabla" maxlength="16" />
		</div>				

		<div data-linea="2">
			<label for="identificador">identificador </label>
			<input type="text" id="identificador" name="identificador" value="<?php echo $this->modeloCronogramaVacaciones->getIdentificador(); ?>"
			placeholder="Cedula de funcionario que planifica las vacaciones" maxlength="13" />
		</div>				

		<div data-linea="3">
			<label for="fecha_ingreso_institucion">fecha_ingreso_institucion </label>
			<input type="text" id="fecha_ingreso_institucion" name="fecha_ingreso_institucion" value="<?php echo $this->modeloCronogramaVacaciones->getFechaIngresoInstitucion(); ?>"
			placeholder="Fecha de ingreso del primer contrato" maxlength="16" />
		</div>				

		<div data-linea="4">
			<label for="id_puesto">id_puesto </label>
			<input type="text" id="id_puesto" name="id_puesto" value="<?php echo $this->modeloCronogramaVacaciones->getIdPuesto(); ?>"
			placeholder="identificador unico de la tabla g_catalogos.puestos" maxlength="16" />
		</div>				

		<div data-linea="5">
			<label for="identificador_backup">identificador_backup </label>
			<input type="text" id="identificador_backup" name="identificador_backup" value="<?php echo $this->modeloCronogramaVacaciones->getIdentificadorBackup(); ?>"
			placeholder="Cedula de funcionario backup del que planifica las vacaciones" maxlength="13" />
		</div>				

		<div data-linea="6">
			<label for="total_dias_planificados">total_dias_planificados </label>
			<input type="text" id="total_dias_planificados" name="total_dias_planificados" value="<?php echo $this->modeloCronogramaVacaciones->getTotalDiasPlanificados(); ?>"
			placeholder="Número total de dias planificados de vacaciones" maxlength="16" />
		</div>				

		<div data-linea="7">
			<label for="observacion">observacion </label>
			<input type="text" id="observacion" name="observacion" value="<?php echo $this->modeloCronogramaVacaciones->getObservacion(); ?>"
			placeholder="Observaciones de la aprobación o rechazo de la planificacion de vacaciones" maxlength="512" />
		</div>				

		<div data-linea="16">
			<label for="identificador_revisor">identificador_revisor </label>
			<input type="text" id="identificador_revisor" name="identificador_revisor" value="<?php echo $this->modeloCronogramaVacaciones->getIdentificadorRevisor(); ?>"
			placeholder="Cedula de funcionario que tiene altualmente el tramite" maxlength="13" />
		</div>				

		<div data-linea="9">
			<label for="id_area_revisor">id_area_revisor </label>
			<input type="text" id="id_area_revisor" name="id_area_revisor" value="<?php echo $this->modeloCronogramaVacaciones->getIdAreaRevisor(); ?>"
			placeholder="Area de funcionario que tiene altualmente el tramite" maxlength="13" />
		</div>				

		<div data-linea="10">
			<label for="usuario_creacion">usuario_creacion </label>
			<input type="text" id="usuario_creacion" name="usuario_creacion" value="<?php echo $this->modeloCronogramaVacaciones->getUsuarioCreacion(); ?>"
			placeholder="Cedula de funcionario que registra la planificacion las vacaciones" maxlength="13" />
		</div>				

		<div data-linea="11">
			<label for="usuario_modificacion">usuario_modificacion </label>
			<input type="text" id="usuario_modificacion" name="usuario_modificacion" value="<?php echo $this->modeloCronogramaVacaciones->getUsuarioModificacion(); ?>"
			placeholder="Cedula de funcionario que actualiza la planificacion las vacaciones" maxlength="16" />
		</div>				

		<div data-linea="12">
			<label for="fecha_creacion">fecha_creacion </label>
			<input type="text" id="fecha_creacion" name="fecha_creacion" value="<?php echo $this->modeloCronogramaVacaciones->getFechaCreacion(); ?>"
			placeholder="Fecha de registro en el sistema" maxlength="16" />
		</div>				

		<div data-linea="13">
			<label for="fecha_modificacion">fecha_modificacion </label>
			<input type="text" id="fecha_modificacion" name="fecha_modificacion" value="<?php echo $this->modeloCronogramaVacaciones->getFechaModificacion(); ?>"
			placeholder="Fecha de modificación en el sistema" maxlength="16" />
		</div>				

		<div data-linea="14">
			<label for="estado_cronograma_vacacion">estado_cronograma_vacacion </label>
			<input type="text" id="estado_cronograma_vacacion" name="estado_cronograma_vacacion" value="<?php echo $this->modeloCronogramaVacaciones->getEstadoCronogramaVacacion(); ?>"
			placeholder="Estado de la revisión del registro de planificacion de vacaciones" maxlength="16" />
		</div>				

		<div data-linea="15">
			<label for="estado_solicitud">estado_solicitud </label>
			<input type="text" id="estado_solicitud" name="estado_solicitud" value="<?php echo $this->modeloCronogramaVacaciones->getEstadoSolicitud(); ?>"
			placeholder="Estado del registro Activo/Inactivo" maxlength="16" />
		</div>				

		<div data-linea="16">
			<label for="anio_cronograma_vacacion">anio_cronograma_vacacion </label>
			<input type="text" id="anio_cronograma_vacacion" name="anio_cronograma_vacacion" value="<?php echo $this->modeloCronogramaVacaciones->getAnioCronogramaVacacion(); ?>"
			placeholder="" maxlength="16" />
		</div>

		<div data-linea="17">
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
