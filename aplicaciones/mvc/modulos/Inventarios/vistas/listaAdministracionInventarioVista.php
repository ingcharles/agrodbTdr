<header>
	<nav><?php echo $this->crearAccionBotones();?></nav>
</header>
<?php 

    foreach ($this->itemsFiltrados as $items){
        echo $items;
    }
?>

<script type="text/javascript">

$(document).ready(function(){
	$("#listadoItems").addClass("comunes");
	$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');
});

</script>
