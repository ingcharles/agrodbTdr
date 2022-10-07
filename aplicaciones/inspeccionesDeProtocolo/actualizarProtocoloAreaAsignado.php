<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorProtocolos.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
    
    $idProtocoloAreaAsignado = htmlspecialchars ($_POST['idProtocoloAreaAsignado'],ENT_NOQUOTES,'UTF-8');
    $estadoProtocoloAsignado = htmlspecialchars ($_POST['estadoProtocoloAsignado'],ENT_NOQUOTES,'UTF-8');
        
    try {

        $conexion = new Conexion();
        $cp = new ControladorProtocolos();
                
        $cp->actualizarProtocoloAreaAsignado($conexion, $idProtocoloAreaAsignado, $estadoProtocoloAsignado);
        
        $mensaje['estado'] = 'exito';
        $mensaje['mensaje'] = 'Los datos fueron actualizados';
        
        $conexion->desconectar();
        echo json_encode($mensaje);
    } catch (Exception $ex){
        pg_close($conexion);
        $mensaje['estado'] = 'error';
        $mensaje['mensaje'] = "Error al ejecutar sentencia";
        echo json_encode($mensaje);
    }
} catch (Exception $ex) {
    $mensaje['estado'] = 'error';
    $mensaje['mensaje'] = 'Error de conexión a la base de datos';
    echo json_encode($mensaje);
}
?>