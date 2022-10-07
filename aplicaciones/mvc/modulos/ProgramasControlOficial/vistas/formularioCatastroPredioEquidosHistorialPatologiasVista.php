<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>ProgramasControlOficial' data-opcion='catastropredioequidoshistorialpatologias/guardar' data-destino="detalleItem" data-accionEnExito ="ACTUALIZAR" method="post">
	<fieldset>
		<legend>CatastroPredioEquidosHistorialPatologias</legend>				

		<div data-linea="1">
			<label for="id_catastro_predio_equidos_historial_patologias">id_catastro_predio_equidos_historial_patologias </label>
			<input type="text" id="id_catastro_predio_equidos_historial_patologias" name="id_catastro_predio_equidos_historial_patologias" value="<?php echo $this->modeloCatastroPredioEquidosHistorialPatologias->getIdCatastroPredioEquidosHistorialPatologias(); ?>"
			placeholder="" required maxlength="8" />
		</div>				

		<div data-linea="2">
			<label for="id_catastro_predio_equidos">id_catastro_predio_equidos </label>
			<input type="text" id="id_catastro_predio_equidos" name="id_catastro_predio_equidos" value="<?php echo $this->modeloCatastroPredioEquidosHistorialPatologias->getIdCatastroPredioEquidos(); ?>"
			placeholder="" required maxlength="8" />
		</div>				

		<div data-linea="3">
			<label for="identificador">identificador </label>
			<input type="text" id="identificador" name="identificador" value="<?php echo $this->modeloCatastroPredioEquidosHistorialPatologias->getIdentificador(); ?>"
			placeholder="" required maxlength="13" />
		</div>				

		<div data-linea="4">
			<label for="fecha_creacion">fecha_creacion </label>
			<input type="text" id="fecha_creacion" name="fecha_creacion" value="<?php echo $this->modeloCatastroPredioEquidosHistorialPatologias->getFechaCreacion(); ?>"
			placeholder="" required maxlength="8" />
		</div>				

		<div data-linea="5">
			<label for="id_enfermedad">id_enfermedad </label>
			<input type="text" id="id_enfermedad" name="id_enfermedad" value="<?php echo $this->modeloCatastroPredioEquidosHistorialPatologias->getIdEnfermedad(); ?>"
			placeholder="" required maxlength="8" />
		</div>				

		<div data-linea="6">
			<label for="enfermedad">enfermedad </label>
			<input type="text" id="enfermedad" name="enfermedad" value="<?php echo $this->modeloCatastroPredioEquidosHistorialPatologias->getEnfermedad(); ?>"
			placeholder="" required maxlength="32" />
		</div>				

		<div data-linea="7">
			<label for="id_vacuna">id_vacuna </label>
			<input type="text" id="id_vacuna" name="id_vacuna" value="<?php echo $this->modeloCatastroPredioEquidosHistorialPatologias->getIdVacuna(); ?>"
			placeholder="" required maxlength="8" />
		</div>				

		<div data-linea="8">
			<label for="vacuna">vacuna </label>
			<input type="text" id="vacuna" name="vacuna" value="<?php echo $this->modeloCatastroPredioEquidosHistorialPatologias->getVacuna(); ?>"
			placeholder="" required maxlength="32" />
		</div>				

		<div data-linea="9">
			<label for="laboratorio">laboratorio </label>
			<input type="text" id="laboratorio" name="laboratorio" value="<?php echo $this->modeloCatastroPredioEquidosHistorialPatologias->getLaboratorio(); ?>"
			placeholder="" required maxlength="32" />
		</div>

		<div data-linea="10">
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
