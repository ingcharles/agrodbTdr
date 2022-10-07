<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorAplicaciones.php';
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
	}
	
	//print_r($_SESSION);
?>

	<header>
		<h1>Planificación Anual a Reformar</h1>
		<nav>
		<?php 
			
			$conexion = new Conexion();
			$ca = new ControladorAplicaciones();
			$res = $ca->obtenerAccionesPermitidas($conexion, $_POST["opcion"], $identificador);
			
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
	</header>
	
	<div id="estadoSesion"></div>
	
	<div id="creado">
		<h2>Elementos Creados</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="enviadoRevisor">
		<h2>Elementos enviados para Revisión</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="revisado">
		<h2>Elementos Revisados</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="enviadoAprobadorPI">
		<h2>Elementos enviados a la Dirección de Planificación</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="revisadoAprobadorPI">
		<h2>Elementos Revisados por la Dirección de Planificación</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="enviadoRevisorGA">
		<h2>Elementos enviados a Gestión Administrativa</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="revisadoGA">
		<h2>Elementos Revisados por Gestión Administrativa</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="enviadoRevisorGF">
		<h2>Elementos enviados a Gestión Financiera</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="aprobado">
		<h2>Elementos Aprobados</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="rechazado">
		<h2>Elementos Rechazados</h2>
		<div class="elementos"></div>
	</div>
	
	<?php 
	if($identificador != ''){
		$crp = new ControladorReformaPresupuestaria();
		$res = $crp->listarProgramacionAnualVistaTemporal($conexion, $identificador, $anio);
			
		while($fila = pg_fetch_assoc($res)){
			$total = pg_fetch_result($crp->numeroPresupuestosYCostoTotalTemporal($conexion, $fila['id_planificacion_anual']), 0, 'num_presupuestos');
			$presupuestosRevisados = pg_fetch_result($crp->numeroPresupuestosRevisadosTemporal($conexion, $fila['id_planificacion_anual'], 'revisado'), 0, 'num_presupuestos_revisados');
			$presupuestosRechazados = pg_fetch_result($crp->numeroPresupuestosRevisadosTemporal($conexion, $fila['id_planificacion_anual'], 'rechazado'), 0, 'num_presupuestos_revisados');
			$presupuestosEnviadosRevisor = pg_fetch_result($crp->numeroPresupuestosXEstadoTemporal($conexion, $fila['id_planificacion_anual'], 'enviadoRevisor'), 0, 'num_presupuestos_revisados');
			$presupuestosEnviadosAprobador = pg_fetch_result($crp->numeroPresupuestosXEstadoTemporal($conexion, $fila['id_planificacion_anual'], 'enviadoAprobador'), 0, 'num_presupuestos_revisados');
			$presupuestosAprobadosDGPGE = pg_fetch_result($crp->numeroPresupuestosXEstadoTemporal($conexion, $fila['id_planificacion_anual'], 'aprobadoDGPGE'), 0, 'num_presupuestos_revisados');
			
			if($presupuestosRechazados>0){
				$categoria ="rechazado";
			}else{
				//Planificacion Anual
				if($fila['estado']=='creado'){
					$categoria ="creado";
				}else if($fila['estado']=='enviadoRevisor'){
					$categoria ="enviadoRevisor";
				}else if($fila['estado']=='revisado'){
					$categoria ="revisado";
				}else if($fila['estado']=='enviadoAprobadorPI'){ //revisar
					$categoria ="enviadoAprobadorPI";
				}else if($fila['estado']=='revisadoAprobadorPI'){ //revisar
					$categoria ="revisadoAprobadorPI";
				}else if($fila['estado']=='enviadoRevisorGA'){
					$categoria ="enviadoRevisorGA";
				}else if($fila['estado']=='revisadoGA'){
					$categoria ="revisadoGA";
				}else if($fila['estado']=='enviadoRevisorGF'){
					$categoria ="enviadoRevisorGF";
				}else if($fila['estado']=='aprobado'){
					$categoria ="aprobado";
				}else if($fila['estado']=='rechazado'){
					$categoria ="rechazado";
				}
			}			
			
			$num = pg_fetch_assoc($crp->numeroPresupuestosYCostoTotalTemporal($conexion, $fila['id_planificacion_anual']));
			
			$contenido ='<article 
								id="'.$fila['id_planificacion_anual'].'"
								class="item"
								data-rutaAplicacion="reformaPresupuestaria"
								data-opcion="abrirReformaPlanificacionAnual" 
								ondragstart="drag(event)" 
								draggable="true" 
								data-destino="detalleItem">
							<span class="ordinal">'.++$contador.'</span>
							<small><span><b>Tipo: </b>'.$fila['tipo'].'</span><br />
							<span>'.$fila['actividad'].'</span></small>
							<aside><small>'.$fila['id_area_unidad'].' - Código: '.$fila['id_planificacion_anual'].'<br />
							Presupuestos: '.$num['num_presupuestos'].'</small></aside>
						</article>';		
	?>
			
			<script type="text/javascript">
							var contenido = <?php echo json_encode($contenido);?>;
							var categoria = <?php echo json_encode($categoria);?>;
							$("#"+categoria+" div.elementos").append(contenido);
			</script>
	<?php	
			}
		}				
	?>

<script>
var usuario = <?php echo json_encode($usuario); ?>;

	$(document).ready(function(){
		$("#listadoItems").addClass("comunes");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un ítem para revisarlo.</div>');	

		$("#creado div> article").length == 0 ? $("#creado").remove():"";
		$("#enviadoRevisor div> article").length == 0 ? $("#enviadoRevisor").remove():"";
		$("#revisado div> article").length == 0 ? $("#revisado").remove():"";
		$("#enviadoAprobadorPI div> article").length == 0 ? $("#enviadoAprobadorPI").remove():"";
		$("#revisadoAprobadorPI div> article").length == 0 ? $("#revisadoAprobadorPI").remove():"";
		$("#enviadoRevisorGA div> article").length == 0 ? $("#enviadoRevisorGA").remove():"";
		$("#revisadoGA div> article").length == 0 ? $("#revisadoGA").remove():"";
		$("#enviadoRevisorGF div> article").length == 0 ? $("#enviadoRevisorGF").remove():"";
		$("#revisadoAprobadorGF div> article").length == 0 ? $("#revisadoAprobadorGF").remove():"";
		$("#aprobado div> article").length == 0 ? $("#aprobado").remove():"";
		$("#rechazado div> article").length == 0 ? $("#rechazado").remove():"";	
	});

	if(usuario == '0'){
		$("#estadoSesion").html("Su sesión ha expirado, por favor ingrese nuevamente al Sistema GUIA.").addClass("alerta");
		$("#_nuevo").hide();
		$("#_eliminar").hide();
		$("#_enviarRevision").hide();
	}
</script>