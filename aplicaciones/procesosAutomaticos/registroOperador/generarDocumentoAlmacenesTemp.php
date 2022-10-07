<?php

	require_once '../../../clases/Conexion.php';
	require_once '../../../clases/ControladorReportes.php';
	require_once '../../../clases/ControladorRegistroOperador.php';
	require_once'http://localhost:8081/JavaBridge/java/Java.inc';

	$conexion = new Conexion();
	$cr = new ControladorRegistroOperador();
	$jru = new ControladorReportes();
	
	set_time_limit(86000);
	
	define('PRO_MSG', '<br/> ');
	define('IN_MSG','<br/> >>> ');
	$fecha = date("Y-m-d h:m:s");
	$numero = '1';

	echo IN_MSG .'<b>INICIO PROCESO DE GENERACIÓN DE REPORTE DE ALMACENES '.$fecha.'</b>';
		
	$qCantidadOperador = $cr->obtenerOperadoresCGRIAtemp($conexion,'Almacenista');

	while($operador = pg_fetch_assoc($qCantidadOperador)){

		echo IN_MSG. $numero++ . '.- Identificador operador: '.$operador['identificador_operador']. ' Sitio: '.$operador['id_sitio'];

		$idOperador = $operador['identificador_operador'];
		$idSitio = $operador['id_sitio'];
		$solicitud = $operador['id_operacion'];
		$idOperadorTipoOperacion = $operador['id_operador_tipo_operacion'];

		$ReporteJasper= '/aplicaciones/registroOperador/reportes/registroAlmacen/riaAlmacenes.jrxml';
		$salidaReporte= '/aplicaciones/registroOperador/certificados/registroAlmacen/'.$idOperador.'_'.$idSitio.'.pdf';
		$rutaArchivo= 'aplicaciones/registroOperador/certificados/registroAlmacen/'.$idOperador.'_'.$idSitio.'.pdf';
		$rutaArchivoCodigoQr = 'http://181.112.155.173/'.$constg::RUTA_APLICACION.$salidaReporte;

		$parameters = new java('java.util.HashMap');
		$parameters ->put('idSitio',(int)$idSitio);
		$parameters ->put('rutaCertificado',$rutaArchivoCodigoQr);

		$jru->generarReporteJasper($ReporteJasper,$parameters,$conexion->getConnection(),$salidaReporte,'ria');
		$cr->guardarDocumentoOperador($conexion, $solicitud, $idOperadorTipoOperacion, $rutaArchivo, 'riaAlmacenistas', $idSitio, $idOperador, 'Certificación de registro de almacén de expendio.');
	}

	$fecha = date("Y-m-d h:m:s");

	echo IN_MSG .'<b>FIN PROCESO DE GENERACIÓN DE OPADORES ALMACENISTAS '.$fecha.'</b>';
?>