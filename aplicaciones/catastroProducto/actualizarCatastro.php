<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastroProducto.php';
require_once '../../clases/ControladorCatalogos.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	$conexion = new Conexion();
	$ccp = new ControladorCatastroProducto();
	$cc = new ControladorCatalogos ();
	
	$codigoEspecie = $_POST ['codigoEspecie'];
	$idDetalleCatastro = $_POST ['idDetalleCatastro'];
	$identificadorProducto = $_POST ['identificador'];
	$identificadorAntiguo = $_POST ['identificadorAntiguo'];
	
	//ARRAY
	$arrayErrorIdentificadorProducto = array ();
	
	for($i = 0; $i < count ( $idDetalleCatastro ); $i ++) {
		if($identificadorProducto[$i]!=""){
			
		if ($codigoEspecie == 'PORCI') {
			$qCodigoEspecie=$cc->obtenerEspecieXcodigo($conexion, $codigoEspecie );
			$qBuscarSerieArete = $cc->buscarSerieArete ( $conexion, pg_fetch_result($qCodigoEspecie, 0, 'id_especies') , strtoupper($identificadorProducto[$i]) );
			
			if (pg_num_rows ( $qBuscarSerieArete ) == 1) {
				if (pg_fetch_result($qBuscarSerieArete, 0, 'estado') == 'utilizado'){
					$arrayErrorIdentificadorProducto [] = ' #' . strtoupper($identificadorProducto[$i]) . ' utilizado';
				}else{
					$conexion->ejecutarConsulta("begin;");
					$ccp->actualizarIdentificadorProducto ( $conexion, strtoupper($identificadorProducto[$i]), 'utilizado' );
				}
			} else {
				$arrayErrorIdentificadorProducto [] = ' #' . strtoupper($identificadorProducto[$i]) . ' no existe';
			}
		}
		
		$conexion->ejecutarConsulta("begin;");
		$ccp->actualizarDetalleCatastroIdentificador($conexion, $idDetalleCatastro[$i],strtoupper($identificadorProducto[$i]));
		$ccp->actualizarDetalleVacunacionIdentificador($conexion, $identificadorAntiguo[$i],strtoupper($identificadorProducto[$i]));
		$ccp->actualizarDetalleMovilizacionIdentificador($conexion, $identificadorAntiguo[$i],strtoupper($identificadorProducto[$i]));
		
		
		}
	}
	
	
	
	if ($arrayErrorIdentificadorProducto != null) {
		$mensaje ['estado'] = 'error';
		$mensaje ['mensaje'] = array ( array ('Los identificadores son incorrectos: '), $arrayErrorIdentificadorProducto );
	} else {
		try {
			$conexion->ejecutarConsulta("commit;");
			$conexion->ejecutarConsulta("begin;");
			$ccp->actualizarCatastro($conexion,$_POST['idCatastro'],$_POST['peso'],$_POST['unidadMedidaPeso'],$_POST['numeroLote']);

			$conexion->ejecutarConsulta("commit;");
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = 'El catastro ha sido actualizado satisfactoriamente.';
		
			 } catch (Exception $ex) {
	 	$conexion->ejecutarConsulta("rollback;");
	 	$mensaje['mensaje'] = $ex->getMessage();
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