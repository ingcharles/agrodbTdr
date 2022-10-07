<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCapacitacion.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorUsuarios.php';
$conexion = new Conexion();	
$cc = new ControladorCapacitacion();
$cu = new ControladorUsuarios();

$identificador = $_SESSION['usuario'];
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

<div id="R6">
	<h2>Solicitudes enviadas para aprobación</h2>
	<div class="elementos"></div>
</div>

<div id="R11">
	<h2>Solicitudes aprobadas por el director</h2>
	<div class="elementos"></div>
</div>

<div id="R0">
	<h2>Solicitudes rechazado por el director</h2>
	<div class="elementos"></div>
</div>

<?php

	$res = $cc->obtenerRequerimientos($conexion, null, null, null, null, null, $identificador, $estadoInicio, $estadoFin);
	
	while($fila = pg_fetch_assoc($res)){
		$categoria='';
		switch ($fila['estado_requerimiento']){
			case 0: $categoria = 'R0'; break;
			case 6: $categoria = 'R6'; break;
			case 11: $categoria = 'R11'; break;
		}
		if($categoria!=''){
			$contenido = '<article
							id="'.$fila['id_requerimiento'].'"
							class="item"
							data-rutaAplicacion="capacitacion"
							data-opcion="aprobarRequerimiento"
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

	$("#R0 div> article").length == 0 ? $("#R0").remove():"";
	$("#R6 div> article").length == 0 ? $("#R6").remove():"";
	$("#R11 div> article").length == 0 ? $("#R11").remove():"";
						
});
</script>