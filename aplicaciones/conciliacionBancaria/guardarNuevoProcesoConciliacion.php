<?php
session_start();

$imprimirArray = false;

require_once '../general/PHPExcel.php';
require_once 'xlsxwriter.class.php';
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorConciliacionBancaria.php';
require_once '../../clases/ControladorFinanciero.php';

set_time_limit(3600);

function fechaFormateada($campoValor)
{
    $dia = substr($campoValor, 0, 2);
    $mes = substr($campoValor, 2, 2);
    $anio = substr($campoValor, 4, 4);
    
    $res = $dia . '/' . $mes . '/' . $anio;
    
    return $res;
}

function convertirNumero($numero)
{
    $entero = intval(substr($numero, 0, 18));
    $decimal = intval(substr($numero, 18, 19));
    
    $total = $entero . "." . $decimal;
    
    return ($total);
}

function convertirNumeroDocumento($numero)
{
    
    // $dato = substr($numero, 1);
    // $dato = substr('$', '', $numero);
    $dato = str_replace(',', '.', $numero);
    
    return $dato;
}

function strposArr($haystack, $needle)
{
    if (! is_array($needle))
        $needle = array(
            $needle
        );
    foreach ($needle as $what) {
        if (($pos = strpos($haystack, $what)) !== false)
            return $what;
    }
    return false;
}

$conexion = new Conexion();
$cc = new ControladorCatalogos();
$cb = new ControladorConciliacionBancaria();
$cf = new ControladorFinanciero();

$idRegistroProcesoConciliacion = $_POST['idRegistroProcesoConciliacion'];
$anioProcesoConciliacionInicio = $_POST['anioProcesoConciliacionInicio'];
$mesProcesoConciliacionInicio = str_pad($_POST['mesProcesoConciliacionInicio'], 2, "0", STR_PAD_LEFT);
$diaProcesoConciliacionInicio = str_pad($_POST['diaProcesoConciliacionInicio'], 2, "0", STR_PAD_LEFT);

$anioProcesoConciliacionFin = $_POST['anioProcesoConciliacionFin'];
$mesProcesoConciliacionFin = str_pad($_POST['mesProcesoConciliacionFin'], 2, "0", STR_PAD_LEFT);
$diaProcesoConciliacionFin = str_pad($_POST['diaProcesoConciliacionFin'], 2, "0", STR_PAD_LEFT);

$totalRecaudado = $_POST['totalRecaudado'];

$banderaNoHaytramasGuia = array();

$banderaNoHayDocumentosGuia = array();

$resultado = array();

$fechaConciliacionInicio = ($diaProcesoConciliacionInicio . '/' . $mesProcesoConciliacionInicio . '/' . $anioProcesoConciliacionInicio);
$fechaConciliacionFin= ($diaProcesoConciliacionFin . '/' . $mesProcesoConciliacionFin . '/' . $anioProcesoConciliacionFin);


$fechaConciliacion = date( 'Y/m/d');

list($anioProcesoConciliacion, $mesProcesoConciliacion, $diaProcesoConciliacion) = explode("/", $fechaConciliacion);

$bancosProcesoConciliacion = $_POST['hBancoProcesoConciliacion'];
$rutasProcesoConciliacion = $_POST['hRutaArchivo'];

$qIdProcesoCociliacion = $cb->guardarProcesoConciliacion($conexion, $idRegistroProcesoConciliacion, $anioProcesoConciliacion, $mesProcesoConciliacion, $diaProcesoConciliacion, $totalRecaudado);
$idProcesoConciliacion = pg_fetch_result($qIdProcesoCociliacion, 0, 'id_proceso_conciliacion');

foreach ($bancosProcesoConciliacion as $idBancoProcesoConciliacion => $valor) {
    
    $cb->guardarTotalBancoProcesoConciliacion($conexion, $idProcesoConciliacion, $idBancoProcesoConciliacion, $valor);
}

foreach ($rutasProcesoConciliacion as $idDocumentoProcesoConciliacion => $ruta) {
    
    $cb->guardarRutaDocumentoProcesoConciliacion($conexion, $idProcesoConciliacion, $idDocumentoProcesoConciliacion, $ruta);
}

// Variables para mostrar resultados

$numeroFacturasGuia = 0;
$numeroFacturasConciliadas = 0;
$numeroFacturasNoConciliadas = 0;
$numeroFacturasConciliadasDiasAnteriores = 0;
$numeroTransaccionesTrama = 0;
$numeroTransaccionesDocumento = 0;
$numeroTransaccionesConciliadasGuiaTrama = 0;
$numeroTransaccionesConciliadasGuiaDocumento = 0;
$numeroTransaccionesBancoPacifico = 0;
$numeroTransaccionesConciliadasGuiaBancoPacifico = 0;

// Fin variables para mostrar resultados

// Contadores para los arrays de tramas y de documentos

$contadorTrama = 0;
$contadorDocumento = 0;
$contadorGeneral = 0;

// Obtiene el id del registro de proceso de conciliacion
// echo "Proceso conciliacion".$idProcesoConciliacion."<br>";

// echo "<br><br>";
// Se obtienen las rutas de los documentos a utilizar para el proceso de conciliacion // Revisar esta función si esta trayendo lo que se necesita

$qRutaDocumento = $cb->obtenerDocumentosRutasProcesoConciliacionXIdRegistroProcesoConciliacion($conexion, $idProcesoConciliacion/*$procesoConciliacion['id_registro_proceso_conciliacion']*/);

// echo "<br><br>";

// Obtiene las facturas de guia generadas en la fecha de la conciliacion

$arrayOrdenesGuia = array();

$arrayTramas = array();

$arrayDocumentos = array();

$arrayCodigoVUENoConciliadas = array();

$qCamposOrdenPagoGuia = $cf->abrirCamposOrdenPagoXFechaFacturacion($conexion, $fechaConciliacionInicio, $fechaConciliacionFin);
$arrayOrdenesGuia = pg_fetch_all_columns($qCamposOrdenPagoGuia, 0);

// echo "<br><br>";

$arrayPrincipalGuia = array();
$arrayGuiaTP = array();
$arrayGuiaTS = array();
$arrayGuiaTP += array(
    'facturaGuia' => 'Factura GUIA'
);
$arrayPrincipalGuia['guia']['tituloPrincipal'] = $arrayGuiaTP;

$arrayDetalleGuia = array();

// Fin de variables para array principal de las ordenes que esisten en Guia

// Trae las ordenes de pago de guia generadas dentro del rango de la fecha de conciliacion.
$arrayOrdenesGeneradasGuia = array();
$arrayOrdenesGeneradasGuia = $cf->abrirOrdenPagoPorFechaConciliacion($conexion, $fechaConciliacionInicio, $fechaConciliacionFin);

// Cuenta el numero de facturas que existen en guia en la fecha de conciliacion
$numeroFacturasGuia = pg_num_rows($qCamposOrdenPagoGuia);

// echo "numero_facturasguia:".$numeroFacturasGuia;

if ($numeroFacturasGuia == 0) {
    $ordenesGuiaVue = false;
} else {
    $ordenesGuiaVue = true;
}

