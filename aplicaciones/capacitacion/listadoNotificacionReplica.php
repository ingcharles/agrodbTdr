<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCapacitacion.php';
require_once '../../clases/ControladorAplicaciones.php';

$conexion = new Conexion();
$cc = new ControladorCapacitacion();

$identificador=$_SESSION['usuario'];
$estadoInicio = 1;
$estadoFin = 20;

?>
<header>
	<h1>Administración capacitación</h1>
	<nav>
		<?php			
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

<div id="D15">
	<h2>Solicitudes para calificar replicación</h2>
	<div class="elementos"></div>
</div>

<div id="D16">
	<h2>Solicitudes con replicación calificada</h2>
	<div class="elementos"></div>
</div>

<div id="D19">
	<h2>Solicitudes para generar procedimiento/manual</h2>
	<div class="elementos"></div>
</div>

<div id="D20">
	<h2>Solicitudes con procedimiento/manual</h2>
	<div class="elementos"></div>
</div>

<?php 
	$res = $cc->listarReplicacionUsuario($conexion,null,null,null,$identificador,$estadoInicio,$estadoFin);

	while($fila = pg_fetch_assoc($res)){
		$categoria='';
		switch ($fila['estado_requerimiento']){
			case '15': $pagina = 'calificarReplica';  $categoria = 'D15'; break;
			case '16': $pagina = 'verCalificacionReplica';	 $categoria = 'D16'; break;
			case '19':	$pagina = 'cargarArchivoProcedimiento';	 $categoria = 'D19'; break;
			case '20':	$pagina = 'cargarArchivoProcedimiento';	 $categoria = 'D20'; break;
		}
		
		$verificacion = $cc->verificarCalificacionReplicado($conexion, $fila['id_requerimiento'], $identificador);

		if(pg_num_rows($verificacion)!=0 && $fila['estado_requerimiento'] != '19')
			$pagina = 'verCalificacionReplica';

		if($categoria!=''){
		$contenido = '<article
							id="'.$fila['id_requerimiento'].'"
							class="item"
							data-rutaAplicacion="capacitacion"
							data-opcion="'.$pagina.'"
							ondragstart="drag(event)"
							draggable="true"
							data-destino="detalleItem">
							<span class="ordinal">'.++$contador.'</span>
							<span><small>'.(strlen($fila['nombre_evento'])>40?(substr($fila['nombre_evento'],0,40).'...'):(strlen($fila['nombre_evento'])>0?$fila['nombre_evento']:'')).'</small></br></span>
							<span><small>'.(strlen($fila['nombre_replicante'])>22?(substr($fila['nombre_replicante'],0,22).'...'):$fila['nombre_replicante']).'</small></span>
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

		$("#D15 div> article").length == 0 ? $("#D15").remove():"";
		$("#D16 div> article").length == 0 ? $("#D16").remove():"";
		$("#D19 div> article").length == 0 ? $("#D19").remove():"";	
		$("#D20 div> article").length == 0 ? $("#D20").remove():"";			
	});
</script>