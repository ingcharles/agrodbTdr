<?php
session_start();
require_once '../../../clases/Conexion.php';
require_once '../../../clases/ControladorRegistroOperador.php';

$mensaje = array();
$mensaje['estado'] = 'exito';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

$idCrearOperador = $_POST['id'];
$codigoIngresado = $_POST['codigo'];
$conexion = new Conexion();

try{
    $cr = new ControladorRegistroOperador();
    $consulta=$cr->obtenerCrearOperador($conexion, $idCrearOperador);
    $codigo = pg_fetch_result($consulta, 0, 'codigo_verificacion');
    if($codigo != ''){
        if($codigo == $codigoIngresado){
            $mensaje['mensaje'] = 'Código correcto';
        }else{
            $mensaje['estado'] = 'error';
            $mensaje['mensaje'] = 'Código incorrecto';
        }
    }else{
        $mensaje['estado'] = 'error';
        $mensaje['mensaje'] = 'Código incorrecto';
    }
    echo json_encode($mensaje);
} catch (Exception $ex) {
    $mensaje['estado'] = 'error';
    $mensaje['mensaje'] = 'Error de conexión a la base de datos';
    echo json_encode($mensaje);
    $conexion->ejecutarLogsTryCatch($ex);
}