switch ($ordenesGuiaVue) {
    
    case false: // SE verifica si existen datos en GUIA Y TRAMA
                // echo "No existen registros con la fecha ".$fechaConciliacion;
                
        // if($banderaNoHaytramasGuiaX!=true || $banderaNoHaytramasGuia!=true){
        $cb->eliminarBorrarRutasProcesoConciliacion($conexion, $idProcesoConciliacion);
        $cb->eliminarBorrarProcesoConciliacion($conexion, $idProcesoConciliacion);
        
        // }
        
        echo "<header><h1>Proceso de conciliación</h1></header>
				<div></br><b>No existen registros con la fecha " . $fechaConciliacion;
        
        break;
    
    case true: // SE verifica si existen datos en GUIA Y TRAMA
        
        $imprimirArray = true; // QUITAR ESTO
                               
        // Forma el array de las ordenes de GUIA para mostrar la informacion en la tabla de excel
        foreach ($arrayOrdenesGeneradasGuia as $keyOrdenesGeneradasGuia => $valorOrdenesGeneradasGuia) {
            
            $contadorGuia ++;
            
            if ($contadorGuia == 1) {
                
                $arrayGuiaTS['facturaGuia'] = array(
                    'numero_factura' => 'numero_factura_guia',
                    'numero_orden_vue' => 'numero_orden_pago_factura',
                    'total_pagar' => 'monto_recaudacion',
                    'tipo_liquidacion' => 'tipo_liquidacion',
                    'fecha_hora_pago_vue' => 'fecha_hora_pago_vue'
                );
                $arrayPrincipalGuia['guia']['tituloSecundario'] = $arrayGuiaTS;
            }
            
            foreach ($arrayGuiaTS['facturaGuia'] as $llave => $valor) {
                
                foreach ($valorOrdenesGeneradasGuia as $keyOrdenesGeneradasGuiaX1 => $valorOrdenesGeneradasGuiaX1) {
                    
                    if ($keyOrdenesGeneradasGuiaX1 == $llave) {
                        
                        $arrayDetalleGuia['facturaGuia'][$llave] = ($valorOrdenesGeneradasGuiaX1 == '') ? '' : $valorOrdenesGeneradasGuiaX1;
                    }
                }
            }
            
            $arrayPrincipalGuia['guia'][] = $arrayDetalleGuia;
        }
        
        // Recorre los documentos registrados para el proceso de conciliacion
        
        while ($rutaDocumento = pg_fetch_assoc($qRutaDocumento)) {
            
            $formatoDocumento = $rutaDocumento['formato_documento_entrada_proceso_conciliacion'];
            $tipoDocumento = $rutaDocumento['tipo_documento_proceso_conciliacion'];
            $nombreDocumento = $rutaDocumento['nombre_documento_entrada_proceso_conciliacion'];
            $ruta = $rutaDocumento['ruta_documento_proceso_conciliacion'];
            $idDocumento = $rutaDocumento['id_documento_entrada_proceso_conciliacion'];
            
            switch ($tipoDocumento) { // Verifica si es documento o trama
                
                case 'trama':
                    
                    $arrayTramas[] = array(
                        'tipoDocumento' => $tipoDocumento,
                        'formatoDocumento' => $formatoDocumento,
                        'rutaDocumento' => $ruta,
                        'nombreDocumento' => $nombreDocumento
                    ); // arrayTramas
                    
                    $arrayFilaCampos = array();
                    
                    $arrayTotalesTrama = array();
                    
                    $arrayTotalesGuiaTrama = array();
                    
                    switch ($formatoDocumento) { // Verifica el formato del documento .txt
                        
                        case 'txt':
                            
                            $contadorTrama ++;
                            $contadorGeneral ++;
                            
                            ${arrayPrincipalTrama . $contadorTrama} = array();
                            ${arrayPrincipalTrama . $contadorTrama}['campoCabecera'] = 'valor por definir';
                            
                            // Variables para crear array de trama
                            
                            $arrayDetalleTP = array();
                            $arrayDetalleTS = array();
                            
                            $arrayDetalleTP += array(
                                'trama' => $nombreDocumento
                            );
                            $arrayDetalleTP += array(
                                'resumen' => 'Resumen trama'
                            );
                            
                            ${arrayPrincipalTrama . $contadorTrama}['campoDetalle']['tituloPrincipal'] = $arrayDetalleTP;
                            
                            $arrayFilaCampos = array();
                            $arrayFilaCamposDetalle = array();
                            
                            // Fin variables para crear array de trama
                            
                            ${arrayFilaCamposNoGuia . $contadorTrama} = array(); // array para campos que no estan en GUIA
                            
                            $contadorFilaDetalle = 0;
                            
                            $arrayPrincipalTramaNoGuia = array();
                            
                            // Variables para crear el array de la lectura general de la tabla
                            
                            ${arrayPrincipalGeneral . $contadorTrama} = array();
                            $arrayGeneralTP = array();
                            $arrayGeneralTS = array();
                            
                            $arrayGeneralTP += array(
                                'lecturaTrama' => 'Lectura documento de trama'
                            );
                            
                            ${arrayPrincipalGeneral . $contadorTrama}['general']['tituloPrincipal'] = $arrayGeneralTP;
                            
                            $contadorTramaDetalle = 0;
                            
                            // Fin variables para crear el array de la lectura general de la tabla
                            
                            // Ruta del archivo a cargar // TODO: REVISAR PARA CARGAR LA RUTA
                            
                            $tramasNoConciliadas = fopen("documentos/tramasNoConciliadas.txt", "a+");
                            
                            while (! feof($tramasNoConciliadas)) {
                                
                                $archivo = fopen("../../" . $ruta, "a");
                                
                                $linea = fgets($tramasNoConciliadas);
                                
                                fwrite($archivo, $linea);
                                
                                fclose($archivo);
                            }
                            
                            fclose($tramasNoConciliadas);
                            unlink("documentos/tramasNoConciliadas.txt");
                            
                            // Fin Ruta del archivo a cargar
                            
                            // echo $ruta;
                            
                            $archivoEliminarDuplicadoTrama = file("../../" . $ruta);
                            $archivoEliminarDuplicadoTrama = array_unique($archivoEliminarDuplicadoTrama);
                            fclose("../../" . $ruta);
                            
                            $archivo = fopen("../../" . $ruta, "w+");
                            
                            foreach ($archivoEliminarDuplicadoTrama as $lineaArchivo) {
                                // echo $lineaArchivo;
                                fwrite($archivo, $lineaArchivo);
                            }
                            
                            fclose($archivo);
                            
                            $archivo = fopen("../../" . $ruta, "r");
                            
                            $qDatosDetalle = $cb->obtenerDatosDetalleTrama($conexion, $idProcesoConciliacion);
                            $datosDetalle = pg_fetch_assoc($qDatosDetalle);
                            
                            // //Se obtiene de todos los campos generales que fueron registrados en trama como array
                            
                            $qDatosCamposComparar = $cb->abrirDatosCamposCompararXIdProcesoConciliacionXXX($conexion, $idProcesoConciliacion, $datosDetalle['codigo_segmento_detalle_trama']); // TODO:REVISAR LA CONSULTA
                                                                                                                                                                                               
                            // Consulta que tre todos los campos registrados en la trama
                            
                            $qDatosCamposDetalle = $cb->abrirDatosCamposDetalleXIdProcesoConciliacion($conexion, $idProcesoConciliacion, $datosDetalle['codigo_segmento_detalle_trama']);
                            
                            while (! feof($archivo)) {
                                
                                if ($linea = fgets($archivo)) {
                                    
                                    $tipoFila = strpos($linea, $datosDetalle['codigo_segmento_detalle_trama']);
                                    
                                    if ($tipoFila !== FALSE) {
                                        
                                        $numeroTransaccionesTrama ++;
                                        
                                        $arrayCampos = array();
                                        $arrayCamposDetalle = array();
                                        
                                        $banderaValorEncontrado = false;
                                                                                
                                        foreach ($qDatosCamposComparar as $keyTrama => $valorTrama) {
                                            
                                            $tipoCampo = $valorTrama['tipo_campo_proceso_conciliacion'];
                                            $codigoSegmento = $valorTrama['codigo_segmento_detalle_trama'];
                                            $idCampo = $valorTrama['id_campo_documento_comparar_proceso_conciliacion'] . '<br>';
                                            $idDocumento = $valorTrama['id_documento_proceso_conciliacion'];
                                            $posicionInicial = $valorTrama['posicion_inicial_campo_detalle'];
                                            $longitudSegmento = $valorTrama['longitud_segmento_campo_detalle'];
                                            $campoComparar = $valorTrama['campo_guia_comparar_proceso_conciliacion'];
                                            $nombreCampoXX = $valorTrama['nombre_campo_detalle'];                               	 
                                            	
                                            $qObtenerDatosBanco = $cb->obtenerDatosCampoBanco($conexion, $idProcesoConciliacion, $codigoSegmento);
                                            	$obtenerDatosBanco = pg_fetch_assoc($qObtenerDatosBanco);
                                            	
                                            	$codigoS = $obtenerDatosBanco['codigo_segmento_detalle_trama'];
                                            	
                                            	$tipo = "campoDetalle";
                                            	
                                            	$posicionI = $obtenerDatosBanco['posicion_inicial_campo_detalle'];
                                            	$longitudS = $obtenerDatosBanco['longitud_segmento_campo_detalle'];
                                            	
                                            	switch ($tipo) {
                                            	    
                                            	    case 'campoDetalle':
                                            	       
                                            	        $tamanio = strlen($codigoS); // Obtiene el tamanio del codigo del segmento
                                            	        $posicion = strpos($linea, $codigoS);
                                            	        
                                            	        if ($posicion !== FALSE && $posicion == 0) {
                                            	            
                                            	           $valorCampo = substr($linea, ($posicionI - 1), $longitudS);
                                            	           //echo "<br>";
                                                        	
                                                        	$qCampoBanco = $cb->abrirCampoDetalle($conexion, $valorTrama['id_detalle_trama'], 'banco', $valorCampo);
                                                        	$campoBanco = pg_fetch_result($qCampoBanco,0,'nombre_catalogo_campo_detalle');
                                            	
                                                            	//echo $campoBanco.'<br>';
                                                            	
                                                            	$arrayCampos += array(
                                                            	    'banco' => $campoBanco
                                                            	);
                                            	        }
                                            	}
                                            
                                            switch ($tipoCampo) {
                                                
                                                case 'campoDetalle':
                                                    
                                                    $tamanio = strlen($codigoSegmento); // Obtiene el tamanio del codigo del segmento
                                                    $posicion = strpos($linea, $codigoSegmento);
                                                    
                                                    if ($posicion !== FALSE && $posicion == 0) {
                                                        
                                                        $valorCampo = substr($linea, ($posicionInicial - 1), $longitudSegmento);
                                                        
                                                        switch ($campoComparar) {
                                                            
                                                            case 'fecha_facturacion':
                                                                $valorCampo = fechaFormateada($valorCampo);
                                                                break;
                                                            
                                                            case 'total_pagar':
                                                                $valorCampo = convertirNumero($valorCampo);
                                                                break;
                                                        }
                                                        
                                                        // echo '<br>'.$nombreCampoXX.'--'.$campoComparar;
                                                        
                                                        $arrayCampos += array(
                                                            $campoComparar => $valorCampo
                                                        );
                                                        
                                                        // TODO:AQUI VERIFICAR SI HAY CAMPOS DE NUMERO ORDEN DE VUE->SI NO HAY MANDAR MENSAJE
                                                        
                                                        // Busca el índice donde se encuentra la orden de pago dentro de GUIA por el numero_orden_vue
                                                        
                                                        $indice = array_search($valorCampo, $arrayOrdenesGuia, true);
                                                        
                                                        if ($indice !== FALSE && $indice >= 0) {
                                                            
                                                            $indiceArray = $indice;
                                                            $banderaValorEncontrado = true;
                                                        }
                                                    }
                                                    
                                                    break;
                                            }
                                        }
                                        
                                        if ($banderaValorEncontrado) {
                                            
                                            $arrayFilaCampos[$indiceArray] = $arrayCampos;
                                            
                                            $banderaNoHaytramasGuia[] = true;
                                        } else {
                                            
                                            $banderaNoHaytramasGuia[] = false;
                                            
                                            $arrayCampos += array(
                                                'estado' => 'no conciliado'
                                            );
                                            ${arrayFilaCamposNoGuia . $contadorTrama}[] = $arrayCampos;
                                        }
                                        
                                        ksort($arrayFilaCampos);
                                        
                                        // Se recorre el array de todos los campos generales que fueron registrados en trama
                                        
                                        foreach ($qDatosCamposDetalle as $keyTramaDetalle => $valorTramaDetalle) {
                                            
                                            $codigoSegmentoDetalle = $valorTramaDetalle['codigo_segmento_detalle_trama'];
                                            $posicionInicialDetalle = $valorTramaDetalle['posicion_inicial_campo_detalle'];
                                            $longitudSegmentoDetalle = $valorTramaDetalle['longitud_segmento_campo_detalle'];
                                            $campoDetalle = $valorTramaDetalle['nombre_campo_detalle'];
                                            
                                            $idCampoDetalle = $valorTramaDetalle['id_campo_detalle'];
                                            
                                            // $idNombre = $valorTramaDetalle['id_campo_detalle'];
                                            
                                            // switch($tipoCampo){
                                            
                                            // case 'campoCabecera':
                                            
                                            // break;
                                            
                                            // case 'campoDetalle':
                                            
                                            $tamanioDetalle = strlen($codigoSegmentoDetalle); // Obtiene el tamanio del codigo del segmento
                                            $posicionDetalle = strpos($linea, $codigoSegmentoDetalle);
                                            
                                            if ($posicionDetalle !== FALSE && $posicionDetalle == 0) {
                                                
                                                $valorCampoDetalle = substr($linea, ($posicionInicialDetalle - 1), $longitudSegmentoDetalle);
                                                
                                                $catalogoCampo = $cb->abrirDatoscatalogosCampos($conexion, $idCampoDetalle, $valorCampoDetalle);
                                                
                                                if (pg_num_rows($catalogoCampo) != 0) {
                                                    
                                                    $valorCatalogoCampo = pg_fetch_array($catalogoCampo); // Puede fucnionar pg_fetch_all($catalogoCampo)-> toca poner posicion cero abajo
                                                    
                                                    $valorCampoDetalle = $valorCatalogoCampo['nombre_catalogo_campo_detalle'];
                                                }
                                                
                                                $arrayCamposDetalle += array(
                                                    $campoDetalle => $valorCampoDetalle
                                                );
                                            }
                                            
                                            // break;
                                            
                                            // }
                                        }
                                        
                                        // Se crea el array de todos los campos generales que fueron registrados en trama
                                        
                                        $arrayFilaCamposDetalle[] = $arrayCamposDetalle;
                                    }
                                }
                            }
                            
                            $resultado = array_unique($banderaNoHaytramasGuia);
                            
                            if (count($resultado) == 1) {
                                
                                if ($resultado[0] == false) {
                                    
                                    $banderaNoHaytramasGuiaX = false;
                                } else {
                                    
                                    $banderaNoHaytramasGuiaX = true;
                                }
                            } else {
                                
                                $banderaNoHaytramasGuiaX = true;
                            }
                            
                            $arrayTotalesTrama[$nombreDocumento] = $numeroTransaccionesTrama; // XXX
                                                                                              
                            // Se desarrolla el array de todos los campos generales registrados en tramas
                            
                            foreach ($arrayFilaCamposDetalle as $keyFilaTramaDetalle => $valorFilaTramaDetalle) {
                                
                                $contadorTramaDetalle ++;
                                
                                foreach ($valorFilaTramaDetalle as $keyTramaDetalle => $valorTramaDetalle) {
                                    
                                    // echo $valorTramaDetalle.'<br>';
                                    
                                    if ($contadorTramaDetalle == 1) {
                                        
                                        $arrayGeneralTS['lecturaTrama'][] = $keyTramaDetalle;
                                    }
                                    
                                    $arrayDetalleGeneral['lecturaTrama'][$keyTramaDetalle] = $valorTramaDetalle;
                                }
                                
                                if ($contadorTramaDetalle == 1) {
                                    
                                    ${arrayPrincipalGeneral . $contadorTrama}['general']['tituloSecundario'] = $arrayGeneralTS;
                                }
                                
                                ${arrayPrincipalGeneral . $contadorTrama}['general'][$keyFilaTramaDetalle] = $arrayDetalleGeneral;
                            }
                            
                            // Se recorre los campos que estan presentes en guia y trama
                            
                            if ($banderaNoHaytramasGuiaX) {
                                
                                foreach ($arrayFilaCampos as $keyFilaTrama => $valorFilaTrama) {
                                    
                                    // echo 'Número de orden de vue: '.$valorFilaTrama['numero_orden_vue'].'<br>';
                                    
                                    $contadorFilaDetalle ++;
                                    
                                    // Se obtienen los valores de las ordenes de pago por numero_orden_vue
                                    
                                    $qCamposOrdenPago = $cf->abrirCamposOrdenPagoXFechaFacturacionXNumeroOrdenVue($conexion, $fechaConciliacionInicio, $fechaConciliacionFin, $valorFilaTrama['numero_orden_vue']);
                                    
                                    if (pg_num_rows($qCamposOrdenPago) == 1) {
                                        
                                        $filaCampoGuia = pg_fetch_all($qCamposOrdenPago);
                                        
                                        $banderaVCValido = true;                                        
                                       
                                        foreach ($valorFilaTrama as $keysd => $valor1d) {
                                                                                        
                                            $banderaNoEncontrado = true;
                                            
                                            if ($contadorFilaDetalle == 1) {                                                
                                                                                               
                                                $keyCambiadoATrama = $keysd;                                                
                                                                                               
                                                foreach ($qDatosCamposComparar as $keyTrama => $valorTrama) {
                                                    
                                                    if ($valorTrama['campo_guia_comparar_proceso_conciliacion'] == $keysd) {
                                                        $keyCambiadoATrama = $valorTrama['nombre_campo_detalle'];
                                                    }
                                                }
                                                
                                                $arrayDetalleTS['trama'][] = $keyCambiadoATrama;
                                            }
                                            
                                            foreach ($filaCampoGuia[0] as $keys => $valors) {
                                                
                                                // Se verifica si las llaves son las mismas
                                                
                                                
                                                if($keysd == "banco"){                                                  
                                                    
                                                    $arrayDetalleValorCampo['trama'][$keysd] = $valor1d;
                                                }
                                                
                                                if ($keysd == $keys) {
                                                    
                                                    // Se verifica si los valores son los mismos
                                                    
                                                    if ($valor1d == $valors) {
                                                        
                                                        $valorDiferencia = $valors - $valor1d;
                                                        
                                                        if ($valorDiferencia == 0) {
                                                            $valorDiferencia = '0';
                                                        }
                                                        
                                                        $arrayDetalleValorCampo['trama'][$keysd] = $valor1d;
                                                        
                                                        // echo "El Valor del campo (".$keysd.") si coincide con GUIA ||| ".$valor1d." != ".$valors."<br></br>";
                                                    } else {
                                                        
                                                        // $valorDiferencia = 0;
                                                        
                                                        $valorDiferencia = $valors - $valor1d;
                                                        
                                                        $banderaVCValido = false;
                                                        
                                                        // echo "El Valor del campo (".$keysd.") no coincide con GUIA ||| ".$valor1d." != ".$valors."<br></br>";
                                                        
                                                        $arrayDetalleValorCampo['trama'][$keysd] = $valor1d;
                                                    }
                                                }
                                            }
                                        }
                                        
                                        // echo '<br>-------'.$keysd.'.....<br>';
                                        
                                        if ($banderaVCValido) {
                                            
                                            $numeroTransaccionesConciliadasGuiaTrama ++;
                                            
                                            // echo "<br><b>BANDERA VALIDO CONCILIADO</b><br>";
                                            $arrayDetalleValorCampo['resumen'][0] = 'conciliado';                                            
                                        } else {
                                            // echo "<br>BANDERA NO VALIDO NO CONCILIADO<br>";
                                            $arrayDetalleValorCampo['resumen'][0] = 'no conciliado';
                                            
                                            $numeroFacturasNoConciliadas++;
                                            
                                        }
                                        
                                        $arrayTotalesGuiaTrama[$nombreDocumento] = $numeroTransaccionesConciliadasGuiaTrama;
                                    } else if (pg_num_rows($qCamposOrdenPago) > 1) {
                                        
                                        // echo "existen mas de registro con numero de orden vue en bdd";
                                    } else {
                                        
                                        // echo "No encontrado no esta en la conciliacion del dia no esta en GUIA (guardar en tabla para conciliar otro dia) el registro de la trama<br>";
                                    }                                    
                                    
                                    $arrayDetalleTS['resumen'] = array(
                                        'estadoTrama' => 'Estado'
                                    );
                                    
                                    if ($contadorFilaDetalle == 1) {
                                        
                                        ${arrayPrincipalTrama . $contadorTrama}['campoDetalle']['tituloSecundario'] = $arrayDetalleTS;
                                    }
                                    
                                    ${arrayPrincipalTrama . $contadorTrama}['campoDetalle'][$keyFilaTrama] = $arrayDetalleValorCampo;
                                }
                            }
                            
                            break;
                    }

                    break;
                    
                case 'documento':
                    
                    // TODO:Para saber donde empezar a leer
                    $qDocumento = $cb->abrirDocumentoXIdDocumento($conexion, $idDocumento);
                    $documento = pg_fetch_assoc($qDocumento);
                    
                    $arrayDocumentos[] = array(
                        'tipoDocumento' => $tipoDocumento,
                        'formatoDocumento' => $formatoDocumento,
                        'rutaDocumento' => $ruta,
                        'nombreDocumento' => $nombreDocumento
                    );
                    
                    $contadorDocumento ++;
                    
                    // Variables para crear array de documento
                    
                    ${arrayPrincipalDocumento . $contadorDocumento} = array();
                    
                    $arrayDetalleTP2 = array();
                    $arrayDetalleTS2 = array();
                    
                    $arrayDetalleTP2 += array(
                        'documento' => $nombreDocumento
                    );
                    $arrayDetalleTP2 += array(
                        'resumen' => 'Resumen Banco'
                    );
                    
                    ${arrayPrincipalDocumento . $contadorDocumento}['documentoDetalle']['tituloPrincipal'] = $arrayDetalleTP2;
                    
                    // Fin de variables para crear array de documento
                    
                    $arrayFilaCamposDocumento = array();
                    $arrayFilaCamposDocumentoNoGuia = array();
                    
                    $arrayTotalesDocumento = array();
                    $arrayTotalesGuiaDocumento = array();
                    $arrayTotalesRecaudadosDocumento = array();
                    
                    $totalConciliacion = 0; // Variable para sumar el total conciliado
                                            
                    // Se carga la ruta del archivo //TODO: VERIFICAR COMO CARGAR LA RUTA
                    
                    $archivo = "../../" . $ruta;
                    
                    if (! file_exists($archivo)) {
                        
                        echo "no existe el archivo";
                    } else {
                        
                        // echo "<br>existe el archivo---";
                        
                        $objPHPExcel = PHPExcel_IOFactory::load($archivo);
                        $objPHPExcel->setActiveSheetIndex(0);
                        $sheet = $objPHPExcel->getActiveSheet();
                        $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
                        
                        $highestRow = $sheet->getHighestRow();
                        
                        $filaInicioLectura = $documento['fila_inicio_lectura_documento'];
                        $columnaInicioLectura = $documento['columna_inicio_lectura_documento'];
                        
                        // Se busca los campos a comparar por proceso de conciliación
                        $qDatosCamposCompararDocumento = $cb->abrirDatosCamposCompararXIdProcesoConciliacionDocumentos($conexion, $idProcesoConciliacion);
                        
                        // Se recorre caga fila del archivo de excel
                        
                        for ($i = $filaInicioLectura; $i <= $highestRow; $i ++) {
                            
                            $numeroTransaccionesDocumento ++;
                            
                            $arrayCamposDocumento = array(); // Se almacenarán los campos que se recorrerán
                            $arrayCamposBancosNoGUia = array();
                            
                            $banderaValorEncontrado = false;
                            
                            foreach ($qDatosCamposCompararDocumento as $keyDocumento => $valorDocumento) {
                                
                                $campoComparar = $valorDocumento['campo_guia_comparar_proceso_conciliacion'];
                                $nombreCampoDocumento = $valorDocumento['nombre_campo_documento'];
                                
                                $valorExcel = $sheet->getCellByColumnAndRow($valorDocumento['posicion_campo_documento'] - 1, $i)->getValue();
                                
                                switch ($campoComparar) {
                                    
                                    case 'fecha_facturacion':
                                        $valorExcel = fechaFormateada($valorExcel);
                                        break;
                                    
                                    case 'total_pagar':
                                        $valorExcel = convertirNumeroDocumento($valorExcel);
                                        break;
                                }
                                
                                // echo '<br>nombre del campo: '.$nombreCampoDocumento.'--'.$campoComparar;
                                
                                $arrayCamposDocumento += array(
                                    $campoComparar => $valorExcel
                                );
                                
                                // Busca el índice donde se encuentra la orden de pago dentro de GUIA
                                
                                $indice = array_search($valorExcel, $arrayOrdenesGuia, true);
                                
                                if ($indice !== FALSE && $indice >= 0) {
                                    
                                    $indiceArray = $indice;
                                    $banderaValorEncontrado = true;
                                }
                            }
                            
                            if ($banderaValorEncontrado) {
                                
                                $arrayFilaCamposDocumento[$indiceArray] = $arrayCamposDocumento;
                                
                                // echo "bbb<br>";
                                
                                $banderaNoHayDocumentosGuia[] = true;
                            } else {
                                
                                // echo "ddd<br>";
                                
                                $banderaNoHayDocumentosGuia[] = false;
                                $arrayCamposDocumento += array(
                                    'estado' => 'no conciliado'
                                );
                                ${arrayDocumentoCamposNoGuia . $contadorDocumento}[] = $arrayCamposDocumento;
                            }
                        }
                        
                        ksort($arrayFilaCamposDocumento);
                        
                        $resultado = array_unique($banderaNoHayDocumentosGuia);
                        
                        if (count($resultado) == 1) {
                            
                            if ($resultado[0] == false) {
                                $banderaNoHayDocumentosGuiaX = false;
                                
                                // echo "siiiii";
                            } else {
                                $banderaNoHayDocumentosGuiaX = true;
                            }
                        } else {
                            $banderaNoHayDocumentosGuiaX = true;
                        }
                        
                        // Recorremos el array que se formo con los Campos del documento que se encontraron en GUIA
                        
                        if ($banderaNoHayDocumentosGuiaX) {
                            
                            foreach ($arrayFilaCamposDocumento as $keyFilaCampo => $valorFilaCampo) {
                                
                                $contadorFilaDetalleDocumento ++;
                                
                                $qCamposOrdenPago = $cf->abrirCamposOrdenPagoXFechaFacturacionXNumeroOrdenVue($conexion, $fechaConciliacionInicio, $fechaConciliacionFin, $valorFilaCampo['numero_orden_vue']);
                                
                                if (pg_num_rows($qCamposOrdenPago) == 1) {
                                    
                                    $filaCampoGuia = pg_fetch_all($qCamposOrdenPago);
                                    
                                    $banderaVCValido = true;
                                    
                                    foreach ($valorFilaCampo as $keysd => $valor1d) {
                                        
                                        $banderaNoEncontrado = true;
                                        
                                        if ($contadorFilaDetalleDocumento == 1) {
                                            
                                            $keyCambiadoADocumento = $keysd;
                                            
                                            foreach ($qDatosCamposCompararDocumento as $keyDocumento => $valorDocumento) {
                                                
                                                if ($valorDocumento['campo_guia_comparar_proceso_conciliacion'] == $keysd) {
                                                    $keyCambiadoADocumento = $valorDocumento['nombre_campo_documento'];
                                                }
                                            }
                                            
                                            $arrayDetalleTS2['documento'][] = $keyCambiadoADocumento;
                                        }
                                        
                                        foreach ($filaCampoGuia[0] as $keys => $valors) {
                                            
                                            if ($keysd == $keys) {
                                                
                                                if ($valor1d == $valors) {
                                                    
                                                    // echo 'diferencia'.$valorDiferencia = $valors - $valor1d;
                                                    
                                                    $arrayDetalleValorCampoDocumento['documento'][$keysd] = $valor1d;
                                                    
                                                    // echo "El Valor del campo (".$keysd.") si coincide con GUIA ||| ".$valor1d." != ".$valors."<br></br>";
                                                    
                                                    if ($valorDiferencia == 0) {
                                                        $valorDiferencia = '0';
                                                    }
                                                    
                                                    $totalConciliacion += $valor1d;
                                                } else {
                                                    
                                                    $valorDiferencia = $valors - $valor1d;
                                                    
                                                    $banderaVCValido = false;
                                                    // echo "El Valor del campo (".$keysd.") no coincide con GUIA ||| ".$valor1d." != ".$valors."<br></br>";
                                                    
                                                    $arrayDetalleValorCampoDocumento['documento'][$keysd] = $valor1d;
                                                }
                                            }
                                        }
                                        
                                        $arrayTotalesRecaudadosDocumento[$nombreDocumento] = $totalConciliacion;
                                    }
                                    
                                    // echo '<br>-------'.$keysd.'.....<br>';
                                    
                                    if ($banderaVCValido) {
                                        
                                        $numeroTransaccionesConciliadasGuiaDocumento ++;
                                        
                                        // echo "<br><b>BANDERA VALIDO CONCILIADO</b><br>";
                                        $arrayDetalleValorCampoDocumento['resumen'][0] = 'conciliado';
                                        
                                        // $arrayDetalleValorCampoDocumento['resumen'][1]= $valorDiferencia;
                                    } else {
                                        
                                        // echo "<br>BANDERA NO VALIDO NO CONCILIADO</br>";
                                        $arrayDetalleValorCampoDocumento['resumen'][0] = 'no conciliado';
                                        
                                        $numeroFacturasConciliadas++;
                                        
                                        // $arrayDetalleValorCampoDocumento['resumen'][1]= $valorDiferencia;
                                    }
                                    
                                    $arrayTotalesGuiaDocumento[$nombreDocumento] = $numeroTransaccionesConciliadasGuiaDocumento;
                                    
                                    $contadorCabecera ++;
                                } else if (pg_num_rows($qCamposOrdenPago) > 1) {
                                    
                                    // echo "existen mas de registro con numero de orden vue en bdd";
                                } else {
                                    
                                    // numeroFacturasNoConciliadas++;
                                    // echo "No encontrado no esta en la conciliacion del dia no esta en GUIA (guardar en tabla para conciliar otro dia) el registro de la trama<br>";
                                }
                                
                                $arrayDetalleTS2['resumen'] = array(
                                    'estadoBanco' => 'Estado'
                                );
                                
                                if ($contadorFilaDetalleDocumento == 1) {
                                    
                                    ${arrayPrincipalDocumento . $contadorDocumento}['documentoDetalle']['tituloSecundario'] = $arrayDetalleTS2;
                                }
                                
                                ${arrayPrincipalDocumento . $contadorDocumento}['documentoDetalle'][$keyFilaCampo] = $arrayDetalleValorCampoDocumento;
                            }
                        }
                    }
                    
                    $arrayTotalesDocumento[$nombreDocumento] = $numeroTransaccionesDocumento; // XXXkk
                    
                    break;
            }
        }
        
        if ($banderaNoHaytramasGuiaX && $banderaNoHayDocumentosGuiaX) {
            
            // ////////////////////////////////////////////////////////////---------------------------GENERACION DE TABLAS---------------------------//////////////////////////////////////////////////////////////////////////
            
            // ------------------Generacion de tabla general de las tramas recibidas------------------//
            
            for ($h = 1; $h <= $contadorGeneral; $h ++) {
                
                $tablaGeneralInicio = '<table border=1>';
                $lineaDinamicaGeneral = '';
                $primeraLineaGeneral = '<tr>';
                $segundaLineaGeneral = '<tr>';
                
                $colspanGeneral = array();
                
                // Se crean los segundo títulos del reporte
                
                $arrayTablaGeneral = array();
                
                foreach (${arrayPrincipalGeneral . $contadorTrama}['general']['tituloPrincipal'] as $keyPrincipal => $valorPrincipal) {
                    $primeraLineaGeneral .= '<td colspan="' . $colspanGeneral[$keyPrincipal] . '">';
                    $primeraLineaGeneral .= $valorPrincipal;
                    $primeraLineaGeneral .= '</td>';
                    array_push($arrayTablaGeneral, array(
                        $valorPrincipal
                    ));
                }
                
                foreach (${arrayPrincipalGeneral . $contadorTrama}['general']['tituloSecundario'] as $keySecundario => $valorSecundario) {
                    
                    $colspanGeneral = count($valorSecundario);
                    $arrayValoresSecundarios = array();
                    foreach ($valorSecundario as $key2 => $valor2) {
                        
                        $segundaLineaGeneral .= '<td>';
                        $segundaLineaGeneral .= $valor2;
                        $segundaLineaGeneral .= '</td>';
                        $arrayValoresSecundarios[] = $valor2;
                    }
                    array_push($arrayTablaGeneral, $arrayValoresSecundarios);
                }
                
                $segundaLineaGeneral .= '</tr>';
                // Fin se crean los segundo títulos del reporte
                
                $primeraLineaGeneral .= '</tr>';
                
                foreach (${arrayPrincipalGeneral . $contadorTrama}['general'] as $keyDinamico => $valorDinamico) {
                    
                    $numer = is_numeric($keyDinamico);
                    
                    if ($numer) {
                        
                        $lineaDinamicaGeneral .= '<tr>';
                        
                        foreach ($valorDinamico as $key3 => $valor3) {
                            $arrayValoresDinamicos = array();
                            foreach ($valor3 as $key4 => $valor4) {
                                
                                $lineaDinamicaGeneral .= '<td>';
                                $lineaDinamicaGeneral .= ($valor4 != "") ? $valor4 : "";
                                $lineaDinamicaGeneral .= '</td>';
                                $arrayValoresDinamicos[] = $valor4;
                            }
                        }
                        array_push($arrayTablaGeneral, $arrayValoresDinamicos);
                        
                        $lineaDinamicaGeneral .= '</tr >';
                    }
                }
                
                $tablaGeneralFin = '</table>';
                
                // echo $lineaGeneralTotal = $tablaGeneralInicio . $primeraLineaGeneral . $segundaLineaGeneral . $lineaDinamicaGeneral . $tablaGeneralFin;
            }
            
            // ------------------Fin de generacion de tabla general de las tramas recibidas------------------//
            
            // Genetacion de la tabla reporte general de la conciliacion
            
            $tablaConciliacionInicio = '<table border=1>';
            
            $primeraLineaConciliacion = '<tr>';
            $segundaLineaConciliacion = '<tr>';
            $lineaDinamicaConciliacion = '';
            $lineasBanred .= '<tr>';
            
            $colspanpbb = array();
            
            $h = 1;
            $j = 1;
            $colspanTituloSecundario = array();
            
            $arrayTablaConciliacion = array();
            
            $cantidadRegistrosConciliacion = array();
            
            // echo '<br></br>';
            foreach ($arrayOrdenesGuia as $keyOrden => $valueOrden) {
                
                // echo "xxxxxx<br>";
                
                // Poner todas las ordenes de pago en estado no conciliado
                $cb->actualizarEstadoConciliacion($conexion, $valueOrden, 'noConciliado');
                
                if ($j == 1) {
                    
                    // Se generan los titulos secundarios
                    
                    $arrayTituloSecundarioConciliacion = array();
                    
                    foreach ($arrayPrincipalGuia['guia']['tituloSecundario'] as $keySecundarioTS => $valorSecundarioTS) {
                        
                        $cantidadRegistrosConciliacion[] = count($valorSecundarioTS);
                        $colspanTituloSecundario += array(
                            $keySecundarioTS => count($valorSecundarioTS)
                        );
                        
                        foreach ($valorSecundarioTS as $keyGuiaTS => $valorGuiaTS) {
                            
                            $segundaLineaConciliacion .= '<td>';
                            $segundaLineaConciliacion .= $valorGuiaTS;
                            $segundaLineaConciliacion .= '</td>';
                            $arrayTituloSecundarioConciliacion[] = $valorGuiaTS;
                        }
                    }
                    
                    for ($h = 1; $h <= $contadorTrama; $h ++) {
                        
                        foreach (${arrayPrincipalTrama . $h}['campoDetalle']['tituloSecundario'] as $keyTramaTS => $valorTramaTS) {
                            
                            $cantidadRegistrosConciliacion[] = count($valorTramaTS);
                            $colspanTituloSecundario += array(
                                $keyTramaTS => count($valorTramaTS)
                            );
                            
                            foreach ($valorTramaTS as $keyTramaTS1 => $valorTramaTS1) {
                                
                                $segundaLineaConciliacion .= '<td>';
                                $segundaLineaConciliacion .= $valorTramaTS1;
                                $segundaLineaConciliacion .= '</td>';
                                
                                $arrayTituloSecundarioConciliacion[] = $valorTramaTS1;
                            }
                        }
                        
                        foreach (${arrayPrincipalDocumento . $h}['documentoDetalle']['tituloSecundario'] as $keyDocumentoTS => $valorDocumentoTS) {
                            
                            $cantidadRegistrosConciliacion[] = count($valorDocumentoTS);
                            $colspanTituloSecundario += array(
                                $keyDocumentoTS => count($valorDocumentoTS)
                            );
                            
                            foreach ($valorDocumentoTS as $keyDocumentoTS1 => $valorDocumentoTS1) {
                                
                                $segundaLineaConciliacion .= '<td>';
                                $segundaLineaConciliacion .= $valorDocumentoTS1;
                                $segundaLineaConciliacion .= '</td>';
                                $arrayTituloSecundarioConciliacion[] = $valorDocumentoTS1;
                            }
                        }
                        $segundaLineaConciliacion .= '</tr>';
                    }
                    
                    // Fin se generan los titulos secundarios
                    
                    // Se generan los titulos principales
                    
                    $arrayTituloPrincipalConciliacion = array();
                    
                    foreach ($arrayPrincipalGuia['guia']['tituloPrincipal'] as $keyPrincipalTP => $valorPrincipalTP) {
                        
                        $primeraLineaConciliacion .= '<td colspan="' . $colspanTituloSecundario[$keyPrincipalTP] . '">';
                        $primeraLineaConciliacion .= $valorPrincipalTP;
                        $primeraLineaConciliacion .= '</td>';
                        $arrayTituloPrincipalConciliacion[] = $valorPrincipalTP;
                        for ($cantidad = 0; $cantidad < $colspanTituloSecundario[$keyPrincipalTP] - 1; $cantidad ++) {
                            $arrayTituloPrincipalConciliacion[] = '';
                        }
                    }
                    
                    for ($h = 1; $h <= $contadorTrama; $h ++) {
                        
                        foreach (${arrayPrincipalTrama . $h}['campoDetalle']['tituloPrincipal'] as $keyPrincipalTramaTP => $valorPrincipalTramaTP) {
                            $primeraLineaConciliacion .= '<td colspan="' . $colspanTituloSecundario[$keyPrincipalTramaTP] . '">';
                            $primeraLineaConciliacion .= $valorPrincipalTramaTP;
                            $primeraLineaConciliacion .= '</td>';
                            $arrayTituloPrincipalConciliacion[] = $valorPrincipalTramaTP;
                            for ($cantidad = 0; $cantidad < $colspanTituloSecundario[$keyPrincipalTramaTP] - 1; $cantidad ++) {
                                $arrayTituloPrincipalConciliacion[] = '';
                            }
                        }
                        
                        foreach (${arrayPrincipalDocumento . $h}['documentoDetalle']['tituloPrincipal'] as $keyPrincipalDocumentoTP => $valorPrincipalDocumentoTP) {
                            $primeraLineaConciliacion .= '<td colspan="' . $colspanTituloSecundario[$keyPrincipalDocumentoTP] . '">';
                            $primeraLineaConciliacion .= $valorPrincipalDocumentoTP;
                            $primeraLineaConciliacion .= '</td>';
                            $arrayTituloPrincipalConciliacion[] = $valorPrincipalDocumentoTP;
                            for ($cantidad = 0; $cantidad < $colspanTituloSecundario[$keyPrincipalDocumentoTP] - 1; $cantidad ++) {
                                $arrayTituloPrincipalConciliacion[] = '';
                            }
                        }
                        $primeraLineaConciliacion .= '</tr>';
                    }
                    
                    array_push($arrayTablaConciliacion, $arrayTituloPrincipalConciliacion);
                    
                    // Fin se generan los titulos principales
                    
                    $j ++;
                    array_push($arrayTablaConciliacion, $arrayTituloSecundarioConciliacion);
                }
                
                $arrayDatosDinamicosConciliacion = array();
                
                foreach ($arrayPrincipalGuia['guia'][$keyOrden] as $keyDinamicoGuia => $valorDinamicoGuia) {
                    $lineaDinamicaConciliacion .= '<tr>';
                    foreach ($valorDinamicoGuia as $keyDinamicoGuia1 => $valorDinamicoGuia1) {
                        $lineaDinamicaConciliacion .= '<td>';
                        $lineaDinamicaConciliacion .= ($valorDinamicoGuia1 != "") ? $valorDinamicoGuia1 : "";
                        $lineaDinamicaConciliacion .= '</td>';
                        $arrayDatosDinamicosConciliacion[] = ($valorDinamicoGuia1 != "") ? $valorDinamicoGuia1 : "";
                    }
                }
                
                for ($h = 1; $h <= $contadorTrama; $h ++) {
                    
                    if (is_array(${arrayPrincipalTrama . $h}['campoDetalle'][$keyOrden])) {
                        
                        foreach (${arrayPrincipalTrama . $h}['campoDetalle'][$keyOrden] as $keyDinamicoTrama => $valorDinamicoTrama) {
                            
                            foreach ($valorDinamicoTrama as $keyDinamicoTrama1 => $valorDinamicoTrama1) {
                                $lineaDinamicaConciliacion .= '<td>';
                                $lineaDinamicaConciliacion .= ($valorDinamicoTrama1 != "") ? $valorDinamicoTrama1 : "";
                                $lineaDinamicaConciliacion .= '</td>';
                                $arrayDatosDinamicosConciliacion[] = ($valorDinamicoTrama1 != "") ? $valorDinamicoTrama1 : "";
                            }
                        }
                    } else {
                        
                        if (is_array(${arrayPrincipalTrama . $h}['campoDetalle']['tituloSecundario'])) {
                            
                            foreach (${arrayPrincipalTrama . $h}['campoDetalle']['tituloSecundario'] as $keySecundario => $valorSecundario) {
                                
                                foreach ($valorSecundario as $keySecundario1 => $valorSecundario1) {
                                    
                                    if ($keySecundario1 !== 'estadoTrama') {
                                        
                                        $lineaDinamicaConciliacion .= '<td>';
                                        $lineaDinamicaConciliacion .= "";
                                        $lineaDinamicaConciliacion .= '</td>';
                                        $arrayDatosDinamicosConciliacion[] = '';
                                    } else {
                                        
                                        $lineaDinamicaConciliacion .= '<td>';
                                        $lineaDinamicaConciliacion .= "no conciliado";
                                        $lineaDinamicaConciliacion .= '</td>';
                                        $arrayDatosDinamicosConciliacion[] = "no conciliado";
                                    }
                                }
                            }
                        }
                    }
                }
                
                for ($h = 1; $h <= $contadorDocumento; $h ++) {
                    
                    if (is_array(${arrayPrincipalDocumento . $h}['documentoDetalle'][$keyOrden])) {
                        
                        foreach (${arrayPrincipalDocumento . $h}['documentoDetalle'][$keyOrden] as $keyDinamicoDocumento => $valorDinamicoDocumento) {
                            
                            foreach ($valorDinamicoDocumento as $keyDinamicoDocumento1 => $valorDinamicoDocumento1) {
                                $lineaDinamicaConciliacion .= '<td>';
                                $lineaDinamicaConciliacion .= ($valorDinamicoDocumento1 != "") ? $valorDinamicoDocumento1 : "";
                                $lineaDinamicaConciliacion .= '</td>';
                                $arrayDatosDinamicosConciliacion[] = ($valorDinamicoDocumento1 != "") ? $valorDinamicoDocumento1 : "";
                            }
                        }
                    } else {
                        
                        if (is_array(${arrayPrincipalDocumento . $h}['documentoDetalle']['tituloSecundario'])) {
                            
                            foreach (${arrayPrincipalDocumento . $h}['documentoDetalle']['tituloSecundario'] as $keySecundario => $valorSecundario) {
                                
                                foreach ($valorSecundario as $keySecundario1 => $valorSecundario1) {
                                    
                                    if ($keySecundario1 !== 'estadoBanco') {
                                        
                                        $lineaDinamicaConciliacion .= '<td>';
                                        $lineaDinamicaConciliacion .= "";
                                        $lineaDinamicaConciliacion .= '</td>';
                                        $arrayDatosDinamicosConciliacion[] = '';
                                    } else {
                                        
                                        $lineaDinamicaConciliacion .= '<td>';
                                        $lineaDinamicaConciliacion .= "no conciliado";
                                        $lineaDinamicaConciliacion .= '</td>';
                                        $arrayDatosDinamicosConciliacion[] = "no conciliado";
                                    }
                                }
                            }
                        }
                    }
                    $lineaDinamicaConciliacion .= '</tr>';
                }
                
                array_push($arrayTablaConciliacion, $arrayDatosDinamicosConciliacion);
            }
            
            $primeraLineaConciliacion;
            $segundaLineaConciliacion;
            $lineaDinamicaConciliacion;
            
            $tablaConciliacionFin = '</table>';
            
            // echo "ssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssss";
            // echo "XXX---Tabla genaral conciliación--XXXXXXX";
            // echo $lineaConciliacion = $tablaConciliacionInicio . $primeraLineaConciliacion . $segundaLineaConciliacion . $lineaDinamicaConciliacion . $tablaConciliacionFin;
            
            // /FIn de la tabla general de conciliacion
            
            // TRamas restantes
            
            $contadorX = 0;
            $contadorXX = 0;
            
            // Se abre el archivo para cargar las tramas no conciliadas
            $fp2 = fopen("documentos/tramasNoConciliadas.txt", "w");
            
            for ($h = 1; $h <= $contadorTrama; $h ++) {
                
                echo 'Ingreso a datos para carga de archivo no conciliado' . $h;
                
                $tablaRestantesTramaInicio = '<table border=1>';
                $lineaDinamicaRestanteTrama = '<tr>';
                $lineaUnoRestanteTrama = '<tr>';
                
                $arrayTablaSobrantesTrama = array();
                // $colspanTituloSecundarioX=array();
                
                foreach (${arrayPrincipalTrama . $h}['campoDetalle']['tituloSecundario'] as $keyDocumentoTS => $valorDocumentoTS) {
                    
                    // $colspanTituloSecundarioX+=array($keyDocumentoTS=>count($valorDocumentoTS));
                    
                    foreach ($valorDocumentoTS as $keyDocumentoTS1 => $valorDocumentoTS1) {
                        
                        $lineaUnoRestanteTrama .= '<td>';
                        $lineaUnoRestanteTrama .= $valorDocumentoTS1;
                        $lineaUnoRestanteTrama .= '</td>';
                        $arrayValoresSecundariosTrama[] = $valorDocumentoTS1;
                    }
                }
                
                array_push($arrayTablaSobrantesTrama, $arrayValoresSecundariosTrama);
                
                $lineaUnoRestanteTrama .= '</tr>';
                
                foreach (${arrayFilaCamposNoGuia . $h} as $keyCamposNoGuia => $valueCamposNoGuia) {
                    
                    $arrayValoresDinamicosTrama = array();
                    
                    foreach ($valueCamposNoGuia as $keyOrden => $valueOrden) {
                        
                        // Se verifica las ordenes que no estan en GUIA y estan en trama
                        
                        if ($keyOrden == 'numero_orden_vue') {
                            // echo $valueOrden.'----------------------------------<br>';
                            
                            $arrayCodigoVUENoConciliadas[] = $valueOrden;
                        }
                        // echo $keyOrden.'----------------------------------<br>';
                        
                        $lineaDinamicaRestanteTrama .= '<td >';
                        $lineaDinamicaRestanteTrama .= $valueOrden;
                        $lineaDinamicaRestanteTrama .= '</td>';
                        $arrayValoresDinamicosTrama[] = $valueOrden;
                    }
                    
                    // echo 'dd'.$contadorDocumento;
                    
                    $contadorX ++;
                    
                    $lineaDinamicaRestanteTrama .= '</tr>';
                    array_push($arrayTablaSobrantesTrama, $arrayValoresDinamicosTrama);
                }
                
                $tablaRestantesTramaFin = '</table>';
            }
            
            $arrayCodigoVUENoConciliadas = array_unique($arrayCodigoVUENoConciliadas);
            
            foreach ($arrayTramas as $keyArchivo => $valueArchivo) {
                
                $tipoArchivo = $valueArchivo['tipoDocumento'];
                $formatoArchivo = $valueArchivo['formatoDocumento'];
                $rutaArchivo = $valueArchivo['rutaDocumento'];
                
                if ($tipoArchivo == "trama" && $formatoArchivo == "txt") {
                    
                    $lineas = file("../../" . $rutaArchivo);
                    
                    $lineas = array_unique($lineas);
                    
                    foreach ($lineas as $linea) {
                        
                        foreach ($arrayCodigoVUENoConciliadas as $numeroOrdenVue) {
                            if (strstr($linea, $numeroOrdenVue)) {
                                // echo ''.$linea."<br>";
                                fwrite($fp2, $linea);
                            }
                        }
                    }
                }
            }
            
            fclose($fp2);
            
            // echo "XX_---------------------TRAMAS RESTANTES__--------------XX";
            // echo $tablaRestantesTramaInicio.$lineaUnoRestanteTrama.$lineaDinamicaRestanteTrama.$tablaRestantesTramaFin;
            
            // Tablas sobrantes de documento
            
            for ($h = 1; $h <= $contadorDocumento; $h ++) {
                
                $tablaRestantesDocumentoInicio = '<table border=1>';
                $lineaDinamicaRestanteDocumento = '<tr>';
                $lineaUnoRestanteDocumento = '<tr>';
                
                $arrayTablaSobrantesDocumento = array();
                
                foreach (${arrayPrincipalDocumento . $h}['documentoDetalle']['tituloSecundario'] as $keyDocumentoTS => $valorDocumentoTS) {
                    
                    // $colspanTituloSecundarioX+=array($keyDocumentoTS=>count($valorDocumentoTS));
                    
                    foreach ($valorDocumentoTS as $keyDocumentoTS1 => $valorDocumentoTS1) {
                        
                        $lineaUnoRestanteDocumento .= '<td>';
                        $lineaUnoRestanteDocumento .= $valorDocumentoTS1;
                        $lineaUnoRestanteDocumento .= '</td>';
                        $arrayValoresSecundariosDocumento[] = $valorDocumentoTS1;
                    }
                }
                
                array_push($arrayTablaSobrantesDocumento, $arrayValoresSecundariosDocumento);
                
                $lineaUnoRestanteTrama .= '</tr>';
                
                foreach (${arrayDocumentoCamposNoGuia . $h} as $keyCamposNoGuia => $valueCamposNoGuia) {
                    
                    $arrayValoresDinamicosDocumento = array();
                    
                    foreach ($valueCamposNoGuia as $keyOrden => $valueOrden) {
                        
                        // Se verifica las ordenes que no estan en GUIA y estan en documento
                        
                        if ($keyOrden == 'numero_orden_vue') {
                            
                            foreach ($arrayTramas as $keyArchivo => $valueArchivo) {
                                
                                $tipoArchivo = $valueArchivo['tipoDocumento'];
                                $formatoArchivo = $valueArchivo['formatoDocumento'];
                                $rutaArchivo = $valueArchivo['rutaDocumento'];
                                
                                if ($tipoArchivo == "documento" && $formatoArchivo == "xls") {
                                    
                                    $objPHPExcel = PHPExcel_IOFactory::load("../../" . $rutaArchivo);
                                    $objPHPExcel->setActiveSheetIndex(0);
                                    $sheet = $objPHPExcel->getActiveSheet();
                                    $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
                                    
                                    $highestRow = $sheet->getHighestRow();
                                    
                                    // echo "vvvv--";
                                    // echo $valueOrden.'----------------------------------<br>';
                                    
                                    for ($i = 2; $i <= $highestRow; $i ++) {
                                        // echo $valorExcel1=$sheet -> getCellByColumnAndRow('A',$i)->getValue();
                                    }
                                }
                            }
                        }
                        
                        $lineaDinamicaRestanteDocumento .= '<td >';
                        $lineaDinamicaRestanteDocumento .= $valueOrden;
                        $lineaDinamicaRestanteDocumento .= '</td>';
                        $arrayValoresDinamicosDocumento[] = $valueOrden;
                    }
                    
                    // echo 'dd'.$contadorDocumento;
                    
                    $contadorX ++;
                    
                    $lineaDinamicaRestanteDocumento .= '</tr>';
                    array_push($arrayTablaSobrantesDocumento, $arrayValoresDinamicosDocumento);
                }
                
                $tablaRestantesDocumentoFin = '</table>';
            }
            
            // echo "XX----------------Tabla sobrante de documentos----------XXXXXXXXX";
            // echo $tablaRestantesDocumentoInicio.$lineaUnoRestanteDocumento.$lineaDinamicaRestanteDocumento.$tablaRestantesDocumentoFin;
            
            // ////////////////////////////////////////////////////////////---------------------------GENERACION DE TABLAS---------------------------//////////////////////////////////////////////////////////////////////////
            
            // //GNERAR EXCEL
            
            // ////////////////////////////////////////////////////////////---------------------------GENERACION DE TABLAS---------------------------//////////////////////////////////////////////////////////////////////////
            
            $writer = new XLSXWriter();
            
            $sheet1 = 'resumenGeneralTramas';
            $sheet2 = 'resumenConciliacion';
            $sheet3 = 'sobrantesTramas';
            $sheet4 = 'sobrantesDocumentos';
            
            $styles1 = array(
                'border' => 'left,right,top,bottom',
                'halign' => 'center',
                'fill' => '#1a60aa',
                'color' => '#ffffff',
                'font-style' => 'bold'
            );
            $styles2 = array(
                'border' => 'left,right,top,bottom'
            );
            
            // Insertar registros de hoja 1
            $valorColumna = 0;
            $header = array(
                "string",
                "string",
                "string",
                "string",
                "string",
                "string",
                "string",
                "string",
                "string",
                "string",
                "string",
                "string",
                "string",
                "string",
                "string",
                "string",
                "string",
                "string",
                "string",
                "string",
                "string",
                "string",
                "string",
                "string",
                "string",
                "string",
                "string",
                "string",
                "string",
                "string"
            );
            $writer->writeSheetHeader($sheet1, $header, $col_options = array(
                'widths' => [
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25
                ],
                'suppress_row' => true
            ));
            $writer->writeSheetHeader($sheet2, $header, $col_options = array(
                'widths' => [
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25
                ],
                'suppress_row' => true
            ));
            $writer->writeSheetHeader($sheet3, $header, $col_options = array(
                'widths' => [
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25
                ],
                'suppress_row' => true
            ));
            $writer->writeSheetHeader($sheet4, $header, $col_options = array(
                'widths' => [
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25,
                    25
                ],
                'suppress_row' => true
            ));
            
            foreach ($arrayTablaGeneral as $tablaGeneral) {
                if ($valorColumna == 0) {
                    $writer->writeSheetRow($sheet1, $tablaGeneral, $styles1);
                } else {
                    $writer->writeSheetRow($sheet1, $tablaGeneral, $styles2);
                }
                ++ $valorColumna;
            }
            
            // Aplicar celdas combinadas en hoja 1
            $writer->markMergedCell($sheet1, $start_row = 0, $start_col = 0, $end_row = 0, $end_col = $colspanGeneral - 1);
            
            // Insertar registros de hoja 2
            $valorColumna = 0;
            foreach ($arrayTablaConciliacion as $tablaConciliacion) {
                if ($valorColumna == 0) {
                    $writer->writeSheetRow($sheet2, $tablaConciliacion, $styles1);
                } else {
                    $writer->writeSheetRow($sheet2, $tablaConciliacion, $styles2);
                }
                ++ $valorColumna;
            }
            
            for ($i = 0; $i < count($cantidadRegistrosConciliacion); $i ++) {
                if ($i == 0) {
                    $inicio = 0;
                    $fin = $cantidadRegistrosConciliacion[$i] - 1;
                    $writer->markMergedCell($sheet2, $start_row = 0, $start_col = $inicio, $end_row = 0, $end_col = $fin);
                } else {
                    $inicio = ++ $fin;
                    $fin += $cantidadRegistrosConciliacion[$i] - 1;
                    // if($inicio != $fin){
                    $writer->markMergedCell($sheet2, $start_row = 0, $start_col = $inicio, $end_row = 0, $end_col = $fin);
                    // }
                }
            }
            
            $valorColumna = 0;
            foreach ($arrayTablaSobrantesTrama as $tablaTramaSobrantes) {
                if ($valorColumna == 0) {
                    $writer->writeSheetRow($sheet3, $tablaTramaSobrantes, $styles1);
                } else {
                    $writer->writeSheetRow($sheet3, $tablaTramaSobrantes, $styles2);
                }
                ++ $valorColumna;
            }
            
            $valorColumna = 0;
            foreach ($arrayTablaSobrantesDocumento as $tablaDocumentoSobrantes) {
                if ($valorColumna == 0) {
                    $writer->writeSheetRow($sheet4, $tablaDocumentoSobrantes, $styles1);
                } else {
                    $writer->writeSheetRow($sheet4, $tablaDocumentoSobrantes, $styles2);
                }
                ++ $valorColumna;
            }
            
            $hora = time();
            $rutaArchivoConciliacion = 'reportesConciliaciones/conciliacion' . $hora . '.xlsx';
            
            // Guardar PDF
            $writer->writeToFile($rutaArchivoConciliacion);
            
            // /////////////////////////////////////////
            // Para cargar formas de pago
            
            $arrayNumeroVue = array();
            $con = false;
            
            $h = 1;
            
            foreach (${arrayPrincipalTrama . $h}['campoDetalle'] as $keyFormaPago => $valueFormaPago) {
                
                $numer = is_numeric($keyFormaPago);
                
                if ($numer) {
                    
                    foreach ($valueFormaPago as $keyFormaPagoX1 => $valueFormaPagoX1) {
                        
                        foreach ($valueFormaPagoX1 as $keyC => $valueC) {
                            
                            if ($keyC === 'numero_orden_vue') {
                                
                                $numeroVue = $valueC;
                                $con = true;
                                
                                $numeroVue . '=' . $valueC;
                            }
                            
                            if ($con == true && $valueC === 'conciliado') {
                                
                                $arrayNumeroVue[] = $numeroVue;
                                
                                // Actualizar las ordenes que han sido conciliadas
                                $cb->actualizarEstadoConciliacion($conexion, $numeroVue, 'conciliado');
                            }
                        }
                    }
                }
            }
            
            $qDatosDetalle = $cb->obtenerDatosDetalleTrama($conexion, $idProcesoConciliacion);
            $datosDetalle = pg_fetch_assoc($qDatosDetalle);
            
            $qDatosCamposDetalle = $cb->abrirDatosCamposDetalleXIdProcesoConciliacion($conexion, $idProcesoConciliacion, $datosDetalle['codigo_segmento_detalle_trama']);
            
            foreach ($arrayTramas as $keyArchivo => $valueArchivo) {
                
                $tipoArchivo = $valueArchivo['tipoDocumento'];
                $formatoArchivo = $valueArchivo['formatoDocumento'];
                $rutaArchivo = $valueArchivo['rutaDocumento'];
                
                $archivo1 = file("../../" . $rutaArchivo);
            }
            
            foreach ($archivo1 as $item) {
                
                $resultadoNumeroOrdenVue = strposArr($item, $arrayNumeroVue);
                
                if ($resultadoNumeroOrdenVue) {
                    
                    $qIdPagoFecha = pg_fetch_assoc($cb->buscarIdPagoYFechaXIdVue($conexion, $resultadoNumeroOrdenVue));
                    
                    foreach ($qDatosCamposDetalle as $keyFilaTramaDetalle => $valorFilaTramaDetalle) { // TODO:ESTA BIEN
                        
                        $codigoSegmentoDetalle = $valorFilaTramaDetalle['codigo_segmento_detalle_trama'];
                        $posicionInicialDetalle = $valorFilaTramaDetalle['posicion_inicial_campo_detalle'];
                        $longitudSegmentoDetalle = $valorFilaTramaDetalle['longitud_segmento_campo_detalle'];
                        $campoDetalle = $valorFilaTramaDetalle['nombre_campo_detalle'];
                        
                        $idCampoDetalle = $valorFilaTramaDetalle['id_campo_detalle'];
                        $formaPago = ($valorFilaTramaDetalle['campo_forma_pago'] == '') ? '' : $valorFilaTramaDetalle['campo_forma_pago'];
                        
                        $tamanioDetalle = strlen($codigoSegmentoDetalle); // Obtiene el tamanio del codigo del segmento
                        $posicionDetalle = strpos($item, $codigoSegmentoDetalle);
                        
                        if ($posicionDetalle !== FALSE && $posicionDetalle == 0) {
                            
                            $valorCampoDetalle = substr($item, ($posicionInicialDetalle - 1), $longitudSegmentoDetalle);
                            
                            $catalogoCampo = $cb->abrirDatoscatalogosCampos($conexion, $idCampoDetalle, $valorCampoDetalle);
                            
                            $idPago = $qIdPagoFecha['id_pago'];
                            $fechaOrdenPago = $qIdPagoFecha['fecha_orden_pago'];
                            
                            if (pg_num_rows($catalogoCampo) != 0) {
                                
                                $valorCatalogoCampo = pg_fetch_array($catalogoCampo); // Puede fucnionar pg_fetch_all($catalogoCampo)-> toca poner posicion cero abajo
                                
                                $valorCampoDetalle = $valorCatalogoCampo['nombre_catalogo_campo_detalle'];
                            }
                            
                            switch ($formaPago) {
                                
                                case 'banco':
                                    $banco = $valorCampoDetalle;
                                    
                                    if ($valorCampoDetalle == 'Banco de Guayaquil') {
                                        $idBanco = 7;
                                        $notaCredito = 0.00;
                                        $idCuentaBancaria = 4;
                                        // $numeroCuenta = '6243398';
                                    } else if ($valorCampoDetalle == 'Banco de Pacifico') {
                                        $idBanco = 9;
                                        $notaCredito = 0.00;
                                        $idCuentaBancaria = 5;
                                        // $numeroCuenta = '7331077';
                                    }
                                    
                                    break;
                                
                                case 'transaccion':
                                    $transaccion = $valorCampoDetalle;
                                    break;
                                
                                case 'valorDeposito':
                                    $valorDeposito = convertirNumero($valorCampoDetalle);
                                    $valorDeposito = ($valorDeposito == '') ? 0 : $valorDeposito;
                                    break;
                                
                                case 'numeroCuenta':
                                    $numeroCuenta = $valorCampoDetalle;
                                    $numeroCuenta = ($valorCampoDetalle == '') ? 0 : $valorCampoDetalle;
                                    break;
                            }
                        }
                    }
                    
                    if (pg_num_rows($cb->buscarIdPagoFormaPago($conexion, $idPago)) == 0) {
                        $cb->insertarFormaPagoGuia($conexion, $idPago, $idBanco, $banco, $transaccion, $valorDeposito, $fechaOrdenPago, 0, $idCuentaBancaria, $numeroCuenta);
                    } else {
                        $cb->actualizarFormaPagoGuia($conexion, $idPago, $idBanco, $banco, $transaccion, $valorDeposito, $fechaOrdenPago, 0, $idCuentaBancaria, $numeroCuenta);
                    }
                }
            }
            
            fclose($archivo1);
            
            // -------FIN CARGA DE FORMAS DE PAGO-------//
            
            
            $numeroFacturasConciliadasDiasAnteriores = pg_fetch_result($cb-> obtenerFacturasConciliadas($conexion, 'conciliado'),0,'count');            
                      
            
            $resultadoProcesoConciliacion = '# Facturas GUIA ' . $fechaConciliacion . ': ' . $numeroFacturasGuia . ',# Facturas conciliadas días anteriores:  '. $numeroFacturasConciliadasDiasAnteriores .',# Facturas Conciliadas: ' . $numeroTransaccionesConciliadasGuiaTrama . ',# Facturas No Conciliadas: ' . $numeroFacturasNoConciliadas . ',';
            
            foreach ($arrayTramas as $keyArchivo => $valueArchivo) {
                
                $tipoArchivo = $valueArchivo['tipoDocumento'];
                $formatoArchivo = $valueArchivo['formatoDocumento'];
                $rutaArchivo = $valueArchivo['rutaDocumento'];
                $nombreDocumento = $valueArchivo['nombreDocumento'];
                
                foreach ($arrayTotalesTrama as $keyTotalTrama => $valueTotalTrama) {
                    
                    if ($nombreDocumento == $keyTotalTrama) {
                        
                        $resultadoProcesoConciliacion .= '# Transacciones ' . $nombreDocumento . ': ' . $valueTotalTrama . ',';
                    }
                }
                
                foreach ($arrayTotalesGuiaTrama as $keyTotalGuiaTrama => $valueTotalGuiaTrama) {
                    
                    if ($nombreDocumento == $keyTotalGuiaTrama) {
                        
                        $resultadoProcesoConciliacion .= '# Transacciones GUIA-' . $nombreDocumento . ': ' . $valueTotalGuiaTrama . ',';
                    }
                }
            }
            
            foreach ($arrayDocumentos as $keyArchivo => $valueArchivo) {
                
                $tipoArchivo = $valueArchivo['tipoDocumento'];
                $formatoArchivo = $valueArchivo['formatoDocumento'];
                $rutaArchivo = $valueArchivo['rutaDocumento'];
                $nombreDocumento = $valueArchivo['nombreDocumento'];
                
                foreach ($arrayTotalesDocumento as $keyTotalDocumento => $valueTotalDocumento) {
                    
                    if ($nombreDocumento == $keyTotalDocumento) {
                        
                        $resultadoProcesoConciliacion .= '# Transacciones ' . $nombreDocumento . ': ' . $valueTotalDocumento . ',';
                    }
                }
                
                foreach ($arrayTotalesGuiaDocumento as $keyTotalGuiaDocumento => $valueTotalGuiaDocumento) {
                    
                    if ($nombreDocumento == $keyTotalGuiaDocumento) {
                        
                        $resultadoProcesoConciliacion .= '# Transacciones GUIA-' . $nombreDocumento . ': ' . $valueTotalGuiaDocumento . ',';
                    }
                }
                
                foreach ($arrayTotalesRecaudadosDocumento as $keyTotalRecaudadoDocumento => $valueTotalRecaudadoDocumento) {
                    
                    if ($nombreDocumento == $keyTotalRecaudadoDocumento) {
                        
                        $resultadoProcesoConciliacion .= '# Monto recaudado ' . $nombreDocumento . ': ' . $valueTotalRecaudadoDocumento . ',';
                    }
                }
            }
            
            $resultadoProcesoConciliacion = substr($resultadoProcesoConciliacion, 0, - 1);
            
            $cb->guardarResultadoConciliacion($conexion, $idProcesoConciliacion, $resultadoProcesoConciliacion, $rutaArchivoConciliacion);
           
          echo '<input type="hidden" id="' . $idProcesoConciliacion . '" data-rutaAplicacion="conciliacionBancaria" data-opcion="abrirProcesoConciliacion" data-destino="detalleItem"/>';
        } else {
            
            $cb->eliminarBorrarRutasProcesoConciliacion($conexion, $idProcesoConciliacion);
            $cb->eliminarBorrarProcesoConciliacion($conexion, $idProcesoConciliacion);
            // echo "Ningún registro de tramas o documento coincide con los de GUIA";
            
            echo "<header><h1>Proceso de conciliación</h1></header>
              <div></br><b>Ningún registro de tramas o documento coincide con los de GUIA.</b></div>";
        }
        
        break;
}

