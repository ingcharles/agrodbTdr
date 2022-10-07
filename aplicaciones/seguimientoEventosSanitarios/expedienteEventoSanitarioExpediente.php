<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorEventoSanitario.php';
	require_once '../../clases/ControladorUsuarios.php';
	require_once '../../clases/ControladorReportes.php';
	
	$conexion = new Conexion();
	$cpco = new ControladorEventoSanitario();
	$cu = new ControladorUsuarios();
			
	$numSolicitud = htmlspecialchars ($_POST['id'],ENT_NOQUOTES,'UTF-8');
	
	///JASPER///
	
	//Ruta del reporte compilado por Jasper y generado por IReports
	
	$jru = new ControladorReportes();
	
	$filename = $numSolicitud.'.pdf';
	$ReporteJasper='aplicaciones/seguimientoEventosSanitarios/reportes/Reporte/expedienteES.jrxml';
	$salidaReporte = 'aplicaciones/seguimientoEventosSanitarios/reportes/expedientes/'.$filename;
	$rutaSubreporte = $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/aplicaciones/seguimientoEventosSanitarios/reportes/Reporte/';
	$rutaImagenAgro = $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/aplicaciones/seguimientoEventosSanitarios/img/agrocalidad.png';
	$rutaImagenMagap = $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/aplicaciones/seguimientoEventosSanitarios/img/magap_logo.jpg';
	
	$parameters['parametrosReporte'] = array(
		'id_evento_sanitario'=> (integer)$numSolicitud,
		'magap'=> $rutaImagenMagap,
		'agrocalidad'=> $rutaImagenAgro,
		'rutaSubreporte'=> $rutaSubreporte
	);

	
	$jru->generarReporteJasper($ReporteJasper,$parameters,$conexion,$salidaReporte,'ninguno');
		
?>

<div id="reporte">
	<?php 
		echo '<embed id="visor" src='.$salidaReporte.' width="540" height="800">';
	?>
</div>