<?php

session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorMovilizacionProductos.php';
require_once '../../clases/ControladorCatastroProducto.php';
require_once '../../clases/ControladorReportes.php';


try{
    
    $mensaje = array();
    $mensaje['estado'] = 'error';
    $mensaje['mensaje'] = 'Ha ocurrido un error!';    

    try{
        
        $conexion = new Conexion();
        $cp = new ControladorCatastroProducto();
        set_time_limit(2000);
    
        $usuarioResponsable = $_SESSION['usuario'];
        
        $idCatastro = $_POST['idCatastro'];
        $identificadorProducto = $_POST['identificadorProductoActivar'];
        $motivoActivacion = $_POST['motivoActivacion'];
        $observacionActivacion = $_POST['observacionActivacion'];
        $estadoDetalleCatastro = "";
        
        $conexion->ejecutarConsulta("begin");        
        
        if($motivoActivacion == 'operacionComerciante'){            
            $estadoDetalleCatastro = 'activo';
        }else{
            $estadoDetalleCatastro = 'temporal';
        }
     
        $cp->cambiarEstadoIdentificadorProductoDetalleCatastroXIdCatastro($conexion, $identificadorProducto, 'temporal');
        $cp->guardarLogActivarProducto($conexion, 'null', 'null', 'null', 'null', $identificadorProducto, $usuarioResponsable, 'temporal', $motivoActivacion, $observacionActivacion);
            
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
