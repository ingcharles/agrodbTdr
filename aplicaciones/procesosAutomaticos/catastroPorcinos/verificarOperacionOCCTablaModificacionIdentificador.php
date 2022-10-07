<?php

if($_SERVER['REMOTE_ADDR'] == ''){
//if(1){
    require_once '../../../clases/Conexion.php';
    require_once '../../../clases/ControladorMonitoreo.php';
    require_once '../../../clases/ControladorCatastroProducto.php';
    
    $conexion = new Conexion();
    $cm = new ControladorMonitoreo();
    $ccp = new ControladorCatastroProducto();
    
    define ( 'IN_MSG', '<br/> >>> ' );
    define ( 'OUT_MSG', '<br/> <<< ' );
    define ( 'PRO_MSG', '<br/> &emsp;&emsp;... ' );
    
    $fecha = date("Y-m-d h:m:s");
    
    $resultadoMonitoreo = $cm->obtenerCronPorCodigoEstado($conexion, 'CRON_VERIF_OPERA_OCC');//TODO: Crear nuevo código de cron en base de datos "CRON_VERIF_OPERA_OPICC" 
    if($resultadoMonitoreo){
    //if(1){
        
 
        echo '<h1>VERIFICACION DE OPERADORES CON OPERACION INDUSTRIAL CICLO CERRADO</h1>';
    
        
        echo IN_MSG .'<b>INICIO PROCESO VERIFICACION DE OPERADORES OCC - TABLA MODIFICACION_IDENTIFICADOR EN CATASTRO '.$fecha.'</b></p>';
   
        $qModificacionIdentificador = $ccp->obtenerOperadorModificacionIdentificador($conexion);
        
        while ( $filaActivo = pg_fetch_assoc ( $qModificacionIdentificador ) ) {
            $qOperacionesUsuario = $ccp->obtenerOperacionesUsuario($conexion, $filaActivo['identificador_operador'], "('OCC')");
            if(pg_num_rows($qOperacionesUsuario) > 0){
                if($filaActivo['estado_modificacion_identificador'] == 'inactivo'){
                    $ccp->actualizarEstadoOperadorModificacionIdentificador($conexion, $filaActivo['identificador_operador'], 'activo');
                    echo '<b>' . PRO_MSG . 'Proceso que verifica si el operador con N° Identificador ' . $filaActivo['identificador_operador'] . ' tiene operacion OCC - el registro fue activado exitosamente' . '</b>';
                }
            }else{
                if($filaActivo['estado_modificacion_identificador'] == 'activo'){
                    $ccp->actualizarEstadoOperadorModificacionIdentificador($conexion, $filaActivo['identificador_operador'], 'inactivo');
                    echo '<b>' . PRO_MSG . 'Proceso que verifica si el operador con N° Identificador ' . $filaActivo['identificador_operador'] . ' no tiene operacion OCC - el registro fue inactivado exitosamente' . '</b>';
                }
            }
        }
               
        echo '</p>'.OUT_MSG .'<b>FIN DEL PROCESO DE VERIFICACION '.$fecha.'</b>';
        
    }
}else{
    $minutoS1=microtime(true);
    $minutoS2=microtime(true);
    $tiempo=$minutoS2-$minutoS1;
    $xcadenota = "FECHA ".date("d/m/Y")." ".date("H:i:s");
    $xcadenota.= "; IP REMOTA ".$_SERVER['REMOTE_ADDR'];
    $xcadenota.= "; SERVIDOR HTTP ".$_SERVER['HTTP_REFERER'];
    $xcadenota.= "; SEGUNDOS ".$tiempo."\n";
    $arch = fopen("../../../aplicaciones/logs/cron/verificar_operacion_OCC_".date("d-m-Y").".txt", "a+");
    fwrite($arch, $xcadenota);
    fclose($arch);
    
}
?>