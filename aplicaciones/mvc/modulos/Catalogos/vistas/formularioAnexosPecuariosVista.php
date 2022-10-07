<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>Catalogos' data-opcion='AnexosPecuarios/guardar' data-destino="detalleItem" method="post">
	<input type="hidden" id="id_anexo_pecuario" name="id_anexo_pecuario" value="<?php echo $this->modeloAnexosPecuarios->getIdAnexoPecuario(); ?>" />

	<fieldset>
		<legend>Anexos Pecuarios</legend>				

		<div data-linea="1">
			<label for="anexo_pecuario">Documento Anexo: </label>
			<input type="text" id="anexo_pecuario" name="anexo_pecuario" value="<?php echo $this->modeloAnexosPecuarios->getAnexoPecuario(); ?>" required maxlength="512" />
		</div>				

		<div data-linea="2">
			<label for="id_grupo_producto">Grupo de Producto: </label>
			<select id="id_grupo_producto" name="id_grupo_producto" required>
				<option value="">Seleccione....</option>
                <?php
                    echo $this->comboGrupoProducto($this->modeloAnexosPecuarios->getIdGrupoProducto());
                ?>
            </select>
			
			<input type="hidden" id="grupo_producto" name="grupo_producto" value="<?php echo $this->modeloAnexosPecuarios->getGrupoProducto(); ?>" maxlength="64" />
		</div>				

		<div data-linea="4">
			<label for="proceso_revision">Proceso de Revisión: </label>
			<select id="proceso_revision" name="proceso_revision" required>
				<option value="">Seleccione....</option>
                <?php
                    echo $this->comboProcesosRevisionDossierPecuario($this->modeloAnexosPecuarios->getProcesoRevision());
                ?>
            </select>
		</div>				

		<div data-linea="5">
			<label for="estado_anexo_pecuario">Estado: </label>
			<select id="estado_anexo_pecuario" name="estado_anexo_pecuario" required>
                <?php
                    echo $this->comboActivoInactivo($this->modeloAnexosPecuarios->getEstadoAnexoPecuario());
                ?>
            </select>
		</div>

		<div data-linea="6">
			<button type="submit" class="guardar">Guardar</button>
		</div>
	</fieldset >
</form >
<script type="text/javascript">
	$(document).ready(function() {
		construirValidador();
		distribuirLineas();
	 });

	$('#id_grupo_producto').change(function(event){
		if($("#id_grupo_producto").val() != ""){
			$("#grupo_producto").val($("#id_grupo_producto option:selected").text());
		}else{
			$("#grupo_producto").val('');
		}
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