<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorAplicaciones.php';
	require_once '../../clases/ControladorReformaPresupuestaria.php';
	require_once '../../clases/ControladorCatalogos.php';

	//$fecha = getdate();
	//$anio = $fecha['year'];
	
	$conexion = new Conexion();
	$ca = new ControladorAplicaciones();
	$crp = new ControladorReformaPresupuestaria();
	
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
	$anio = htmlspecialchars ($_POST['anio'],ENT_NOQUOTES,'UTF-8');

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
	
	<div id="rechazado">
		<h2>Elementos Rechazados - Pendientes de Corrección</h2>
		<div class="elementos"></div>
	</div>
	
	<?php  
		
		$contador = 0;
		$res = $crp->listarProgramacionAnualTemporalVistaAprobacion($conexion, $areaN2, $areaN4, $idGestion, $tipo,
				$anio, $identificadorRevisor, "'enviadoRevisorGF'", 'GF');
		
			
		while($fila = pg_fetch_assoc($res)){
			
			$total = pg_fetch_result($crp->numeroPresupuestosYCostoTotalTemporal($conexion, $fila['id_planificacion_anual']), 0, 'num_presupuestos');
			$presupuestosRevisados = pg_fetch_result($crp->numeroPresupuestosRevisadosTemporal($conexion, $fila['id_planificacion_anual'], 'revisado'), 0, 'num_presupuestos_revisados');
			$presupuestosRechazados = pg_fetch_result($crp->numeroPresupuestosRevisadosTemporal($conexion, $fila['id_planificacion_anual'], 'rechazado'), 0, 'num_presupuestos_revisados');
			$presupuestosEnviadosRevisor = pg_fetch_result($crp->numeroPresupuestosXEstadoTemporal($conexion, $fila['id_planificacion_anual'], 'enviadoRevisor'), 0, 'num_presupuestos_revisados');
			$presupuestosEnviadosAprobador = pg_fetch_result($crp->numeroPresupuestosXEstadoTemporal($conexion, $fila['id_planificacion_anual'], 'enviadoAprobador'), 0, 'num_presupuestos_revisados');
			$presupuestosAprobados = pg_fetch_result($crp->numeroPresupuestosXEstadoTemporal($conexion, $fila['id_planificacion_anual'], 'aprobado'), 0, 'num_presupuestos_revisados');
						
			if($presupuestosRechazados>0){
				$categoria ="rechazado";
			}else{
				//Planificacion Anual
				if(($fila['tipo']=='Proceso') && ($fila['estado']=='revisado')){
					$categoria ="ProcesoRevisado";
				}else if(($fila['tipo']=='Proceso') && ($fila['estado']=='revisadoGF')){
					if($presupuestosEnviadosRevisor>0){
						$categoria ="Proceso";
					}else{
						$categoria ="ProcesoRevisado";
					}
				}else if(($fila['tipo']=='Proceso')){
					$categoria ="Proceso";
				}else if(($fila['tipo']=='Proyecto Gasto Corriente') && $fila['estado']=='revisado'){
					$categoria ="ProyectoGastoCorrienteRevisado";
				}else if(($fila['tipo']=='Proyecto Gasto Corriente') && ($fila['estado']=='revisadoGF')){
					if($presupuestosEnviadosRevisor>0){
						$categoria ="ProyectoGastoCorriente";
					}else{
						$categoria ="ProyectoGastoCorriente";
					}
				}else if(($fila['tipo']=='Proyecto Gasto Corriente')){
					$categoria ="ProyectoGastoCorriente";
				}else if(($fila['tipo']=='Proyecto Inversion') && $fila['estado']=='revisado'){
					$categoria ="ProyectoInversionRevisado";
				}else if(($fila['tipo']=='Proyecto Inversion') && ($fila['estado']=='revisadoGF')){
					if($presupuestosEnviadosRevisor>0){
						$categoria ="ProyectoInversion";
					}else{
						$categoria ="ProyectoInversion";
					}
				}else if(($fila['tipo']=='Proyecto Inversion')){ //($fila['revisado']==null)){
					$categoria ="ProyectoInversion";
				}
			}
			
			$num = pg_fetch_assoc($crp->numeroPresupuestosYCostoTotalTemporal($conexion, $fila['id_planificacion_anual']));
			$numPresupuestos = $num['num_presupuestos'] - $presupuestosAprobados;
			 
			$contenido ='<article 
								id="'.$fila['id_planificacion_anual'].'"
								class="item"
								data-rutaAplicacion="reformaPresupuestaria"
								data-opcion="abrirReformaPlanificacionAnualRevisionGF" 
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
										Presupuestos: '.$numPresupuestos.'
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
			$("#rechazado div> article").length == 0 ? $("#rechazado").remove():"";
			
		});
		
		if(usuario == '0'){
			$("#estadoSesion").html("Su sesión ha expirado, por favor ingrese nuevamente al Sistema GUIA.").addClass("alerta");
			$("#_actualizar").hide();
			$("#_seleccionar").hide();
			$("#filtrarPlanificacionAnual").hide();
		}
	</script>
</html>