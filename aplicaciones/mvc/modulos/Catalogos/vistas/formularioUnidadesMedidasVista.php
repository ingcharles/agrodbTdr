<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

<!-- Despliegue de datos -->
<div id="datosVisualizacion">
		
	<fieldset>
		<legend>Datos Generales</legend>
		
		<div data-linea="1">
			<label for="nombre">Nombre de Unidad de Medida:</label>
				<?php echo $this->modeloUnidadesMedidas->getNombre(); ?>
		</div>				

		<div data-linea="2">
			<label for="codigo">Símbolo: </label>
				<?php echo $this->modeloUnidadesMedidas->getCodigo(); ?>
		</div>				

		<div data-linea="3">
			<label for="estado_unidad_medida">Estado: </label>
    			<?php echo $this->modeloUnidadesMedidas->getEstado(); ?>
		</div>
	</fieldset>
</div>

<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>Catalogos' data-opcion='unidadesMedidas/guardar' data-destino="detalleItem" method="post">
	<input type="hidden" id="id_unidad_medida" name="id_unidad_medida" value="<?php echo $this->modeloUnidadesMedidas->getIdUnidadMedida(); ?>" />
	<input type="hidden" id="clasificacion" name="clasificacion" value="CRIA_UMED" />
	
	<fieldset>
		<legend>Unidades de Medida</legend>				

		<div data-linea="1">
			<label for="nombre">Nombre de Unidad de Medida:</label>
			<input type="text" id="nombre" name="nombre" value="<?php echo $this->modeloUnidadesMedidas->getNombre(); ?>" required maxlength="128" />
		</div>				

		<div data-linea="2">
			<label for="codigo">Símbolo: </label>
			<input type="text" id="codigo" name="codigo" value="<?php echo $this->modeloUnidadesMedidas->getCodigo(); ?>" required maxlength="8" />
		</div>				

		<div data-linea="3">
			<label for="estado_unidad_medida">Estado: </label>
			<select id="estado_unidad_medida" name="estado" required>
                <?php
                    echo $this->comboActivoInactivo($this->modeloUnidadesMedidas->getEstado());
                ?>
            </select>
		</div>

		<div data-linea="4">
			<button type="submit" class="guardar">Guardar</button>
		</div>
	</fieldset >
</form >

<script type ="text/javascript">
var bandera = <?php echo json_encode($this->unidadEditable); ?>;

	$(document).ready(function() {
		$("#formulario").hide();
		$("#datosVisualizacion").hide();

		if(bandera == 'Editable'){
			$("#formulario").show();
			$("#datosVisualizacion").hide();
		}else{
			$("#formulario").hide();
			$("#datosVisualizacion").show();
		}
		
		construirValidador();
		distribuirLineas();
	 });

	$("#formulario").submit(function (event) {
		event.preventDefault();
		var error = false;
		if (!error) {
			var respuesta = JSON.parse(ejecutarJson($(this)).responseText);
	       	if (respuesta.estado == 'exito'){
	            fn_filtrar();
	       	}
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});
</script>
