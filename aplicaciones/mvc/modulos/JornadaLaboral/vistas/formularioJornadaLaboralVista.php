<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
<form id='formulario'
	data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>JornadaLaboral' data-opcion='jornadaLaboral/guardar' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR" method="post">
	<fieldset>
		<legend>JornadaLaboral</legend>
 
			<input type="hidden" id="id_jornada_laboral" name="id_jornada_laboral" value="<?php echo $this->modeloJornadaLaboral->getIdJornadaLaboral(); ?>" />

		<div data-linea="2">
			<label for="identificador">identificador </label> 
				<input type="text" 	id="identificador" name="identificador" value="<?php echo $this->modeloJornadaLaboral->getIdentificador(); ?>" 
					placeholder="Numero de identificación del funcionario" required="required" maxlength="13" />
		</div>

		<div data-linea="3">
			<label for="grupo">grupo </label> 
				<input type="text" id="grupo" name="grupo" value="<?php echo $this->modeloJornadaLaboral->getGrupo(); ?>" 
					placeholder="Tipo de grupo, opciones: Grupo 1, Grupo 2" required="required" maxlength="32" />
		</div>

		<div data-linea="4">
			<label for="horario">horario </label> 
				<input type="text" id="horario" name="horario" value="<?php echo $this->modeloJornadaLaboral->getHorario(); ?>"
					placeholder="Horario en el que desempeña su jornada laboral cada funcionario" required="required" maxlength="32" />
		</div>

		<div data-linea="5">
			<label for="mes">mes </label> 
				<input type="text" id="mes" name="mes" value="<?php echo $this->modeloJornadaLaboral->getMes(); ?>"
					placeholder="mes en el cual se estable la jornada laboral" required="required" maxlength="32" />
		</div>

		<div data-linea="6">
			<label for="estado_registro">Estado: </label>
				<select id="estado_registro" name="estado_registro">
					<option value="">Seleccionar....</option>
                	<?php 
                		echo $this->comboActivoInactivo($this->modeloJornadaLaboral->getEstadoRegistro());
                	?>
            	</select>
		</div>

		<div data-linea="9">
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
