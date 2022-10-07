<?php
/**
 * Created by IntelliJ IDEA.
 * User: antonio
 * Date: 23/02/18
 * Time: 9:12
 */
require_once 'ControladorEvaluacion.php';
require_once '../../../clases/Conexion.php';
require_once '../Modelo/Evaluacion.php';
$conexion = new Conexion();

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


$controladorEvaluacion=new ControladorEvaluacion();
$ic_evaluacion_analisis_id = isset($_POST['ic_evaluacion_analisis_id']) ? $_POST['ic_evaluacion_analisis_id'] : 0;
$ic_resultado_decision_id = isset($_POST['ic_resultado_decision_id']) ? $_POST['ic_resultado_decision_id'] : 0;

if($ic_resultado_decision_id==0)
    $mensaje['mensaje'] = "Debe elegir una acción de la lista y que sea distinta a ''Sin Resultado'' ";
if($ic_evaluacion_analisis_id>0 && $ic_resultado_decision_id>0) {
    $mensaje = $controladorEvaluacion->evaluarAccion($ic_evaluacion_analisis_id, $ic_resultado_decision_id);
}
echo json_encode($mensaje);