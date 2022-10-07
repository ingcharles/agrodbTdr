<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorProtocolos.php';
//require_once '../../clases/ControladorCatalogos.php';
//require_once '../../clases/ControladorAuditoria.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	
    $nombreAreaCodigo = htmlspecialchars ($_POST['nombreAreaCodigo'],ENT_NOQUOTES,'UTF-8');
	$nombreOperacion = htmlspecialchars ($_POST['nombreOperacion'],ENT_NOQUOTES,'UTF-8');
	$nombreProtocolo = htmlspecialchars ($_POST['nombreProtocolo'],ENT_NOQUOTES,'UTF-8');
	$codigoArea = htmlspecialchars ($_POST['codigoArea'],ENT_NOQUOTES,'UTF-8');
	$idOperacion = htmlspecialchars ($_POST['idOperacion'],ENT_NOQUOTES,'UTF-8');
	$protocolo = htmlspecialchars ($_POST['protocolo'],ENT_NOQUOTES,'UTF-8');
	$estado = htmlspecialchars ($_POST['estadoInspeccion'],ENT_NOQUOTES,'UTF-8');

	try {
		$conexion = new Conexion();
		$cp = new ControladorProtocolos();
		
		if(pg_num_rows($cp->buscarProtocoloArea($conexion, $codigoArea, $idOperacion)) == 0){
			
		    $qIdProtocoloArea = $cp -> guardarProtocoloArea($conexion, $nombreAreaCodigo, $codigoArea, $nombreOperacion, $idOperacion);
			$qDetalleProtocoloArea= $cp ->guardarProtocoloAreaAsignado($conexion, pg_fetch_result($qIdProtocoloArea, 0, 'id_protocolo_area'), $protocolo, $estado);
	
    		$mensaje['estado'] = 'exito';
    		$mensaje['mensaje'] = $cp->imprimirLineaProtocoloAreaAsignado(pg_fetch_result($qDetalleProtocoloArea, 0, 'id_protocolo_area_asignado'), $nombreProtocolo, $estado);
    		
		}else{
			
		    $qIdProtocoloArea = $cp->buscarProtocoloArea($conexion, $codigoArea, $idOperacion);
			
			if(pg_num_rows($cp->buscarProtocoloAreaAsignado($conexion, pg_fetch_result($qIdProtocoloArea, 0, 'id_protocolo_area'), $protocolo)) == 0){
					
				$qDetalleProtocoloArea= $cp ->guardarProtocoloAreaAsignado($conexion, pg_fetch_result($qIdProtocoloArea,0,'id_protocolo_area'), $protocolo, $estado);
				
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = $cp->imprimirLineaProtocoloAreaAsignado(pg_fetch_result($qDetalleProtocoloArea,0,'id_protocolo_area_asignado'), $nombreProtocolo, $estado);
			}else{
				
				$mensaje['mensaje'] = 'El protocolo y area elegidos ya han sido registrados.';
			}
		
		}
		
		$conexion->desconectar();
		
		echo json_encode($mensaje);
		
		} catch (Exception $ex){
			pg_close($conexion);
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = "Error al ejecutar sentencia".$ex;
			echo json_encode($mensaje);
		}
		} catch (Exception $ex) {
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = 'Error de conexión a la base de datos';
			echo json_encode($mensaje);
		}	
?>