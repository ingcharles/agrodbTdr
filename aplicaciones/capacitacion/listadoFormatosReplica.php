<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCapacitacion.php';
require_once '../../clases/ControladorAplicaciones.php';

$conexion = new Conexion();	
$cc = new ControladorCapacitacion();

$identificador = $_SESSION['usuario'];
$estado_incial = 17;
$estado_final = 17;

?>
<header>
	<h1>Subir réplica</h1>
	<nav>
		<?php			
			
		    $ca = new ControladorAplicaciones();
			$res = $ca->obtenerAccionesPermitidas($conexion, $_POST["opcion"], $identificador);			
			while($fila = pg_fetch_assoc($res)){				
				echo '<a href="#"
						id="' . $fila['estilo'] . '"
						data-destino="detalleItem"
						data-opcion="' . $fila['pagina'] . '"
						data-rutaAplicacion="' . $fila['ruta'] . '">'.(($fila['estilo']=='_seleccionar')?'<div id="cantidadItemsSeleccionados">0</div>':''). $fila['descripcion'] . '</a>';							
			}
		?>
		</nav>
</header>

<div id="R17">
	<h2>Solicitudes para entrega de formato réplica</h2>
	<div class="elementos"></div>
</div>

<?php 
	$res = $cc->obtenerRequerimientos($conexion,null,null,null,$id_requerimiento,$identificador,'',$estado_incial,$estado_final);
	
	while($fila = pg_fetch_assoc($res)){
		$categoria='';
		switch ($fila['estado_requerimiento']){
			case 17:  $categoria = 'R17'; break;
		}
		if($categoria!=''){
		$contenido = '<article
							id="'.$fila['id_requerimiento'].'"
							class="item"
							data-rutaAplicacion="capacitacion"
							data-opcion="elaborarInformeReplica"
							ondragstart="drag(event)"
							draggable="true"
							data-destino="detalleItem">
							<span class="ordinal">'.++$contador.'</span>
							<span><small>'.(strlen($fila['nombre_evento'])>50?(substr($fila['nombre_evento'],0,50).'...'):(strlen($fila['nombre_evento'])>0?$fila['nombre_evento']:'')).'</small></br></span>
							<span></span>
							<aside><small> Desde: '.$fila['fecha_inicio'].'<br/> Hasta: '.$fila['fecha_fin'].'</small></aside>
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
$(document).ready(function(){
	$("#listadoItems").addClass("comunes");
	$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui una solicitud para revisarla.</div>');

	$("#R17 div> article").length == 0 ? $("#R17").remove():"";						
});
</script>