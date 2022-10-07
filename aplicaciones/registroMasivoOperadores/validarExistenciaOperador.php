<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';
$mensaje['razonSocial'] = '';
$mensaje['nombreRepresentante'] = '';
$mensaje['apellidoRepresentante'] = '';

try {
    
    $conexion = new Conexion();
    $cro = new ControladorRegistroOperador();
    
    $identificadorOperador = $_POST['numero'];
    
    try{
        
        $conexion->ejecutarConsulta("begin");
        
        $qBuscarOperador = $cro->buscarOperador($conexion, $identificadorOperador);
        
        if(pg_num_rows($qBuscarOperador) > 0){
            
            $buscarOperador = pg_fetch_assoc($qBuscarOperador);
            
            $razonSocial = ($buscarOperador['razon_social'] == "") ? "" : $buscarOperador['razon_social'];
            $nombreOperador = $buscarOperador['nombre_representante'];
            $apellidoOperador = $buscarOperador['apellido_representante'];
            
            $mensaje['estado'] = 'exito';
            $mensaje['razonSocial'] = $razonSocial;
            $mensaje['nombreRepresentante'] = $nombreOperador;
            $mensaje['apellidoRepresentante'] = $apellidoOperador;
                        
        }else{
            
            $mensaje['estado'] = 'error';
            $mensaje['mensaje'] = 'El código temporal ingresado es incorrecto';
            
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