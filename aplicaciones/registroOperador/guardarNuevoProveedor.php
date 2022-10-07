<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorCatalogos.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
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
		
		//Verifica si existe el operador - proveedor
		//$qProveedor = $cr->buscarOperador($conexion, $idProveedor);
		
		//Verifica si el operador - proveedor posee una operacion con ese producto
		$qProveedor = $cr->obtenerDatosProveedor($conexion, $idProveedor, $idProducto);
		
		if (pg_num_rows($qProveedor) > 0){
			if($idPais != ''){
				
				$proveedorIngresado = $cr->buscarProductoProveedor($conexion, $identificador, $idProveedor, $idProducto, $idPais); 
				
				if(pg_num_rows($proveedorIngresado) == 0){
					$cr->guardarNuevoProveedorComercioExterior($conexion, $idProveedor, $identificador, $idTipoOperacion, $nombreOperacion,$idProducto, $nombreProducto, $idPais, $nombrePais);
					
					$mensaje['estado'] = 'exito';
					$mensaje['mensaje'] = 'El proveedor ha sido creado satisfactoriamente.';
					
				}else{
					$mensaje['estado'] = 'error';
					$mensaje['mensaje'] = 'El proveedor ya ha sido registrado.';
				}
				
				
			}else{
				
				$codigoPais=$cc->obtenerIdLocalizacion($conexion,'ECUADOR','PAIS');
				
				$proveedorIngresado = $cr->buscarProductoProveedor($conexion, $identificador, $idProveedor, $idProducto, pg_fetch_result($codigoPais, 0, 'id_localizacion'));
								
				if(pg_num_rows($proveedorIngresado) == 0){
					$cr->guardarNuevoProveedor($conexion, $idProveedor, $identificador, $idTipoOperacion, $idProducto, $nombreProducto, pg_fetch_result($codigoPais, 0, 'id_localizacion'), 'Ecuador');
					
					$mensaje['estado'] = 'exito';
					$mensaje['mensaje'] = 'El proveedor ha sido creado satisfactoriamente.';
					
				}else{
					$mensaje['estado'] = 'error';
					$mensaje['mensaje'] = 'El proveedor ya ha sido registrado.';
				}
	
			}
			
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = 'El proveedor no posee registrado el producto '.$nombreProducto;
			//Por favor contáctese con su proveedor para que se registre en Agrocalidad.
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
