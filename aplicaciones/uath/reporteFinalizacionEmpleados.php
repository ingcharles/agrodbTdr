<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastro.php';
require_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion();
$ce = new ControladorCatastro();
$cc = new ControladorCatalogos();

$grupoOcupacional =$ce->obtenerGrupoOcupacional($conexion);
$cantones= $cc->listarSitiosLocalizacion($conexion,'CANTONES');
$oficinas = $cc->listarSitiosLocalizacion($conexion,'SITIOS');

$regimenLaboral =$cc->obtenerRegimenLaboral($conexion);
//$grupoOcupacional =$ce->obtenerGrupoOcupacional($conexion);

$obtenerPuestos=$cc->obtenerPuestos($conexion);

?>

<header>
	<h1>Reporte Consolidado Contratos Nacional</h1>
	<nav>
	<form id="reporteContratos" data-rutaAplicacion="uath" action="aplicaciones/uath/listaContratosFiltrados.php" target="_blank" method="post" >
		<!-- action="aplicaciones/uath/listaContratosFiltrados.php" target="_blank" method="post" -->
		<table class="filtro">
			<tr>
				<th>Régimen Laboral</th>
				<td>
					<select name="regimen_laboral" id="regimen_laboral" style=" width:100%">
							<option value="">Seleccione....</option>
							<?php 	
								while($regimen = pg_fetch_assoc($regimenLaboral)){
									echo '<option value="' . $regimen['nombre'] . '">' . $regimen['nombre'] . '</option>';
								}
							?>
					</select>
				</td>	
				
				<td></td>
				
				<th>
					Provincia
				</th>
				
				<td>
					<select id="provincia" name="provincia" style=" width:100%">
						<option value="">Provincia....</option>
							<?php 	
								$provincias = $cc->listarSitiosLocalizacion($conexion,'PROVINCIAS');
								foreach ($provincias as $provincia){
									echo '<option value="' . $provincia['nombre'] . '" data-codigo="' . $provincia['codigo'] . '">' . $provincia['nombre'] . '</option>';
								}
							?>
					</select>
				</td>
			</tr>
				
				
			<tr>
				<th>Fecha Inicio:</th>
				<td><input type="text" name="fechaInicio" id="fechaInicio" style=" width:100%"/></td>
				<td></td>
				<th>Fecha Fin:</th>
				<td><input type="text" name="fechaFin" id="fechaFin" style=" width:100%"/></td>
			</tr>
				
			<tr>
				<th>Estado</th>
				<td>
					<select name="estado" id="estado" style=" width:100%">
						<option value="">Todos</option>
						<option value="2">Caducado</option>
						<option value="3">Finalizado</option>
						<option value="4">Inactivo</option>
						<option value="1">Vigente</option>
					</select>
				</td>
				
			</tr>
			
			<tr>
				<td colspan="5"><button>Buscar</button></td>
			</tr>
		</table>
		</form>
		
	</nav>
</header>
<div id="tabla"></div>
<script>

	$(document).ready(function(){
		
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");
		$("#detalleItem").html('<div class="mensajeInicial">Presione "Buscar" para generar el reporte.</div>');

		$("#fechaInicio").datepicker({
		      changeMonth: true,
		      changeYear: true
		    });

		$("#fechaFin").datepicker({
		      changeMonth: true,
		      changeYear: true
		    });
	});


	$("#reporteContratos").submit(function(event){
		$(this).submit();
	});

</script>