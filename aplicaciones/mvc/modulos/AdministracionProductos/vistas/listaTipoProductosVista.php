<header>
	<nav><?php echo $this->crearAccionBotones();?></nav>
</header>

<div id="LT">
	<h2>Laboratorios</h2>
	<?php echo $this->article;?>
</div>

<script>
	$(document).ready(function () {
		$("#listadoItems").addClass("comunes");
	});

	// Función para filtrar
	function filtrarFormulario(valor) {
		if(valor == 'refrescar'){
			$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí una solicitud para revisarla.</div>');
		}
	}

</script>
