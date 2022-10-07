<?php

require_once 'ControladorMail.php';

class ControladorMonitoreo{
	
	private function guardarDetalleEjecucionCron($conexion, $idCron, $tipoProceso, $origen, $fechaEjecucion){
		 
		$res = $conexion->ejecutarConsulta("INSERT INTO g_monitoreos.crons_detalles(id_cron, tipo_proceso, origen, fecha_ejecucion)
				VALUES ('$idCron', '$tipoProceso', '$origen', '$fechaEjecucion');");
		return $res;
	}
		
	public function obtenerCronPorCodigoEstado($conexion, $codigo, $estado = 'activo'){
				
		$res = $conexion->ejecutarConsulta("SELECT 
													*, (SELECT count(*) FROM pg_stat_activity) as numero_conexion_base
											FROM 
												g_monitoreos.crons
											WHERE
												codigo = '$codigo' and 
												estado='$estado';");
		
		$datosCron = pg_fetch_assoc($res);
		
		if($datosCron['estado'] == 'activo'){		    
		    
		    $periodicidad = $datosCron['periodicidad'];
		    $tipoPeriocidad = $datosCron['tipo_periodicidad'];
		    $idCron = $datosCron['id_cron'];
		    $codigoCron = $datosCron['codigo'];
		    
		    if($datosCron['numero_conexion'] >= $datosCron['numero_conexion_base']){		        
		        $resultado = $this->verificarProcesoEjecucion($conexion, $idCron, $tipoPeriocidad, $periodicidad, $codigoCron);
		    }else{
		        
		        $t = microtime(true);
		        $micro = sprintf("%06d",($t - floor($t)) * 1000000);
		        $d = new DateTime(date('Y-m-d H:i:s.'.$micro, $t));
		        $fechaAlmacenada = $d->format("Y/m/d G:i:s.u");
		        
		        $resultado = false;
		        $this->guardarDetalleEjecucionCron($conexion, $idCron, 'ERROR', $this->obtenerIPUsuario().'/Limite de conexión superado: '.$datosCron['numero_conexion_base'], $fechaAlmacenada);
		        $cMail = new ControladorMail();
		        $asunto = 'Limite de conexión superadas.';
		        $cuerpoMensaje = 'Se finalizo el proceso de ejecución del cron con código '.$codigoCron.' por superar el maximo de conexiones permitidas '.$datosCron['numero_conexion_base'];
		        $codigoModulo = 'MONITOREO';
		        $tablaModulo = '';
		        $destinatario = array();
		        array_push($destinatario, 'edison.ayala@agrocalidad.gob.ec');
		        array_push($destinatario, 'eduardo.anchundia@agrocalidad.gob.ec');
		        array_push($destinatario, 'jakeddy1907@hotmail.com');
		        $qGuardarCorreo=$cMail->guardarCorreo($conexion, $asunto, $cuerpoMensaje, 'Por enviar', $codigoModulo, $tablaModulo, '');
		        $idCorreo=pg_fetch_result($qGuardarCorreo, 0, 'id_correo');
		        $cMail->guardarDestinatario($conexion, $idCorreo, $destinatario);
		    }			
		}else{
			$resultado = false;
		} 
		
		return $resultado;
	}
	
	private function obtenerUltimoRegistroEjecucionCron($conexion, $idCron){
								
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_monitoreos.crons_detalles cd
											WHERE
												id_cron_detalle = (SELECT
																		max(id_cron_detalle)
																   FROM
																		g_monitoreos.crons c,
																		g_monitoreos.crons_detalles cd1
																   WHERE
																		c.id_cron = cd1.id_cron and 
																		c.id_cron = $idCron);");
		
		return $res;
		
		
	}
	
	private function obtenerDetalleCronPorFechas($conexion, $idCron, $fechaInicio, $fechaFin, $tipoProceso = 'NORMAL'){
								
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_monitoreos.crons_detalles cd
											WHERE
												to_char(fecha_ejecucion, 'YYYY/MM/DD HH24:MI:SS') > '$fechaInicio' 
												and to_char(fecha_ejecucion, 'YYYY/MM/DD HH24:MI:SS') < '$fechaFin' 
												and id_cron = $idCron
												and tipo_proceso = '$tipoProceso';");
		
		return $res;
	}
	
	private function obtenerIPUsuario(){
	
		if (isset($_SERVER["HTTP_CLIENT_IP"])){
			return $_SERVER["HTTP_CLIENT_IP"];
		}elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
			return $_SERVER["HTTP_X_FORWARDED_FOR"];
		}elseif (isset($_SERVER["HTTP_X_FORWARDED"])){
			return $_SERVER["HTTP_X_FORWARDED"];
		}elseif (isset($_SERVER["HTTP_FORWARDED_FOR"])){
			return $_SERVER["HTTP_FORWARDED_FOR"];
		}elseif (isset($_SERVER["HTTP_FORWARDED"])){
			return $_SERVER["HTTP_FORWARDED"];
		}else{
			return $_SERVER["REMOTE_ADDR"];
		}
	}
	
	private function verificarProcesoEjecucion($conexion, $idCron, $tipoPeriocidad, $periodicidad, $codigoCron){
				
		$resultado = false;
		
		$qDetalleCron = $this->obtenerUltimoRegistroEjecucionCron($conexion, $idCron);
		
		$t = microtime(true);
		$micro = sprintf("%06d",($t - floor($t)) * 1000000);
		$d = new DateTime(date('Y-m-d H:i:s.'.$micro, $t));
		$fechaAlmacenada = $d->format("Y/m/d G:i:s.u");
						
		if(pg_num_rows($qDetalleCron) == 0){
			$resultado = true;		
			$this->guardarDetalleEjecucionCron($conexion, $idCron, 'NORMAL', $this->obtenerIPUsuario(), $fechaAlmacenada);
		}else{
			
			$detalleCron = pg_fetch_assoc($qDetalleCron);

			switch ($tipoPeriocidad){
				
				case 'diario':					
					$fechaActual = date('Y/m/d',strtotime($fechaAlmacenada));
					$fechaInicio = $fechaActual. ' 00:00:00';
					$fechaFin = $fechaActual. ' 24:00:00'; 
					$registrosAlmacenados = $this->obtenerDetalleCronPorFechas($conexion, $idCron, $fechaInicio, $fechaFin);
					
				break;
				case 'minutos':
					$fechaFin = date('Y/m/d G:i:s',strtotime($fechaAlmacenada));
					$fechaInicio = date('Y/m/d G:i:s', strtotime($fechaAlmacenada.' -'.$periodicidad.' minute'));
					$fechaInicio = date('Y/m/d G:i:s', strtotime($fechaInicio.' +1 second'));
					$registrosAlmacenados = $this->obtenerDetalleCronPorFechas($conexion, $idCron, $fechaInicio, $fechaFin);
				break;

			}			
						
			//$fechaEjecucion = date('d/m/Y G:i:s',strtotime($detalleCron['fecha_ejecucion']));
			//$nuevafecha = strtotime ( '+30 second' , strtotime ( $fecha ) ) ;
						
			if(pg_num_rows($registrosAlmacenados) == 0){
				$resultado = true;
				$this->guardarDetalleEjecucionCron($conexion, $idCron, 'NORMAL', $this->obtenerIPUsuario(), $fechaAlmacenada);				
			}else{
				$resultado = false;
				$this->guardarDetalleEjecucionCron($conexion, $idCron, 'ERROR', $this->obtenerIPUsuario(), $fechaAlmacenada);
				$cMail = new ControladorMail();
				$asunto = 'Ejecución de proceso no autorizado. PRUEBA';
				$cuerpoMensaje = 'Se presenta una ejecución del proceso con código '.$codigoCron.' a las '.$fechaAlmacenada;
				$codigoModulo = 'MONITOREO';
				$tablaModulo = '';
				$destinatario = array();
				array_push($destinatario, 'edison.ayala@agrocalidad.gob.ec');
				array_push($destinatario, 'jakeddy1907@hotmail.com');
				$qGuardarCorreo=$cMail->guardarCorreo($conexion, $asunto, $cuerpoMensaje, 'Por enviar', $codigoModulo, $tablaModulo, '');
				$idCorreo=pg_fetch_result($qGuardarCorreo, 0, 'id_correo');				
				$cMail->guardarDestinatario($conexion, $idCorreo, $destinatario);
					
			}
			
		}
		
		return $resultado;
				
	}
		
}
