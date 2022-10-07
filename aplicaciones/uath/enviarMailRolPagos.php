<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>
	<?php

	if($_SERVER['REMOTE_ADDR'] == ''){


		require_once '../../clases/Conexion.php';
		require_once '../../clases/ControladorCatastro.php';
		require_once '../../clases/ControladorMonitoreo.php';
		require_once '../../clases/ControladorMail.php';
		define('IN_MSG','<br/> >>> ');
		try{
			$conexion = new Conexion();
			$ca = new ControladorCatastro();
			$cMail = new ControladorMail();
			$cm = new ControladorMonitoreo();

			$resultadoMonitoreo = $cm->obtenerCronPorCodigoEstado($conexion, 'CRON_TTHH_ROL_PAG');

			if($resultadoMonitoreo){

				try {
					echo IN_MSG.'Enviar Rol de Pagos<br>';
					$consultaRol = $ca->obtenerListadoRolPagos ($conexion,'true');
					while($consulta = pg_fetch_assoc($consultaRol)){
						//------------------------------------enviar mail de roles de pago--------------------------------------------------------------------------------
						$fila=pg_fetch_assoc($ca->obtenerDatosUsuarioAgrocalidad($conexion, $consulta['identificador']));
						$cuerpoMail=str_replace(".pdf", " ",$consulta['nombre_archivo']);
						$cuerpoMail=str_replace("_", " ",$cuerpoMail);
						$mesAnio = split(' ',$cuerpoMail);
						$asunto = $cuerpoMail.' AGROCALIDAD';
						$familiaLetra = "font-family:'Text Me One', 'Segoe UI', 'Tahoma', 'Helvetica', 'freesans', 'sans-serif'";
						$letraCodigo = "font-family:'Segoe UI', 'Helvetica'";
						$cuerpoMensaje = '<table><tbody>
								<tr><td style="'.$familiaLetra.'; text-align:center; font-size:30px; color:rgb(255,206,0); font-weight:bold; text-transform:uppercase;">Sistema GUIA <span style="color:rgb(19,126,255);">te </span> <span style="color:rgb(204,41,44);">saluda</span></td></tr>
								<tr><td style="'.$familiaLetra.'; padding-top:2px; font-size:17px; color:rgb(19,126,255); font-weight:bold;"></td></tr>
										<tr><td style="'.$familiaLetra.'; padding-top:20px; font-size:20px; color:rgb(46,78,158);font-weight:bold;">ROL DE PAGOS DEL MES DE '.strtoupper($mesAnio[2]).' DEL '.$mesAnio[3].'</td></tr>
												<tr><td style="'.$familiaLetra.'; padding-top:20px; font-size:14px;color:#2a2a2a;">Estimad@,</tr>
												<tr><td style="'.$familiaLetra.'; padding-top:30px; font-size:14px;color:#2a2a2a;">Se ha enviado su rol de pagos correspondiente al mes de '.strtolower($mesAnio[2]).' del '.$mesAnio[3].'. Recuerde qué también este documento lo tiene disponible en:<br> <span style="color:rgb(46,78,158); font-weight:bold;">Sistema GUIA</span> >>> <span style="color:rgb(46,78,158); font-weight:bold;">OPCION MIS DATOS</span> >>> <span style="color:rgb(46,78,158); font-weight:bold;">ROL DE PAGOS.</span> </td></tr>
														<tr><td style="'.$familiaLetra.'; padding-top:30px; font-size:14px;color:#2a2a2a;"><span style="'.$familiaLetra.'; padding-top:20px; font-size:14px; color:rgb(204,41,44);font-weight:bold;" >NOTA: </span>Este correo fue generado automaticamente por el sistema GUIA, por favor no responder a este mensaje. </td></tr>
																<tr><td style="'.$familiaLetra.'; padding-top:25px; font-size:14px;color:#2a2a2a;">El equipo de Desarrollo Tecnológico de Agrocalidad </td></tr>
																</tbody></table>';
						$destinatario = array();
						if($fila['mail_institucional']!= ''){
							array_push($destinatario, $fila['mail_institucional']);
						}else if($fila['mail_personal'] !=''){
							array_push($destinatario, $fila['mail_personal']);
						}
						$ruta = dirname(__FILE__).'/archivosRolPagos/pdf/'.$consulta['identificador'];
						$arrayAdjunto=array();
						$arrayAdjunto[]= $ruta.'/'.$consulta['nombre_archivo'];
						$estadoMail = $cMail->enviarMail($conexion, $destinatario, $asunto, $cuerpoMensaje,$arrayAdjunto);
						$ca->actualizarEstadoMailRolPagos ($conexion, $consulta['id_funcionario_rol_pago']);
						//-------------------------------------------------------------------------------------------------------------------------------------------------
						echo IN_MSG.'Enviar '.$consulta['nombre_archivo'].'>>'.$consulta['identificador'].' '.$estadoMail.'<br>';
					}
					$conexion->desconectar();
				} catch (Exception $ex){
					pg_close($conexion);
					echo IN_MSG.'Error de ejecucion';
				}
			}
		} catch (Exception $ex) {

			echo IN_MSG.'Error de conexión a la base de datos';
		}

	}else{

		$minutoS1=microtime(true);
		$minutoS2=microtime(true);
		$tiempo=$minutoS2-$minutoS1;
		$xcadenota = "FECHA ".date("d/m/Y")." ".date("H:i:s");
		$xcadenota.= "; IP REMOTA ".$_SERVER['REMOTE_ADDR'];
		$xcadenota.= "; SERVIDOR HTTP ".$_SERVER['HTTP_REFERER'];
		$xcadenota.= "; SEGUNDOS ".$tiempo."\n";
		$arch = fopen("../../aplicaciones/logs/cron/mail_rol_pago_".date("d-m-Y").".txt", "a+");
		fwrite($arch, $xcadenota);
		fclose($arch);

	}
	?>
</body>
</html>
