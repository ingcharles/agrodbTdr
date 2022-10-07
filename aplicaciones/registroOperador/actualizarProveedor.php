<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorCatalogos.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
		$id = $_POST['id'];
		$identificador = $_POST['idOperador'];
		$idProveedor = $_POST['idProveedor'];
		$idProducto = $_POST['producto'];
		$nombreProducto = $_POST['nombreProducto'];
		
		$idPais = $_POST['pais'];
		$nombrePais = $_POST['nombrePais'];
		$idTipoOperacion = $_POST['operacion'];
		$nombreOperacion = $_POST['nombreOperacion'];
		
	try {
		$conexion = new Conexion();
		$cr = new ControladorRegistroOperador();
		$cc = new ControladorCatalogos();
		
		$qProveedor = $cr->buscarOperador($conexion, $idProveedor);
		
		if (pg_num_rows($qProveedor) > 0){
			if($idTipoOperacion != ''){
				$cr->actualizarProveedorComercioExterior($conexion, $id, $idProveedor, $identificador, $idTipoOperacion, $nombreOperacion, $idProducto, $nombreProducto, $idPais, $nombrePais);
			}else{
				$codigoPais=$cc->obtenerIdLocalizacion($conexion,'ECUADOR', 'PAIS');
				$cr->actualizarProveedor($conexion, $id, $idProveedor, $identificador, $idProducto, $nombreProducto, pg_fetch_result($codigoPais, 0, 'id_localizacion'), 'Ecuador');
			}
			
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = 'El proveedor ha sido actualizado satisfactoriamente.';
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = 'Por favor contáctese con su proveedor para que se registre en Agrocalidad.';
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