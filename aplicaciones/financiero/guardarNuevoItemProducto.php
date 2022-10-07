<?php
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorCatalogos.php';
	require_once '../../clases/ControladorFinanciero.php';

	$mensaje = array();
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	
	$idServicio = $_POST['idServicioProducto'];
	$producto = $_POST['producto'];
				
	try {
		$conexion = new Conexion();
		$cf = new ControladorFinanciero();
		$cc = new ControladorCatalogos();
		
		$ingreso = true;
		$seleccionarProducto = true;
		$imprimirServicioProducto = '';
		
		if(count($producto) != 0){
			
			$todosProductos = implode(',', $producto);
			$todosProductos = "(".rtrim($todosProductos,',').")";
			
			$aProducto = $cf->obtenerServicioPorProducto($conexion, $idServicio, $todosProductos);
			//$productoExoneracion = pg_fetch_assoc($aProducto);
			
			if(pg_num_rows($aProducto)!= 0){
				$ingreso = false;
				while ($fila = pg_fetch_assoc($aProducto)){
					$qProducto = $cc->obtenerNombreProducto($conexion, $fila['id_producto']);
					$productosIngresados .= pg_fetch_result($qProducto, 0, 'nombre_comun').', ';
				}
			}
			
		}else{
			$seleccionarProducto = false;
		}
		
		if($ingreso && $seleccionarProducto){
			
		
			for($i = 0; $i < count($producto); $i++){
				//echo "hola";
				$servicioProducto = $cf->buscarServicioProducto($conexion, $producto[$i]) ;
								
				if(pg_num_rows($servicioProducto) == 0){
						
					$idServicioProducto = pg_fetch_result($cf->guardarNuevoServicioPorProducto($conexion, $idServicio, $producto[$i], 'false'), 0, 'id_servicio_producto');
					$nombreProducto = pg_fetch_assoc($cc->obtenerNombreProducto($conexion, $producto[$i]));
						
					$imprimirServicioProducto .= $cf->imprimirLineaServicioProducto($idServicioProducto, $producto[$i], $nombreProducto['nombre_comun'],'inactivo');
						
					$mensaje['estado'] = 'exito';
					$mensaje['mensaje'] = $imprimirServicioProducto;
						
					
				}else{
					$mensaje['estado'] = 'error';
					$mensaje['mensaje'] = 'Los productos' .trim($productosIngresados,', ').' ya se ha registrado para un servicio.';
				}
				
			}
			
			
		}else{
			$mensaje['estado'] = 'error';
			if(!$seleccionarProducto){
				$mensaje['mensaje'] = 'Seleccione al menos un producto.';
			}else{
				$mensaje['mensaje'] = 'Los productos '.trim($productosIngresados,', ').' ya han sido ingresadas previamente para el servicio seleccionado.';
			}
			
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


