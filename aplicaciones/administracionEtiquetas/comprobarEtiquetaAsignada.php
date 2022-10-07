<?php
session_start();

require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorLotes.php';


$mensaje = array();
$mensaje['estado'] = 'exito';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

$producto = $_POST['producto'];

$conexion = new Conexion();
$cl = new ControladorLotes();

try{
    
    try {
        
        $items = array();
        
        $plantillas=$cl->obtenerPlantillasXidProducto($conexion, $producto, 1);
        
        $noPlantillas=pg_num_rows($plantillas);
        
        if($noPlantillas>0){
            $option="";            
            while($plantillaProducto=pg_fetch_assoc($plantillas)){
                
                $res=$cl->obtenerPlantillasRutaXcodigoYtipo($conexion,$plantillaProducto['plantilla'], 1);
                $fila=pg_fetch_assoc($res);
               
                
                $option='<option value="'.$fila['codigo'].'" data-ruta="'.$fila['ruta'].'" >'.$fila['nombre'].'</option>';
                $plantila='<div style="text-align:center;width:100%;padding-bottom: 10px;"><img alt="plantilla1" src="'.$fila['ruta'].'" width="250" height="150"></div>';
                
                
                $items[] = array(contenido => $option, plantilla => $plantila);
            }			
            
            $mensaje['estado'] = 'exito';
            $mensaje['mensaje'] = $items;
        }else{
            
            
            $res=$cl->obtenerPlantillasRuta($conexion, 1);
            $option.= '<option value="">Seleccione....</option>';
            while($fila=pg_fetch_assoc($res)){
                $option.= '<option value="'.$fila['codigo'].'" data-ruta="'.$fila['ruta'].'" >'.$fila['nombre'].'</option>';
            }       
            
            $items[] = array(contenido => $option);
            
            $mensaje['estado'] = 'vacio';
            $mensaje['mensaje'] = $items;
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