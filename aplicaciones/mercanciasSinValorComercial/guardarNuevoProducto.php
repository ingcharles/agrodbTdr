<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorMercanciasSinValorComercial.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	$conexion = new Conexion();
	$ce = new ControladorMercanciasSinValorComercial();

	try {
		$idRegistro = htmlspecialchars($_POST['idRegistro'], ENT_NOQUOTES, 'UTF-8');

		$nombreTipoProducto= $_POST['nombreTipoProducto'];
		$idTipoProducto= $_POST['tipoProducto'];
		$idSubtipoProducto= $_POST['subTipoProducto'];
		$nombreSubtipoProducto= $_POST['nombreSubTipoProducto'];
		$idProducto= $_POST['producto'];
		$nombreProducto= $_POST['nombreProducto'];
		$sexo= $_POST['sexo'];
		$raza= $_POST['raza'];
		$edad= $_POST['edad'];
		$color= $_POST['color'];
		$identicacionProducto= $_POST['identificacionProducto'];

		$productoRepetido = $ce->verificarProductoExistente($conexion, $idProducto, $identicacionProducto, $idRegistro);

		if(pg_num_rows($productoRepetido) == 0){
			$conexion->ejecutarConsulta("begin;");
			$idProductoSolicitud = pg_fetch_result($ce->guardarDetalleSolicitud($conexion,$idTipoProducto, $nombreTipoProducto, $idSubtipoProducto,$nombreSubtipoProducto, $idProducto, $nombreProducto,$sexo,$raza,$edad,$color,$identicacionProducto,$idRegistro), 0, 'id_producto_solicitud');
			$conexion->ejecutarConsulta("commit;");
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $ce->imprimirLineaProducto($idProductoSolicitud, $nombreTipoProducto, $nombreProducto, $identicacionProducto);
		}else{
			$mensaje['mensaje'] = "No es posible agregar un mismo producto con el mismo identificador.";
		}
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