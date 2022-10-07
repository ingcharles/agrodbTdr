<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorPAPP.php';

$conexion = new Conexion();
$cd = new ControladorPAPP();
$res = $cd->listarSeguimientosRemitidos($conexion, $_SESSION['usuario'],$_POST['subProceso'],$_POST['asunto'],$_POST['fi'],$_POST['ff']);
$contador = 0;
$itemsFiltrados[] = array();
$estado = 0;
$cantidadCaracteres = 50;

while($fila = pg_fetch_assoc($res)){
	
	$cadenaDescipcion = strpos($fila['descripcion'],' ',$cantidadCaracteres);
	$cadenaActividad = strpos($fila['actividad'],' ',$cantidadCaracteres);
	
	$descripcion = (strlen($fila['descripcion'])>50?(substr($fila['descripcion'], 0, (($cadenaDescipcion)?$cadenaDescipcion:$cantidadCaracteres)).'...'):(strlen($fila['descripcion'])>0?$fila['descripcion']:'Sin asunto'));
	$actividad = (strlen($fila['actividad'])>50?(substr($fila['actividad'], 0, (($cadenaActividad)?$cadenaActividad:$cantidadCaracteres)).'...'):(strlen($fila['actividad'])>0?$fila['actividad']:'Sin asunto'));
	
	$itemsFiltrados[] = array('<tr
		id="'.$fila['id_item'].'"
		class="item"
		data-rutaAplicacion="poa"
		data-opcion="realizarComentarioSeguimiento"
		ondragstart="drag(event)"
		draggable="true"
		data-destino="detalleItem">
		<td style="white-space:nowrap;">
			<input name="idSeguimiento[]" value="'.$fila['id_seguimiento'].'" type="hidden">
			<b>'.$fila['id_item'].'</b>
		</td>
		<td>'.$descripcion.'</td>
		<td>'.$actividad.'</td>
		<td>'.$fila['trimestre'].'</td>
		<td>'.number_format($fila['porcentaje_avance'],2).'%</td>
		<td>'.number_format($fila['items_realizados'],2).'</td>
		<td>'.number_format($fila['items_solicitados'],2).'</td>
		<td>'.number_format($fila['porcentaje_cumplimiento'],2).'%</td>
		<td>'.($fila['estado']==4?'Aprobado':($fila['estado']==2?'Por revisar':($fila['estado']==3?'Enviado Admin':($fila['estado']==1 && $fila['observaciones']!=null?'Correción':'Corrección')))).'</td>
		</tr>');

	if($fila['estado']!=2){
		$estado = 1;
	}
}
?>
<div id="paginacion" class="normal">

</div>
<form id="enviarMatrizPlanta" data-rutaAplicacion="poa"	data-opcion="enviarSeguimientoPlantaCentral" data-destino="detalleItem" data-accionEnExito="#ventanaAplicacion #filtrar">
<table id="tablaItems">
	<thead>
		<tr>
			<th>Código</th>
			<th>Subproceso</th>
			<th>Actividad</th>
			<th>Trim.</th>
			<th>% avance</th>
			<th># realizado</th>
			<th># solicitado</th>
			<th>% cumplimiento</th>
			<th>Estado</th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>
<button id="botonEnviarRevision" type="submit" class="guardar">Aprobar Seguimiento Trimestral</button>
</form>

<script type="text/javascript"> 
var estado= <?php echo json_encode($estado); ?>;
var itemInicial = 0;
	
	$(document).ready(function(){
		construirPaginacion($("#paginacion"),<?php echo json_encode($itemsFiltrados);?>);

		if(estado == 0){
			$("#botonEnviarRevision").show();
			
		}else{
			$("#botonEnviarRevision").hide();
		}
	});
	
	$("#enviarMatrizPlanta").submit(function(event){
		event.preventDefault();
		ejecutarJson(this);
	});
	
</script>


