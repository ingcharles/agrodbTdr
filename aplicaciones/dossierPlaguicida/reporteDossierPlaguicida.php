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
		<h2>Reporte de estados de solicitudes de dossier plaguicidas</h2>
	</div>

	<form id='reporteProductosInocuidad'
		action="aplicaciones/dossierPlaguicida/reporteEstadosPlaguicidas.php"
		data-rutaAplicacion='dossierPlaguicida' target="_blank" method="post">
		<div style="text-align: center;">
			<button type="submit">Generar reporte</button>
		</div>
	</form>

	<div>
		<h2>Reporte gerencial</h2>
	</div>
	<form id='reporteProductosVeterinarios'
		action="aplicaciones/dossierPlaguicida/reporteGerencialPlaguicidas.php"
		data-rutaAplicacion='dossierPlaguicida' target="_blank" method="post">
		<div style="text-align: center;">
			<button type="submit">Generar reporte</button>
		</div>
	</form>

	
</body>
<script>
	$(document).ready(function(){

		$("#detalleItem").html('<div class="mensajeInicial">Reportes del dossier plaguicidas.</div>');
	});
</script>
</html>
