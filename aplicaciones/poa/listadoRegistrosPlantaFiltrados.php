<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorPAPP.php';

$conexion = new Conexion();
$cd = new ControladorPAPP();
$res = $cd->listarPOARemitidos($conexion, $_SESSION['usuario'],$_POST['subProceso'],$_POST['asunto'],$_POST['fi'],$_POST['ff'],'', $_POST['anio']);
$contador = 0;
$itemsFiltrados[] = array();

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


$estado = 0;
$cantidadCaracteres = 50;


while($fila = pg_fetch_assoc($res)){
	
	
	if(($fila['estado']==1 && $fila['observaciones']!= null)|| $fila['estado']==2){
		
		/*$cadenaProceso = strpos($fila['subproceso'],' ',$cantidadCaracteres);
		$cadenaDescripcion = strpos($fila['descripcion'],' ',$cantidadCaracteres);
		
		$subProceso = (strlen($fila['subproceso'])>$cantidadCaracteres?(substr($fila['subproceso'], 0, (($cadenaProceso)?$cadenaProceso:$cantidadCaracteres)).'...'):(strlen($fila['subproceso'])>0?$fila['subproceso']:'Sin subproceso'));
		$descripcion = (strlen($fila['descripcion'])>$cantidadCaracteres?(substr($fila['descripcion'],0, (($cadenaDescripcion)?$cadenaDescripcion:$cantidadCaracteres)).'...'):(strlen($fila['descripcion'])>0?$fila['descripcion']:'Sin descripcion'));
		*/
		
		$cadenaSubproceso = reemplazarCaracteres($fila['subproceso']);
		$subProceso = (strlen($cadenaSubproceso)>$cantidadCaracteres?(substr($cadenaSubproceso,0,$cantidadCaracteres).'...'):$cadenaSubproceso);
		
		
		$cadenaDescripcion = reemplazarCaracteres($fila['descripcion']);
		$descripcion = (strlen($cadenaDescripcion)>$cantidadCaracteres?(substr($cadenaDescripcion,0,$cantidadCaracteres).'...'):$cadenaDescripcion);
		
		
		$itemsFiltrados[] = array('<tr
			id="'.$fila['id_item'].'"
			class="item"
			data-rutaAplicacion="poa"
			data-opcion="realizarComentarioItemCoord"
			ondragstart="drag(event)"
			draggable="true"
			data-destino="detalleItem">
			<td style="white-space:nowrap;"><b>'.$fila['id_item'].'<input name="item_id[]" value="'.$fila['id_item'].'" type="hidden"></b></td>
			<td>'.$subProceso.'</td>
			<td>'.$descripcion.'</td>
			
			<!--td>'.$fila['meta1'].'</td>
			<td>'.$fila['meta2'].'</td>
			<td>'.$fila['meta3'].'</td>
			<td>'.$fila['meta4'].'</td-->
			<td>'.($fila['estado']==4?'<span class= "exito">Aprobado</span>':($fila['estado']==2?'Por revisar':($fila['estado']==3?'<span class= "advertencia">Enviado Administrador</span>':($fila['estado']==1 && $fila['observaciones']!=null?'<span class = "alerta">Correción</span>':'Creado')))).'</td>
			</tr>');
		
		if($fila['estado']!=2){
			$estado = 1;
		}
	}
}
?>
<div id="paginacion" class="normal">

</div>
<form id="enviarPlanta" data-rutaAplicacion="poa" data-opcion="enviarPlantaCentral" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
<table id="tablaItems">
	<thead>
		<tr>
			<th>Código</th>
			<th>Subproceso</th>
			<th>Actividad</th>
			<!-- th>meta 1</th>
			<th>meta 2</th>
			<th>meta 3</th>
			<th>meta 4</th-->
			<th>Estado</th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>
<button id="botonEnviarRevision" type="submit" class="guardar">Aprobar Proforma </button>
</form>
<script type="text/javascript"> 
var estado= <?php echo json_encode($estado); ?>;
var itemInicial = 0;
	
	$(document).ready(function(){
		$("#listadoItems").removeClass("comunes");
		construirPaginacion($("#paginacion"),<?php echo json_encode($itemsFiltrados);?>);

		if(estado == 0){
			$("#botonEnviarRevision").show();
			
		}else{
			$("#botonEnviarRevision").hide();
		}
	});
	
	$("#enviarPlanta").submit(function(event){
		event.preventDefault();
		ejecutarJson(this);	    
	});
	
</script>


