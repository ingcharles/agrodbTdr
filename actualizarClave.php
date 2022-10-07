<?php
session_start();
require_once 'clases/Conexion.php';
require_once 'clases/ControladorUsuarios.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try {
    
    $conexion = new Conexion();
    $cu = new ControladorUsuarios();
        
    $codigoTemporal = md5($_POST['codigoTemporal']);
    $claveNueva1 = pg_escape_string($_POST['claveNueva1']);
    $claveNueva2 = pg_escape_string($_POST['claveNueva2']);
    
    try{
        
        $conexion->ejecutarConsulta("begin");
        
        if($claveNueva1 == $claveNueva2){
        
            $resultado = $cu->verificarUsuario($conexion, $_SESSION['usuario']);
            $fila = pg_fetch_assoc($resultado);
            
            if($codigoTemporal == $fila['codigo_temporal']){
                
                $claveNueva1 = md5($claveNueva1);
                $cu->actualizarUsuario($conexion, $_SESSION['usuario'], $fila['nombre_usuario'], "'".$claveNueva1."'");
                $mensaje['estado'] = 'exito';
                $mensaje['mensaje'] = 'El cambio se efectuo correctamente.';
                
            }else{
                
                $mensaje['estado'] = 'error';
                $mensaje['mensaje'] = 'El código temporal ingresado es incorrecto';
                
            }
            
        }
        
        $conexion->ejecutarConsulta("commit");
        
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