// IMPRESIONES ARRAY

// PAra SE verifica si existen datos en GUIA Y TRAMA

// if ($imprimirArray && $banderaNoHaytramasGuia && $banderaNoHayDocumentosGuia) {

// echo "<br>----------------------------------------------------------------------------------PAra SE verifica si existen datos en GUIA Y TRAMA por fecha-----------------------------------------------------------------------------<br>";
// echo '<pre>';
// print_r($arrayPrincipalGuia);
// echo '<pre>';

// echo "<br>-----------------------------------------------------------------------------------PAra arrayTramas-----------------------------------------------------------------------------<br>";
// echo '<pre>';
// print_r($arrayTramas);
// echo '<pre>';

// echo "<br>----------------------------------------------------------------------------------campos de tramas que no estan en GUIA-----------------------------------------------------------------------------<br>";
// echo '<pre>';
// print_r(${arrayFilaCamposNoGuia . $contadorTrama});
// echo '<pre>';

// echo "<br>----------------------------------------------------------------------------------campos generales que vienen en tramas-----------------------------------------------------------------------------<br>";
// echo '<pre>';
// print_r($arrayFilaCamposDetalle);
// echo '<pre>';

// foreach ($arrayFilaCamposDetalle as $items => $valor1){
// foreach ($valor1 as $item => $val){
// if($item == 'numero_orden'){
// echo $val.'</br>';
// }
// }
// }

