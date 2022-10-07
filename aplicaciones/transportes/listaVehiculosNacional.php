<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVehiculos.php';

$conexion = new Conexion();
$cv = new ControladorVehiculos();

?>

<header>
	<h1>Liberación de Vehículos</h1>
	<nav>
	<form id="listaLiberarVehiculo" data-rutaAplicacion="transportes" data-opcion="listaVehiculosNacionalFiltrado" data-destino="tabla">
		<table class="filtro">
			<tr>
				<th>Vehículo</th>

				<td>placa:</td>
				
				<td>
					<input type="text" id="placa" name="placa" />
				</td>
					</tr>

			<tr>
				<td colspan="5"><button>Filtrar lista</button></td>
			</tr>
		</table>
		</form>
		
	</nav>
</header>

<div id="tabla"></div>
<script>
	$("#listaLiberarVehiculo").submit(function(e){
		abrir($(this),e,false);
	});
	
	$(document).ready(function(){
		$("#listadoItems").addClass("lista");
		$("#detalleItem").html('<div class="mensajeInicial">Seleccione un vehículo para liberar.</div>');
	});
</script>
