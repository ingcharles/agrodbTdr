<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
<form id='formulario'
	data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>ProveedoresExterior'
	data-opcion='subsanacion/guardar' data-destino="detalleItem"
	data-accionEnExito="ACTUALIZAR" method="post">
	<fieldset>
		<legend>Subsanacion</legend>

		<div data-linea="1">
			<label for="id_subsanacion">id_subsanacion </label> <input
				type="text" id="id_subsanacion" name="id_subsanacion"
				value="<?php echo $this->modeloSubsanacion->getIdSubsanacion(); ?>"
				placeholder="Identificador_unico de la tabla" required maxlength="8" />
		</div>

		<div data-linea="2">
			<label for="id_proveedor_exterior">id_proveedor_exterior </label> <input
				type="text" id="id_proveedor_exterior" name="id_proveedor_exterior"
				value="<?php echo $this->modeloSubsanacion->getIdProveedorExterior(); ?>"
				placeholder="Identificador de la tabla g_proveedores_exterior.proveedor_exterior (llave foranea)"
				required maxlength="8" />
		</div>

		<div data-linea="3">
			<label for="id_periodo_subsanacion">id_periodo_subsanacion </label> <input
				type="text" id="id_periodo_subsanacion"
				name="id_periodo_subsanacion"
				value="<?php echo $this->modeloSubsanacion->getIdPeriodoSubsanacion(); ?>"
				placeholder="Identificador unico de la tabla g_proveedores_exterior.periodo_subsanacion (llave foranea)"
				required maxlength="8" />
		</div>

		<div data-linea="4">
			<label for="dias_subsanacion">dias_subsanacion </label> <input
				type="text" id="dias_subsanacion" name="dias_subsanacion"
				value="<?php echo $this->modeloSubsanacion->getDiasSubsanacion(); ?>"
				placeholder="Campo que almacena los dias del periodo de subsanacion"
				required maxlength="8" />
		</div>

		<div data-linea="5">
			<label for="saldo_dias_subsanacion">saldo_dias_subsanacion </label> <input
				type="text" id="saldo_dias_subsanacion"
				name="saldo_dias_subsanacion"
				value="<?php echo $this->modeloSubsanacion->getSaldoDiasSubsanacion(); ?>"
				placeholder="Campo que almacena los dias restantes para realizar el proceso de subsanacion"
				required maxlength="8" />
		</div>

		<div data-linea="6">
			<label for="identificador_revisor">identificador_revisor </label> <input
				type="text" id="identificador_revisor" name="identificador_revisor"
				value="<?php echo $this->modeloSubsanacion->getIdentificadorRevisor(); ?>"
				placeholder="Campo que almacena el identificador del técnico que genera el proceso de subsanacion"
				required maxlength="13" />
		</div>

		<div data-linea="7">
			<label for="fecha_subsanacion">fecha_subsanacion </label> <input
				type="text" id="fecha_subsanacion" name="fecha_subsanacion"
				value="<?php echo $this->modeloSubsanacion->getFechaSubsanacion(); ?>"
				placeholder="Campo que almacena la fecha en que se envia a subsanar la solicitud"
				required maxlength="8" />
		</div>

		<div data-linea="8">
			<label for="fecha_subsanacion_operador">fecha_subsanacion_operador </label>
			<input type="text" id="fecha_subsanacion_operador"
				name="fecha_subsanacion_operador"
				value="<?php echo $this->modeloSubsanacion->getFechaSubsanacionOperador(); ?>"
				placeholder="Campo que almacena la fecha en la que el operador subsana la solicitud"
				required maxlength="8" />
		</div>

		<div data-linea="9">
			<label for="descontar_dias">descontar_dias </label> <input
				type="text" id="descontar_dias" name="descontar_dias"
				value="<?php echo $this->modeloSubsanacion->getDescontarDias(); ?>"
				placeholder="Campo bandera que indica si se debe realizar el proceso de descuento de dias"
				required maxlength="2" />
		</div>

		<div data-linea="10">
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
