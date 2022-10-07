<?php
session_start();

require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorChat.php';


$mensaje = array();
$mensaje['estado'] = 'exito';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

$data  = json_decode($_POST['data'], true);

$usuario= $_POST['usuario'];
$contacto= $_POST['contacto'];

$conexion = new Conexion();
$cc = new ControladorChat();

try{
		
	try {
		
		$items = array();		
		
		$fila=$cc->obtenerSolicitudesRecibidas($conexion, $usuario);
				
		$solicitud=pg_num_rows($fila);
			
		if($fila){
				
			while($solicitud=pg_fetch_assoc($fila)){
					
				if($solicitud['fotografia']!=''){
					$foto=$solicitud['fotografia'];
				}else{
					$foto='aplicaciones/agroChat/img/user2.png';
				}				
				
				$pie='<a class="enviarSolicitudLink"  title="Aceptar solicitud" onclick="aceptarSolicitud('."'".$solicitud['identificador_usuario']."'".')">Aceptar Solicitud</a> <a class="cancelarSolicitudLink" title="Rechazar solicitud" onclick="rechazarSolicitud('."'".$solicitud['identificador_usuario']."'".')">Rechazar</a>';
					
				$contenido='<li id="listn_'.$solicitud['identificador_usuario'].'" class="notificacion" onmouseover="quitarNotificacion(this)">
								<div class="contenedorContactoNuevo" id="csn_'. $solicitud['identificador_usuario'].'" >'.
								'<div class="fotoUsuarioChatNuevo" ><img src="'.$foto.'" class="imgUsuarioChatNuevo"> </div>'.
								'<div class="contenedorUsuarioDatosNuevo">'.
								'<div class="nombreUsuarioNuevo" >'.$solicitud['nombres'].'</div>'.
								'<div class="contenedorEnviarSolicitud" id="sru_'.$solicitud['identificador_usuario'].'" >'.$pie.'</div>'.
								'</div>
						</div></li> '	;					
					
							
				$items[] = array(contenido => $contenido, contacto=>$solicitud['identificador_usuario']);						
					
			}
				
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = $items;
		  }else{
				$mensaje['estado'] = 'error';
				$mensaje['mensaje'] = 'Error al obtner solicitudes';
				//$contenido.="<div class='mensajeAviso borde-5 letraNegrita '>Aun no hay una conversacion</div>";
		  }
			
			
		  if(!empty($items)){
		      echo json_encode($mensaje);
		  }
	} catch (Exception $ex){
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = 'Error al ejecutar sentencia';
		echo json_encode($mensaje);
	}
} catch (Exception $ex) {
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexi�n a la base de datos';
	echo json_encode($mensaje);
}
?>