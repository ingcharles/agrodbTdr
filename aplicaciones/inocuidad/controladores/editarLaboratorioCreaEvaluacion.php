<?php
/**
 * Created by IntelliJ IDEA.
 * User: antonio
 * Date: 22/02/18
 * Time: 23:09
 */
session_start();
require_once 'ControladorLaboratorio.php';
require_once 'ControladorEvaluacion.php';
require_once '../../../clases/Conexion.php';
require_once '../Modelo/Laboratorio.php';

$conexion = new Conexion();
$controladorLaboratorio=new ControladorLaboratorio();
$controladorEvaluacion = new ControladorEvaluacion();
$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


$ic_analisis_muestra_id=isset($_POST['ic_analisis_muestra_id']) ? $_POST['ic_analisis_muestra_id'] : null;


//Llama a metodo Guardar Evaluación
if($ic_analisis_muestra_id!=null) {
    $laboratorio = $controladorLaboratorio->getLaboratorio($ic_analisis_muestra_id);
    $resultado = $controladorEvaluacion->creaEvaluacionLaboratorio($laboratorio);

    $evaluacion = $controladorEvaluacion->getEvaluacion($resultado);
    $auditoria = new ControladorAuditoria();
    $auditoria->auditarRegistroInsert($_SESSION['usuario'],$evaluacion);

    if ($resultado != null) {
        $mensaje['estado'] = 'exito';
        $mensaje['mensaje'] = 'Se ha enviado la Muestra con Éxito!';
    }
}
echo json_encode($mensaje);