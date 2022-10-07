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
	<h1>Aprobación Planificación Anual</h1>
	
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
		<form id="filtrarPlanificacionAnual" data-rutaAplicacion="programacionAnualPresupuestaria" data-opcion="listaPlanificacionAnualFiltradaAprobacion" data-destino="contenedor">
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
											echo '<option value="' . $fila['id_area'] . '">' . $fila['nombre'].' </option>';
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

<div id="contenedor">

    <div id="Proceso">
    		<h2>Procesos</h2>
    		<div class="elementos"></div>
    	</div>
    	
    	<div id="ProcesoRevisado">
    		<h2>Procesos Revisados</h2>
    		<div class="elementos"></div>
    	</div>
    	
    	<div id="ProyectoGastoCorriente">
    		<h2>Proyectos de Gasto Corriente</h2>
    		<div class="elementos"></div>
    	</div>
    	
    	<div id="ProyectoGastoCorrienteRevisado">
    		<h2>Proyectos de Gasto Corriente Revisados</h2>
    		<div class="elementos"></div>
    	</div>
    	
    	<div id="ProyectoInversion">
    		<h2>Proyectos de Inversión</h2>
    		<div class="elementos"></div>
    	</div>
    	
    	<div id="ProyectoInversionRevisado">
    		<h2>Proyectos de Inversión Revisados</h2>
    		<div class="elementos"></div>
    	</div>
    
    	<?php  
    		
    		$contador = 0;
    		$res = $cpp->listarProgramacionAnualTotalVistaAprobacion($conexion, $anio, $identificador, "'enviadoAprobador','revisadoAprobador'");
    		
    		while($fila = pg_fetch_assoc($res)){
    			if(($fila['tipo']=='Proceso') && ($fila['estado']=='revisadoAprobador')){
    				$categoria ="ProcesoRevisado";
    			}else if(($fila['tipo']=='Proceso') && ($fila['estado']=='enviadoAprobador')){
    				$categoria ="Proceso";
    			}else if(($fila['tipo']=='Proyecto Gasto Corriente') && $fila['estado']=='revisadoAprobador'){
    				$categoria ="ProyectoGastoCorrienteRevisado";
    			}else if(($fila['tipo']=='Proyecto Gasto Corriente') && ($fila['estado']=='enviadoAprobador')){
    				$categoria ="ProyectoGastoCorriente";
    			}else if(($fila['tipo']=='Proyecto Inversion') && $fila['estado']=='revisadoAprobador'){
    				$categoria ="ProyectoInversionRevisado";
    			}else if(($fila['tipo']=='Proyecto Inversion') && ($fila['estado']=='enviadoAprobador')){
    				$categoria ="ProyectoInversion";
    			}
    			
    			$num = pg_fetch_assoc($cpp->numeroPresupuestosYCostoTotal($conexion, $fila['id_planificacion_anual']));
    			
    			$contenido ='<article 
    								id="'.$fila['id_planificacion_anual'].'"
    								class="item"
    								data-rutaAplicacion="programacionAnualPresupuestaria"
    								data-opcion="abrirPlanificacionAnualAprobacion" 
    								ondragstart="drag(event)" 
    								draggable="true" 
    								data-destino="detalleItem">
    							<span class="ordinal">'.++$contador.'</span>
    							<small>
    								<span><b>Tipo: </b>'.$fila['tipo'].'</span><br />
    								<span>'.$fila['actividad'].'</span>
    							</small>
    							<aside>
    								<small> N2: '.$fila['id_area_n2'].'-'.$fila['id_area_unidad'].'  ID: '.$fila['id_planificacion_anual'].'<br />
    										Presupuestos: '.$num['num_presupuestos'].'
    								</small>
    							</aside>
    						</article>';			
    			
    	?>
    			
    			<script type="text/javascript">
    							var contenido = <?php echo json_encode($contenido);?>;
    							var categoria = <?php echo json_encode($categoria);?>;
    							var clase = <?php echo json_encode($clase);?>;
    							$("#"+categoria+" div.elementos").append(contenido);
    			</script>
    	<?php	
    		}				
    	?>
</div>

<script>
var usuario = <?php echo json_encode($usuario); ?>;

    $(document).ready(function(){
    	$("#listadoItems").addClass("comunes");
    	$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un ítem para revisarlo.</div>');	
    
    	$("#Proceso div> article").length == 0 ? $("#Proceso").remove():"";
    	$("#ProcesoRevisado div> article").length == 0 ? $("#ProcesoRevisado").remove():"";
    	$("#ProyectoGastoCorriente div> article").length == 0 ? $("#ProyectoGastoCorriente").remove():"";
    	$("#ProyectoGastoCorrienteRevisado div> article").length == 0 ? $("#ProyectoGastoCorrienteRevisado").remove():"";
    	$("#ProyectoInversion div> article").length == 0 ? $("#ProyectoInversion").remove():"";
    	$("#ProyectoInversionRevisado div> article").length == 0 ? $("#ProyectoInversionRevisado").remove():"";
    	
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
		$("#filtrarPlanificacionAnual").attr('data-opcion', 'listaPlanificacionAnualFiltradaAprobacion');
	    $("#filtrarPlanificacionAnual").attr('data-destino', 'contenedor');
		
		abrir($(this),event,false);
	});
						
</script>