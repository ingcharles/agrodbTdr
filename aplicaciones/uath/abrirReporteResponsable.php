<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorCatastro.php';
	require_once '../../clases/ControladorAreas.php';
	require_once '../../clases/ControladorVacaciones.php';

try {
	$conexion = new Conexion();
	$cc = new ControladorCatastro();
	$ca = new ControladorAreas();
	$cv = new ControladorVacaciones();
	
	$tmp = explode('.',$_POST['id']);
	$identificador=$tmp[0];
	$area=$tmp[1];
	$responsable=$tmp[2];

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<header>
	<h1>Reporte Responsable</h1>
</header>
<body><div id="estado"></div>
<form id="imprimirReporteResponsable" data-rutaAplicacion="uath" action="aplicaciones/uath/abrirReporteResponsableExcel.php"  target="_blank" method="post" data-accionEnExito="ACTUALIZAR" >
	<input type="hidden" id="responsable" name="responsable" value="<?php echo $responsable; ?>"/>	
	<input type="hidden" id="identificador" name="identificador" value="<?php echo $identificador; ?>"/>	
	<input type="hidden" id="area" name="area" value="<?php echo $area; ?>" /> 
<p>
	</p>
	<button id="buscar">Imprimir</button>
	<fieldset>
		<legend>Funcionario</legend>		
		<table style="width: 100%">
			<thead>
				<tr>	
					<th>Nombre funcionario</th>
					<th>Área</th>
					<th>Fecha Inicio</th>
					<th>Fecha Fin</th>
				</tr>
			</thead>
			<?php 
			try {
			$contador = 0;
			$listaReporte = $cc->filtroObtenerEncargo($conexion, $identificador, '', '', 'Aprobado', '','unico','');
			while($fila = pg_fetch_assoc($listaReporte)) {	
				$fecha_fin='';
				if($responsable=='Subrogante'){
					$estado="activo','inactivo";
				$fecha_inicio = pg_fetch_result($Reporte = $cc->obtenerSubrogacionesFuncionarios($conexion, $area,$identificador,$estado), 0, 'fecha_inicio');
				$fecha_fin = pg_fetch_result($Reporte = $cc->obtenerSubrogacionesFuncionarios($conexion, $area,$identificador,$estado), 0, 'fecha_inicio');
				}else {
				$fecha_inicio = pg_fetch_result($cc->obtenerFechasResponsables($conexion, $area, $identificador), 0, 'fecha_inicio');
									
				}
				if($responsable=='Titular')
					$fecha_inicio = pg_fetch_result($cc->obtenerFechasResponsablesPuestos($conexion, $area, $identificador), 0, 'fecha_inicio');
				
				if($fecha_inicio == '')
					$fecha_inicio = pg_fetch_result($cc->obtenerInformacionFuncionarioContratoActivo ($conexion, $identificador), 0, 'fecha_inicio');
				
				if($fecha_fin=='')$fecha_fin='Actualidad';
				
				$fecha_inicio=date('Y-m-d', strtotime($fecha_inicio));
			
			if($fila['area']==$area){			
					echo '<tr>					
							<td>'.$fila['nombre'].'</td>
							<td>'.$fila['area'].'<br>'.$fila['nombrearea'].'</td>
							<td>'.$fecha_inicio.'</td>
							<td> '.$fecha_fin.' </td>
						</tr>';
					}
	 		}
	 	//-----------------------------------------------------------------
	 		} catch (Exception $e) {
	 			$err = preg_replace( "/\r|\n/", " ", $conexion->mensajeError);
	 			$conexion->ejecutarLogsTryCatch($e.'---'.$err);
	 		}
	 	?>
		</table>
	</fieldset>
	<fieldset>
		<legend>Funcionarios</legend>		
		<table style="width: 100%">
			<thead>
				<tr>
					<th>Nombre funcionario</th>
					<th>Puesto</th>
					<th>Fecha Inicio</th>
					<th>Fecha Fin</th>
					<th>Archivo Adjunto</th>
					<th>Observación</th>
				</tr>
			</thead>
			<?php 
			try {
			$contador = 0;
			$listaReporte = $cc->filtroObtenerEncargo($conexion, $identificador, '', '', 'Aprobado', $area,'','no');
			while($fila = pg_fetch_assoc($listaReporte)) {

			$consulta=pg_fetch_assoc($cc->obtenerInformacionFuncionarioContratoActivo ($conexion, $fila['identificador_subrogador']));
			$observacion=pg_fetch_result($cv->devolverObservacionPermiso($conexion,$fila['id_permiso_empleado']),0,'observacion');
											
			echo '<tr>					
					<td>'.$fila['nombre'].'</td>
					<td>'.$fila['nombre_puesto_encargado'].'</td>					
					<td>'.$fila['fecha_ini'].'</td>
					<td>'.$fila['fecha_fin'].'</td>';
			
		    echo '<td>'.($fila['ruta_subrogacion']==''? '<span class="alerta"></span>':
					'<a href='.$fila['ruta_subrogacion'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a></td>');
			echo '<td>'.$observacion.'</td>';
			echo '</tr>';
	 	    }
	 	    
	 	    } catch (Exception $e) {
	 	    	$err = preg_replace( "/\r|\n/", " ", $conexion->mensajeError);
	 	    	$conexion->ejecutarLogsTryCatch($e.'---'.$err);
	 	    }
	 	?>
		</table>
	</fieldset>
	</form>
<script>
	
$("#imprimirReporteResponsable").submit(function(e) {
	$(this).submit();  
});

</script>
	</body>
</html>
<?php 
     } catch (Exception $e) {
	  	$err = preg_replace( "/\r|\n/", " ", $conexion->mensajeError);
	   	$conexion->ejecutarLogsTryCatch($e.'---'.$err);
	}
?>
