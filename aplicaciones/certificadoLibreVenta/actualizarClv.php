<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorClv.php';
require_once '../../clases/ControladorRequisitos.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	
	$datos = array('idClv' => $_POST['idClv'],
		'nombreOperador' => $_POST['nombreOperador'],
		'direccionOperador' => $_POST['direccionOperador'],
		'tipoProductoClv' => $_POST['tipoProductoClv'],
		'observacion' => $_POST['observacion']
	);

	try {
		
		$conexion = new Conexion();
		$cclv = new ControladorClv();
		$cr = new ControladorRequisitos();
		
		$datosProducto = $cclv->listaProdInocuidad($conexion, $datos['idClv']);
		$composicionProducto = $cr->listarComposicionProductosInocuidad($conexion, $datosProducto[0]['id_producto']);
		$especieProducto = $cr->listarUsos($conexion, $datosProducto[0]['id_producto']);
		
		$usoProducto = array();
		$especie = array();
		
		while ($fila = pg_fetch_assoc($especieProducto)) {
			$usoProducto[] = $fila['nombre_uso'];
			if($fila['id_especie']){
				$especie[] = $fila['nombre'];
			}
			
		}
		
		$presentacionProducto = $cr->listarCodigoInocuidad($conexion, $datosProducto[0]['id_producto']);
		
		$presentacion = array();
		
		while ($fila = pg_fetch_assoc($presentacionProducto)) {
			$presentacion[] = $fila['presentacion'].' '.$fila['unidad_medida'];
		}
		
		$cclv->eliminarDetalleProductosClv($conexion, $datos['idClv']);
		
		$fechaVencimiento = date('Y/m/d',strtotime(date("Y/m/d")."+ 1 year" ));
		
		if($datos['tipoProductoClv'] =='IAP'){
			
			$cclv->actualizarClvPlaguicida($conexion, $datos['nombreOperador'],$datos['direccionOperador'],
				$datosProducto[0]['subpartida'],$datosProducto[0]['producto'],$datosProducto[0]['codigo_producto'],
				$datosProducto[0]['formulacionGuia'], $datosProducto[0]['numero_registro'],$datos['observacion'],$datos['idClv'],
				$datosProducto[0]['fecha_registro'], $fechaVencimiento);
				
			while ($fila = pg_fetch_assoc($composicionProducto)){
				$cclv -> guardarDetalleCertificadoProductoP($conexion, $datos['idClv'], $fila['tipo_componente'] .' - '. $fila['ingrediente_activo'], $fila['concentracion'], $fila['unidad_medida']);
			}
			
			
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = 'Los datos han sido ingresados satisfactoriamente.';
		}
		
		if($datos['tipoProductoClv'] =='IAV'){
			if(count($especie) != 0){
				
				if(pg_num_rows($composicionProducto) != 0){
				
					$especies = implode(', ',array_unique($especie));
					$presentaciones = substr(implode(', ',$presentacion), 0, 1023);
					
					$usoProducto = implode(', ',array_unique($usoProducto));
					
					$cclv->actualizarClvVeterinario($conexion, $datos['nombreOperador'], $datos['direccionOperador'],
						$datosProducto[0]['subpartida'], $datosProducto[0]['producto'],
						$presentaciones, $datosProducto[0]['clasificacion'], $datosProducto[0]['formulacionGuia'], $usoProducto,
						$especies, $datosProducto[0]['numero_registro'],$datos['observacion'],$datos['idClv'],
						$datosProducto[0]['fecha_registro'], $fechaVencimiento, $datosProducto[0]['codigo_producto']);
						
					while ($fila = pg_fetch_assoc($composicionProducto)){
						$cclv->guardarDetalleCertificadoProductoV($conexion, $datos['idClv'], $fila['tipo_componente'],
							$fila['concentracion'],$fila['unidad_medida'],$fila['ingrediente_activo']);
					}
				
					$mensaje['estado'] = 'exito';
					$mensaje['mensaje'] = 'Los datos han sido ingresados satisfactoriamente.';
						
				}else{
					$mensaje['estado'] = 'error';
					$mensaje['mensaje'] = 'Debe seleccionar por lo menos una composición para el producto.';
				}
			
			}else{
				$mensaje['estado'] = 'error';
				$mensaje['mensaje'] = 'Debe seleccionar por lo menos una especie.';
			}
		}	
		
	
	$mensaje['estado'] = 'exito';
	$mensaje['mensaje'] = 'Los datos han sido ingresados satisfactoriamente.';
	
	$conexion->desconectar();			
	echo json_encode($mensaje);
			
	} catch (Exception $ex){
		pg_close($conexion);
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = "Error al ejecutar sentencia";
		echo json_encode($mensaje);
	}
}
catch (Exception $ex){
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}

?>