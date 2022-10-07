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
				
	$fechaInicio = $fechaInicio.' 00:00:00';
	$fechaFin = $fechaFin.' 24:00:00';
			
	//Rutas Reporte Factura
	$ReporteJasper='/aplicaciones/vacacionesPermisos/reportes/historicoVacaciones.jrxml';
	$salidaReporte='/aplicaciones/vacacionesPermisos/reportes/ReporteHistorico.pdf';
	$rutaArchivo='aplicaciones/vacacionesPermisos/reportes/ReporteHistorico.pdf';
	
	$parameters['parametrosReporte'] = array(
		'identificador'=> ($identificador == '' ? 'null' : $identificador),
		'apellido'=> ($apellido == '' ? 'null' : $apellido),
		'nombre'=> ($nombre == '' ? 'null' : $nombre),
		'area'=> ($area == '' ? 'null' : $area),
		'tipoPermiso' => ($tipoPermiso == '' ? 0 : $tipoPermiso),
		'subTipoPermiso' => ($subtipoPermiso == '' ? 0 : $subtipoPermiso),
		'fechaInicio'=> $fechaInicio,
		'fechaFin'=> $fechaFin,
		'estado'=> ($estadoVacacion == '' ? 'null' : $estadoVacacion),
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