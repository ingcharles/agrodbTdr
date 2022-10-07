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
	
	$codSolicitud=$_POST['solicitud'];
	
	
	$identificador=pg_fetch_result ($consulta=$cai->listarDatosAccidente($conexion,'', '','',
			'', '',$codSolicitud, '','',''), 0, 'identificador_accidentado' ); 
	$datosEmpleado = pg_fetch_assoc($ce->obtenerFichaEmpleado($conexion, $identificador));
	$datosCierreCaso=pg_fetch_array($cai->buscarCierreCaso($conexion,$codSolicitud));
					
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
	/*	
	echo $rutaFotografia.'<br>'; 
	echo $rutaImagen.'<br>' ;
	echo $rutaSubreporte.'<br>' ;
	echo $rutaTitle.'<br>' ;
	echo $rutaIdentificacion.'<br>' ;
	echo $rutaInfoAccidente.'<br>' ;
	echo $rutaDescripAccidente.'<br>' ;
	echo $rutaDatosMedicos.'<br>' ;
	echo $rutaRiesgoTrabajo.'<br>' ;
	echo $rutaInfoTestigos.'<br>' ;
	echo $rutaPiePagina.'<br>' ;
	
	 */
	
	$parameters['parametrosReporte'] = array(
		'codSolicitud'=> intval($codSolicitud),
		'rutaFotografia'=> $rutaFotografia,
		'rutaImagen' => $rutaImagen,
		'subReporte'=> $rutaSubreporte,
		'rutaTitle' => $rutaTitle,
		'rutaIdentificacion'=> $rutaIdentificacion,
		'rutaInfoAccidente' => $rutaInfoAccidente,
		'rutaDescripAccidente'=> $rutaDescripAccidente,
		'rutaDatosMedicos'=> $rutaDatosMedicos,
		'rutaRiesgoTrabajo'=> $rutaRiesgoTrabajo,
		'rutaInfoTestigos' => $rutaInfoTestigos,
		'rutaPiePagina' => $rutaPiePagina
	);
	
	$jru->generarReporteJasper($ReporteJasper,$parameters,$conexion,$salidaReporte,'ninguno');
	
	$cai->actualizarArchivoFichaSso($conexion,$codSolicitud,$salidaReporte);
	} catch (Exception $e) {
		//echo $e;
	}
?>

<div id="reporte">
	<?php 
		echo '<embed id="visor" src='.$salidaReporte.' width="540" height="600">';
		if($datosCierreCaso['archivo_unidad_riesgos_iess']!=''){
		echo '<br><br>';
	?>
		<fieldset>
			<legend>Documentos Adjunto de Respaldo</legend>
			<div data-linea="1">
				<label>Documentación Emitida por la Unidad de Riesgos del Trabajo
					del IESS:</label><br>
				<?php 
				    echo $datosCierreCaso['archivo_unidad_riesgos_iess']=='' ? '<span class="alerta">No ha subido ningún archivo.</span>':'<a href="'.$datosCierreCaso['archivo_unidad_riesgos_iess'].'" target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>';?>
			</div>
									
			<br>
			
			<div data-linea="2">
				<label>Certificado Médico:</label><br>
								<?php 
				    echo $datosCierreCaso['archivo_certificado_medico']=='0' ? '<span class="alerta">No ha subido ningún archivo.</span>':'<a href="'.$datosCierreCaso['archivo_certificado_medico'].'" target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>';?>
				
			</div>
			<br>
			<div data-linea="3">
				<label>Informe Ampliado Firmado y con Sello de la Persona que Reporta:</label><br>
					<?php 
				    echo $datosCierreCaso['archivo_informe_reporte']=='' ? '<span class="alerta">No ha subido ningún archivo.</span>':'<a href="'.$datosCierreCaso['archivo_informe_reporte'].'" target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>';?>
					
			</div>
	<?php }?>		
		</fieldset>
</div>