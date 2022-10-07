<?php
/**
 * Created by IntelliJ IDEA.
 * User: antonio
 * Date: 05/02/18
 * Time: 10:12
 */
require_once 'ControladorLmr.php';
require_once '../../../clases/Conexion.php';
require_once '../Modelo/Lmr.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
    $conexion = new Conexion();
    $controladorLmr=new ControladorLmr($conexion);
    $ic_lmr_id = isset($_POST['ic_lmr_id']) ? $_POST['ic_lmr_id'] : null;
    $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : null;
    $descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : null;

    $lmr = new Lmr($ic_lmr_id,$nombre,$descripcion);

    $controladorLmr->saveAndUpdateLmr($lmr);
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