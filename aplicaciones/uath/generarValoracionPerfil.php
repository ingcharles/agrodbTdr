<?php

	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorEmpleados.php';
	require_once '../../clases/ControladorReportes.php';
		
	$conexion = new Conexion();
	$ce = new ControladorEmpleados();
	
	$mensaje = array();
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Ha ocurrido un error!';
	
	if(strlen($_POST['id']) == 10){
		$identificador=$_POST['id'];
	}else{
		$identificador=$_SESSION['usuario'];
	}
	
	$datosEmpleado = pg_fetch_assoc($ce->obtenerFichaEmpleado($conexion, $identificador));
					
	///JASPER///
	
	//Ruta del reporte compilado por Jasper y generado por IReports
	
	$jru = new ControladorReportes();
	
	$filename = $identificador.'-valoracionPerfil.pdf';
	$ReporteJasper='aplicaciones/uath/reportes/R_valoracionper.jrxml';
	$salidaReporte = 'aplicaciones/uath/archivosValoracionPerfil/'.$filename;
	$rutaSubreportes = $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/aplicaciones/uath/reportes/';
	$rutaLogoMag = $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/aplicaciones/uath/img/magap_logo.jpg';
	$rutaLogoAgro = $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/aplicaciones/uath/img/agrocalidad.png';
	
	if($datosEmpleado['fotografia'] == ''){
	    $rutaFotografia = $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/aplicaciones/uath/fotos/foto.png';
	}else{
	    $rutaFotografia = $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/'.$datosEmpleado['fotografia'];
	}
	
	$parameters['parametrosReporte'] = array(
		'identificador'=> $identificador,
		'rutaSubreportes'=> $rutaSubreportes,
		'rutaLogoMag'=> $rutaLogoMag,
		'rutaLogoAgro'=> $rutaLogoAgro,
		'rutaFotografia'=> $rutaFotografia
	);
	
	
	$jru->generarReporteJasper($ReporteJasper,$parameters,$conexion,$salidaReporte,'ninguno');
		
?>

<div id="reporte">
	<?php 
		echo '<embed id="visor" src='.$salidaReporte.' width="540" height="800">';
	?>
</div>
