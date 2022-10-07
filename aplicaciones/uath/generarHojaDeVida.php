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
		
	$filename = $identificador.'-HojaDeVida.pdf';
	$ReporteJasper='aplicaciones/uath/reportes/CV.jrxml';
	$salidaReporte = 'aplicaciones/uath/archivosHojaVida/'.$filename;
	$rutaSubreporte = $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/aplicaciones/uath/reportes/';
	$rutaImagen = $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/aplicaciones/uath/img/CV/';
	
	if($datosEmpleado['fotografia'] == ''){
	    $rutaFotografia = $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/aplicaciones/uath/fotos/foto.png';
	}else{
	    $rutaFotografia = $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/'.$datosEmpleado['fotografia'];
	}
	
	$parameters['parametrosReporte'] = array(
		'identificador' => $identificador,
		'rutaSubreporte' => $rutaSubreporte,
		'rutaImagen' => $rutaImagen,
		'rutaFotografia' => $rutaFotografia
	);
	
	if( count($parameters) > 0 )
	{
		$command .= " -P";
		foreach ($parameters['parametrosReporte'] as $key => $value)
		{
			if( is_string($value) )
				echo 'SI';
			else
				echo 'NO';
		}
	}
	
	//CAMBIAR RUTA IMAGEN
	$jru->generarReporteJasper($ReporteJasper,$parameters,$conexion,$salidaReporte,'ninguno');
		
?>

<div id="reporte">
	<?php 
		echo '<embed id="visor" src='.$salidaReporte.' width="540" height="800">';
	?>
</div>