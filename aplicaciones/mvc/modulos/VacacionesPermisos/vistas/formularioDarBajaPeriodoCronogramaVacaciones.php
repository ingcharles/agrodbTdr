<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

<?php $this->datosGenerales; ?>
<!-- form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>VacacionesPermisos' data-opcion='periodocronogramavacaciones/guardar' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR" method="post">
	<fieldset>
		<legend>PeriodoCronogramaVacaciones</legend>				

		<div data-linea="1">
			<label for="id_periodo_cronograma_vacacion">id_periodo_cronograma_vacacion </label>
			<input type="text" id="id_periodo_cronograma_vacacion" name="id_periodo_cronograma_vacacion" value="<?php echo $this->modeloPeriodoCronogramaVacaciones->getIdPeriodoCronogramaVacacion(); ?>"
			placeholder="Identificador unico de la tabla" required maxlength="8" />
		</div>				

		<div data-linea="2">
			<label for="id_cronograma_vacacion">id_cronograma_vacacion </label>
			<input type="text" id="id_cronograma_vacacion" name="id_cronograma_vacacion" value="<?php echo $this->modeloPeriodoCronogramaVacaciones->getIdCronogramaVacacion(); ?>"
			placeholder="Identificador unico de la tabla g_vacaciones.cronograma_vacaciones" required maxlength="8" />
		</div>				

		<div data-linea="3">
			<label for="numero_periodo">numero_periodo </label>
			<input type="text" id="numero_periodo" name="numero_periodo" value="<?php echo $this->modeloPeriodoCronogramaVacaciones->getNumeroPeriodo(); ?>"
			placeholder="Numero del periodo del cronograma de vacaciones Primer Periodo/Segundo Periodo" required maxlength="8" />
		</div>				

		<div data-linea="4">
			<label for="fecha_inicio">fecha_inicio </label>
			<input type="text" id="fecha_inicio" name="fecha_inicio" value="<?php echo $this->modeloPeriodoCronogramaVacaciones->getFechaInicio(); ?>"
			placeholder="Fecha de inicio de periodo de vacaciones" required maxlength="8" />
		</div>				

		<div data-linea="5">
			<label for="fecha_fin">fecha_fin </label>
			<input type="text" id="fecha_fin" name="fecha_fin" value="<?php echo $this->modeloPeriodoCronogramaVacaciones->getFechaFin(); ?>"
			placeholder="Fecha de retorno de periodo de vacaciones" required maxlength="8" />
		</div>				

		<div data-linea="6">
			<label for="total_dias">total_dias </label>
			<input type="text" id="total_dias" name="total_dias" value="<?php echo $this->modeloPeriodoCronogramaVacaciones->getTotalDias(); ?>"
			placeholder="Numero total de dias del periodo de vacaciones" required maxlength="8" />
		</div>				

		<div data-linea="7">
			<label for="estado_registro">estado_registro </label>
			<input type="text" id="estado_registro" name="estado_registro" value="<?php echo $this->modeloPeriodoCronogramaVacaciones->getEstadoRegistro(); ?>"
			placeholder="Estado del registro Activo/Inactivo" required maxlength="16" />
		</div>				

		<div data-linea="8">
			<label for="estado_reprogramacion">estado_reprogramacion </label>
			<input type="text" id="estado_reprogramacion" name="estado_reprogramacion" value="<?php echo $this->modeloPeriodoCronogramaVacaciones->getEstadoReprogramacion(); ?>"
			placeholder="Estado de la reprogracion SI/NO caso contratio si es NULL es planificacion" required maxlength="8" />
		</div>

		<div data-linea="9">
			<button type="submit" class="guardar">Guardar</button>
		</div>
	</fieldset >
</form -->
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
