<header>
	<h1><?php echo $this->accion;?></h1>
</header>
<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>VacacionesPermisos' data-opcion='RevisionCronogramaVacaciones/guardarEnviarDirectorEjecutivo' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR" method="post">
	<?php echo $this->resumenCronogramaVacacion; ?>
</form >
<script type ="text/javascript">
	$(document).ready(function() {
		mostrarMensaje("", "");
		$(".alertaCombo").removeClass("alertaCombo");
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
