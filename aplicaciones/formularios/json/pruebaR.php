<?php

require_once '../../../clases/Conexion.php';
//require_once '../../../clases/ControladorRegistroOperador.php';
//$idSubtipo = htmlspecialchars($_POST['id'], ENT_NOQUOTES, 'UTF-8');

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Error al ejecutar';

try{
    $datos = json_decode($_POST['inspecciones']);
    $syncDTO = json_decode($_POST['syncDTO']);
    //$archivo = "prueba.txt";
    //$archivo2 = "prueba2.txt";
    //file_put_contents($archivo, serialize($datos));
    //file_put_contents($archivo2, serialize($datos[1]->fecha));
    //sleep(10);
    $conexion = new Conexion();
    foreach($datos as $dato){
        $conexion->ejecutarConsulta("
            insert into
              g_revision_solicitudes.prueba
              (id,
              identificador_inspector,
              fecha,
              identificador_asignante,
              nombre_formulario,
              resultado,
              identificador_tablet,
              version_bd,
              respuestas,
              observaciones,
              grupos,
              )
            values
              ('$dato->id',
              '$dato->identificador_inspector',
              '$dato->fecha',
              '$dato->identificador_asignante',
              '$dato->nombre_formulario',
              '$dato->resultado',
              '$dato->identificador_tablet',
              $syncDTO->version_bd,
              $syncDTO->respuestas,
              $dato->observaciones,
              $dato->grupos);
        ");
        //$respuestas = json_decode($datos->respuestas);
        //$observaciones = json_decode($datos->observaciones);
        //$grupos = json_decode($datos->grupos);
    }
    $mensaje['estado'] = 'exito';
    $mensaje['mensaje'] = 'se guardo';
    $conexion->desconectar();
} catch (Exception $ex){
    $mesaje['mesae'] = $ex->getMessage();
}
echo json_encode($mensaje);












