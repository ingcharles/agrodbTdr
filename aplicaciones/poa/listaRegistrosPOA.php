<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorPAPP.php';
	
	$fecha = getdate();
	
	$conexion = new Conexion();
	$cd = new ControladorPAPP();
	
	$res = $cd->listarRegistrosPOAUSuario($conexion, $_SESSION['usuario'], $fecha['year']);
	$contador = 0;	
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>
<header>
	<h1>Registros Proforma</h1>


</header>
<div id="estado"></div>

<form id="enviarSupervisar" data-rutaAplicacion="poa" data-opcion="enviarPOASupervisor" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR" >
		
		<div id="paginacion" class="normal"></div>

		<div data-linea="1">
			<table id="tablaItems">
				<thead>
					<tr>
						<th>#</th>
						<th>Subproceso</th>
						<th>Actividad</th>
						<!-- th>meta 1</th>
						<th>meta 2</th>
						<th>meta 3</th>
						<th>meta 4</th-->
						
					</tr>
				</thead>
				<tbody>
				<?php 
				
				$cantidadCaracteres = 50;
				
				while($fila = pg_fetch_assoc($res)){

				$cadenasubProceso = strpos($fila['subproceso'],' ',$cantidadCaracteres);
				$cadenaDescripcion = strpos($fila['descripcion'],' ',$cantidadCaracteres);

				//$subProceso = (strlen($fila['subproceso'])>50?(substr($fila['subproceso'], 0, (($cadenasubProceso)?$cadenasubProceso:$cantidadCaracteres)).'...'):(strlen($fila['subproceso'])>0?$fila['subproceso']:'Sin sub proceso'));
				//$descripcion = (strlen($fila['descripcion'])>50?(substr($fila['descripcion'], 0, (($cadenaDescripcion)?$cadenaDescripcion:$cantidadCaracteres)).'...'):(strlen($fila['descripcion'])>0?$fila['descripcion']:'Sin descripcion'));
				$subProceso = $fila['subproceso'];
				$descripcion =  $fila['descripcion'].': '.$fila['detalle_actividad'];
				
					echo '<tr id="'.$fila['id_item'].'">
					<td>'.++$contador.'<input name="item_id[]" value="'.$fila['id_item'] .'" type="hidden"></td>
					<td>'.$subProceso.'</td>
					<td>'.$descripcion.'</td>
					<!--td>'.$fila['meta1'].'</td>
					<td>'.$fila['meta2'].'</td>
					<td>'.$fila['meta3'].'</td>
					<td>'.$fila['meta4'].'</td-->
					</tr>';
				
				}		
				?>
				</tbody>
			</table>	
		</div>

	<div data-linea="6">
		<button type="submit" class="guardar">Enviar Proforma al Director/Coordinador</button>
	</div>
</form>
</body>
<script type="text/javascript">

	$("#enviarSupervisar").submit(function(event){
		event.preventDefault();
		ejecutarJson(this);
		//abrir($(this),event,false);	
		$("#_actualizar").click();
	});

	$(document).ready(function(){
		distribuirLineas();
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un ítem para revisarlo.</div>');
	});
</script>


