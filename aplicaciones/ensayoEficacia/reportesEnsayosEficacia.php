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
		<h2>Reporte de estados de solicitudes de ensayos de eficacia</h2>
	</div>

	<form id='reporteProductosInocuidad'
		action="aplicaciones/ensayoEficacia/reporteEstadosEnsayos.php"
		data-rutaAplicacion='ensayoEficacia' target="_blank" method="post">
		<div style="text-align: center;">
			<button type="submit">Generar reporte</button>
		</div>
	</form>

	

	<div>
		<h2>Reporte gerencial de ensayos de eficacia</h2>
	</div>
	<form id='reporteProductosVeterinarios'
		action="aplicaciones/ensayoEficacia/reporteGerencialEnsayos.php"
		data-rutaAplicacion='ensayoEficacia' target="_blank" method="post">
		<div style="text-align: center;">
			<button type="submit">Generar reporte</button>
		</div>
	</form>

	<div>
		<h2>Reporte de estados de solicitudes de informes finales</h2>
	</div>
	<form id='reporteInformesFinales'
		action="aplicaciones/ensayoEficacia/reporteEstadosInformes.php"
		data-rutaAplicacion='ensayoEficacia' target="_blank" method="post">
		<div style="text-align: center;">
			<button type="submit">Generar reporte</button>
		</div>
	</form>

	

	<div>
		<h2>Reporte gerencial de informes finales</h2>
	</div>
	<form id='reporteInformesFinalesGerencia'
		action="aplicaciones/ensayoEficacia/reporteGerencialInformes.php"
		data-rutaAplicacion='ensayoEficacia' target="_blank" method="post">
		<div style="text-align: center;">
			<button type="submit">Generar reporte</button>
		</div>
	</form>

	
</body>
<script>
	$(document).ready(function(){

		$("#detalleItem").html('<div class="mensajeInicial">Reportes de ensayos de eficacia.</div>');
	});
</script>
</html>
