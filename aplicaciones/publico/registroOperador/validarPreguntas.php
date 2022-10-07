<?php
session_start();

require_once '../../../clases/Conexion.php';
require_once '../../../clases/ControladorRegistroOperador.php';

$mensaje = array();
$mensaje['estado'] = 'exito';
$mensaje['mensaje'] = 'Ha ocurrido un error!';
$mensaje['pregunta1'] = 'exito';
$mensaje['pregunta2'] = 'exito';

$idCrearUsuario = $_POST['id'];
$respuesta1= $_POST['respuesta1'];
$respuesta2= $_POST['respuesta2'];
$idpregunta1=$_POST['idPregunta1'];
$idpregunta2=$_POST['idPregunta2'];

$conexion = new Conexion();

try{
    
    $cr = new ControladorRegistroOperador();
    $arrayDatos = array(
        'idCrearOperador' => $idCrearUsuario,
        'idPreguntasCrearOperador' => $idpregunta1,
        'respuestaPregunta' => $respuesta1
    );
    
    $resultado = $cr->obtenerDetalleCrearOperador($conexion, $arrayDatos);
    if(pg_num_rows($resultado) > 0){
        $mensaje['mensaje'] = 'Validación correcta';
    }else{
        $mensaje['estado'] = 'error';
        $mensaje['pregunta1'] = 'error';
        $mensaje['mensaje'] = 'Datos incorrectos';
    }
    
    $arrayDatos = array(
        'idCrearOperador' => $idCrearUsuario,
        'idPreguntasCrearOperador' => $idpregunta2,
        'respuestaPregunta' => $respuesta2
    );
    $resultado = $cr->obtenerDetalleCrearOperador($conexion, $arrayDatos);
    if(pg_num_rows($resultado) > 0){
        $mensaje['mensaje'] = 'Validación correcta';
    }else{
        $mensaje['estado'] = 'error';
        $mensaje['pregunta2'] = 'error';
        $mensaje['mensaje'] = 'Datos incorrectos';
    }
    
    echo json_encode($mensaje);
} catch (Exception $ex) {
    $mensaje['estado'] = 'error';
    $mensaje['mensaje'] = 'Error de conexión a la base de datos';
    echo json_encode($mensaje);
    $conexion->ejecutarLogsTryCatch($ex);
}