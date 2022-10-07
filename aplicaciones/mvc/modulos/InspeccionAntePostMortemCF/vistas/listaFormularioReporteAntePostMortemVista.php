<header><nav></nav></header>

	<div>
		<h1>Formularios aprobados</h1>
		<div class="elementos"></div>
	</div>
	
<?php echo $this->article;?>

<script>
	$(document).ready(function () {
		$("#listadoItems").addClass("comunes");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui una operación para revisarla.</div>');
	 });
</script>
