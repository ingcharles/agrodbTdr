<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

<?php echo $this->datosGenerales; ?>

<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>VacacionesPermisos' data-opcion='RevisionCronogramaVacaciones/guardarValidarPeriodo' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR" method="post">
	
	<?php echo $this->periodoCronograma; ?>

</form>

<script type ="text/javascript">
	$(document).ready(function() {
		construirValidador();
		distribuirLineas();
		$("#estado").html("");
		$(".alertaCombo").removeClass("alertaCombo");
	 });

	$("#formulario").submit(function (event) {
		event.preventDefault();
		var error = false;
		$("#estado").html("");
		$(".alertaCombo").removeClass("alertaCombo");

		if ($('input.activo:checked').length == 0){       
			$('input.activo').addClass("alertaCombo");
			var error = true;
		}

		if (!error) {
			setTimeout(function() {
				var respuesta = JSON.parse(ejecutarJson($("#formulario")).responseText);
			}, 1000);
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});
</script>
