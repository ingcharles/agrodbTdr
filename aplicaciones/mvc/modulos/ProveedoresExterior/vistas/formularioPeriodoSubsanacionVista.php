<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
<form id='formulario'
	data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>ProveedoresExterior'
	data-opcion='periodosubsanacion/guardar' data-destino="detalleItem"
	data-accionEnExito="ACTUALIZAR" method="post">
	<fieldset>
		<legend>PeriodoSubsanacion</legend>

		<div data-linea="1">
			<label for="id_periodo_subsanacion">id_periodo_subsanacion </label> <input
				type="text" id="id_periodo_subsanacion"
				name="id_periodo_subsanacion"
				value="<?php echo $this->modeloPeriodoSubsanacion->getIdPeriodoSubsanacion(); ?>"
				placeholder="Identificador único de la tabla" required maxlength="8" />
		</div>

		<div data-linea="2">
			<label for="tiempo_periodo_subsanacion">tiempo_periodo_subsanacion </label>
			<input type="text" id="tiempo_periodo_subsanacion"
				name="tiempo_periodo_subsanacion"
				value="<?php echo $this->modeloPeriodoSubsanacion->getTiempoPeriodoSubsanacion(); ?>"
				placeholder="Campo que almacena el tiempo en dias maximos para subsanar una solicitud"
				required maxlength="8" />
		</div>

		<div data-linea="3">
			<label for="estado_periodo_subsanacion">estado_periodo_subsanacion </label>
			<input type="text" id="estado_periodo_subsanacion"
				name="estado_periodo_subsanacion"
				value="<?php echo $this->modeloPeriodoSubsanacion->getEstadoPeriodoSubsanacion(); ?>"
				placeholder="Campo que almacena el estado del periodo de subsanacion (solo debe haber un activo)"
				required maxlength="8" />
		</div>

		<div data-linea="4">
			<label for="fecha_creacion_periodo_subsanacion">fecha_creacion_periodo_subsanacion
			</label> <input type="text" id="fecha_creacion_periodo_subsanacion"
				name="fecha_creacion_periodo_subsanacion"
				value="<?php echo $this->modeloPeriodoSubsanacion->getFechaCreacionPeriodoSubsanacion(); ?>"
				placeholder="Campo que almacena la fecha en que se creo el registro de periodo de subsanacion"
				required maxlength="8" />
		</div>

		<div data-linea="5">
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
