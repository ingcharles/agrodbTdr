<?php

class ControladorVehiculos{

	public function obtenerDatosVehiculos ($conexion, $localizacion,$opcion){
		
		switch ($opcion){
			case 'Combustible': $busqueda = 've.estado IN (1,2,3)'; break;
			case 'Otro': $busqueda = 've.estado = 1 '; break;
		}
		
		$res = $conexion->ejecutarConsulta("(select ve.id_vehiculo,
													ve.marca, 
												    ve.modelo, 
												    ve.placa, 
													ve.condicion,
													ve.combustible,
													ve.kilometraje_actual,
													ve.kilometraje_inicial,
													g_uath.cambia_formato_nombre(fe.nombre,fe.apellido) AS nombres_completos, 
													a.nombre,
													'LOCAL' as tipo
											from	g_transportes.vehiculos ve,
													g_estructura.area a,
													g_usuario.usuarios u,
													g_uath.ficha_empleado fe
											where   " . $busqueda ."
													and ve.area = a.id_area
													and ve.identificador = u.identificador
													and u.identificador = fe.identificador
													and ve.localizacion = '$localizacion')
											UNION
												
												(select
													ve.id_vehiculo,
													ve.marca,
													ve.modelo,
													ve.placa,
													ve.condicion,
													ve.combustible,
													ve.kilometraje_actual,
													ve.kilometraje_inicial,
													g_uath.cambia_formato_nombre(fe.nombre,fe.apellido) AS nombres_completos,
													a.nombre,
													'COMISION' as tipo
												from	g_transportes.vehiculos ve,
													g_estructura.area a,
													g_usuario.usuarios u,
													g_uath.ficha_empleado fe,
													g_transportes.movilizaciones m,
													g_transportes.rutas r
													
												where  
													ve.area = a.id_area
													and ve.identificador = u.identificador
													and u.identificador = fe.identificador
													and ve.placa = m.placa
													and m.id_movilizacion = r.id_movilizacion
													and r.localizacion = '$localizacion'
													and ve.localizacion != '$localizacion'
													and m.estado not in (4,9)
													and ve.estado != 9);");
		return $res;
	}
	
