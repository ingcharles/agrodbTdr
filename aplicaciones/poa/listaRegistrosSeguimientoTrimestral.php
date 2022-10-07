<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorPAPP.php';

$conexion = new Conexion();
$cd = new ControladorPAPP();

if(date("n")>0 && date("n")<4){
	$trimestre = 1;
}else if(date("n")>3 && date("n")<7){
	$trimestre = 2;
}if(date("n")>6 && date("n")<10){
	$trimestre = 3;
}else{
	$trimestre = 4;
}
//$trimestre = 3;

$metas = $cd->listarRegistrosSeguimientoUsuario($conexion, $trimestre, $_SESSION['usuario']);
$contador = 0;
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>
	<header>
		<h1>Envío de Avance de Metas para Seguimiento Trimestral</h1>
	
	
	</header>
	<div id="estado"></div>
	
	<form id="enviarCoordinador" data-rutaAplicacion="poa" data-opcion="enviarSeguimientoCoordinador" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR" >
			
			<div id="paginacion" class="normal"></div>
	
			<div data-linea="1">
				<table id="tablaItems">
					<thead>
						<tr>
							<th>#</th>
							<th>Subproceso</th>
							<th>Actividad</th>
							<th>Trimestre</th>
							<th>Avance metas</th>
							<th>% avance</th>
							<th># realizado</th>
							<th># solicitado</th>
							<th>% cumplimiento</th>
						</tr>
					</thead>
					<tbody>
					<?php 
					
					$cantidadCaracteres = 50;
					
					while($fila = pg_fetch_assoc($metas)){
	
					$cadenasubProceso = strpos($fila['subproceso'],' ',$cantidadCaracteres);
					$cadenaDescripcion = strpos($fila['descripcion'],' ',$cantidadCaracteres);
	
					$subProceso = $fila['subproceso'];
					$descripcion =  $fila['descripcion'].': '.$fila['detalle_actividad'];
					
						echo '<tr id="'.$fila['id_seguimiento'].'">
						<td>'.++$contador.'<input name="item_id[]" value="'.$fila['id_seguimiento'] .'" type="hidden"></td>
						<td>'.$subProceso.'</td>
						<td>'.$descripcion.'</td>
						<td>'.$fila['trimestre'].'</td>
						<td>'.number_format($fila['avance_meta'],2).'</td>
						<td>'.number_format($fila['porcentaje_avance'],2).'%</td>
						<td>'.number_format($fila['items_realizados'],2).'</td>
						<td>'.number_format($fila['items_solicitados'],2).'</td>
						<td>'.number_format($fila['porcentaje_cumplimiento'],2).'%</td>
						</tr>';	
					}		
					?>
					</tbody>
				</table>	
			</div>
	
		<div data-linea="6">
			<button type="submit" class="guardar">Enviar Seguimiento Trimestral al Coordinador</button>
		</div>
		
	</form>
</body>

<script type="text/javascript">

	$("#enviarCoordinador").submit(function(event){
		event.preventDefault();
		ejecutarJson(this);
		$("#_actualizar").click();
	});

	$(document).ready(function(){
		distribuirLineas();
		$("#detalleItem").html('<div class="mensajeInicial">Envío de seguimiento trimestral.</div>');
	});
</script>