<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorAplicaciones.php';
	require_once '../../clases/ControladorProgramacionPresupuestaria.php';
	require_once '../../clases/ControladorCatalogos.php';

	$fecha = getdate();
	$anio = $fecha['year'];
	
	$conexion = new Conexion();
	$ca = new ControladorAplicaciones();
	$cpp = new ControladorProgramacionPresupuestaria();
	
	$identificadorRevisor = htmlspecialchars ($_POST['identificadorRevisor'],ENT_NOQUOTES,'UTF-8');
	$areaRevisor= htmlspecialchars ($_POST['areaRevisor'],ENT_NOQUOTES,'UTF-8');
	$areaN2 = htmlspecialchars ($_POST['areaN2'],ENT_NOQUOTES,'UTF-8');
	$nombreAreaN2= htmlspecialchars ($_POST['nombreAreaN2'],ENT_NOQUOTES,'UTF-8');
	$areaN4 = htmlspecialchars ($_POST['areaN4FiltroRevision'],ENT_NOQUOTES,'UTF-8');
	$nombreAreaN4= htmlspecialchars ($_POST['nombreAreaN4'],ENT_NOQUOTES,'UTF-8');
	$idGestion = htmlspecialchars ($_POST['idGestion'],ENT_NOQUOTES,'UTF-8');
	$nombreGestion= htmlspecialchars ($_POST['nombreGestion'],ENT_NOQUOTES,'UTF-8');
	$tipo= htmlspecialchars ($_POST['tipoFiltroRevision'],ENT_NOQUOTES,'UTF-8');
	$opcion = htmlspecialchars ($_POST['opcion'],ENT_NOQUOTES,'UTF-8');

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>
	
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
		$res = $cpp->listarProgramacionAnualVistaAprobacionDGAF($conexion, $areaN2, $areaN4, $idGestion, $tipo, 
															$anio, "'aprobadoDGPGE'");
		
		while($fila = pg_fetch_assoc($res)){
			
			$num = pg_fetch_assoc($cpp->numeroPresupuestosYCostoTotal($conexion, $fila['id_planificacion_anual']));
				
			$total = pg_fetch_result($cpp->numeroPresupuestosYCostoTotal($conexion, $fila['id_planificacion_anual']), 0, 'num_presupuestos');
			$presupuestosEnviadosAprobador = pg_fetch_result($cpp->numeroPresupuestosXEstado($conexion, $fila['id_planificacion_anual'], 'enviadoAprobador'), 0, 'num_presupuestos_revisados');
			$presupuestosRevisados = pg_fetch_result($cpp->numeroPresupuestosRevisados($conexion, $fila['id_planificacion_anual'], 'aprobadoDGAF'), 0, 'num_presupuestos_revisados');
			
			if($fila['estado']=='aprobadoDGPGE'){
				if(($fila['tipo']=='Proceso') && ($total != $presupuestosRevisados)){
					$categoria ="Proceso";
				}else if(($fila['tipo']=='Proceso') && ($total == $presupuestosRevisados)){
					$categoria ="ProcesoRevisado";
				}else if(($fila['tipo']=='Proyecto Gasto Corriente') && ($total != $presupuestosRevisados)){
					$categoria ="ProyectoGastoCorriente";
				}else if(($fila['tipo']=='Proyecto Gasto Corriente') && ($total == $presupuestosRevisados)){
					$categoria ="ProyectoGastoCorrienteRevisado";
				}else if(($fila['tipo']=='Proyecto Inversion') && ($total != $presupuestosRevisados)){
					$categoria ="ProyectoInversion";
				}else if(($fila['tipo']=='Proyecto Inversion') && ($total == $presupuestosRevisados)){
					$categoria ="ProyectoInversionRevisado";
				}
			}
			
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
								<small>'.$fila['id_area_unidad'].' - Código: '.$fila['id_planificacion_anual'].'<br />
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
			
	
	
</body>
<script>
var usuario = <?php echo json_encode($identificadorRevisor); ?>;

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
</script>
</html>