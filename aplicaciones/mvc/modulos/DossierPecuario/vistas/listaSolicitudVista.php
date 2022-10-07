<header>
	<nav><?php echo $this->crearAccionBotones();?></nav>
</header>

<div id="article"><?php echo $this->article; ?></div>

<script>
    $(document).ready(function () {
    	$("#listadoItems").addClass("comunes");
    	$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí una solicitud para revisarla.</div>');		
    });
</script>