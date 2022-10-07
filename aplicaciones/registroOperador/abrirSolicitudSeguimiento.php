<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorCatalogos.php';
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>

<header>
	<img src='aplicaciones/general/img/encabezado.png'>
	<h1>Detalle solicitud</h1>
</header>

<?php


	$conexion = new Conexion();
	$cr = new ControladorRegistroOperador();
	$cc = new ControladorCatalogos();
	
	$qSolicitud = $cr->abrirSolicitud($conexion, $_SESSION['usuario'], $_POST['id']);
	
	$reporte= ($_POST['valoresFiltrados']);

	
	echo'<table class="soloImpresion">
				<tr>';
?>
</body>
<script type="text/javascript">
</script>
</html>
