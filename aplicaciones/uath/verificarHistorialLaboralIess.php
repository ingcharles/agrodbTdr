<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastro.php';
require_once '../../clases/ControladorMail.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	$opcion=$_POST['opcion'];
	$observaciones=$_POST['observaciones'];
	$estado= $_POST['estado_item'];
	$historial_seleccionado=$_POST['historial_seleccionado'];
	$n        = count($estado);
	$i        = 0;

		try {
				$conexion = new Conexion();
				$cc = new ControladorCatastro();
				$cMail = new ControladorMail();
				
				while ($i < $n) 
				{
					$cc->modificarHistorialLaboralIess ($conexion, $historial_seleccionado[$i], '', $observaciones[$i],$estado[$i],'','');
					if(strcmp($estado[$i],'Rechazado') == 0 ){
						$fila=pg_fetch_assoc($cc->obtenerDatosUsuarioAgrocalidad($conexion,$_POST['identificadorServidor']));
						$asunto = 'VALIDACIÓN DE INFORMACIÓN EN HISTORIAL LABORAL IESS';
						$familiaLetra = "font-family:Text Me One,Segoe UI, Tahoma, Helvetica, freesans, sans-serif";
						$letraCodigo = "font-family:'Segoe UI', 'Helvetica'";
						$cuerpoMensaje = '<table><tbody>
											<tr><td style="'.$familiaLetra.'; padding-top:2px; font-size:17px; color:rgb(19,126,255); font-weight:bold;"></td></tr>
											<tr><td style="'.$familiaLetra.'; padding-top:20px; font-size:20px; color:rgb(46,78,158);font-weight:bold;">VALIDACIÓN DE INFORMACIÓN</td></tr>
											<tr><td style="'.$familiaLetra.'; padding-top:20px; font-size:14px;color:#2a2a2a;">Estimad@,</tr>
											<tr><td style="'.$familiaLetra.'; padding-top:30px; font-size:14px;color:#2a2a2a;">Se ha revisado la información ingresada como sustento de su hoja de vida, la misma que presenta ciertos errores, favor corregir y enviar nuevamente para la revisión por parte de la Dirección de Administración de Recursos Humanos. <br><br>Observaciones: '.$observaciones[$i].'</td></tr>
											<tr><td style="'.$familiaLetra.'; padding-top:30px; font-size:14px;color:#2a2a2a;"><br>Gracias por su colaboración.</td></tr>
											<tr><td style="'.$familiaLetra.'; padding-top:30px; font-size:14px;color:#2a2a2a;"><span style="'.$familiaLetra.'; padding-top:20px; font-size:14px; color:rgb(204,41,44);font-weight:bold;" >NOTA: </span>Este correo fue generado automaticamente por el sistema GUIA, por favor no responder a este mensaje. </td></tr>
											</tbody></table>';
						$destinatario = array();
						$mailDestino='';
						if($fila['mail_institucional']!= ''){
							array_push($destinatario, $fila['mail_institucional']);
							$mailDestino=$fila['mail_institucional'];
						}else if($fila['mail_personal'] !=''){
							array_push($destinatario, $fila['mail_personal']);
							$mailDestino=$fila['mail_personal'];
						}
							
						if($mailDestino != ''){
							//----------------guardar correo para proceso automatico-----------------------
							$codigoModulo = 'PRG_CATASTRO';
							$tablaModulo = 'g_uath.datos_historial_laboral_iess';
					
							$qGuardarCorreo=$cMail->guardarCorreo($conexion, $asunto, $cuerpoMensaje, 'Por enviar', $codigoModulo, $tablaModulo, $historial_seleccionado[$i]);
							$idCorreo=pg_fetch_result($qGuardarCorreo, 0, 'id_correo');
							$cMail->guardarDestinatario($conexion, $idCorreo, $destinatario);
						}
					}
					$i++;
				}
				
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = 'Los datos han sido actualizados satisfactoriamente';
				
				$conexion->desconectar();
				echo json_encode($mensaje);
				} catch (Exception $ex){
					pg_close($conexion);
					$mensaje['estado'] = 'error';
					$mensaje['mensaje'] = "Error al ejecutar sentencia";
					echo json_encode($mensaje);
				}

} catch (Exception $ex) {
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}
?>
