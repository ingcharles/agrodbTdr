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
$tipo= $_POST['tipo'];

$conexion = new Conexion();
$cc = new ControladorChat();

try{
		
	try {
		
		$items = array();		
		
		if($tipo=='todos'){
		
    		$fila=$cc->grupoPerteneciente($conexion,  $_SESSION ['usuario']);
    				
    		$grupo=pg_num_rows($fila);
    			
    		if($fila){
    				
    			while($grupo=pg_fetch_assoc($fila)){					
    				
    				$foto='aplicaciones/agroChat/img/user2.png';
    				
    				$cadenaNombre = str_replace(' ', '', $grupo['nombre_grupo']);
    				$contenido = '
    								<li id="'.$cadenaNombre.'">
    								<div class="contenedorContactoChat" id="ctcg_' . $grupo ['id_grupo']. '" name="'.$grupo ['nombre_grupo'].'" onClick="abrirChat(id,'."'grp'".')">
    									<div class="fotoUsuarioChat"><img src="' . $foto. '" class="imgUsuarioChat"> </div>
    									<div class="nombreUsuarioChat">' . $grupo ['nombre_grupo'] . '</div>
    									<span class="fechaMensajes" style="display:none;">'.$grupo ['fecha'].'</span>
    									<span class="fechaUltimoMensaje"  style="display:none;">'.$grupo ['fecha_mensaje'].'</span>
    								</div>
    								</li>
    							';
    					
    							
    				$items[] = array(contenido => $contenido, contacto=>$grupo['id_grupo'], nombre=>$grupo ['nombre_grupo']);	
    			}
    				
    				$mensaje['estado'] = 'exito';
    				$mensaje['mensaje'] = $items;
    		  }else{
    				$mensaje['estado'] = 'error';
    				$mensaje['mensaje'] = 'Error al obtner solicitudes';
    		  }
		} else{
		    $fila=$cc->grupoPerteneciente($conexion,  $_SESSION ['usuario'], 'si');
		    
		    $grupo=pg_num_rows($fila);
		    
		    if($fila){
		        
		        while($grupo=pg_fetch_assoc($fila)){
		            
		            $foto='aplicaciones/agroChat/img/user2.png';
		            
		            $cadenaNombre = str_replace(' ', '', $grupo['nombre_grupo']);
		            
		            $contenido = '<li class="itemLista" id="lsgrpm_'.$grupo['id_grupo'].'" onmouseleave="cancelarSalirGrupoAutomatico('."'".'lsgrpm_'.$grupo['id_grupo']."'".','."'".$grupo['nombre_grupo']."'".')">
        						<div class="contenedorContactoNuevo" id="ctgrpm_'.$grupo['id_grupo'].'" onclick="verMiembrosGrupo('."'".'ctgrpm_'.$grupo['id_grupo']."'".')">
            						<div class="fotoUsuarioChatNuevo">
            							<img src=" aplicaciones/agroChat/img/user2.png" class="imgUsuarioChatNuevo">
            						</div>
            						<div class="contenedorUsuarioDatosNuevo">
                						<div class="nombreGrupos">'.$grupo['nombre_grupo'].'</div>
                						<div class="contenedorEnviarSolicitud">
                							<a class="accionesGrupoChatExistente" id="grpm_'.$grupo['id_grupo'].'" onclick="prepararSalirGrupo('."'".'grpm_'.$grupo['id_grupo']."'".')">Salir del grupo</a>
            							</div>
            						</div>
        						</div>
        					</li>';
		            
		            
		            $items[] = array(contenido => $contenido, contacto=>$grupo['id_grupo']);
		        }
		        
		        $mensaje['estado'] = 'exito';
		        $mensaje['mensaje'] = $items;
		    }else{
		        $mensaje['estado'] = 'error';
		        $mensaje['mensaje'] = 'Error al obtner solicitudes';
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