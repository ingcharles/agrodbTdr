<?php
session_start();

require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorChat.php';


$mensaje = array();
$mensaje['estado'] = 'exito';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

$grupo = htmlentities($_POST['grupo'],  ENT_NOQUOTES);
$identificador = htmlspecialchars ($_POST['usuario'],ENT_NOQUOTES,'UTF-8');
$conexion = new Conexion();
$cc = new ControladorChat();

try{
    
    try {
        
        $items = array();
        
        $conexion->ejecutarConsulta("begin;");
        $result= $cc->salirGrupo($conexion,$identificador, $grupo);
        $mensaje['estado'] = 'exito';
        $mensaje['mensaje'] = 'Grupo eliminado con éxito.';
      
        
        $conexion->ejecutarConsulta("commit;");        
        
    } catch (Exception $ex){
        $conexion->ejecutarConsulta("rollback;");
        $mensaje['estado'] = $conexion->mensajeError;
        $mensaje['mensaje'] = $ex->getMessage();
        
    } finally {
        $conexion->desconectar();
    }
} catch (Exception $ex) {
    $mensaje['estado'] = $conexion->mensajeError;
    $mensaje['mensaje'] = $ex->getMessage();
    echo json_encode($mensaje);
} finally {
    echo json_encode($mensaje);
}
?>