<header>
<nav><?php echo $this->crearAccionBotones();?></nav>
</header>
<div class="elementos"></div>

<?php echo $this->article;?>
<script>
	$(document).ready(function () {
		$("#listadoItems").addClass("comunes");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui una operación para revisarla.</div>');
	 });
</script>
