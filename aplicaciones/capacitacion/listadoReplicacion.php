<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCapacitacion.php';
require_once '../../clases/ControladorAplicaciones.php';

$conexion = new Conexion();
$cc = new ControladorCapacitacion();

$identificador = $_SESSION['usuario'];
$estadoInicio = 14;
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


<div id="R14">
	<h2>Solicitudes por asignar replicación</h2>
	<div class="elementos"></div>
</div>

<div id="R15">
	<h2>Solicitudes con replicación asignada</h2>
	<div class="elementos"></div>
</div>

<div id="R20">
	<h2>Solicitudes para revisión procedimiento/manual</h2>
	<div class="elementos"></div>
</div>

<?php 

	$res = $cc->obtenerRequerimientos($conexion,null,null,null,null,null,null, $estadoInicio, $estadoFin, null);

	while($fila = pg_fetch_assoc($res)){
		switch ($fila['estado_requerimiento']){
			case 14: 
				$resu = $cc->obtenerRequerimientos($conexion,null,null,null,null,null,null, $fila['estado_requerimiento'], $fila['estado_requerimiento'], $identificador);
				while($filas = pg_fetch_assoc($resu)){
					$categoria = 'R14'; $pagina="asignarReplicantes";
					$archivoInforme = $fila['ruta_informe']==''? '<span class="alerta">Por generar informe</span>':'<a href='.$fila['ruta_informe'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Informe Generado</a>';
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
							<span>'.$archivoInforme.'</span>
							<aside><small> Desde: '.$fila['fecha_inicio'].'<br/> Hasta: '.$fila['fecha_fin'].'</small></aside>
							</article>';
				}
				?>
							<script type="text/javascript">
								var contenido = <?php echo json_encode($contenido);?>;
								var categoria = <?php echo json_encode($categoria);?>;
								$("#"+categoria+" div.elementos").append(contenido);
							</script>
				<?php
			break;
			case 15: 
			case 19:
				$resu = $cc->obtenerRequerimientos($conexion,null,null,null,null,null,null, $fila['estado_requerimiento'], $fila['estado_requerimiento'], $identificador);
				while($filas = pg_fetch_assoc($resu)){
					$categoria = 'R15'; $pagina="revisionAsignarReplicantes"; 
					$archivoInforme = $fila['ruta_informe']==''? '<span class="alerta">Por generar informe</span>':'<a href='.$fila['ruta_informe'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Informe Generado</a>';
					
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
								<span>'.$archivoInforme.'</span>
								<aside><small> Desde: '.$fila['fecha_inicio'].'<br/> Hasta: '.$fila['fecha_fin'].'</small></aside>
								</article>';
				}
				?>
								<script type="text/javascript">
									var contenido = <?php echo json_encode($contenido);?>;
									var categoria = <?php echo json_encode($categoria);?>;
									$("#"+categoria+" div.elementos").append(contenido);
								</script>
				<?php
				
				
			break;
				
			case 20: 
				$resu = $cc->listarRevisionArchivoReplica($conexion, 'cargado',$fila['id_requerimiento']);
				if(pg_num_rows($resu)>0){
					$pagina="verificarArchivoProcedimiento";
					$categoria = 'R20';
					$archivoInforme = $fila['ruta_informe']==''? '<span class="alerta">Por generar informe</span>':'<a href='.$fila['ruta_informe'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Informe Generado</a>';
				
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
								<span>'.$archivoInforme.'</span>
								<aside><small> Desde: '.$fila['fecha_inicio'].'<br/> Hasta: '.$fila['fecha_fin'].'</small></aside>
								</article>';
				}
				?>
								<script type="text/javascript">
									var contenido = <?php echo json_encode($contenido);?>;
									var categoria = <?php echo json_encode($categoria);?>;
									$("#"+categoria+" div.elementos").append(contenido);
								</script>
				<?php
				
				
			 break;
		}
	}
?>

<script>	
	$(document).ready(function(){
		$("#listadoItems").addClass("comunes");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui una solicitud para revisarla.</div>');
		$("#R14 div> article").length == 0 ? $("#R14").remove():"";
		$("#R15 div> article").length == 0 ? $("#R15").remove():"";
		$("#R20 div> article").length == 0 ? $("#R20").remove():"";								
	});
</script>