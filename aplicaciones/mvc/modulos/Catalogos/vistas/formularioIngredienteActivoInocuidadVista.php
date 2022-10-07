<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>Catalogos' data-opcion='IngredienteActivoInocuidad/guardar' data-destino="detalleItem" method="post">
	<input type="hidden" id="id_ingrediente_activo" name="id_ingrediente_activo" value="<?php echo $this->modeloIngredienteActivoInocuidad->getIdIngredienteActivo(); ?>" />

	<fieldset>
		<legend>Nombre de Componente / Ingrediente Activo</legend>				

		<div data-linea="1" id="area_editable">
			<label for="id_area">Área: </label>
			<select id="id_area" name="id_area" required>
                <?php
                    echo $this->comboAreasRegistroInsumosPecuarios($this->modeloIngredienteActivoInocuidad->getIdArea());
                ?>
            </select>
		</div>
		
		<div data-linea="2">
			<label for="ingrediente_activo">Nombre del Componente </label>
			<input type="text" id="ingrediente_activo" name="ingrediente_activo" value="<?php echo $this->modeloIngredienteActivoInocuidad->getIngredienteActivo(); ?>" required maxlength="1024" />
		</div>				

		<div data-linea="3">
			<label for="estado_ingrediente_activo">Estado: </label>
			<select id="estado_ingrediente_activo" name="estado_ingrediente_activo" required>
                <?php
                    echo $this->comboActivoInactivo($this->modeloIngredienteActivoInocuidad->getEstadoIngredienteActivo());
                ?>
            </select>
		</div>				

		<div data-linea="10">
			<button type="submit" class="guardar">Guardar</button>
		</div>
	</fieldset >
</form >
<script type="text/javascript">
var bandera = <?php echo json_encode($this->formulario); ?>;

	$(document).ready(function() {
		if(bandera == 'abrir'){
			$("#area_editable").hide();
		}else{
			$("#area_editable").show();
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