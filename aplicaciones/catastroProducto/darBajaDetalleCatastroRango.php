<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastroProducto.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{

 	$idCatastro = htmlspecialchars ($_POST['idCatastro'],ENT_NOQUOTES,'UTF-8');
	$idConceptoCatastro = htmlspecialchars ($_POST['conceptoCatastro'],ENT_NOQUOTES,'UTF-8');
	$inicioRango = htmlspecialchars ($_POST['inicioRango'],ENT_NOQUOTES,'UTF-8');
	$finRango = htmlspecialchars ($_POST['finRango'],ENT_NOQUOTES,'UTF-8');
	
	$conexion = new Conexion();
	$cp = new ControladorCatastroProducto();
	
	 try {
	 	
	 	$conexion->ejecutarConsulta("begin;");
	 
		$tmpInicio= explode("-", $inicioRango);
	 	$tmpFin= explode("-", $finRango);
	 	
	 	$formatoAreaAnoInicio=$tmpInicio[0].'-'.$tmpInicio[1].'-';
	 	$formatoAreaAnoFin=$tmpFin[0].'-'.$tmpFin[1].'-';
	 	
	 	$qCatastroIndividual = $cp->cantidadCatastroIndividualActivo($conexion, $idCatastro);
	 	$filaCatastro = pg_fetch_assoc($qCatastroIndividual);
	 	
	 	function controlReproduccion($conexion,$cp,$contador,$idProducto,$idSitio){
	 		
	 		$idProductoReproduccion=pg_fetch_result($cp->obtenerIdProductoXCodigoProducto($conexion, 'PORDRE'),0,'id_producto');
	 		$idProductoLechon=pg_fetch_result($cp->obtenerIdProductoXCodigoProducto($conexion, 'PORHON'),0,'id_producto');
	 		$idProductoLechona=pg_fetch_result($cp->obtenerIdProductoXCodigoProducto($conexion, 'PORONA'),0,'id_producto');
	 		
	 		$identificadorOperador=pg_fetch_result($cp->abrirSitio($conexion, $idSitio), 0, 'identificador_operador');
	 		$qObtenerMaximoControlReproduccion=$cp->obtenerMaximoControlReproduccion($conexion, $identificadorOperador,$idProductoReproduccion);
	 		$qCantidadCatastro=$cp->obtenerCantidadCatastroXOperador($conexion,  $identificadorOperador, '('.$idProductoReproduccion.')');
	 		$qCantidadCatastroCrias=$cp->obtenerCantidadCatastroXOperador($conexion,  $identificadorOperador, '('.$idProductoLechon.','.$idProductoLechona.')');
	 		
	 		if($idProducto==$idProductoReproduccion){
	 	
	 			$cantidadReproduccion=$contador*28;
	 			
	 			if (pg_num_rows($qObtenerMaximoControlReproduccion)!=0){
					$cupoCria=pg_fetch_result($qObtenerMaximoControlReproduccion, 0, 'cupo_cria')-$cantidadReproduccion;
					$cantidadCriaB=pg_fetch_result($qObtenerMaximoControlReproduccion, 0, 'cantidad_cria');
	 			}else{
					$cupoCria=(pg_fetch_result($qCantidadCatastro, 0, 'cantidad')*28)-$cantidadReproduccion;
					$cantidadCriaB=pg_fetch_result($qCantidadCatastroCrias, 0, 'cantidad');
	 			}
				if($cupoCria<0)
					$cupoCria=0;
	 			
	 			$cp->guardarControlReproduccion($conexion, $identificadorOperador, $idProductoReproduccion, $cupoCria,$cantidadCriaB);
	 	
	 		}
	 	}
	 	 
	 	if(count($tmpInicio)==1 && count($tmpFin)==1 && substr($inicioRango,0,2)=='EC' ){
	 	//TODO:FORMATO EC0000000001
		 	$ini=intval(substr($inicioRango,2));
		 	$fin=intval(substr($finRango,2));
	 		
	 		//TODO: Busco el ultima transaccion de catastro para sacar la cantidad total
	 		$qConsultarCantidadTotalProducto = $cp->consultarCantidadTotalProducto($conexion, $filaCatastro['id_area'], $filaCatastro['id_producto'],$filaCatastro['unidad_comercial'],$filaCatastro['id_tipo_operacion']);
	 		
	 		$cantidadContador=0;
	 		for ($ii=$ini; $ii<=$fin;$ii++){
	 			$identificadorProducto=substr($inicioRango,0,2).str_pad($ii, 9, "0", STR_PAD_LEFT);
	 			$qIdentificadoresCatastro = $cp->consultarIdentificadoresIdCatastro($conexion,$idCatastro,$identificadorProducto);
	 			if(pg_num_rows($qIdentificadoresCatastro)>0){
	 				$cantidadContador++;
	 				$arrayDetalleCatastro[]= array(detalleCatastro=>pg_fetch_result($qIdentificadoresCatastro, 0, 'id_detalle_catastro'));
	 			}
	 		}
	 		
	 		controlReproduccion($conexion, $cp, $cantidadContador, $filaCatastro['id_producto'], $filaCatastro['id_sitio']);

	 		$cantidadTotal =  (pg_num_rows($qConsultarCantidadTotalProducto)!=0 ? pg_fetch_result($qConsultarCantidadTotalProducto, 0, 'cantidad_total'):0) - $cantidadContador;
	 		
	 		//TODO: Guarda los datos de la transacion total de catastro
	 		$cp->guardarCatastroTransaccionResta($conexion,$idCatastro ,$filaCatastro['id_area'], $idConceptoCatastro,  $filaCatastro['id_producto'], $cantidadContador, $cantidadTotal,$filaCatastro['unidad_comercial'],$_SESSION['usuario'],$filaCatastro['id_tipo_operacion']);
	 		
	 		foreach($arrayDetalleCatastro as $detalleCatastro ){
	 			$cp->actualizarEstadoDetalleCatastroEliminado($conexion,$detalleCatastro['detalleCatastro'], 'Eliminado por dar de baja el catastro');
	 		}
	 		
	 		$mensaje['estado'] = 'exito';
	 		$mensaje['mensaje'] = "Los datos han sido eliminados satisfactoriamente.";
	 		
	 		
	 	}elseif(count($tmpInicio)==1 && count($tmpFin)==1 && substr($inicioRango,0,2)!='EC' ){
	 	//TODO:FORMATO formato 23,24,79,80
		 	$ini=intval($inicioRango);
		 	$fin=intval($finRango);

	 		//TODO: Busco el ultima transaccion de catastro para sacar la cantidad total
	 		$qConsultarCantidadTotalProducto = $cp->consultarCantidadTotalProducto($conexion, $filaCatastro['id_area'], $filaCatastro['id_producto'],$filaCatastro['unidad_comercial'], $filaCatastro['id_tipo_operacion']);

	 		$cantidadContador=0;
	 		for ($ii=$ini; $ii<=$fin;$ii++){
	 			$identificadorProducto=$ii;
	 			$qIdentificadoresCatastro = $cp->consultarIdentificadoresIdCatastro($conexion,$idCatastro,$identificadorProducto);
	 			if(pg_num_rows($qIdentificadoresCatastro)>0){
	 				$cantidadContador++;
	 				$arrayDetalleCatastro[]= array(detalleCatastro=>pg_fetch_result($qIdentificadoresCatastro, 0, 'id_detalle_catastro'));
	 			}
	 		}
	 		
	 		controlReproduccion($conexion, $cp, $cantidadContador, $filaCatastro['id_producto'], $filaCatastro['id_sitio']);
	 		
	 		$cantidadTotal =  (pg_num_rows($qConsultarCantidadTotalProducto)!=0 ? pg_fetch_result($qConsultarCantidadTotalProducto, 0, 'cantidad_total'):0) - $cantidadContador;
	 		
	 		//TODO: Guarda los datos de la transacion total de catastro
	 		$cp->guardarCatastroTransaccionResta($conexion,$idCatastro ,$filaCatastro['id_area'], $idConceptoCatastro,  $filaCatastro['id_producto'], $cantidadContador, $cantidadTotal,$filaCatastro['unidad_comercial'],$_SESSION['usuario'], $filaCatastro['id_tipo_operacion']);

	 		foreach($arrayDetalleCatastro as $detalleCatastro ){
	 			$cp->actualizarEstadoDetalleCatastroEliminado($conexion,$detalleCatastro['detalleCatastro'], 'Eliminado por dar de baja el catastro');
	 		}
	 			 		
	 		$mensaje['estado'] = 'exito';
	 		$mensaje['mensaje'] = "Los datos han sido eliminados satisfactoriamente.";
	 		
	 		
	 	}elseif(count($tmpInicio)!=1 && count($tmpInicio)!=1 && $formatoAreaAnoInicio==$formatoAreaAnoFin ){
	 		//TODO:FORMATO 1623-15-000222

	 		 $ini=intval(end($tmpInicio));
	 		 $fin=intval(end($tmpFin));
	 
	 		//TODO: Busco el ultima transaccion de catastro para sacar la cantidad total
	 		$qConsultarCantidadTotalProducto = $cp->consultarCantidadTotalProducto($conexion, $filaCatastro['id_area'], $filaCatastro['id_producto'],$filaCatastro['unidad_comercial'], $filaCatastro['id_tipo_operacion']);
	 		
	 		$cantidadContador=0;
	 		for ($ii=$ini; $ii<=$fin;$ii++){
	 			$identificadorProducto=$formatoAreaAnoInicio.str_pad($ii, 6, "0", STR_PAD_LEFT);
	 			$qIdentificadoresCatastro = $cp->consultarIdentificadoresIdCatastro($conexion,$idCatastro,$identificadorProducto);
	 			if(pg_num_rows($qIdentificadoresCatastro)>0){
	 				$cantidadContador++;
	 				$arrayDetalleCatastro[]= array(detalleCatastro=>pg_fetch_result($qIdentificadoresCatastro, 0, 'id_detalle_catastro'));
	 			}
	 		}
	 		
	 		controlReproduccion($conexion, $cp, $cantidadContador, $filaCatastro['id_producto'], $filaCatastro['id_sitio']);
	 		
	 		$cantidadTotal =  (pg_num_rows($qConsultarCantidadTotalProducto)!=0 ? pg_fetch_result($qConsultarCantidadTotalProducto, 0, 'cantidad_total'):0) - $cantidadContador;
	 		
	 		//TODO: Guarda los datos de la transacion total de catastro
	 		$cp->guardarCatastroTransaccionResta($conexion,$idCatastro, $filaCatastro['id_area'], $idConceptoCatastro,  $filaCatastro['id_producto'], $cantidadContador, $cantidadTotal,$filaCatastro['unidad_comercial'],$_SESSION['usuario'], $filaCatastro['id_tipo_operacion']);

	 		foreach($arrayDetalleCatastro as $detalleCatastro ){
	 			$cp->actualizarEstadoDetalleCatastroEliminado($conexion,$detalleCatastro['detalleCatastro'], 'Eliminado por dar de baja el catastro');
	 		}
	 			 		
	 		$mensaje['estado'] = 'exito';
	 		$mensaje['mensaje'] = "Los datos han sido dados de baja satisfactoriamente.";
	 		
	 	}else{
	 		$mensaje['estado'] = 'error';
	 		$mensaje['mensaje'] = "Error rango direfentes";
	 	}
	 	
	 	$conexion->ejecutarConsulta("commit;");
	 
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