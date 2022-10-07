<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>ProgramasControlOficial' data-opcion='catastropredioequidoscatastro/guardar' data-destino="detalleItem" data-accionEnExito ="ACTUALIZAR" method="post">
	<fieldset>
		<legend>CatastroPredioEquidosCatastro</legend>				

		<div data-linea="1">
			<label for="id_catastro_predio_equidos_catastro">id_catastro_predio_equidos_catastro </label>
			<input type="text" id="id_catastro_predio_equidos_catastro" name="id_catastro_predio_equidos_catastro" value="<?php echo $this->modeloCatastroPredioEquidosCatastro->getIdCatastroPredioEquidosCatastro(); ?>"
			placeholder="" required maxlength="8" />
		</div>				

		<div data-linea="2">
			<label for="id_catastro_predio_equidos">id_catastro_predio_equidos </label>
			<input type="text" id="id_catastro_predio_equidos" name="id_catastro_predio_equidos" value="<?php echo $this->modeloCatastroPredioEquidosCatastro->getIdCatastroPredioEquidos(); ?>"
			placeholder="" required maxlength="8" />
		</div>				

		<div data-linea="3">
			<label for="identificador">identificador </label>
			<input type="text" id="identificador" name="identificador" value="<?php echo $this->modeloCatastroPredioEquidosCatastro->getIdentificador(); ?>"
			placeholder="" required maxlength="13" />
		</div>				

		<div data-linea="4">
			<label for="fecha_creacion">fecha_creacion </label>
			<input type="text" id="fecha_creacion" name="fecha_creacion" value="<?php echo $this->modeloCatastroPredioEquidosCatastro->getFechaCreacion(); ?>"
			placeholder="" required maxlength="8" />
		</div>				

		<div data-linea="5">
			<label for="id_catastro">id_catastro </label>
			<input type="text" id="id_catastro" name="id_catastro" value="<?php echo $this->modeloCatastroPredioEquidosCatastro->getIdCatastro(); ?>"
			placeholder="" required maxlength="8" />
		</div>				

		<div data-linea="6">
			<label for="catastro">catastro </label>
			<input type="text" id="catastro" name="catastro" value="<?php echo $this->modeloCatastroPredioEquidosCatastro->getCatastro(); ?>"
			placeholder="" required maxlength="32" />
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
