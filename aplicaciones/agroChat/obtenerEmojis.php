<?php
session_start();

require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorChat.php';


$mensaje = array();
$mensaje['estado'] = 'exito';
$mensaje['mensaje'] = 'Ha ocurrido un error!';
$tipo = htmlspecialchars($_POST['tipo'],  ENT_NOQUOTES);

$conexion = new Conexion();
$cc = new ControladorChat();

try{
		
	try {
		
		$items = array();		
		
		$filas=$cc->obtenerEmojis($conexion);
		
		while($emojis=pg_fetch_assoc($filas)){
		    if($tipo=='lista'){
		        $contenido = '<span class="emojiVista" name="::~'.$emojis['nombre'].'" onclick="colocarEmoji(this)" style="background-image: url('."'".$emojis['ruta']."'".');"/>';
		        $items[] = array(contenido => $contenido);
		    } else{
		        $contenido = '<img class="emoji" name="::~'.$emojis['nombre'].'" src="'.$emojis['ruta'].'">';
		        $nombres = "::~".$emojis['nombre'];  
		        $items[] = array(contenido => $contenido, nombres => $nombres);	
		    }
		}	
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $items;				
			
		echo json_encode($mensaje);
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