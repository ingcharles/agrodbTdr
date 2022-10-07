<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

<iframe id="formularioCreado" width="100%" height="100%"
	src="<?php echo $this->url; ?>" frameborder="0" allowfullscreen></iframe>
<script type="text/javascript">
	$(document).ready(function() {
	    distribuirLineas();
	 });
</script>
