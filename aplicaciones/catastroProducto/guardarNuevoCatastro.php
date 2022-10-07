 <?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastroProducto.php';
require_once '../../clases/ControladorCatalogos.php';

set_time_limit(500);
$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try {
    $conexion = new Conexion();
    $cc = new ControladorCatalogos();
    $cp = new ControladorCatastroProducto();

    $identificadorResponsable = htmlspecialchars($_POST['identificadorResponsable'], ENT_NOQUOTES, 'UTF-8');
    $arrayDetalleCatastro = json_decode($_POST['array_detalle_catastro'], true);
    $anio = date("y");
    $unidadMedidaPeso = 'Kg';

    /*
     * echo "<pre>";
     * print_r($arrayDetalleCatastro);
     * echo "<pre>";
     *
     * echo "<pre>";
     * print_r($arrayDetalleIdentificadores);
     * echo "<pre>";
     */
    $arrayErrorIdentificadorProducto = false;
    
    foreach ($arrayDetalleCatastro as $itemDc) {
        if (($itemDc['hIdentificador'] == 'checked' && $itemDc['hRango'] == 'checked') || ($itemDc['hIdentificador'] == 'checked' && $itemDc['hRango'] == '')) {
            $arrayIdentificadores = "ARRAY['" . implode("', '", $itemDc["arrayIdentificadores"]) . "']";
            $cantidadAreteUtilizado = pg_fetch_result($cc->buscarRangoSerieArete($conexion, 6, $arrayIdentificadores, "utilizado"),0,'cantidad_arete');
            
            if ($cantidadAreteUtilizado > 0) {
                
                $arrayErrorIdentificadorProducto = true;
                break;
                
            }else{
                
                $cantidadAreteCreado = pg_fetch_result($cc->buscarRangoSerieArete($conexion, 6, $arrayIdentificadores, "creado"),0,'cantidad_arete');
       
                if ($cantidadAreteCreado != count($itemDc["arrayIdentificadores"])) {

                    $arrayErrorIdentificadorProducto = true;
                    break;
                   
                }
            }
        }
    }
    
    if ($arrayErrorIdentificadorProducto) {
        $mensaje ['estado'] = 'error';
        $mensaje ['mensaje'] =  'Los identificadores son incorrectos o están utilizados.';
    }else{
        try {
    
            $conexion->ejecutarConsulta("begin;");
    
            foreach ($arrayDetalleCatastro as $itemDc) {
    
                // INICIO CONTROL REPRODUCCION MADRES PORCINOS
                $idProductoReproduccion = pg_fetch_result($cp->obtenerIdProductoXCodigoProducto($conexion, 'PORDRE'), 0, 'id_producto');
                $idProductoLechon = pg_fetch_result($cp->obtenerIdProductoXCodigoProducto($conexion, 'PORHON'), 0, 'id_producto');
                $idProductoLechona = pg_fetch_result($cp->obtenerIdProductoXCodigoProducto($conexion, 'PORONA'), 0, 'id_producto');
    
                $identificadorOperador = pg_fetch_result($cp->abrirSitio($conexion, $itemDc['hSitio']), 0, 'identificador_operador');
                $qObtenerMaximoControlReproduccion = $cp->obtenerMaximoControlReproduccion($conexion, $identificadorOperador, $idProductoReproduccion);
                $qCantidadCatastro = $cp->obtenerCantidadCatastroXOperador($conexion, $identificadorOperador, '(' . $idProductoReproduccion . ')');
                $qCantidadCatastroCrias = $cp->obtenerCantidadCatastroXOperador($conexion, $_POST['identificadorOperador'], '(' . $idProductoLechon . ',' . $idProductoLechona . ')');
                $cantidadCria = pg_fetch_result($qCantidadCatastroCrias, 0, 'cantidad');
    
                if ($itemDc['hCodigoProducto'] == 'PORDRE') {
    
                    $cantidadReproduccion = $itemDc['hCantidad'] * 28;
    
                    if (pg_num_rows($qObtenerMaximoControlReproduccion) != 0) {
                        $cupoCria = pg_fetch_result($qObtenerMaximoControlReproduccion, 0, 'cupo_cria') + $cantidadReproduccion;
                        $cantidadCriaB = pg_fetch_result($qObtenerMaximoControlReproduccion, 0, 'cantidad_cria');
                    } else {
                        $cantidadCriaB = $cantidadCria;
                        $cupoCria = (pg_fetch_result($qCantidadCatastro, 0, 'cantidad') * 28) + $cantidadReproduccion;
                    }
                    if ($cupoCria < 0) {
                        $cupoCria = 0;
                    }
    
                    $cp->guardarControlReproduccion($conexion, $identificadorOperador, $idProductoReproduccion, $cupoCria, $cantidadCriaB);
                } else if ($itemDc['hCodigoProducto'] == 'PORHON' || $itemDc['hCodigoProducto'] == 'PORONA') {
    
                    $cantidadReproduccion = $itemDc['hCantidad'];
                    $cantidadMadre = pg_fetch_result($qCantidadCatastro, 0, 'cantidad');
    
                    if (pg_num_rows($qObtenerMaximoControlReproduccion) != 0) {
                        $cupoCria = pg_fetch_result($qObtenerMaximoControlReproduccion, 0, 'cupo_cria') - $cantidadReproduccion;
                        $cantidadCriaB = pg_fetch_result($qObtenerMaximoControlReproduccion, 0, 'cantidad_cria') + $cantidadReproduccion;
                    } else {
                        $cupoCria = 14 + ($cantidadMadre * 28) - $cantidadCria - $cantidadReproduccion;
                        $cantidadCriaB = $cantidadCria + $cantidadReproduccion;
                    }
    
                    if ($cupoCria < 0) {
                        $cupoCria = 0;
                    }
    
                    $cp->guardarControlReproduccion($conexion, $identificadorOperador, $idProductoReproduccion, $cupoCria, $cantidadCriaB);
                }
    
                // FIN CONTROL REPRODUCCION MADRES PORCINOS
    
                $qObtenerIdEspecie = $cc->obtenerEspecieXcodigo($conexion, $itemDc['hCodigoEspecie']);
                $qResultadoNombreProducto = $cc->obtenerNombreProducto($conexion, $itemDc['hProducto']);
    
                $qCatastro = $cp->guardarCatastroProducto($conexion, $itemDc['hSitio'], $itemDc['hIdArea'], $itemDc['hProducto'], pg_fetch_result($qResultadoNombreProducto, 0, 'nombre_comun'), $itemDc['hCantidad'], $itemDc['hUnidadComercial'], $identificadorResponsable, $unidadMedidaPeso, pg_fetch_result($qObtenerIdEspecie, 0, 'id_especies'), $itemDc['hFechaNacimiento'], $itemDc['hNumeroLote'], $itemDc['hDiasInicioEtapa'], $itemDc['hDiasFinEtapa'], $itemDc['hAreaTematica'], $itemDc['hOperacion']);
                $idCatastro = pg_fetch_result($qCatastro, 0, 'id_catastro');
                if ($itemDc['hIdentificador'] == '') {
                    // "sin identificador- identificador asignado por sistema";
    
                    $qVericarSecuencial = $cp->verificarSecuenciaCatastro($conexion, $itemDc['hIdArea'], $anio);
                    $verificarSecuencial = pg_fetch_result($qVericarSecuencial, 0, 'secuencia_final');
    
                    // Verifica si existe la serie en la tabla de secuencia
                    if ($verificarSecuencial != null || $verificarSecuencial != '') {
    
                        // Obtengo la secuencia final por año y área de la tabla de secuencias
                        $secuencialInicial = $verificarSecuencial;
                        $secuencialFinal = $secuencialInicial + $itemDc['hCantidad'];
                    } else {
    
                        // Obtengo la secuencia final por año y área de la tabla de detalle de catastros
                        $secuencialInicial = $cp->autogenerarSecuencialDetalleCatastroProducto($conexion, $itemDc['hIdArea']);
                        $secuencialFinal = $secuencialInicial + $itemDc['hCantidad'];
                    }
    
                    // Inserto la secuencia en la tabla de secuencias
                    $cp->insertarSecuencialCatastroSecuencia($conexion, $itemDc['hIdArea'], $anio, $secuencialInicial, $secuencialFinal - 1);
                    $valores = "";
                    for ($k = $secuencialInicial; $k < $secuencialFinal; $k ++) {
                    
                        $identificadorUnico = $itemDc['hIdArea'] . '-' . date('y') . '-' . str_pad($k, 6, "0", STR_PAD_LEFT);
                        $valores .= "(" . $idCatastro . ", null, " . $k . ", '" . $identificadorUnico . "', 'activo'),";
                    
                    }
                    // Guardo el detalle del catastro con identificados identificadores unicos del sistema
                    $cp->guardarDetalleCatastroProductoNRegistros($conexion, rtrim($valores, ","));
                    
                } else if (($itemDc['hIdentificador'] == 'checked' && $itemDc['hRango'] == 'checked') || ($itemDc['hIdentificador'] == 'checked' && $itemDc['hRango'] == '')) {
    
                    $qVericarSecuencial = $cp->verificarSecuenciaCatastro($conexion, $itemDc['hIdArea'], $anio);
                    $verificarSecuencial = pg_fetch_result($qVericarSecuencial, 0, 'secuencia_final');
    
                    // Verifica si existe la serie en la tabla de secuencia
                    if ($verificarSecuencial != null || $verificarSecuencial != '') {
    
                        $secuencialInicial = $verificarSecuencial;
                        $secuencialFinal = $secuencialInicial + $itemDc['hCantidad'];
                    } else {
    
                        // Obtengo la secuencia final por año y área de la tabla de detalle de catastros
                        $secuencialInicial = $cp->autogenerarSecuencialDetalleCatastroProducto($conexion, $itemDc['hIdArea']);
                        $secuencialFinal = $secuencialInicial + $itemDc['hCantidad'];
                    }
    
                    // Inserto la secuencia en la tabla de secuencias
                    $cp->insertarSecuencialCatastroSecuencia($conexion, $itemDc['hIdArea'], $anio, $secuencialInicial, $secuencialFinal - 1);
    
                    $arrayIdentificadores = str_replace(array('[',']'), '', $itemDc["arrayIdentificadores"]);
    
                    $valores = "";
                    foreach ($arrayIdentificadores as $identificadorArete) {
                        
                        $identificadorUnico = $itemDc['hIdArea'] . '-' . date('y') . '-' . str_pad($secuencialInicial, 6, "0", STR_PAD_LEFT);
                        $valores .= "(" . $idCatastro . ", '" . $identificadorArete . "', " . $secuencialInicial . ", '" . $identificadorUnico . "', 'activo'),";
                        $secuencialInicial ++;
                    }
                    
                    $identificadoresProducto = "ARRAY['" . implode("', '", $itemDc["arrayIdentificadores"]) . "']";
    
                    // Guardo el detalle del catastro con identificados aretes - codigos EC
                    $cp->guardarDetalleCatastroProductoNRegistros($conexion, rtrim($valores, ","));
                    
                    
                    // Actualizo los identificados aretes - codigos EC
                    $cp->actualizarIdentificadorProductoNRegistros($conexion, $identificadoresProducto, 6, 'utilizado');
                }
    
                // TODO: Busco el ultima transaccion de catastro para sacar la cantidad total
                $qConsultarCantidadTotalProducto = $cp->consultarCantidadTotalProducto($conexion, $itemDc['hIdArea'], $itemDc['hProducto'], $itemDc['hUnidadComercial'], $itemDc['hOperacion']);
                
                $cantidadTotal = $itemDc['hCantidad'] + (pg_num_rows($qConsultarCantidadTotalProducto) != 0 ? pg_fetch_result($qConsultarCantidadTotalProducto, 0, 'cantidad_total') : 0);
    
                // TODO: Busco el concepto del catstro del tipo de transacion a realizar
                $qConsultaConceptoCatastroXCodigo = $cp->consultaConceptoCatastroXCodigo($conexion, 'RECA');
                $fila = pg_fetch_assoc($qConsultaConceptoCatastroXCodigo);
                
                // TODO: Guarda los datos de la transacion total de catastro
                $cp->guardarCatastroTransaccion($conexion, $idCatastro, $itemDc['hIdArea'], $fila['id_concepto_catastro'], $itemDc['hProducto'], $itemDc['hCantidad'], $cantidadTotal, $itemDc['hUnidadComercial'], $identificadorResponsable, $itemDc['hOperacion']);
    
                
            }
            $conexion->ejecutarConsulta("commit;");
            
            $mensaje['estado'] = 'exito';
            $mensaje['mensaje'] = 'Los datos han sido ingresados satisfactoriamente.';
        } catch (Exception $ex) {
            $conexion->ejecutarConsulta("rollback;");
            $errorMensaje = $ex->getMessage();
			$errorSql = strpos($conexion->mensajeError, 'ERROR:  llave duplicada');
			if($errorSql !== false){
				$errorMensaje = "Ya se esta ejecutando una transacción sobre el área de operación. Por favor vuelva a intentar.";
			}			
            $mensaje['mensaje'] = $errorMensaje;
            $mensaje['error'] = $conexion->mensajeError;
        } finally {
            $conexion->desconectar();
        }
    }
} catch (Exception $ex) {
    $mensaje['mensaje'] = $ex->getMessage();
    $mensaje['error'] = $conexion->mensajeError;
} finally {
    echo json_encode($mensaje);
}
?>