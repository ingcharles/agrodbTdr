<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>SeguimientoDocumental' data-opcion='ventanillas/guardar' data-destino="detalleItem" method="post">

<input type="hidden" id="id_ventanilla" name="id_ventanilla" value="<?php echo $this->modeloVentanillas->getIdVentanilla(); ?>" />


	<fieldset>
		<legend>Datos de la Ventanilla</legend>				

		<div data-linea="1">
			<label for="nombre">Ventanilla: </label>
			<input type="text" id="nombre" name="nombre" value="<?php echo $this->modeloVentanillas->getNombre(); ?>"
			placeholder="Nombre de la ventanilla" required maxlength="512" />
		</div>
		
		<div data-linea="2">
			<label for="id_unidad_destino">Unidad Asignada: </label>
			<select id="id_unidad_destino" name="id_unidad_destino" required>
                <option value="">Seleccionar....</option>
                <?php
                echo $this->comboAreasCategoriaNacional($this->modeloVentanillas->getIdUnidadDestino());
                ?>
            </select>
		</div>

		<div data-linea="2">
			<label for="codigo_ventanilla">Siglas  Asignadas: </label>
			<input type="text" id="codigo_ventanilla" name="codigo_ventanilla" value="<?php echo $this->modeloVentanillas->getCodigoVentanilla(); ?>"
			placeholder="Código de la ventanilla" required="required" maxlength="4" <?php echo $this->formulario=="abrir"?'readonly="readonly"':""; ?> />
		</div>				

		<div data-linea="3">
			<label for="id_provincia">Provincia: </label>
			<select id="id_provincia" name="id_provincia" required <?php echo $this->formulario=="abrir"?'disabled':""; ?>>
                <option value="">Seleccionar....</option>
                <?php
                echo $this->comboProvinciasEc($this->modeloVentanillas->getIdProvincia());
                ?>
            </select>

		</div>

		<div data-linea="3">
			<label for="estado_ventanilla">Activado: </label>
			<select id="estado_ventanilla" name="estado_ventanilla" required>
                <?php
                    echo $this->comboActivoInactivo($this->modeloVentanillas->getEstadoVentanilla());
                ?>
            </select>
		</div>

		<div data-linea="9">
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