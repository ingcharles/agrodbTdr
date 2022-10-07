<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try {
    
    $conexion = new Conexion();
    $cc = new ControladorCatalogos();
    $arrayDetalleCatastro = json_decode($_POST['array_detalle_catastro'], true);
    $itemSeleccionado = json_decode($_POST['codigo_seleccionado'], true);

    try{
       
        $arrayIdentificadores = "ARRAY['".implode("', '", $arrayDetalleCatastro[0][$itemSeleccionado]['arrayIdentificadores'])."']";
     
        $cantidadAreteUtilizado = pg_fetch_result($cc->buscarRangoSerieArete($conexion, 6, $arrayIdentificadores, "utilizado"),0,'cantidad_arete');
        
        if ($cantidadAreteUtilizado > 0) {
            $mensaje ['mensaje'] =  'Los identificadores son incorrectos o están utilizados.';
                    
        }else{

            $cantidadAreteCreado = pg_fetch_result($cc->buscarRangoSerieArete($conexion, 6, $arrayIdentificadores, "creado"),0,'cantidad_arete');
        
            if ($cantidadAreteCreado == count($arrayDetalleCatastro[0][$itemSeleccionado]['arrayIdentificadores'])) {
            
                $mensaje['estado'] = 'exito';
                $mensaje['mensaje'] = 'Todos los identificadores estan habilitados para ser utilizados';
                               
            }else{
                $mensaje ['mensaje'] =  'Los identificadores son incorrectos o están utilizados.';
            }
            
        }
        
    } catch (Exception $ex) {
        $mensaje['mensaje'] = $ex->getMessage();
        $mensaje['error'] = $conexion->mensajeError;
    } finally {
        $conexion->desconectar();
    }
} catch (Exception $ex) {
    $mensaje['mensaje'] = $ex->getMessage();
    $mensaje['error'] = $conexion->mensajeError;
} finally {
    echo json_encode($mensaje);
}



?>