// echo "<br>----------------------------------------------------------------------------------campos generales que vienen en documentos-----------------------------------------------------------------------------<br>";
// echo '<pre>';
// print_r($arrayFilaCamposDocumento);
// echo '<pre>';

// echo "<br>---------------------------------------------------------------------------------array general de tramas con GUIA-----------------------------------------------------------------------------<br>";
// echo '<pre>';
// print_r(${arrayPrincipalGeneral . $contadorTrama});
// echo '<pre>';

// echo "<br>----------------------------------------------------------------------------------array general de trama coinciden con GUIA-----------------------------------------------------------------------------<br>";
// echo '<pre>';
// print_r(${arrayPrincipalTrama . $contadorTrama});
// echo '<pre>';

// echo "<br>----------------------------------------------------------------------------------array general de documentos coinciden con GUIA-----------------------------------------------------------------------------<br>";
// echo '<pre>';
// print_r(${arrayPrincipalDocumento . $contadorDocumento});
// echo '<pre>';

// }
?>

<script type="text/javascript">

	var banderaNoHaytramasGuiaX = <?php echo json_encode($banderaNoHaytramasGuiaX); ?>;
	var banderaNoHayDocumentosGuiaX = <?php echo json_encode($banderaNoHayDocumentosGuiaX); ?>;

	
	$("document").ready(function(){
		
		
		if(banderaNoHaytramasGuiaX || banderaNoHayDocumentosGuiaX){
			abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),null,true);
			abrir($("#detalleItem input"),null,true);
		}else{
			//alert("sddd");
			abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),null,true);
		}
		
	});			
</script>

