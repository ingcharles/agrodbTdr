<?php

	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorVacaciones.php';

	
	$conexion = new Conexion();
	$cv = new ControladorVacaciones();
	
	$anio = $_POST['bAnio'];
	$identificador = $_POST['bIdentificador'];
	$nombre = $_POST['bNombre'];
		
	$listaReporte = $cv->filtroObtenerReporteHistoricoCronogramavacacion($conexion, $anio, $identificador, $nombre);
	
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
		<form id="generarReporteExcel" action="aplicaciones/vacacionesPermisos/generarReporteHistoricoVacacionesExcel.php" target="_blank" method="post">
	
			<input type="hidden" name="anio" value="<?php echo $anio;?>"/>
			<input type="hidden" name="identificador" value="<?php echo $identificador;?>"/>
			<input type="hidden" name="nombre" value="<?php echo $nombre;?>"/>
			
		<button id="btnReporteExcel" type="submit" class="guardar">Generar reporte excel</button>
	
	</form>
	</td>
</tr>

</table>

<table>
	<thead>
		<tr>
			<th>#</th>
			<th>Cédula</th>
			<th>Nombre</th>
			<th>Puesto</th>
			<th>Anio cronograma</th>
			<th>Estado</th>	
		</tr>
	</thead>
	
<?php 
		$contador = 0;
		$datosCronograma = "";
		while($fila = pg_fetch_assoc($listaReporte)){

			if(isset($fila['id_cronograma_vacacion'])){
				$datosCronograma = ' class="item"
				data-rutaAplicacion="vacacionesPermisos"
				data-opcion="abrirHistoricoCronogramaVacaciones" 
				ondragstart="drag(event)" 
				draggable="true" 
				data-destino="detalleItem ';
			}

			echo '<tr 
						id="' . $fila['id_cronograma_vacacion'] . '"
						' . $datosCronograma . '">
					<td>' . ++$contador . '</td>
					<td>'. $fila['identificador'] . '</td>
					<td><b>' . $fila['nombres_completos'] . '</b></td>
					<td>' . $fila['puesto_institucional'] . '</td>
					<td>' . $fila['anio_cronograma_vacacion'] . '</td>
					<td>' . $fila['estado'] . '</td>
				</tr>';
			}
?>			
</table>

</body>

<script type="text/javascript"> 

	$(document).ready(function(){
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");
	});

	$("#generarReportePDF").submit(function(event){
		abrir($(this),event,false);	
	});
	
</script>
</html>
