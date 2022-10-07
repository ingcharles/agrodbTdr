<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorReportes.php';
	require_once '../../clases/ControladorAreas.php';
	
	$conexion = new Conexion();
	$jru = new ControladorReportes();
	
	$identificador = $_POST['identificador'];
	$apellido = $_POST['apellido'];
	$nombre = $_POST['nombre'];
	$fechaInicio = $_POST['fechaInicio'];
	$fechaFin = $_POST['fechaFin'];
	$tipoPermiso = $_POST['tipoPermiso'];
	$subtipoPermiso = $_POST['subtipoPermiso'];
	$estadoVacacion = $_POST['estadoVacacion'];
	$area = $_POST['area'];
			
		if($identificador != ''){
			$busqueda = "and pe.identificador IN ('$identificador')";
		}
		
		if($apellido != ''){
			$busqueda .= " and pe.identificador IN (SELECT identificador FROM g_uath.ficha_empleado WHERE upper(apellido) like upper('%$apellido%'))";
		}
		
		if($nombre != ''){
			$busqueda .= " and pe.identificador IN (SELECT identificador FROM g_uath.ficha_empleado WHERE upper(nombre) like upper('%$nombre%'))";
		}
		
		if($fechaInicio!=''){
			$fechaInicio = $fechaInicio.' 00:00:00';
			
			$busqueda .= " and pe.fecha_inicio > '$fechaInicio' ";
		}
		
		if($fechaFin!=''){
			$fechaFin = $fechaFin.' 24:00:00';
				
			$busqueda .= " and pe.fecha_inicio < '$fechaFin' ";
		}
		
		if($tipoPermiso != ''){
			$busqueda .= " and tp.id_permiso = $tipoPermiso";
		}
		
		if($subtipoPermiso != ''){
			$busqueda .= " and pe.subtipo_permiso_reintegro = $subtipoPermiso";
		}
		
		if($estadoVacacion!=''){
			$busqueda .= " and pe.estado = '$estadoVacacion'";
		}
		
		if($area != ''){
			
			if($area == 'DE'){
				
				$areaSubproceso = "'".$area."',";
				
			}else{
			
				$ca = new ControladorAreas();
				$areaProceso = $ca->buscarDivisionEstructura($conexion, $area);
			
				while($fila = pg_fetch_assoc($areaProceso)){
					$areaSubproceso .= "'".$fila['id_area']."',";
				}
			}
			
			$areaSubproceso = "(".rtrim($areaSubproceso,',').")";
				
			$busqueda .= ' and pe.identificador IN (SELECT identificador FROM g_estructura.funcionarios WHERE id_area IN '.$areaSubproceso.')';
			
		}
		
		if($busqueda == ''){
			$busqueda = 'vacio';
		}
			
	//Rutas Reporte Factura
	$ReporteJasper='/aplicaciones/vacacionesPermisos/reportes/historicoVacacionesReintegro.jrxml';
	$salidaReporte='/aplicaciones/vacacionesPermisos/reportes/ReporteHistoricoReintegro.pdf';
	$rutaArchivo='aplicaciones/vacacionesPermisos/reportes/ReporteHistoricoReintegro.pdf';
	
	$parameters['parametrosReporte'] = array(
		'consulta'=>$busqueda
	);
	
	$jru->generarReporteJasper($ReporteJasper,$parameters,$conexion,$salidaReporte,'defecto');
		
	
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>

<body>

	<embed id="visor" src="<?php echo $rutaArchivo; ?>" width="540" height="490">

</body>

<script type="text/javascript">

 
</script>
</html>