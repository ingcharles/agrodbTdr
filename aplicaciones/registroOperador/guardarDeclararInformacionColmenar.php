<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';

$idOperadorTipoOperacion = htmlspecialchars($_POST['idOperadorTipoOperacion'], ENT_NOQUOTES, 'UTF-8');
$idHistorialOperacion = htmlspecialchars($_POST['idHistorialOperacion'], ENT_NOQUOTES, 'UTF-8');
$idOperacion = htmlspecialchars($_POST['idOperacion'], ENT_NOQUOTES, 'UTF-8');
$duenioSitio = htmlspecialchars($_POST['duenioSitio'], ENT_NOQUOTES, 'UTF-8');
$numeroColmenares = htmlspecialchars($_POST['numeroColmenares'], ENT_NOQUOTES, 'UTF-8');
$numeroPromedioColmenas = htmlspecialchars($_POST['numeroPromedioColmenas'], ENT_NOQUOTES, 'UTF-8');

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
    
    $conexion = new Conexion();
    $cro = new ControladorRegistroOperador();
    
    try{
        
        $qBuscarRegistro = $cro->verificarRegistroInformacionColmenar($conexion, $idOperacion, $idOperadorTipoOperacion);
        
        $conexion->ejecutarConsulta("begin;");
        
        if (pg_num_rows($qBuscarRegistro) == 0){
            
            $cro->guardarRegistroInformacionColmenar($conexion, $idOperacion, $idOperadorTipoOperacion, $duenioSitio, $numeroColmenares, $numeroPromedioColmenas);
            
        }else{
                                  
            $buscarRegistro = pg_fetch_assoc($qBuscarRegistro);
            
            $idDatoColmenar = $buscarRegistro['id_dato_colmenar'];
            
            $cro->actualizarRegistroInformacionColmenarPorIdColmenar($conexion, $idDatoColmenar, $duenioSitio, $numeroColmenares, $numeroPromedioColmenas);

        }
        
        $operacion = pg_fetch_assoc($cro->abrirOperacionXid($conexion, $idOperacion));
        $idflujoOperacion = pg_fetch_assoc($cro->obtenerIdFlujoXOperacion($conexion, $idOperacion));
        $idFlujoActual = pg_fetch_assoc($cro->obtenerEstadoActualFlujoOperacion($conexion, $idflujoOperacion['id_flujo_operacion'], 'declararIColmenar'));
        $estado = pg_fetch_assoc($cro->obtenerEstadoFlujoOperacion($conexion, $idflujoOperacion['id_flujo_operacion'], $idFlujoActual['predecesor']));
        
        if ($operacion['modulo_provee'] == 'moduloExterno' && $estado['estado'] == 'cargarProducto'){
            $estado = pg_fetch_assoc($cro->obtenerEstadoFlujoOperacion($conexion, $idflujoOperacion['id_flujo_operacion'], $idFlujoActual['predecesor'] + 1));
        }
        
        $cro->actualizarEstadoAnteriorPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $idHistorialOperacion);
        
        switch ($estado['estado']) {
            
            case 'pago':
                $cro->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $idHistorialOperacion, $estado['estado']);
                break;
            case 'documental':
                $cro->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $idHistorialOperacion, $estado['estado']);
                break;
            case 'inspeccion':
                $cro->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $idHistorialOperacion, $estado['estado']);
                break;
            case 'cargarAdjunto':
                $cro->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $idHistorialOperacion, $estado['estado']);
                break;
            case 'cargarProducto':
                $cro->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $idHistorialOperacion, $estado['estado']);
                break;
        }
        
        $cro->actualizarEstadoTipoOperacionPorIndentificadorSitio($conexion, $idOperadorTipoOperacion, $estado['estado']);
        
        $conexion->ejecutarConsulta("commit;");
        
        $mensaje['estado'] = 'exito';
        $mensaje['mensaje'] = '';
        
    }catch (Exception $ex){
        $conexion->ejecutarConsulta("rollback;");
        $mensaje['mensaje'] = $ex->getMessage();
        $mensaje['error'] = $conexion->mensajeError;
        
    }finally {
        $conexion->desconectar();
    }
    
}catch (Exception $ex){
    $mensaje['mensaje'] = $ex->getMessage();
    $mensaje['error'] = $conexion->mensajeError;
}finally {
    echo json_encode($mensaje);
}

?>

