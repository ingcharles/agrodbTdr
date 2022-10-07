<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorPAPP.php';
	
	$fecha = getdate();
	
	$conexion = new Conexion();
	$cd = new ControladorPAPP();
	$res = $cd->listarMatrizAprobada($conexion, $_POST['areaDireccion'], $_POST['subproceso'],$_POST['asunto'],$_POST['fi'],$_POST['ff'],$_POST['estadoRegistro'], $fecha['year']);
	$contador = 0;
	$itemsFiltrados[] = array();
	$cantidadCaracteres = 30;
	
	while($fila = pg_fetch_assoc($res)){
		
		$cadenaDescripcion = strpos($fila['descripcion'],' ',$cantidadCaracteres);
		$cadenaActividad = strpos($fila['actividad'],' ',$cantidadCaracteres);
		
		$descripcion = (strlen($fila['descripcion'])>50?(substr($fila['descripcion'], 0, (($cadenaDescripcion)?$cadenaDescripcion:$cantidadCaracteres)).'...'):(strlen($fila['descripcion'])>0?$fila['descripcion']:'Sin asunto'));
		$actividad = (strlen($fila['actividad'])>50?(substr($fila['actividad'], 0, (($cadenaActividad)?$cadenaActividad:$cantidadCaracteres)).'...'):(strlen($fila['actividad'])>0?$fila['actividad']:'Sin asunto'));
		
		$itemsFiltrados[] = array('<tr
			id="'.$fila['id_item'].'_'.$_POST['estadoRegistro'].'"
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
			<td>'.($fila['estado']==4?'<span class= "exito">Aprobado</span>':($fila['estado']==3 ?'Por revisar':($fila['estado']==2?'<span class= "advertencia">Revisión Coordinador</span>':($fila['estado']==1 && $fila['observaciones']!=null?'<span class = "alerta">Correción</span>':'Creado')))).'</td>
			</tr>');
		//<td>'.($fila['estado']==4?'<span class= "exito">Aprobado</span>':(($fila['estado']==3 && $fila['revisado']=='f')?'Por revisar':(($fila['estado']==3 && $fila['revisado']=='t')?'Revisado':($fila['estado']==2?'<span class= "advertencia">Revisión Coordinador</span>':($fila['estado']==1 && $fila['observaciones']!=null?'<span class = "alerta">Correción</span>':'Creado'))))).'</td>
	}
?>
<div id="paginacion" class="normal">

</div>
<form id="enviarMatrizPlanta" data-rutaAplicacion="poa"	data-opcion="aprobadoMatrizPlantaCentral" data-destino="detalleItem">
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
<button type="submit" class="guardar" id="btnGuardar">Aprobar Matriz Presupuesto</button>
</form>
<script type="text/javascript"> 
	var itemInicial = 0;
	
	$(document).ready(function(){
		construirPaginacion($("#paginacion"),<?php echo json_encode($itemsFiltrados);?>);
		if($("#estadoRegistro").val()==4){
			$("#btnGuardar").attr("disabled", "disabled");
			}
		else{
			$("#btnGuardar").removeAttr("disabled");
			}
	});
	
	$("#enviarMatrizPlanta").submit(function(event){
		abrir($(this),event,false);    
	});

	
	
</script>


