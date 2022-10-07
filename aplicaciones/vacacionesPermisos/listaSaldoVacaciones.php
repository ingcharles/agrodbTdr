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
	
	$listaReporte = $cv->filtroObtenerReporteSaldoUsuario($conexion, $identificador, $estadoSaldo, $apellido, $nombre, $area, 'unico');
	
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
		<form id="generarReporteExcel" action="aplicaciones/vacacionesPermisos/generarReporteSaldoUsuarioExcel.php" target="_blank" method="post">
	
			<input type="hidden" name="identificador" value="<?php echo $identificador;?>"/>
			<input type="hidden" name="estadoSaldo" value="<?php echo $estadoSaldo;?>"/>
			<input type="hidden" name="apellidoUsuario" value="<?php echo $apellido;?>"/>
			<input type="hidden" name="nombreUsuario" value="<?php echo $nombre;?>"/>
			<input type="hidden" name="area" value="<?php echo $area;?>"/>
			
		<button id="btnReporteExcel" type="submit" class="guardar">Generar reporte excel</button>
	
	</form>
	</td>
	<td>
	<form id="generarReportePDF" data-rutaAplicacion="vacacionesPermisos"	data-opcion="generarReporteSaldoPDF" data-destino="detalleItem">
	
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

<table>
	<thead>
		<tr>
			<th>#</th>
			<th>Identificador</th>
			<th>Nombre funcionario</th>
			<th>Cantidad disponible</th>
		</tr>
	</thead>
	
<?php 
        $contador = 0; //$listaReporteFuncionario
        $ban = 1;
		while($fila = pg_fetch_assoc($listaReporte)){
			$ban=0;
			$listaReporteFuncionario = $cv->filtroObtenerReporteSaldoFuncionario($conexion, $fila['identificador'], $estadoSaldo, $apellido, $nombre, $area, 'unico');
			if(pg_num_rows($listaReporteFuncionario) > 0){
				    $consult = pg_fetch_assoc($listaReporteFuncionario);
				    if($fila['identificador'] == $consult['identificador']){
						$fila['minutos_disponibles'] =$fila['minutos_disponibles'] + $consult['minutos_disponibles'];
				    }
			}
			
			$identifi=$fila['identificador'].'.'.$estadoSaldo;
			echo '<tr 
						id="'.$identifi.'"
						class="item"
						data-rutaAplicacion="vacacionesPermisos"
						data-opcion="abrirSaldoVacaciones" 
						ondragstart="drag(event)" 
						draggable="true" 
						data-destino="detalleItem">
					<td>'.++$contador.'</td>
					<td style="white-space:nowrap;"><b>'.$fila['identificador'].'</b></td>
					<td>'.$fila['apellido'].' '.$fila['nombre'].'</td>
					<td align="right">'.number_format(($fila['minutos_disponibles']/480),2) .' día(s)</td>
				</tr>';
			}
			
			if($ban){
				
				$listaReporteFuncionario = $cv->filtroObtenerReporteSaldoFuncionario($conexion, $identificador, $estadoSaldo, $apellido, $nombre, $area, 'unico');
				while($fila = pg_fetch_assoc($listaReporteFuncionario)){
					
					$identifi=$fila['identificador'].'.'.$estadoSaldo;
					echo '<tr
						id="'.$identifi.'"
						class="item"
						data-rutaAplicacion="vacacionesPermisos"
						data-opcion="abrirSaldoVacaciones"
						ondragstart="drag(event)"
						draggable="true"
						data-destino="detalleItem">
					<td>'.++$contador.'</td>
					<td style="white-space:nowrap;"><b>'.$fila['identificador'].'</b></td>
					<td>'.$fila['apellido'].' '.$fila['nombre'].'</td>
					<td align="right">'.number_format(($fila['minutos_disponibles']/480),2) .' día(s)</td>
				</tr>';
				}
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
