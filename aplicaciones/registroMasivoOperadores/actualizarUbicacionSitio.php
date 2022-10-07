<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
    
    $conexion = new Conexion();
    $cro = new ControladorRegistroOperador();
    
    $idSitio = $_POST['idSitio'];
    $supervisarUbicacion = $_POST['supervisarUbicacion'];
    $latitudSitio = $_POST['latitudSitio'];
    $longitudSitio = $_POST['longitudSitio'];
    $observacionSitio = $_POST['observacionSitio'];
    
    try {
        
        $conexion->ejecutarConsulta("begin;");
        
        $cro->actualizarCoordenadasSitio($conexion, $idSitio, $latitudSitio, $longitudSitio, $supervisarUbicacion, $observacionSitio);
        
        $mensaje['estado'] = 'exito';
        $mensaje['mensaje'] = 'Las coordenadas han sido actualizadas.';
        
        $conexion->ejecutarConsulta("commit;");
        
        
    } catch (Exception $ex) {
        $conexion->ejecutarConsulta("rollback;");
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
