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
<table>

<tr>
	<td class="botonesTabla">
		<form id="generarReporteExcel" action="aplicaciones/vacacionesPermisos/generarReporteSaldoLiquidacionExcel.php" target="_blank" method="post">
	
			<input type="hidden" name="identificador" value="<?php echo $identificador;?>"/>
			<input type="hidden" name="estadoSaldo" value="<?php echo $estadoSaldo;?>"/>
			<input type="hidden" name="apellidoUsuario" value="<?php echo $apellido;?>"/>
			<input type="hidden" name="nombreUsuario" value="<?php echo $nombre;?>"/>
			<input type="hidden" name="area" value="<?php echo $area;?>"/>
			
		<button id="btnReporteExcel" type="submit" class="guardar">Generar reporte excel</button>
	
	</form>
	</td>
	<td>
	<form id="generarReportePDF" data-rutaAplicacion="vacacionesPermisos"	data-opcion="generarReporteSaldoLiquidacionPDF" data-destino="detalleItem">
	
			<input type="hidden" name="identificador" value="<?php echo $identificador;?>"/>
			<input type="hidden" name="estadoSaldo" value="<?php echo $estadoSaldo;?>"/>
			<input type="hidden" name="apellidoUsuario" value="<?php echo $apellido;?>"/>
			<input type="hidden" name="nombreUsuario" value="<?php echo $nombre;?>"/>
			<input type="hidden" name="area" value="<?php echo $area;?>"/>
			
		<button id="btnReportePDF" type="submit" class="guardar alineacion">Generar reporte pdf</button>
	
	</form>
	</td>
</tr>

</table>

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
<?php 
        $contador = 0; //$listaReporteFuncionario
        $itemsFiltrados[] = array();
		while($fila = pg_fetch_assoc($listaReporte)){
				$opcion='formularioLiquidacionesVacaciones';
			
			$identifi=$fila['identificador'].'.'.$estadoSaldo.'.'.$fila['id_liquidacion_vacaciones'];
			echo '<tr 
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
				</tr>';
			}
			
?>			

</table>

</body>

<script type="text/javascript"> 

	$(document).ready(function(){
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un registro para revisarlo.</div>');		
	});

	$("#generarReportePDF").submit(function(event){
		abrir($(this),event,false);	
	});
	
</script>
</html>
