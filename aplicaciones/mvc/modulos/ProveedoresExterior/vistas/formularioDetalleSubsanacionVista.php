<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
<form id='formulario'
	data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>ProveedoresExterior'
	data-opcion='detallesubsanacion/guardar' data-destino="detalleItem"
	data-accionEnExito="ACTUALIZAR" method="post">
	<fieldset>
		<legend>DetalleSubsanacion</legend>

		<div data-linea="1">
			<label for="id_detalle_subsanacion">id_detalle_subsanacion </label> <input
				type="text" id="id_detalle_subsanacion"
				name="id_detalle_subsanacion"
				value="<?php echo $this->modeloDetalleSubsanacion->getIdDetalleSubsanacion(); ?>"
				placeholder="Identificador unico de la tabla" required maxlength="8" />
		</div>

		<div data-linea="2">
			<label for="id_subsanacion">id_subsanacion </label> <input
				type="text" id="id_subsanacion" name="id_subsanacion"
				value="<?php echo $this->modeloDetalleSubsanacion->getIdSubsanacion(); ?>"
				placeholder="Identificador unico de la tabla g_proveedores_exterior.subsanaciones (llave foranea)"
				required maxlength="8" />
		</div>

		<div data-linea="3">
			<label for="identificador_revisor">identificador_revisor </label> <input
				type="text" id="identificador_revisor" name="identificador_revisor"
				value="<?php echo $this->modeloDetalleSubsanacion->getIdentificadorRevisor(); ?>"
				placeholder="Campo que almacena el identificador del técnico que genera el proceso de subsanacion"
				required maxlength="13" />
		</div>

		<div data-linea="4">
			<label for="fecha_subsanacion">fecha_subsanacion </label> <input
				type="text" id="fecha_subsanacion" name="fecha_subsanacion"
				value="<?php echo $this->modeloDetalleSubsanacion->getFechaSubsanacion(); ?>"
				placeholder="Campo que almacena la fecha en que se envia a subsanar la solicitud"
				required maxlength="8" />
		</div>

		<div data-linea="5">
			<label for="fecha_subsanacion_operador">fecha_subsanacion_operador </label>
			<input type="text" id="fecha_subsanacion_operador"
				name="fecha_subsanacion_operador"
				value="<?php echo $this->modeloDetalleSubsanacion->getFechaSubsanacionOperador(); ?>"
				placeholder="Campo que almacena la fecha en la que el operador subsana la solicitud"
				required maxlength="8" />
		</div>

		<div data-linea="6">
			<label for="dias_transcurridos">dias_transcurridos </label> <input
				type="text" id="dias_transcurridos" name="dias_transcurridos"
				value="<?php echo $this->modeloDetalleSubsanacion->getDiasTranscurridos(); ?>"
				placeholder="Campo que almacena los dias transcurridos para que el operador subsane la solicitud"
				required maxlength="8" />
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
