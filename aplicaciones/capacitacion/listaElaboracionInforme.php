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



<div id="R13">
	<h2>Solicitudes para elaborar informe</h2>
	<div class="elementos"></div>
</div>

<div id="R14">
	<h2>Solicitudes con informe elaborado</h2>
	<div class="elementos"></div>
</div>

<div id="R2">
	<h2>Solicitudes rechazadas por certificación</h2>
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
	
		$areaRevisorDistrital = pg_fetch_assoc($ca->buscarAreaPadrePorClasificacion($conexion, $zona, 'Dirección Distrital A'));
	
		$areaBusqueda = $areaRevisorDistrital['id_area'];
	}
		
	$res = $cc->obtenerRequerimientosRevisionProceso($conexion, null, null, null, $estadoInicio, $estadoFin, $areaBusqueda);
	
	while($fila = pg_fetch_assoc($res)){
		$categoria='';
		switch ($fila['estado_requerimiento']){
			case 2: $categoria = 'R2'; break;	
			case 13: $categoria = 'R13'; break;	
			case 14: $categoria = 'R14'; break;	
		}
		
		$archivoInforme = $fila['ruta_informe']!=''?'<a href='.$fila['ruta_informe'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Informe Generado</a>':'';
	 
		if($categoria!=''){
		$contenido = '<article
							id="'.$fila['id_requerimiento'].'"
							class="item"
							data-rutaAplicacion="capacitacion"
							data-opcion="abrirElaborarInforme"
							ondragstart="drag(event)"
							draggable="true"
							data-destino="detalleItem">
							<span class="ordinal">'.++$contador.'</span>
							<span><small>'.(strlen($fila['nombre_evento'])>40?(substr($fila['nombre_evento'],0,40).'...'):(strlen($fila['nombre_evento'])>0?$fila['nombre_evento']:'')).'</small></br></span>
							<span>'.$archivoInforme.'</span>
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

		$("#R2 div> article").length == 0 ? $("#R2").remove():"";
		$("#R13 div> article").length == 0 ? $("#R13").remove():"";
		$("#R14 div> article").length == 0 ? $("#R14").remove():"";
							
	});
</script>