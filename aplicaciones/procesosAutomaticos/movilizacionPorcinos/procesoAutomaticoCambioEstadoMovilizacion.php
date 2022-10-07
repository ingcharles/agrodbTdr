<?php
if ($_SERVER['REMOTE_ADDR'] == '') {
    // if(1){
    require_once '../../../clases/Conexion.php';
    require_once '../../../clases/ControladorMonitoreo.php';
    require_once '../../../clases/ControladorMovilizacionProductos.php';
    
    define('IN_MSG', '<br/> >>> ');
    define('OUT_MSG', '<br/> <<< ');
    define('PRO_MSG', '<br/> ... ');
    
    $conexion = new Conexion();
    $cm = new ControladorMonitoreo();
    $cmp = new ControladorMovilizacionProductos();
    
    $resultadoMonitoreo = $cm->obtenerCronPorCodigoEstado($conexion, 'CRON_EST_MOV_PRO_AGRO');
    
    if ($resultadoMonitoreo) {
        // if (1) {
        try {
            try {
                echo '<h1>ACTUALIZACION AUTOMATICA DE ESTADOS EN CERTIFICADOS DE MOVILIZACION</h1>';
                echo '<p> <strong>INICIO PROCESO DE ACTUALIZACION</strong>';
                
                $qCambioEstadoVigente = $cmp->consultarCertificadosMovilizacionCambioEstado($conexion, "('creado')", 'fecha_inicio_vigencia');
                
                $contadorEstadoVigente = 1;
                while ($filaEstadoVigente = pg_fetch_assoc($qCambioEstadoVigente)) {
                    $conexion->ejecutarConsulta("begin;");
                    echo '<b>' . PRO_MSG . 'Proceso A Estado Vigente #' . $contadorEstadoVigente ++ . ' - ' . ' N° Certificado ' . $filaEstadoVigente['numero_certificado'] . '</b>';
                    echo IN_MSG . 'Inicio del envio de la solicitud a actualizar estado del certificado de movilizacion a estado vigente';
                    $cmp->actualizarEstadoCertificadosMovilizacion($conexion, $filaEstadoVigente['id_movilizacion'], 'vigente');
                    echo OUT_MSG . 'Fin del envio de solicitud';
                    $conexion->ejecutarConsulta("commit;");
                }
                
                $qCambioEstadoCaducado = $cmp->consultarCertificadosMovilizacionCambioEstado($conexion, "('vigente')", 'fecha_fin_vigencia');
                $contadorEstadoCaducado = 1;
                while ($filaEstadoCaducado = pg_fetch_assoc($qCambioEstadoCaducado)) {
                    $conexion->ejecutarConsulta("begin;");
                    echo '<b>' . PRO_MSG . 'Proceso A Estado Caducado #' . $contadorEstadoCaducado ++ . ' - ' . ' N° Certificado ' . $filaEstadoCaducado['numero_certificado'] . '</b>';
                    echo IN_MSG . 'Inicio del envio de la solicitud a actualizar estado del certificado de movilizacion a estado caducado';
                    $cmp->actualizarEstadoCertificadosMovilizacion($conexion, $filaEstadoCaducado['id_movilizacion'], 'caducado');
                    $conexion->ejecutarConsulta("commit;");
                    echo OUT_MSG . 'Fin del envio de solicitud';
                }
                
                echo '<br/><strong>FIN</strong></p>';
                // $conexion->desconectar ();
            } catch (Exception $ex) {
                $conexion->ejecutarConsulta("rollback;");
                $err = preg_replace("/\r|\n/", " ", $conexion->mensajeError);
                $conexion->ejecutarLogsTryCatch($ex . '---ERROR:' . $err);
            } finally {
                $conexion->desconectar();
            }
        } catch (Exception $ex) {
            $err = preg_replace("/\r|\n/", " ", $conexion->mensajeError);
            $conexion->ejecutarLogsTryCatch($ex . '---ERROR:' . $err);
        }
    }
} else {
    
    $minutoS1 = microtime(true);
    $minutoS2 = microtime(true);
    $tiempo = $minutoS2 - minutoS1;
    $xcadenota = "FECHA " . date("d/m/Y") . " " . date("H:i:s");
    $xcadenota .= "; IP REMOTA " . $_SERVER['REMOTE_ADDR'];
    $xcadenota .= "; SERVIDOR HTTP " . $_SERVER['HTTP_REFERER'];
    $xcadenota .= "; SEGUNDOS " . $tiempo . "\n";
    $arch = fopen("../../aplicaciones/logs/cron/cambio_estado_movilizacion_producto_" . date("d-m-Y") . ".txt", "a+");
    fwrite($arch, $xcadenota);
    fclose($arch);
}
?>