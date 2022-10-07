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

	echo IN_MSG .'<b>INICIO PROCESO DE GENERACIÓN DE REPORTE DE EMPRESAS '.$fecha.'</b>';
		
	$qCantidadOperador = $cr->obtenerOperadoresCGRIAtemp($conexion,'Empresas');

	while($operador = pg_fetch_assoc($qCantidadOperador)){

		echo IN_MSG. $numero++ . '.- Identificador operador: '.$operador['identificador_operador'];
		
		$idOperador = $operador['identificador_operador'];
		$solicitud = $operador['id_operacion'];
		$idOperadorTipoOperacion = $operador['id_operador_tipo_operacion'];

		$ReporteJasper= '/aplicaciones/registroOperador/reportes/registroEmpresa/riaEmpresas.jrxml';
		$salidaReporte= '/aplicaciones/registroOperador/certificados/registroEmpresa/'.$idOperador.'.pdf';
		$rutaArchivo= 'aplicaciones/registroOperador/certificados/registroEmpresa/'.$idOperador.'.pdf';
		$rutaArchivoCodigoQr = 'http://181.112.155.173/'.$constg::RUTA_APLICACION.$salidaReporte;
		
		$parameters = new java('java.util.HashMap');
		$parameters ->put('identificadorOperador',$idOperador);
		$parameters ->put('rutaCertificado',$rutaArchivoCodigoQr);
		
		$jru->generarReporteJasper($ReporteJasper,$parameters,$conexion->getConnection(),$salidaReporte,'ria');
		
		$cr->guardarDocumentoOperador($conexion, $solicitud, $idOperadorTipoOperacion, $rutaArchivo, 'riaEmpresas', '1', $idOperador, 'Certificación de registro de empresa.');
	}

	$fecha = date("Y-m-d h:m:s");

	echo IN_MSG .'<b>FIN PROCESO DE GENERACIÓN DE OPADORES ALMACENISTAS '.$fecha.'</b>';
?>