<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';

$identificadorOperador = htmlspecialchars($_POST['identificadorOperador'], ENT_NOQUOTES, 'UTF-8');
$idOperadorTipoOperacion = htmlspecialchars($_POST['idOperadorTipoOperacion'], ENT_NOQUOTES, 'UTF-8');
$idProducto = htmlspecialchars($_POST['idProducto'], ENT_NOQUOTES, 'UTF-8');
$idOperacion = htmlspecialchars($_POST['idOperacion'], ENT_NOQUOTES, 'UTF-8');
$idPaisDestino = htmlspecialchars($_POST['idPaisDestino'], ENT_NOQUOTES, 'UTF-8');
$usoDestino = htmlspecialchars($_POST['usoDestino'], ENT_NOQUOTES, 'UTF-8');
$nombreProducto = htmlspecialchars($_POST['nombreProducto'], ENT_NOQUOTES, 'UTF-8');
$nombrePais = htmlspecialchars($_POST['nombrePais'], ENT_NOQUOTES, 'UTF-8');

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{

	$conexion = new Conexion();
	$cr = new ControladorRegistroOperador();

	try{
		$buscarRegistro = $cr->verificarRegistroMercanciasPecuaria($conexion, $idProducto, $idPaisDestino, $usoDestino, $identificadorOperador, $idOperacion, $idOperadorTipoOperacion);

		if (pg_num_rows($buscarRegistro) == 0){

			$conexion->ejecutarConsulta("begin;");

			$idCentroPecuario = pg_fetch_result($cr->guardarRegistroMercanciasPecuaria($conexion, $idProducto, $idPaisDestino, $usoDestino, $identificadorOperador, $idOperacion, $idOperadorTipoOperacion), 0, 'id_centro_pecuario');

			$conexion->ejecutarConsulta("commit;");

			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cr->imprimirLineaMercanciasPecuaria($idCentroPecuario, $nombreProducto, $nombrePais, $usoDestino);
		}else{
			$mensaje['mensaje'] = 'El registro ya ha sido asignado previamente.';
		}
	}catch (Exception $ex){
		$conexion->ejecutarConsulta("rollback;");
		$mensaje['mensaje'] = $ex->getMessage();
		$mensaje['error'] = $conexion->mensajeError;
	}finally {
		$conexion->desconectar();
	}
}catch (Exception $ex){
	$mensaje['mensaje'] = $ex->getMessage();
	$mensaje['error'] = $conexion->mensajeError;
}finally {
	echo json_encode($mensaje);
}

?>

