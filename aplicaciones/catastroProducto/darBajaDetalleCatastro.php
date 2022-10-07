<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastroProducto.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{

	$idDetalleCatastro = htmlspecialchars ($_POST['idDetalleCatastro'],ENT_NOQUOTES,'UTF-8');
	$idConceptoCatastro = htmlspecialchars ($_POST['idConceptoCatastro'],ENT_NOQUOTES,'UTF-8');
	$idCatastro = htmlspecialchars ($_POST['idCatastro'],ENT_NOQUOTES,'UTF-8');

	$conexion = new Conexion();
	$ccp = new ControladorCatastroProducto();
	try {
		 
		$conexion->ejecutarConsulta("begin;");
		 
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

			$cantidadReproduccion=28;
			
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
		 		
		$qBuscarDetalle = $ccp->consultarDatosDetalleCatastro($conexion, $idDetalleCatastro);
		$fila = pg_fetch_assoc($qBuscarDetalle);

		//echo "cantidad".pg_num_rows($qBuscarDetalle)."<br>";
		
		//TODO: Busco el ultima transaccion de catastro para sacar la cantidad total
		$qConsultarCantidadTotalProducto = $ccp->consultarCantidadTotalProducto($conexion, $fila['id_area'], $fila['id_producto'],$fila['unidad_comercial'],$fila['id_tipo_operacion']);
		$cantidadTotal =  (pg_num_rows($qConsultarCantidadTotalProducto)!=0 ? pg_fetch_result($qConsultarCantidadTotalProducto, 0, 'cantidad_total'):0) -1;

		//TODO: Guarda los datos de la transacion total de catastro
		$ccp->guardarCatastroTransaccionResta($conexion,$idCatastro ,$fila['id_area'], $idConceptoCatastro,  $fila['id_producto'], 1, $cantidadTotal,$fila['unidad_comercial'],$_SESSION['usuario'],$fila['id_tipo_operacion']);
		 
		
		$ccp->actualizarEstadoDetalleCatastroEliminado($conexion, $idDetalleCatastro, 'Eliminado por dar de baja el catastro');

		$conexion->ejecutarConsulta("commit;");
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = $idDetalleCatastro;

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
