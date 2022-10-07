<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAreas.php';
require_once '../../clases/ControladorCapacitacion.php';
require_once '../../clases/ControladorAplicaciones.php';

$conexion = new Conexion();	
$caa = new ControladorAreas();
$cc = new ControladorCapacitacion();
$identificador = $_SESSION['usuario'];

$estadoInicio=1;
$estadoFin=12;

?>

<header>
	<h1>Administración capacitación</h1>
	<nav>
		<?php			
			
		    $ca = new ControladorAplicaciones();
			$res = $ca->obtenerAccionesPermitidas($conexion, $_POST["opcion"], $_SESSION['usuario']);			
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

<div id="R11">
	<h2>Solicitudes por aprobar en talento humano</h2>
	<div class="elementos"></div>
</div>

<div id="R12">
	<h2>Solicitudes aprobadas por talento humano</h2>
	<div class="elementos"></div>
</div>

<div id="R1">
	<h2>Solicitudes rechazadas por talento humano</h2>
	<div class="elementos"></div>
</div>



<?php 

	$areaUsuario = pg_fetch_assoc($caa->areaUsuario($conexion, $identificador));
	$areaRecursiva = pg_fetch_assoc($caa->buscarAreaResponsablePorUsuarioRecursivo($conexion, $areaUsuario['id_area']));
	
	$tipoArea = $areaRecursiva['clasificacion'];
	$arrayAreas = explode(',', $areaRecursiva['path']);
	
	if($tipoArea == 'Planta Central'){
		$areasRevision = $caa->buscarAreaPadrePorClasificacion($conexion, 'DE', 'Planta Central');
		while ($fila = pg_fetch_assoc($areasRevision)){
			$areaBusqueda .= $fila['id_area']."-";
		}
		$areaBusqueda = rtrim($areaBusqueda,"-");
	}else{
		$zona = $arrayAreas[2];
		$areaRevisorDistrital = pg_fetch_assoc($caa->buscarAreaPadrePorClasificacion($conexion, $zona, 'Dirección Distrital A'));
		$areaBusqueda = $areaRevisorDistrital['id_area'];
	}

		
	$res = $cc->obtenerRequerimientosRevisionProceso($conexion, null, null, null, $estadoInicio, $estadoFin, $areaBusqueda);
	
	while($fila = pg_fetch_assoc($res)){
		$categoria='';
		switch ($fila['estado_requerimiento']){
			case 1: $categoria = 'R1'; break;
			case 11: $categoria = 'R11'; break;
			case 12: $categoria = 'R12'; break;
		}
		if($categoria!=''){
			$contenido = '<article
							id="'.$fila['id_requerimiento'].'"
							class="item"
							data-rutaAplicacion="capacitacion"
							data-opcion="aprobarRequerimientoTH"
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

	$("#R1 div> article").length == 0 ? $("#R1").remove():"";
	$("#R11 div> article").length == 0 ? $("#R11").remove():"";
	$("#R12 div> article").length == 0 ? $("#R12").remove():"";
						
});
</script>