<?php echo $this->descripcionConfiguracionCronogramaVacaciones; ?>

<script type="text/javascript">
	$(document).ready(function() {
		$("#estado").html("").removeClass('alerta');
		construirValidador();
		distribuirLineas();
	});

	$("#formEnviarDe").submit(function(event) {
		event.preventDefault();
		var error = false;
		$(".alertaCombo").removeClass("alertaCombo");

		$('#formEnviarDe .validacion').each(function(i, obj) {
			if (!$.trim($(this).val())) {
				error = true;
				$(this).addClass("alertaCombo");
			}
		});

		if (!error) {
			$("#cargarMensajeTemporal").html("<div id='cargando' style='position :fixed'>Cargando...</div>").fadeIn();
			setTimeout(function() {
				var respuesta = JSON.parse(ejecutarJson($("#formEnviarDe")).responseText);
			}, 1000);

		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});
</script>