<?php
session_start();

require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorChat.php';


$mensaje = array();
$mensaje['estado'] = 'exito';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

$data  = json_decode($_POST['data'], true);
$grupo  = json_decode($_POST['grupo'], true);
$usuario = $_POST['usuario'];

$conexion = new Conexion();
$cc = new ControladorChat();

try{
		
	try {
		
		$items = array();		
		
		$busqueda="";
		foreach ($data as $key1 => $val1){
			
			$fecha = $val1['fecha'];
			$fecha  = $fecha!="" ? "'" . $fecha. "'" : "'0001-01-01 00:00:00'";
			
			$busqueda.="(mc.identificador_usuario='".$val1['identificadorContacto'] ."' and mc.contacto= '".$val1['identificadorUsuario'] ."' and mc.fecha > ".$fecha.") or ";
						
		}
		
		$trim = rtrim ($busqueda,"or ");
		
		foreach ($grupo as $key1 => $val1){		    
		    $fecha = $val1['fecha'];
		    $fecha  = $fecha!="" ? "'" . $fecha. "'" : "'0001-01-01 00:00:00'";		    
		    $busqueda2.="((id_grupo=".$val1['grupo'] .") and mc.identificador_usuario !='".$usuario."'and mc.fecha > ".$fecha.") or ";		    
		}	
		
		if($busqueda2!=''){
		    $trim=$trim.'or '.$busqueda2;
		}
		
		if($data){
		  $mensajesUsuario=$cc->obtenerMenajesTodos($conexion, rtrim ($trim, 'or ' ));
		}
		$noMensajesUsuario=pg_num_rows($mensajesUsuario);
			
			if($noMensajesUsuario>0){
				while($mensajeUsuario=pg_fetch_assoc($mensajesUsuario)){
					
				    if($mensajeUsuario['id_grupo']!=null){					
				        $nombre='<div class="nombreContactoGrupo">'.$mensajeUsuario['nombre'].'</div>';
					}
					
					$filas=$cc->obtenerEmojis($conexion);
					$arrayEmoji = array();
					while($emojis=pg_fetch_assoc($filas)){
					    $contenido = '<img class="emoji" name="::~'.$emojis['nombre'].'" src="'.$emojis['ruta'].'">';
					    $nombres = "::~".$emojis['nombre'];
					    array_push($arrayEmoji[$nombres]=$contenido);
					}
					
					$cadena = htmlspecialchars_decode($mensajeUsuario['mensaje']);
					
					$contenido = '<tr style="width:205px;">
									<td style="width:205px;">
									<div class="msgContacto">
                                    '.$nombre
                                    .strtr($cadena, $arrayEmoji).'</div>
									</td>
								  </tr>'
								;
					
								$items[] = array(contenido => $contenido, fecha=>$mensajeUsuario['fecha'], contacto=>$mensajeUsuario['usuario'], grupo=>$mensajeUsuario['id_grupo']);								
				}
				
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = $items;
		  }else{
				$mensaje['estado'] = 'vacio';
				$mensaje['mensaje'] = 'no hay mensajes nuevos';				
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
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}
?>