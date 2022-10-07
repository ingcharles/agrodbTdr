<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>Catalogos' data-opcion='tipoComponente/guardar' data-destino="detalleItem" method="post">
	<input type="hidden" id="id_tipo_componente" name="id_tipo_componente" value="<?php echo $this->modeloTipoComponente->getIdTipoComponente(); ?>" />
			
	<fieldset>
		<legend>Tipo de Componente</legend>				

		<div data-linea="1">
			<label for="tipo_componente">Tipo de Componente: </label>
			<input type="text" id="tipo_componente" name="tipo_componente" value="<?php echo $this->modeloTipoComponente->getTipoComponente(); ?>" required maxlength="128" />
		</div>				
		
		<div data-linea="2">
			<label for="id_area">Área: </label>
			<select id="id_area" name="id_area" required>
                <?php
                    echo $this->comboAreasRegistroInsumosAgropecuarios($this->modeloTipoComponente->getIdArea());
                ?>
            </select>
		</div>

		<div data-linea="3">
			<label for="estado_tipo_componente">Estado: </label>
			<select id="estado_tipo_componente" name="estado_tipo_componente" required>
                <?php
                    echo $this->comboActivoInactivo($this->modeloTipoComponente->getEstadoTipoComponente());
                ?>
            </select>
		</div>

		<div data-linea="4">
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
			var respuesta = JSON.parse(ejecutarJson($(this)).responseText);
	       	if (respuesta.estado == 'exito'){
	            fn_filtrar();
	       	}
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});
</script>
