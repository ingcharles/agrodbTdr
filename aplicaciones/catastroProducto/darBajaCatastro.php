<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastroProducto.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{

	$idConceptoCatastro = $_POST['conceptoCatastro'];
	 $idCatastro = $_POST['idCatastro'];
	 $conexion = new Conexion();
	 $ccp = new ControladorCatastroProducto();
		
	try {
		
		$conexion->ejecutarConsulta("begin;");
		//reproduccion
		$qIdentificadoresProducto=$ccp->notificarEliminarCatastro($conexion, $idCatastro);
		 
		$contador=0;
		while($filaNotificar = pg_fetch_assoc($qIdentificadoresProducto)){
			if( $filaNotificar['estado_registro']=='activo'  ){
				$contador++;
			}
		}
		
		$qCatastroIndividual = $ccp->cantidadCatastroIndividualActivo($conexion, $idCatastro);
		$filaCatastro = pg_fetch_assoc($qCatastroIndividual);
		
		//reproduccion
		$idProductoReproduccion=pg_fetch_result($ccp->obtenerIdProductoXCodigoProducto($conexion, 'PORDRE'),0,'id_producto');
		$idProductoLechon=pg_fetch_result($ccp->obtenerIdProductoXCodigoProducto($conexion, 'PORHON'),0,'id_producto');
		$idProductoLechona=pg_fetch_result($ccp->obtenerIdProductoXCodigoProducto($conexion, 'PORONA'),0,'id_producto');
		
		$identificadorOperador=pg_fetch_result($ccp->abrirSitio($conexion, $filaCatastro['id_sitio']), 0, 'identificador_operador');
		$qObtenerMaximoControlReproduccion=$ccp->obtenerMaximoControlReproduccion($conexion, $identificadorOperador,$idProductoReproduccion);
		$qCantidadCatastro=$ccp->obtenerCantidadCatastroXOperador($conexion,  $identificadorOperador, '('.$idProductoReproduccion.')');
		$qCantidadCatastroCrias=$ccp->obtenerCantidadCatastroXOperador($conexion,  $identificadorOperador, '('.$idProductoLechon.','.$idProductoLechona.')');
			
		if($filaCatastro['id_producto']==$idProductoReproduccion){
		
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

			$ccp->guardarControlReproduccion($conexion, $identificadorOperador, $idProductoReproduccion, $cupoCria,$cantidadCriaB);
		
		}
	
	
		//TODO: Busco el ultima transaccion de catastro para sacar la cantidad total
		$qConsultarCantidadTotalProducto = $ccp->consultarCantidadTotalProducto($conexion, $filaCatastro['id_area'], $filaCatastro['id_producto'],$filaCatastro['unidad_comercial'],$filaCatastro['id_tipo_operacion']);
		//$filaCantidad = pg_fetch_assoc($qConsultarCantidadTotalProducto);
		$cantidadTotal =  (pg_num_rows($qConsultarCantidadTotalProducto)!=0 ? pg_fetch_result($qConsultarCantidadTotalProducto, 0, 'cantidad_total'):0) -$filaCatastro['cantidad'];
		
		//TODO: Guarda los datos de la transacion total de catastro
		$ccp->guardarCatastroTransaccionResta($conexion,  $idCatastro ,$filaCatastro['id_area'], $idConceptoCatastro,  $filaCatastro['id_producto'], $filaCatastro['cantidad'], $cantidadTotal,$filaCatastro['unidad_comercial'],$_SESSION['usuario'], $filaCatastro['id_tipo_operacion']);
		 
		$qCatastroIndividualDetalle = $ccp->abrirDetalleCatatroIndividualProducto($conexion, $idCatastro);
		while ($filaDetalle = pg_fetch_assoc($qCatastroIndividualDetalle)){
			$ccp->actualizarEstadoDetalleCatastroEliminado($conexion, $filaDetalle['id_detalle_catastro'], 'Eliminado por dar de baja el catastro');
		}
		
		$conexion->ejecutarConsulta("commit;");
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'Los datos han sido eliminados satisfactoriamente';
	
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

