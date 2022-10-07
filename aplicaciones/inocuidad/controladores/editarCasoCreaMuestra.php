<?php
/**
 * Created by PhpStorm.
 * User: ccarrera
 * Date: 2/1/18
 * Time: 11:52 PM
 */
session_start();
require_once 'ControladorRequerimiento.php';
require_once 'ControladorMuestra.php';
require_once '../../../clases/Conexion.php';
require_once '../Modelo/Caso.php';
$conexion = new Conexion();
$controladorRequerimiento=new ControladorRequerimiento($conexion);
$controladorMuestra = new ControladorMuestra($conexion);
$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


$requerimientoId=isset($_POST['ic_requerimiento_id']) ? $_POST['ic_requerimiento_id'] : null;


//Llama a metodo Guardar de Caso
$resultado= $controladorRequerimiento->creaMuestra($requerimientoId);

//Creamos a continuación la muestra, a partir del caso.
$muestra = $controladorMuestra->getMuestra($resultado);
$auditoria = new ControladorAuditoria();
$auditoria->auditarRegistroInsert($_SESSION['usuario'],$muestra);

if($resultado!=null){
    $mensaje['estado'] = 'exito';
    $mensaje['mensaje'] = 'Se ha enviado el Caso con Éxito!';
}
echo json_encode($mensaje);