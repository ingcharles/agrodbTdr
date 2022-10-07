<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorProgramasControlOficial.php';
	
	$conexion = new Conexion();
	$cpco = new ControladorProgramasControlOficial();
	
	$idInspeccionOCCS = $_POST['idInspeccionOCCS'];
	$idBioseguridadInspeccionOCCS = $_POST['idBioseguridadInspeccionOCCS'];
	
	$bioseguridad = pg_fetch_assoc($cpco->abrirBioseguridadInspeccionOCCS($conexion, $idBioseguridadInspeccionOCCS));
	
?>

	<header>
		<h1>Bioseguridad, Sanidad, Infraestructura y Manejo Animal</h1>
	</header>

	<div id="estado"></div>
	
	<form id="regresar" data-rutaAplicacion="programasControlOficial" data-opcion="abrirInspeccionOCCS" data-destino="detalleItem">
		<input type="hidden" name="id" value="<?php echo $idInspeccionOCCS;?>"/>
		<button class="regresar">Regresar</button>
	</form>

		<fieldset>
			<legend>Información de Bioseguridad, Sanidad, Infraestructura y Manejo</legend>
			
			<div data-linea="27">
				<label>Calendario Vacunación:</label>
					<?php echo $bioseguridad['calendario_vacunacion'];?> 					
			</div>
			
			<div data-linea="27" >
				<label id="dlVacuna">Vacuna:</label>
				<?php echo $bioseguridad['vacuna'];?>
			</div>
						
			<div data-linea="28">
				<label>Calendario Desparasitación:</label>
				<?php echo $bioseguridad['calendario_desparacitacion'];?>
			</div>
			
			<div data-linea="28" >
				<label id="lFrecuencia">Frecuencia:</label>
				<?php echo $bioseguridad['frecuencia'];?>
			</div>
			
			<hr />
			<div data-linea="29">
				<label>Asesoramiento Técnico:</label>
				<?php echo $bioseguridad['asesoramiento_tecnico'];?> 					
			</div>
			
			<div data-linea="30" >
				<label id="lAsesoramientoTecnico1">Nombre:</label>
				<?php echo $bioseguridad['nombre_asesor_tecnico'];?>
			</div>
			
			<div data-linea="30" >
				<label id="lAsesoramientoTecnico2">Profesión:</label>
				<?php echo $bioseguridad['profesion'];?>
			</div>
			
			<hr />
			
			<div data-linea="31">
				<label>Identificación Individual:</label>
				<?php echo $bioseguridad['identificacion_individual'];?>
			</div>
			
			<div data-linea="31" >
				<label id="lTipoIdentificacion">Tipo de Identificación:</label>
				<?php echo $bioseguridad['tipo_identificacion'];?>
			</div>
			
			<div data-linea="32">
				<label>Tipo de Alimentación:</label>
				<?php echo $bioseguridad['tipo_alimentacion'];?>
			</div>
			
			<div data-linea="32">
				<label>Corral de Manejo:</label>
				<?php echo $bioseguridad['corral_manejo'];?> 					
			</div>
		
			<hr />
			
			<div data-linea="34">
				<label>Registros Productivos:</label>
				<?php echo $bioseguridad['registros_productivos'];?> 					
			</div>
			
			<div data-linea="34">
				<label>Tipo de Producción:</label>
				<?php echo $bioseguridad['tipo_produccion'];?>					
			</div>
			
			<div data-linea="35">
				<label>Sector Perteneciente:</label>
				<?php echo $bioseguridad['sector_perteneciente'];?>				
			</div>
						
		</fieldset>
	

<script type="text/javascript">
						
	$('document').ready(function(){
		acciones("#nuevaInspeccionOCCS","#detalleInspeccionOCCS");
		distribuirLineas();
	});
</script>