	public function datosVehiculos ($conexion, $localizacion){
		$res = $conexion->ejecutarConsulta("(select  
													ve.id_vehiculo,
													ve.marca,
													ve.modelo,
													ve.placa,
													ve.estado,
													ve.combustible,
													g_uath.cambia_formato_nombre(fe.nombre,fe.apellido) AS nombres_completos,
													a.nombre,
													'LOCAL' as tipo
												from	g_transportes.vehiculos ve,
													g_estructura.area a,
													g_usuario.usuarios u,
													g_uath.ficha_empleado fe
												where  
													ve.area = a.id_area
													and ve.identificador = u.identificador
													and u.identificador = fe.identificador
													and ve.localizacion = '$localizacion'
													and ve.estado not in (3,9))
									
											UNION
												
												(select
													ve.id_vehiculo,
													ve.marca,
													ve.modelo,
													ve.placa,
													ve.estado,
													ve.combustible,
													g_uath.cambia_formato_nombre(fe.nombre,fe.apellido) AS nombres_completos,
													a.nombre,
													'COMISION' as tipo
												from	g_transportes.vehiculos ve,
													g_estructura.area a,
													g_usuario.usuarios u,
													g_uath.ficha_empleado fe,
													g_transportes.movilizaciones m,
													g_transportes.rutas r
													
												where  
													ve.area = a.id_area
													and ve.identificador = u.identificador
													and u.identificador = fe.identificador
													and ve.id_vehiculo = m.id_vehiculo
													and m.id_movilizacion = r.id_movilizacion
													and r.localizacion = '$localizacion'
													and m.estado not in (4,9)
													and ve.estado != 9)");
				return $res;
	}
	
	
	public function abrirVehiculo ($conexion, $placa){
		$res = $conexion->ejecutarConsulta("select ve.*, a.id_area, a.nombre as area, fe.identificador, fe.nombre, fe.apellido
											from	g_transportes.vehiculos ve,
													g_estructura.area a,
													g_usuario.usuarios u,
													g_uath.ficha_empleado fe
											where   ve.area = a.id_area
													and ve.identificador = u.identificador
													and u.identificador = fe.identificador
													and ve.placa = '$placa';");
		return $res;
	}
	
	public function listarMovilizacion ($conexion, $estado, $localizacion){
	
		$busqueda = '';
		switch ($estado){
			case 'ABIERTOS': $busqueda = 'm.estado IN (1,2,3) and '; break;
		}
	
	
		$res = $conexion->ejecutarConsulta("select *
											from	g_transportes.movilizaciones m													
											where   " . $busqueda ."
													m.localizacion = '$localizacion';");
		return $res;
	}
	
	public function abrirMovilizacion ($conexion, $id_movilizacion){
	
		$res = $conexion->ejecutarConsulta("select 
												m.*
											from	
												g_transportes.movilizaciones m
											where
												m.id_movilizacion = '$id_movilizacion';");
		return $res;
	}
	
	public function abrirMovilizacionOcupantes ($conexion, $id_movilizacion){
	
		$res = $conexion->ejecutarConsulta("select
												o.identificador,
												g_uath.cambia_formato_nombre(fe.nombre,fe.apellido) AS nombres_completos 
											from
												g_transportes.movilizaciones m,
												g_transportes.ocupantes o,
												g_usuario.usuarios u,
												g_uath.ficha_empleado fe
											where
												o.id_movilizacion = m.id_movilizacion 
												and o.identificador = u.identificador
												and u.identificador = fe.identificador
												and o.id_movilizacion = '$id_movilizacion';");
				return $res;
	}
	
	public function abrirMovilizacionRutas ($conexion, $id_movilizacion){
	
		$res = $conexion->ejecutarConsulta("select
												r.localizacion
											from
												g_transportes.movilizaciones m,
												g_transportes.rutas r
											where
												r.id_movilizacion = m.id_movilizacion 
												and m.id_movilizacion = '$id_movilizacion' ;");
		return $res;
	}
	
	
	public function guardarMovilizacion($conexion, $id_movilizacion, $tipoMovilizacion,$descripcion,$observacion,$localizacion,$observacion_ocupante, $identificadorUsuarioRegistro){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO 
													g_transportes.movilizaciones(
													id_movilizacion,
										            tipo_movilizacion,fecha_solicitud, 
													descripcion, estado,localizacion,observacion_ruta,observacion_ocupante, identificador_registro)
										    VALUES ('$id_movilizacion','$tipoMovilizacion',now(),
													'$descripcion',1,'$localizacion','$observacion','$observacion_ocupante', '$identificadorUsuarioRegistro') RETURNING id_movilizacion;");
		return $res;
	}
	
	public function guardarMovilizacionRutas($conexion,$idMovilizacion,$idLocalizacion,$fechaDesde, $fechaHasta){
	
		$res = $conexion->ejecutarConsulta("INSERT into g_transportes.rutas(
												id_movilizacion, localizacion, fecha_desde, fecha_hasta)
											VALUES ('$idMovilizacion','$idLocalizacion', '$fechaDesde', '$fechaHasta');");
		return $res;
	}
	
	public function guardarMovilizacionOcupantes($conexion,$idMovilizacion,$identificador){
	
		$res = $conexion->ejecutarConsulta("INSERT into g_transportes.ocupantes(
													id_movilizacion,identificador)
											VALUES ('$idMovilizacion','$identificador');");
		return $res;
	}
	
	public function listarMantenimiento ($conexion,$localizacion, $estado, $tipo){
	
		$busqueda = '';
		if($estado =='ABIERTOS' && $tipo =='MANTENIMIENTOS'){
			$busqueda = "m.estado in (1,2) and m.tipo like 'Mantenimiento%' and";
		}else if($estado =='ABIERTOS' && $tipo =='LAVADAS'){
			$busqueda = "m.estado in (1,2) and m.tipo = 'Lavada' and";
		}
			
	
		$res = $conexion->ejecutarConsulta("select m.id_mantenimiento, m.motivo, m.fecha_solicitud, m.kilometraje,
												   m.placa, m.conductor, m.fecha_liquidacion, m.valor_liquidacion, 
												   m.numero_factura, m.estado as estado_mantenimiento,
												   m.taller, m.localizacion, m.imagen_factura, v.marca, v.tipo, v.modelo
											from	g_transportes.mantenimientos m,
													g_transportes.vehiculos v
											where   " . $busqueda ."
													m.placa = v.placa 
													and m.localizacion = '$localizacion';");
		return $res;
	}

	/*public function abrirMantenimiento ($conexion, $id_mantenimiento){
	
		$res = $conexion->ejecutarConsulta("select
												m.*,
												m.tipo as tipo_mantenimiento,
												v.marca,
												v.modelo,
												v.tipo,
												v.numero_motor,
												v.numero_chasis,
												fe.nombre as nombreconductor,
												fe.apellido,
												t.nombre as nombre_taller
											from	
												g_transportes.mantenimientos m,	
												g_transportes.vehiculos v,
												g_uath.ficha_empleado fe,
												g_transportes.talleres t
											where   
												m.placa = v.placa
												and m.id_mantenimiento= '$id_mantenimiento'
												and m.conductor = fe.identificador
												and m.taller = t.id_taller;");
		return $res;
	}*/
	
	
	public function abrirMantenimiento ($conexion, $id_mantenimiento){
	
		$res = $conexion->ejecutarConsulta("select
												m.*,
												m.tipo as tipo_mantenimiento,
												v.marca,
												v.modelo,
												v.tipo,
												v.numero_motor,
												v.numero_chasis
											from	
												g_transportes.mantenimientos m,	
												g_transportes.vehiculos v
											where   
												m.placa = v.placa
												and m.id_mantenimiento= '$id_mantenimiento';");
		return $res;
	}

	
	
	public function abrirDetalleMantenimiento ($conexion, $id_mantenimiento){
	
										$res = $conexion->ejecutarConsulta("select *
																			from
																				g_transportes.mantenimientos m,
																				g_transportes.detalle_mantenimientos dm
																			where
																				m.id_mantenimiento = dm.id_mantenimiento
																				and dm.id_mantenimiento= '$id_mantenimiento';");
																		return $res;
	}
	
	public function guardarVehiculo($conexion, $marca,$modelo, $tipo,$placa,$combustible,
									$carroceria,$color_uno,$color_dos,$pais_origen,$condicion,$fabricacion,$tonelaje,$cilindraje,
									$motor,$chasis,$fecha_compra,$numero_factura,$valor_comprar,$area,$responsable,$kilometraje,
									$avaluo,$localizacion, $observacion,$identificadorUsuarioRegistro){
		
		$res = $conexion->ejecutarConsulta( "INSERT INTO g_transportes.vehiculos(placa, marca, 
																				tipo, combustible, 
																				modelo, pais_origen, 
																				anio_fabricacion, 
            																	carroceria, color, 
																				color_secundario, tonelaje, 
																				cilindraje, numero_motor, 
           																		numero_chasis, area, identificador, estado,
																				kilometraje_actual,condicion, fecha_compra, 
																				factura_compra,valor_compra, 
																				kilometraje_inicial, avaluo, localizacion, 
																				observacion, identificador_registro)
    												VALUES ('$placa','$marca','$tipo','$combustible', '$modelo','$pais_origen',	'$fabricacion', 
															'$carroceria','$color_uno','$color_dos','$tonelaje','$cilindraje','$motor',
															'$chasis', '$area','$responsable',1,'$kilometraje','$condicion','$fecha_compra',
															 '$numero_factura','$valor_comprar','$kilometraje','$avaluo','$localizacion', '$observacion', 
															'$identificadorUsuarioRegistro') RETURNING id_vehiculo;");
		return $res;
	}
	
	
	public function actualizarFoto($conexion, $idVehiculo, $ruta, $opcion){
		
		switch ($opcion){
			case 'Frontal': $actualiza = "imagen_frontal = '".$ruta."'" ; break;
			case 'Posterior': $actualiza = "imagen_trasera ='".$ruta."'"; break;
			case 'Derecha': $actualiza = "imagen_derecha ='".$ruta."'"; break;
			case 'Izquierda': $actualiza = "imagen_izquierda ='".$ruta."'"; break;
			
		}
	
		$res = $conexion->ejecutarConsulta( "UPDATE g_transportes.vehiculos
											 SET " . $actualiza ."
											 WHERE id_vehiculo = $idVehiculo;");
		return $res;
	}
	
	public function actualizarDatosVehiculo($conexion, $idVehiculo,$placa,$marca,$modelo,$tipo,$combustible,$carroceria,$color_uno,$color_dos,$pais_origen,$condicion,$fabricacion,$tonelaje,
											$cilindraje,$motor,$chasis,$fecha_compra,$factura_compra,$valor_compra,$area,$responsable,$avaluo,$observaciones,
											$identificadorUsuarioRegistro){
		
		$res = $conexion->ejecutarConsulta("UPDATE  
													g_transportes.vehiculos
										    SET 	
													placa = '$placa',
													marca='$marca', 
													tipo='$tipo', 
													combustible='$combustible', 
													modelo='$modelo', 
													pais_origen='$pais_origen', 
											       	anio_fabricacion='$fabricacion', 
											      	carroceria='$carroceria', 
											     	color='$color_uno', 
											     	color_secundario='$color_dos', 
											       	tonelaje='$tonelaje', 
											       	cilindraje='$cilindraje', 
											       	numero_motor='$motor', 
											       	numero_chasis='$chasis', 
											       	area='$area', 
											       	identificador='$responsable', 
											       	condicion='$condicion',
											       	fecha_compra='$fecha_compra', 
											       	factura_compra='$factura_compra', 
											       	valor_compra='$valor_compra',
													avaluo='$avaluo',
													identificador_registro='$identificadorUsuarioRegistro'
										   WHERE 	
													id_vehiculo =$idVehiculo;");
		return $res;
	}
		

	public function abrirDatosTalleres($conexion, $localizacion){
	
		$res = $conexion->ejecutarConsulta("select
													t.id_taller,
													t.nombre as nombretaller,
													t.direccion,
													t.telefono,
													t.contacto,
													t.observacion,
													t.localizacion
													
											from 	g_transportes.talleres t
													
											where 	not t.estado = 9
													and t.localizacion = '$localizacion';");
		return $res;
	}
	
	
	public function guardarNuevoMantenimiento($conexion, $id_mantenimiento, $motivo, $placa, $conductor, $kilometraje, $taller,  $localizacion, $tipo, $idVehiculo, $identificadorUsuarioRegistro){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO g_transportes.mantenimientos(
										           id_mantenimiento, motivo, fecha_solicitud, 
													placa, conductor, 
										            kilometraje, 
													estado, taller, localizacion, tipo, id_vehiculo,
													identificador_registro)
										    VALUES ('$id_mantenimiento','$motivo',now(),
													'$placa','$conductor',
													'$kilometraje',1,'$taller','$localizacion','$tipo', $idVehiculo,
													'$identificadorUsuarioRegistro')
											RETURNING id_mantenimiento;");
		return $res;
	}
	
	
	public function actualizarDatosMantenimiento($conexion, $id_mantenimiento,$motivo,$taller,$conductor, $identificadorUsuarioRegistro){
	
		$res = $conexion->ejecutarConsulta("UPDATE
													g_transportes.mantenimientos
										    SET
													motivo='".$motivo."',
													taller=".$taller.",
													conductor='".$conductor."',
													identificador_registro = '$identificadorUsuarioRegistro'
											WHERE
													id_mantenimiento ='" . $id_mantenimiento . "';");
		return $res;
	}
	
	
	public function actualizarDatosMantenimientoDetalle($conexion, $id_mantenimiento,$valorLiquidado,$numeroFactura,$kilometraje, $identificadorUsuarioRegistro, $razonIncrementoKilometraje=null){
		
		if ($kilometraje != 0){
			$res = $conexion->ejecutarConsulta("UPDATE
													g_transportes.mantenimientos
										    SET
													fecha_liquidacion=now(),
													valor_liquidacion=$valorLiquidado,
													kilometraje_final=$kilometraje,
													numero_factura='$numeroFactura',
													estado=3,
													identificador_registro = '$identificadorUsuarioRegistro',
													razon_incremento_kilometraje = '$razonIncrementoKilometraje'
											WHERE
													id_mantenimiento ='$id_mantenimiento';");
			
		}else{
			$res = $conexion->ejecutarConsulta("UPDATE
													g_transportes.mantenimientos
										    SET
													fecha_liquidacion=now(),
													valor_liquidacion=$valorLiquidado,
													numero_factura='$numeroFactura',
													estado=3,
													identificador_registro = '$identificadorUsuarioRegistro'
											WHERE
													id_mantenimiento ='$id_mantenimiento';");
			
		}
		
		return $res;
		
	}
	
	public function actualizarKilometrajeVehiculo($conexion,$placa,$kilometraje,$opcion){
		
		switch ($opcion){
			case 'Inicial': $actualiza = "kilometraje_inicial = '$kilometraje',kilometraje_actual = '$kilometraje'" ; break;
			case 'Actual': $actualiza = "kilometraje_actual ='$kilometraje'"; break;

				
		}
	
		$res = $conexion->ejecutarConsulta("UPDATE
													g_transportes.vehiculos
										    SET
													estado = 1,
													" . $actualiza ."											
											WHERE
													placa ='$placa';");
		return $res;
	}
	
	
	public function actualizarDatosMantenimientoImpresion($conexion, $id_mantenimiento, $identificadorUsuarioRegistro){
	
		$res = $conexion->ejecutarConsulta("UPDATE
													g_transportes.mantenimientos
										    SET
													estado=2,
													identificador_registro = '$identificadorUsuarioRegistro'
											WHERE
													id_mantenimiento ='" . $id_mantenimiento . "';");
		return $res;
	}

	public function actualizarDatosMovilizacion($conexion, $id_movilizacion,$placa,$conductor,$kilometraje_inicial, $idVehiculo, $identificadorUsuarioRegistro){
	
		$res = $conexion->ejecutarConsulta("UPDATE
													g_transportes.movilizaciones
										    SET
													placa='$placa',
													conductor='$conductor',
													kilometraje_inicial = '$kilometraje_inicial',
													estado=2,
													id_vehiculo = '$idVehiculo',
													identificador_registro = '$identificadorUsuarioRegistro'
											WHERE
													id_movilizacion ='$id_movilizacion';");
		return $res;
	}
	
	
	public function ingresarDetalleMantenimiento($conexion, $id_mantenimiento,$detalle,$valor){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO 
														g_transportes.detalle_mantenimientos(id_mantenimiento, detalle, valor)
										    VALUES
													('$id_mantenimiento','".$detalle."','$valor');");
		return $res;
	}
	
	
	public function abrirDatosGasolineras($conexion,$localizacion){
		$res = $conexion->ejecutarConsulta("select
													g.*
											from	
													g_transportes.gasolineras g
											where 	
													g.localizacion = '$localizacion' and
													g.estado = 1;");
		return $res;
	}
	
	public function guardarNuevoCombustible($conexion,$id_combustible, $placa, $kilometraje, $conductor, $combustible, $gasolinera, $localizacion, $fecha_despacho, $idVehiculo, $identificadorUsuarioRegistro, $montoSolicitado, $galonesSolicitados){
	
		$res = $conexion->ejecutarConsulta("INSERT into g_transportes.combustible(
													id_combustible,
										            fecha_solicitud, placa,
													kilometraje, conductor,
										            tipo_combustible, gasolinera,
													estado, localizacion, fecha_despacho, id_vehiculo, identificador_registro,
													monto_solicitado, galones_solicitados)
										    VALUES ('$id_combustible',now(),'$placa',
													'$kilometraje','$conductor',
													'$combustible','$gasolinera',1,'$localizacion', '$fecha_despacho', $idVehiculo, '$identificadorUsuarioRegistro',
													$montoSolicitado, $galonesSolicitados)
											returning id_combustible;");
		return $res;
	}
	
	
	public function listarCombustible ($conexion,$localizacion, $estado){
	
		$busqueda = '';
		switch ($estado){
			case 'ABIERTOS': $busqueda = 'c.estado in (1,2) and '; break;
		}
	
		$res = $conexion->ejecutarConsulta("select  c.id_combustible, c.fecha_solicitud,c.placa,c.tipo_combustible, c.kilometraje,
												    c.gasolinera,c.fecha_liquidacion,c.valor_liquidacion,c.estado as estado_combustible, 
												    g.nombre, g.direccion,g.telefono,g.contacto,g.saldo_disponible,
												    v.marca,v.modelo,v.tipo,v.combustible, c.fecha_despacho
											from	g_transportes.combustible c,
													g_transportes.gasolineras g,
													g_transportes.vehiculos v
											where   " . $busqueda ."
													c.placa = v.placa
													and c.gasolinera = g.id_gasolinera
													and c.localizacion = '$localizacion';");
		return $res;
	}
	
	public function abrirCombustible ($conexion, $id_combustible){
	
		$res = $conexion->ejecutarConsulta("select
													c.*,
													v.marca,
													v.modelo,
													v.tipo,
													v.numero_motor,
													v.numero_chasis,
													g.nombre as nombregasolinera,
							  						fe.nombre as nombreconductor,
													fe.apellido,
													g_uath.cambia_formato_nombre(fe.nombre,fe.apellido) AS nombres_completos
											from	g_transportes.combustible c,
													g_transportes.vehiculos v,
													g_transportes.gasolineras g,
													g_uath.ficha_empleado fe
											where   c.placa = v.placa
													and c.conductor = fe.identificador
													and c.gasolinera = g.id_gasolinera
													and	c.id_combustible = '$id_combustible' ;");

		return $res;
	}
	
	
	public function actualizarDatosCombustible($conexion,$id_combustible,$kilometraje,$observacion,$gasolinera,$combustible,$conductor, $fecha_despacho, $identificadorUsuarioRegistro, $montoSolicitado, $galonesSolicitados){
	
		$res = $conexion->ejecutarConsulta("UPDATE
													g_transportes.combustible
											SET
													kilometraje= '$kilometraje',
													gasolinera = '$gasolinera',
													tipo_combustible='$combustible',
													observacion = '$observacion',
													conductor='$conductor',
													fecha_despacho = '$fecha_despacho',
													identificador_registro = '$identificadorUsuarioRegistro',
													monto_solicitado = $montoSolicitado,
													galones_solicitados = $galonesSolicitados
											WHERE
													id_combustible = '$id_combustible';");
		return $res;
	}
	
	public function actualizarDatosCombustibleDetalle($conexion, $id_combustible,$fechaLiquidacion,$valorLiquidado, $cantidadGalones){
	
		$res = $conexion->ejecutarConsulta("UPDATE
													g_transportes.combustible
										    SET
													fecha_liquidacion='".$fechaLiquidacion."',
													valor_liquidacion=".$valorLiquidado.",
													cantidad_galones = $cantidadGalones,
													estado = 3
											WHERE
													id_combustible ='$id_combustible';");
		return $res;
	}
	
	
	public function actualizarDatosCombustibleImpresion($conexion, $id_combustible, $identificadorUsuarioRegistro){
	
		$res = $conexion->ejecutarConsulta("UPDATE
													g_transportes.combustible
										    SET
													estado = 2,
													identificador_registro = '$identificadorUsuarioRegistro'
											WHERE
													id_combustible ='$id_combustible';");
		return $res;
	}
	
	
	public function listarTalleres ($conexion,$localizacion, $estado){
	
		$busqueda = '';
		switch ($estado){
			case 'ABIERTOS': $busqueda = 't.estado = 1 and '; break;
		}
	
		$res = $conexion->ejecutarConsulta("select *
											from	g_transportes.talleres t
											where   " . $busqueda ."
													t.localizacion = '$localizacion';");
		return $res;
	}
	
	public function listarGasolineras ($conexion,$localizacion, $estado){
	
		$busqueda = '';
		switch ($estado){
			case 'ABIERTOS': $busqueda = 'g.estado = 1 and'; break;
		}
	
		$res = $conexion->ejecutarConsulta("select *
											from	
													g_transportes.gasolineras g
											where   " . $busqueda ."
											 g.localizacion = '$localizacion';");
		return $res;
	}
	
	public function guardarNuevoGasolinera($conexion, $nombreGasolinera,$direccion,$cupo,$contacto,$telefono,$observaciones,$extra,$super,$diesel, $ecopais,$localizacion, $identificadorUsuarioRegistro){
	
		$res = $conexion->ejecutarConsulta("INSERT into g_transportes.gasolineras(nombre, direccion, telefono, contacto,saldo_disponible,  
            										observacion, localizacion, estado, extra, super, diesel, ecopais, cupo, identificador_registro)
										    VALUES ('$nombreGasolinera','$direccion','$telefono','$contacto','$cupo','$observaciones',
													'$localizacion',1,'$extra','$super','$diesel','$ecopais','$cupo', '$identificadorUsuarioRegistro');");
		return $res;
	}
	
	public function abrirGasolinera ($conexion, $id_gasolinera){
	
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_transportes.gasolineras g
												
											where
												g.id_gasolinera= ".$id_gasolinera.";");
		return $res;
	}
	
	
	public function actualizarDatosGasolinera($conexion, $id_gasolinera, $nombreGasolinera, $direccion, $cupo, $contacto, $telefono, $observaciones, $extra, $super, $diesel, $ecopais, $identificadorUsuarioRegistro){
	
		$res = $conexion->ejecutarConsulta("UPDATE
													g_transportes.gasolineras
										    SET
													nombre='$nombreGasolinera',
													direccion='$direccion',
													contacto='$contacto',
													telefono='$telefono',
													observacion='$observaciones',
													extra='$extra',
													super='$super',
													diesel='$diesel',
													ecopais='$ecopais',
													cupo = '$cupo',
													identificador_registro = '$identificadorUsuarioRegistro'
											WHERE
													id_gasolinera ='" . $id_gasolinera . "';");
		return $res;
	}
	
	
	public function guardarNuevoTaller($conexion, $taller, $direccion, $contacto, $telefono, $observaciones,$localizacion, $identificadorUsuarioRegistro){
	
		$res = $conexion->ejecutarConsulta("insert into g_transportes.talleres(
										            nombre, direccion,
													telefono, contacto,
										            observacion,
													localizacion, estado,
													identificador_registro)
										    VALUES ('".$taller."','".$direccion."',
													'".$telefono."','".$contacto."',
												    '".$observaciones."','$localizacion',1,
													'$identificadorUsuarioRegistro');");
		return $res;
	
	}
	
		
	public function abrirTaller($conexion, $id_taller){
	
		$res = $conexion->ejecutarConsulta("select
													*
											from 	g_transportes.talleres
							
											where 	id_taller =".$id_taller.";");
		return $res;
	}
	
	public function actualizarTaller($conexion,$id_taller,$taller,$direccion,$telefono,$contacto,$observaciones, $identificadorUsuarioRegistro){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_transportes.talleres
											SET
												nombre= '$taller',
												direccion = '$direccion',
												telefono='$telefono',
												contacto='$contacto',
												observacion='$observaciones',
												identificador_registro = '$identificadorUsuarioRegistro'
											WHERE
												id_taller = '$id_taller';");
		return $res;
	}
	
	public function filtrarVehiculos($conexion, $localizacion, $placa, $anio, $marca, $modelo, $fechaInicio, $fechaFin, $estado){
			$localizacion  = $localizacion!="" ? "'" . $localizacion . "'" : "null";
			$placa = $placa!="" ? "'" . $placa . "'" : "null";
			$anio = $anio!="" ? $anio : "null";
			$marca = $marca!="" ? "'" . $marca . "'" : "null";
			$modelo = $modelo!="" ? "'" . $modelo . "'" : "null";
			$fechaInicio = $fechaInicio!="" ? "'" . $fechaInicio . "'" : "null";
			$fechaFin = $fechaFin!="" ? "'" . $fechaFin . "'" : "null";
			$estado = $estado!="" ? $estado : "null";
			$res = $conexion->ejecutarConsulta("
				select
					v.placa,
					v.marca,
					v.modelo,
					v.estado,
					v.fecha_compra,
					l.nombre,
					g_uath.cambia_formato_nombre(fe.nombre,fe.apellido) AS nombres_completos
				from
					g_transportes.mostrar_vehiculos_filtrados($localizacion,$placa,$anio,$marca,$modelo,$fechaInicio,$fechaFin,$estado) as v
					INNER JOIN g_catalogos.localizacion l ON v.localizacion=l.nombre
        			INNER JOIN g_uath.ficha_empleado fe ON v.identificador=fe.identificador
					");
		return $res;
	}
	
	public function filtrarCombustibles($conexion, $localizacion, $placa, $gasolinera, $tipo_combustible, $fechaInicio, $fechaFin, $estado){
		$localizacion = $localizacion!="" ? "'" . $localizacion . "'" : "null";
		$placa = $placa!="" ? "'" . $placa . "'" : "null";
		$gasolinera = $gasolinera!="" ? $gasolinera : "null";
		$tipo_combustible = $tipo_combustible!="" ? "'" . $tipo_combustible . "'" : "null";
		$fechaInicio = $fechaInicio!="" ? "'" . $fechaInicio . "'" : "null";
		$fechaFin = $fechaFin!="" ? "'" . $fechaFin . "'" : "null";
		$estado = $estado!="" ? $estado : "null";
		$res = $conexion->ejecutarConsulta("
				select
					c.id_combustible,
					c.placa,
					c.tipo_combustible,
					c.gasolinera,
					g.nombre as nombre_gasolinera,
					c.valor_liquidacion,
					c.localizacion as nombre_localizacion,
					g_uath.cambia_formato_nombre(fe.nombre,fe.apellido) AS nombres_completos
				from
					g_transportes.mostrar_combustibles_filtrados($localizacion,$placa,$tipo_combustible,$gasolinera,$fechaInicio,$fechaFin,$estado) as c
					INNER JOIN g_uath.ficha_empleado fe ON c.conductor=fe.identificador
					INNER JOIN g_transportes.gasolineras g ON c.gasolinera=g.id_gasolinera");
		return $res;
	}
	
	public function filtrarGasolineras($conexion, $localizacion, $gasolinera, $direccion, $estado){
		$localizacion = $localizacion!="" ? "'" . $localizacion . "'" : "null";
		$gasolinera = $gasolinera!="" ? $gasolinera : "null";
		$direccion = $direccion!="" ? "'" . $direccion . "'" : "null";
		$estado = $estado!="" ? $estado : "null";
		$res = $conexion->ejecutarConsulta("
				select
					g.id_gasolinera,
					g.nombre as nombre_gasolinera,
					g.direccion,
					g.direccion,
					g.telefono,
					g.contacto,
					g.saldo_disponible,
					g.localizacion as nombre_localizacion
				from
					g_transportes.mostrar_gasolineras_filtradas($localizacion,$gasolinera,$direccion,$estado) as g");
				return $res;
	}
	
	public function filtrarTalleres($conexion, $localizacion, $taller, $direccion, $estado){
		$localizacion = $localizacion!="" ? "'" . $localizacion . "'" : "null";
		$taller = $taller!="" ? $taller : "null";
		$direccion = $direccion!="" ? "'" . $direccion . "'" : "null";
		$estado = $estado!="" ? $estado : "null";
		$res = $conexion->ejecutarConsulta("
				select
					t.id_taller,
					t.nombre as nombre_taller,
					t.nombre,
					t.direccion,
					t.telefono,
					t.contacto,
					t.nombre as nombre_localizacion
				from
					g_transportes.mostrar_talleres_filtrados($localizacion,$taller,$direccion,$estado) as t");
				return $res;
	}
	
	public function filtrarMovilizacion($conexion, $localizacion, $placa, $tipo, $fechaInicio, $fechaFin, $estado){
		$localizacion = $localizacion!="" ? "'" . $localizacion . "'" : "null";
		$placa = $placa!="" ? "'" . $placa . "'" : "null";
		$tipo = $tipo!="" ? "'" . $tipo . "'" : "null";
		$fechaInicio = $fechaInicio!="" ? "'" . $fechaInicio . "'" : "null";
		$fechaFin = $fechaFin!="" ? "'" . $fechaFin . "'" : "null";
		$estado = $estado!="" ? $estado : "null";
		$res = $conexion->ejecutarConsulta("
				select
					m.id_movilizacion,
					m.tipo_movilizacion,
					m.descripcion,
					m.placa,
					m.estado,
					m.fecha_solicitud,
					m.localizacion as nombre_localizacion,
					g_uath.cambia_formato_nombre(fe.nombre,fe.apellido) AS nombres_completos
				from
					g_transportes.mostrar_movilizaciones_filtradas($localizacion,$tipo,$placa,$fechaInicio,$fechaFin,$estado) as m
					INNER JOIN g_uath.ficha_empleado fe ON m.conductor=fe.identificador
				");
		return $res;
	}
	
	
	public function filtrarMantenimiento($conexion, $localizacion, $placa, $taller, $factura, $motivo, $fechaInicio, $fechaFin, $estado){
		$localizacion = $localizacion!="" ? "'" . $localizacion . "'" : "null";
		$placa = $placa!="" ? "'" . $placa . "'" : "null";
		$taller = $tipo!="" ? $taller : "null";
		$factura = $tipo!="" ? "'" . $factura . "'" : "null";
		$motivo = $tipo!="" ? "'" . $motivo . "'" : "null";
					$fechaInicio = $fechaInicio!="" ? "'" . $fechaInicio . "'" : "null";
					$fechaFin = $fechaFin!="" ? "'" . $fechaFin . "'" : "null";
					$estado = $estado!="" ? $estado : "null";
		$res = $conexion->ejecutarConsulta("
					select
						m.id_mantenimiento,
						m.motivo,
						m.fecha_solicitud,
						m.placa,
						m.fecha_liquidacion,
						m.valor_liquidacion,
						m.numero_factura,
						m.estado,
						t.nombre as nombre_taller,
						m.localizacion as nombre_localizacion,
						g_uath.cambia_formato_nombre(fe.nombre,fe.apellido) AS nombres_completos
					from
						g_transportes.mostrar_mantenimientos_filtrados($localizacion,$motivo,$taller,$placa,$fechaInicio,$fechaFin,$estado) as m
						INNER JOIN g_uath.ficha_empleado fe ON m.conductor=fe.identificador
						INNER JOIN g_transportes.talleres t ON m.taller=t.id_taller
					");
					return $res;
	}
	
	public function historialVehiculo($conexion, $placa){
		
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_transportes.historial_vehiculo('" .$placa."');");
				return $res;
	}
	
	public function actualizarFactura($conexion,$idMantenimiento, $ruta){
	
		$res = $conexion->ejecutarConsulta( "UPDATE g_transportes.mantenimientos
											 SET imagen_factura = '$ruta'
											 WHERE id_mantenimiento = '$idMantenimiento';");
		return $res;
	}
	
	
	public function finalizarMovilizacion($conexion,$idMovilizacion, $kilometraje, $observacion, $identificadorUsuarioRegistro, $razonIncrementoKilometraje){
	
		$res = $conexion->ejecutarConsulta( "UPDATE 
													g_transportes.movilizaciones
											SET 
													kilometraje_final = '$kilometraje',
													observacion_movilizacion = '$observacion',
													estado = 4,
													identificador_registro = '$identificadorUsuarioRegistro',
													razon_incremento_kilometraje = '$razonIncrementoKilometraje'
											WHERE 
													id_movilizacion = '$idMovilizacion';");
		return $res;
	}
	
	public function actualizarCupoGasolinera($conexion,$gasolinera,$saldo){
	
		$res = $conexion->ejecutarConsulta("UPDATE
													g_transportes.gasolineras
										    SET
													saldo_disponible = '$saldo'
											WHERE
													id_gasolinera ='$gasolinera';");
		return $res;
	}
	
	public function  generarNumeroMantenimiento($conexion,$codigo,$secuencial){
	    
	    $res = $conexion->ejecutarConsulta("SELECT
												max(split_part(id_mantenimiento, $secuencial , 2)::int) as numero
											FROM
												g_transportes.mantenimientos
											WHERE id_mantenimiento LIKE '$codigo';");
	    return $res;
	}
	
	public function  generarNumeroCombustible($conexion,$codigo,$secuencial){
	    
	    $res = $conexion->ejecutarConsulta("SELECT
												max(split_part(id_combustible, $secuencial , 2)::int) as numero
											FROM
												g_transportes.combustible
											WHERE id_combustible LIKE '$codigo';");
	    return $res;
	}
	
	public function  generarNumeroMovilizacion($conexion,$codigo,$secuencial){
	
		$res = $conexion->ejecutarConsulta("SELECT
												max(split_part(id_movilizacion, $secuencial , 2)::int) as numero 
											FROM
												g_transportes.movilizaciones
											WHERE id_movilizacion LIKE '$codigo';");
		return $res;
	}
	
	public function actualizarEstadoVehiculo($conexion, $placa, $condicion){
	
		switch ($condicion){
			
			case 'Liberar': $actualiza = "estado = 1 " ; break;
			case 'Mantenimiento': $actualiza = "estado = 2 " ; break;
			case 'Movilizacion': $actualiza = "estado = 3 "; break;
			case 'Siniestro': $actualiza = "estado = 4 "; break;
			case 'Combustible': $actualiza = "estado = 5 "; break;
			case 'Lavado': $actualiza = "estado = 6"; break;
			case 'Baja': $actualiza = "estado = 9"; break;
			
		}
	
		$res = $conexion->ejecutarConsulta( "UPDATE g_transportes.vehiculos
											 SET " . $actualiza ."
											 WHERE placa = '$placa';");
		return $res;
	}
	
	public function abrirMovilizacionFechas ($conexion, $id_movilizacion){
	
		$res = $conexion->ejecutarConsulta("select
												MIN(fecha_desde)as fecha_desde, 
												MAX(fecha_hasta) as fecha_hasta
											from
												g_transportes.rutas
											where
												id_movilizacion = '$id_movilizacion' ;");
		return $res;
	}
	
	/*public function eliminarVehiculo ($conexion, $placa){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_transportes.vehiculos
											SET
												estado = 9
											where
												placa = '$placa' ;");
		return $res;
	}*/
	
	public function darBajaVehiculo ($conexion, $placa, $observacion, $identificadorUsuarioRegistro){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_transportes.vehiculos
											SET
												estado = 9,
												concepto_baja = '$observacion',
												identificador_registro = '$identificadorUsuarioRegistro'
											WHERE
												estado = 1
												AND placa = '$placa' ;");
		return $res;
	}
	
	
	public function darBajaTaller ($conexion, $taller, $observacion, $identificadorUsuarioRegistro){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_transportes.talleres
											SET
												estado = 9,
												observacion = '$observacion',
												identificador_registro = '$identificadorUsuarioRegistro'
											WHERE
												id_taller = $taller ;");
		return $res;
	}
	
	
	public function darBajaGasolinera ($conexion, $gasolinera, $observacion, $identificadorUsuarioRegistro){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_transportes.gasolineras
											SET
												estado = 9,
												observacion = '$observacion',
												identificador_registro = '$identificadorUsuarioRegistro'
											WHERE
												id_gasolinera = $gasolinera;");
		return $res;
	}
	
	public function darBajaMovilizacion ($conexion, $movilizacion, $observacion, $identificadorUsuarioRegistro){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_transportes.movilizaciones
											SET
												estado = 9,
												observacion_movilizacion = '$observacion',
												identificador_registro = '$identificadorUsuarioRegistro'
											WHERE
												id_movilizacion = '$movilizacion' ;");
		return $res;
	}
	
	
	public function darBajaMantenimiento ($conexion, $mantenimiento, $observacion, $identificadorUsuarioRegistro){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_transportes.mantenimientos
											SET
												estado = 9,
												observacion = '$observacion',
												identificador_registro = '$identificadorUsuarioRegistro'
											WHERE
												id_mantenimiento = '$mantenimiento';");
		return $res;
	}
	
	
	public function buscarEstadoTaller ($conexion,$taller){
	
		$res = $conexion->ejecutarConsulta(" SELECT *
											FROM g_transportes.mantenimientos
											WHERE taller = $taller
												AND estado not in (3,9); ");
	
				return $res;
	}
	
	public function buscarEstadoGasolinera ($conexion,$gasolinera){
	
	$res = $conexion->ejecutarConsulta(" SELECT *
										FROM g_transportes.combustible
										WHERE gasolinera = $gasolinera
										AND estado not in (3,9); ");
	
			return $res;
	}
	
	
	///////////////////////////////////////////////////////////////////////////////
	
	//SINIESTROS
	
	//////////////////////////////////////////////////////////////////////////////
	
	public function listarSiniestro ($conexion,$localizacion, $estado, $tipo){
	
		$res = $conexion->ejecutarConsulta("select 
												s.id_siniestro, 
												s.placa, 
												s.fecha_siniestro, 
												s.lugar_siniestro,
												s.estado, 
												s.tipo_siniestro, 
												s.fecha_solicitud, 
												v.marca, 
												v.tipo, 
												v.modelo
											from	
												g_transportes.siniestros s,
												g_transportes.vehiculos v
											where
												s.placa = v.placa and 
												s.localizacion = '$localizacion' and 
												s.estado in (1,2);");
		return $res;
	}
	
	public function abrirSiniestro ($conexion, $id_siniestro){
	
		$res = $conexion->ejecutarConsulta("select
												s.*,
												v.marca,
												v.modelo,
												v.tipo,
												v.numero_motor,
												v.numero_chasis,
												v.color,
												l.nombre
											from
												g_transportes.siniestros s,
												g_transportes.vehiculos v,
												g_catalogos.localizacion l
											where
												s.placa = v.placa
												and v.localizacion = l.nombre
												and s.id_siniestro= '$id_siniestro';");
		return $res;
	}
	
	public function actualizarDatosSiniestro($conexion, $id_siniestro,$motivo,$fecha,$lugar,$observaciones,$conductor,$magnitud_danio, $identificadorUsuarioRegistro){
	
		$res = $conexion->ejecutarConsulta("UPDATE
													g_transportes.siniestros
										    SET
													tipo_siniestro='".$motivo."',
													fecha_siniestro='".$fecha."',
													lugar_siniestro='".$lugar."',
													observacion_siniestro='".$observaciones."',
													magnitud_danio_siniestro='".$magnitud_danio."',
													conductor='".$conductor."',
													identificador_registro = '$identificadorUsuarioRegistro'
											WHERE
													id_siniestro ='" . $id_siniestro . "';");
		return $res;
	}
	
	public function actualizarDatosSiniestroFactura($conexion, $id_siniestro, $numero_factura, $identificadorUsuarioRegistro){
	
		$res = $conexion->ejecutarConsulta("UPDATE
													g_transportes.siniestros
										    SET
													numero_factura='".$numero_factura."',
													identificador_registro = '$identificadorUsuarioRegistro'
											WHERE
													id_siniestro ='" . $id_siniestro . "';");
		return $res;
	}
	
	
	public function actualizarDatosSiniestroInforme($conexion, $id_siniestro, $informe_quipux, $identificadorUsuarioRegistro){
	
		$res = $conexion->ejecutarConsulta("UPDATE
													g_transportes.siniestros
										    SET
													informe_quipux='".$informe_quipux."',
													identificador_registro = '$identificadorUsuarioRegistro'
											WHERE
													id_siniestro ='" . $id_siniestro . "';");
		return $res;
	}
	
	
	public function actualizarDatosSiniestroResolucion($conexion, $id_siniestro, $resolucion_quipux, $identificadorUsuarioRegistro){
	
		$res = $conexion->ejecutarConsulta("UPDATE
													g_transportes.siniestros
										    SET
													resolucion_quipux='$resolucion_quipux',
													identificador_registro = '$identificadorUsuarioRegistro'
											WHERE
													id_siniestro ='$id_siniestro';");
		return $res;
	}
	
	
	public function actualizarDatosSiniestroCierreFase($conexion, $id_siniestro, $estado, $identificadorUsuarioRegistro){
	
		$res = $conexion->ejecutarConsulta("UPDATE
													g_transportes.siniestros
										    SET
													estado= '" . $estado . "',
													identificador_registro = '$identificadorUsuarioRegistro'
											WHERE
													id_siniestro ='" . $id_siniestro . "';");
		return $res;
	}
	
	public function actualizarDatosSiniestroImpresion($conexion, $id_siniestro){
	
		$res = $conexion->ejecutarConsulta("UPDATE
													g_transportes.siniestros
										    SET
													estado=2
											WHERE
													id_siniestro ='" . $id_siniestro . "';");
		return $res;
	}
	
	public function  generarNumeroSiniestro($conexion,$codigo,$secuencial){
	    
	    $res = $conexion->ejecutarConsulta("SELECT
												max(split_part(id_siniestro, $secuencial , 2)::int) as numero
											FROM
												g_transportes.siniestros
											WHERE id_siniestro LIKE '$codigo';");
	    return $res;
	}
	
	public function guardarNuevoSiniestro($conexion, $id_siniestro, $fecha_siniestro, $lugar_siniestro, 
											$observacion_siniestro, $placa, $conductor, $localizacion, $tipo_siniestro,
											$magnitud_danio_siniestro, $idVehiculo, $kilometrajeInicial, $identificadorUsuarioRegistro){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO g_transportes.siniestros(
												id_siniestro, fecha_siniestro, lugar_siniestro, observacion_siniestro,
												estado, tipo_siniestro, conductor, localizacion, placa, 
												fecha_solicitud, magnitud_danio_siniestro, id_vehiculo, 
												identificador_registro, kilometraje_inicial)
											VALUES 
												('$id_siniestro', '$fecha_siniestro', '$lugar_siniestro', '$observacion_siniestro', 
												1, '$tipo_siniestro', '$conductor', '$localizacion', '$placa', 
												now(), '$magnitud_danio_siniestro', $idVehiculo, 
												'$identificadorUsuarioRegistro', $kilometrajeInicial);");
		return $res;
	}
	
	public function actualizarFotoSiniestro($conexion, $id_siniestro, $ruta, $opcion){
	
		switch ($opcion){
			case 'Frontal': $actualiza = "imagen_frontal = '".$ruta."'" ; break;
			case 'Posterior': $actualiza = "imagen_trasera ='".$ruta."'"; break;
			case 'Derecha': $actualiza = "imagen_derecha ='".$ruta."'"; break;
			case 'Izquierda': $actualiza = "imagen_izquierda ='".$ruta."'"; break;
				
		}
	
		$res = $conexion->ejecutarConsulta( "UPDATE g_transportes.siniestros
	
											 SET " . $actualiza ."
											 WHERE id_siniestro = '".$id_siniestro."';");
	
		return $res;
	}
	
	public function actualizarDocumentacionSiniestro($conexion,$id_siniestro, $ruta){
	
		$res = $conexion->ejecutarConsulta( "UPDATE g_transportes.siniestros
				SET documentacion_siniestro = '$ruta'
				WHERE id_siniestro = '$id_siniestro';");
		return $res;
	}
	
	public function actualizarInformeSiniestro($conexion,$id_siniestro, $ruta){
	
		$res = $conexion->ejecutarConsulta( "UPDATE g_transportes.siniestros
				SET informe_siniestro = '$ruta'
				WHERE id_siniestro = '$id_siniestro';");
		return $res;
	}
	
	public function actualizarFacturaSiniestro($conexion,$id_siniestro, $ruta){
	
		$res = $conexion->ejecutarConsulta( "UPDATE g_transportes.siniestros
				SET imagen_factura = '$ruta'
				WHERE id_siniestro = '$id_siniestro';");
		return $res;
	}
	
	
	public function filtrarSiniestro($conexion, $localizacion, $placa, $tipo_siniestro, $fecha_inicio, $fecha_fin, $estado){
		$localizacion = $localizacion!="" ? "'" . $localizacion . "'" : "null";
		$placa = $placa!="" ? "'" . $placa . "'" : "null";
		$tipo_siniestro = $tipo_siniestro!="" ? "'" . $tipo_siniestro . "'" : "null";
		$fecha_inicio = $fecha_inicio!="" ? "'" . $fecha_inicio . "'" : "null";
		$fecha_fin = $fecha_fin!="" ? "'" . $fecha_fin . "'" : "null";
		$estado = $estado!="" ? $estado : "null";
	
		$res = $conexion->ejecutarConsulta("select
												s.id_siniestro,
												s.tipo_siniestro,
												s.fecha_solicitud,
												s.fecha_siniestro,
												s.placa,
												s.estado,
												l.nombre as nombre_localizacion,
												g_uath.cambia_formato_nombre(fe.nombre,fe.apellido) AS nombres_completos
											from
												g_transportes.mostrar_siniestros_filtrados($tipo_siniestro,$placa,$estado,$fecha_inicio,$fecha_fin,$localizacion) as s
												INNER JOIN g_catalogos.localizacion l ON s.localizacion=l.nombre
												INNER JOIN g_uath.ficha_empleado fe ON s.conductor=fe.identificador");
		
		return $res;
	}
	
	public function abrirDetalleSiniestro ($conexion, $id_siniestro){
	
	$res = $conexion->ejecutarConsulta("select *
			from
			g_transportes.siniestros s
			where
			s.id_siniestro= '$id_siniestro';");
			return $res;
	}
	
	public function ingresarDetalleSiniestro($conexion, $id_siniestro,$detalle,$valor){
	
	$res = $conexion->ejecutarConsulta("INSERT INTO
			g_transportes.detalle_siniestros(id_siniestro, detalle, valor)
			VALUES
			('$id_siniestro','".$detalle."','$valor');");
			return $res;
	}
	
	public function buscarAdministrador($conexion, $idLocalizacion){
				
		$res = $conexion->ejecutarConsulta("SELECT
												g_uath.cambia_formato_nombre(fe.nombre,fe.apellido) AS nombres_completos
											FROM
												g_estructura.funcionarios f,
												g_uath.ficha_empleado fe
											WHERE
												f.identificador = fe.identificador 
												and f.id_oficina = $idLocalizacion
												and  f.administrador in (1,2)
												and f.id_area not in ('AGR','DE','GDMA')");
		return $res;
	}
	
	public function filtrarConsolidado($conexion, $localizacion, $placa, $fechaInicio, $fechaFin){
		$localizacion = $localizacion!="" ? "'" . $localizacion . "'" : "null";
		$placa = $placa!="" ? "'" . $placa . "'" : "null";
		$fechaInicio = $fechaInicio!="" ? "'" . $fechaInicio . "'" : "null";
		$fechaFin = $fechaFin!="" ? "'" . $fechaFin . "'" : "null";
		
		$res = $conexion->ejecutarConsulta("select * from g_transportes.mostrar_consolidado($localizacion,$placa,$fechaInicio,$fechaFin)");
		
		return $res;
	}
	
	/*public function listarVehiculos ($conexion, $localizacion){
	
		$res = $conexion->ejecutarConsulta("select
												placa, 
												numero_motor,
												numero_chasis
											from
												g_transportes.vehiculos
											where
												localizacion = '$localizacion' ;");
				return $res;
	}*/
	
	public function filtrarVehiculosProvincia($conexion, $localizacion){
		$res = $conexion->ejecutarConsulta("select
												ve.marca,
												ve.modelo,
												ve.placa,
												ve.condicion,
												ve.combustible,
												ve.estado,
												ve.localizacion,
												g_uath.cambia_formato_nombre(fe.nombre,fe.apellido) AS nombres_completos,
												a.nombre
											from
												g_transportes.vehiculos ve,
												g_estructura.area a,
												g_usuario.usuarios u,
												g_uath.ficha_empleado fe
											where
												ve.area = a.id_area
												and ve.identificador = u.identificador
												and u.identificador = fe.identificador
												and ve.localizacion = '$localizacion'
												and ve.estado not in (3,4,9) --Movilización, Siniestro, Baja");
	
				return $res;
	}
	
				public function filtrarVehiculosPlaca($conexion, $placa){
				$res = $conexion->ejecutarConsulta("select
														ve.marca,
														ve.modelo,
														ve.placa,
														ve.condicion,
														ve.combustible,
														ve.estado,
														ve.localizacion,
														g_uath.cambia_formato_nombre(fe.nombre,fe.apellido) AS nombres_completos,
														a.nombre
													from
														g_transportes.vehiculos ve,
														g_estructura.area a,
														g_usuario.usuarios u,
														g_uath.ficha_empleado fe
													where
														ve.area = a.id_area
														and ve.identificador = u.identificador
														and u.identificador = fe.identificador
														and ve.placa = '$placa'
														and ve.estado not in (3,4,9) --Movilización, Siniestro, Baja");
					
				return $res;
		}
	
	public function reasignarVehiculo($conexion, $placa, $localizacion, $motivo, $identificadorUsuarioRegistro){
			$res = $conexion->ejecutarConsulta("UPDATE
													g_transportes.vehiculos
												SET
													localizacion='$localizacion',
													motivo_reasignacion='$motivo - realizado por $identificadorUsuarioRegistro'													
												WHERE placa='$placa';");
				
			return $res;
		}
	
		public function actualizarOrdenTrabajo($conexion, $idMantenimiento, $ordenTrabajo){
		$res = $conexion->ejecutarConsulta("UPDATE
												g_transportes.mantenimientos
											SET
												orden_trabajo='$ordenTrabajo'
											WHERE
												id_mantenimiento='$idMantenimiento';");
					
				return $res;
		}
	
				public function obtenerEstadoVehiculo($conexion, $placa){
				$res = $conexion->ejecutarConsulta("SELECT
														estado
													FROM
														g_transportes.vehiculos
													WHERE
														placa='$placa';");
					
				return $res;
		}
	
		public function actualizarObservacionMovilizacion($conexion, $idMovilizacion, $observacion){
		$res = $conexion->ejecutarConsulta("UPDATE
												g_transportes.movilizaciones
											SET
												observacion_ruta='$observacion'
											WHERE
												id_movilizacion='$idMovilizacion';");
			
		return $res;
	}
	
	public function numeroPlacaTemporal($conexion){
		$res = $conexion->ejecutarConsulta("SELECT 
												count(placa)+1 as numero
											FROM
												g_transportes.vehiculos
											WHERE
												placa like 'SIN%'");
		
		$codigo = str_pad(pg_fetch_result($res, 0, 'numero'), 4, "0", STR_PAD_LEFT);
				
		return $codigo;
	}
	
	public function filtrarCombustibleGeneral($conexion, $placa, $localizacion, $fechaInicio, $fechaFin, $cantidad){
		$localizacion  = $localizacion!="" ? "'" . $localizacion . "'" : "null";
		$placa = $placa!="" ? "'" . $placa . "'" : "null";
		$fechaInicio = $fechaInicio!="" ? "'" . $fechaInicio . "'" : "null";
		$fechaFin = $fechaFin!="" ? "'" . $fechaFin . "'" : "null";
		$cantidad = $cantidad!="" ? "'" . $cantidad . "'" : "null";
	
		$res = $conexion->ejecutarConsulta("
											SELECT
												placa,
												marca,
												modelo,
												valor,
												localizacion
											FROM
												g_transportes.mostrar_combustibles_general($placa,$fechaInicio,$fechaFin,$localizacion, $cantidad)");
				return $res;
	}
	
	public function abrirHistorialIndividualCombustible ($conexion, $placa, $fechaInicio, $fechaFin, $localizacion){
		
		$localizacion  = $localizacion!="" ? "'" . $localizacion . "'" : "null";
		$placa = $placa!="" ? "'" . $placa . "'" : "null";
		$fechaInicio = $fechaInicio!="" ? "'" . $fechaInicio . "'" : "null";
		$fechaFin = $fechaFin!="" ? "'" . $fechaFin . "'" : "null";
		
	
		$res = $conexion->ejecutarConsulta("SELECT
												v.marca,
												v.modelo,
												v.anio_fabricacion,
												c.*
											FROM
												g_transportes.mostrar_combustibles_individual($placa,$fechaInicio,$fechaFin,$localizacion) as c
												INNER JOIN g_transportes.vehiculos v ON v.placa=c.placa");
		
		return $res;
	}
	
	public function filtrarMantenimientoGeneral($conexion, $placa, $localizacion, $fechaInicio, $fechaFin, $cantidad){
		$localizacion  = $localizacion!="" ? "'" . $localizacion . "'" : "null";
		$placa = $placa!="" ? "'" . $placa . "'" : "null";
		$fechaInicio = $fechaInicio!="" ? "'" . $fechaInicio . "'" : "null";
		$fechaFin = $fechaFin!="" ? "'" . $fechaFin . "'" : "null";
		$cantidad = $cantidad!="" ? "'" . $cantidad . "'" : "null";
	
		$res = $conexion->ejecutarConsulta("
											SELECT
												*
											FROM
												g_transportes.mostrar_mantenimientos_general($placa,$fechaInicio,$fechaFin,$localizacion, $cantidad)");
				return $res;
	}
	
	public function abrirHistorialIndividualMantenimiento ($conexion, $placa, $fechaInicio, $fechaFin, $localizacion){
	
		$localizacion  = $localizacion!="" ? "'" . $localizacion . "'" : "null";
		$placa = $placa!="" ? "'" . $placa . "'" : "null";
		$fechaInicio = $fechaInicio!="" ? "'" . $fechaInicio . "'" : "null";
		$fechaFin = $fechaFin!="" ? "'" . $fechaFin . "'" : "null";
	
	
		$res = $conexion->ejecutarConsulta("SELECT
												v.marca,
												v.modelo,
												v.anio_fabricacion,
												c.*
											FROM
												g_transportes.mostrar_mantenimientos_individual($placa,$fechaInicio,$fechaFin,$localizacion) as c
												INNER JOIN g_transportes.vehiculos v ON v.placa=c.placa");
	
				return $res;
	}
	
	public function filtrarMovilizacionGeneral($conexion, $placa, $localizacion, $fechaInicio, $fechaFin, $cantidad){
		$localizacion  = $localizacion!="" ? "'" . $localizacion . "'" : "null";
		$placa = $placa!="" ? "'" . $placa . "'" : "null";
		$fechaInicio = $fechaInicio!="" ? "'" . $fechaInicio . "'" : "null";
		$fechaFin = $fechaFin!="" ? "'" . $fechaFin . "'" : "null";
		$cantidad = $cantidad!="" ? "'" . $cantidad . "'" : "null";
	
		$res = $conexion->ejecutarConsulta("
											SELECT
												*
											FROM
												g_transportes.mostrar_movilizacion_general($placa,$fechaInicio,$fechaFin,$localizacion, $cantidad)");
				return $res;
	}
	
	public function abrirHistorialIndividualMovilizacion ($conexion, $placa, $fechaInicio, $fechaFin, $localizacion){
	
		$localizacion  = $localizacion!="" ? "'" . $localizacion . "'" : "null";
		$placa = $placa!="" ? "'" . $placa . "'" : "null";
		$fechaInicio = $fechaInicio!="" ? "'" . $fechaInicio . "'" : "null";
		$fechaFin = $fechaFin!="" ? "'" . $fechaFin . "'" : "null";
	
	
		$res = $conexion->ejecutarConsulta("SELECT
												v.placa,
												v.marca,
												v.modelo,
												v.anio_fabricacion,
												c.*
											FROM
												g_transportes.mostrar_movilizacion_individual($placa,$fechaInicio,$fechaFin,$localizacion) as c
												INNER JOIN g_transportes.vehiculos v ON v.placa=c.placa");
	
				return $res;
	}
	
	public function filtrarVehiculosAntiguos($conexion, $placa, $localizacion, $cantidad){
		$localizacion  = $localizacion!="" ? "'" . $localizacion . "'" : "null";
		$placa = $placa!="" ? "'" . $placa . "'" : "null";
		$cantidad = $cantidad!="" ? "'" . $cantidad . "'" : "null";
			
		$res = $conexion->ejecutarConsulta("
											SELECT
												v.placa,
												v.marca,
												v.modelo,
												c.anio_fabricacion,
												c.localizacion
											FROM
												g_transportes.mostrar_antiguo_general($placa,$localizacion,$cantidad) as c
												INNER JOIN g_transportes.vehiculos v ON v.placa=c.placa");
				return $res;
	}
	
	
	public function filtrarVehiculosDeBaja($conexion, $placa, $localizacion, $cantidad){
		$localizacion  = $localizacion!="" ? "'" . $localizacion . "'" : "null";
		$placa = $placa!="" ? "'" . $placa . "'" : "null";
		$cantidad = $cantidad!="" ? "'" . $cantidad . "'" : "null";
			
		$res = $conexion->ejecutarConsulta("SELECT
												v.placa,
												v.marca,
												v.modelo,
												v.concepto_baja,
												c.localizacion
											FROM
												g_transportes.mostrar_vehiculosdebaja_general($placa,$localizacion,$cantidad) as c
												INNER JOIN g_transportes.vehiculos v ON v.placa=c.placa");
				return $res;
	}
	
	
	public function filtrarRendimientoGeneral($conexion, $placa, $localizacion, $fechaInicio, $fechaFin, $cantidad){
		$localizacion  = $localizacion!="" ? "'" . $localizacion . "'" : "null";
		$placa = $placa!="" ? "'" . $placa . "'" : "null";
		$fechaInicio = $fechaInicio!="" ? "'" . $fechaInicio . "'" : "null";
		$fechaFin = $fechaFin!="" ? "'" . $fechaFin . "'" : "null";
		$cantidad = $cantidad!="" ? "'" . $cantidad . "'" : "null";
	
		$res = $conexion->ejecutarConsulta("
											SELECT
												v.placa,
												v.marca,
												v.modelo,
												c.valor,
												c.localizacion
											FROM
												g_transportes.mostrar_rendimiento_general($placa,$fechaInicio,$fechaFin,$localizacion, $cantidad) as c
												INNER JOIN g_transportes.vehiculos v ON v.placa=c.placa");
				return $res;
	}
	
	
	public function actualizarDatosMovilizacionImpresion($conexion, $id_movilizacion, $identificadorUsuarioRegistro){
	
		$res = $conexion->ejecutarConsulta("UPDATE
													g_transportes.movilizaciones
										    SET
													estado=3,
													identificador_registro = '$identificadorUsuarioRegistro'
											WHERE
													id_movilizacion ='$id_movilizacion';");
		return $res;
	}
	
	
	public function actualizarOrdenTrabajoKilometraje($conexion, $idMantenimiento, $ordenTrabajo, $kilometrajeFinal, $identificadorUsuarioRegistro, $razonIncrementoKilometraje){
		$res = $conexion->ejecutarConsulta("UPDATE
												g_transportes.mantenimientos
											SET
												orden_trabajo='$ordenTrabajo',
												identificador_registro = '$identificadorUsuarioRegistro',
												kilometraje_final = $kilometrajeFinal,
												observacion = 'Vehículo liberado con orden de trabajo #$ordenTrabajo por responsable $identificadorUsuarioRegistro',
												razon_incremento_kilometraje = '$razonIncrementoKilometraje'
											WHERE
												id_mantenimiento='$idMantenimiento';");
					
				return $res;
	}
		
	public function actualizarComprobanteGasolinera($conexion,$idCombustible, $ruta){
	
		$res = $conexion->ejecutarConsulta( "UPDATE 
												g_transportes.combustible
											SET 
												comprobante_gasolinera = '$ruta'
											WHERE 
												id_combustible = '$idCombustible';");
		return $res;
	}

	public function actualizarRazonCambioCombustible($conexion, $idCombustible, $razonCambio){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_transportes.combustible
											SET
												razon_cambio_monto = '$razonCambio'
											WHERE
												id_combustible ='$idCombustible';");
		return $res;
	}

	public function filtrarVehiculosRegistrados($conexion, $placa, $localizacion, $cantidad){
		$localizacion  = $localizacion!="" ? "'" . $localizacion . "'" : "null";
		$placa = $placa!="" ? "'" . $placa . "'" : "null";
		$cantidad = $cantidad!="" ? "'" . $cantidad . "'" : "null";
			
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_transportes.mostrar_vehiculo_general($placa,$localizacion,$cantidad);");
	
		return $res;
	}
	
	public function filtrarTalleresGeneral($conexion, $localizacion, $fechaInicio, $fechaFin, $cantidad){
		$localizacion  = $localizacion!="" ? "'" . $localizacion . "'" : "null";
		$placa = $placa!="" ? "'" . $placa . "'" : "null";
		$fechaInicio = $fechaInicio!="" ? "'" . $fechaInicio . "'" : "null";
		$fechaFin = $fechaFin!="" ? "'" . $fechaFin . "'" : "null";
		$cantidad = $cantidad!="" ? "'" . $cantidad . "'" : "null";
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_transportes.mostrar_talleres_general($fechaInicio,$fechaFin,$localizacion, $cantidad)");
		return $res;
	}
	
	public function abrirHistorialIndividualTaller ($conexion, $taller, $fechaInicio, $fechaFin, $localizacion){
		$localizacion  = $localizacion!="" ? "'" . $localizacion . "'" : "null";
		$taller = $taller!="" ? "'" . $taller . "'" : "null";
		$fechaInicio = $fechaInicio!="" ? "'" . $fechaInicio . "'" : "null";
		$fechaFin = $fechaFin!="" ? "'" . $fechaFin . "'" : "null";
	
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_transportes.mostrar_talleres_individual($taller,$fechaInicio,$fechaFin,$localizacion)");
	
		return $res;
	}
	
	public function filtrarGasolinerasGeneral($conexion, $localizacion, $fechaInicio, $fechaFin, $cantidad){
		$localizacion  = $localizacion!="" ? "'" . $localizacion . "'" : "null";
		$placa = $placa!="" ? "'" . $placa . "'" : "null";
		$fechaInicio = $fechaInicio!="" ? "'" . $fechaInicio . "'" : "null";
		$fechaFin = $fechaFin!="" ? "'" . $fechaFin . "'" : "null";
		$cantidad = $cantidad!="" ? "'" . $cantidad . "'" : "null";
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_transportes.mostrar_gasolineras_general($fechaInicio,$fechaFin,$localizacion, $cantidad)");
		return $res;
	}
	
	public function abrirHistorialIndividualGasolinera ($conexion, $gasolinera, $fechaInicio, $fechaFin, $localizacion){
		$localizacion  = $localizacion!="" ? "'" . $localizacion . "'" : "null";
		$gasolinera = $gasolinera!="" ? "'" . $gasolinera . "'" : "null";
		$fechaInicio = $fechaInicio!="" ? "'" . $fechaInicio . "'" : "null";
		$fechaFin = $fechaFin!="" ? "'" . $fechaFin . "'" : "null";
	
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_transportes.mostrar_gasolineras_individual($gasolinera,$fechaInicio,$fechaFin,$localizacion)");
	
		return $res;
	}
	
	public function enviarSiniestroAdministrador($conexion, $id_siniestro, $localizacion, $identificadorUsuarioRegistro){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_transportes.siniestros
											SET
												localizacion= '$localizacion',
												identificador_registro = '$identificadorUsuarioRegistro',
												fecha_envio_siniestro = now()
											WHERE
												id_siniestro ='$id_siniestro';");
	
		return $res;
	}
	
	public function actualizarDatosSiniestroMonto($conexion, $id_siniestro, $montoTerceros, $valorTotal, $identificadorUsuarioRegistro){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_transportes.siniestros
											SET
												monto_danio_terceros = $montoTerceros,
												valor_total = $valorTotal,
												identificador_registro = '$identificadorUsuarioRegistro'
											WHERE
												id_siniestro ='" . $id_siniestro . "';");
				return $res;
	}
	
	public function actualizarFechaSalidaKilometraje($conexion, $idSiniestro, $fechaSalida, $kilometrajeFinal, $identificadorUsuarioRegistro, $razonIncrementoKilometraje){
		$res = $conexion->ejecutarConsulta("UPDATE
												g_transportes.siniestros
											SET
												fecha_habilitacion_vehiculo='$fechaSalida',
												identificador_registro = '$identificadorUsuarioRegistro',
												kilometraje_final = $kilometrajeFinal,
												observacion = 'Vehículo entregado del taller en $fechaSalida y es habilitado por el responsable $identificadorUsuarioRegistro',
												razon_incremento_kilometraje = '$razonIncrementoKilometraje'
											WHERE
												id_siniestro = '$idSiniestro';");
			
		return $res;
	}
	
	public function filtrarSiniestroGeneral($conexion, $placa, $localizacion, $fechaInicio, $fechaFin, $cantidad){
		$localizacion  = $localizacion!="" ? "'" . $localizacion . "'" : "null";
		$qLocalizacion  = $localizacion!='null' ? 'and v.localizacion = '.$localizacion : "";
		$placa = $placa!="" ? "'" . $placa . "'" : "null";
		$fechaInicio = $fechaInicio!="" ? "'" . $fechaInicio . "'" : "null";
		$fechaFin = $fechaFin!="" ? "'" . $fechaFin . "'" : "null";
		$cantidad = $cantidad!="" ? "'" . $cantidad . "'" : "null";
	
		$res = $conexion->ejecutarConsulta("
											SELECT
												v.placa,
												v.marca,
												v.modelo,
												c.valor_total,
												v.localizacion,
												c.estado
											FROM
												g_transportes.mostrar_siniestro_general($placa,$fechaInicio,$fechaFin,$localizacion, $cantidad) as c
												INNER JOIN
												g_transportes.vehiculos v ON v.placa=c.placa $qLocalizacion");
		return $res;
	}
	
	public function abrirHistorialIndividualSiniestro ($conexion, $placa, $fechaInicio, $fechaFin, $localizacion){
	
		$localizacion  = $localizacion!="" ? "'" . $localizacion . "'" : "null";
		$placa = $placa!="" ? "'" . $placa . "'" : "null";
		$fechaInicio = $fechaInicio!="" ? "'" . $fechaInicio . "'" : "null";
		$fechaFin = $fechaFin!="" ? "'" . $fechaFin . "'" : "null";
	
	
		$res = $conexion->ejecutarConsulta("SELECT
												v.marca,
												v.modelo,
												v.anio_fabricacion,
												c.*
											FROM
												g_transportes.mostrar_siniestros_individual($placa,$fechaInicio,$fechaFin,$localizacion) as c
												INNER JOIN
												g_transportes.vehiculos v ON v.placa=c.placa");
	
				return $res;
	}
	
	public function obtenerLocalizacionVehiculo($conexion, $placa){
		$res = $conexion->ejecutarConsulta("SELECT
												localizacion
											FROM
												g_transportes.vehiculos
											WHERE
												placa='$placa';");
			
		return $res;
	}
	
	public function actualizarLocalizacionSiniestro($conexion, $idSiniestro, $localizacion){
		$res = $conexion->ejecutarConsulta("UPDATE
												g_transportes.siniestros
											SET
												localizacion = '$localizacion'
											WHERE
												id_siniestro = '$idSiniestro';");
			
		return $res;
	}
	
	public function guardarRutaDocumento($conexion,$idOrden,$rutaOrden, $tipoOrden){
	
		switch($tipoOrden){
			case 'movilizaciones':
				$id = 'id_movilizacion';
			break;
			case 'mantenimientos':
				$id = 'id_mantenimiento';
			break;
			case 'combustible':
				$id = 'id_combustible';
			break;
		}
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_transportes.$tipoOrden
											SET
												ruta_archivo= '$rutaOrden'
											WHERE
												$id = '$idOrden';");
	
		return $res;
	}
	
	public function buscarOrdenesCombustible($conexion, $placa, $idProvincia, $kilometrajeSolicitud){
	
		$fechaActual = pg_fetch_result($conexion->ejecutarConsulta("select TO_CHAR(NOW(), 'YYYY-MM-DD') as fecha_inicio;"), 0, 'fecha_inicio');
	
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_transportes.combustible
											where
												placa = '$placa' and
												fecha_solicitud = '$fechaActual' and
												id_provincia = $idProvincia and
												km_solicitud = $kilometrajeSolicitud;");
	
		return $res;
	}
	
	public function obtenerReporteUsuariosMovilizados($conexion,$localizacion, $fechaInicio, $fechaFin){
	
		$localizacion = $localizacion!="" ? "'%" . $localizacion . "%'" : "null";
		$fechaInicio = $fechaInicio!="" ? "" . $fechaInicio . "" : "null";
		$fechaFin = $fechaFin!="" ? "" . $fechaFin . "" : "null";
			
		$res = $conexion->ejecutarConsulta("SELECT
												m.id_movilizacion,
												m.tipo_movilizacion,
												m.fecha_solicitud,
												m.descripcion,
												m.placa,
												m.kilometraje_inicial,
												m.kilometraje_final,
												m.localizacion,
												m.observacion_ruta,
												count (m.id_movilizacion) as num_pasajeros,
												fe.nombre,
												fe.apellido,
												m.estado
											FROM
												g_transportes.movilizaciones m,
												g_transportes.ocupantes o,
												g_uath.ficha_empleado fe
											WHERE
												m.id_movilizacion = o.id_movilizacion and
												m.localizacion like $localizacion and
												m.fecha_solicitud >= '$fechaInicio 00:00:00' and
												m.fecha_solicitud <= '$fechaFin 24:00:00' and
												m.conductor = fe.identificador
											GROUP BY
												m.id_movilizacion, o.id_movilizacion, fe.nombre, fe.apellido
											ORDER BY
												m.fecha_solicitud asc;");
	
				return $res;
	}
	
	
				public function obtenerReporteCombustiblesGenerados($conexion,$localizacion, $fechaInicio, $fechaFin){
	
				$localizacion = $localizacion!="" ? "'%" . $localizacion . "%'" : "null";
				$fechaInicio = $fechaInicio!="" ? "" . $fechaInicio . "" : "null";
				$fechaFin = $fechaFin!="" ? "" . $fechaFin . "" : "null";
					
				$res = $conexion->ejecutarConsulta("SELECT
														c.id_combustible,
														c.fecha_solicitud,
														c.placa,
														c.kilometraje,
														c.tipo_combustible,
														g.nombre as gasolinera,
														c.fecha_liquidacion,
														c.valor_liquidacion,
														c.estado,
														c.localizacion,
														c.cantidad_galones,
														c.fecha_despacho,
														c.monto_solicitado,
														c.galones_solicitados,
														razon_cambio_monto,
														fe.nombre,
														fe.apellido
													FROM
														g_transportes.combustible c,
														g_transportes.gasolineras g,
														g_uath.ficha_empleado fe
													WHERE
														c.localizacion like $localizacion and
														c.gasolinera = g.id_gasolinera and
														c.fecha_solicitud >= '$fechaInicio 00:00:00' and
														c.fecha_solicitud <= '$fechaFin 24:00:00' and
														c.conductor = fe.identificador
													ORDER BY
														fecha_solicitud,
														localizacion  asc;");
	
								return $res;
	}
	
	public function obtenerReporteMantenimientosGenerados($conexion,$localizacion, $fechaInicio, $fechaFin){
	
	$localizacion = $localizacion!="" ? "'%" . $localizacion . "%'" : "null";
	$fechaInicio = $fechaInicio!="" ? "" . $fechaInicio . "" : "null";
	$fechaFin = $fechaFin!="" ? "" . $fechaFin . "" : "null";
		
	$res = $conexion->ejecutarConsulta("SELECT
											distinct (m.id_mantenimiento),
											m.motivo,
											m.fecha_solicitud,
											m.placa,
											m.kilometraje,
											m.fecha_liquidacion,
											m.valor_liquidacion,
											m.numero_factura,
											m.estado,
											m.localizacion,
											m.tipo,
											m.observacion,
											m.orden_trabajo,
											m.kilometraje_final,
											fe.nombre,
											fe.apellido,
											t.nombre as taller
										FROM
											g_transportes.mantenimientos m,
											g_uath.ficha_empleado fe,
											g_transportes.talleres t
										WHERE
											m.conductor=fe.identificador and
											m.localizacion  like $localizacion and
											m.fecha_solicitud >= '$fechaInicio 00:00:00' and
											m.fecha_solicitud <= '$fechaFin 24:00:00' and
											m.taller = t.id_taller
										ORDER BY
											m.fecha_solicitud;");
	
		return $res;
	}
	
	public function darBajaCombustible ($conexion, $idCombustible, $usuarioSolicitante, $glpi, $identificadorUsuarioRegistro){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_transportes.combustible
											SET
												estado = 9,
												observacion = 'Eliminada por solicitud de $usuarioSolicitante en ticket $glpi',
												identificador_registro = '$identificadorUsuarioRegistro'
											WHERE
												id_combustible = '$idCombustible';");
		return $res;
	}
	
	
	public function resetearCupoCombustible ($conexion, $idGasolinera, $monto, $identificadorUsuario){
	
	$res = $conexion->ejecutarConsulta("UPDATE
											g_transportes.gasolineras
										SET
											saldo_disponible=$monto,
											identificador_registro = '$identificadorUsuarioRegistro'
										WHERE
											id_gasolinera=$idGasolinera;");
	
	return $res;
	}
	
	public function ultimaMovilizacionVehiculo ($conexion, $placa){
	
	$res = $conexion->ejecutarConsulta("SELECT
											id_movilizacion,
											fecha_solicitud,
											estado,
											kilometraje_inicial,
											kilometraje_final,
											estado
										FROM
											g_transportes.movilizaciones
										WHERE
											placa='$placa' and
											estado not in (9)
										ORDER BY
											fecha_solicitud desc
										LIMIT 1;");
	
		return $res;
	}
	
	public function liberarVehiculoActualizarKilometraje ($conexion, $placa, $kmActual, $identificador){
	
	$res = $conexion->ejecutarConsulta("UPDATE
											g_transportes.vehiculos
										SET
											estado = 1,
											kilometraje_inicial=$kmActual,
											kilometraje_actual=$kmActual,
											identificador_registro='$identificador'
										WHERE
											placa='$placa';");
	
			return $res;
		}

public function abrirMovilizacionDetalle ($conexion, $id_movilizacion){
	
		$res = $conexion->ejecutarConsulta("select
				m.*,
				fe.nombre as nombreconductor,
				fe.apellido
				from
				g_transportes.movilizaciones m,
				g_uath.ficha_empleado fe
				where
				m.id_movilizacion = '$id_movilizacion'
				and m.conductor = fe.identificador;");
				return $res;
	}

public function abrirMantenimientoDetalle ($conexion, $id_mantenimiento){
	
		$res = $conexion->ejecutarConsulta("select
												m.*,
												m.tipo as tipo_mantenimiento,
												v.marca,
												v.modelo,
												v.tipo,
												v.numero_motor,
												v.numero_chasis,
												fe.nombre as nombreconductor,
												fe.apellido,
												t.nombre as nombre_taller
											from	
												g_transportes.mantenimientos m,	
												g_transportes.vehiculos v,
												g_uath.ficha_empleado fe,
												g_transportes.talleres t
											where   
												m.placa = v.placa
												and m.id_mantenimiento= '$id_mantenimiento'
												and m.conductor = fe.identificador
												and m.taller = t.id_taller;");
		return $res;
	}
	
	public function reaperturaOrden ($conexion, $tipoOrden, $numeroOrden, $usuarioSolicitante, $glpi, $identificadorUsuarioRegistro){
	
		switch($tipoOrden){
			case 'Movilizacion':
				$tabla = 'movilizaciones';
				$id = 'id_movilizacion';
				$observacion = "observacion_movilizacion = 'Reapertura por solicitud de $usuarioSolicitante en ticket $glpi'";
				break;
			case 'Mantenimiento':
				$tabla = 'mantenimientos';
				$id = 'id_mantenimiento';
				$observacion = "observacion = 'Reapertura por solicitud de $usuarioSolicitante en ticket $glpi'";
				break;
			case 'Combustible':
				$tabla = 'combustible';
				$id = 'id_combustible';
				$observacion = "observacion = 'Reapertura por solicitud de $usuarioSolicitante en ticket $glpi'";
				break;
		}
		
		$res = $conexion->ejecutarConsulta("UPDATE
												g_transportes.$tabla
											SET
												estado = 1,
												$observacion,
												identificador_registro = '$identificadorUsuarioRegistro'
											WHERE
												$id = '$numeroOrden';");
		
		return $res;
	}
	
	public function eliminarMantenimientoDetalle ($conexion, $idMantenimiento){
	
		$res = $conexion->ejecutarConsulta("DELETE FROM 
												g_transportes.detalle_mantenimientos
							 				WHERE 
												id_mantenimiento = '$idMantenimiento';");
		
		return $res;
	}
	
	public function abrirMovilizacionRutasFechas ($conexion, $idMovilizacion){
	
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_transportes.rutas r
											where
												r.id_movilizacion = '$idMovilizacion' ;");
		
		return $res;
	}
	
	public function buscarMovilizacionRutasFechas ($conexion, $idMovilizacion, $localizacion, $fechaDesde, $fechaHasta){
	
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_transportes.rutas r
											where
												r.id_movilizacion = '$idMovilizacion' and
												r.localizacion = '$localizacion' and
												r.fecha_desde = '$fechaDesde' and
												r.fecha_hasta = '$fechaHasta';");
	
		return $res;
	}


	
}
