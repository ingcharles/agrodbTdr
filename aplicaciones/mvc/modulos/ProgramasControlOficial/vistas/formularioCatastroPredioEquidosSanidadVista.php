<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>ProgramasControlOficial' data-opcion='catastropredioequidossanidad/guardar' data-destino="detalleItem" data-accionEnExito ="ACTUALIZAR" method="post">
	<fieldset>
		<legend>CatastroPredioEquidosSanidad</legend>				

		<div data-linea="1">
			<label for="id_catastro_predio_equidos_sanidad">id_catastro_predio_equidos_sanidad </label>
			<input type="text" id="id_catastro_predio_equidos_sanidad" name="id_catastro_predio_equidos_sanidad" value="<?php echo $this->modeloCatastroPredioEquidosSanidad->getIdCatastroPredioEquidosSanidad(); ?>"
			placeholder="" required maxlength="8" />
		</div>				

		<div data-linea="2">
			<label for="id_catastro_predio_equidos">id_catastro_predio_equidos </label>
			<input type="text" id="id_catastro_predio_equidos" name="id_catastro_predio_equidos" value="<?php echo $this->modeloCatastroPredioEquidosSanidad->getIdCatastroPredioEquidos(); ?>"
			placeholder="" required maxlength="8" />
		</div>				

		<div data-linea="3">
			<label for="identificador">identificador </label>
			<input type="text" id="identificador" name="identificador" value="<?php echo $this->modeloCatastroPredioEquidosSanidad->getIdentificador(); ?>"
			placeholder="" required maxlength="13" />
		</div>				

		<div data-linea="4">
			<label for="fecha_creacion">fecha_creacion </label>
			<input type="text" id="fecha_creacion" name="fecha_creacion" value="<?php echo $this->modeloCatastroPredioEquidosSanidad->getFechaCreacion(); ?>"
			placeholder="" required maxlength="8" />
		</div>				

		<div data-linea="5">
			<label for="profesional_tecnico">profesional_tecnico </label>
			<input type="text" id="profesional_tecnico" name="profesional_tecnico" value="<?php echo $this->modeloCatastroPredioEquidosSanidad->getProfesionalTecnico(); ?>"
			placeholder="" required maxlength="32" />
		</div>				

		<div data-linea="6">
			<label for="pesebreras">pesebreras </label>
			<input type="text" id="pesebreras" name="pesebreras" value="<?php echo $this->modeloCatastroPredioEquidosSanidad->getPesebreras(); ?>"
			placeholder="" required maxlength="2" />
		</div>				

		<div data-linea="7">
			<label for="area_cuarentena">area_cuarentena </label>
			<input type="text" id="area_cuarentena" name="area_cuarentena" value="<?php echo $this->modeloCatastroPredioEquidosSanidad->getAreaCuarentena(); ?>"
			placeholder="" required maxlength="2" />
		</div>				

		<div data-linea="8">
			<label for="eliminacion_desechos">eliminacion_desechos </label>
			<input type="text" id="eliminacion_desechos" name="eliminacion_desechos" value="<?php echo $this->modeloCatastroPredioEquidosSanidad->getEliminacionDesechos(); ?>"
			placeholder="" required maxlength="2" />
		</div>				

		<div data-linea="9">
			<label for="control_vectores">control_vectores </label>
			<input type="text" id="control_vectores" name="control_vectores" value="<?php echo $this->modeloCatastroPredioEquidosSanidad->getControlVectores(); ?>"
			placeholder="" required maxlength="2" />
		</div>				

		<div data-linea="10">
			<label for="uso_aperos_individuales">uso_aperos_individuales </label>
			<input type="text" id="uso_aperos_individuales" name="uso_aperos_individuales" value="<?php echo $this->modeloCatastroPredioEquidosSanidad->getUsoAperosIndividuales(); ?>"
			placeholder="" required maxlength="2" />
		</div>				

		<div data-linea="11">
			<label for="reporte_positivo_aie">reporte_positivo_aie </label>
			<input type="text" id="reporte_positivo_aie" name="reporte_positivo_aie" value="<?php echo $this->modeloCatastroPredioEquidosSanidad->getReportePositivoAie(); ?>"
			placeholder="" required maxlength="2" />
		</div>				

		<div data-linea="12">
			<label for="id_medida_sanitaria">id_medida_sanitaria </label>
			<input type="text" id="id_medida_sanitaria" name="id_medida_sanitaria" value="<?php echo $this->modeloCatastroPredioEquidosSanidad->getIdMedidaSanitaria(); ?>"
			placeholder="" required maxlength="8" />
		</div>				

		<div data-linea="13">
			<label for="medida_sanitaria">medida_sanitaria </label>
			<input type="text" id="medida_sanitaria" name="medida_sanitaria" value="<?php echo $this->modeloCatastroPredioEquidosSanidad->getMedidaSanitaria(); ?>"
			placeholder="" required maxlength="32" />
		</div>

		<div data-linea="14">
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
