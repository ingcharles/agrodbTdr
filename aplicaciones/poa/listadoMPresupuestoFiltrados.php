<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorPAPP.php';
	
	$fecha = getdate();
	
	$conexion = new Conexion();
	$cd = new ControladorPAPP();

	$res = $cd->listarMatrizRemitida($conexion, $_SESSION['usuario'],$_POST['subProceso'],$_POST['asunto'],$_POST['fi'],$_POST['ff'], $fecha['year']);
	$contador = 0;
	$itemsFiltrados[] = array();
	$estado = 0;
	$cantidadCaracteres = 50;
	
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
	
	while($fila = pg_fetch_assoc($res)){
		$cadenaActividad = reemplazarCaracteres($fila['actividad']);
		$actividad = (strlen($cadenaActividad)>$cantidadCaracteres?(substr($cadenaActividad,0,$cantidadCaracteres).'...'):$cadenaActividad);
		
		
		$cadenaDescripcion = reemplazarCaracteres($fila['descripcion']);
		$descripcion = (strlen($cadenaDescripcion)>$cantidadCaracteres?(substr($cadenaDescripcion,0,$cantidadCaracteres).'...'):$cadenaDescripcion);
		
		/*
		$cadenaDescipcion = strpos($fila['descripcion'],' ',$cantidadCaracteres);
		$cadenaActividad = strpos($fila['actividad'],' ',$cantidadCaracteres);
		
		$descripcion = (strlen($fila['descripcion'])>50?(substr($fila['descripcion'], 0, (($cadenaDescipcion)?$cadenaDescipcion:$cantidadCaracteres)).'...'):(strlen($fila['descripcion'])>0?$fila['descripcion']:'Sin asunto'));
		$actividad = (strlen($fila['actividad'])>50?(substr($fila['actividad'], 0, (($cadenaActividad)?$cadenaActividad:$cantidadCaracteres)).'...'):(strlen($fila['actividad'])>0?$fila['actividad']:'Sin asunto'));
		*/
		
		$itemsFiltrados[] = array('<tr
			id="'.$fila['id_item'].'_2"
			class="item"
			data-rutaAplicacion="poa"
			data-opcion="realizarComentarioMatriz"
			ondragstart="drag(event)"
			draggable="true"
			data-destino="detalleItem">
			<td style="white-space:nowrap;"><b>'.$fila['id_item'].'<input name="item_id[]" value="'.$fila['id_item'].'" type="hidden"></b></td>
			<td>'.$descripcion.'</td>
			<td>'.$actividad.'</td>
			<td>'.$fila['total'].'</td>
			<td>'.$fila['count'].'</td>
			<td>'.($fila['estado']==4?'Aprobado':($fila['estado']==2?'Por revisar':($fila['estado']==3?'Enviado Admin':($fila['estado']==1 && $fila['observaciones']!=null?'Correción':'Corrección')))).'</td>
			</tr>');
	
		if($fila['estado']!=2){
			$estado = 1;
		}
	}
?>
<div id="paginacion" class="normal">

</div>
<form id="enviarMatrizPlanta" data-rutaAplicacion="poa"	data-opcion="enviarMatrizPlantaCentral" data-destino="detalleItem">
<table id="tablaItems">
	<thead>
		<tr>
			<th>Código</th>
			<th>Subproceso</th>
			<th>Actividad</th>
			<th>Total gasto</th>
			<th>No. Items </th>
			<th>Estado</th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>
<button id="botonEnviarRevision" type="submit" class="guardar">Aprobar Matriz Presupuesto</button>
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

		abrir($(this),event,false);
			
	    
	});
	
</script>


