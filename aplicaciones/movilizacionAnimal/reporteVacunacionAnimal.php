<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacunacionAnimal.php';
$conexion = new Conexion();
$vdr = new ControladorVacunacionAnimal();

?>
<header>
	<h1>Reportes de registro de vacunación animal</h1>
	<nav>
		<form id="filtrarVacunacionAnimal" action="aplicaciones/movilizacionAnimal/reporteImprimirVacunaAnimal.php" target="_blank" method="post">			
			<button type="submit" class="guardar">Generar reporte</button> 
		</form>
	</nav>
</header>
<script>
	$(document).ready(function(){
		$("#listadoItems").addClass("comunes");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui el reporte para revisarlo.</div>');
	});
</script>