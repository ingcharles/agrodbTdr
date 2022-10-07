<?php
/**
 * Created by IntelliJ IDEA.
 * User: antonio
 * Date: 20/02/18
 * Time: 22:54
 */
session_start();
require_once 'ControladorMuestra.php';
require_once '../../../clases/Conexion.php';
require_once '../Modelo/Muestra.php';
require_once 'ControladorLaboratorio.php';

$conexion = new Conexion();
$controladorMuestra=new ControladorMuestra($conexion);
$controladorLaboratorio = new ControladorLaboratorio();
$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


$muestraId=isset($_POST['ic_muestra_id']) ? $_POST['ic_muestra_id'] : null;


if($muestraId!=null) {
    $resultado = $controladorMuestra->creaLaboratorio($muestraId,1);

    $laboratorio = $controladorLaboratorio->getLaboratorio($resultado);
    $auditoria = new ControladorAuditoria();
    $auditoria->auditarRegistroInsert($_SESSION['usuario'],$laboratorio);
    if ($resultado != null) {
        $mensaje['estado'] = 'exito';
        $mensaje['mensaje'] = 'Se ha enviado la Muestra con Éxito!';
    }
}
echo json_encode($mensaje);