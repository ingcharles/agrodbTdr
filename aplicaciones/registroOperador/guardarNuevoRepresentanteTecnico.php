<?php

session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{

		$idAreaRepresentante = $_POST['idArea'];
		$idOperacion = $_POST['idOperacion'];
		$idOperadorTipoOperacion = $_POST['idOperadorTipoOperacion'];
		$idHistorialOperacion = $_POST['idHistorialOperacion'];

		$idAreaOperacion = $_POST['idAreaOperacion'];
		$idTipoProducto = ($_POST['tipoProducto'] == ''?'null':$_POST['tipoProducto']);
		$identificadorRepresentante = $_POST['numero'];
		$nombreRepresentante = $_POST['nombreTecnico'];
		$tituloAcademico = $_POST['nombreTituloTecnico'];
		$numeroRegistro = $_POST['numeroRegistro'];
		$nombreTipoProducto = ($_POST['nombreTipoProducto'] == '' ? 'N/A':$_POST['nombreTipoProducto']);

	try {
		$conexion = new Conexion();
		$cr = new ControladorRegistroOperador();

		$conexion->ejecutarConsulta("begin;");
		
		$idRepresentanteTecnico = $cr->consultarRepresentanteTecnicoOperacion($conexion, $idOperadorTipoOperacion, $idHistorialOperacion, $idOperacion, $idAreaOperacion);
		
		if(pg_num_rows($idRepresentanteTecnico) == '0'){
			$idRepresentanteTecnico=$cr->guardarNuevoRepresentanteTecnico($conexion, $idOperadorTipoOperacion,$idOperacion, $idAreaOperacion, $idHistorialOperacion);
			$idRepresentanteTecnico=pg_fetch_result($idRepresentanteTecnico, 0, 'id_representante_tecnico');
		}else{
			$idRepresentanteTecnico=pg_fetch_result($idRepresentanteTecnico, 0, 'id_representante_tecnico');
		}

		$verificacionRepresentante = $cr->verificarRepresentanteTecnico($conexion, $idRepresentanteTecnico, $idTipoProducto, $identificadorRepresentante, $nombreRepresentante, $tituloAcademico, $numeroRegistro, $idAreaRepresentante);

		if(pg_num_rows($verificacionRepresentante) == 0){
			$idDetalleRepresentanteTecnico = pg_fetch_result($cr->guardarNuevoDetalleRepresentanteTecnico($conexion, $idRepresentanteTecnico,$idTipoProducto, $identificadorRepresentante, $nombreRepresentante, $tituloAcademico, $numeroRegistro, $idAreaRepresentante), 0, 'id_detalle_representante_tecnico');

			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cr->imprimirLineaRepresentanteTecnico($idDetalleRepresentanteTecnico, $identificadorRepresentante, $nombreRepresentante, $tituloAcademico, $numeroRegistro, $nombreTipoProducto);

		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = 'El representante técnico ya ha sido ingresado previamente.';
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