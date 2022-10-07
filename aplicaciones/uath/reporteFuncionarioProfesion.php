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

$titulos = $cc->listarTitulosOCarrera($conexion, 'TITULOS');
$carreras = $cc->listarTitulosOCarrera($conexion, 'CARRERAS');

?>

<header>
	<h1>Reporte Consolidado Funcionarios por Profesión</h1>
	<nav>
	<form id="reporteFuncionarioProfesion" data-rutaAplicacion="uath" action="aplicaciones/uath/listaFuncionarioProfesionFiltrados.php" target="_blank" method="post" >
		<!-- action="aplicaciones/uath/listaContratosFiltrados.php" target="_blank" method="post" -->
		<table class="filtro">
			<tr>
				<th>Título</th>
				<td>
					<select name="titulos" id="titulos" style=" width:100%">
						<option value="">Seleccione....</option>
						<?php 	
							while($titulo = pg_fetch_assoc($titulos)){
								echo '<option value="' . $titulo['titulo_carrera'] . '">' . $titulo['titulo_carrera'] . '</option>';
							}
						?>
					</select>
				</td>	
				
				<td></td>
				
				<th>Carrera</th>
				<td>
					<select name="carrera" id="carrera" style=" width:100%">
						<option value="">Seleccione....</option>
						<?php 	
							while($carrera = pg_fetch_assoc($carreras)){
								echo '<option value="' . $carrera['titulo_carrera'] . '">' . $carrera['titulo_carrera'] . '</option>';
							}
						?>
					</select>
				</td>	
				
			</tr>
			<tr>
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
				
				<td></td>

				<th>Estado</th>
				<td>
					<select name="estado" id="estado" style=" width:100%">
						<option value="">Todos</option>
						<option value="Aceptado">Aceptado</option>
						<option value="Ingresado">Ingresado</option>
						<option value="Modificado">Modificado</option>
						<option value="Rechazado">Rechazado</option>
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


	$("#reporteFuncionarioProfesion").submit(function(event){
		$(this).submit();
	});

</script>