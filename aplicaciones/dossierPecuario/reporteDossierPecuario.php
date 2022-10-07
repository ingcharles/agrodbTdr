<?php
session_start();


?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>
	<header> </header>
	<div>
		<h2>Reporte de estados de solicitudes de registros de insumos pecuarios</h2>
	</div>

	<form id='reporteProductosInocuidad'
		action="aplicaciones/dossierPecuario/reporteEstadosPecuarios.php"
		data-rutaAplicacion='dossierPecuario' target="_blank" method="post">
		<div style="text-align: center;">
			<button type="submit">Generar reporte</button>
		</div>
	</form>

	<div>
		<h2>Reporte gerencial</h2>
	</div>
	<form id='reporteProductosVeterinarios'
		action="aplicaciones/dossierPecuario/reporteGerencialPecuario.php"
		data-rutaAplicacion='dossierPecuario' target="_blank" method="post">
		<div style="text-align: center;">
			<button type="submit">Generar reporte</button>
		</div>
	</form>

	
</body>
<script>
	$(document).ready(function(){

		$("#detalleItem").html('<div class="mensajeInicial">Reportes del dossier pecuario.</div>');
	});
</script>
</html>
