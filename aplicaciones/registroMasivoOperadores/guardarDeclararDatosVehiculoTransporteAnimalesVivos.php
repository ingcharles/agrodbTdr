<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorReportes.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorFirmaDocumentos.php';



$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try {

    $conexion = new Conexion();
    $cc = new ControladorCatalogos();
    $cro = new ControladorRegistroOperador();
    $cfd = new ControladorFirmaDocumentos();
    $jru = new ControladorReportes();

    $idSitio = $_POST['idSitio'];
    $idArea = $_POST['idArea'];
    $idOperacion = $_POST['idOperacion'];

    try {

        $conexion->ejecutarConsulta("begin;");

        $operacion = pg_fetch_assoc($cro->abrirOperacionXid($conexion, $idOperacion));
        $idOperadorTipoOperacion = $operacion['id_operador_tipo_operacion'];
        $idHistorialOperacion = $operacion['id_historial_operacion'];
        $idTipoOperacion = $operacion['id_tipo_operacion'];
        $procesoModificacion = $operacion['proceso_modificacion'];
        $procesoGuardar = true;
        $generarCertificado = false;

        $idflujoOperacion = pg_fetch_assoc($cro->obtenerIdFlujoXOperacion($conexion, $idOperacion));
        $idFlujoActual = pg_fetch_assoc($cro->obtenerEstadoActualFlujoOperacion($conexion, $idflujoOperacion['id_flujo_operacion'], 'declararDVehiculo'));
        $estado = pg_fetch_assoc($cro->obtenerEstadoFlujoOperacion($conexion, $idflujoOperacion['id_flujo_operacion'], $idFlujoActual['predecesor']));

        if ($operacion['modulo_provee'] == 'moduloExterno' && $estado['estado'] == 'cargarProducto') {
            $estado = pg_fetch_assoc($cro->obtenerEstadoFlujoOperacion($conexion, $idflujoOperacion['id_flujo_operacion'], $idFlujoActual['predecesor'] + 1));
        }

        $cro->actualizarEstadoAnteriorPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $idHistorialOperacion);

        switch ($estado['estado']) {

            case 'pago':
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
            case 'documental':
                $cro->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $idHistorialOperacion, $estado['estado']);
                break;
            case 'registrado':

                $tamanioContenedor = $_POST['tamanioContenedor'];
                $caracteristicaContenedor = $_POST['caracteristicaContenedor'];

                if ($procesoModificacion != "t") {

                    $placa = strtoupper($_POST['placa']);
                    $identificadorPropietario = $_POST['identificadorPropietario'];
                    $marca = $_POST['marca'];
                    $modelo = $_POST['modelo'];
                    $anio = $_POST['anio'];
                    $color = $_POST['color'];
                    $clase = $_POST['clase'];
                    $tipo = $_POST['tipo'];
                    $actualizacionFechas = true;

                    
                    $qInformacionVehiculoRegistradoOperador = $cro->obtenerDatosMedioTrasporteAnimalesVivosPorPropietarioPorPlaca($conexion, $identificadorPropietario, $placa);
                    
                    if (pg_num_rows($qInformacionVehiculoRegistradoOperador) == 0) {
                    
                        $qInformacionVehiculosRegistrados = $cro->obtenerInformacionVehiculoTransporteAnimalesVivosPorPlaca($conexion, $placa);
    
                        $qCodigoProvinciaSitio = $cro->abrirSitio($conexion, $idSitio);
                        $qCodigoProvinciaSitio = pg_fetch_assoc($qCodigoProvinciaSitio);
                        $idCodigoProvincia = str_pad($qCodigoProvinciaSitio['codigo_provincia'], 2, '0', STR_PAD_LEFT);
                        $anioCertificado = date('Y');
                        $qDatoVehiculoNuevo = pg_fetch_assoc($cro->guardarInformacionDatosVehiculoTransporteAnimalesVivos($conexion, $idArea, $idTipoOperacion, $idOperadorTipoOperacion, $idHistorialOperacion, $idCodigoProvincia, $anioCertificado, $placa, $identificadorPropietario, $marca, $modelo, $anio, $color, $clase, $tipo, $tamanioContenedor, $caracteristicaContenedor));
                        $idDatoVehiculoNuevo = $qDatoVehiculoNuevo['insertar_datos_transporte_animales_vivos'];
                        
                        if (pg_num_rows($qInformacionVehiculosRegistrados) > 0) {
    
                            while ($informacionVehiculosRegistrados = pg_fetch_assoc($qInformacionVehiculosRegistrados)) {
    
                                $idDatoVehiculoAntiguo = $informacionVehiculosRegistrados['id_dato_vehiculo_transporte_animales'];
                                $identificadorPropietarioRegistrado = $informacionVehiculosRegistrados['identificador_propietario_vehiculo'];
                                $idOperadorTipoOperacionRegistrado = $informacionVehiculosRegistrados['id_operador_tipo_operacion'];
                                $idHistorialOperacionRegistrado = $informacionVehiculosRegistrados['id_historial_operacion'];
    
                                $cro->inactivarMedioTrasporteAnimalesVivosPorIdDatoVehiculo($conexion, $idDatoVehiculoAntiguo);
                                $cro->actualizarEstadoDocumentoOperador($conexion, $identificadorPropietarioRegistrado, $idOperadorTipoOperacionRegistrado, 'inactivo');
    
                                $cro->actualizarEstadoAnteriorPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacionRegistrado, $idHistorialOperacionRegistrado);
                                $cro->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacionRegistrado, $idHistorialOperacionRegistrado, 'noHabilitado', 'Inhabilitación realizada por cambio de dueño de vehículo');
                                $cro->cambiarEstadoAreaOperacionPorPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacionRegistrado, $idHistorialOperacionRegistrado);
                                $cro->actualizarEstadoTipoOperacionPorIndentificadorSitio($conexion, $idOperadorTipoOperacionRegistrado, 'noHabilitado');
                            
                                $cro->insertarDatosVehiculoTransporteAnimalesExpirado($conexion, $idDatoVehiculoAntiguo, $idDatoVehiculoNuevo);
                            }
                        }    
                        
                    }else{
                        $procesoGuardar = false;
                        $mensaje['estado'] = 'error';
                        $mensaje['mensaje'] = "El vehículo con placa " . $placa . " ya fué registrado por el operador " . $identificadorPropietario . ".";
                    }                
                
                } else {

                    $actualizacionFechas = false;
                    $qDatosVehiculo = $cro->obtenerDatosMedioTrasporteAnimalesVivosPorIdOperadorTipoOperacion($conexion, $idOperadorTipoOperacion);
                    $qDatosVehiculo = pg_fetch_assoc($qDatosVehiculo);
                    $idDatoVehiculo = $qDatosVehiculo['id_dato_vehiculo_transporte_animales'];
                    $identificadorPropietario = $qDatosVehiculo['identificador_propietario_vehiculo'];
                    $cro->actualizarDatosMedioTrasporteAnimalesVivosPorIdDatoVehiculo($conexion, $idDatoVehiculo, $tamanioContenedor, $caracteristicaContenedor);
                }

                
                if($procesoGuardar){
                    
                    $generarCertificado = true;
                
                    $cro->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $idHistorialOperacion, $estado['estado']);
                    $cro->cambiarEstadoAreaOperacionPorPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $idHistorialOperacion);
                    $cro->actualizarProcesoActualizacionOperacion($conexion, $idOperadorTipoOperacion, $idHistorialOperacion);
    
                    if ($actualizacionFechas) {
                        $cro->actualizarFechaAprobacionOperaciones($conexion, $idOperadorTipoOperacion, $idHistorialOperacion);
                    } else {
                        $cro->actualizarFechaAprobacionOperacionesProcesoModificacion($conexion, $idOperadorTipoOperacion, $idHistorialOperacion);
                    }
    
                    $ReporteJasper = '/aplicaciones/registroOperador/reportes/transporteAnimalesVivos/transporteAnimalesVivos.jrxml';
                    $salidaReporte = '/aplicaciones/registroOperador/certificados/transporteAnimalesVivos/' . $identificadorPropietario . '-' . $idOperadorTipoOperacion . '.pdf';
                    $rutaArchivo = 'aplicaciones/registroOperador/certificados/transporteAnimalesVivos/' . $identificadorPropietario . '-' . $idOperadorTipoOperacion . '.pdf';
                    $rutaArchivoCodigoQr = 'https://guia.agrocalidad.gob.ec/' . $constg::RUTA_APLICACION . $salidaReporte;
					
					 $parameters['parametrosReporte'] = array(
    	                        	'idOperadorTipoOperacion'=> $idOperadorTipoOperacion,
    	                        	'rutaCertificado' => $rutaArchivoCodigoQr
    	                        );
    
                    $existenciaDocumento = $cro->obtenerDocumentoGeneradoPorSecuencialIdentificadorOperadorTipoDocumento($conexion, '1', $identificadorPropietario, 'transporteAnimalesVivos');
    
                    if (pg_num_rows($existenciaDocumento) > 0) {
                        $cro->eliminarDocumentoXIdOperadorTipoOperacion($conexion, $idOperacion, $idOperadorTipoOperacion);
                    }
    
                    $cro->guardarDocumentoOperador($conexion, $idOperacion, $idOperadorTipoOperacion, $rutaArchivo, 'transporteAnimalesVivos', '1', $identificadorPropietario, 'Certificación de registro de transporte de animales vivos.');

                    $conexion->ejecutarConsulta("commit;");
                }
                
                break;
        }

        if ($generarCertificado) {
            $jru->generarReporteJasper($ReporteJasper, $parameters, $conexion, $salidaReporte, 'transporteAnimalesVivos');
            
            //Tabla de firmas físicas
            $firmaResponsable = pg_fetch_assoc($cc->obtenerFirmasResponsablePorProvincia($conexion, $qCodigoProvinciaSitio['provincia'], 'SA'));
            
            $rutaArchivo = $constg::RUTA_SERVIDOR_OPT . '/' . $constg::RUTA_APLICACION . '/' .$rutaArchivo;
            
            //Firma Electrónica
            $parametrosFirma = array(
            	'archivo_entrada'=>$rutaArchivo,
            	'archivo_salida'=>$rutaArchivo,
            	'identificador'=>$firmaResponsable['identificador'],
            	'razon_documento'=>'Certificación de registro de transporte de animales vivos.',
            	'tabla_origen'=>'g_operadores.documentos_operador',
            	'campo_origen'=>'ruta_archivo',
            	'id_origen'=>$idOperacion,
            	'estado'=>'Por atender',
            	'proceso_firmado'=>'NO'
            );
            
            //Guardar registro para firma
            $cfd->ingresoFirmaDocumento($conexion, $parametrosFirma);
            
            $mensaje['estado'] = 'exito';
            $mensaje['mensaje'] = "Los datos han sido guardados satisfactoriamente";
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