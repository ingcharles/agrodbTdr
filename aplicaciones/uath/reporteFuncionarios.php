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

?>

<header>
	<h1>Reporte Consolidado de Funcionarios Nacional</h1>
	<nav>
	<form id="reporteFuncionario" data-rutaAplicacion="uath" action="aplicaciones/uath/listaFuncionariosFiltrados.php" target="_blank" method="post" >
		<!-- action="aplicaciones/uath/listaContratosFiltrados.php" target="_blank" method="post" -->
		<table class="filtro">
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
				
				<th>Identificación Étnica</th>
				<td>
					<select name="identificacion_etnica" id="identificacion_etnica" style=" width:100%">
						<option value="">Seleccione....</option>
						<option value="Afroecuatoriano">Afroecuatoriano</option>
						<option value="Blanco">Blanco</option>
						<option value="Indigena">Indigena</option>
						<option value="Mestizo">Mestizo</option>
						<option value="Montubio">Montubio</option>
						<option value="Mulato">Mulato</option>
						<option value="Negro">Negro</option>
						<option value="Otros">Otros</option>
					</select>
				</td>	
								
			</tr>
			<tr>
				<th>Género</th>
				<td>
					<select name="genero" id="genero" style=" width:100%">
						<option value="">Seleccione....</option>
						<option value="Femenino">Femenino</option>
						<option value="Masculino">Masculino</option>
					</select>
				</td>	
				
				<td></td>

				<th>Estado Civil</th>
				<td>
					<select name="estado_civil" id="estado_civil" style=" width:100%">
						<option value="">Seleccione....</option>
						<option value="Casado(a)">Casado(a)</option>
						<option value="Soltero(a)">Soltero(a)</option>
						<option value="Divorciado(a)">Divorciado(a)</option>
						<option value="Viudo(a)">Viudo(a)</option>
						<option value="UnionLibre">Unión libre</option>
					</select>
				</td>	
				
			</tr><tr>
				<th>Discapacidad</th>
				<td>
					<select name="discapacidad" id="discapacidad" style=" width:100%">
						<option value="">Seleccione....</option>
						<option value="SI">Si</option>
						<option value="NO">No</option>
					</select>
				</td>	
				
				<td></td>

				<th></th>
				<td>
					
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


	$("#reporteFuncionario").submit(function(event){
		$(this).submit();
	});

</script>