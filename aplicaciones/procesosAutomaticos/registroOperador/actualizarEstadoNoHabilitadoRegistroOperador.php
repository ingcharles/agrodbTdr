<?php
//echo "df";
//if($_SERVER['REMOTE_ADDR'] == ''){
if(1){
	require_once '../../../clases/Conexion.php';
	require_once '../../../clases/ControladorMonitoreo.php';
	require_once '../../../clases/ControladorRegistroOperador.php';
	require_once '../../../clases/ControladorCatalogos.php';

	$conexion = new Conexion();
	$cr = new ControladorRegistroOperador();
	$cm = new ControladorMonitoreo();
	$cc = new ControladorCatalogos();
	
	define('PRO_MSG', '<br/> ');
	define('IN_MSG','<br/> >>> ');
	$fecha = date("Y-m-d h:m:s");

	//$resultadoMonitoreo = $cm->obtenerCronPorCodigoEstado($conexion, 'CRON_NOHABILI_REG_OPER');
	//if($resultadoMonitoreo){
	if(1){
		
		echo IN_MSG .'<b>INICIO PROCESO DE CADUCIDAD DE OPERACIONES '.$fecha.'</b>';
		
		$qCaducidadOperacion = $cr->obtenerOperacionesACaducar($conexion,'porCaducar');
		
		$arrayOperadorTipoOperacion = array();
		
		while($caducidadOperacion = pg_fetch_assoc($qCaducidadOperacion)){
					
			$cr->enviarOperacionEstadoAnterior($conexion, $caducidadOperacion['id_operacion']);
			
			$cr->enviarOperacion($conexion, $caducidadOperacion['id_operacion'], 'noHabilitado', 'Operación caducada el '. $caducidadOperacion['fecha_finalizacion']);
			
			$cr->cambiarEstadoAreaXidSolicitud($conexion, $caducidadOperacion['id_operacion'], 'noHabilitado', 'Operación caducada el '. $caducidadOperacion['fecha_finalizacion']);
			
			$arrayOperadorTipoOperacion[] = $caducidadOperacion['id_operador_tipo_operacion'];			
			
		}
		
		$arrayOperadorTipoOperacion = array_unique($arrayOperadorTipoOperacion);
		
		foreach ($arrayOperadorTipoOperacion as $valor){
			
		    $cr->actualizarEstadoTipoOperacionPorIndentificadorSitio($conexion, $valor, 'porCaducar');			    
			
		    //NUEVO PROCESOS EN TABLAS RELACIONAS
		    $qcodigoTipoOperacion= $cr->obtenerCodigoXIdOperadorTipoOperacion($conexion, $valor);
		    $opcionArea=  pg_fetch_result($qcodigoTipoOperacion, 0, 'codigo');
		    $idArea=  pg_fetch_result($qcodigoTipoOperacion, 0, 'id_area');
		    
		    switch ($idArea){
		    		
		    	case 'IAP':
		    	break;
		    
		    	case 'SV':
		    	break;
		    	
		    	case 'LT':
		    	break;
		    	
		    	case 'SA':
		    	break;

		    	case 'IAV':
		    	break;

		    	case 'IAF':
		    	break;
		    			    			    			    
		    	case 'AI':
		    
		    		switch ($opcionArea){
		    				
		    			case 'ACO':
		    				$cr->cambiarEstadoCentroAcopioXIdOperadorTipoOperacion($conexion, $valor, 'inactivo');		    					
		    			break;
		    
		    			case 'MDT':
		    				$cr->cambiarEstadoVehiculoRecolectorXIdOperadorTipoOperacion($conexion, $valor, 'inactivo');		    					
		    			break;
		    
		    		}
		    			
		    	break;	    
		    
		    }
		    
		    //FIN NUEVO PROCESOS EN TABLAS RELACIONAS
		    
		}
		
		echo IN_MSG .'<b>FIN PROCESO DE CADUCIDAD DE OPERACIONES '.$fecha.'</b>';


		/**
		 * Proceso para inactivar certificado temporal de operacions de material reproductivo de SA.
		 */
		echo IN_MSG .'<b>INICIO PROCESO DE CADUCIDAD DE CERTIFICADO "PMR,CPM,DMR,AMR" DE SA '.$fecha.'</b>';

		$operaciones = $cr->obtenerOperacionesMaterilReproductivoSAporCaducar($conexion);

		$valores = '';   
		$idTipoOperacion = '';

		while ($filas = pg_fetch_row($operaciones)) {
			$valores .= "($filas[0],'$filas[1]','inactivo'),";
			$idTipoOperacion .="$filas[0],";
		}

		$idTipoOperacion = trim($idTipoOperacion , ',');
		$valores = trim($valores, ',');

		if ($valores != '') {

			echo '<br/> >>>>Inactivación de docuementos<br/>';
			$cr->actualizarEstadoDocumentoOperadorPorLista($conexion, $valores);
			echo '<br/> <<< Fin Inactivación de docuementos<br/>';
			
		}

		if ($idTipoOperacion != '') {

			echo '<br/> >>>>Actualizar estado anterior<br/>';
			$cr->actualizarEstadoAnteriorMaterialReproductivo($conexion, $idTipoOperacion);
			echo '<br/> <<< Fin Actualizar estado anterior<br/>';

			echo '<br/> >>>>Inactivación de operaciones<br/>';
			$cr->inactivarOperacionesMaterialReproductivo($conexion, $idTipoOperacion);
			echo '<br/> <<< Fin Inactivación de operaciones<br/>';

			echo '<br/> >>>>Inactivación área operaciones<br/>';
			$cr->inactivarAreaOperacionMaterialReproductivo($conexion, $idTipoOperacion);
			echo '<br/> <<< Fin Inactivación área operaciones<br/>';
			
		}

		echo IN_MSG .'<b>FIN PROCESO DE CADUCIDAD DE CERTIFICADO '.$fecha.'</b>';
		
		
	}
}else{
    $minutoS1=microtime(true);
    $minutoS2=microtime(true);
    $tiempo=$minutoS2-minutoS1;
    $xcadenota = "FECHA ".date("d/m/Y")." ".date("H:i:s");
    $xcadenota.= "; IP REMOTA ".$_SERVER['REMOTE_ADDR'];
    $xcadenota.= "; SERVIDOR HTTP ".$_SERVER['HTTP_REFERER'];
    $xcadenota.= "; SEGUNDOS ".$tiempo."\n";
    $arch = fopen("../../../aplicaciones/logs/cron/noHabilitado_reg_operador_".date("d-m-Y").".txt", "a+");
    fwrite($arch, $xcadenota);
    fclose($arch);
    
}
?>