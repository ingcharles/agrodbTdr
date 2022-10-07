<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorAuditoria.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{

	
	$idOperacion = htmlspecialchars ($_POST['idOperacion'],ENT_NOQUOTES,'UTF-8');
	$idVariedad = htmlspecialchars ($_POST['idVariedad'],ENT_NOQUOTES,'UTF-8');
	$nombreVariedad = htmlspecialchars ($_POST['nombreVariedad'],ENT_NOQUOTES,'UTF-8');
	$nombreTipoOperacion = htmlspecialchars ($_POST['nombreTipoOperacion'],ENT_NOQUOTES,'UTF-8');
	$idProducto = htmlspecialchars ($_POST['idProducto'],ENT_NOQUOTES,'UTF-8');
		
	/*
	$tipo_aplicacion = ($_SESSION['idAplicacion']);*/
	
	try {
		$conexion = new Conexion();
		$cro = new ControladorRegistroOperador();
		
		
		$qResultadoMultiple=$cro->buscarOperacionesMultiplesVariedades($conexion, $idOperacion);
		$ResultadoMultiple = pg_fetch_assoc($qResultadoMultiple);
	
		$qResultadoExisteOperacion=$cro->buscarExisteOperacionVariedad($conexion, $idOperacion);
		
		if(pg_num_rows($cro->buscarOperacionesVariedades($conexion, $idOperacion, $idVariedad))==0){
		
		
			if ($ResultadoMultiple['multiple_variedad']=='f'){
				if(pg_num_rows($qResultadoExisteOperacion)<1){	
					$cro->guardarOperacionVariedad($conexion, $idOperacion, $idVariedad);
					$mensaje['estado'] = 'exito';
					$mensaje['mensaje'] = $cro->imprimirOperacionVariedad($idOperacion,$idVariedad,$nombreTipoOperacion,$nombreVariedad);
				}
				else{
					$mensaje['mensaje'] = 'Para esta operación se permite declarar una sola variedad de producto.';
				}
			}else{
					$cro->guardarOperacionVariedad($conexion, $idOperacion, $idVariedad);
					$mensaje['estado'] = 'exito';
					$mensaje['mensaje'] = $cro->imprimirOperacionVariedad($idOperacion,$idVariedad,$nombreTipoOperacion,$nombreVariedad);
			}
		}else{
			$mensaje['mensaje'] = 'La variedad ya ha sido asignada.';
		}
		/*$ca = new ControladorAuditoria();*/
		
		
		/*if(pg_num_rows($cro->buscarOperacionesSiHayMultiplesVariedades($conexion, $idOperacion, $idProducto))==0){
			
			$mensaje['mensaje'] = 'Solo se puede agregar una variedad a este producto.';
		}
		else{*/
		
		
		
	/*	if(pg_num_rows($cro->buscarOperacionesVariedades($conexion, $idOperacion, $idVariedad))==0){
			$cro->guardarOperacionVariedad($conexion, $idOperacion, $idVariedad);
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cro->imprimirOperacionVariedad($idOperacion,$idVariedad,$nombreTipoOperacion,$nombreVariedad);
			
		}
		else {
			$mensaje['mensaje'] = 'La variedad ya ha sido asignada.';
		}
		*/
		
		
		
		//}
		
		/*if(pg_num_rows($cr->buscarCodigoComplementarioSuplementario($conexion, $idProducto, $codigoComplementario, $codigoSuplementario))==0){
			$cr -> guardarNuevoCodigoComplementarioSuplementario($conexion, $idProducto, $codigoComplementario, $codigoSuplementario);
			
			/*AUDOTORIA*/
			
			/*$qTransaccion = $ca -> buscarTransaccion($conexion, $idProducto,  $_SESSION['idAplicacion']);
			$transaccion = pg_fetch_assoc($qTransaccion);
			
			if($transaccion['id_transaccion'] == ''){
				$qLog = $ca -> guardarLog($conexion,$tipo_aplicacion);
				$qTransaccion = $ca ->guardarTransaccion($conexion, $idProducto, pg_fetch_result($qLog, 0, 'id_log'));
			}
			
			$ca ->guardarInsert($conexion, pg_fetch_result($qTransaccion, 0, 'id_transaccion'),$_SESSION['usuario'],'El usuario <b>' . $_SESSION['datosUsuario'] . '</b> ha asociado al producto con id '.$idProducto.' el codigo complementaio '.$codigoComplementario.' y el codigo suplementario '.$codigoSuplementario );
			
			/*FIN AUDITORIA*/
			
			/*$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cr-> imprimirCodigoComplementarioSuplementario($idProducto, $codigoComplementario, $codigoSuplementario);
		}else{
			$mensaje['mensaje'] = 'El código complementario y suplementario ya ha sido asignado.';
		}*/
		
	
		
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