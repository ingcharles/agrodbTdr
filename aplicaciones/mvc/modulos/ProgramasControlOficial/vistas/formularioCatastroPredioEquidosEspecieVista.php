<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>ProgramasControlOficial' data-opcion='catastropredioequidosespecie/guardar' data-destino="detalleItem" data-accionEnExito ="ACTUALIZAR" method="post">
	<fieldset>
		<legend>CatastroPredioEquidosEspecie</legend>				

		<div data-linea="1">
			<label for="id_catastro_predio_equidos_especie">id_catastro_predio_equidos_especie </label>
			<input type="text" id="id_catastro_predio_equidos_especie" name="id_catastro_predio_equidos_especie" value="<?php echo $this->modeloCatastroPredioEquidosEspecie->getIdCatastroPredioEquidosEspecie(); ?>"
			placeholder="" required maxlength="8" />
		</div>				

		<div data-linea="2">
			<label for="id_catastro_predio_equidos">id_catastro_predio_equidos </label>
			<input type="text" id="id_catastro_predio_equidos" name="id_catastro_predio_equidos" value="<?php echo $this->modeloCatastroPredioEquidosEspecie->getIdCatastroPredioEquidos(); ?>"
			placeholder="" required maxlength="8" />
		</div>				

		<div data-linea="3">
			<label for="identificador">identificador </label>
			<input type="text" id="identificador" name="identificador" value="<?php echo $this->modeloCatastroPredioEquidosEspecie->getIdentificador(); ?>"
			placeholder="" required maxlength="13" />
		</div>				

		<div data-linea="4">
			<label for="fecha_creacion">fecha_creacion </label>
			<input type="text" id="fecha_creacion" name="fecha_creacion" value="<?php echo $this->modeloCatastroPredioEquidosEspecie->getFechaCreacion(); ?>"
			placeholder="" required maxlength="8" />
		</div>				

		<div data-linea="5">
			<label for="id_especie">id_especie </label>
			<input type="text" id="id_especie" name="id_especie" value="<?php echo $this->modeloCatastroPredioEquidosEspecie->getIdEspecie(); ?>"
			placeholder="" required maxlength="8" />
		</div>				

		<div data-linea="6">
			<label for="nombre_especie">nombre_especie </label>
			<input type="text" id="nombre_especie" name="nombre_especie" value="<?php echo $this->modeloCatastroPredioEquidosEspecie->getNombreEspecie(); ?>"
			placeholder="" required maxlength="32" />
		</div>				

		<div data-linea="7">
			<label for="id_raza">id_raza </label>
			<input type="text" id="id_raza" name="id_raza" value="<?php echo $this->modeloCatastroPredioEquidosEspecie->getIdRaza(); ?>"
			placeholder="" required maxlength="8" />
		</div>				

		<div data-linea="8">
			<label for="nombre_raza">nombre_raza </label>
			<input type="text" id="nombre_raza" name="nombre_raza" value="<?php echo $this->modeloCatastroPredioEquidosEspecie->getNombreRaza(); ?>"
			placeholder="" required maxlength="32" />
		</div>				

		<div data-linea="9">
			<label for="id_categoria">id_categoria </label>
			<input type="text" id="id_categoria" name="id_categoria" value="<?php echo $this->modeloCatastroPredioEquidosEspecie->getIdCategoria(); ?>"
			placeholder="" required maxlength="8" />
		</div>				

		<div data-linea="10">
			<label for="nombre_categoria">nombre_categoria </label>
			<input type="text" id="nombre_categoria" name="nombre_categoria" value="<?php echo $this->modeloCatastroPredioEquidosEspecie->getNombreCategoria(); ?>"
			placeholder="" required maxlength="32" />
		</div>				

		<div data-linea="11">
			<label for="numero_animales">numero_animales </label>
			<input type="text" id="numero_animales" name="numero_animales" value="<?php echo $this->modeloCatastroPredioEquidosEspecie->getNumeroAnimales(); ?>"
			placeholder="" required maxlength="8" />
		</div>

		<div data-linea="12">
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
