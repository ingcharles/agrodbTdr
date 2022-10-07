<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAreas.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorProgramacionPresupuestaria.php';

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
		
$area = pg_fetch_assoc($ca->areaUsuario($conexion, $_SESSION['usuario']));

$_SESSION['id_area'] = $area['id_area'];
$areaRevisor = $area['id_area'];
?>

<header>
	<h1>Reporte Matriz PAC</h1>
	<nav>
		<form id="filtrar" data-rutaAplicacion="programacionAnualPresupuestaria" data-opcion="guardarNuevaPlanificacionAnual" data-destino="detalleItem" action="aplicaciones/programacionAnualPresupuestaria/reporteMatrizPacDetalle.php" target="_blank" method="post"> 
		
			<input type='hidden' id='opcion' name='opcion' />
			
			<table class="filtro">
				<tr>
					<td>Coordinación/ Dirección:</td>
					<td style="width: 48%;">						
						<select id="areaN2" name="areaN2" >
							<option value="">Seleccione</option>
							<option value="">Todos</option>
							<option value="DGAF-DGATH-DTIC-DGDA-DGAJ-DGPGE-DCS">Fortalecimiento Institucional</option>
							<?php 
								$areasN2 = $ca->buscarEstructuraPlantaCentralProvincias($conexion);
						
								while($fila = pg_fetch_assoc($areasN2)){
									echo '<option value="' . $fila['id_programa'] . '" data-codigo-programa="' . $fila['codigo'] . '">' . $fila['nombre'].' </option>';
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
					<td>Provincia:</td>
					<td>
						<select id="provincia" name="provincia" required="required">
							<option value="">Provincia....</option>
							<option value="">Todos</option>
								<?php 	
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
					
						<input type="hidden" id="idProvincia" name="idProvincia" value="<?php echo $idProvincia;?>"/>
						<input type="hidden" id="nombreProvincia" name="nombreProvincia" value="<?php echo $nombreProvincia;?>"/>
					</td>
				
				<td></td>
				<td></td>
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
		$("#fechaInicio").datepicker();
		$("#fechaFin").datepicker();
	});

	$("#objetivoEstrategico").change(function (event) {
		$("#idObjetivoEstrategico").val($("#objetivoEstrategico option:selected").val());
		$("#nombreObjetivoEstrategico").val($("#objetivoEstrategico option:selected").text());
	});
	
	$("#areaN2").change(function (event) {
		$("#nombreAreaN2").val($("#areaN2 option:selected").text());
		
		if($("#areaN2 option:selected").val() != ''){
			$("#filtrar").attr('data-opcion', 'combosPlanificacionAnual');
		    $("#filtrar").attr('data-destino', 'dN4Reporte');
		    $("#opcion").val('n4ReportePresupuesto');
	
		    abrir($("#filtrar"), event, false); //Se ejecuta ajax
		}
	});

	$("#programasReporte").change(function (event) {
		$("#idProgramaPAC").val($("#programasReporte option:selected").val());
		$("#codigoProgramaPAC").val($("#programasReporte option:selected").attr('data-codigo-programa'));
		$("#nombreProgramaPAC").val($("#programasReporte option:selected").text());
		
		$("#filtrar").attr('data-opcion', 'combosPlanificacionAnual');
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