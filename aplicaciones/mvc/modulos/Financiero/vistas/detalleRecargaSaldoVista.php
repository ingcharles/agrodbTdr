<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

<?php echo $this->cabeceraRecargaSaldo;?>
<?php echo $this->detalleRecargaSaldo;?>

<script type="text/javascript">
	$(document).ready(function() {
		distribuirLineas();
	});
</script>