<?php
session_start();

require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorLotes.php';
require_once '../../clases/ControladorAdministrarCaracteristicas.php';
require_once 'mailEtiquetas.php';


$mensaje = array();
$mensaje['estado'] = 'exito';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

$producto = $_POST['producto'];
$nProducto = $_POST['nPorducto'];

$conexion = new Conexion();
$cl = new ControladorLotes();
$cc = new ControladorAdministrarCaracteristicas();
$ml = new mailEtiquetas();

try{
    
    try {
        
        $items = array();
        
        $plantillas=$cl->obtenerPlantillasXidProducto($conexion, $producto,1);
        
        $noPlantillas=pg_num_rows($plantillas);
        
        if($noPlantillas>0){
            
            if($noPlantillas>1){
            
                $option="";
                while($plantillaProducto=pg_fetch_assoc($plantillas)){
                    $option.='<option value="'.$plantillaProducto['id_plantilla'].'">'.$plantillaProducto['nombre'].'</option>';
                }
                    
                $contenido='<div data-linea="4" id="divTamanioEtiqueta" >
                				<label for="tamanioEtiqueta" >Tamaño hoja:</label>
                				<select id="tamanioEtiqueta" name="tamanioEtiqueta" >'.
                				$option.'                					
                				</select>				
                			</div>';    
            } else{
                $plantillaProducto=pg_fetch_assoc($plantillas);
                $contenido='<div data-linea="4" id="divTamanioEtiqueta" >                				
                				<input type="hidden" id="tamanioEtiqueta" name="tamanioEtiqueta" value="'.$plantillaProducto['id_plantilla'].'">
                			</div>';
            }
										    
			$items[] = array(contenido => $contenido, cantidad=>$noPlantillas);										    
            
            
            $mensaje['estado'] = 'exito';
            $mensaje['mensaje'] = $items;
        }else{
            
            $valor=$cc->obtenerModulo($conexion, 'PRG_ADMIN_ETI');
            $modulo = pg_fetch_assoc($valor);            
            
            $ml->generarMail($modulo['id_aplicacion'],$nProducto);
            
            $mensaje['estado'] = 'vacio';            
            $mensaje['mensaje'] = "No existen plantillas de etiquetas asignadas";
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