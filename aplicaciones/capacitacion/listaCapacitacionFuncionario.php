<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCapacitacion.php';
require_once '../../clases/ControladorAplicaciones.php';

$conexion = new Conexion();	
$cc = new ControladorCapacitacion();
$identificador=$_SESSION['usuario'];
$contador = 0;


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
	<h2>Solicitudes ingresadas</h2>
	<div class="elementos"></div>
</div>

<div id="R7">
	<h2>Solicitudes modificadas</h2>
	<div class="elementos"></div>
</div>

<div id="R11">
	<h2>Solicitudes aprobadas por jefe inmediato</h2>
	<div class="elementos"></div>
</div>

<div id="R0">
	<h2>Solicitudes rechazadas por jefe inmediato</h2>
	<div class="elementos"></div>
</div>

<div id="R1">
	<h2>Solicitudes rechazadas por talento humano</h2>
	<div class="elementos"></div>
</div>

<div id="R8">
	<h2>Solicitudes devueltas por talento humano</h2>
	<div class="elementos"></div>
</div>

<!-- <div id="R12">
	<h2>Solicitudes aceptadas para certificar</h2>
	<div class="elementos"></div>
</div> -->

<div id="R13">
	<h2>Solicitudes certificicadas en dirección financiera</h2>
	<div class="elementos"></div>
</div>

<div id="R14">
	<h2>Solicitudes para generar informe</h2>
	<div class="elementos"></div>
</div>

<!-- <div id="R15">
	<h2>Solicitudes para notificación de replicación</h2>
	<div class="elementos"></div>
</div> -->

<div id="R16">
	<h2>Solicitudes para generar formato réplica</h2>
	<div class="elementos"></div>
</div>

<div id="R17">
	<h2>Solicitudes para entrega de formato replica</h2>
	<div class="elementos"></div>
</div>

			
<?php 	
	$res_area = $cc->obtenerAreaUsuario($conexion, $identificador);
	$area= pg_fetch_assoc($res_area);
	
	$codigo_area="".$area['id_area']."-".$area['id_area_padre']."-TODAS";
	
	$res = $cc->obtenerRequerimientosUsuario($conexion,null,null,null,$identificador,$estadoInicio,$estadoFin,$codigo_area,'');
	while($fila = pg_fetch_assoc($res)){
		$pagina="verRequerimiento";
		$categoria="";
		switch ($fila['estado_requerimiento']){
			case 0: $pagina="descargarFormatoReplica"; $categoria = 'R0'; break;
			case 1: $pagina="descargarFormatoReplica"; $categoria = 'R1'; break;
			case 6: $pagina="descargarFormatoReplica"; $categoria = 'R6'; break;
			case 7: $pagina="descargarFormatoReplica"; $categoria = 'R7'; break;
			case 8: $pagina="descargarFormatoReplica"; $categoria = 'R8'; break;
			case 11: $pagina="descargarFormatoReplica"; $categoria = 'R11'; break;
			//case 12: $pagina="descargarFormatoReplica"; $categoria = 'R12'; break;
			case 13: $pagina="descargarFormatoReplica"; $categoria = 'R13'; break;
			case 14: $pagina="descargarFormatoReplica"; $categoria = 'R14'; break;
			//case 15: $pagina="descargarFormatoReplica"; $categoria = 'R15'; break;
			case 16: $pagina="descargarFormatoReplica"; $categoria = 'R16'; break;
			case 17: $pagina="descargarFormatoReplica"; $categoria = 'R17'; break;
		}
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
	$("#R1 div> article").length == 0 ? $("#R1").remove():"";
	$("#R6 div> article").length == 0 ? $("#R6").remove():"";
	$("#R7 div> article").length == 0 ? $("#R7").remove():"";	
	$("#R8 div> article").length == 0 ? $("#R8").remove():"";
	$("#R11 div> article").length == 0 ? $("#R11").remove():"";
	//$("#R12 div> article").length == 0 ? $("#R12").remove():"";
	$("#R13 div> article").length == 0 ? $("#R13").remove():"";	
	$("#R14 div> article").length == 0 ? $("#R14").remove():"";
	//$("#R15 div> article").length == 0 ? $("#R15").remove():"";
	$("#R16 div> article").length == 0 ? $("#R16").remove():"";	
	$("#R17 div> article").length == 0 ? $("#R17").remove():"";						
});

</script>

