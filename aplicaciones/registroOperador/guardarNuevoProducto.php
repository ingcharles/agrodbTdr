<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorVigenciaDocumentos.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	$producto = $_POST['producto'];
	$identificadorOperador = htmlspecialchars($_POST['identificadorOperador'], ENT_NOQUOTES, 'UTF-8');
	$idOperacion = htmlspecialchars($_POST['idOperacion'], ENT_NOQUOTES, 'UTF-8');
	$nombreTipoProducto = htmlspecialchars($_POST['nombreTipoProducto'], ENT_NOQUOTES, 'UTF-8');
	$nombreSubtipoProducto = htmlspecialchars($_POST['nombreSubtipoProducto'], ENT_NOQUOTES, 'UTF-8');
	$nombreProducto = htmlspecialchars($_POST['nombreProducto'], ENT_NOQUOTES, 'UTF-8');
	$productoLaboratorio = htmlspecialchars($_POST['opcion'], ENT_NOQUOTES, 'UTF-8');
	$procesoLaboratorio = false;
	

	if(isset($productoLaboratorio) && ($productoLaboratorio == 'producto')){

		$estado = 'cargarProducto';
		$procesoLaboratorio = false;
	}else{
		
		$estado = 'documental';
		$procesoLaboratorio = true;
	}


	try{
		$conexion = new Conexion();
		$cc = new ControladorCatalogos();
		$cr = new ControladorRegistroOperador();
		$cvd = new ControladorVigenciaDocumentos();

		$ingreso = true;
		$seleccionarProducto = true;
		$productosIngresados = array();
		$imprimirOperacion = '';
		$bandera = array();

		$qOperacion = $cr->abrirOperacionXid($conexion, $idOperacion);
		$operacion = pg_fetch_assoc($qOperacion);

		if (count($producto) != 0){

			$todosProductos = implode(',', $producto);
			$todosProductos = "(" . rtrim($todosProductos, ',') . ")";

			$areasTipoOperacion = $cr->obtenerAreasOperacion($conexion, $idOperacion);

			foreach ($areasTipoOperacion as $areaOperacion){
				$vAreaProductoOperacion = $cr->buscarAreasOperacionProductoXSolicitud($conexion, $operacion['id_tipo_operacion'], $todosProductos, $areaOperacion['idArea'], $identificadorOperador);

				if (pg_num_rows($vAreaProductoOperacion) != 0){

					while ($fila = pg_fetch_assoc($vAreaProductoOperacion)){

						if (($fila['estado_operacion'] == 'porCaducar' && $fila['estado_anterior'] == 'registrado') || ($fila['estado_operacion'] == 'noHabilitado' && $fila['estado_anterior'] == 'porCaducar')){
							$ingreso = true;
							$bandera[] = $ingreso;
						}else{
							$ingreso = false;
							$productosIngresados[] = $fila['nombre_producto'];
							$bandera[] = $ingreso;
						}
					}
				}
			}
		}else{
			$seleccionarProducto = false;
		}

		$resultado = array_unique($bandera);

		if (count($resultado) == 1){
			if ($resultado[0]){
				$ingreso = true;
			}else{
				$ingreso = false;
			}
		}else if (count($resultado) == 2){
			$ingreso = false;
		}

		if ($ingreso && $seleccionarProducto){

			for ($i = 0; $i < count($producto); $i ++){

				$qOperacion = $cr->abrirOperacionXid($conexion, $idOperacion);
				$operacion = pg_fetch_assoc($qOperacion);

				$qProducto = $cc->obtenerNombreProducto($conexion, $producto[$i]);

				$qCabeceraVigencia = $cvd->buscarTipoOperacionCabeceraVigencia($conexion, $operacion['id_tipo_operacion']);

				$idVigencia = 0;

				if (pg_num_rows($qCabeceraVigencia) > 0){

					$cabeceraVigencia = pg_fetch_assoc($qCabeceraVigencia);
					if ($cabeceraVigencia['nivel_lista'] == 'operacion'){
						$idVigencia = $cabeceraVigencia['id_vigencia_documento'];
					}else{
						$qDetalleVigencia = $cvd->buscarVigenciaProducto($conexion, $cabeceraVigencia['id_vigencia_documento'], $producto[$i]);
						if (pg_num_rows($qDetalleVigencia) > 0){
							$detalleVigencia = pg_fetch_assoc($qDetalleVigencia);
							$idVigencia = $detalleVigencia['id_vigencia_documento'];
						}
					}
				}

				if ($operacion['id_producto'] == null){

					$cr->actualizarProductoOperacion($conexion, $idOperacion, $producto[$i], pg_fetch_result($qProducto, 0, 'nombre_comun'), $idVigencia);
					$idSolicitud = $idOperacion;
				}else{

					$qIdSolicitud = $cr->guardarNuevaOperacionPorTipoOperacion($conexion, $operacion['id_tipo_operacion'], $identificadorOperador, $operacion['id_operador_tipo_operacion'], $operacion['id_historial_operacion'], $estado, $idVigencia);
					$idSolicitud = pg_fetch_result($qIdSolicitud, 0, 'id_operacion');
					$cr->actualizarProductoOperacion($conexion, $idSolicitud, $producto[$i], pg_fetch_result($qProducto, 0, 'nombre_comun'), $idVigencia);

					foreach ($areasTipoOperacion as $areaOperacion){
						$cr->guardarAreaOperacion($conexion, $areaOperacion['idArea'], $idSolicitud);
					}
				}

				$cr->enviarOperacionEstadoAnterior($conexion, $idSolicitud);

				$imprimirOperacion .= $cr->imprimirLineaProductoOperacion($idSolicitud, $nombreTipoProducto, $nombreSubtipoProducto, pg_fetch_result($qProducto, 0, 'nombre_comun'), $producto[$i], 'SI', '0');
			}

			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $imprimirOperacion;
		}else{
			$mensaje['estado'] = 'error';
			if (! $seleccionarProducto){
				$mensaje['mensaje'] = 'Seleccione al menos un producto.';
			}else{
				$productosIngresados = array_unique($productosIngresados);
				$cadenaProducto = implode(', ', $productosIngresados);
				$mensaje['mensaje'] = 'Los productos ' . trim($cadenaProducto, ', ') . ' ya han sido ingresados previamente para el área y operacion seleccionada.';
			}
		}
		
		if($procesoLaboratorio){
			
			$rango = $_POST['rango'];
			$idOperadorTipoOperacion = $operacion['id_operador_tipo_operacion'];
			$idHistorialOperacion = $operacion['id_historial_operacion'];
			$imprimirLaboratorioOperacion = '';
			$mensaje['estado'] = 'exito';
			
			if(!$ingreso){
				$idSolicitud = pg_fetch_result($vAreaProductoOperacion, 0, 'id_operacion');
			}
			
			for ($i = 0; $i < count($rango); $i ++){
				
				$validacionRango = $cr->obtenerRangosLaboratorioProdcuto($conexion, $idSolicitud, $idOperadorTipoOperacion, $idHistorialOperacion, $rango[$i]);
				$datosAdiconalesProducto = pg_fetch_assoc($cc->listarProductoParametrosMetodoPorRango($conexion, $rango[$i]));
				
				if(pg_num_rows($validacionRango) == 0){
					
					$nombreParametro = $datosAdiconalesProducto['descripcion_parametro'];
					$nombreMetodo = $datosAdiconalesProducto['descripcion_metodo'];
					$nombreRango = $datosAdiconalesProducto['descripcion_rango'];
					
					$idOperacionLaboratorio = pg_fetch_assoc($cr->guardarOperacionesParametrosLaboratorio($conexion, $idSolicitud, $datosAdiconalesProducto['id_parametro'], $nombreParametro, 
						$datosAdiconalesProducto['id_metodo'], $nombreMetodo, $datosAdiconalesProducto['id_rango'],  $nombreRango, $idOperadorTipoOperacion, $idHistorialOperacion));
					
					$imprimirLaboratorioOperacion .= $cr->imprimirLineaProductoLaboratorio($idOperacionLaboratorio['id_operacion_laboratorio'], $nombreParametro, $nombreMetodo, $nombreRango, $nombreProducto, '1');
					
				}else{
					$mensaje['estado'] = 'error';
					$imprimirLaboratorioOperacion = 'El rango  ' . $datosAdiconalesProducto['descripcion_rango'] . ' método '. $datosAdiconalesProducto['descripcion_metodo'] . ' y parámetro '. $datosAdiconalesProducto['descripcion_parametro']. ' han sido ingresado previamente.';
					break;
				}
				
			}
			
			
			$mensaje['mensaje'] = $imprimirLaboratorioOperacion;
			
		}

		$conexion->desconectar();
		echo json_encode($mensaje);
	}catch (Exception $ex){
		pg_close($conexion);
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = "Error al ejecutar sentencia";
		echo json_encode($mensaje);
	}
}catch (Exception $ex){
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}
?>


