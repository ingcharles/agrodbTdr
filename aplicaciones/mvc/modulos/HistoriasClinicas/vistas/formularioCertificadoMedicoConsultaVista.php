<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

	<fieldset id="divFuncionario">
	<?php echo $this->paciente;?>
    </fieldset>
    <fieldset id="divFirma">
    <?php echo $this->firma;?>
    </fieldset >
    <fieldset id="divAdjunto">
    <?php echo $this->adjunto;?>
    </fieldset >
   
<script type ="text/javascript">
	$(document).ready(function() {
		construirValidador();
		distribuirLineas();
	 });
</script>
