<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorPAPP.php';

$fecha = getdate();

$conexion = new Conexion();
$cd = new ControladorPAPP();

function reemplazarCaracteres($cadena){
	$cadena = str_replace('á', 'a', $cadena);
	$cadena = str_replace('é', 'e', $cadena);
	$cadena = str_replace('í', 'i', $cadena);
	$cadena = str_replace('ó', 'o', $cadena);
	$cadena = str_replace('ú', 'u', $cadena);
	$cadena = str_replace('ñ', 'n', $cadena);

	$cadena = str_replace('Á', 'A', $cadena);
	$cadena = str_replace('É', 'E', $cadena);
	$cadena = str_replace('Í', 'I', $cadena);
	$cadena = str_replace('Ó', 'O', $cadena);
	$cadena = str_replace('Ú', 'U', $cadena);
	$cadena = str_replace('Ñ', 'N', $cadena);

	return $cadena;
}

if($_POST['estadoProceso']==1){
	$res = $cd->listarRegistrosPOAAprobados($conexion, 4,$_SESSION['usuario'], $fecha['year']);
			
}else{
	
	$res = $cd->listarRegistrosCerrados($conexion, 4,$_SESSION['usuario']);
}
	$contador = 0;
	$itemsFiltrados[] = array();
	$cantidadCaracteres = 50;
	
	while($fila = pg_fetch_assoc($res)){
		
		/*$cadenaProceso = strpos($fila['proceso'],' ',$cantidadCaracteres);
		$cadenaSubProceso = strpos($fila['subproceso'],' ',$cantidadCaracteres);
		$cadenaActividad = strpos($fila['actividad'],' ',$cantidadCaracteres);
		
		$proceso = (strlen($fila['proceso'])>50?(substr($fila['proceso'], 0, (($cadenaProceso)?$cadenaProceso:$cantidadCaracteres)).'...'):(strlen($fila['proceso'])>0?$fila['proceso']:'Sin asunto'));
		$subProceso= (strlen($fila['subproceso'])>50?(substr($fila['subproceso'], 0, (($cadenaSubProceso)?$cadenaSubProceso:$cantidadCaracteres)).'...'):(strlen($fila['subproceso'])>0?$fila['subproceso']:'Sin asunto'));
		$actividad = (strlen($fila['actividad'])>50?(substr($fila['actividad'], 0, (($cadenaActividad)?$cadenaActividad:$cantidadCaracteres)).'...'):(strlen($fila['actividad'])>0?$fila['actividad']:'Sin asunto'));
	*/
		
		$cadenaProceso = reemplazarCaracteres($fila['proceso']);
		$proceso = (strlen($cadenaProceso)>$cantidadCaracteres?(substr($cadenaProceso,0,$cantidadCaracteres).'...'):$cadenaProceso);
		
		$cadenaSubproceso = reemplazarCaracteres($fila['subproceso']);
		$subProceso = (strlen($cadenaSubproceso)>$cantidadCaracteres?(substr($cadenaSubproceso,0,$cantidadCaracteres).'...'):$cadenaSubproceso);
		
		$cadenaActividad = reemplazarCaracteres($fila['actividad']);
		$actividad = (strlen($cadenaActividad)>$cantidadCaracteres?(substr($cadenaActividad,0,$cantidadCaracteres).'...'):$cadenaActividad);
				
		
		$itemsFiltrados[] = array('<tr
		id="'.$fila['id_item'].'_'.$_POST['estadoProceso'].'"
		class="item"
		data-rutaAplicacion="poa"
		data-opcion="actualizarMatrizPOA"
		ondragstart="drag(event)"
		draggable="true"
		data-destino="detalleItem">
		<td style="white-space:nowrap;"><b>'.$fila['id_item'].'</b></td>
		<td>'.$proceso.'</td>
		<td>'.$subProceso.'</td>
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

