<header>
	<h1><?php echo $this->accion; $anioConsolidado = date("Y") + 1;?></h1>
</header>
<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>VacacionesPermisos' data-opcion='consolidadocronogramavacaciones/guardar' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR" method="post">
<input type="hidden" name="anio_consolidado_cronograma_vacacion" id="anio_consolidado_cronograma_vacacion" value="<?php echo $anioConsolidado; ?>" >
	<fieldset>
		<legend>Datos generales</legend>
		<div data-linea="1">
			<label>Año: </label><?php echo $anioConsolidado; ?>
		</div>		
	</fieldset>

	<?php echo $this->resumenCronogramaVacacion; ?>

	<div data-linea="10">
		<button type="submit" class="guardar">Guardar</button>
	</div>
	
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
			JSON.parse(ejecutarJson($("#formulario")).responseText);
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});
</script>
