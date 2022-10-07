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
	
	//ARRAY
	$arrayErrorIdentificadorProducto = array ();
	
	for($i = 0; $i < count ( $idDetalleCatastro ); $i ++) {
		if($identificadorProducto[$i]!=""){
			
		if ($codigoEspecie == 'PORCI') {
			$qCodigoEspecie=$cc->obtenerEspecieXcodigo($conexion, $codigoEspecie );
			//echo  pg_fetch_result($qCodigoEspecie, 0, 'id_especies') , $identificadorProducto[$i]
			$qBuscarSerieArete = $cc->buscarSerieArete ( $conexion, pg_fetch_result($qCodigoEspecie, 0, 'id_especies') , $identificadorProducto[$i] );
			
			if (pg_num_rows ( $qBuscarSerieArete ) == 1) {
				if (pg_fetch_result($qBuscarSerieArete, 0, 'estado') == 'utilizado'){
					$arrayErrorIdentificadorProducto [] = ' #' . $identificadorProducto[$i] . ' utilizado';
				}else{
					
					//$ccp->actualizarIdentificadorProducto ( $conexion, $identificadorProducto[$i], 'utilizado' );
				}
			} else {
				$arrayErrorIdentificadorProducto [] = ' #' . $identificadorProducto[$i] . ' no existe';
			}
		}
	
		//$ccp->actualizarDetalleCatastroIdentificador($conexion, $idDetalleCatastro[$i], $identificadorProducto[$i]);
		
		}
	}
	
	
	
	if ($arrayErrorIdentificadorProducto != null) {
		$mensaje ['estado'] = 'error';
		$mensaje ['mensaje'] =  'Los identificadores son incorrectos o estan utilizados:';
	} else {
		try {
			
			//$ccp->actualizarCatastro($conexion,$_POST['idCatastro'],$_POST['peso'],$_POST['unidadMedidaPeso'],$_POST['numeroLote']);
			
	
		$conexion->ejecutarConsulta("commit;");
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = 'Identificadores correctos.';
	
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