<?php
session_start();
require_once '../../clases/Conexion.php';

try{
	$inspector = htmlspecialchars ($_POST['inspector'],ENT_NOQUOTES,'UTF-8');
	$idSolicitud= $_POST['idSolicitud'];
	$tipoSolicitud = htmlspecialchars ($_POST['tipoSolicitud'],ENT_NOQUOTES,'UTF-8');
	$montoRecaudado = htmlspecialchars ($_POST['totalPagar'],ENT_NOQUOTES,'UTF-8');
	$numeroFactura = htmlspecialchars ($_POST['numeroFactura'],ENT_NOQUOTES,'UTF-8');
	
	$resultado = htmlspecialchars ($_POST['resultado'],ENT_NOQUOTES,'UTF-8');
	$observacion = htmlspecialchars ($_POST['observacion'],ENT_NOQUOTES,'UTF-8');
	$idOperador = htmlspecialchars ($_POST['idOperador'],ENT_NOQUOTES,'UTF-8');
	
	try {
		$conexion = new Conexion();
		
			switch ($tipoSolicitud){
				
				case 'Emisión de Etiquetas':
				    
				    require_once '../../clases/ControladorEtiquetas.php';					
					$ce = new ControladorEtiquetas();
					
					$ce->actualizarDatosSolicitudEtiqueta($conexion,'estado',$resultado,$idSolicitud);
					$ce->actualizarDatosSolicitudEtiqueta($conexion,'fecha_aprobacion','now()',$idSolicitud);
						
				break;
				
				case 'dossierPecuario':
				    
				    require_once '../../clases/ControladorDossierPecuario.php';
				    $cdp = new ControladorDossierPecuario();
				    
				    $datosDocumento=array();
				    
				    $datosDocumento['id_solicitud'] = $idSolicitud;
				    $datosDocumento['estado']='asignarTecnico';
				    $cdp -> guardarSolicitud($conexion,$datosDocumento);
				    
				break;
				
				case 'dossierFertilizantes':
				    
				    require_once '../../clases/ControladorDossierFertilizante.php';
				    $cdf = new ControladorDossierFertilizante();
				    
				    $datosDocumento=array();
				    
				    $datosDocumento['id_solicitud'] = $idSolicitud;
				    $datosDocumento['estado']='asignarTecnico';
				    $cdf -> guardarSolicitud($conexion,$datosDocumento);
				    
				break;
				
				case 'dossierPlaguicida':
				    
				    require_once '../../clases/ControladorDossierPlaguicida.php';
				    $cep = new ControladorDossierPlaguicida();
				    
				    $datosDocumento=array();
				    
				    $datosDocumento['id_solicitud'] = $idSolicitud;
				    $datosDocumento['estado']='asignarTecnico';
				    $cep -> guardarSolicitud($conexion,$datosDocumento);
				    
				    break;
				
				case 'ensayoEficacia':
				    
				    require_once '../../clases/ControladorEnsayoEficacia.php';
				    $cee = new ControladorEnsayoEficacia();
				    
				    $datosDocumento=array();
				    
				    $datosDocumento['id_protocolo'] = $idSolicitud;
				    $datosDocumento['estado']='verificacionProtocolo';
				    $cee -> guardarProtocolo($conexion,$datosDocumento);
				    
			    break;
							
				default :
					break;
			}
				
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = 'La operación se ha guardado satisfactoriamente.';
					
		$conexion->desconectar();
		echo json_encode($mensaje);
		
	} catch (Exception $ex){
		pg_close($conexion);
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = "Error al ejecutar sentencia";
		echo json_encode($mensaje);
	}
} catch (Exception $ex) {
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}
?>