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
		
		$fila= $cc->listarContactos ( $conexion, $usuario);
			
		if($fila){
				
			while ( $contacto = pg_fetch_assoc ( $fila) ) {
				if($contacto['fotografia']!=''){
					$foto=$contacto['fotografia'];
				}else{
					$foto='aplicaciones/agroChat/img/user2.png';
				}
				
				$cadenaNombre = str_replace(' ', '', $contacto['nombres']);
					$contenido='
								<li id="'.$cadenaNombre. $contacto['contacto'].'">
								<div class="contenedorContactoChat" id="ctg_' . $contacto['contacto']. '" name="'.$contacto['nombres'].'" onClick="agregarContactosGrupoChat(id,'."'".$foto."',"."'".$contacto['nombres']."'".')">
									<div class="fotoUsuarioChat"><img src="' . $foto. '" class="imgUsuarioChat"> </div>
									<div class="nombreUsuarioListaGrupoChat">' . $contacto['nombres'] . '</div>										
									<span class="fechaMensajes" id="'.$contacto['fecha'].'" style="display:none;">'.$contacto['fecha'].'</span>                                    
								</div>
								</li>
							';
		
					$items[] = array(contenido => $contenido, contacto=>$contacto['contacto'], nombres=>$contacto['nombres'], foto =>$foto);	
					
				}
				
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = $items;
		  }else{
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = 'Erro al obtner contactos';
				//$contenido.="<div class='mensajeAviso borde-5 letraNegrita '>Aun no hay una conversacion</div>";
		  }
			
			
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