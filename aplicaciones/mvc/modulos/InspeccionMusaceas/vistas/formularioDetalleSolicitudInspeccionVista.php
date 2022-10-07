<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>InspeccionMusaceas' data-opcion='detallesolicitudinspeccion/guardar' data-destino="detalleItem" data-accionEnExito ="ACTUALIZAR" method="post">
	<fieldset>
		<legend>DetalleSolicitudInspeccion</legend>				

		<div data-linea="1">
			<label for="id_detalle_solicitud_inspeccion">id_detalle_solicitud_inspeccion </label>
			<input type="text" id="id_detalle_solicitud_inspeccion" name="id_detalle_solicitud_inspeccion" value="<?php echo $this->modeloDetalleSolicitudInspeccion->getIdDetalleSolicitudInspeccion(); ?>"
			placeholder="Llave primaria de la tabla" required maxlength="8" />
		</div>				

		<div data-linea="2">
			<label for="razon_social">razon_social </label>
			<input type="text" id="razon_social" name="razon_social" value="<?php echo $this->modeloDetalleSolicitudInspeccion->getRazonSocial(); ?>"
			placeholder="Razón social" required maxlength="100" />
		</div>				

		<div data-linea="3">
			<label for="area">area </label>
			<input type="text" id="area" name="area" value="<?php echo $this->modeloDetalleSolicitudInspeccion->getArea(); ?>"
			placeholder="Área" required maxlength="60" />
		</div>				

		<div data-linea="4">
			<label for="num_cajas">num_cajas </label>
			<input type="text" id="num_cajas" name="num_cajas" value="<?php echo $this->modeloDetalleSolicitudInspeccion->getNumCajas(); ?>"
			placeholder="" required maxlength="8" />
		</div>				

		<div data-linea="5">
			<label for="fecha_creacion">fecha_creacion </label>
			<input type="text" id="fecha_creacion" name="fecha_creacion" value="<?php echo $this->modeloDetalleSolicitudInspeccion->getFechaCreacion(); ?>"
			placeholder="Fecha de creación del registro" required maxlength="8" />
		</div>				

		<div data-linea="6">
			<label for="id_solicitud_inspeccion">id_solicitud_inspeccion </label>
			<input type="text" id="id_solicitud_inspeccion" name="id_solicitud_inspeccion" value="<?php echo $this->modeloDetalleSolicitudInspeccion->getIdSolicitudInspeccion(); ?>"
			placeholder="Llave foránea de la tabla solicitud_inspeccion" required maxlength="8" />
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
