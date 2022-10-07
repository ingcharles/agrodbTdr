<?php
//if($_SERVER['REMOTE_ADDR'] == ''){
if(1){
	require_once '../../../clases/Conexion.php';
	require_once '../../../clases/ControladorMail.php';
	require_once '../../../clases/ControladorMonitoreo.php';
	require_once '../../../clases/ControladorRegistroOperador.php';

	$conexion = new Conexion();
	$cr = new ControladorRegistroOperador();
	$cMail = new ControladorMail();
	$cm = new ControladorMonitoreo();
	
	define('PRO_MSG', '<br/> ');
	define('IN_MSG','<br/> >>> ');
	$fecha = date("Y-m-d h:m:s");

	//$resultadoMonitoreo = $cm->obtenerCronPorCodigoEstado($conexion, 'CRON_CADUCAR_REG_OPER');
	
	//if($resultadoMonitoreo){
	if(1){
		$datosSincronizar = $cr->obtenerRegistrosCaducidadVigenciaRegistroOperador($conexion, '1');
		

		while ($fila = pg_fetch_assoc($datosSincronizar)){
									
			echo IN_MSG .'<b>INICIO ACTUALIZACIÓN ESTADO VIGENCIA OPERACIONES DEL OPERADOR '.$fila['identificador'].'</b>';
			
			$qOperacionesACaducar = $cr->obtenerOperacionesCaducidadVigenciaRegistroOperadorXOperador($conexion, $fila['identificador']);
			
			$arrayOperadorTipoOperacion = array();
						
			while($operacionesACaducar = pg_fetch_assoc($qOperacionesACaducar)){
			    
			    $cr->enviarOperacionEstadoAnterior($conexion, $operacionesACaducar['id_operacion']);
			    
			    $cr->enviarOperacion($conexion, $operacionesACaducar['id_operacion'], 'porCaducar', 'Operación por caducar el '. $operacionesACaducar['fecha_finalizacion']);
			    
			    $cr->cambiarEstadoAreaXidSolicitud($conexion, $operacionesACaducar['id_operacion'], 'porCaducar', 'Operación por caducar el '. $operacionesACaducar['fecha_finalizacion']);
                		       
			    $arrayOperadorTipoOperacion[] = $operacionesACaducar['id_operador_tipo_operacion'];
			    
			}
			
			$arrayOperadorTipoOperacion = array_unique($arrayOperadorTipoOperacion);
			
			foreach ($arrayOperadorTipoOperacion as $valor){
			    $cr->actualizarEstadoTipoOperacionPorIndentificadorSitio($conexion, $valor, 'porCaducar');
			    
			    //NUEVO PROCESOS EN TABLAS RELACIONAS
			    $qcodigoTipoOperacion= $cr->obtenerCodigoXIdOperadorTipoOperacion($conexion, $valor);
			    $opcionArea=  pg_fetch_result($qcodigoTipoOperacion, 0, 'codigo');
			    $idArea=  pg_fetch_result($qcodigoTipoOperacion, 0, 'id_area');
			    $nombreOperacion=  pg_fetch_result($qcodigoTipoOperacion, 0, 'nombre');
			    
			    switch ($idArea){
			    
			    	case 'IAP':
			    	
			    		$nombreAreaTematica = "Registro de Insumos Agrícolas";
			    		 
			    		switch ($opcionArea){
			    		
				    		case 'DIS':
				    			$nombreTipoOperacion = $nombreOperacion;			    			
				    		break;
				    		case 'ENV':
				    			$nombreTipoOperacion = $nombreOperacion;
				    		break;
				    		case 'FOR':
				    			$nombreTipoOperacion = $nombreOperacion;
				    		break;
				    		case 'FRA':
				    			$nombreTipoOperacion = $nombreOperacion;			    			
				    		break;
				    		case 'AER':
				    			$nombreTipoOperacion = $nombreOperacion;			    			
				    		break;
				    		case 'MAN':
				    			$nombreTipoOperacion = $nombreOperacion;			    			
				    		break;
				    		case 'IMP':
				    			$nombreTipoOperacion = $nombreOperacion;
				    			break;
				    		case 'EXP':
				    			$nombreTipoOperacion = $nombreOperacion;			    			
				    		break;
				    		case 'DMR':
				    			$nombreTipoOperacion = $nombreOperacion;
				    		break;
				    		case 'ALM':
				    			$nombreTipoOperacion = $nombreOperacion;			    			
				    		break;
				    		case 'ODI':
				    			$nombreTipoOperacion = $nombreOperacion;			    			
				    		break;
				    		
			    		}
			    		
			    	break;		    	
			    				    	
				    case 'SV':
				    		
				    	$nombreAreaTematica = "Sanidad Vegetal";
				    	
				    	switch ($opcionArea){
				    		 
				    		case 'PRO':
				    			$nombreTipoOperacion = $nombreOperacion;
				    		break;
				    		case 'CUA':
				    			$nombreTipoOperacion = $nombreOperacion;
				    		break;
				    		case 'ACO':
				    			$nombreTipoOperacion = $nombreOperacion;
				    		break;
				    		case 'COM':
				    			$nombreTipoOperacion = $nombreOperacion;
				    		break;
				    		case 'IND':
				    			$nombreTipoOperacion = $nombreOperacion;
				    		break;
				    		case 'TRA':
				    			$nombreTipoOperacion = $nombreOperacion;
				    		break;
				    		case 'FRA':
				    			$nombreTipoOperacion = $nombreOperacion;
				    		break;
				    		case 'AGE':
				    			$nombreTipoOperacion = $nombreOperacion;
				    		break;
				    		case 'CON':
				    			$nombreTipoOperacion = $nombreOperacion;
				    		break;
				    		case 'EPO':
				    			$nombreTipoOperacion = $nombreOperacion;
				    		break;
				    		case 'IMP':
				    			$nombreTipoOperacion = $nombreOperacion;
				    		break;
				    		case 'EXP':
				    			$nombreTipoOperacion = $nombreOperacion;
				    		break;
				    		case 'VIV':
				    			$nombreTipoOperacion = $nombreOperacion;
				    		break;
				    		case 'ALM':
				    			$nombreTipoOperacion = $nombreOperacion;
				    		break;
				    		case 'VER':
				    			$nombreTipoOperacion = $nombreOperacion;
				    		break;
				    		case 'INV':
				    			$nombreTipoOperacion = $nombreOperacion;
				    		break;
				    		case 'MIM':
				    			$nombreTipoOperacion = $nombreOperacion;
				    		break;
				    		case 'REP':
				    			$nombreTipoOperacion = $nombreOperacion;
				    		break;
				    		case 'AVI':
				    			$nombreTipoOperacion = $nombreOperacion;
				    		break;
				    	
				    	}
				    		
				    break;
			    
				    case 'LT':
				    
				    	$nombreAreaTematica = "Registro de Laboratorios";
				    	 
				    	switch ($opcionArea){
				    		 
				    		case 'APL':
				    			$nombreTipoOperacion = $nombreOperacion;
				    		break;
				    		case 'AGL':
				    			$nombreTipoOperacion = $nombreOperacion;
				    		break;
				    		case 'AML':
				    			$nombreTipoOperacion = $nombreOperacion;
				    		break;
				    		case 'LCLI':
				    			$nombreTipoOperacion = $nombreOperacion;
				    		break;
				    		case 'LSAN':
				    			$nombreTipoOperacion = $nombreOperacion;
				    		break;
				    			 
				    	}
				    	 
				    break;
				    
				    case 'SA':
				    
				    	$nombreAreaTematica = "Sanidad Animal";
				    	 
				    	switch ($opcionArea){
				    		 
				    		case 'COM':
				    			$nombreTipoOperacion = $nombreOperacion;
				    		break;
				    		case 'EPO':
				    			$nombreTipoOperacion = $nombreOperacion;
				    		break;
				    		case 'IND':
				    			$nombreTipoOperacion = $nombreOperacion;
				    		break;
				    		case 'INC':
				    			$nombreTipoOperacion = $nombreOperacion;
				    		break;
				    		case 'PRO':
				    			$nombreTipoOperacion = $nombreOperacion;
				    		break;
				    		case 'REP':
				    			$nombreTipoOperacion = $nombreOperacion;
				    		break;
				    		case 'IMP':
				    			$nombreTipoOperacion = $nombreOperacion;
				    		break;
				    		case 'EXP':
				    			$nombreTipoOperacion = $nombreOperacion;
				    		break;
				    		case 'COD':
				    			$nombreTipoOperacion = $nombreOperacion;
				    		break;
				    		case 'CUA':
				    			$nombreTipoOperacion = $nombreOperacion;
				    		break;
				    		case 'DIS':
				    			$nombreTipoOperacion = $nombreOperacion;
				    		break;
				    		case 'VAC':
				    			$nombreTipoOperacion = $nombreOperacion;
				    		break;
				    		case 'ADV':
				    			$nombreTipoOperacion = $nombreOperacion;
				    		break;
				    		case 'FER':
				    			$nombreTipoOperacion = $nombreOperacion;
				    		break;
				    		case 'AFA':
				    			$nombreTipoOperacion = $nombreOperacion;
				    		break;
				    		case 'EMO':
				    			$nombreTipoOperacion = $nombreOperacion;
				    		break;
				    		case 'MVD':
				    			$nombreTipoOperacion = $nombreOperacion;
				    		break;
				    		case 'MVB':
				    			$nombreTipoOperacion = $nombreOperacion;
				    		break;
				    		case 'MVC':
				    			$nombreTipoOperacion = $nombreOperacion;
				    		break;
				    		case 'MVE':
				    			$nombreTipoOperacion = $nombreOperacion;
				    		break;
				    		case 'MEU':
				    			$nombreTipoOperacion = $nombreOperacion;
				    		break;
				    		case 'RFM':
				    			$nombreTipoOperacion = $nombreOperacion;
				    		break;
				    		case 'EDM':
				    			$nombreTipoOperacion = $nombreOperacion;
				    		break;
				    		case 'CCP':
				    			$nombreTipoOperacion = $nombreOperacion;
				    		break;
				    		case 'CMA':
				    			$nombreTipoOperacion = $nombreOperacion;
				    		break;
				    		case 'SPB':
				    			$nombreTipoOperacion = $nombreOperacion;
				    		break;
				    		case 'CHM':
				    			$nombreTipoOperacion = $nombreOperacion;
				    		break;
				    		case 'CTM':
				    			$nombreTipoOperacion = $nombreOperacion;
				    		break;
				    		case 'ADP':
				    			$nombreTipoOperacion = $nombreOperacion;
				    		break;
				    		case 'CDM':
				    			$nombreTipoOperacion = $nombreOperacion;
				    		break;
				    			 
				    	}
				    
				    break;
				    	 
				    case 'IAV':
				    
				    	$nombreAreaTematica = "Registro de Insumos Pecuarios";
				    
				    	switch ($opcionArea){
				    		 
				    		case 'DIS':
				    			$nombreTipoOperacion = $nombreOperacion;
				    		break;
				    		case 'ENV':
				    			$nombreTipoOperacion = $nombreOperacion;
				    		break;
				    		case 'FOR':
				    			$nombreTipoOperacion = $nombreOperacion;
				    		break;
				    		case 'FRA':
				    			$nombreTipoOperacion = $nombreOperacion;
				    		break;
				    		case 'IMP':
				    			$nombreTipoOperacion = $nombreOperacion;
				    		break;
				    		case 'EXP':
				    			$nombreTipoOperacion = $nombreOperacion;
				    		break;
				    		case 'DMR':
				    			$nombreTipoOperacion = $nombreOperacion;
				    		break;
				    		case 'ALM':
				    			$nombreTipoOperacion = $nombreOperacion;
				    		break;
				    		case 'ALT':
				    			$nombreTipoOperacion = $nombreOperacion;
				    		break;
				    
				    	}
				    
				    break;				    
				    
			    	case 'AI':
		
			    		$nombreAreaTematica = "Incocuidad de los alimentos";
			    		
			    		switch ($opcionArea){
			    
			    			case 'FAE':
			    				$nombreTipoOperacion = $nombreOperacion;
			    			break;	
			    			case 'PRO':
			    				$nombreTipoOperacion = $nombreOperacion;
			    			break;
			    			case 'PRC':
			    				$nombreTipoOperacion = $nombreOperacion;
			    			break;
			    			case 'COM':
			    				$nombreTipoOperacion = $nombreOperacion;
			    			break;
			    			case 'ACO':			    				
			    				$nombreTipoOperacion = $nombreOperacion;			    				
			    				$cr->cambiarEstadoCentroAcopioXIdOperadorTipoOperacion($conexion, $valor, 'porCaducar'); 			
			    			break;			    
			    			case 'MDT':
			    				$nombreTipoOperacion = $nombreOperacion;			    					
			    				$cr->cambiarEstadoVehiculoRecolectorXIdOperadorTipoOperacion($conexion, $valor, 'porCaducar');			    				
			    			break;
			    
			    		}
			    		 
			    	break;			    	
			    	
			    	case 'IAF':
			    	
			    		$nombreAreaTematica = "Registro de insumos fertilzantes";
			    		 
			    		switch ($opcionArea){
			    			 
			    			case 'DIS':
			    				$nombreTipoOperacion = $nombreOperacion;
			    			break;
			    			case 'ENV':
			    				$nombreTipoOperacion = $nombreOperacion;
			    			break;
			    			case 'FOR':
			    				$nombreTipoOperacion = $nombreOperacion;
			    			break;
			    			case 'FRA':
			    				$nombreTipoOperacion = $nombreOperacion;
			    			break;
			    			case 'ALM':
			    				$nombreTipoOperacion = $nombreOperacion;
			    			break;
			    			case 'IEX':
			    				$nombreTipoOperacion = $nombreOperacion;
			    			break;
			    			case 'FED':
			    				$nombreTipoOperacion = $nombreOperacion;
			    			break;
			    				 
			    		}
			    	
			    	break;			    	
			    
			    }
			    
			    //FIN NUEVO PROCESOS EN TABLAS RELACIONAS
			    
			    
			}			
			
			echo IN_MSG .'<b>FIN ACTUALIZACIÓN ESTADO VIGENCIA OPERACIONES DEL OPERADOR '.$fila['identificador'].'</b>';
			
			echo IN_MSG .'<b>INICIO PROCESO ENVÍO MAIL OPERADOR '.$fecha.'</b>';				
			
			echo PRO_MSG;
			echo IN_MSG .'Operador #'.$fila['identificador'].'';
			
			$cuerpoMensaje= '<html xmlns="http://www.w3.org/1999/xhtml"><body style="margin:0; padding:0;">
					<style type="text/css">
			
						.titulo  {
							margin-top: 30px;
							width: 800px;
							text-align: center;
							font-size: 14px;
							font-weight: bold;
							font-family:Times New Roman;
						}
			
						.lineaDos{
							font-style: oblique;
							font-weight: normal;
						}
			
						.lineaLeft{
							text-align: left;
						}
			
						.lineaEspacio{
							height: 35px;
						}
						.lineaEspacioMedio{
							height: 50px;
						}
						.espacioLeft{
							padding-left: 15px;
						}
					</style>';
			
			$cuerpoMensaje.='<table class="titulo">
					<thead>
					<tr><th>Usted tiene una operación de <b>' . $nombreTipoOperacion . ' - ' . $nombreAreaTematica . '</b> próxima a caducar.</th></tr>
					</thead>
					<tbody>
					<tr><td class="lineaDos lineaEspacio">Tener en cuenta para el respectivo proceso de renovación.</td></tr>
					<tr><td class=""><a href="https://guia.agrocalidad.gob.ec">guia.agrocalidad.gob.ec</a></td></tr>
					</tbody>
					<tfooter>
					<tr><td class="lineaEspacioMedio"></td></tr>
					<tr><td class="lineaDos lineaLeft espacioLeft"><span style="font-weight:bold;" >NOTA: </span>Este correo fue generado automaticamente por el sistema GUIA, por favor no responder a este mensaje. </td></tr>
					<tr><td class="lineaDos lineaLeft espacioLeft">Dirección de Tecnologías de Información y Comunicación</td></tr>
					</tfooter>
					</table>';
			
			$asunto = 'Certificación próxima a caducar.';
			$codigoModulo='';
			$tablaModulo='';
			
			$destinatarios = array();
				
			echo IN_MSG .'Razón social '.$fila['razon_social'].'';
			
			array_push($destinatarios, $fila['correo']);
			echo IN_MSG .'Correo '.$fila['correo'].'';
			
			$qGuardarCorreo=$cMail->guardarCorreo($conexion, $asunto, $cuerpoMensaje, 'Por enviar', $codigoModulo, $tablaModulo, '');
			$idCorreo=pg_fetch_result($qGuardarCorreo, 0, 'id_correo');
			$cMail->guardarDestinatario($conexion, $idCorreo,$destinatarios);
			
		}
	}
}else{
	$minutoS1=microtime(true);
	$minutoS2=microtime(true);
	$tiempo=$minutoS2-minutoS1;
	$xcadenota = "FECHA ".date("d/m/Y")." ".date("H:i:s");
	$xcadenota.= "; IP REMOTA ".$_SERVER['REMOTE_ADDR'];
	$xcadenota.= "; SERVIDOR HTTP ".$_SERVER['HTTP_REFERER'];
	$xcadenota.= "; SEGUNDOS ".$tiempo."\n";
	$arch = fopen("../../../aplicaciones/logs/cron/caducar_reg_operador_".date("d-m-Y").".txt", "a+");
	fwrite($arch, $xcadenota);
	fclose($arch);

}
?>