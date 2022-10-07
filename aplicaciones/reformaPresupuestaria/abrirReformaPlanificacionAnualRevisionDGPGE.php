<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorAreas.php';
	require_once '../../clases/ControladorCatalogos.php';
	require_once '../../clases/ControladorUsuarios.php';
	require_once '../../clases/ControladorReformaPresupuestaria.php';
	
	$conexion = new Conexion();
	$ca = new ControladorAreas();
	$cc = new ControladorCatalogos();
	$cu = new ControladorUsuarios();
	$crp = new ControladorReformaPresupuestaria();
		
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
	
	$idProgramacionAnual = $_POST['id'];
	
	$programacionAnual = pg_fetch_assoc($crp->abrirProgramacionAnualRevisionTemporal($conexion, $idProgramacionAnual));
	
	$estadoProgramacionAnual = $programacionAnual['estado'];
	$presupuesto = $crp->listarPresupuestosTemporalesXEstado($conexion, $idProgramacionAnual,"enviadoRevisor', 'revisado', 'rechazado");
	
	$total = pg_fetch_assoc($crp->numeroPresupuestosReformadosTemporal($conexion, $idPlanificacionAnual, "enviadoRevisor', 'revisado"));
	$presupuestosRevisados = pg_fetch_result($crp->numeroPresupuestosRevisadosTemporal($conexion, $idProgramacionAnual, 'revisado'), 0, 'num_presupuestos_revisados');
	$presupuestosRechazados = pg_fetch_result($crp->numeroPresupuestosRevisadosTemporal($conexion, $idProgramacionAnual, 'rechazado'), 0, 'num_presupuestos_revisados');
	$presupuestosEnviadosRevisor = pg_fetch_result($crp->numeroPresupuestosXEstadoTemporal($conexion, $idProgramacionAnual, 'enviadoRevisor'), 0, 'num_presupuestos_revisados');
	
	if($total['num_presupuestos'] == $presupuestosRevisados){
		$presRevisados = 1;
	}else{
		if($programacionAnual['id_area_aprobador']!= null){
			$presRevisados = 1;
		}else{
			$presRevisados = 0;
		}		
	}

	$totalPresupuesto = pg_fetch_assoc($crp->numeroPresupuestosYCostoTotalIVATemporal($conexion, $idProgramacionAnual));
?>
	
	<header>
		<h1>Planificación Anual</h1>
	</header>
	
	<div id="estado1"></div>
	
	<div id="estado"></div>
	
	<div class="pestania">
		<div id="informacion">
			<fieldset>
				<legend>Planificación Anual</legend>
				
				<div data-linea="1">
					<label>Objetivo Estratégico:</label>
					<?php echo $programacionAnual['objetivo_estrategico'];?>
				</div>
				
				<div data-linea="2">
					<label>N2 - Coordinacion/Dirección:</label>
					<?php echo $programacionAnual['area_n2'];?>
				</div>
				
				<div data-linea="3">
					<label>Objetivo Específico:</label>
					<?php echo $programacionAnual['objetivo_especifico'];?>
				</div>
				
				<div data-linea="4">
					<label>N4 - Dirección/Dirección Distrital:</label>
					<?php echo $programacionAnual['area_n4'];?>
				</div>
				
				<div data-linea="5">
					<label>Objetivo Operativo:</label>
					<?php echo $programacionAnual['objetivo_operativo'];?>
				</div>
				
				<div data-linea="6">
					<label>Gestión/Unidad:</label>
					<?php echo $programacionAnual['gestion'];?>
				</div>
				
				<div data-linea="7">
					<label>Tipo:</label>
					<?php echo $programacionAnual['tipo'];?>
				</div>
				
				<div data-linea="8">
					<label>Proceso/Proyecto:</label>
					<?php echo $programacionAnual['proceso_proyecto'];?>
				</div>
				
				<div data-linea="9">
					<label>Producto Final:</label>
					<?php echo $programacionAnual['producto_final'];?>
				</div>
				
				<div data-linea="10">
					<label>Componente:</label>
					<?php echo $programacionAnual['componente'];?>
				</div>
				
				<div data-linea="11">
					<label>Actividad:</label>
					<?php echo $programacionAnual['actividad'];?>
				</div>
				
				<div data-linea="12">
					<label>Provincia:</label>
					<?php echo $programacionAnual['provincia'];?>
				</div>
				
				<div data-linea="13">
					<label>Cantidad de Usuarios:</label>
					<?php echo $programacionAnual['cantidad_usuarios'];?>
				</div>
				
				<div data-linea="13">
					<label>Población Objetivo:</label>
					<?php echo $programacionAnual['poblacion_objetivo'];?>
				</div>
				
				<div data-linea="14">
					<label>Medio de Verificación:</label>
					<?php echo $programacionAnual['medio_verificacion'];?>
				</div>
				
				<div data-linea="15">
					<label>Responsable:</label>
					<?php echo $programacionAnual['nombre_responsable'];?>
				</div>
				
				<div data-linea="16">
					<label>Monto Solicitado:</label>
					<?php echo number_format($totalPresupuesto['total'], 2, ',', ' ') .' USD';?>
				</div>
		
			</fieldset>
			
		</div>
	</div>
	
	<div class="pestania">
		<fieldset>
			<legend>Presupuestos Registrados</legend>
			<table id="detallePresupuestos">
				<thead>
					<tr>
					    <th width="15%">Actividad</th>
						<th width="15%">Detalle del Gasto</th>
						<th width="10%">Renglo</th>
						<th width="10%">Costo</th>
						<th width="10%">Cuatrimestre</th>
						<th width="10%">Estado</th>
						<th width="10%">Revisado</th>
						<th width="10%">Abrir</th>
					</tr>
				</thead>
				<?php 
					while ($presupuestos = pg_fetch_assoc($presupuesto)){
						echo $crp->imprimirLineaPresupuestoRevisionDGAFDGPGE($presupuestos['id_presupuesto'], $presupuestos['nombre_actividad'], $presupuestos['detalle_gasto'], 
															$presupuestos['renglon'], $presupuestos['costo_iva'], $presupuestos['cantidad'], $presupuestos['cuatrimestre'], 
															$presupuestos['id_planificacion_anual'], 'reformaPresupuestaria',
															$presupuestos['revisado'], 'DGPGE', $presupuestos['estado']);
					}
				?>
			</table>
		</fieldset>
		
	</div>
	
<script type="text/javascript">
	var usuario = <?php echo json_encode($usuario); ?>;
	var estadoProgramacionAnual = <?php echo json_encode($estadoProgramacionAnual); ?>;
	var estadoRevision = <?php echo json_encode($estadoRevision); ?>;
	var presupuestosRevisados = <?php echo json_encode($presRevisados); ?>;
	
		$("document").ready(function(){

			distribuirLineas();
			actualizarBotonesOrdenamiento();
			construirAnimacion($(".pestania"));
			//$('.bsig').attr("disabled","disabled");
			
			acciones("#nuevoPresupuesto","#detallePresupuestos");
			
			if(usuario == '0'){
				$("#estado1").html("Su sesión ha expirado, por favor ingrese nuevamente al Sistema GUIA.").addClass("alerta");
				$("#botonGuardar").attr("disabled", "disabled");
			}

			if(estadoRevision == '1'){
				$("#revision").hide();
			}else{
				$("#revision").show();
			}

			if(presupuestosRevisados == '1'){
				$("#revision").show();
			}else{
				$("#revision").hide();
			}

		});
	
</script>