<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorVacaciones.php';

	$conexion = new Conexion();
	$cv = new ControladorVacaciones();
	
	$identificador = $_POST['identificador'];
	$estadoSaldo = $_POST['estadoSaldo'];
	$apellido = $_POST['apellidoUsuario'];
	$nombre = $_POST['nombreUsuario'];
	$area = $_POST['area'];
	
	$listaReporte = $cv->filtroObtenerReporteFuncionariosLiquidar($conexion, $identificador, $estadoSaldo, $apellido, $nombre, $area, 'unico');
	
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>

<div id="paginacion" class="normal">
 </div>
<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th>Identificador</th>
			<th>Nombre funcionario</th>
			<th>Año</th>
			<th>Saldo</th>
		</tr>
	</thead>
	<tbody>
	</tbody>
	</table>
<?php 
        $contador = 0; //$listaReporteFuncionario
        $itemsFiltrados[] = array();
		while($fila = pg_fetch_assoc($listaReporte)){
			if($estadoSaldo == 'Liquidado'){
				$opcion='formularioLiquidacionesVacaciones';
			}else{
				$opcion='formularioXLiquidarVacaciones';
			}
			
			$identifi=$fila['identificador'].'.'.$estadoSaldo.'.'.$fila['id_liquidacion_vacaciones'];
			$itemsFiltrados[]= array( '<tr 
						id="'.$identifi.'"
						class="item"
						data-rutaAplicacion="vacacionesPermisos"
						data-opcion="'.$opcion.'" 
						ondragstart="drag(event)" 
						draggable="true" 
						data-destino="detalleItem">
					<td>'.++$contador.'</td>
					<td style="white-space:nowrap;"><b>'.$fila['identificador'].'</b></td>
					<td>'.$fila['apellido'].' '.$fila['nombre'].'</td>
					<td>'.$fila['anios_liquidados'].'</td>
					<td align="right">'.$cv->devolverTiempoFormateadoDHM($fila['minutos_liquidados']) .'</td>
				</tr>');
			}
			
?>			


</body>

<script type="text/javascript"> 

	$(document).ready(function(){
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un registro para revisarlo.</div>');		
		construirPaginacion($("#paginacion"),<?php echo json_encode($itemsFiltrados);?>);	
	});

	$("#generarReportePDF").submit(function(event){
		abrir($(this),event,false);	
	});
	
</script>
</html>
