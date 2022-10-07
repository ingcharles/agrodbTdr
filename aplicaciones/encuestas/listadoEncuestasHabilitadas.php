<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	/*require_once '../../clases/controladorAplicaciones.php';*/
	require_once '../../clases/ControladorEncuestas.php';
	
	$conexion = new Conexion();
	$cs = new ControladorEncuestas();
	
	
?>

	<header>
		<h1>Encuestas pendientes</h1>	
	</header>
	
	<?php 
		
		$res = $cs->listarEncuestasHabilitadas($conexion, $_SESSION['usuario']);
		$contador = 0;
		while($fila = pg_fetch_assoc($res)){
			
			echo '<article 
						id="'.$fila['id_encuesta'].'"
						class="item"
						data-rutaAplicacion="encuestas"
						data-opcion="abrirEncuesta" 
						ondragstart="drag(event)" 
						draggable="true" 
						data-destino="detalleItem">
					<span class="ordinal">'.++$contador.'</span>
					<span>'.$fila['nombre'].'</span>
					<aside>'.date('j/n/Y',strtotime($fila['fecha_creacion'])).'</aside>
				</article>';

	
		}
	?>
	
<script>

	$(document).ready(function(){
		$("#listadoItems").addClass("comunes");

		$("#enviado div> article").length == 0 ? $("#enviado").remove():"";
		$("#reenviado div> article").length == 0 ? $("#reenviado").remove():"";
		$("#revisionResponsable div> article").length == 0 ? $("#revisionResponsable").remove():"";

	});
</script>