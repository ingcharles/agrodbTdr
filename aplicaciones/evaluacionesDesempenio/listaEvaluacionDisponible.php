<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorAplicaciones.php';
	require_once '../../clases/ControladorEvaluacionesDesempenio.php';
	
	$identificador = $_SESSION['usuario']
?>

<header>
		<h1>Evaluaciones</h1>
		<nav>
		<?php 

			$conexion = new Conexion();
			$ca = new ControladorAplicaciones();
			$res = $ca->obtenerAccionesPermitidas($conexion, $_POST["opcion"], $_SESSION['usuario']);
			//data-rutaAplicacion="' . $fila['ruta'] .'"
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
	
	<div id="superior">
		<h2>Evaluación a funcionarios a cargo</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="inferior">
		<h2>Evaluación a jefe directo</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="pares">
		<h2>Evaluación a pares</h2>
		<div class="elementos"></div>
	</div>
	
	
	<div id="autoevaluacion">
		<h2>Autoevaluación</h2>
		<div class="elementos"></div>
	</div>

	<?php 
	$ced = new ControladorEvaluacionesDesempenio();
	
	$res = $ced->abrirEvaluacionDisponibleUsuario($conexion, $identificador,'activo');
	
	$contador = 0;
	while($fila = pg_fetch_assoc($res)){

		$categoria = strtolower($fila['tipo']);
		$codigo=$fila['id_aplicante'].'-'.$fila['id_evaluacion'];
		$contenido = '<article
						id="'.$codigo.'"
						class="item"
						data-rutaAplicacion="evaluacionesDesempenio"
						data-opcion="abrirEvaluacionDisponible"
						ondragstart="drag(event)"
						draggable="true"
						data-destino="detalleItem">
					<span class="ordinal">'.++$contador.'</span>
					<span>'.$fila['nombres_completos'].'<br/></span>
					<aside>'.($fila['tipo']=='superior'?'Evaluación miembros equipo':($fila['tipo']=='inferior'?'Evaluación director área':($fila['tipo']=='pares'?'Evaluación a miembros pares':'Autoevaluación'))).'</aside>
				</article>';
		?>
			<script type="text/javascript">
				var contenido = <?php echo json_encode($contenido);?>;
				var categoria = <?php echo json_encode($categoria);?>;
				$("#"+categoria+" div.elementos").append(contenido);
			</script>
	<?php 
		}
	?>
	

<script>
	$(document).ready(function(){
		$("#listadoItems").addClass("comunes");

		$("#superior div> article").length == 0 ? $("#superior").remove():"";
		$("#inferior div> article").length == 0 ? $("#inferior").remove():"";
		$("#pares div> article").length == 0 ? $("#pares").remove():"";
		$("#autoevaluacion div> article").length == 0 ? $("#autoevaluacion").remove():"";
		
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui una evaluación para revisarlo.</div>');
	});
</script>