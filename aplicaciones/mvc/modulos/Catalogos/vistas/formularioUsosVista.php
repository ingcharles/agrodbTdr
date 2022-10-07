<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>Catalogos' data-opcion='usos/guardar' data-destino="detalleItem" method="post">
	<input type="hidden" id="id_uso" name="id_uso" value="<?php echo $this->modeloUsos->getIdUso(); ?>" />

	<fieldset>
		<legend>Usos</legend>				

		<div data-linea="1" id="area_editable">
			<label for="id_area">Área: </label>
			<select id="id_area" name="id_area" required>
                <?php
                echo $this->comboAreasRegistroInsumosPecuarios($this->modeloUsos->getIdArea());
                ?>
            </select>
		</div>
		
		<div data-linea="2">
			<label for="nombre_uso">Nombre científico / Uso pecuario: </label>
			<input type="text" id="nombre_uso" name="nombre_uso" value="<?php echo $this->modeloUsos->getNombreUso(); ?>" required maxlength="64" />
		</div>				

		<div data-linea="3">
			<label for="nombre_comun_uso">Nombre común: </label>
			<input type="text" id="nombre_comun_uso" name="nombre_comun_uso" value="<?php echo $this->modeloUsos->getNombreComunUso(); ?>" maxlength="64" />
		</div>							

		<div data-linea="4">
			<label for="estado_uso">Estado: </label>
			<select id="estado_uso" name="estado_uso" required>
                <?php
                    echo $this->comboActivoInactivo($this->modeloUsos->getEstadoUso());
                ?>
            </select>
		</div>

		<div data-linea="9">
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