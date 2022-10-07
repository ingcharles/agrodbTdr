<?php

if($_SERVER['REMOTE_ADDR']==''){

	require_once '../../../clases/Conexion.php';
	require_once '../../../clases/ControladorMonitoreo.php';
	require_once '../../../clases/ControladorVacunacion.php';
	require_once '../../../clases/ControladorCatastroProducto.php';

	define ( 'IN_MSG', '<br/> >>> ' );
	define ( 'OUT_MSG', '<br/> <<< ' );
	define ( 'PRO_MSG', '<br/> ... ' );

	$conexion = new Conexion ();
	$cm = new ControladorMonitoreo();
	$va = new ControladorVacunacion ();
	$ccp = new ControladorCatastroProducto ();
	
	set_time_limit(3000);

	$resultadoMonitoreo = $cm->obtenerCronPorCodigoEstado($conexion, 'CRON_VACU_PRO');

	if($resultadoMonitoreo){

		echo '<h1>ACTUALIZACION AUTOMATICA DE ESTADOS EN CERTIFICADOS DE VACUNACION</h1>';
		echo '<p> <strong>INICIO PROCESO DE ACTUALIZACION</strong>';
		
		$contadorCaducado = 1;
		$contadorCatastroInactivo = 1;
		
		$qVencimientoVacuna = $va->consultarCertificadosVacunacionACaducar ( $conexion,'vigente');
		
		while ($fila = pg_fetch_assoc($qVencimientoVacuna ) ) {
		    echo '<b>' . PRO_MSG . 'Proceso  A Estado Caducado #' . $contadorCaducado++ . ' - '.' N° Certificado ' . $fila['numero_certificado'] . '</b>';
			echo IN_MSG . 'Inicio del envio de la solicitud a actualizar el certificado por vencimiento de vacuna a estado caducado';
			
			$va->actualizarEstadoVacunacion($conexion, $fila['id_vacunacion'],'caducado');
			
			$qIdentificadoresXVacunacion = $va->abrirDetalleVacunacionIdentificadoresXIdVacunacion($conexion, $fila['id_vacunacion']);			
			
			while ($identificadoresXVacunacion = pg_fetch_assoc($qIdentificadoresXVacunacion ) ) {
			
			    echo '<b>' . PRO_MSG . 'Proceso  A Estado Inactivo Catastro #' . $contadorCatastroInactivo++ . ' - '.' N° Identificador ' . $identificadoresXVacunacion['identificador'] . '</b>';
			    echo IN_MSG . 'Inicio de la actualización de estado de activo a inactivo catastro';	
			    
			    $ccp->actualizarEstadoDetalleCatastroXIdentificadorProducto($conexion, $identificadoresXVacunacion['identificador'], 'inactivo');
			    			    	
			}
			
			echo OUT_MSG . 'Fin del envio de solicitud';
		}

		echo '<br/><strong>FIN</strong></p>';
		$conexion->desconectar ();
	}

}else {

	$s=microtime(true);
	$s1=microtime(true);
	$t=$s1-$s;
	$xcadenota = date("d/m/Y").", ".date("H:i:s");
	$xcadenota.= ";REMOTE ".$_SERVER['REMOTE_ADDR'];
	$xcadenota.= ";HTTP ".$_SERVER['HTTP_REFERER'];
	$xcadenota.= "; ".$t." seg\n";
	$arch = fopen("../../aplicaciones/uath/lib_logs/logs/vacunacion_producto_".date("d-m-Y").".txt", "a+");
	fwrite($arch, $xcadenota);
	fclose($arch);
}
?>