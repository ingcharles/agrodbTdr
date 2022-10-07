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
		<h1>Planificación Anual Presupuestaria Aprobada</h1>
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
	
	<div id="aprobado">
		<h2>Elementos Aprobados</h2>
		<div class="elementos"></div>
	</div>
	
	<?php 
	if($identificador != ''){
		$crp = new ControladorReformaPresupuestaria();
		$res = $crp->listarProgramacionAnualAprobadaVista($conexion, $identificador, $anio);
			
		while($fila = pg_fetch_assoc($res)){
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
			
			$num = pg_fetch_assoc($crp->numeroPresupuestosYCostoTotal($conexion, $fila['id_planificacion_anual']));
			
			$contenido ='<article 
								id="'.$fila['id_planificacion_anual'].'"
								class="item"
								data-rutaAplicacion="reformaPresupuestaria"
								data-opcion="abrirPlanificacionAnualAprobada" 
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