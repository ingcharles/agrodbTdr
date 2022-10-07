<?php
class ControladorTrazabilidad{

	public function guardarRegistroIngreso ($conexion,$codproveedor,$calidad,$variedad,$idProducto,$nombreProducto,$idAreaProveedor, $nombreAreaProveedor,$idSitioProveedor, $nombreSitioProveedor,$idOperacionProveedor,$nombreOperacionProveedor,$operador,$idAreaOperador,$nombreAreaOperador,$idOperacionOperador,$nombreOperacionOperador){
		
		$res = $conexion->ejecutarConsulta("INSERT INTO
											g_trazabilidad.registro_ingreso (id_codigo_proveedor,id_calidad_producto,id_variedad_producto,
																				id_producto, nombre_producto,
																				id_area_proveedor, nombre_area_proveedor,
																				id_sitio_proveedor, nombre_sitio_proveedor,
																				id_operacion_proveedor, nombre_operacion_proveedor,
																				id_operador,
																				id_area_operador, nombre_area_operador,
																				id_operacion_operador, nombre_operacion_operador)
											VALUES
											('$codproveedor','$calidad','$variedad',
											$idProducto, '$nombreProducto',
											$idAreaProveedor, '$nombreAreaProveedor',
											$idSitioProveedor, '$nombreSitioProveedor',
											$idOperacionProveedor, '$nombreOperacionProveedor',
											'$operador',
											$idAreaOperador, '$nombreAreaOperador',
											$idOperacionOperador, '$nombreOperacionOperador') RETURNING id_registro_ingreso;");

		return $res;
	}

	public function guardarDetalleRegistro ($conexion,$cantidad, $unidadmedida, $bultos, $tipo, $max){
        
		$res = $conexion->ejecutarConsulta("INSERT INTO
											g_trazabilidad.detalle_ingreso (cantidad_producto,id_unidad_cantidad,numero_bultos,id_descripcion_bultos,fecha_ingreso,fecha_confirmacion,estado,id_registro_ingreso)
											VALUES
											('$cantidad','$unidadmedida','$bultos','$tipo',now(),now(),'1','$max');");

		return $res;
	}


	public function convertirCantidad ($cantidad,$unidadMedida){

		switch ($unidadMedida){
			case 'KG': $resultado = $cantidad; break;
			case 'G': $resultado = $cantidad/1000; break;
			case 'MG': $resultado = $cantidad/1000000; break;
			case 'LB': $resultado = $cantidad/2.2; break;
			case 'OZ': $resultado = $cantidad/0.0283; break;
			case 'T': $resultado = $cantidad*1000; break;
		}
	
		return $resultado;
	}

		
	public function eliminarDetalle($conexion, $detalleId){
		   	$res = $conexion->ejecutarConsulta("UPDATE
													g_trazabilidad.detalle_ingreso
												SET
													estado=0
												WHERE
													id_detalle_ingreso =$detalleId;");
		return $res;
	}
	
	public function listarSitiosRegistroIngreso($conexion, $identificadorProveedor, $identificadorOperador){
		$res = $conexion->ejecutarConsulta("SELECT 
												distinct(s.id_sitio),
												s.nombre_lugar
											FROM
												g_trazabilidad.registro_ingreso ri,
												g_operadores.sitios s
											WHERE
												ri.id_sitio_proveedor = s.id_sitio
												and id_codigo_proveedor = '$identificadorProveedor'
												and id_operador = '$identificadorOperador'");
		return $res;
	}
	
	public function listarProductoRegistroIngreso($conexion, $identificadorProveedor, $identificadorOperador){
		$res = $conexion->ejecutarConsulta("SELECT 
												distinct(p.id_producto),
												p.nombre_comun
											FROM
												g_trazabilidad.registro_ingreso ri,
												g_catalogos.productos p
											WHERE
												ri.id_producto= p.id_producto
												and id_codigo_proveedor = '$identificadorProveedor'
												and id_operador = '$identificadorOperador'");
				return $res;
	}
	
	public function listarAreasRegistroIngreso($conexion, $identificadorProveedor, $identificadorOperador){
		$res = $conexion->ejecutarConsulta("SELECT 
												distinct(a.id_area),
												a.nombre_area,
												s.id_sitio
											FROM
												g_trazabilidad.registro_ingreso ri,
												g_operadores.areas a,
												g_operadores.sitios s
											WHERE
												ri.id_area_proveedor = a.id_area
												and s.id_sitio = ri.id_sitio_proveedor
												and a.id_sitio = s.id_sitio
												and id_codigo_proveedor = '$identificadorProveedor'
												and id_operador = '$identificadorOperador'");
				return $res;
	}
	
	public function listarFechasRegistroIngreso($conexion, $identificadorProveedor, $identificadorOperador){
		$res = $conexion->ejecutarConsulta("SELECT 
												distinct(di.fecha_ingreso),
												ri.id_area_proveedor
											FROM
												g_trazabilidad.registro_ingreso ri,
												g_trazabilidad.detalle_ingreso di
											WHERE
												ri.id_registro_ingreso = di.id_registro_ingreso
												and ri.id_codigo_proveedor = '$identificadorProveedor'
												and ri.id_operador = '$identificadorOperador'");
				return $res;
	}
	
	
	public function filtrarDetallesIngresosOperador($conexion, $identificadorOperador, $identificadorProveedor, $idProducto, $idSitio, $idArea, $fecha){
							
		$res = $conexion->ejecutarConsulta("
											SELECT 
												di.id_detalle_ingreso, cp.id_calidad_producto, cp.nombre as nombre_calidad,
												vp.id_variedad_producto, vp.nombre as nombre_variedad,
												di.cantidad_producto, di.id_unidad_cantidad,
												um.nombre as medida, di.numero_bultos, db.nombre as nombre_bultos
											FROM
												g_trazabilidad.registro_ingreso ri, g_catalogos.productos p,
												g_trazabilidad.detalle_ingreso di, g_catalogos.descripcion_bultos db,
												g_catalogos.calidad_producto cp, g_catalogos.variedad_producto vp,
												g_catalogos.unidades_medidas um
												
											WHERE
												di.id_registro_ingreso= ri.id_registro_ingreso
												and ri.id_variedad_producto = vp.id_variedad_producto
												and ri.id_calidad_producto = cp.id_calidad_producto
												and di.id_descripcion_bultos = db.id_descripcion_bultos
												and di.id_unidad_cantidad = um.id_unidad_medida
												and ri.id_producto = p.id_producto
												and id_codigo_proveedor = '$identificadorProveedor'
												and id_operador = '$identificadorOperador'
												and ri.id_area_proveedor = $idArea
												and ri.id_sitio_proveedor = $idSitio
												and ri.id_producto = $idProducto
												and di.fecha_ingreso = '$fecha'
												and di.estado = 1");
					
		return $res;
	}
	
	public function filtarReporteIngreso($conexion, $operador, $proveedor, $producto, $idSitio, $idArea, $fechaInicio, $fechaFin){
		
		$operador  = $operador!="" ? "'" . $operador . "'" : "null";
		$proveedor  = $proveedor!="" ? "'" . $proveedor . "'" : "null";
		$producto  = $producto!="" ? "'" . $producto . "'" : "null";
		$idSitio  = $idSitio!="" ? "'" . $idSitio . "'" : "null";
		$idArea  = $idArea!="" ? "'" . $idArea . "'" : "null";
		$fechaInicio  = $fechaInicio!="" ? "'" . $fechaInicio . "'" : "null";
		$fechaFin  = $fechaFin!="" ? "'" . $fechaFin . "'" : "null";
		
		$res = $conexion->ejecutarConsulta("SELECT 
												c.id_registro_ingreso, c.id_detalle_ingreso, c.id_calidad_producto, cp.nombre as nombre_calidad,
												c.id_variedad_producto, vp.nombre as nombre_variedad,
												c.cantidad_producto, c.id_unidad_cantidad,
												um.nombre as medida, c.numero_bultos, db.nombre as nombre_bultos
											FROM 
												g_trazabilidad.mostrar_registro_ingreso($operador, $proveedor, $producto, $idSitio, $idArea, $fechaInicio, $fechaFin) as c,
												g_catalogos.productos p, g_catalogos.descripcion_bultos db,
												g_catalogos.calidad_producto cp, g_catalogos.variedad_producto vp,
												g_catalogos.unidades_medidas um
											WHERE
												c.id_variedad_producto = vp.id_variedad_producto
												and c.id_calidad_producto = cp.id_calidad_producto
												and c.id_descripcion_bultos = db.id_descripcion_bultos
												and c.id_unidad_cantidad = um.id_unidad_medida
												and c.id_producto = p.id_producto");
		return $res;
	}
	
	public function datosRegistro ($conexion,$usuario){
		$res = $conexion->ejecutarConsulta("SELECT
												ri.id_codigo_proveedor,
												p.nombre_comun,
												sum(di.cantidad_producto) as cantidad_producto,
												um.codigo
											FROM
												g_trazabilidad.registro_ingreso ri,
												g_trazabilidad.detalle_ingreso di,
												g_catalogos.unidades_medidas um,
												g_catalogos.productos p
											WHERE
												ri.id_registro_ingreso = di.id_registro_ingreso
												and p.id_producto = ri.id_producto
												and di.id_unidad_cantidad=um.id_unidad_medida and
												di.estado != 0 and
												ri.id_operador='$usuario'
											GROUP BY
												ri.id_codigo_proveedor,
												p.nombre_comun,
												um.codigo;");
	
				return $res;
	}

}

