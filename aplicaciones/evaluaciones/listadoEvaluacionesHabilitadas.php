<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorEvaluaciones.php';
	
	$conexion = new Conexion();
	$ce = new ControladorEvaluaciones();
	
?>

	<header>
		<h1>Evaluaciones pendientes</h1>	
	</header>
	
	<?php 
		
		$res = $ce->listarEvaluacionesHabilitadas($conexion, $_SESSION['usuario'],'EVALUACION');
		$contador = 0;
		while($fila = pg_fetch_assoc($res)){
			
			echo '<article 
						id="'.$fila['id_evaluacion'].'"
						class="item"
						data-rutaAplicacion="evaluaciones"
						data-opcion="abrirEvaluacion" 
						ondragstart="drag(event)" 
						draggable="true" 
						data-destino="detalleItem">
					<span class="ordinal">'.++$contador.'</span>
					<span>'.(strlen($fila['nombre'])>50?(substr($fila['nombre'],0,50).'...'):($fila['nombre'])).'</span>
					<aside>'.date('j/n/Y',strtotime($fila['fecha_creacion'])).'</aside>
				</article>';

		}
	?>
	
<script>

	$(document).ready(function(){
		$("#listadoItems").addClass("comunes");
	});
</script>