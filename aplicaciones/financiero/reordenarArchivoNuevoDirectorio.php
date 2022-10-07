<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/Constantes.php';
require_once '../../clases/ControladorCertificados.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';
$mensaje['rutaCertificado']='';


$conexion = new Conexion();
$cc = new ControladorCertificados();
$constg = new Constantes();

set_time_limit(20000);


$datosFacturacion =$cc->obtenerRegistrosDocumento($conexion);

$contador=1;

while($fila=pg_fetch_assoc($datosFacturacion)){
    
    $banderaXML = false;
    
	echo '<b>PROCESO # '.$contador.'</b><br>';
			
	$nombreArchivoOrdenPago = end(explode('/', $fila['orden_pago']));
	$nombreArchivoFactura = end(explode('/', $fila['factura']));
	$nombreArchivoFacturaComprobante = end(explode('/', $fila['comprobante_factura']));
	$rutaDocumento = '';
	
	switch ($fila['tipo_solicitud']){	    
	    
	    case 'Ingreso Caja':
	        
	        echo 'ID PAGO INGRESO CAJA ---> '.$fila['id_pago'].'<br>';
	        
	        $rutaNuevaIngresoCajaOrden = $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/aplicaciones/financiero/documentos/ingresoCaja/'.$fila['fecha_orden_pago'].'/'.$nombreArchivoOrdenPago;
	        $rutaAntiguaIngresoCajaOrden = $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/'.$fila['orden_pago'];
	        
	        if(!file_exists('documentos/ingresoCaja/'.$fila['fecha_orden_pago'].'/')){
	            mkdir('documentos/ingresoCaja/'.$fila['fecha_orden_pago'].'/', 0777,true);
	        }
	        
	        if(is_file($rutaAntiguaIngresoCajaOrden)){
	            rename ($rutaAntiguaIngresoCajaOrden, $rutaNuevaIngresoCajaOrden);
	            $rutaDocumento = 'aplicaciones/financiero/documentos/ingresoCaja/'.$fila['fecha_orden_pago'].'/'.$nombreArchivoOrdenPago;
	            $cc->actualizarRutaCertificados($conexion, 'g_financiero.orden_pago', 'orden_pago', $rutaDocumento, 'id_pago', $fila['id_pago']);
	            echo 'Actualización de ruta de comprobante ingreso de caja</br>';
	        }else{
	            echo 'No existe archivo de comprobante de ingreso de caja</br>';
	        }	  
	        
	        $rutaNuevaIngresoCaja = $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/aplicaciones/financiero/documentos/ingresoCaja/'.$fila['fecha_facturacion'].'/'.$nombreArchivoFactura;	        
	        $rutaAntiguaIngresoCaja = $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/'.$fila['factura'];	       
	        
	        if(!file_exists('documentos/ingresoCaja/'.$fila['fecha_facturacion'].'/')){
	            mkdir('documentos/ingresoCaja/'.$fila['fecha_facturacion'].'/', 0777,true);
	        }
	        
	        if(is_file($rutaAntiguaIngresoCaja)){
	            rename ($rutaAntiguaIngresoCaja, $rutaNuevaIngresoCaja);
	            $rutaDocumento = 'aplicaciones/financiero/documentos/ingresoCaja/'.$fila['fecha_facturacion'].'/'.$nombreArchivoFactura;
	            $cc->actualizarRutaCertificados($conexion, 'g_financiero.orden_pago', 'factura', $rutaDocumento, 'id_pago', $fila['id_pago']);
	            $cc->actualizarRutaCertificados($conexion, 'g_financiero.orden_pago', 'comprobante_factura', $rutaDocumento, 'id_pago', $fila['id_pago']);
	            echo 'Actualización de ruta de ingreso de caja</br>';
	        }else{
	            echo 'No existe archivo de ingreso de caja</br>';
	        }
	        
	    break;
	    
	    case 'Nota de credito':
	        
	        $tipoSolicitud = 'notaCredito';
	        
	        echo 'ID NOTA DE CREDITO ---> '.$fila['id_pago'].'</br>';
	        
	        $rutaNuevaComprobanteNotaCredito = $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/aplicaciones/financiero/documentos/notaCredito/'.$fila['fecha_facturacion'].'/'.$nombreArchivoFacturaComprobante;
	        $rutaAntiguaComprobanteNotaCredito = $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/'.$fila['comprobante_factura'];
	        
	        if(!file_exists('documentos/notaCredito/'.$fila['fecha_facturacion'].'/')){
	            mkdir('documentos/notaCredito/'.$fila['fecha_facturacion'].'/', 0777,true);
	        }
	        
	        if(is_file($rutaAntiguaComprobanteNotaCredito)){
	            rename ($rutaAntiguaComprobanteNotaCredito, $rutaNuevaComprobanteNotaCredito);
	            $rutaDocumento = 'aplicaciones/financiero/documentos/notaCredito/'.$fila['fecha_facturacion'].'/'.$nombreArchivoFacturaComprobante;
	            $cc->actualizarRutaCertificados($conexion, 'g_financiero.nota_credito', 'comprobante_nota_credito', $rutaDocumento, 'id_nota_credito', $fila['id_pago']);
	            echo 'Actualización de ruta de comprobante de nota de credito<br>';
	        }else{
	            echo 'No existe archivo de comprobante de nota de credito</br>';
	        }	  
	        
	        $rutaNuevaNotaCredito = $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/aplicaciones/financiero/documentos/notaCredito/'.$fila['fecha_facturacion'].'/'.$nombreArchivoFactura;
	        $rutaAntiguaNotaCredito = $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/'.$fila['factura'];
	        
	        if(is_file($rutaAntiguaNotaCredito)){
	            rename ($rutaAntiguaNotaCredito, $rutaNuevaNotaCredito);
	            $rutaDocumento = 'aplicaciones/financiero/documentos/notaCredito/'.$fila['fecha_facturacion'].'/'.$nombreArchivoFactura;
	            $cc->actualizarRutaCertificados($conexion, 'g_financiero.nota_credito', 'ruta_nota_credito', $rutaDocumento, 'id_nota_credito', $fila['id_pago']);
	            echo 'Actualización de ruta de nota de credito<br>';
	        }else{
	            echo 'No existe archivo de nota de credito</br>';
	        }	
	       
	    break;
	    
	    default:
	        
	        echo 'ID PAGO ---> '.$fila['id_pago'].'</br>';
	        
	        $rutasNuevaOrdenPago= $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/aplicaciones/financiero/documentos/ordenPago/'.$fila['fecha_orden_pago'].'/'.$nombreArchivoOrdenPago;
	        $rutaAntiguaOrdenPago = $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/'.$fila['orden_pago'];
	        
	        if(!file_exists('documentos/ordenPago/'.$fila['fecha_orden_pago'].'/')){
	            mkdir('documentos/ordenPago/'.$fila['fecha_orden_pago'].'/', 0777,true);
	        }
	        
	        if(is_file($rutaAntiguaOrdenPago)){
	            rename ($rutaAntiguaOrdenPago, $rutasNuevaOrdenPago);
	            $rutaDocumento = 'aplicaciones/financiero/documentos/ordenPago/'.$fila['fecha_orden_pago'].'/'.$nombreArchivoOrdenPago;
	            $cc->actualizarRutaCertificados($conexion, 'g_financiero.orden_pago', 'orden_pago', $rutaDocumento, 'id_pago', $fila['id_pago']);
	            echo 'Actualización de ruta de orden de pago</br>';
	        }else{
	            echo 'No existe archivo de orden de pago</br>';
	        }	        
	        
	        $rutaNuevaFactura = $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/aplicaciones/financiero/documentos/facturas/'.$fila['fecha_facturacion'].'/'.$nombreArchivoFactura;
	        $rutaAntiguaFactura = $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/'.$fila['factura'];
	        
	        if(!file_exists('documentos/facturas/'.$fila['fecha_facturacion'].'/')){
	            mkdir('documentos/facturas/'.$fila['fecha_facturacion'].'/', 0777,true);
	        }
	        
	        if(is_file($rutaAntiguaFactura)){
	            rename ($rutaAntiguaFactura, $rutaNuevaFactura);
	            $rutaDocumento = 'aplicaciones/financiero/documentos/facturas/'.$fila['fecha_facturacion'].'/'.$nombreArchivoFactura;
	            $cc->actualizarRutaCertificados($conexion, 'g_financiero.orden_pago', 'factura', $rutaDocumento, 'id_pago', $fila['id_pago']);
	            echo 'Actualización de ruta de factura</br>';
	        }else{
	            echo 'No existe archivo de factura</br>';
	        }
	        
	        $rutaNuevaFactura = $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/aplicaciones/financiero/documentos/facturas/'.$fila['fecha_facturacion'].'/'.$nombreArchivoFacturaComprobante;
	        $rutaAntiguaComprobanteFactura = $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/'.$fila['comprobante_factura'];
	       
	        if(is_file($rutaAntiguaComprobanteFactura)){
	            rename ($rutaAntiguaComprobanteFactura, $rutaNuevaFactura);
	            $rutaDocumento = 'aplicaciones/financiero/documentos/facturas/'.$fila['fecha_facturacion'].'/'.$nombreArchivoFacturaComprobante;
	            $cc->actualizarRutaCertificados($conexion, 'g_financiero.orden_pago', 'comprobante_factura', $rutaDocumento, 'id_pago', $fila['id_pago']);
	            echo 'Actualización de ruta de comprobante factura</br>';
	        }else{
	            echo 'No existe archivo de comprobante de factura</br>';
	        }
	}
	
	$rutaNuevaRechazadosSRI = $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/aplicaciones/financiero/archivoXml/rechazadosSRI/'.$fila['fecha_facturacion'].'/'.$fila['clave_acceso'].'.xml';
	$rutaAntiguaRechazadosSRI = $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/aplicaciones/financiero/archivoXml/rechazadosSRI/'.$fila['clave_acceso'].'.xml';
	
	if(!file_exists('archivoXml/rechazadosSRI/'.$fila['fecha_facturacion'].'/')){
	    mkdir('archivoXml/rechazadosSRI/'.$fila['fecha_facturacion'].'/', 0777,true);
	}
	
	if(is_file($rutaAntiguaRechazadosSRI)){
	    rename ($rutaAntiguaRechazadosSRI, $rutaNuevaRechazadosSRI);
	    $banderaXML = true;
	    $banderaTipoacceso = 'rechazado';
	}else{
	    echo 'No existe archivo rechazado</br>';
	}
	
	$rutaNuevaAutorizado = $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/aplicaciones/financiero/archivoXml/autorizados/'.$fila['fecha_facturacion'].'/'.$fila['clave_acceso'].'.xml';
	$rutaAntiguaAutorizado = $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/aplicaciones/financiero/archivoXml/autorizados/'.$fila['clave_acceso'].'.xml';
	
	if(!file_exists('archivoXml/autorizados/'.$fila['fecha_facturacion'].'/')){
	    mkdir('archivoXml/autorizados/'.$fila['fecha_facturacion'].'/', 0777,true);
	}
	
	if(is_file($rutaAntiguaAutorizado)){
	    rename ($rutaAntiguaAutorizado, $rutaNuevaAutorizado);
	    $banderaXML = true;
	    $banderaTipoacceso = 'autorizado';
	}else{
	    echo 'No existe archivo autorizado</br>';
	}	
	
	$rutaNuevaAutorizadosSRI = $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/aplicaciones/financiero/archivoXml/autorizadosSRI/'.$fila['fecha_facturacion'].'/'.$fila['clave_acceso'].'.xml';
	$rutaAntiguaAutorizadosSRI = $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/aplicaciones/financiero/archivoXml/autorizadosSRI/'.$fila['clave_acceso'].'.xml';
	
	if(!file_exists('archivoXml/autorizadosSRI/'.$fila['fecha_facturacion'].'/')){
	    mkdir('archivoXml/autorizadosSRI/'.$fila['fecha_facturacion'].'/', 0777,true);
	}
	
	if(is_file($rutaAntiguaAutorizadosSRI)){
	    rename ($rutaAntiguaAutorizadosSRI, $rutaNuevaAutorizadosSRI);
	    $banderaXML = true;
	    $banderaTipoacceso = 'autorizadoSRI';
	}else{
	    echo 'No existe archivo autorizado SRI</br>';
	}
	
	
	$rutaNuevaFirmados = $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/aplicaciones/financiero/archivoXml/firmados/'.$fila['fecha_facturacion'].'/'.$fila['clave_acceso'].'.xml';
	$rutaAntiguaFirmados = $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/aplicaciones/financiero/archivoXml/firmados/'.$fila['clave_acceso'].'.xml';
	
	if(!file_exists('archivoXml/firmados/'.$fila['fecha_facturacion'].'/')){
	    mkdir('archivoXml/firmados/'.$fila['fecha_facturacion'].'/', 0777,true);
	}
	
	if(is_file($rutaAntiguaFirmados)){
	    rename ($rutaAntiguaFirmados, $rutaNuevaFirmados);
	    echo 'Actualización de ruta de archivo xml firmado</br>';
	}else{
	    echo 'No existe archivo firmado</br>';
	}
	
	$rutaNuevaGenerados = $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/aplicaciones/financiero/archivoXml/generados/'.$fila['fecha_facturacion'].'/'.$fila['clave_acceso'].'.xml';
	$rutaAntiguaGenerados = $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/aplicaciones/financiero/archivoXml/generados/'.$fila['clave_acceso'].'.xml';
	
	if(!file_exists('archivoXml/generados/'.$fila['fecha_facturacion'].'/')){
	    mkdir('archivoXml/generados/'.$fila['fecha_facturacion'].'/', 0777,true);
	}
	
	if(is_file($rutaAntiguaGenerados)){
	    rename ($rutaAntiguaGenerados, $rutaNuevaGenerados);
	    echo 'Actualización de ruta de archivo xml generado</br>';
	}else{
	    echo 'No existe archivo generado</br>';
	}
	
	$nuevaRuta = '';
	
	if($banderaXML){
	    
	    switch ($banderaTipoacceso){
	        case 'rechazado':
	            $nuevaRuta = $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/aplicaciones/financiero/archivoXml/rechazadosSRI/'.$fila['fecha_facturacion'].'/'.$fila['clave_acceso'].'.xml';
	            echo 'Actualización de ruta de orden de pago a rechazado</br>';
	        break;
	        case 'autorizado':
	            $nuevaRuta = $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/aplicaciones/financiero/archivoXml/autorizados/'.$fila['fecha_facturacion'].'/'.$fila['clave_acceso'].'.xml';
	            echo 'Actualización de ruta de orden de pago a autorizado</br>';
	        break;
	        case 'autorizadoSRI':
	            $nuevaRuta = $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/aplicaciones/financiero/archivoXml/autorizadosSRI/'.$fila['fecha_facturacion'].'/'.$fila['clave_acceso'].'.xml';
	            echo 'Actualización de ruta de orden de pago a autorizado SRI</br>';
	        break;
	    }
	    
	    if($fila['tipo_solicitud'] == 'Nota de credito'){
	        $cc->actualizarRutaCertificados($conexion, 'g_financiero.nota_credito', 'ruta_xml', $nuevaRuta, 'id_nota_credito', $fila['id_pago']);
	    }else{
	        $cc->actualizarRutaCertificados($conexion, 'g_financiero.orden_pago', 'ruta_xml', $nuevaRuta, 'id_pago', $fila['id_pago']);
	    }
	    
	    
	}
	
	if($fila['tipo_solicitud'] == 'Nota de credito'){
	    $cc->actualizarRutaCertificados($conexion, 'g_financiero.nota_credito', 'actuafin', 'SI', 'id_nota_credito', $fila['id_pago']);
	}else{
	    $cc->actualizarRutaCertificados($conexion, 'g_financiero.orden_pago', 'actuafin', 'SI', 'id_pago', $fila['id_pago']);
	}
	
	echo '<br><br>';

	$contador++;
}
?>



