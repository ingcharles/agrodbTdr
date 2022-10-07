<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>RegistroControlDocumentos' data-opcion='detalletecnico/guardar' data-destino="detalleItem" data-accionEnExito ="ACTUALIZAR" method="post">
	<fieldset>
		<legend>DetalleTecnico</legend>				

		<div data-linea="1">
			<label for="id_detalle_tecnico">id_detalle_tecnico </label>
			<input type="text" id="id_detalle_tecnico" name="id_detalle_tecnico" value="<?php echo $this->modeloDetalleTecnico->getIdDetalleTecnico(); ?>"
			placeholder="Llave primaria de la tabla" required maxlength="8" />
		</div>				

		<div data-linea="2">
			<label for="id_registro_sgc">id_registro_sgc </label>
			<input type="text" id="id_registro_sgc" name="id_registro_sgc" value="<?php echo $this->modeloDetalleTecnico->getIdRegistroSgc(); ?>"
			placeholder="Llave foránea de la tabla registro_sgc" required maxlength="8" />
		</div>				

		<div data-linea="3">
			<label for="identificador">identificador </label>
			<input type="text" id="identificador" name="identificador" value="<?php echo $this->modeloDetalleTecnico->getIdentificador(); ?>"
			placeholder="Identificador_tecnico" required maxlength="13" />
		</div>				

		<div data-linea="4">
			<label for="tecnico">tecnico </label>
			<input type="text" id="tecnico" name="tecnico" value="<?php echo $this->modeloDetalleTecnico->getTecnico(); ?>"
			placeholder="Nombre del tecnico" required maxlength="128" />
		</div>				

		<div data-linea="5">
			<label for="estado">estado </label>
			<input type="text" id="estado" name="estado" value="<?php echo $this->modeloDetalleTecnico->getEstado(); ?>"
			placeholder="Estado del registro" required maxlength="12" />
		</div>				

		<div data-linea="6">
			<label for="fecha_creacion">fecha_creacion </label>
			<input type="text" id="fecha_creacion" name="fecha_creacion" value="<?php echo $this->modeloDetalleTecnico->getFechaCreacion(); ?>"
			placeholder="Fecha de creación del registro" required maxlength="8" />
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
