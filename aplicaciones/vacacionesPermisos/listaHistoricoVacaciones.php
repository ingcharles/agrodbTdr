<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorVacaciones.php';

	
	$conexion = new Conexion();
	$cv = new ControladorVacaciones();
	
	$identificador = $_POST['identificador'];
	$apellido = $_POST['apellidoUsuario'];
	$nombre = $_POST['nombreUsuario'];
	$fechaInicio = $_POST['fechaInicio'];
	$fechaFin = $_POST['fechaFin'];
	$tipoPermiso = $_POST['tipoSolicitud'];
	$subtipoPermiso = $_POST['subtipoPermiso'];
	$estadoVacacion = $_POST['estadoVacacion'];
	$area = $_POST['area'];
		
	$listaReporte = $cv->filtroObtenerReporteHistoricoUsuario($conexion, $identificador, $apellido, $nombre, $fechaInicio, $fechaFin, $tipoPermiso, $subtipoPermiso, $estadoVacacion, $area);
	
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
	
			<input type="hidden" name="identificador" value="<?php echo $identificador;?>"/>
			<input type="hidden" name="estadoVacacion" value="<?php echo $estadoVacacion;?>"/>
			<input type="hidden" name="apellido" value="<?php echo $apellido;?>"/>
			<input type="hidden" name="nombre" value="<?php echo $nombre;?>"/>
			<input type="hidden" name="fechaInicio" value="<?php echo $fechaInicio;?>"/>
			<input type="hidden" name="fechaFin" value="<?php echo $fechaFin;?>"/>
			<input type="hidden" name="tipoPermiso" value="<?php echo $tipoPermiso;?>"/>
			<input type="hidden" name="subtipoPermiso" value="<?php echo $subtipoPermiso;?>"/>
			<input type="hidden" name="area" value="<?php echo $area;?>"/>
			
		<button id="btnReporteExcel" type="submit" class="guardar">Generar reporte excel</button>
	
	</form>
	</td>
	<td>
		<form id="generarReportePDF" data-rutaAplicacion="vacacionesPermisos"	data-opcion="generarReporteHistoricoPDF" data-destino="detalleItem">
	
			<input type="hidden" name="identificador" value="<?php echo $identificador;?>"/>
			<input type="hidden" name="estadoVacacion" value="<?php echo $estadoVacacion;?>"/>
			<input type="hidden" name="apellido" value="<?php echo $apellido;?>"/>
			<input type="hidden" name="nombre" value="<?php echo $nombre;?>"/>
			<input type="hidden" name="fechaInicio" value="<?php echo $fechaInicio;?>"/>
			<input type="hidden" name="fechaFin" value="<?php echo $fechaFin;?>"/>
			<input type="hidden" name="tipoPermiso" value="<?php echo $tipoPermiso;?>"/>
			<input type="hidden" name="subtipoPermiso" value="<?php echo $subtipoPermiso;?>"/>
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
			<th>Código</th>
			<th style="width: 24%;">Nombre</th>
			<th>Subtipo permiso</th>
			<th>Tiempo utilizado</th>
			<th>Fechas</th>
			<th>Estado</th>
			<th>Observación</th>
			
		
		</tr>
	</thead>
	
<?php 
		$contador = 0;
		while($fila = pg_fetch_assoc($listaReporte)){
			
			$tiempoSolicitado=$cv->devolverFormatoDiasDisponibles($fila['minutos_utilizados']);
			
			$tiempoActual='';
			if(($fila['codigo'] == 'PE-PIV' || $fila['codigo'] == 'VA-VA' || $fila['codigo'] == 'PE-PIVF' || $fila['codigo'] == 'PE-DA') and $fila['minutos_actuales'] != ''){
				
				$tiempoActual="Tiempo a la fecha ".$cv->devolverFormatoDiasDisponibles($fila['minutos_actuales']);
			}
				
			echo '<tr 
						id="'.$fila['id_permiso_empleado'].'"
						class="item"
						data-rutaAplicacion="vacacionesPermisos"
						data-opcion="abrirPermisoVacacionesHistorico" 
						ondragstart="drag(event)" 
						draggable="true" 
						data-destino="detalleItem">
					<td>'.++$contador.'</td>
					<td>'.$fila['id_permiso_empleado'].'</td>
					<td style="width: 24%;"><b>'.$fila['nombre'].'</b></td>
					<td>'.$fila['descripcion_subtipo'].'</td>
					<td>'.$tiempoSolicitado.'</td>
					<td> Fecha desde: '. date('Y-m-d H:i',strtotime($fila['fecha_inicio'])).' Fecha hasta: '. date('Y-m-d H:i',strtotime($fila['fecha_fin'])).'</td>					
					<td>'.$fila['estado'].'</td>
					<td>'.$tiempoActual.'</td>
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
