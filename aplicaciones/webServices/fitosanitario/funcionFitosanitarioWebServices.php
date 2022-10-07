<?php

require_once '../../../clases/Conexion.php';
require_once '../../../clases/ControladorFitosanitarioExportacion.php';

$conexion = new Conexion();
$certificadoFitosanitario = new ControladorFitosanitarioExportacion();


function Certificado_actualizacion_fecha_estado($fechaDesde, $fechaHasta, $estado){
    
    $validacion = true;
    $conexion = new Conexion();
    $certificadoFitosanitario = new ControladorFitosanitarioExportacion();
    
    if($estado == ''){        
        return array('Por favor ingrese los datos de consulta.');  
        $validacion = false;
    }
    
    if(!$certificadoFitosanitario->validateDateEs($fechaDesde)){
        return array('Por favor verificar formato fecha desde');
        $validacion = false;
    }
    
    if(!$certificadoFitosanitario->validateDateEs($fechaHasta)){
        return array('Por favor verificar formato fecha hasta');
        $validacion = false;
    }
    
    if($validacion){
        
        $estado = 'aprobado';
        
        $datos = array(
            'fecha_desde'    => $fechaDesde,
            'fecha_hasta'     => $fechaHasta,
            'estado'  => $estado);
        
        $identificadoresFitosanitario = $certificadoFitosanitario->buscarFitosanitarioExportacionPorFechaEstado($conexion, $datos);
        
        return $identificadoresFitosanitario;
        
    }
    
}


function Recupera_certificado_oficial($numeroCertificado){
    
    if($numeroCertificado == ''){
        return 'Por favor ingrese un número de certificado';
    }else{
    
        $datos = array('numero_certificado' => $numeroCertificado);
    	
    	$conexion = new Conexion();
    	$certificadoFitosanitario = new ControladorFitosanitarioExportacion();
    	
    	$fitosanitarioXml = $certificadoFitosanitario->buscarFitosanitarioExportacionPorIdentificador($conexion, $datos);
        
    	//return array('mensaje'=>$fitosanitarioXml);
    	return $fitosanitarioXml;
	
    }
}

function Recupera_certificados_firmados($numeroCertificado){
    
    if($numeroCertificado == ''){
        return 'Por favor ingrese un número de certificado';
    }else{
        $datos = array('numero_certificado' => $numeroCertificado);
        
        $conexion = new Conexion();
        $certificadoFitosanitario = new ControladorFitosanitarioExportacion();
        
        $fitosanitarioXml = $certificadoFitosanitario->buscarFitosanitarioExportacionPorIdentificador($conexion, $datos, true);
        
        //return array('mensaje'=>$fitosanitarioXml);
        return $fitosanitarioXml;
    }
    
}

function Confirmacion_certificado($numeroCertificado){
    
    if($numeroCertificado == ''){
        return 'Por favor ingrese un número de certificado';
    }else{    
        $datos = array('numero_certificado' => $numeroCertificado);
        
        $conexion = new Conexion();
        $certificadoFitosanitario = new ControladorFitosanitarioExportacion();
        
        $confirmacion = $certificadoFitosanitario->actualizarEstadoRecepcionCertificadoFitosanitarioExportacion($conexion, $datos);
    
        return $confirmacion;
    }
}

?>