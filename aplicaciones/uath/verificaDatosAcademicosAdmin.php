<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastro.php';
require_once '../../clases/ControladorMail.php';
//require_once('../../FirePHPCore/FirePHP.class.php'); borrado
//ob_start(); borrado


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	$opcion=$_POST['opcion'];
	/*$nivel_instruccion = $_POST['nivel_instruccion'];
	$num_certificado = $_POST['num_certificado'];
	$institucion = $_POST['institucion'];
	$anios_estudio = $_POST['años_estudio'];
	$archivo= $_POST['archivo'];
	$egresado = $_POST['egresado'];
	$titulo= $_POST['titulo'];
	$pais=$_POST['pais'];
	$usuario=$_POST['usuario'];*/
	$observaciones=$_POST['observaciones'];
	$estado= $_POST['estado_item'];
	$id_datos_academicos=$_POST['academico_seleccionado'];

	$n        = count($estado);
	$i        = 0;
		
		try {
				$conexion = new Conexion();
				$cc = new ControladorCatastro();
				$cMail = new ControladorMail();
				/*if($opcion=='Verifica')
				{*/
				while ($i < $n)
				{
					$cc->verificaDatosAcademicosAdmin($conexion, $id_datos_academicos[$i], $estado[$i], $observaciones[$i]);
					if(strcmp($estado[$i],'Rechazado') == 0 ){
						$fila=pg_fetch_assoc($cc->obtenerDatosUsuarioAgrocalidad($conexion,$_POST['identificadorServidor']));
						$asunto = 'VALIDACIÓN DE INFORMACIÓN EN DATOS ACADÉMICOS';
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
							$tablaModulo = 'g_uath.datos_academicos';
								
							$qGuardarCorreo=$cMail->guardarCorreo($conexion, $asunto, $cuerpoMensaje, 'Por enviar', $codigoModulo, $tablaModulo, $id_datos_academicos[$i]);
							$idCorreo=pg_fetch_result($qGuardarCorreo, 0, 'id_correo');
							$cMail->guardarDestinatario($conexion, $idCorreo, $destinatario);
						}
					}
					$i++;
				}
					
				/*}*/
				/*if($opcion=='Actualizar')
				{
					$cc->actualizarDatosAcademicos($conexion, $id_datos_academicos, $nivel_instruccion, $num_certificado, $institucion, $anios_estudio, $egresado, $titulo, $pais, $archivo, 'Modificado');
				}*/
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = 'Los datos han sido actualizados satisfactoriamente';
				
				$conexion->desconectar();
				echo json_encode($mensaje);
				} catch (Exception $ex){
					pg_close($conexion);
					$error=$ex->getMessage();
					//$firephp->warn('Captura Error:'.$error);
					$mensaje['estado'] = 'error';
					$suma_cod_error;
					$error_code=0;
					$suma_cod_error= $error_code + (stristr($error, 'duplicate key')!=FALSE)?1:0;
					$error_code= $error_code + $suma_cod_error;
					$suma_cod_error= $error_code + (stristr($error, 'numero_contrato')!=FALSE)?2:0;
					$error_code= $error_code + $suma_cod_error;
					////$firephp->warn('Captura Error:'.$error);
					////$firephp->warn('Visor:'.stristr($error, 'duplicate key'));
					////$firephp->warn('Error Code:'.$error_code);
					
					////$firephp->warn('Error Code:'.$error_code);
					switch($error_code){
						case 0:		$mensaje['mensaje'] = 'No se puede ejecutar la sentencia';
							break;	
						case 3:		$mensaje['mensaje'] = 'Error: Ya existe un contrato con el mismo número';
							break;
					}
					echo json_encode($mensaje);
				}
/*	}catch (Exception $ex){
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = "Error al subir el archivo";
			echo json_encode($mensaje);
	}*/
} catch (Exception $ex) {
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}
?>