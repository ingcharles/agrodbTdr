<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAreas.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorProgramacionPresupuestaria.php';
require_once '../../clases/ControladorReformaPresupuestaria.php';

$fecha = getdate();
$anio = $fecha['year'];

$identificador=$_SESSION['usuario'];

if($identificador==''){
	$usuario=0;
}else{
	$usuario=1;
	$idAreaFuncionario = $_SESSION['idArea'];
	$nombreProvinciaFuncionario = $_SESSION['nombreProvincia'];
}//$usuario=0;

$conexion = new Conexion();
$ca = new ControladorAreas();
$cc = new ControladorCatalogos();
$cpp = new ControladorProgramacionPresupuestaria();
$crp = new ControladorReformaPresupuestaria();
		
$area = pg_fetch_assoc($ca->areaUsuario($conexion, $_SESSION['usuario']));

$_SESSION['id_area'] = $area['id_area'];
$areaRevisor = $area['id_area'];
?>

<header>
	<h1>Reporte Reforma Presupuestaria por Partidas</h1>
	<nav>
		<form id="filtrar" data-rutaAplicacion="reformaPresupuestaria" data-opcion="guardarNuevaPlanificacionAnual" data-destino="detalleItem" action="aplicaciones/reformaPresupuestaria/reporteReformaPresupuestariaPartidaTemporalDetalle.php" target="_blank" method="post"> 
		
			<input type='hidden' id='opcion' name='opcion' />
			
			<table class="filtro">
				<tr>
					<td>Ejercicio:</td>
					<td style="width: 48%;">
							<select id=anio name="anio" required="required">
								<option value="">Seleccione....</option>
								<?php 
									$anios = $crp->listarAniosPapPacRefPres($conexion);

									while($fila = pg_fetch_assoc($anios)){
										echo '<option value="' . $fila['anio'] . '">' . $fila['anio'].' </option>';
									}
								?>
							</select>
					</td>	
				</tr>
				
				<tr>
					<td>Coordinación/ Dirección:</td>
					<td>						
						<select id="areaN2" name="areaN2" required="required" >
							<option value="">Seleccione</option>
							<!-- option value="">Todos</option-->
							<?php 
								$areasN2 = $ca->buscarEstructuraPlantaCentralProvincias($conexion);
						
								while($fila = pg_fetch_assoc($areasN2)){
									echo '<option value="' . $fila['id_area'] . '">' . $fila['nombre'].' </option>';
								}
							?>
						</select>
						
						<input type='hidden' id='nombreAreaN2' name='nombreAreaN2' />			
					</td>
					
					<td>Programa:</td>
					<td>	
						<div id="dProgramas">					
							<select id="programasReporte" name="programasReporte" >
								<option value="">Seleccione</option>
								<option value="">Todos</option>
								<?php 
								$programas = $cpp->listarProgramas($conexion);
						
								while($fila = pg_fetch_assoc($programas)){
									echo '<option value="' . $fila['id_programa'] .'" data-codigo-programa="' . $fila['codigo'].'">' . $fila['nombre'].' </option>';
								}
							?>
							</select>
						</div>	
						
						<input type="hidden" id="idProgramaPAC" name="idProgramaPAC" />
						<input type="hidden" id="codigoProgramaPAC" name="codigoProgramaPAC" />
						<input type="hidden" id="nombreProgramaPAC" name="nombreProgramaPAC" />			
					</td>
					
				</tr>
				<tr>
					<td>Proyecto:</td>
					<td>	
						<div id="dProyectoReporte">					
							<select id="proyectoReporte" name="proyectoReporte" >
								<option value="">Seleccione</option>
								<option value="">Todos</option>
							</select>
						</div>				
					</td>
					
					<td>Actividad:</td>
					<td>	
						<div id="dActividadReporte">					
							<select id="actividadReporte" name="actividadReporte" >
								<option value="">Seleccione</option>
								<option value="">Todos</option>
							</select>
						</div>				
					</td>
				
				</tr>
				
				<tr>					
					<!-- >td>Provincia:</td>
					<td>
						<select id="provincia" name="provincia" required="required">
							<option value="">Provincia....</option>
							<option value="">Todos</option>
								< ?php 	
									$provincias = $cc->listarSitiosLocalizacion($conexion,'PROVINCIAS');
									foreach ($provincias as $provincia){
										if($provincia['nombre'] == $_SESSION['nombreProvincia']){
											echo '<option value="' . $provincia['codigo'] . '" selected>' . $provincia['nombre'] . '</option>';
											$idProvincia = $provincia['codigo'];
											$nombreProvincia = $provincia['nombre'];
										}else{
											echo '<option value="' . $provincia['codigo'] . '" >' . $provincia['nombre'] . '</option>';
										}
									}
								?>
						</select> 
					
						<input type="hidden" id="idProvincia" name="idProvincia" value="< ?php echo $idProvincia;?>"/>
						<input type="hidden" id="nombreProvincia" name="nombreProvincia" value="< ?php echo $nombreProvincia;?>"/>
					</td-->
				
				<td>Estado:</td>
					<td>	
						<div id="dEstadoReporte">					
							<select id="estadoReporte" name="estadoReporte" required="required" >
								<option value="">Seleccione</option>
								<option value="enviadoRevisorGA">Enviado a Revisión GA</option>
								<option value="revisadoGA">Revisado GA</option>
								<option value="enviadoRevisorGF">Enviado a Revisión GF</option>
							</select>
						</div>				
					</td>
				</tr>
							
				<tr>
					<th></th>
					<td colspan="5"><button>Generar Reporte</button></td>
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
		$("#detalleItem").html('<div class="mensajeInicial">Seleccione las opciones de búsqueda para generar el reporte.</div>');
	});

	$("#programasReporte").change(function (event) {
		$("#idProgramaPAC").val($("#programasReporte option:selected").val());
		$("#codigoProgramaPAC").val($("#programasReporte option:selected").attr('data-codigo-programa'));
		$("#nombreProgramaPAC").val($("#programasReporte option:selected").text());
		
		$("#filtrar").attr('data-opcion', 'combosReformaPlanificacionAnual');
	    $("#filtrar").attr('data-destino', 'dProyectoReporte');
	    $("#opcion").val('proyectoReportePAC');
	    		
		if($("#programasReporte option:selected").val() != ''){
			abrir($("#filtrar"), event, false); //Se ejecuta ajax
		}
	});

	$("#provincia").change(function(){
		$('#idProvincia').val($("#provincia option:selected").val());
		$('#nombreProvincia').val($("#provincia option:selected").text());
	});
</script>