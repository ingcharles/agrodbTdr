<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();

$localizacion = $cc->listarLocalizacion($conexion, 'SITIOS');
?>

<header>
	<h1>Reseteo de Cupo de Combutible de Gasolineras - Nacional</h1>
	<nav>
	<form id="listaGasolinerasNacional" data-rutaAplicacion="transportes" data-opcion="listaGasolinerasNacionalFiltrado" data-destino="tabla">
		<table class="filtro">
			<tr>
				<td>Localización:</td>
				
				<td>
					<select id="localizacion" name="localizacion">
						<option value="">Seleccione....</option>
						<?php 
							while($fila = pg_fetch_assoc($localizacion)){
									//if(strstr($fila['nombre'], 'Coordinación') || strstr($fila['nombre'], 'Oficina') || strstr($fila['nombre'], 'Oficina Planta Central') || strstr($fila['nombre'], 'Laboratorios tumbaco')){
										echo '<option value="' . $fila['nombre'] . '">' . $fila['nombre'] . '</option>';
									//}
								}
						?>
						
					</select>
				</td>
			</tr>
			<tr>	
				<td>Gasolineras:</td>
				
				<td>
					<div id="dGasolineras">
						<select id="gasolinera" name="gasolinera" required>
							<option value="">Seleccione....</option>
						</select>
					</div>
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
	$("#listaGasolinerasNacional").submit(function(e){
		$("#listaGasolinerasNacional").attr('data-opcion', 'listaGasolinerasNacionalFiltrado');
	    $("#listaGasolinerasNacional").attr('data-destino', 'tabla');

	    abrir($(this),e,false);
	});
	
	$(document).ready(function(){
		$("#listadoItems").addClass("lista");
		$("#detalleItem").html('<div class="mensajeInicial">Seleccione una orden para visualizar.</div>');
	});

	$("#localizacion").change(function (event) {
		$("#listaGasolinerasNacional").attr('data-opcion', 'combosGasolineras');
	    $("#listaGasolinerasNacional").attr('data-destino', 'dGasolineras');

	    abrir($("#listaGasolinerasNacional"), event, false); //Se ejecuta ajax
	});
</script>
