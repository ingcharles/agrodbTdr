<?php
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorRequisitos.php';
	require_once '../../clases/ControladorCatalogos.php';
	require_once '../../clases/ControladorReportes.php';

	$mensaje = array();
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Ha ocurrido un error!';
	
try{
	$idProducto = htmlspecialchars ($_POST['idProducto'],ENT_NOQUOTES,'UTF-8');
	$idArea = htmlspecialchars ($_POST['idArea'],ENT_NOQUOTES,'UTF-8');
	
	try {
		$conexion = new Conexion();
		$cr = new ControladorRequisitos();		
		
	    ///JASPER///
	    $jru = new ControladorReportes();
	    
	    $parameters['parametrosReporte'] = array(
	    	'idSolicitud'=>(int)$idProducto
	    );
	    
	    //Verificar tipo reporte
	    if($idArea == 'IAV'){
    	    $ReporteJasper='aplicaciones/registroProducto/reportes/CertificadoVeterinarios.jrxml';
    	    $filename = "CertificadoVeterinario_".$idProducto.'.pdf';
    	    $salidaReporte = 'aplicaciones/registroProducto/certificadosVeterinarios/'.$filename;
	    }else{
	        $ReporteJasper='aplicaciones/registroProducto/reportes/CertificadoFertilizantes.jrxml';
	        $filename = "CertificadoFertilizante_".$idProducto.'.pdf';
	        $salidaReporte = 'aplicaciones/registroProducto/certificadosFertilizantes/'.$filename;
	    }
	    	    	    
	    $jru->generarReporteJasper($ReporteJasper,$parameters,$conexion,$salidaReporte,'CertificadoVeterinarioFertilizantePlaguicida');
	    $cr -> guardarRutaCertificado($conexion, $idProducto, $salidaReporte);
	    
	    $mensaje['estado'] = 'exito';
	    $mensaje['mensaje'] = $idProducto;
	    $mensaje['salidaReporte'] = $salidaReporte;
	    		   
	   $conexion->desconectar();
	   echo json_encode($mensaje);
					
	} catch (Exception $ex){
		pg_close($conexion);
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = "Error al ejecutar sentencia - ".$ex;
		echo json_encode($mensaje);
	}
} catch (Exception $ex) {
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}
?>