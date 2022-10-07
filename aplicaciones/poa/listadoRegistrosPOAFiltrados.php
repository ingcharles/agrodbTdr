<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorPAPP.php';
	
	$fecha = getdate();
	
	$conexion = new Conexion();
	$cd = new ControladorPAPP();
	$res = $cd->listarPOAAprobadosPlanta($conexion, 4,$_SESSION['usuario'], $fecha['year']);
	$contador = 0;
	$itemsFiltrados[] = array();
	$cantidadCaracteres = 30;
	
	while($fila = pg_fetch_assoc($res)){
		
		$cadenaProceso = strpos($fila['proceso'],' ',$cantidadCaracteres);
		$cadenaSubProceso = strpos($fila['subproceso'],' ',$cantidadCaracteres);
		$cadenaActividad = strpos($fila['actividad'],' ',$cantidadCaracteres);
		
		$proceso = (strlen($fila['proceso'])>50?(substr($fila['proceso'], 0, (($cadenaProceso)?$cadenaProceso:$cantidadCaracteres)).'...'):(strlen($fila['proceso'])>0?$fila['proceso']:'Sin asunto'));
		$subproceso = (strlen($fila['subproceso'])>50?(substr($fila['subproceso'], 0, (($cadenaSubProceso)?$cadenaSubProceso:$cantidadCaracteres)).'...'):(strlen($fila['subproceso'])>0?$fila['subproceso']:'Sin asunto'));
		$actividad =  (strlen($fila['actividad'])>50?(substr($fila['actividad'], 0, (($cadenaActividad)?$cadenaActividad:$cantidadCaracteres)).'...'):(strlen($fila['actividad'])>0?$fila['actividad']:'Sin asunto'));
		
		$itemsFiltrados[] = array('<tr
			id="'.$fila['id_item'].'"
			class="item"
			data-rutaAplicacion="poa"
			data-opcion="nuevoItemPresupuestario"
			ondragstart="drag(event)"
			draggable="true"
			data-destino="detalleItem">
			<td style="white-space:nowrap;"><b>'.$fila['id_item'].'</b></td>
			<td>'.$proceso.'</td>
			<td>'.$subproceso.'</td>
			<td>'.$actividad.'</td>
			</tr>');
	
	}
?>
<div id="paginacion" class="normal">

</div>

<table id="tablaItems">
	<thead>
		<tr>
			<th>Código</th>
			<th>Proceso</th>
			<th>Subproceso</th>
			<th>Actividad</th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>


<script type="text/javascript"> 
	var itemInicial = 0;
	
	$(document).ready(function(){
		construirPaginacion($("#paginacion"),<?php echo json_encode($itemsFiltrados);?>);
	});
	
	
	
</script>

