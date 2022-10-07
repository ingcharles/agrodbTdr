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
		
		$fila=$cc->buscarUsuarios($conexion, $contacto, $usuario);
				
		$usuarios=pg_num_rows($fila);
			
		if($usuarios>0){			
				
				while($contacto=pg_fetch_assoc($fila)){
					
					if($contacto['fotografia']!='')
						$foto=$contacto['fotografia'];
					else
						$foto='aplicaciones/agroChat/img/user2.png';
					
					$contenido='<li>
									<div class="contenedorContactoNuevo" id="ctn_'.$contacto['identificador'].'">
										<div class="fotoUsuarioChat"><img src=" '.$foto.' " class="imgUsuarioChat"> </div>
										<div class="nombreUsuarioNuevo">'.$contacto['nombres'].'</div>
									</div>
									</li>';
							
					$items[] = array(contenido => $contenido, relacion=>$contacto['relacion']);						
					
				}
							
				
				
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = $items;
		  }else{
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = 'No existen coincidencias';
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
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}
?>