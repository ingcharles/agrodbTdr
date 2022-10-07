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
	<h1>Reporte Matriz PAP</h1>
	<nav>
		<form id="filtrar" data-rutaAplicacion="programacionAnualPresupuestaria" data-opcion="" data-destino="detalleItem" action="aplicaciones/programacionAnualPresupuestaria/reporteMatrizPAPAprobadorDetalle.php" target="_blank" method="post"> 
		
			<input type='hidden' id='opcion' name='opcion' />
			
			<table class="filtro">
				<tr>
					<td>Objetivo Estratégico:</td>
					<td style="width: 48%;">
						<select id=objetivoEstrategico name="objetivoEstrategico" style="width: 40%;">
							<option value="">Seleccione</option>
							<option value="">Todos</option>
							<?php 
								$objetivoEstrategico = $cpp->listarObjetivoEstrategico($conexion);
								
								while($fila = pg_fetch_assoc($objetivoEstrategico)){
									echo '<option value="' . $fila['id_objetivo_estrategico'] . '" >' . $fila['nombre'].' </option>';
								}
							?>
						</select>
						
						<input type='hidden' id='idObjetivoEstrategico' name='idObjetivoEstrategico' />
						<input type='hidden' id='nombreObjetivoEstrategico' name='nombreObjetivoEstrategico' />
					</td>
					
					<td>N2 - Coordinación/ Dirección:</td>
					<td style="width: 48%;">						
						<select id="areaN2" name="areaN2" >
							<option value="">Seleccione</option>
							<option value="">Todos</option>
							<?php 
								$areasN2 = $ca->buscarEstructuraPlantaCentralProvincias($conexion);
						
								while($fila = pg_fetch_assoc($areasN2)){
									echo '<option value="' . $fila['id_area'] . '">' . $fila['nombre'].' </option>';
								}
							?>
						</select>
						
						<input type='hidden' id='nombreAreaN2' name='nombreAreaN2' />			
					</td>
					
				</tr>
				<tr>
				<td>Objetivo Específico:</td>
					<td>
						<div id="dObjetivoEspecificoReporte">
							<select id="objetivoEspecificoReporte" name="objetivoEspecificoReporte" >
								<option value="">Seleccione</option>
								<option value="">Todos</option>
						</select>	
						</div>						
								
					</td>
					
				<td>N4 - Dirección/ Distrital:</td>
					<td>	
						<div id="dN4Reporte">					
							<select id="areaN4Reporte" name="areaN4Reporte" >
								<option value="">Seleccione</option>
								<option value="">Todos</option>
							</select>
						</div>			
					</td>
					
			</tr>
			<tr>

				<td>Objetivo Operativo:</td>
				<td>	
					<div id="dObjetivoOperativoReporte">						
						<select id="objetivoOperativoReporte" name="objetivoOperativoReporte" >
							<option value="">Seleccione</option>
							<option value="">Todos</option>
						</select>	
					</div>		
				</td>	
								
				<td>Gestión/ Unidad:</td>
				<td>	
					<div id="dGestionReporte">					
						<select id="gestionReporte" name="gestionReporte" >
							<option value="">Seleccione</option>
							<option value="">Todos</option>
						</select>		
					</div>		
				</td>
			</tr>
			
			<tr>
				<td>Tipo:</td>
					<td>
						<select id="tipoReporte" name="tipoReporte" >
							<option value="">Seleccione</option>
							<option value="">Todos</option>
							<option value="Proceso">Proceso</option>
							<option value="Proyecto Gasto Corriente">Proyecto Gasto Corriente</option>
							<option value="Proyecto Inversion">Proyecto Inversion</option>
						</select>	
					</td>
					
					<td>Proceso/ Proyecto:</td>
					<td>	
						<div id="dProcesoProyectoReporte">					
							<select id="procesoReporte" name="procesoReporte" >
								<option value="">Seleccione</option>
								<option value="">Todos</option>
							</select>
						</div>				
					</td>
				</tr>
				
				<tr>
				<td>Componente:</td>
					<td>
						<div id="dComponenteReporte">
							<select id="componenteReporte" name="componenteReporte" >
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
					
					<td>Estado:</td>
					<td>						
						<select id="estado" name="estado" >
							<option value="">Todos</option>
							<option value="creado">Creado</option>
							<option value="enviadoRevisor">Enviado a Revisor</option>
							<option value="revisado">Revisado</option>
							<option value="enviadoAprobador">Enviado a DGPGE</option>
							<option value="aprobadoDGPGE">Revisado DGPGE</option>
							<option value="aprobado">Aprobado</option>
						</select>				
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
		$("#idObjetivoEstrategico").val($("#objetivoEstrategico option:selected").val());
		$("#nombreObjetivoEstrategico").val($("#objetivoEstrategico option:selected").text());
		$("#nombreAreaN2").val($("#areaN2 option:selected").text());
		
		if($("#areaN2 option:selected").val() != ''){
			$("#filtrar").attr('data-opcion', 'combosPlanificacionAnual');
		    $("#filtrar").attr('data-destino', 'dObjetivoEspecificoReporte');
		    $("#opcion").val('objetivoEspecificoReporte');
	
		    abrir($("#filtrar"), event, false); //Se ejecuta ajax
		}
	});

	$("#tipoReporte").change(function (event) {
		$("#filtrar").attr('data-opcion', 'combosPlanificacionAnual');
	    $("#filtrar").attr('data-destino', 'dProcesoProyectoReporte');
	    $("#opcion").val('procesoProyectoReporte');
	    		
		if($("#tipoReporte option:selected").val() != ''){
			abrir($("#filtrar"), event, false); //Se ejecuta ajax
		}
	});

	$("#provincia").change(function(){
		$('#idProvincia').val($("#provincia option:selected").val());
		$('#nombreProvincia').val($("#provincia option:selected").text());
	});
</script>