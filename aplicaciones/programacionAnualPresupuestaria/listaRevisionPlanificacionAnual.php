<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorAreas.php';
	require_once '../../clases/ControladorCatalogos.php';
	require_once '../../clases/ControladorAplicaciones.php';
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
	$car = new ControladorAreas();
	$cc = new ControladorCatalogos();
	$ca = new ControladorAplicaciones();
	$cpp = new ControladorProgramacionPresupuestaria();
			
	$area = pg_fetch_assoc($car->areaUsuario($conexion, $_SESSION['usuario']));
	
	$_SESSION['id_area'] = $area['id_area'];
	$areaRevisor = $area['id_area'];
?>

<header>
	<h1>Revisión Planificación Anual</h1>
	
	<nav>
		<?php 
			$ca = new ControladorAplicaciones();
			$res = $ca->obtenerAccionesPermitidas($conexion, $_POST["opcion"], $_SESSION['usuario']);
			
			while($fila = pg_fetch_assoc($res)){
				echo '<a href="#"
						id="' . $fila['estilo'] . '"
						data-destino="detalleItem"
						data-opcion="' . $fila['pagina'] . '"
						data-rutaAplicacion="' . $fila['ruta'] . '"
						>'.(($fila['estilo']=='_seleccionar')?'<div id="cantidadItemsSeleccionados">0</div>':''). $fila['descripcion'] . '</a>';
				
			}
		?>
	</nav>
	
	<nav style="width: 78%;">
		<form id="filtrarPlanificacionAnual" data-rutaAplicacion="programacionAnualPresupuestaria" data-opcion="listaPlanificacionAnualFiltrada" data-destino="contenedor">
			<input type="hidden" id="identificadorRevisor" name="identificadorRevisor" value="<?php echo $identificador?>"/>
			<input type="hidden" id="areaRevisor" name="areaRevisor" value="<?php echo $areaRevisor?>"/>
			<input type="hidden" id="opcion" name="opcion" />
		
			<table class="filtro">
				<tr>
					<td>
						<div data-linea="2">
							<label id="lAreaN2">N2 - Coordinación/Dirección/Dirección Distrital:</label>
								<select id=areaN2 name="areaN2" required="required">
									<option value="">Seleccione....</option>
									<?php 
										$areasN2 = $car->buscarEstructuraPlantaCentralProvincias($conexion);

										while($fila = pg_fetch_assoc($areasN2)){
											if($areaRevisor == $fila['id_area']){
												echo '<option value="' . $fila['id_area'] . '">' . $fila['nombre'].' </option>';
											}
										}
									?>
								</select>
								
								<input type='hidden' id='nombreAreaN2' name='nombreAreaN2' />
						</div>
					</td>	
				</tr>
				<tr>
					<td>
						<div data-linea="2">
							<div id="dN4"></div>
						</div>
					</td>
				</tr>	
				<tr>
					<td>
						<div data-linea="3">
							<div id="dGestion"></div>
						</div>
					</td>
					
				</tr>	
				<tr>
					<td>
						<div data-linea="4">
							<div id="dTipo"></div>
						</div>
					</td>
					
				</tr>
				<tr>
					<td colspan="5"><button>Buscar</button></td>
				</tr>
			</table>
		</form>
	</nav>
</header>

<div id="estadoSesion"></div>

<div id="contenedor"></div>

<script>
var usuario = <?php echo json_encode($usuario); ?>;

	$(document).ready(function(){
		$("#listadoItems").addClass("comunes");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un ítem para revisarlo.</div>');	
	});
	
	if(usuario == '0'){
		$("#estadoSesion").html("Su sesión ha expirado, por favor ingrese nuevamente al Sistema GUIA.").addClass("alerta");
		$("#_actualizar").hide();
		$("#_seleccionar").hide();
		$("#filtrarPlanificacionAnual").hide();
	}

	$("#areaN2").change(function (event) {
		$("#nombreAreaN2").val($("#areaN2 option:selected").text());

		$("#filtrarPlanificacionAnual").attr('data-opcion', 'combosPlanificacionAnual');
	    $("#filtrarPlanificacionAnual").attr('data-destino', 'dN4');
	    $("#opcion").val('n4FiltroRevision');

	    abrir($("#filtrarPlanificacionAnual"), event, false); //Se ejecuta ajax
	});

	$("#filtrarPlanificacionAnual").submit(function(event){
		$("#filtrarPlanificacionAnual").attr('data-opcion', 'listaPlanificacionAnualFiltrada');
	    $("#filtrarPlanificacionAnual").attr('data-destino', 'contenedor');
		
		abrir($(this),event,false);
	});
						
</script>