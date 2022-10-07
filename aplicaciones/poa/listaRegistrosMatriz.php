<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorPAPP.php';
	
	$fecha = getdate();
	
	$conexion = new Conexion();
	$cd = new ControladorPAPP();
	
	$res = $cd->listarRegistrosMatrizUSuario($conexion, $_SESSION['usuario'], $fecha['year']);
	$contador = 0;
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>
<header>
	<h1>Registros matriz presupuesto</h1>


</header>

<div id="estado"></div>


<form id="enviarSupervisar" data-rutaAplicacion="poa" data-opcion="enviarMatrizSupervisor" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">

	<div id="paginacion" class="normal"></div>

<table id="tablaItems">
	<thead>
		<tr>
			<th>Código</th>
			<th>SubProceso</th>
			<th>Actividad</th>
			<th>Gasto</th>
			<th>Total</th>
			
		</tr>
	</thead>
	<tbody>
	<?php 
	$cantidadCaracteres = 50;
	
	while($fila = pg_fetch_assoc($res)){
		
		$cadenaDescripcion = strpos($fila['descripcion'],' ',$cantidadCaracteres);
		$cadenaActividad = strpos($fila['codigo_actividad'],' ',$cantidadCaracteres);
		$cadenaDetalleGasto = strpos($fila['detalle_gasto'],' ',$cantidadCaracteres);
		
		$descripcion = (strlen($fila['descripcion'])>$cantidadCaracteres?(substr($fila['descripcion'], 0, (($cadenaDescripcion)?$cadenaDescripcion:$cantidadCaracteres)).'...'):(strlen($fila['descripcion'])>0?$fila['descripcion']:'Sin asunto'));
		$codigoActividad = (strlen($fila['codigo_actividad'])>$cantidadCaracteres?(substr($fila['codigo_actividad'], 0, (($cadenaActividad)?$cadenaActividad:$cantidadCaracteres)).'...'):(strlen($fila['codigo_actividad'])>0?$fila['codigo_actividad']:'Sin asunto'));
		$detalleGasto = (strlen($fila['detalle_gasto'])>$cantidadCaracteres?(substr($fila['detalle_gasto'], 0, (($cadenaDetalleGasto)?$cadenaDetalleGasto:$cantidadCaracteres)).'...'):(strlen($fila['detalle_gasto'])>0?$fila['detalle_gasto']:'Sin gasto'));
	
		echo '<tr id="'.$fila['id_item'].'">
		<td style="white-space:nowrap;"><b>'.$fila['id_item'].'</b><input name="item_id[]" value="'.$fila['id_item'] .'" type="hidden"><input name="codigo_item[]" value="'.$fila['codigo_item'] .'" type="hidden"></td>
		<td>'.$descripcion.'</td>
		<td>'.$codigoActividad.'</td>
		<td>'.$detalleGasto.'</td>
		<td>'.$fila['total'].'</td>
		</tr>';
	
		
	}
	
	
	?>
	</tbody>
</table>
<span>

<button type="submit" class="guardar">Enviar Matriz al Director/Coordinador</button>
</span>
</form>
</body>
<script type="text/javascript">

	$("#enviarSupervisar").submit(function(event){

		//abrir($(this),event,false);
		event.preventDefault();
		ejecutarJson(this);	
		
	});

	$(document).ready(function(){
		$("#detalleItem").html('<div class="mensajeInicial">Registro de items presupuestarios.</div>');
	});
</script>


