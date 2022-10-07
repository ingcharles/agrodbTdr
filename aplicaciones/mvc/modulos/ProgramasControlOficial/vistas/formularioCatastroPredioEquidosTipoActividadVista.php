<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>ProgramasControlOficial' data-opcion='catastropredioequidostipoactividad/guardar' data-destino="detalleItem" data-accionEnExito ="ACTUALIZAR" method="post">
	<fieldset>
		<legend>CatastroPredioEquidosTipoActividad</legend>				

		<div data-linea="1">
			<label for="id_catastro_predio_equidos_tipo_actividad">id_catastro_predio_equidos_tipo_actividad </label>
			<input type="text" id="id_catastro_predio_equidos_tipo_actividad" name="id_catastro_predio_equidos_tipo_actividad" value="<?php echo $this->modeloCatastroPredioEquidosTipoActividad->getIdCatastroPredioEquidosTipoActividad(); ?>"
			placeholder="" required maxlength="8" />
		</div>				

		<div data-linea="2">
			<label for="id_catastro_predio_equidos">id_catastro_predio_equidos </label>
			<input type="text" id="id_catastro_predio_equidos" name="id_catastro_predio_equidos" value="<?php echo $this->modeloCatastroPredioEquidosTipoActividad->getIdCatastroPredioEquidos(); ?>"
			placeholder="" required maxlength="8" />
		</div>				

		<div data-linea="3">
			<label for="identificador">identificador </label>
			<input type="text" id="identificador" name="identificador" value="<?php echo $this->modeloCatastroPredioEquidosTipoActividad->getIdentificador(); ?>"
			placeholder="" required maxlength="13" />
		</div>				

		<div data-linea="4">
			<label for="fecha_creacion">fecha_creacion </label>
			<input type="text" id="fecha_creacion" name="fecha_creacion" value="<?php echo $this->modeloCatastroPredioEquidosTipoActividad->getFechaCreacion(); ?>"
			placeholder="" required maxlength="8" />
		</div>				

		<div data-linea="5">
			<label for="id_tipo_actividad">id_tipo_actividad </label>
			<input type="text" id="id_tipo_actividad" name="id_tipo_actividad" value="<?php echo $this->modeloCatastroPredioEquidosTipoActividad->getIdTipoActividad(); ?>"
			placeholder="" required maxlength="8" />
		</div>				

		<div data-linea="6">
			<label for="tipo_actividad">tipo_actividad </label>
			<input type="text" id="tipo_actividad" name="tipo_actividad" value="<?php echo $this->modeloCatastroPredioEquidosTipoActividad->getTipoActividad(); ?>"
			placeholder="" required maxlength="32" />
		</div>				

		<div data-linea="7">
			<label for="extension_actividad">extension_actividad </label>
			<input type="text" id="extension_actividad" name="extension_actividad" value="<?php echo $this->modeloCatastroPredioEquidosTipoActividad->getExtensionActividad(); ?>"
			placeholder="" required maxlength="8" />
		</div>

		<div data-linea="8">
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
