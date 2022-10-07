<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorPAPP.php';
	
	$fecha = getdate();
	
	$conexion = new Conexion();
	$cd = new ControladorPAPP();
	//$res = $cd->listarPOAAprobados($conexion, $_SESSION['usuario'],$_POST['areaDireccion'],$_POST['asunto'],$_POST['fi'],$_POST['ff'],$_POST['estadoRegistro']);
	//$res = $cd->listarPOAAprobados($conexion, $_SESSION['usuario'],$_POST['areaDireccion'],$_POST['asunto'],$_POST['fi'],$_POST['ff'],'');
	$res = $cd->listarPOARemitidosAdministrador($conexion, $_POST['areaDireccion'], $_POST['subproceso'], $_POST['asunto'],$_POST['fi'],$_POST['ff'], $_POST['estadoRegistro'], $fecha['year']);
	
	$contador = 0;
	$itemsFiltrados[] = array();
	
	$estado = 0;
	$cantidadCaracteres = 30;
	
	while($fila = pg_fetch_assoc($res)){
		
		$cadenaSubProceso = strpos($fila['subproceso'],' ',$cantidadCaracteres);
		$cadenaDescripcion = strpos($fila['descripcion'],' ',$cantidadCaracteres);
		
		$subProceso = (strlen($fila['subproceso'])>50?(substr($fila['subproceso'], 0, (($cadenaSubProceso)?$cadenaSubProceso:$cantidadCaracteres)).'...'):(strlen($fila['subproceso'])>0?$fila['subproceso']:'Sin sub proceso'));
		$descripcion = (strlen($fila['descripcion'])>50?(substr($fila['descripcion'], 0, (($cadenaDescripcion)?$cadenaDescripcion:$cantidadCaracteres)).'...'):(strlen($fila['descripcion'])>0?$fila['descripcion']:'Sin descripcion'));
		
		if($_POST['estadoRegistro']==3){
			if(($fila['estado']==1 && $fila['observaciones']!= null)|| $fila['estado']==2 || $fila['estado']==3){
				$itemsFiltrados[] = array('<tr
					id="'.$fila['id_item'].'_'.$_POST['estadoRegistro'].'"
					class="item"
					data-rutaAplicacion="poa"
					data-opcion="realizarComentarioItemAdmin"
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
					<td>'.($fila['estado']==4?'<span class= "exito">Aprobado</span>':(($fila['estado']==3 && $fila['revisado']=='f')?'Por revisar':(($fila['estado']==3 && $fila['revisado']=='t')?'Revisado':($fila['estado']==2?'<span class= "advertencia">Revisión Coordinador</span>':($fila['estado']==1 && $fila['observaciones']!=null?'<span class = "alerta">Correción</span>':'Creado'))))).'</td>
				</tr>');
			
				if($fila['estado']!=3){
					$estado = 1;
				}
			}
		}else if($_POST['estadoRegistro']==4){
			if($fila['estado']==4){
				$itemsFiltrados[] = array('<tr
						id="'.$fila['id_item'].'_'.$_POST['estadoRegistro'].'"
						class="item"
						data-rutaAplicacion="poa"
						data-opcion="realizarComentarioItemAdmin"
						ondragstart="drag(event)"
						draggable="true"
						data-destino="detalleItem">
						<td style="white-space:nowrap;"><b>'.$fila['id_item'].'<input name="item_id[]" value="'.$fila['id_item'].'" type="hidden"></b></td>
						<td>'.(strlen($fila['subproceso'])>50?(substr($fila['subproceso'],0,50).'...'):(strlen($fila['subproceso'])>0?$fila['subproceso']:'Sin sub proceso')).'</td>
						<td>'.(strlen($fila['descripcion'])>50?(substr($fila['descripcion'],0,50).'...'):(strlen($fila['descripcion'])>0?$fila['descripcion']:'Sin descripcion')).'</td>
						<!--td>'.$fila['meta1'].'</td>
						<td>'.$fila['meta2'].'</td>
						<td>'.$fila['meta3'].'</td>
						<td>'.$fila['meta4'].'</td-->
						<td>'.($fila['estado']==4?'<span class= "exito">Aprobado</span>':(($fila['estado']==3 && $fila['revisado']=='f')?'Por revisar':(($fila['estado']==3 && $fila['revisado']=='t')?'Revisado':($fila['estado']==2?'<span class= "advertencia">Revisión Coordinador</span>':($fila['estado']==1 && $fila['observaciones']!=null?'<span class = "alerta">Correción</span>':'Creado'))))).'</td>
					</tr>');
				
				$estado = 1;
			}
		}
	}
?>
<div id="paginacion" class="normal">

</div>
<form id="enviarPlanta" data-rutaAplicacion="poa"	data-opcion="aprobadoPlantaCentral" data-destino="detalleItem">
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
<button id="botonEnviarRevision" type="submit" class="guardar" id="btnGuardar">Aprobar Proforma </button>
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
		/*if($("#estadoRegistro").val()==4){
			$("#btnGuardar").attr("disabled", "disabled");
			}
		else{
			$("#btnGuardar").removeAttr("disabled");
			}*/
	});
	
	$("#enviarPlanta").submit(function(event){

		abrir($(this),event,false);
    
	});

	
	
</script>


