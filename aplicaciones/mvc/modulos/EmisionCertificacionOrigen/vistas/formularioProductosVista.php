<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>EmisionCertificacionOrigen' data-opcion='productos/guardar' data-destino="detalleItem" data-accionEnExito ="ACTUALIZAR" method="post">
	<fieldset>
		<legend>Productos</legend>				

		<div data-linea="1">
			<label for="id_productos">id_productos </label>
			<input type="text" id="id_productos" name="id_productos" value="<?php echo $this->modeloProductos->getIdProductos(); ?>"
			placeholder="Llave primaria de la tabla" required maxlength="8" />
		</div>				

		<div data-linea="2">
			<label for="id_registro_produccion">id_registro_produccion </label>
			<input type="text" id="id_registro_produccion" name="id_registro_produccion" value="<?php echo $this->modeloProductos->getIdRegistroProduccion(); ?>"
			placeholder="Llave foránea de la tabla registro_produccion" required maxlength="8" />
		</div>				

		<div data-linea="3">
			<label for="num_canales_obtenidos">num_canales_obtenidos </label>
			<input type="text" id="num_canales_obtenidos" name="num_canales_obtenidos" value="<?php echo $this->modeloProductos->getNumCanalesObtenidos(); ?>"
			placeholder="Número de canales obtenidos" required maxlength="8" />
		</div>				

		<div data-linea="4">
			<label for="num_canales_obtenidos_uso">num_canales_obtenidos_uso </label>
			<input type="text" id="num_canales_obtenidos_uso" name="num_canales_obtenidos_uso" value="<?php echo $this->modeloProductos->getNumCanalesObtenidosUso(); ?>"
			placeholder="Número de canales obtenidos sin restricción de uso" required maxlength="8" />
		</div>				

		<div data-linea="5">
			<label for="num_canales_uso_industri">num_canales_uso_industri </label>
			<input type="text" id="num_canales_uso_industri" name="num_canales_uso_industri" value="<?php echo $this->modeloProductos->getNumCanalesUsoIndustri(); ?>"
			placeholder="Número de canales para uso industrial" required maxlength="8" />
		</div>				

		<div data-linea="6">
			<label for="fecha_creacion">fecha_creacion </label>
			<input type="text" id="fecha_creacion" name="fecha_creacion" value="<?php echo $this->modeloProductos->getFechaCreacion(); ?>"
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
