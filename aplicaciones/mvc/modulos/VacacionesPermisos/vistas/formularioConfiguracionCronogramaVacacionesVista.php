<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

<?php echo $this->configuracionCronogramaVacacion;?>

<script type ="text/javascript">
	$(document).ready(function() {
		construirValidador();
		distribuirLineas();
	 });

	$("#formulario").submit(function (event) {
		event.preventDefault();
		var error = false;
		
		$('#formulario .validacion').each(function (i, obj) {
            if (!$.trim($(this).val())) {
                error = true;
                $(this).addClass("alertaCombo");
            }
        });

		if (!error) {
			JSON.parse(ejecutarJson($("#formulario")).responseText);
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});
</script>
