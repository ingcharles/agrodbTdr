<?php
session_start();

require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorChat.php';


$mensaje = array();
$mensaje['estado'] = 'exito';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

$data  = json_decode($_POST['data'], true);

$usuario= $_POST['usuario'];
$contacto= $_POST['grupo'];

$conexion = new Conexion();
$cc = new ControladorChat();

try{
		
	try {
		
		$items = array();		

		$fila = $cc->listarMiembrosGrupoCreado($conexion, $usuario, $contacto);
			
		if($fila){

	        $contenido="";
			while($miembro=pg_fetch_assoc($fila)){
			    if($miembro['fotografia']!=""){
			        $foto=$miembro['fotografia'];
			    }else{
			        $foto='aplicaciones/agroChat/img/user2.png';
			    }
				    $contenido = '<li class="itemLista" id="lsgrpsu_'.$miembro['identificador'].'" >
    						      <div class="contenedorContactoMiembro" id="ctgrpsu_'.$miembro['identificador'].'">
            						<div class="fotoUsuarioChatNuevo"><img src="'.$foto.'" class="imgUsuarioChatNuevo"></div>
            						<div class="contenedorUsuarioDatosNuevo">
                						<div class="nombreUsuarioMiembroChat">'.$miembro['nombres'].'</div>                						
            						</div>
        						  </div>
    					          </li>';	
				    
				    $items[] = array(contenido => $contenido, contacto=>$miembro['identificador'], nombres=>$miembro['nombres'], foto =>$foto);	
				}
				
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = $items;
		  }else{
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = 'Error al obtner contactos';
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