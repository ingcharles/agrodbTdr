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
		
	//Rutas Reporte Factura
	$ReporteJasper='/aplicaciones/vacacionesPermisos/reportes/saldoVacaciones.jrxml';
	$salidaReporte='/aplicaciones/vacacionesPermisos/reportes/ReporteSaldo.pdf';
	$rutaArchivo='aplicaciones/vacacionesPermisos/reportes/ReporteSaldo.pdf';
	
	$parameters['parametrosReporte'] = array(
		'identificador'=> ($identificador == '' ? 'null' : $identificador),
		'apellido'=> ($apellido == '' ? 'null' : $apellido),
		'nombre'=> ($nombre == '' ? 'null' : $nombre),
		'area'=> ($area == '' ? 'null' : $area),
		'estadoSaldo'=> $estadoSaldo
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