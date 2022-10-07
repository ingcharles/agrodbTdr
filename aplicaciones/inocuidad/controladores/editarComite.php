<?php
/**
 * Created by IntelliJ IDEA.
 * User: antonio
 * Date: 23/02/18
 * Time: 9:53
 */

session_start();
require_once 'ControladorComite.php';
require_once 'ControladorEvaluacion.php';
require_once '../../../clases/Conexion.php';
require_once '../Modelo/Comite.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
    $conexion = new Conexion();
    $controladorComite=new ControladorComite();
    $ic_evaluacion_comite_id = isset($_POST['ic_evaluacion_comite_id']) ? $_POST['ic_evaluacion_comite_id'] : 0;
    $ic_evaluacion_analisis_id = isset($_POST['ic_evaluacion_analisis_id']) ? $_POST['ic_evaluacion_analisis_id'] : 0;
    $ic_analisis_muestra_id = isset($_POST['ic_analisis_muestra_id']) ? $_POST['ic_analisis_muestra_id'] : 0;
    $observaciones = isset($_POST['observaciones']) ? $_POST['observaciones'] : null;

    $comite = new Comite($ic_evaluacion_comite_id,$ic_evaluacion_analisis_id,$observaciones,'F');

    $controladorComite->saveAndUpdateComite($comite);

    $controladorAnalisis = new ControladorEvaluacion();
    $controladorAnalisis->activarEvaluacion($ic_evaluacion_analisis_id);

    $auditoria = new ControladorAuditoria();
    $auditoria->auditarRegistroUpdate($_SESSION['usuario'],$comite);

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