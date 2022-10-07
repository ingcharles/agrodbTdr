<?php
require_once '../../../clases/Conexion.php';
require_once '../../../clases/ControladorEmpleados.php';
require_once '../../../clases/ControladorRegistroOperador.php';
require_once '../../../clases/ControladorMail.php';
require_once '../../../clases/ControladorUsuarios.php';



$conexion = new Conexion();
$cr = new ControladorRegistroOperador();
$ce = new ControladorEmpleados();
$cMail = new ControladorMail();
$cu = new ControladorUsuarios();

$identificador = htmlspecialchars ($_POST['identificador'],ENT_NOQUOTES,'UTF-8');
$opcion = htmlspecialchars ($_POST['opcion'],ENT_NOQUOTES,'UTF-8');
$valorUsuario = htmlspecialchars ($_POST['valorUsuario'],ENT_NOQUOTES,'UTF-8');
$mailUsuario = htmlspecialchars ($_POST['mail'],ENT_NOQUOTES,'UTF-8');
$origenSolicitud = htmlspecialchars ($_POST['origenSolicitud'],ENT_NOQUOTES,'UTF-8');

$mail = false;

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

switch ($opcion){

	case 'mail':
				
		$qTipoUsuario  = $cu->obtenerTipoUsuario($conexion, $identificador);
	    $tipoUsuario = pg_fetch_assoc($qTipoUsuario);
			
	    if($tipoUsuario['codificacion_perfil'] == "PFL_USUAR_INT" || $tipoUsuario['codificacion_perfil'] == "PFL_USUAR_CIV_PR"){
		
			$resultado = $ce->obtenerFichaEmpleado($conexion, $identificador);
		
			if(pg_num_rows($resultado)== 0){
		
				echo '<div class="mensajeError">Usted no tiene un usuario registrado en el sistema G.U.I.A.<div>';
				echo '<script type="text/javascript">$("button").hide();</script>';
						
			}else{
				$mail = true;
				$mensajeMail = 'Ingresar el e-mail institucional, el cual registró en su ficha técnica. Su clave temporal será enviada a su correo registrado.';
				$mensajeAyuda = '<div class="ayuda">*Sistema G.U.I.A. opción >>> Mis datos personales</div>';
				$tipoUsuario = 'Interno';
			}
		}else{
			$mail = true;
			$mensajeMail = 'Ingresar el e-mail con el que se registró en el Sistema GUIA. Su clave temporal será enviada a su correo registrado.';
			$mensajeAyuda = '';
			$tipoUsuario = 'Externo';
		}
		
		
		
		if($mail){
			
			$qUsuarioCifrado = $cu->buscarUsuarioCifrado($conexion, md5($identificador));

			if(pg_num_rows($qUsuarioCifrado) > 0){
				echo '<div id="contenedor">
		        	<p>Paso 2</p>
			    	<div>'.$mensajeMail.'</div>
		       	 	<input class="input_texto"type="text" id="mail" name="mail">
			    	'.$mensajeAyuda.'
		        </div>';
				
				echo '<script type="text/javascript">$("button").show();</script>';
				
			}
			
		}
		
	break;
	
	case 'verificarMail':
		
		if($valorUsuario == 'Interno'){
			
			$resultadoMail = $ce->verificarCorreoElectronicoUsuarioInterno($conexion, $identificador, $mailUsuario);
			
		}else{
			$resultadoMail = $cr->verificarCorreoElectronicoUsuarioExterno($conexion, $identificador, $mailUsuario);
		}
		
		if(pg_num_rows($resultadoMail)==0){
		    if($origenSolicitud=='recuperarClave'){
			    echo '<div class="mensajeError">El mail ingresado no se encuentra registrado para el identificador '.$identificador.'.<div>';
		    }else{
		        $mensaje['estado'] = 'FALLO';
		        $mensaje['mensaje'] = 'El mail ingresado no se encuentra registrado para el identificador '.$identificador;
		        
		        echo json_encode($mensaje);
		    }
		}else{

			$codigo = $cu->generarCodigoAcceso(8);
			
			$ipUsuario = $cu->obtenerIPUsuario();
			
			$cu->actualizarCodigoTemporal($conexion, $identificador, $codigo, $ipUsuario);
			
			$cu->modificarEstadoUsuarioSistema($conexion, $identificador, 1);
			
			$asunto = 'Recuperación de contraseña Sistema G.U.I.A.';
			
			$codigoModulo = $origenSolicitud;
			$tablaModulo='';
			
			$familiaLetra = "font-family:'Text Me One', 'Segoe UI', 'Tahoma', 'Helvetica', 'freesans', 'sans-serif'";
			$letraCodigo = "font-family:'Segoe UI', 'Helvetica'";
			
			$cuerpoMensaje = '<table><tbody>
							<tr><td style="'.$familiaLetra.'; font-size:25px; color:rgb(255,206,0); font-weight:bold; text-transform:uppercase;">Agrocalidad <span style="color:rgb(19,126,255);">te </span> <span style="color:rgb(204,41,44);">saluda</span></td></tr>
							<tr><td style="'.$familiaLetra.'; padding-top:2px; font-size:17px; color:rgb(19,126,255); font-weight:bold;">Cuenta Sistema GUIA</td></tr>
							<tr><td style="'.$familiaLetra.'; padding-top:20px; font-size:42px; color:rgb(236,107,109);">Código para restablecer contraseña </td></tr>
							<tr><td style="'.$familiaLetra.'; padding-top:20px; font-size:14px;color:#2a2a2a;">Le notificamos que se realizo una solicitud de cambio de contraseña.</tr>
							<tr><td style="'.$familiaLetra.'; padding-top:5px; font-size:14px;color:#2a2a2a;">Tu código es: <span style="'.$letraCodigo.' font-size:14px; font-weight:bold; color:#2a2a2a;">'.$codigo.'</span></td></tr>
							<tr><td style="'.$familiaLetra.'; padding-top:5px; font-size:14px;color:#2a2a2a;">Puede acceder directamente a travez de este link <a href="http://181.112.155.173/agrodbPrueba/aplicaciones/publico/recuperarClave/recuperarClave.php?id='.md5($identificador).'">Click aqui</a></td></tr>
							<tr><td style="'.$familiaLetra.'; padding-top:20px; font-size:14px;color:#2a2a2a;">Si no es así por favor comunicarse inmediatamente con nosotros al número 23960100 ext. 3203, 3204, 3205.</td></tr>
							<tr><td style="'.$familiaLetra.'; padding-top:20px; font-size:14px;color:#2a2a2a;">Recuerde que es su responsabilidad el cuidado de la información de acceso al sistema G.U.I.A. Por ningún motivo comparta su contraseña con terceros y si sospecha que alguien más tiene conocimiento de ésta, proceda al cambio inmediato.</td></tr>
							<tr><td style="'.$familiaLetra.'; padding-top:20px; font-size:14px;color:#2a2a2a;">Esperamos haber resuelto su inconveniente, Gracias </td></tr>
							<tr><td style="'.$familiaLetra.'; padding-top:5px; font-size:14px;color:#2a2a2a;">El equipo de Desarrollo Tecnológico de Agrocalidad </td></tr>		
							</tbody></table>';
			
			$destinatarios = array();
			array_push($destinatarios, $mailUsuario);
			
			$qGuardarCorreo = $cMail->guardarCorreo($conexion, $asunto, $cuerpoMensaje, 'Por enviar', $codigoModulo, $tablaModulo, '');
			
			$idCorreo = pg_fetch_result($qGuardarCorreo, 0, 'id_correo');
			
			$cMail->guardarDestinatario($conexion, $idCorreo, $destinatarios);
			
			if($origenSolicitud=='recuperarClave'){
			    echo "<script type='text/javascript'>$(location).attr('href','recuperarClave.php?id=".md5($identificador)."');</script>";
			}else{
			    if($idCorreo>0){
			        $mensaje['estado'] = 'exito';
			        $mensaje['mensaje'] = 'Se ha guardado el nuevo usuario y enviado el mail al correo registrado para cambio de clave';
			    }else{
			        $mensaje['estado'] = 'FALLO';
			        $mensaje['mensaje'] = 'No se ha podido enviar el mail al usuario '.$identificador;
			    }
			    
			    echo json_encode($mensaje);
			}
		}
	
	break;
}


if($origenSolicitud=='recuperarClave'){
    echo '<script type="text/javascript">
    
            $(document).ready(function(){
            	 $("#valorUsuario").val("'.$tipoUsuario.'");
            });
            
          </script>';
}

?>