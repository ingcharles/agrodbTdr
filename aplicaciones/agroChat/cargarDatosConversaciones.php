<?php
session_start();

require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorChat.php';


$mensaje = array();
$mensaje['estado'] = 'exito';
$mensaje['mensaje'] = 'Ha ocurrido un errorsss!';

$incremento = $_POST['incremento'];
$datoIncremento = $_POST['datoIncremento'];
$identificadorUsuario = $_POST['identificadorUsuario'];
$identificadorContacto = $_POST['identificadorContacto'];
$tipo= $_POST['tipo'];

$conexion = new Conexion();
$cc = new ControladorChat();

try{
		
	try {
		
		$items = array();
		if($tipo=="#vc_"){
		    $mensajesUsuario=$cc->mostrarConversaciones($conexion, $identificadorUsuario, $identificadorContacto, $incremento, $datoIncremento);
		    $noMensajesUsuario=pg_num_rows($mensajesUsuario);
		    
		    if($noMensajesUsuario>0){
		        
		        $filas=$cc->obtenerEmojis($conexion);
		        $arrayEmoji = array();
		        while($emojis=pg_fetch_assoc($filas)){
		            $contenido = '<img class="emoji" name="::~'.$emojis['nombre'].'" src="'.$emojis['ruta'].'">';
		            $nombres = "::~".$emojis['nombre'];
		            array_push($arrayEmoji[$nombres]=$contenido);
		        }
		        
		        while($mensajeUsuario=pg_fetch_assoc($mensajesUsuario)){
		            
		            if($mensajeUsuario['identificador_usuario']==$identificadorUsuario){
		                $clase='msgUsuario';
		            } else{
		                $clase='msgContacto';
		            }
		           
		            $cadena = htmlspecialchars_decode($mensajeUsuario['mensaje'],ENT_NOQUOTES);		         
		            
		            $contenido = '<tr style="width:205px;">
							        <td style="width:205px;">
									<div class="'.$clase.'">'.strtr($cadena, $arrayEmoji).'</div>
									</td>
								  </tr>'
									    ;
									    
				    $items[] = array(contenido => $contenido, fecha=>$mensajeUsuario['fecha'], usuario=>$mensajeUsuario['identificador_usuario'], clase=>$clase);
		        }
		        
		        $mensaje['estado'] = 'exito';
		        $mensaje['mensaje'] = $items;
		    }else{
		        $mensaje['estado'] = 'vacio';
		        $mensaje['mensaje'] = "<div class='mensajeAviso'>No hay mensajes</div>";
		    }
		} else{
		    $mensajesUsuario=$cc->mostrarConversacionesGrupos($conexion, $identificadorUsuario, $identificadorContacto, $incremento, $datoIncremento);
		    $noMensajesUsuario=pg_num_rows($mensajesUsuario);
		    
		    if($noMensajesUsuario>0){
		        
		        $filas=$cc->obtenerEmojis($conexion);
		        $arrayEmoji = array();
		        while($emojis=pg_fetch_assoc($filas)){
		            $contenido = '<img class="emoji" name="::~'.$emojis['nombre'].'" src="'.$emojis['ruta'].'">';
		            $nombres = "::~".$emojis['nombre'];
		            array_push($arrayEmoji[$nombres]=$contenido);
		        }
		        
		        while($mensajeUsuario=pg_fetch_assoc($mensajesUsuario)){
		            
		            if($mensajeUsuario['identificador_usuario']==$identificadorUsuario){
		                $clase='msgUsuario';
		                $nombre='';
		            } else{
		                $clase='msgContacto';
		                $nombre='<div class="nombreContactoGrupo">'.$mensajeUsuario['nombre'].'</div>';
		            }
		            
		            $cadena = $mensajeUsuario['mensaje'];		            
		            		            
		            $contenido = '<tr style="width:205px;">
							        <td style="width:205px;">                                    
									<div class="'.$clase.'">
                                    '.$nombre
                                    .strtr($cadena, $arrayEmoji).'</div>
									</td>
								  </tr>'
									    ;
									    
				    $items[] = array(contenido => $contenido, fecha=>$mensajeUsuario['fecha'], usuario=>$mensajeUsuario['identificador_usuario'], clase=>$clase);
		        }
		        
		        $mensaje['estado'] = 'exito';
		        $mensaje['mensaje'] = $items;
		    }else{
		        $mensaje['estado'] = 'vacio';
		        $mensaje['mensaje'] = "<div class='mensajeAviso borde-5 letraNegrita '>No hay mensajes</div>";
		    }
		}	
		echo json_encode($mensaje);
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