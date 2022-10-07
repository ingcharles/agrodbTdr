<?php
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorEmpleados.php';
	require_once '../../clases/ControladorAccidentesIncidentes.php';
	require_once '../../clases/ControladorReportes.php';
	
try {
	$conexion = new Conexion();
	$ce = new ControladorEmpleados();
	$cai = new ControladorAccidentesIndicentes();
		
	$mensaje = array();
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Ha ocurrido un error!';
	
	$codSolicitud=$_POST['id'];
		
	$identificador=pg_fetch_result (
			$consulta=$cai->listarDatosAccidente($conexion,'', '','','', '',$codSolicitud, '','',''), 0, 'identificador_accidentado' ); 
	$datosEmpleado = pg_fetch_assoc(
			$ce->obtenerFichaEmpleado($conexion, $identificador));
						
	///JASPER///
	
	//Ruta del reporte compilado por Jasper y generado por IReports
	
	$jru = new ControladorReportes();
	
	$filename = $codSolicitud.'_'.$identificador.'_fichaSso.pdf';
	$ReporteJasper='aplicaciones/investigacionAccidentesIncidentes/reportes/fichaAccidentesIncidentes.jrxml';
	$salidaReporte = 'aplicaciones/investigacionAccidentesIncidentes/fichaAccidenteIncidente/'.$filename;
	$rutaImagen = $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/aplicaciones/investigacionAccidentesIncidentes/img/FM/agrocalidad.png';
	$rutaSubreporte = $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/aplicaciones/investigacionAccidentesIncidentes/reportes/';
	$rutaTitle = $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/aplicaciones/investigacionAccidentesIncidentes/img/FM/title.gif';
	$rutaIdentificacion = $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/aplicaciones/investigacionAccidentesIncidentes/img/FM/identificacion.gif';
	$rutaInfoAccidente = $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/aplicaciones/investigacionAccidentesIncidentes/img/FM/rutaInfoAccidente.gif';
	$rutaDescripAccidente = $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/aplicaciones/investigacionAccidentesIncidentes/img/FM/rutaDescripAccidente.gif';
	$rutaDatosMedicos = $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/aplicaciones/investigacionAccidentesIncidentes/img/FM/rutaDatosMedicos.gif';
	$rutaRiesgoTrabajo = $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/aplicaciones/investigacionAccidentesIncidentes/img/FM/rutaRiesgoTrabajo.gif';
	$rutaInfoTestigos = $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/aplicaciones/investigacionAccidentesIncidentes/img/FM/rutaInfoTestigos.gif';
	$rutaPiePagina = $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/aplicaciones/investigacionAccidentesIncidentes/img/FM/piePagina.gif';
	
	
	if($datosEmpleado['fotografia'] == ''){
	    $rutaFotografia = $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/aplicaciones/uath/fotos/foto.png';
	}else{
	    $rutaFotografia = $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/'.$datosEmpleado['fotografia'];
	}
	
	$parameters['parametrosReporte'] = array(
		'rutaImagen'=> $rutaImagen,
		'rutaFotografia' => $rutaFotografia,
		'codSolicitud' => intval($codSolicitud),
		'subReporte' => $rutaSubreporte,
		'rutaTitle'=> $rutaTitle,
		'rutaIdentificacion' => $rutaIdentificacion,
		'rutaInfoAccidente'=> $rutaInfoAccidente,
		'rutaDescripAccidente'=> $rutaDescripAccidente,
		'rutaDatosMedicos'=> $rutaDatosMedicos,
		'rutaRiesgoTrabajo'=> $rutaRiesgoTrabajo,
		'rutaInfoTestigos'=> $rutaInfoTestigos,
		'rutaPiePagina'=> $rutaPiePagina
	);
	
	$jru->generarReporteJasper($ReporteJasper,$parameters,$conexion,$salidaReporte,'ninguno');
	
	$cai->guardarArchivoFichaSso($conexion,$codSolicitud,$salidaReporte);
	$cai->actualizarRegistroSso($conexion,$codSolicitud,'Cerrado','',4);
	
} catch (Exception $e) {
		//echo $e;
	}
?>

<div id="reporte">
	<?php 
	if($salidaReporte != '')
		echo '<embed id="visor" src='.$salidaReporte.' width="540" height="600">';
	echo '<br><br>'?>;
	?>		
</div>