<?php
/**
 * Created by IntelliJ IDEA.
 * User: antonio
 * Date: 22/02/18
 * Time: 22:55
 */

session_start();
require_once 'ControladorEvaluacion.php';
require_once '../../../clases/Conexion.php';
require_once '../Modelo/Evaluacion.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
    $conexion = new Conexion();
    $controladorEvaluacion=new ControladorEvaluacion();
    $ic_evaluacion_analisis_id = isset($_POST['ic_evaluacion_analisis_id']) ? $_POST['ic_evaluacion_analisis_id'] : 0;
    $ic_analisis_muestra_id = isset($_POST['ic_analisis_muestra_id']) ? $_POST['ic_analisis_muestra_id'] : 0;
    $ic_muestra_id = isset($_POST['ic_muestra_id']) ? $_POST['ic_muestra_id'] : 0;
    $observaciones = isset($_POST['observaciones']) ? $_POST['observaciones'] : null;
    $ic_resultado_decision_id = isset($_POST['resultadoDecision']) ? $_POST['resultadoDecision'] : 0;

    $evaluacion = new Evaluacion($ic_evaluacion_analisis_id,$ic_analisis_muestra_id,$ic_muestra_id,$observaciones,true, $ic_resultado_decision_id);

    $controladorEvaluacion->saveAndUpdateEvaluacion($evaluacion);
    $auditoria = new ControladorAuditoria();
    $auditoria->auditarRegistroUpdate($_SESSION['usuario'],$evaluacion);

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