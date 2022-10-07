<?php
session_start();

require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorChat.php';

set_time_limit(600);
ini_set('memory_limit', '256M');

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
		
		$fila=$cc->crearListaUsuarios($conexion, $contacto, $usuario);
		
		$usuarios=pg_num_rows($fila);
		
		if($usuarios>0){
			
			while($contacto=pg_fetch_assoc($fila)){		
				
				if($contacto['fotografia']!=""){
					
					miniatura($contacto['fotografia'], $_SERVER['DOCUMENT_ROOT']."/agrodb/aplicaciones/agroChat/miniatura/".$contacto['identificador'].".jpg", "42");
					
					$foto="aplicaciones/agroChat/miniatura/".$contacto['identificador'].".jpg";
					
					
				}else{
					$foto='aplicaciones/agroChat/img/user2.png';
				}
				
				$items[] = array(identificador => $contacto['identificador'], nombres=>$contacto['nombres'], direccion=>$contacto['direccion'],
						  fotografia=>$foto, relacion=>'', recepcion=>'', estado_solicitud=>'');
				
			}
			
			$json_string = json_encode($items);
			echo $json_string;
			
			$file = 'usuarios.json';
			file_put_contents($file, $json_string);
			
			
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $items;
		}else{
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = 'No existen coincidencias';
			//$contenido.="<div class='mensajeAviso borde-5 letraNegrita '>Aun no hay una conversacion</div>";
		}
		
		
		//echo json_encode($mensaje);	
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



function miniatura($src, $destino, $tamanioDeseado) {	
	
	$rutaImagen = imagecreatefromjpeg($_SERVER['DOCUMENT_ROOT']."/agrodb/".$src);
	$anchura = imagesx($rutaImagen);
	$altura = imagesy($rutaImagen);	
	
	$alturaNueva = floor($altura * ($tamanioDeseado / $anchura));	
	$imgTemporal = imagecreatetruecolor($tamanioDeseado, $alturaNueva);		
	imagecopyresampled($imgTemporal, $rutaImagen, 0, 0, 0, 0, $tamanioDeseado, $alturaNueva, $anchura, $altura);		
	imagejpeg($imgTemporal, $destino);
}
?>