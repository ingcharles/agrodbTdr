<?php

class ControladorNotificacionEnfermedades{

	/////OBTENER TIPO DE PRODUCTO, SUBTIPO DE PRODUCTO, PRODUCTO

	public function listaTipoProducto($conexion, $tipo, $idOperador, $tipoProducto, $subtipoProducto){

		$columnas = '';
		$busqueda = '';

		switch ($tipo){
				
			case 'tipoProducto':
				$columnas= " distinct (tp.id_tipo_producto), tp.nombre, id_area";
			break;
			case 'subtipoProducto':
				$columnas= " distinct (stp.id_subtipo_producto), stp.nombre";
				$busqueda = "and tp.id_tipo_producto = '$tipoProducto'";
			break;
			case 'producto':
				$columnas = "distinct (p.id_producto), p.nombre_comun";
				$busqueda = "and stp.id_subtipo_producto = '$subtipoProducto' and o.estado='registrado'";
			break;
				
		}

		$res = $conexion->ejecutarConsulta("SELECT
												".$columnas."
											FROM
												g_operadores.operaciones o,
												g_catalogos.productos p,
												g_catalogos.subtipo_productos stp,
												g_catalogos.tipo_productos tp
											WHERE
												o.id_producto = p.id_producto and
												p.id_subtipo_producto = stp.id_subtipo_producto and
												stp.id_tipo_producto = tp.id_tipo_producto and
												o.identificador_operador = '$idOperador' 
												".$busqueda.";");
		return $res;
	}

	/////OBTENER OPERACIONES PERMITIDAS	
	
	public function OperacionesPermitidas($conexion, $identificador,$idProducto){
		
		$res=$conexion->ejecutarConsulta("SELECT
												distinct
												(t.nombre),t.id_tipo_operacion, t.codigo
											FROM
												g_operadores.operaciones op , 
												g_catalogos.tipos_operacion t ,
												g_catalogos.productos p 
											WHERE
												op.id_tipo_operacion = t.id_tipo_operacion and 
												op.id_producto = p.id_producto and
												op.identificador_operador = '$identificador' and
												op.estado='registrado' and
												p.id_producto=$idProducto;");
		return $res;

	}

	
	/////OBTENER TIPO ENFERMEDAD

	public function obtenerTipoEnfermedad($conexion, $idProducto){
			
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.productos p,
												g_catalogos.tipos_enfermedades te,
												g_notificaciones_enfermedades.detalle_productos_tipos_enfermedades dpte
											WHERE
												dpte.id_producto=p.id_producto and
												dpte.id_tipo_enfermedad=te.id_tipo_enfermedad and
												dpte.id_producto='$idProducto';");
		return $res;
	}

	/////OBTENER ENFERMEDAD
	
	public function obtenerEnfermedad($conexion, $idTipoEnfermedad){
			
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.enfermedades e,
												g_catalogos.tipos_enfermedades te
											WHERE
												te.id_tipo_enfermedad= e.id_tipo_enfermedad and
												e.id_tipo_enfermedad='$idTipoEnfermedad';");
		return $res;
	}


	/////GUARDAR ENFERMEDADES
	
	public function guardarEnfermedades ($conexion,$idProducto,$nombreProducto,$idTipoOperacion,$fechaReporte,$identificadorDuenio,$identificador,$identificadorAnimal,$nombreAnimal,$archivoRegistroEnfermedad,$laboratorio,$descripcionEnfermedad){
			
		$res = $conexion->ejecutarConsulta("INSERT INTO
												g_notificaciones_enfermedades.enfermedades_zoonosicas
												(id_producto, 
												nombre_producto, 
												id_tipo_operacion, 
												fecha_reporte,
												identificador_duenio,
												identificador, 
												identificador_animal, 
												nombre_animal,
												ruta_adjunto,
												laboratorio,
												descripcion_enfermedad_zoonosica)
											VALUES 
												('$idProducto',
												'$nombreProducto',
												'$idTipoOperacion', 
												'$fechaReporte', 
												'$identificadorDuenio',
												'$identificador',
												'$identificadorAnimal',
												'$nombreAnimal',
												'$archivoRegistroEnfermedad',
												'$laboratorio',
												'$descripcionEnfermedad')
											RETURNING id_enfermedad_zoonosica;");

		return $res;
	}



	/////GUARDAR DETALLE ENFERMEDADES
	
	public function guardarDetalleEnfermedades ($conexion,$idTipoEnfermedad,$idEnfermedad,$idEnfermedadZoonosica){
			
		$res = $conexion->ejecutarConsulta("INSERT INTO
												g_notificaciones_enfermedades.detalle_enfermedades_zoonosicas
												(id_tipo_enfermedad, 
												id_enfermedad,  
												id_enfermedad_zoonosica)
											VALUES 
												('$idTipoEnfermedad',
												'$idEnfermedad', 
												'$idEnfermedadZoonosica');");

		return $res;
	}


	/////LISTA MOVILIZACION///

	public function listarReporteEnfermedades($conexion, $identificador){
		$res = $conexion->ejecutarConsulta("SELECT
												ez.nombre_producto,
												ez.identificador_animal,
												ez.identificador_duenio,
												ez.fecha_reporte,
												ez.id_enfermedad_zoonosica
											FROM
												g_notificaciones_enfermedades.enfermedades_zoonosicas ez
											WHERE
												identificador='$identificador'
											ORDER BY
												fecha_reporte asc;");
		return $res;
	}


	///BUSCAR ENCABEZADO DE REPORTE///
	
	public function buscarReporteEnfermedades($conexion, $idenfermedad){
	 $res = $conexion->ejecutarConsulta("SELECT
									 			ez. id_enfermedad_zoonosica,ez.id_producto,ez.nombre_producto,ez.nombre_animal,ez.identificador_animal,ez.identificador_duenio,e.nombre_enfermedad,te.nombre_tipo_enfermedad,ez.descripcion_enfermedad_zoonosica,ez.ruta_adjunto
									 		FROM
										 		g_notificaciones_enfermedades.enfermedades_zoonosicas ez,
										 		g_notificaciones_enfermedades.detalle_enfermedades_zoonosicas dez,
										 		g_catalogos.enfermedades e,
										 		g_catalogos.tipos_enfermedades te
									 		WHERE
										 		ez.id_enfermedad_zoonosica=$idenfermedad
										 		and e.id_enfermedad=dez.id_enfermedad
										 		and te.id_tipo_enfermedad=dez.id_tipo_enfermedad
										 		and dez.id_enfermedad_zoonosica=ez.id_enfermedad_zoonosica;");
									
	return $res;
	}
	
	
	///BUSCAR DETALLE DE REPORTE///
	
	public function buscarReporteEnfermedadesDetalle($conexion, $idenfermedad){
	$res = $conexion->ejecutarConsulta("SELECT
												ez.id_producto,ez.nombre_producto,ez.nombre_animal,ez.identificador_animal,ez.identificador_duenio,e.nombre_enfermedad,te.nombre_tipo_enfermedad,ez.descripcion_enfermedad_zoonosica,ez.fecha_reporte
											FROM
												g_notificaciones_enfermedades.enfermedades_zoonosicas ez,
												g_notificaciones_enfermedades.detalle_enfermedades_zoonosicas dez,
												g_catalogos.enfermedades e,
												g_catalogos.tipos_enfermedades te
											WHERE
												ez.id_enfermedad_zoonosica=$idenfermedad
												and e.id_enfermedad=dez.id_enfermedad
												and te.id_tipo_enfermedad=dez.id_tipo_enfermedad
												and dez.id_enfermedad_zoonosica=ez.id_enfermedad_zoonosica;");
	
	return $res;
	}
	

	///OBTENER PRODUCTO (GATO, PERRO, LLAMA, DINOSAURIO, ETC)///

	public function ObtenerProducto($conexion, $identificador){
		$res = $conexion->ejecutarConsulta("SELECT
												distinct(p.nombre_comun),p.id_producto
											FROM
												g_catalogos.productos p,
												g_notificaciones_enfermedades.enfermedades_zoonosicas ez,
												g_operadores.operaciones op,
												g_operadores.operadores o
											WHERE
												ez.identificador='$identificador'
												and o.identificador=op.identificador_operador
												and p.id_producto=ez.id_producto
												and op.estado='registrado'
												and ez.identificador=op.identificador_operador;");

		return $res;
	}

	///REPORTE PARA IMPRIMIR (GATO, PERRO, LLAMA, DINOSAURIO, ETC)///
	
	public function listarReporteNotificacionEnfermedades($conexion, $animal, $tipoEnfermedad,$enfermedad,$fechaInicio, $fechaFin,$identificador){
							
		$busqueda = '';
		
		if($animal =="TODOS"){
			$valor = 0;
		}else if ($tipoEnfermedad == "TODOS"){
			$valor = 1; //Animal
		}else if ($tipoEnfermedad != "TODOS" && $enfermedad == "TODOS"){
			$valor = 2; // Animal, tipo enfermedad
		}else if ($tipoEnfermedad != "TODOS" && $enfermedad != "TODOS"){
			$valor = 3; // Animal, tipo enfermedad y enfermedad
		}
		
		switch ($valor) {			
			
			case '1':
				$busqueda = " AND ez.id_producto = '".$animal."'";
			break;
			case '2':
				$busqueda = " AND ez.id_producto = '".$animal."'  AND dez.id_tipo_enfermedad = '".$tipoEnfermedad."'";
			break;
			case '3':
				$busqueda = " AND ez.id_producto = '".$animal."'  AND dez.id_tipo_enfermedad = '".$tipoEnfermedad."' AND dez.id_enfermedad = '".$enfermedad."'";
			break;
			case '0':
				$busqueda = '';
			break;
		}
		
		$res = $conexion->ejecutarConsulta("SELECT
												ez.identificador_animal,
												ez.nombre_producto,
												ez.nombre_animal,
												e.nombre_enfermedad,
												te.nombre_tipo_enfermedad,
												ez.fecha_reporte,
												a.nombre_area,
												s.nombre_lugar,
												ez.identificador_duenio
											FROM
												g_notificaciones_enfermedades.enfermedades_zoonosicas ez,
												g_notificaciones_enfermedades.detalle_enfermedades_zoonosicas dez,
												g_notificaciones_enfermedades.areas_enfermedades_zoonosicas aez,
												g_catalogos.tipos_enfermedades te,
												g_catalogos.enfermedades e,
												g_operadores.areas a,
												g_operadores.sitios s
											WHERE
												ez.id_enfermedad_zoonosica = dez.id_enfermedad_zoonosica AND
												ez.id_enfermedad_zoonosica = aez.id_enfermedad_zoonosica AND
												dez.id_enfermedad_zoonosica = ez.id_enfermedad_zoonosica AND
												dez.id_tipo_enfermedad = te.id_tipo_enfermedad AND
												dez.id_enfermedad = e.id_enfermedad AND
												aez.id_area = a.id_area AND
												s.id_sitio = a.id_sitio AND
												ez.identificador='$identificador' AND
												ez.fecha_reporte >= '$fechaInicio' AND
												ez.fecha_reporte <= '$fechaFin' 
													".$busqueda."
											ORDER BY
												ez.fecha_reporte asc;");		
		return $res;
	}
	

	public function obtenerAreaSitioXIdentificadorProductoTipoArea($conexion, $identificador, $producto, $tipoArea){
		$res = $conexion->ejecutarConsulta("SELECT 
												a.id_area,
												a.nombre_area,
												s.id_sitio,
												s.nombre_lugar,
												case when o.razon_social = '' then o.nombre_representante ||' '|| o.apellido_representante else o.razon_social end razon_social
											FROM
												g_operadores.operaciones op,
												g_operadores.productos_areas_operacion pao,
												g_operadores.areas a,
												g_operadores.sitios s,
												g_operadores.operadores o
											WHERE
												op.id_operacion = pao.id_operacion and
												pao.id_area = a.id_area and
												a.id_sitio = s.id_sitio and
												op.identificador_operador = '$identificador' and
												op.id_producto = $producto and
												op.identificador_operador=o.identificador and
												a.codigo = '$tipoArea';");
				return $res;
	}
	
	
	public function guardarAreasEnfermedades($conexion,$idEnfermedadZoonosica, $idArea){
			
		$res = $conexion->ejecutarConsulta("INSERT INTO
												g_notificaciones_enfermedades.areas_enfermedades_zoonosicas
												(id_enfermedad_zoonosica, 
												id_area)
											VALUES 
												($idEnfermedadZoonosica, 
												$idArea);");
	
		return $res;
	}
	
}

?>