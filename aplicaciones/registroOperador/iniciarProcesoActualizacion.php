<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorRegistroOperador.php';

try {

    $datos = array(
        'idTipoOperacion' => htmlspecialchars($_POST['idTipoOperacion'], ENT_NOQUOTES, 'UTF-8'),
        'idSolicitud' => htmlspecialchars($_POST['idSolicitud'], ENT_NOQUOTES, 'UTF-8'),
        'idOperadorTipoOperacion' => htmlspecialchars($_POST['idOperadorTipoOperacion'], ENT_NOQUOTES, 'UTF-8'),
        'idHistorialOperacion' => htmlspecialchars($_POST['idHistorialOperacion'], ENT_NOQUOTES, 'UTF-8')
    );

    $mensaje = array();
    $mensaje['estado'] = 'error';
    $mensaje['mensaje'] = 'Ha ocurrido un error!';
    
    try {
        $conexion = new Conexion();
        $cr = new ControladorRegistroOperador();
        $cc = new ControladorCatalogos();
        
        $qcodigoTipoOperacion= $cc->obtenerCodigoTipoOperacion($conexion, $datos['idSolicitud']);
        $opcionArea = pg_fetch_result($qcodigoTipoOperacion, 0, 'codigo');
        $idArea = pg_fetch_result($qcodigoTipoOperacion, 0, 'id_area');
                        
        $conexion->ejecutarConsulta("begin;");
        
        switch ($idArea){
            case 'AI':
                switch ($opcionArea){
                    case 'COM':
                        $qOperaciones = $cr->obtenerOperacionesXIdOperadorTipoOperacionXHistorialOperacion($conexion, $datos['idOperadorTipoOperacion'], $datos['idHistorialOperacion']);
                        while($operaciones = pg_fetch_assoc($qOperaciones)){
                            $cr->eliminarOperacionOrganicoXidOperacion($conexion, $operaciones['id_operacion']);
                            $cr->eliminarOperacionMercadoDestinoXIdOperacion($conexion, $operaciones['id_operacion']);
                        }
                        break;
                }
                
                break;
                
        }
        
        $idflujoOperacion = pg_fetch_assoc($cr->obtenerIdFlujoXOperacion($conexion, $datos['idSolicitud']));
        $estadoFlujo = pg_fetch_assoc($cr->obtenerEstadoFlujoOperacion($conexion, $idflujoOperacion['id_flujo_operacion'], '1'));
        $cr->actualizarEstadoAnteriorPorOperadorTipoOperacionHistorial($conexion, $datos['idOperadorTipoOperacion'], $datos['idHistorialOperacion']);
        $cr->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $datos['idOperadorTipoOperacion'], $datos['idHistorialOperacion'], $estadoFlujo['estado'], 'Inicio de proceso de modificación de datos de operación.');
        $cr->cambiarEstadoAreaOperacionPorPorOperadorTipoOperacionHistorial($conexion, $datos['idOperadorTipoOperacion'], $datos['idHistorialOperacion']);
        $cr->actualizarEstadoTipoOperacionPorIndentificadorSitio($conexion, $datos['idOperadorTipoOperacion'], $estadoFlujo['estado']);
        $cr->actualizarProcesoActualizacionOperacion($conexion, $datos['idOperadorTipoOperacion'], $datos['idHistorialOperacion'], TRUE);
        
        $conexion->ejecutarConsulta("commit;");
        
        $mensaje['estado'] = 'exito';
        $mensaje['mensaje'] = 'La solicitud se encuentra en proceso de modificación.';
        
        echo json_encode($mensaje);
    } catch (Exception $ex) {
        $conexion->ejecutarConsulta("rollback;");
        pg_close($conexion);
        $mensaje['estado'] = 'error';
        $mensaje['mensaje'] = "Error al ejecutar sentencia";
        echo json_encode($mensaje);
    }finally {
        $conexion->desconectar();
    }
} catch (Exception $ex) {
    $mensaje['estado'] = 'error';
    $mensaje['mensaje'] = 'Error de conexión a la base de datos';
    echo json_encode($mensaje);
}
?>