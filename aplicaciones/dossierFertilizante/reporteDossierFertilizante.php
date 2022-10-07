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
		<h2>Reporte de estados de solicitudes de dossier fertilizantes</h2>
	</div>

	<form id='reporteProductosInocuidad'
		action="aplicaciones/dossierFertilizante/reporteEstadosFertilizantes.php"
		data-rutaAplicacion='dossierFertilizante' target="_blank" method="post">
		<div style="text-align: center;">
			<button type="submit">Generar reporte</button>
		</div>
	</form>

	<div>
		<h2>Reporte gerencial</h2>
	</div>
	<form id='reporteProductosVeterinarios'
		action="aplicaciones/dossierFertilizante/reporteGerencialFertilizantes.php"
		data-rutaAplicacion='dossierFertilizante' target="_blank" method="post">
		<div style="text-align: center;">
			<button type="submit">Generar reporte</button>
		</div>
	</form>

	
</body>
<script>
	$(document).ready(function(){

		$("#detalleItem").html('<div class="mensajeInicial">Reportes del dossier de fertilizantes.</div>');
	});
</script>
</html>
