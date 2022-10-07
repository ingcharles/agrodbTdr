<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
		$idProducto = $_POST['productoProveedor'];
		$proveedor = $_POST['proveedor'];
		$operacion = $_POST['operacion'];
		
	try {
		$conexion = new Conexion();
		$cr = new ControladorRegistroOperador();

		$proveedor = $cr->obtenerOperadoresAprobados($conexion, $idProducto, $proveedor);
		
		if($proveedor[0]['operador'] != '')
		{
			//$cr->guardarProveedoresSolicitud($conexion, $listaIdSolicitud[$j]['id'], $proveedor, $_SESSION['usuario'],$operacion,$idProducto);
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = 'El proveedor se encuentra registrado y aprobado.';
		}
		else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = 'El proveedor no se encuentra autorizado.';
		}
		
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
