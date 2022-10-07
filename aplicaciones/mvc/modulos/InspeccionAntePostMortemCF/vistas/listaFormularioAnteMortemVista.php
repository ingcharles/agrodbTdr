<header><nav><?php echo $this->botones;?></nav></header>
<script src="<?php echo URL ?>modulos/InspeccionAntePostMortemCF/vistas/js/funcionCf.js"></script>
<br>
	<h1><?php echo $this->nombreCF; ?></h1>
    <div >
	    <h1><?php echo $this->detalleFormulario; ?></h1>
		<div class="elementos"></div>
	</div>
	<?php echo $this->article;?>
	
<script>
	$(document).ready(function () {
	    $("#listadoItems").addClass("comunes"); 
	    $("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui una operación para revisarla.</div>');
	    });
</script>
