<?php
/**
 * Created by IntelliJ IDEA.
 * User: antonio
 * Date: 05/02/18
 * Time: 10:12
 */
require_once 'ControladorInsumo.php';
require_once '../../../clases/Conexion.php';
require_once '../Modelo/Insumo.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

//Llama a metodo Guardar de Caso
try{
    $conexion = new Conexion();
    $controladorInsumo=new ControladorInsumo($conexion);
    $ic_insumo_id = isset($_POST['ic_insumo_id']) ? $_POST['ic_insumo_id'] : null;
    $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : null;
    $descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : null;
    $programa_id = isset($_POST['programa_id']) ? $_POST['programa_id'] : 0;

    $insumo = new Insumo($ic_insumo_id,$nombre,$descripcion,$programa_id);

    $controladorInsumo->saveAndUpdateInsumo($insumo);
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