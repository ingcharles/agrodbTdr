<?php 
	session_start();
	require_once '../../clases/Conexion.php';
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
	}
	
	//print_r($_SESSION);
?>

	<header>
		<h1>Planificación Anual</h1>
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
	
	<div id="enviadoAprobador">
		<h2>Elementos enviados a la Dirección de Planificación</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="revisadoAprobador">
		<h2>Elementos Revisados por la Dirección de Planificación</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="aprobadoDGPGE">
		<h2>Elementos enviados a la Dirección Administrativa Financiera</h2>
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
		$cpp = new ControladorProgramacionPresupuestaria();
		$res = $cpp->listarProgramacionAnualVista($conexion, $identificador, $anio);
			
		while($fila = pg_fetch_assoc($res)){
			$total = pg_fetch_result($cpp->numeroPresupuestosYCostoTotal($conexion, $fila['id_planificacion_anual']), 0, 'num_presupuestos');
			$presupuestosRevisados = pg_fetch_result($cpp->numeroPresupuestosRevisados($conexion, $fila['id_planificacion_anual'], 'revisado'), 0, 'num_presupuestos_revisados');
			$presupuestosRechazados = pg_fetch_result($cpp->numeroPresupuestosRevisados($conexion, $fila['id_planificacion_anual'], 'rechazado'), 0, 'num_presupuestos_revisados');
			$presupuestosEnviadosRevisor = pg_fetch_result($cpp->numeroPresupuestosXEstado($conexion, $fila['id_planificacion_anual'], 'enviadoRevisor'), 0, 'num_presupuestos_revisados');
			$presupuestosEnviadosAprobador = pg_fetch_result($cpp->numeroPresupuestosXEstado($conexion, $fila['id_planificacion_anual'], 'enviadoAprobador'), 0, 'num_presupuestos_revisados');
			$presupuestosAprobadosDGPGE = pg_fetch_result($cpp->numeroPresupuestosXEstado($conexion, $fila['id_planificacion_anual'], 'aprobadoDGPGE'), 0, 'num_presupuestos_revisados');
			
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
				}else if($fila['estado']=='enviadoAprobador'){
					$categoria ="enviadoAprobador";
				}else if($fila['estado']=='revisadoAprobador'){
					$categoria ="revisadoAprobador";
				}else if($fila['estado']=='aprobadoDGPGE'){
					$categoria ="aprobadoDGPGE";
				}else if($fila['estado']=='aprobado'){
					$categoria ="aprobado";
				}else if($fila['estado']=='rechazado'){
					$categoria ="rechazado";
				}
			}
			
			$num = pg_fetch_assoc($cpp->numeroPresupuestosYCostoTotal($conexion, $fila['id_planificacion_anual']));
			
			$contenido ='<article 
								id="'.$fila['id_planificacion_anual'].'"
								class="item"
								data-rutaAplicacion="programacionAnualPresupuestaria"
								data-opcion="abrirPlanificacionAnual" 
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
		$("#enviadoAprobador div> article").length == 0 ? $("#enviadoAprobador").remove():"";
		$("#revisadoAprobador div> article").length == 0 ? $("#revisadoAprobador").remove():"";
		$("#aprobadoDGPGE div> article").length == 0 ? $("#aprobadoDGPGE").remove():"";
		$("#aprobado div> article").length == 0 ? $("#aprobado").remove():"";
		$("#rechazado div> article").length == 0 ? $("#rechazado").remove():"";	
	});

	if(usuario == '0'){
		$("#estadoSesion").html("Su sesión ha expirado, por favor ingrese nuevamente al Sistema GUIA.").addClass("alerta");
		$("#_nuevo").hide();
		$("#_eliminar").hide();
	}
</script>