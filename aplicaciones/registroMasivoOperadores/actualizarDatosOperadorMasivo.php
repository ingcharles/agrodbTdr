<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorUsuarios.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorGestionAplicacionesPerfiles.php';
require_once '../../clases/ControladorVigenciaDocumentos.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
    
    $datos = array(
        'identificador' => htmlspecialchars ($_POST['identificador'],ENT_NOQUOTES,'UTF-8'),
        'nombreSitio' => htmlspecialchars ($_POST['nombreSitio'],ENT_NOQUOTES,'UTF-8'),
        'razon' => htmlspecialchars ($_POST['razon'],ENT_NOQUOTES,'UTF-8'),
        'nombreLegal' => htmlspecialchars ($_POST['nombreLegal'],ENT_NOQUOTES,'UTF-8'),
        'apellidoLegal' => htmlspecialchars ($_POST['apellidoLegal'],ENT_NOQUOTES,'UTF-8'),
        'sitio' => htmlspecialchars ($_POST['sitio'],ENT_NOQUOTES,'UTF-8'),
        'nombreProvincia' => htmlspecialchars ($_POST['nombreProvinciaExistente'],ENT_NOQUOTES,'UTF-8'),
        'idProvincia' => htmlspecialchars ($_POST['provincia'],ENT_NOQUOTES,'UTF-8'),
        'idCanton' => htmlspecialchars ($_POST['canton'],ENT_NOQUOTES,'UTF-8'),
        'idParroquia' => htmlspecialchars ($_POST['parroquia'],ENT_NOQUOTES,'UTF-8'),
        'direccion' => htmlspecialchars ($_POST['direccion'],ENT_NOQUOTES,'UTF-8'),
        'telefono' => htmlspecialchars ($_POST['telefono'],ENT_NOQUOTES,'UTF-8'),
        'celular' => htmlspecialchars ($_POST['celular'],ENT_NOQUOTES,'UTF-8'),
        'idProducto' => $_POST['iProducto'],
        'tipoOperacion' => htmlspecialchars ($_POST['tipoOperacion'],ENT_NOQUOTES,'UTF-8'),
        'areaOperacion' => htmlspecialchars ($_POST['areaOperacion'],ENT_NOQUOTES,'UTF-8'),
        'idFlujo' => htmlspecialchars ($_POST['idFlujo'],ENT_NOQUOTES,'UTF-8'),
        'latitud' => htmlspecialchars ($_POST['latitud'],ENT_NOQUOTES,'UTF-8'),
        'longitud' => htmlspecialchars ($_POST['longitud'],ENT_NOQUOTES,'UTF-8'),
        'zona' => htmlspecialchars ($_POST['zona'],ENT_NOQUOTES,'UTF-8')
    );
    
    try {
        
        $conexion = new Conexion();
        $cc = new ControladorCatalogos();
        $cr = new ControladorRegistroOperador();
        $cu = new ControladorUsuarios();
        $ca = new ControladorAplicaciones();
        $cgap= new ControladorGestionAplicacionesPerfiles();
        $cvd = new ControladorVigenciaDocumentos();
        
        $arrayIdArea= array();
        $ingreso = true;
        $seleccionarProducto = true;
        $areasOperacionB = '';
        $productosIngresados = '';
        $arrayCodigoOperadorTipoOperacion = array();
        $bandera = array();
        $ingresoMultiple = true;
        $banderaAreaOperacion = true;
        
        $conexion->ejecutarConsulta("begin;");
        $cr->actualizarDatosOperadorMasivo($conexion, $datos['identificador'], $datos['razon'], $datos['nombreLegal'], $datos['apellidoLegal']);
        //TODO: Actualizar datos de operador
        
        $multipleOperacion = pg_fetch_result($cc->buscarTipoOperacionMultiple($conexion, $datos['tipoOperacion']), 0, 'operacion_multiple');
        
        $areasOperacion = $cc->obtenerAreasXtipoOperacion($conexion, $datos['tipoOperacion']);
        
        if($datos['sitio'] == 'nuevoSitio'){
            $qLocalizacion = $cc->obtenerNombreLocalizacion($conexion, $datos['idProvincia']);
            $provincia = pg_fetch_assoc($qLocalizacion);
            
            $res = $cc -> obtenerNombreLocalizacion($conexion, $datos['idCanton']);
            $canton = pg_fetch_assoc($res);
            
            $res = $cc -> obtenerNombreLocalizacion($conexion, $datos['idParroquia']);
            $parroquia = pg_fetch_assoc($res);
            
            //TODO: Guardar Nuevo sitio
            $qSecuencialSitio = $cr->obtenerSecuencialSitio($conexion, $provincia['nombre'], $datos['identificador']);
            $secuencialSitio = str_pad(pg_fetch_result($qSecuencialSitio, 0, 'valor'), 2, "0", STR_PAD_LEFT);
            
            $qIdSitio = $cr->guardarNuevoSitio($conexion, $datos['nombreSitio'], $provincia['nombre'],
                $canton['nombre'], $parroquia['nombre'], $datos['direccion'], '', 0, $datos['identificador'], $datos['telefono'],
                $datos['latitud'], $datos['longitud'], $secuencialSitio, '0',$datos['zona'],substr($provincia['codigo_vue'],1));
            
            foreach ($areasOperacion as $areaOperacion){
                
                $qSecuencialArea = $cr-> obtenerSecuencialArea($conexion, $datos['identificador'], $areaOperacion['codigo'],$provincia['nombre']);
                $secuencial = str_pad(pg_fetch_result($qSecuencialArea, 0, 'valor'), 2, "0", STR_PAD_LEFT);
                
                //TODO: Poner el nombre del Área -> Área (nombre del Área)
                $areaOperacionNombreAutomatico= "Área ".substr($secuencial,1)." ".$areaOperacion['nombre'];
                
                //TODO: Guardar Area para el sitio
                $qIdArea = $cr -> guardarNuevaArea($conexion, $areaOperacionNombreAutomatico, $areaOperacion['nombre'], 0, pg_fetch_result($qIdSitio, 0, 'id_sitio'), $areaOperacion['codigo'], $secuencial);
                
                $arrayIdArea[]=pg_fetch_result($qIdArea, 0, 'id_area');
            }
            
            $codigoSitio=pg_fetch_result($qIdSitio, 0, 'id_sitio');
            
            $arrayCodigoOperadorTipoOperacion[] = true;
            
        }else{
            
            foreach ($areasOperacion as $areaOperacion){
                
                //TODO: Consultar si para el sitio, existe un Área del tipo que se esta recorriendo.
                $qAreaExistente=$cr->buscarAreaOperacionXidSitio($conexion, $areaOperacion['nombre'], $datos['sitio']);
                $areaExistente=pg_fetch_assoc($qAreaExistente);
                
                if(pg_num_rows($qAreaExistente) == 0){
                    
                    $qSecuencialArea = $cr-> obtenerSecuencialArea($conexion, $datos['identificador'], $areaOperacion['codigo'],$datos['nombreProvincia']);
                    $secuencial = str_pad(pg_fetch_result($qSecuencialArea, 0, 'valor'), 2, "0", STR_PAD_LEFT);
                    
                    //TODO: Poner el nombre del Área -> Área(nombre del Área)
                    $areaOperacionNombreAutomatico= "Área ".substr($secuencial,1)." ".$areaOperacion['nombre'];
                    
                    //TODO: Guardar Area para el sitio
                    $qIdArea = $cr -> guardarNuevaArea($conexion, $areaOperacionNombreAutomatico, $areaOperacion['nombre'], 0, $datos['sitio'], $areaOperacion['codigo'], $secuencial);
                    
                    $arrayIdArea[]=pg_fetch_result($qIdArea, 0, 'id_area');
                    
                    $arrayCodigoOperadorTipoOperacion[] = true;
                }else{
                    $arrayIdArea[]=$areaExistente['id_area'];
                    $arrayCodigoOperadorTipoOperacion[] = false;
                }
            }
            $codigoSitio= $datos['sitio'];
        }
        
        if(count($datos['idProducto']) != 0){
            $todosProductos = implode(',', $datos['idProducto']);
            $todosProductos = "(".rtrim($todosProductos,',').")";
            foreach($arrayIdArea as $posicion=>$idArea){
                $vAreaProductoOperacion = $cr->buscarAreasOperacionProductoXSolicitud($conexion, $datos['tipoOperacion'], $todosProductos,$idArea, $datos['identificador']);
                if(pg_num_rows($vAreaProductoOperacion)!= 0){
                    $ingreso = false;
                    while ($fila = pg_fetch_assoc($vAreaProductoOperacion)){
                        
                        
                        if(($fila['estado_operacion'] == 'porCaducar' && $fila['estado_anterior'] == 'registrado') || ($fila['estado_operacion'] == 'noHabilitado' && $fila['estado_anterior'] == 'porCaducar')){
                            $ingreso = true;
                            $bandera[] = $ingreso;
                        }else{
                            
                            $datosOperacion = pg_fetch_assoc($cc->obtenerDatosTipoOperacion($conexion, $datos['tipoOperacion']));
                            
                            switch ($datosOperacion['id_area']){
                                case 'LT':
                                    switch ($datosOperacion['codigo']){
                                        case 'LAL':
                                        case 'LDV':
                                            if($fila['estado_operacion'] == 'noHabilitado'){
                                                $ingreso = true;
                                                $bandera[] = $ingreso;
                                            }else{
                                                $ingreso = false;
                                                $bandera[] = $ingreso;
                                            }
                                            break;
                                        default:
                                            $ingreso = false;
                                            $bandera[] = $ingreso;
                                    }
                                    break;
                                default:
                                    $ingreso = false;
                                    $bandera[] = $ingreso;
                            }
                        }
                        
                        $productosIngresados .= $fila['nombre_producto'].', ';
                    }
                }
            }
            
            $resultado = array_unique($bandera);
            
            if(count($resultado) == 1){
                if($resultado[0]){
                    $ingreso = true;
                }else{
                    $ingreso = false;
                }
            }else if(count($resultado) == 2){
                $ingreso = false;
            }
            
        }else{
            $seleccionarProducto = false;
        }
        
        if($multipleOperacion =="f"){
            $cantidadOperaciones = $cr->obtenerOperacionesPorIdentificadorAreaTipoOperacion($conexion, $datos['identificador'], $datos['tipoOperacion'], $datos['areaOperacion'], 'noMultiple');
        }else{
            $cantidadOperaciones = $cr->obtenerOperacionesPorIdentificadorAreaTipoOperacion($conexion, $datos['identificador'], $datos['tipoOperacion'], $datos['areaOperacion'], 'multiple');
        }
        
        if(pg_num_rows($cantidadOperaciones)!= 0){
            $ingresoMultiple = false;
        }
        
        if($ingresoMultiple){
            
            if($ingreso && $seleccionarProducto){
                
                $cantidadOperadorTipoOperacion = count(array_unique($arrayCodigoOperadorTipoOperacion));
                
                if($cantidadOperadorTipoOperacion == 1){
                    if($arrayCodigoOperadorTipoOperacion[0]){
                        //TODO:Nuevo proceso
                        $idOperadorTipoOperacion = pg_fetch_result($cr->guardarTipoOperacionPorIndentificadorSitio($conexion, $datos['identificador'], $codigoSitio, $datos['tipoOperacion']), 0, 'id_operador_tipo_operacion');
                        $historicoOperacion = pg_fetch_result($cr->guardarDatosHistoricoOperacion($conexion,$idOperadorTipoOperacion), 0, 'id_historial_operacion');
                        
                    }else{
                        //TODO:Mandar a buscar el identificador de la operacion minima
                        $qDatosOperacionAsociada = $cr->obtenerIdOperadorTipoOperacionHistorialXIdentificadorTipoOperacionSitio($conexion, $datos['identificador'],  $datos['tipoOperacion'], $codigoSitio, " not in ('eliminado')");
                        
                        if(pg_num_rows($qDatosOperacionAsociada) == 0){
                            $idOperadorTipoOperacion = pg_fetch_result($cr->guardarTipoOperacionPorIndentificadorSitio($conexion, $datos['identificador'], $codigoSitio, $datos['tipoOperacion']), 0, 'id_operador_tipo_operacion');
                            $historicoOperacion = pg_fetch_result($cr->guardarDatosHistoricoOperacion($conexion,$idOperadorTipoOperacion), 0, 'id_historial_operacion');
                        }else{
                            
                            $qAreaOperacion = $cr->obtenerOperacionesOperadorMasivo($conexion, $datos['identificador'], " in ('cargarProducto','registrado','subsanacionProducto')", $idArea, $datos['tipoOperacion']);
                            
                            if(pg_num_rows($qAreaOperacion) > 0){
                                
                                $areaOperacion = pg_fetch_assoc($qAreaOperacion);
                                $estadoAreaOperacion = $areaOperacion['estado'];
                                
                                if($estadoAreaOperacion == 'registrado'){
                                    $condicion = $cr->obtenerCondicionTipoOperacion($conexion, $datos['tipoOperacion'], 'cargarProducto');
                                    if(pg_num_rows($condicion) != 0){
                                        $datosOperacionAsociada = pg_fetch_assoc($qDatosOperacionAsociada);
                                        $idOperacion = $datosOperacionAsociada['id_operacion'];
                                        $idOperadorTipoOperacion = $datosOperacionAsociada['id_operador_tipo_operacion'];
                                        $historicoOperacion = $datosOperacionAsociada['id_historial_operacion'];
                                    }else{
                                        $banderaAreaOperacion = false;
                                    }
                                }else if($estadoAreaOperacion == 'cargarProducto' || $estadoAreaOperacion == 'subsanacionProducto'){
                                    $datosOperacionAsociada = pg_fetch_assoc($qDatosOperacionAsociada);
                                    $idOperacion = $datosOperacionAsociada['id_operacion'];
                                    $idOperadorTipoOperacion = $datosOperacionAsociada['id_operador_tipo_operacion'];
                                    $historicoOperacion = $datosOperacionAsociada['id_historial_operacion'];
                                }else{
                                    $banderaAreaOperacion = false;
                                }
                                
                            }else{
                                $banderaAreaOperacion = false;
                            }
                        }
                    }
                }else{
                    //TODO:Nuevo proceso
                    $idOperadorTipoOperacion = pg_fetch_result($cr->guardarTipoOperacionPorIndentificadorSitio($conexion, $datos['identificador'], $codigoSitio, $datos['tipoOperacion']), 0, 'id_operador_tipo_operacion');
                    $historicoOperacion = pg_fetch_result($cr->guardarDatosHistoricoOperacion($conexion, $idOperadorTipoOperacion), 0, 'id_historial_operacion');
                }
                
                
                if($banderaAreaOperacion){
                    
                    for($i = 0; $i < count($datos['idProducto']); $i++){
                        $valores = array();
                        $resultado = array();
                        
                        
                        //NUEVO VIGENCIA DOCUMENTO
                        
                        $qCabeceraVigencia = $cvd->buscarTipoOperacionCabeceraVigencia($conexion, $datos['tipoOperacion']);
                        
                        $idVigenciaDocumento = 0;
                        
                        if(pg_num_rows($qCabeceraVigencia) > 0){
                            
                            $cabeceraVigencia = pg_fetch_assoc($qCabeceraVigencia);
                            if($cabeceraVigencia['nivel_lista']=='operacion'){
                                
                                //$idVigenciaDocumento = $operacion['id_vigencia_documento'];
                                $idVigenciaDocumento = $cabeceraVigencia['id_vigencia_documento'];
                                
                            }else{
                                $qDetalleVigencia = $cvd->buscarVigenciaProducto($conexion, $cabeceraVigencia['id_vigencia_documento'], $datos['idProducto'][$i]);
                                if(pg_num_rows($qDetalleVigencia) > 0){
                                    $detalleVigencia = pg_fetch_assoc($qDetalleVigencia);
                                    $idVigenciaDocumento = $detalleVigencia['id_vigencia_documento'];
                                }
                            }
                        }
                                                
                        //NUEVO VIGENCIA DOCUMENTO
                        $qProducto = $cc->obtenerNombreProducto($conexion, $datos['idProducto'][$i]);
                        
                        if(isset($idOperacion)){
                        
                            $idProducto = pg_fetch_result($cr->abrirOperacionXid($conexion, $idOperacion), 0, 'id_producto');
                                                
                            if ($idProducto == null){
                                
                                $cr->actualizarProductoOperacion($conexion, $idOperacion, $datos['idProducto'][$i], pg_fetch_result($qProducto, 0, 'nombre_comun'), $idVigenciaDocumento);
                                $idSolicitud = $idOperacion;
                            }else{
                                
                                $qIdSolicitud = $cr->guardarNuevaOperacionPorTipoOperacion($conexion, $datos['tipoOperacion'], $datos['identificador'], $idOperadorTipoOperacion, $historicoOperacion, 'cargarProducto', $idVigenciaDocumento);
                                $idSolicitud = pg_fetch_result($qIdSolicitud, 0, 'id_operacion');
                                $cr->actualizarProductoOperacion($conexion, $idSolicitud, $datos['idProducto'][$i], pg_fetch_result($qProducto, 0, 'nombre_comun'), $idVigenciaDocumento);
                                                 
                            }
                        
                        }else{
                            
                            $qIdSolicitud= $cr->guardarNuevaOperacion($conexion, $datos['tipoOperacion'], $datos['identificador'],$datos['idProducto'][$i],  pg_fetch_result($qProducto, 0, 'nombre_comun'), $idOperadorTipoOperacion, $historicoOperacion);
                            $idSolicitud = pg_fetch_result($qIdSolicitud, 0, 'id_operacion');
                        }

                        $cr->actualizarVigenciaXOperacion($conexion, $idSolicitud, $idVigenciaDocumento);
                        
                        if($i == 0 && $arrayCodigoOperadorTipoOperacion[0]){
                            $cr->actualizarIdentificadorOperacionPorOperadorTipoOperacion($conexion, $idOperadorTipoOperacion, $idSolicitud);
                        }
                        
                        //TODO: Recorrer array de idAreas.
                        foreach($arrayIdArea as $posicion=>$idArea){
                            //TODO: Guardar relación entre area y operacion
                            $idAreas = $cr->guardarAreaOperacion($conexion,  $idArea, $idSolicitud);
                            if($i == 0 && $arrayCodigoOperadorTipoOperacion[0]){
                                $cr->guardarAreaPorIdentificadorTipoOperacion($conexion, $idArea, $idOperadorTipoOperacion);
                            }
                        }
                        
                        //AGREGADO PARA EL FLUJO
                        //TODO: VAMOS A CONSULTAR CON EL ID DE PRODUCTO Y EL ID TIPOOPERACION A LA TABLA DE PRODUCTO_MULTIPLE_VARIEDADES
                        $variedad = $cr->buscarVariedadOperacionProducto($conexion, $datos['tipoOperacion'] , $datos['idProducto'][$i]);
                        $valores[] = (pg_num_rows($variedad) == '0'?'flujoNormal':'variedad');
                        
                        $resultado = array_unique($valores);
                        
                        if(count($resultado) == 1 ){
                            if($resultado[0]=='flujoNormal'){
                                $estadoFlujo = pg_fetch_assoc($cr->obtenerEstadoFlujoOperacion($conexion, $datos['idFlujo'], '1'));
                                
                                if($estadoFlujo['estado'] == 'cargarProducto'){
                                    $estadoFlujo = pg_fetch_assoc($cr->obtenerEstadoFlujoOperacion($conexion, $datos['idFlujo'], '2'));
                                }
                                
                                switch ($estadoFlujo['estado']){
                                    
                                    case 'cargarAdjunto':
                                        $res = $cr -> enviarOperacion($conexion, $idSolicitud,$estadoFlujo['estado']);
                                        break;
                                    case 'inspeccion':
                                        $res = $cr -> enviarOperacion($conexion, $idSolicitud,$estadoFlujo['estado']);
                                        break;
                                    case 'pago':
                                        $res = $cr -> enviarOperacion($conexion, $idSolicitud,$estadoFlujo['estado']);
                                        break;
                                    case 'declararDVehiculo':
                                        $res = $cr -> enviarOperacion($conexion, $idSolicitud,$estadoFlujo['estado']);
                                        break;
                                    case'registrado':
                                        $fechaActual = date('Y-m-d H-i-s');
                                        $cr -> enviarOperacion($conexion, $idSolicitud,'registrado', 'No se realizó proceso de inspección, ni cobro de tasas. Proceso ejecutado por sistema GUIA '.$fechaActual.' en base a memorando MAGAP-DSV/AGROCALIDAD-2014-001427-M');
                                        $cr -> cambiarEstadoAreaXidSolicitud($conexion, $idSolicitud, 'registrado', 'No se realizó proceso de inspección, ni cobro de tasas. Proceso ejecutado por sistema GUIA '.$fechaActual.' en base a memorando MAGAP-DSV/AGROCALIDAD-2014-001427-M');
                                        break;
                                }
                                
                                $cargarInformacion = 'FALSE';
                                
                            }else{
                                $res = $cr -> enviarOperacion($conexion, $idSolicitud,'cargarIA');
                                $cargarInformacion = 'TRUE';
                            }
                        }else{
                            $res = $cr -> enviarOperacion($conexion, $idSolicitud,'cargarIA');
                            $cargarInformacion = 'TRUE';
                        }
                        
                        $areasOperacionB = implode(',', $arrayIdArea);
                        $estadoOperacion = $cr->buscarEstadoOperacionArea($conexion, $datos['tipoOperacion'], $datos['identificador'], $areasOperacionB);
                        $estado = pg_fetch_assoc($estadoOperacion);
                        
                        if ($estado['estado']=='registrado'){
                            
                            $qcodigoTipoOperacion= $cc->obtenerCodigoTipoOperacionXOperacion($conexion, $idSolicitud);
                            $codigoTipoOperacion=  pg_fetch_result($qcodigoTipoOperacion, 0, 'codigo');
                            if($codigoTipoOperacion == 'ACOSV' || $codigoTipoOperacion == 'COMSV' ){
                                $qOperaciones=$cr->buscarOperacionesPorCodigoyAreaOperacion($conexion, $datos['identificador'],"('ACO','COM')","('SV')");
                                if(pg_fetch_row($qOperaciones)>0){
                                    $qGrupoAplicacion=$cgap->obtenerGrupoAplicacion($conexion, "('PRG_EMISI_ETIQU')");
                                    while($filaAplicacion=pg_fetch_assoc($qGrupoAplicacion)){
                                        $qGrupoPerfiles=$cgap->obtenerGrupoPerfilXAplicacion($conexion, $filaAplicacion['id_aplicacion'], "('PFL_SOLIC_ETIQU')");
                                        $perfilesArray=Array();
                                        while($fila=pg_fetch_assoc($qGrupoPerfiles)){
                                            $perfilesArray[]=array('idPerfil'=>$fila['id_perfil'],'codigoPerfil'=>$fila['codificacion_perfil']);
                                        }
                                        if(pg_num_rows($ca->obtenerAplicacionPerfil($conexion, $filaAplicacion['id_aplicacion'] , $datos['identificador']))==0){
                                            $qAplicacionVacunacion=$cgap->guardarGestionAplicacion($conexion, $datos['identificador'],$filaAplicacion['codificacion_aplicacion']);
                                            foreach( $perfilesArray as $datosPerfil){
                                                $qPerfil = $cu-> obtenerPerfilUsuario($conexion, $datosPerfil['idPerfil'],  $datos['identificador']);
                                                if (pg_num_rows($qPerfil) == 0)
                                                    $cgap->guardarGestionPerfil($conexion, $datos['identificador'],$datosPerfil['codigoPerfil']);
                                            }
                                        }else{
                                            foreach( $perfilesArray as $datosPerfil){
                                                $qPerfil = $cu-> obtenerPerfilUsuario($conexion, $datosPerfil['idPerfil'], $datos['identificador']);
                                                if (pg_num_rows($qPerfil) == 0)
                                                    $cgap->guardarGestionPerfil($conexion, $datos['identificador'],$datosPerfil['codigoPerfil']);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    
                    $cr->actualizarEstadoAnteriorPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $historicoOperacion);
                    $cr->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $historicoOperacion, $estadoFlujo['estado']);
                    $cr->actualizarEstadoTipoOperacionPorIndentificadorSitio($conexion, $idOperadorTipoOperacion, $estadoFlujo['estado']);
                                        
                    $areasOperacion = implode(", ", $arrayIdArea);
                    $productosRegistrados = implode(", ", $datos['idProducto']);
                    
                    $cr->registrarLogRegistroOperadorMasivo($conexion, $_SESSION['usuario'], $datos['identificador'], $codigoSitio, $areasOperacion, $datos['tipoOperacion'], $productosRegistrados);
                    
                    $conexion->ejecutarConsulta("commit;");
                    $mensaje['estado'] = 'exito';
                    $mensaje['mensaje'] = 'Los datos han sido ingresados satisfactoriamente.';
                    
                }else{
                    
                    $mensaje['estado'] = 'error';
                    $mensaje['mensaje'] = 'No se puede agregar productos en el estado actual de la solicitud.';
                }
                
            }else{
                $mensaje['estado'] = 'error';
                if(!$seleccionarProducto){
                    $mensaje['mensaje'] = 'Seleccione al menos un producto.';
                }else{
                    $mensaje['mensaje'] = 'Los productos '.trim($productosIngresados,', ').' ya han sido ingresadas previamente en el área y operacion seleccionados.';
                }
            }
            
        }else{
            $mensaje['estado'] = 'error';
            $mensaje['mensaje'] = 'No es posible ingresar mas de un tipo de operación.';
        }
        
    } catch (Exception $ex) {
        $conexion->ejecutarConsulta("rollback;");
        $mensaje['mensaje'] = $ex->getMessage();
        $mensaje['error'] = $conexion->mensajeError;
    } finally {
        $conexion->desconectar();
    }
} catch (Exception $ex) {
    $mensaje['mensaje'] = $ex->getMessage();
    $mensaje['error'] = $conexion->mensajeError;
} finally {
    echo json_encode($mensaje);
}
?>