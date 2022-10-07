<?php
/**
 * Created by IntelliJ IDEA.
 * User: antonio
 * Date: 18/02/18
 * Time: 19:20
 */

session_start();
require_once 'ControladorMuestra.php';
require_once 'ControladorAuditoria.php';
require_once '../../../clases/Conexion.php';
require_once '../Modelo/Muestra.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';
try{
    $conexion = new Conexion();
    $controladorMuestra=new ControladorMuestra($conexion);
    $ic_muestra_id = isset($_POST['ic_muestra_id']) ? $_POST['ic_muestra_id'] : null;
    $ic_requerimiento_id=isset($_POST['ic_requerimiento_id']) ? $_POST['ic_requerimiento_id'] : null;
    $fecha_muestreo    = isset($_POST['fecha_muestreo']) ? $_POST['fecha_muestreo'] : null;
    $codigo_muestras   = isset($_POST['codigo_muestras']) ? $_POST['codigo_muestras'] : null;
    $provincia_id      = isset($_POST['provincia_id']) ? $_POST['provincia_id'] : 0;
    $canton_id         = isset($_POST['canton_id']) ? $_POST['canton_id'] : 0;
    $parroquia_id      = isset($_POST['parroquia_id']) ? $_POST['parroquia_id'] : 0;
    $origen_muestra_id = isset($_POST['origen_muestra_id']) ? $_POST['origen_muestra_id'] : 0;
    $tipo_empresa      = isset($_POST['tipo_empresa']) ? $_POST['tipo_empresa'] : null;
    //Nacional
    $finca_id          = isset($_POST['finca_id'])  ?   $_POST['finca_id'] :   0;
    $utm_x             = isset($_POST['utm_x'])  ?   $_POST['utm_x'] :   null;
    $utm_y             = isset($_POST['utm_y'])  ?   $_POST['utm_y'] :   null;
    //Importacion
    $nombre_rep_legal     = isset($_POST['nombre_rep_legal'])  ?   $_POST['nombre_rep_legal'] :   null;
    $registro_importador  = isset($_POST['registro_importador'])  ?   $_POST['registro_importador'] :   null;
    $pais_procedencia_id  = isset($_POST['pais_procedencia_id'])  ?   $_POST['pais_procedencia_id'] :   0;
    $permiso_fitosanitario= isset($_POST['permiso_fitosanitario'])  ?   $_POST['permiso_fitosanitario'] :   null;
    $tecnico_id           = isset($_POST['tecnico_id'])  ?   $_POST['tecnico_id'] :   0;
    $tipo_muestra_id      = isset($_POST['tipo_muestra_id'])  ?   $_POST['tipo_muestra_id'] :   0;

    $fecha_envio_lab           = isset($_POST['fecha_envio_lab'])  ?   $_POST['fecha_envio_lab'] :   null;
    $cantidad_muestras_lab     = isset($_POST['cantidad_muestras_lab'])  ?   $_POST['cantidad_muestras_lab'] :   0;
    $cantidad_contra_muestra   = isset($_POST['cantidad_contra_muestra'])  ?   $_POST['cantidad_contra_muestra'] :   0;
    $ultimo_insumo_aplicado_id = isset($_POST['ultimo_insumo_aplicado_id'])  ?   $_POST['ultimo_insumo_aplicado_id'] :   0;
    $produccion_estimada       = isset($_POST['produccion_estimada'])  ?   $_POST['produccion_estimada'] :   0;
    $fecha_ultima_aplicacion   = isset($_POST['fecha_ultima_aplicacion'])  ?   $_POST['fecha_ultima_aplicacion'] :   null;
    $tecnica_muestreo          = isset($_POST['tecnica_muestreo'])  ?   $_POST['tecnica_muestreo'] :   null;
    $medio_refrigeracion       = isset($_POST['medio_refrigeracion'])  ?   $_POST['medio_refrigeracion'] :   null;
    $observaciones             = isset($_POST['observaciones'])  ?   $_POST['observaciones'] :   null;

    if($finca_id === '')
        $finca_id=0;

    $muestra = new Muestra($ic_requerimiento_id, $ic_muestra_id, $fecha_muestreo, $codigo_muestras,
        $canton_id, $parroquia_id, $tipo_empresa, $finca_id, $utm_x, $utm_y, $registro_importador,
        $permiso_fitosanitario, $tecnico_id, null, true, true, $provincia_id,
        $origen_muestra_id, $nombre_rep_legal, $pais_procedencia_id, $tipo_muestra_id,
        $fecha_envio_lab, $cantidad_muestras_lab, $cantidad_contra_muestra, $ultimo_insumo_aplicado_id,
        $produccion_estimada, $fecha_ultima_aplicacion, $tecnica_muestreo, $medio_refrigeracion, $observaciones);

    $mensaje['mensaje'] = $controladorMuestra->saveAndUpdateMuestra($muestra, $conexion);
    //Auditoria
    $auditoria = new ControladorAuditoria();
    $auditoria->auditarRegistroUpdate($_SESSION['usuario'],$muestra);

    if($mensaje['mensaje']==null){
        $registroValores = $controladorMuestra->getRegistroMuestraRapidaValores($ic_muestra_id);
        //Si existe muestraRapida, recorremos uno a uno los elementos
        /* @var $registro MuestraRapidaValor */
        foreach ($registroValores as $registro){
            $ic_muestra_rapida_id = $registro->getIcMuestraRapidaId();
            $valor = isset($_POST['valor_'.$ic_muestra_rapida_id]) ? $_POST['valor_'.$ic_muestra_rapida_id] : 0;
            $obs   = isset($_POST['obs_'.$ic_muestra_rapida_id]) ? $_POST['obs_'.$ic_muestra_rapida_id] : null;

            $registro->setValor($valor);
            $registro->setObservaciones($obs);

            $mensaje['mensaje'] = $controladorMuestra->saveAndUpdateMuestraRapidaValor($registro);
            //Auditoria
            $auditoria = new ControladorAuditoria();
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