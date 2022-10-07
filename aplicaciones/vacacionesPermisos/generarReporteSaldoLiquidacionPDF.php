<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorReportes.php';
	require_once '../../clases/ControladorAreas.php';
	
	$conexion = new Conexion();
	$jru = new ControladorReportes();
	
	$identificador = $_POST['identificador'];
	$estadoSaldo = $_POST['estadoSaldo'];
	$apellido = $_POST['apellidoUsuario'];
	$nombre = $_POST['nombreUsuario']; 
	$area = $_POST['area'];
			
	$busqueda = '';
	
	if ($identificador != ''){
		$busqueda = "and lv.identificador IN ('$identificador')";
	}
	
	if ($apellido != ''){
		$busqueda .= " and lv.identificador IN (SELECT identificador FROM g_uath.ficha_empleado WHERE upper(apellido) like upper('%$apellido%'))";
	}
	
	if ($nombre != ''){
		$busqueda .= " and lv.identificador IN (SELECT identificador FROM g_uath.ficha_empleado WHERE upper(nombre) like upper('%$nombre%'))";
	}
	
	if ($area != ''){
		
		if ($area == 'DE'){
			
			$areaSubproceso = "'" . $area . "',";
		}else{
			$areaProceso = $conexion->ejecutarConsulta("select
						*
						from
								g_estructura.area
						where
								id_area_padre = '$area'
						UNION
						select
						*
						from
								g_estructura.area
						where
								id_area = '$area'
						order by
								id_area asc;");
			
			while ($fila = pg_fetch_assoc($areaProceso)){
				$areaSubproceso .= "'" . $fila['id_area'] . "',";
			}
		}
		
		$areaSubproceso = "(" . rtrim($areaSubproceso, ',') . ")";
		
		$busqueda .= ' and lv.identificador IN (SELECT identificador FROM g_estructura.funcionarios WHERE id_area IN ' . $areaSubproceso . ')';
	}
	
	$busqueda .=" and lv.estado = '".$estadoSaldo."'";
	//Rutas Reporte Factura
	$ReporteJasper='/aplicaciones/vacacionesPermisos/reportes/saldoVacacionesLiquidacion.jrxml';
	$salidaReporte='/aplicaciones/vacacionesPermisos/reportes/ReporteLiquidacion.pdf';
	$rutaArchivo='aplicaciones/vacacionesPermisos/reportes/ReporteLiquidacion.pdf';
	
	$parameters['parametrosReporte'] = array(
		'consulta'=> $busqueda,
		'estado'=> $estadoSaldo
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