<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';

$idDatoColmenar = htmlspecialchars($_POST['idDatoColmenar'], ENT_NOQUOTES, 'UTF-8');
$idSitio = htmlspecialchars($_POST['idSitio'], ENT_NOQUOTES, 'UTF-8');
$latitud = htmlspecialchars($_POST['latitud'], ENT_NOQUOTES, 'UTF-8');
$longitud = htmlspecialchars($_POST['longitud'], ENT_NOQUOTES, 'UTF-8');
$zona = htmlspecialchars($_POST['zona'], ENT_NOQUOTES, 'UTF-8');
$duenioSitio = htmlspecialchars($_POST['duenioSitio'], ENT_NOQUOTES, 'UTF-8');
$numeroColmenares = htmlspecialchars($_POST['numeroColmenares'], ENT_NOQUOTES, 'UTF-8');
$numeroPromedioColmenas = htmlspecialchars($_POST['numeroPromedioColmenas'], ENT_NOQUOTES, 'UTF-8');

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
    
    $conexion = new Conexion();
    $cro = new ControladorRegistroOperador();
    
    try{        
            
            $conexion->ejecutarConsulta("begin;");
            
            $cro->actualizarRegistroInformacionColmenarPorIdColmenar($conexion, $idDatoColmenar, $duenioSitio, $numeroColmenares, $numeroPromedioColmenas);
            $cro->actualizarCoordenadasSitioPorIdSitio($conexion, $idSitio, $latitud, $longitud, $zona);
                        
            $conexion->ejecutarConsulta("commit;");
            
            $mensaje['estado'] = 'exito';
            $mensaje['mensaje'] = 'Los datos han sido actualizados';      
        
    }catch (Exception $ex){
        $conexion->ejecutarConsulta("rollback;");
        $mensaje['mensaje'] = $ex->getMessage();
        $mensaje['error'] = $conexion->mensajeError;
        
    }finally {
        $conexion->desconectar();
    }
    
}catch (Exception $ex){
    $mensaje['mensaje'] = $ex->getMessage();
    $mensaje['error'] = $conexion->mensajeError;
}finally {
    echo json_encode($mensaje);
}

?>

