<?php
/**
 * Created by IntelliJ IDEA.
 * User: antonio
 * Date: 22/02/18
 * Time: 13:09
 */
session_start();
require_once 'ControladorLaboratorio.php';
require_once '../../../clases/Conexion.php';
require_once '../Modelo/Laboratorio.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
    $conexion = new Conexion();
    $controladorLaboratorio=new ControladorLaboratorio();

    $ic_analisis_muestra_id     = isset($_POST['ic_analisis_muestra_id']) ? $_POST['ic_analisis_muestra_id'] : null;
    $ic_muestra_id              = isset($_POST['ic_muestra_id']) ? $_POST['ic_muestra_id'] : null;
    $observaciones              = isset($_POST['observaciones']) ? $_POST['observaciones'] : null;

    $laboratorio = new Laboratorio($ic_analisis_muestra_id, $ic_muestra_id, true);
    $laboratorio->setObservaciones($observaciones);

    //Guardamos el registro principal del laboratorio
    $mensaje['mensaje'] = $controladorLaboratorio->saveAndUpdateLaboratorio($laboratorio);

    //Iniciamos auditoria de laboratorio
    $auditoria = new ControladorAuditoria();
    $auditoria->auditarRegistroUpdate($_SESSION['usuario'],$laboratorio);

    if($mensaje['mensaje']==null){
        $registroValores = $controladorLaboratorio->getRegistroValores($ic_analisis_muestra_id);
        //Para cada valor registrado en laboratorio, recorremos los registros almacenando los valores.
        /* @var $registro RegistroValor */
        foreach ($registroValores as $registro){
            $ic_registro_valor_id = $registro->getIcRegistroValorId();
            $valor = isset($_POST['valor_'.$ic_registro_valor_id]) ? $_POST['valor_'.$ic_registro_valor_id] : 0;
            $obs   = isset($_POST['obs_'.$ic_registro_valor_id]) ? $_POST['obs_'.$ic_registro_valor_id] : null;

            $registro->setValor($valor);
            $registro->setObservaciones($obs);

            //Almacenamos Valores
            $mensaje['mensaje'] = $controladorLaboratorio->saveAndUpdateRegistroValor($registro);
            //Auditoria de los valores
            $auditoria->auditarRegistroUpdate($_SESSION['usuario'],$registro);
        }
    }
    if($mensaje['mensaje']!=null){
        $mensaje['estado'] = 'error';
    }else{
        $mensaje['estado'] = 'exito';
        $mensaje['mensaje'] = 'Los datos fueron actualizados';
    }
    $conexion->desconectar();
    echo json_encode($mensaje);
} catch (Exception $ex){
    pg_close($conexion);
    $mensaje['estado'] = 'error';
    $mensaje['mensaje'] = "Error al ejecutar sentencia";
    echo json_encode($mensaje);
}