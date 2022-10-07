<?php

session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorMovilizacionProductos.php';

try{
    
    $mensaje = array();
    $mensaje['estado'] = 'error';
    $mensaje['mensaje'] = 'Ha ocurrido un error!';
    
    try{
        
        $conexion = new Conexion();
        $cmp = new ControladorMovilizacionProductos();
        set_time_limit(2000);
    
        $usuarioResponsable = "'".$_SESSION['usuario']."'";
        
        $identificadorProductoActivar = "'".$_POST['identificadorProductoActivar']."'";
        $identificadorOperadorOrigen = "'".$_POST['identificacionOperadorOrigen']."'";
        $identificadorOperadorDestino = "'".$_POST['identificacionOperadorDestino']."'";
        $idSitioOrigen = $_POST['idSitioOrigen'];
        $idSitioDestino = $_POST['idSitioDestino'];
        $idAreaDestino = $_POST['idAreaDestino'];
                
        $tipoactivacion = "'".'cambioDuenio'."'";
        $motivoactivacion = "'".''."'";
        $observacionactivacion = "'".'Activación por cambio de dueño'."'";                
        
        $conexion->ejecutarConsulta("begin");
    
        $sentencia = "SELECT g_catastro.proceso_cambioduenio($identificadorProductoActivar, $idAreaDestino, $identificadorOperadorOrigen, $identificadorOperadorDestino, $idSitioOrigen, $idSitioDestino, $usuarioResponsable, $tipoactivacion, $motivoactivacion, $observacionactivacion);";
                
        $resultadoFuncion = pg_fetch_assoc($cmp -> guardarProcesoCambioDuenio($conexion, $sentencia));//Esta es la función que realiza el proceso de movilizacion    
                    
            $conexion->ejecutarConsulta("commit;");
            $mensaje['estado'] = 'exito';
            $mensaje['mensaje'] = 'Los datos han sido actualizados satisfactoriamente';        
 

    } catch (Exception $ex) {
        $conexion->ejecutarConsulta("rollback;");
        $mensaje['mensaje'] = $ex->getMessage();
        $mensaje['error'] = $conexion->mensajeError;
        $err = preg_replace( "/\r|\n/", " ", $mensaje['error']);
        $conexion->ejecutarLogsTryCatch($ex.'---ERROR:'.$err);
    } finally {
        $conexion->desconectar();
    }
    
} catch (Exception $ex) {
    $mensaje['mensaje'] = $ex->getMessage();
    $mensaje['error'] = $conexion->mensajeError;
    $err = preg_replace( "/\r|\n/", " ", $mensaje['error']);
    $conexion->ejecutarLogsTryCatch($ex.'---ERROR:'.$err);
} finally {
    echo json_encode($mensaje);
}

?>
