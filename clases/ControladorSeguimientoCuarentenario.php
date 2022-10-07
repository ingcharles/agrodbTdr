<?php
class ControladorSeguimientoCuarentenario{
	public function listarSeguimientosDDAOperador($conexion, $provincia){
		$res = $conexion->ejecutarConsulta("SELECT
												d.id_destinacion_aduanera,
												d.id_vue as codigo_certificado,
												d.fecha_inicio,
												array_to_string(
													ARRAY(
											            SELECT
											               dpp.nombre_producto
											            FROM
															g_dda.destinacion_aduanera_productos dpp
											            WHERE
											                dpp.id_destinacion_aduanera = d.id_destinacion_aduanera)
												,', ') as productos,
												'notificado'::text estado_seguimiento
											FROM
												g_dda.destinacion_aduanera d
											WHERE
												d.estado='aprobado' and
												d.tipo_certificado='VEGETAL' and 
												upper(d.provincia_seguimiento) = upper('$provincia') and
												d.estado_seguimiento='true'
											ORDER BY 
												1
											");
		return $res;
	}
	
	public function listarSeguimientosAbiertoCerradosDDAOperador($conexion, $provincia){
		
		$res = $conexion->ejecutarConsulta("(SELECT
												d.id_destinacion_aduanera,
												d.id_vue as codigo_certificado,
												d.fecha_inicio,
												array_to_string(
													ARRAY(
														SELECT
															dpp.nombre_producto
														FROM
															g_dda.destinacion_aduanera_productos dpp
														WHERE
															dpp.id_destinacion_aduanera = d.id_destinacion_aduanera)
												,', ') as productos,
												sc.estado estado_seguimiento
											FROM
												g_dda.destinacion_aduanera d,
												g_seguimiento_cuarentenario.seguimientos_cuarentenarios sc 
											WHERE 
												sc.id_destinacion_aduanera=d.id_destinacion_aduanera and
												upper(d.provincia_seguimiento) = upper('$provincia') and
												sc.estado='abierto' and
												d.estado='aprobado' and
												d.tipo_certificado='VEGETAL' and 
												d.estado_seguimiento='false'
											ORDER BY 1 ASC)
											UNION ALL
											(SELECT
												d.id_destinacion_aduanera,
												d.id_vue as codigo_certificado,
												d.fecha_inicio,
												array_to_string(
													ARRAY(
														SELECT
															dpp.nombre_producto
														FROM
															g_dda.destinacion_aduanera_productos dpp
														WHERE
															dpp.id_destinacion_aduanera = d.id_destinacion_aduanera)
												,', ') as productos,
												sc.estado estado_seguimiento
											FROM
												g_dda.destinacion_aduanera d,
												g_seguimiento_cuarentenario.seguimientos_cuarentenarios sc 
											WHERE 
												sc.id_destinacion_aduanera=d.id_destinacion_aduanera and
												upper(d.provincia_seguimiento) = upper('$provincia') and
												sc.estado='cerrado' and
												d.estado='aprobado' and
												d.tipo_certificado='VEGETAL' and 
												d.estado_seguimiento='false'
											ORDER BY 1 DESC
											FETCH FIRST 50 ROW ONLY)");
		return $res;
	}
	
	public function obtenerSeguimientosCuarentenariosDDA ($conexion, $idDestinacionAduanera){
		$cid = $conexion->ejecutarConsulta("SELECT
												id_destinacion_aduanera, 
												secuencial_seguimiento, 
						      					to_char(fecha_seguimiento,'DD/MM/YYYY') fecha_seguimiento,
												resultado_inspeccion,
												observacion_seguimiento
						 					FROM 
												g_seguimiento_cuarentenario.detalle_seguimientos_carentenarios  dsc,
												g_seguimiento_cuarentenario.seguimientos_cuarentenarios sc
											WHERE 
												sc.id_seguimiento_cuarentenario=dsc.id_seguimiento_cuarentenario and
												sc.id_destinacion_aduanera='$idDestinacionAduanera'
											ORDER BY 
												2;");
	
				while ($fila = pg_fetch_assoc($cid)){
					$res[] = array(
							idDestinacionAduanera=>$fila['id_destinacion_aduanera'],
							numeroSeguimiento=>$fila['secuencial_seguimiento'],
							fechaSeguimiento=>$fila['fecha_seguimiento'],
							resultadoInspeccion=>$fila['resultado_inspeccion'],
							observacionSeguimiento=>$fila['observacion_seguimiento']
					);
				}
		
		return $res;
	}
		
	public function guardarNuevoSeguimientoDDA ($conexion, $idDestinacionAduanera,$numeroSeguimiento,$numeroPlantas,$estado){
		$conexion->ejecutarConsulta("INSERT INTO g_seguimiento_cuarentenario.seguimientos_cuarentenarios(
            							 id_destinacion_aduanera,numero_seguimientos, numero_plantas, estado)
								    VALUES ('$idDestinacionAduanera', '$numeroSeguimiento', '$numeroPlantas', '$estado');");
	}
	
	public function abrirSeguimientoDDA ($conexion, $idDestinacionAduanera){
		$cid=$conexion->ejecutarConsulta("SELECT 
											estado, 
      										numero_seguimientos, 
											numero_plantas, cantidad_producto_cierre, 
       										to_char(fecha_cierre,'DD/MM/YYYY') fecha_cierre, 
											observacion_cierre
  										  FROM 
											g_seguimiento_cuarentenario.seguimientos_cuarentenarios
										  WHERE 
											id_destinacion_aduanera='$idDestinacionAduanera';");
		
		while ($fila = pg_fetch_assoc($cid)){
			$res[] = array(	numeroSeguimientos=>$fila['numero_seguimientos'],
							numeroPlantas=>$fila['numero_plantas'],
							fechaCierre=>$fila['fecha_cierre'],
							cantidadProductoCierre=>$fila['cantidad_producto_cierre'],
							observacionCierre=>$fila['observacion_cierre'],
							estadoSeguimiento=>$fila['estado']
			);
		}
		return $res;
	}
	
	public function consultarSeguimientoDDA ($conexion, $idDestinacionAduanera){
		$res=$conexion->ejecutarConsulta("SELECT 
											id_destinacion_aduanera
										  FROM 	
											g_seguimiento_cuarentenario.seguimientos_cuarentenarios
										  WHERE 
											id_destinacion_aduanera='$idDestinacionAduanera';");
		return $res;
	}
	
	
	public function actualizarSeguimientosDDA ($conexion, $idDestinacionAduanera,$numeroSeguimiento,$numeroPlantas){
		$conexion->ejecutarConsulta("UPDATE 
		 								g_seguimiento_cuarentenario.seguimientos_cuarentenarios
									 SET
		 								numero_seguimientos=$numeroSeguimiento, 
		 								numero_plantas=$numeroPlantas
									 WHERE 
		 								id_destinacion_aduanera='$idDestinacionAduanera';");
	}
	
	public function actualizarEstadoSeguimientoDDA ($conexion, $idDestinacionAduanera){
		$res = $conexion->ejecutarConsulta("UPDATE
												g_dda.destinacion_aduanera
											SET
												estado_seguimiento = 'FALSE'
											WHERE
												id_destinacion_aduanera = $idDestinacionAduanera;");
		return $res;
	}
	
	public function actualizarEstadoMailSeguimientoDDA ($conexion, $idDestinacionAduanera){
		$res = $conexion->ejecutarConsulta("UPDATE
												g_dda.destinacion_aduanera
											SET
												estado_mail = 'Por enviar'
											WHERE
												id_destinacion_aduanera = $idDestinacionAduanera;");
		return $res;
	}
		
	
	public function actualizarSeguimientoDDACierre ($conexion, $idDestinacionAduanera,$cantidadProductoCierre,$fechaCierre,$observacionCierre){
		$conexion->ejecutarConsulta("UPDATE
										g_seguimiento_cuarentenario.seguimientos_cuarentenarios
									SET
										cantidad_producto_cierre=$cantidadProductoCierre,
										fecha_cierre='$fechaCierre',
										observacion_cierre='$observacionCierre',
										estado='cerrado'
									WHERE
										id_destinacion_aduanera='$idDestinacionAduanera';");
	}	
	
	public function abrirDatosDDA ($conexion, $idDestinacionAduanera){
		$cid = $conexion->ejecutarConsulta("SELECT
												(SELECT case when o.razon_social = '' then o.nombre_representante ||' '|| o.apellido_representante else o.razon_social end) nombre_importador,
												d.pais_exportacion,
												stp.nombre as nombre,
												da.nombre_producto,
												peso,
											  	unidad_peso,
												da.unidad,
											  	da.unidad_medida
											FROM
												g_dda.destinacion_aduanera d,
												g_dda.destinacion_aduanera_productos da,
												g_catalogos.productos p,
												g_catalogos.subtipo_productos stp,
												g_operadores.operadores o
											WHERE
												p.id_subtipo_producto = stp.id_subtipo_producto and
												d.id_destinacion_aduanera = da.id_destinacion_aduanera and
												o.identificador=d.identificador_operador and
												d.id_destinacion_aduanera = '$idDestinacionAduanera' and
												da.id_producto = p.id_producto;");
	
				while ($fila = pg_fetch_assoc($cid)){
				$res[] = array( paisExportacion=>$fila['pais_exportacion'],
								nombreSubTipoProducto=>$fila['nombre'],
								nombreProducto=>$fila['nombre_producto'],
								peso=>$fila['peso'],
								unidadPeso=>$fila['unidad_peso'],
								unidad=>$fila['unidad'],
								unidadMedida=>$fila['unidad_medida'],
								nombreImportador=>$fila['nombre_importador']
					);
		}
	
		return $res;
	}

		
	public function listarTecnicoInspectorProvinciaDDA ($conexion, $provincia,$codificacionPerfil){
		$res=$conexion->ejecutarConsulta("SELECT row_to_json(usuarios)
											FROM (select (
												SELECT array_to_json(array_agg(row_to_json(listado)))
												from (select distinct
													fe.identificador,
													fe.nombre,
													fe.apellido,
													fe.mail_personal,
													fe.mail_institucional
												FROM
													g_uath.ficha_empleado fe,
													g_uath.datos_contrato dc,
													g_usuario.usuarios_perfiles up,
													g_usuario.perfiles p
												WHERE
													fe.identificador = up.identificador and
													up.id_perfil = p.id_perfil and
													fe.identificador = dc.identificador and
													dc.estado = 1 and
													upper(dc.provincia) = upper('$provincia') and
													p.codificacion_perfil in $codificacionPerfil
												ORDER BY
													1
												) as listado) as listado_usuarios)
											as usuarios;");
	
		$json = pg_fetch_assoc($res);
		return json_decode($json[row_to_json],true);
	}
	
	public function buscarDDAEnvioMail ($conexion){
		$cid = $conexion->ejecutarConsulta("SELECT
												d.id_destinacion_aduanera,
												d.provincia_seguimiento,
												d.tipo_certificado
											FROM
												g_dda.destinacion_aduanera d
											WHERE 
												estado_seguimiento=TRUE and 
												estado_mail is null
											ORDER BY
												1 ;");
	
				while ($fila = pg_fetch_assoc($cid)){
					$res[] = array( idDestinacionAduanera=>$fila['id_destinacion_aduanera'],
							nombreProvincia=>$fila['nombre_provincia']
					);
				}
	
		return $res;
	}
	
	public function obtenerMaxSecuencialSeguimientoCuarentenario($conexion, $idSeguimiento){
		
		$consulta = "SELECT 
						COALESCE(MAX(CAST(secuencial_seguimiento as  numeric(5))),0)+1 as secuencial 
					FROM 
						g_seguimiento_cuarentenario.detalle_seguimientos_carentenarios 
					WHERE 
						id_seguimiento_cuarentenario = $idSeguimiento";
		
		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
		
	}
	
	public function obtenerSeguimientoCuarentenarioPorIdentificador($conexion, $idSeguimiento){
		
		$consulta = "SELECT 
						sc.*, d.provincia_seguimiento, codigo_certificado
					FROM 
						g_seguimiento_cuarentenario.seguimientos_cuarentenarios sc,
						g_dda.destinacion_aduanera d
					WHERE
						sc.id_destinacion_aduanera = d.id_destinacion_aduanera and 
						id_seguimiento_cuarentenario = $idSeguimiento;";
		
		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
	}
	
	public function guardarNuevoDetalleSeguiminetoCuarentenario($conexion, $idSeguimiento, $secuencialSeguimiento, $fechaSeguimiento, $resultadoSeguimiento, $observacionSeguimineto){
		
		$consulta = "INSERT INTO g_seguimiento_cuarentenario.detalle_seguimientos_carentenarios(id_seguimiento_cuarentenario, secuencial_seguimiento, fecha_seguimiento, resultado_inspeccion, observacion_seguimiento)
						VALUES ($idSeguimiento, $secuencialSeguimiento, '$fechaSeguimiento', '$resultadoSeguimiento', '$observacionSeguimineto')";
		
		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
	}
	
	/*****SEGUIMIENTO CUARENTENARIO SA*******/
	public function listarSeguimientosSADDAOperador($conexion, $provincia){
	
		$res = $conexion->ejecutarConsulta("SELECT
												d.id_destinacion_aduanera,
												d.id_vue as codigo_certificado,
												d.fecha_inicio,
												array_to_string(
												ARRAY(
													SELECT
														dpp.nombre_producto
													FROM
														g_dda.destinacion_aduanera_productos dpp
													WHERE
														dpp.id_destinacion_aduanera = d.id_destinacion_aduanera)
														,', ') as productos,
												'notificado'::text estado_seguimiento,
                                                case when razon_social = '' then nombre_representante ||' '|| apellido_representante else razon_social end nombre_operador
											FROM
												g_dda.destinacion_aduanera d INNER JOIN g_operadores.operadores op ON d.identificador_operador = op.identificador
											WHERE
												d.estado='aprobado' and
												d.tipo_certificado='ANIMAL' and 
												d.proposito='Importación' and 
												upper(d.provincia_seguimiento) = upper('$provincia') and
												d.estado_seguimiento='TRUE'
											ORDER BY
												1 ASC;
				");
				return $res;
	}
	
	public function listarSeguimientosAbiertoCerradosSADDAOperador($conexion, $provincia,$carga,$incremento, $datoIncremento){
		
		if($carga=='NO'){
			$res = $conexion->ejecutarConsulta("(SELECT
														d.id_destinacion_aduanera,
														d.id_vue as codigo_certificado,
														d.fecha_inicio,
														array_to_string(
															ARRAY(
																SELECT
																	dpp.nombre_producto
																FROM
																	g_dda.destinacion_aduanera_productos dpp
																WHERE
																	dpp.id_destinacion_aduanera = d.id_destinacion_aduanera)
														,', ') as productos,
														sc.estado estado_seguimiento,
                                                        case when razon_social = '' then nombre_representante ||' '|| apellido_representante else razon_social end nombre_operador
													FROM
														g_dda.destinacion_aduanera d,
														g_seguimiento_cuarentenario.seguimientos_cuarentenarios_sa sc,
                                                        g_operadores.operadores op 
													WHERE 
                                                        d.identificador_operador = op.identificador and 
														sc.id_destinacion_aduanera=d.id_destinacion_aduanera and
														upper(d.provincia_seguimiento) = upper('$provincia') and
														sc.estado='abierto' and		
														d.estado='aprobado' and
														d.tipo_certificado='ANIMAL' and 
														d.estado_seguimiento='false'
														ORDER BY 1 ASC
													)
												UNION ALL
												(SELECT
														d.id_destinacion_aduanera,
														d.id_vue as codigo_certificado,
														d.fecha_inicio,
														array_to_string(
															ARRAY(
																SELECT
																	dpp.nombre_producto
																FROM
																	g_dda.destinacion_aduanera_productos dpp
																WHERE
																	dpp.id_destinacion_aduanera = d.id_destinacion_aduanera)
														,', ') as productos,
														sc.estado estado_seguimiento,
                                                        case when razon_social = '' then nombre_representante ||' '|| apellido_representante else razon_social end nombre_operador
													FROM
														g_dda.destinacion_aduanera d,
														g_seguimiento_cuarentenario.seguimientos_cuarentenarios_sa sc,
                                                        g_operadores.operadores op
													WHERE
                                                        d.identificador_operador = op.identificador and 
														sc.id_destinacion_aduanera=d.id_destinacion_aduanera and
														upper(d.provincia_seguimiento) = upper('$provincia') and
														sc.estado='cerrado' and		
														d.estado='aprobado' and
														d.tipo_certificado='ANIMAL' and 
														d.estado_seguimiento='false'
													ORDER BY 1 DESC
														offset $datoIncremento rows
														fetch next $incremento rows only) ");
		}else if($carga=='SI'){
			$res = $conexion->ejecutarConsulta("SELECT
													d.id_destinacion_aduanera,
													d.id_vue as codigo_certificado,
													d.fecha_inicio,
													array_to_string(
													   ARRAY(
													       SELECT
													           dpp.nombre_producto
													       FROM
													           g_dda.destinacion_aduanera_productos dpp
													       WHERE
													           dpp.id_destinacion_aduanera = d.id_destinacion_aduanera)
													        ,', ') as productos,
													sc.estado estado_seguimiento,
                                                    case when razon_social = '' then nombre_representante ||' '|| apellido_representante else razon_social end nombre_operador
												FROM
													g_dda.destinacion_aduanera d,
													g_seguimiento_cuarentenario.seguimientos_cuarentenarios_sa sc,
                                                    g_operadores.operadores op
												WHERE
                                                    d.identificador_operador = op.identificador and
													sc.id_destinacion_aduanera=d.id_destinacion_aduanera and
													upper(d.provincia_seguimiento) = upper('$provincia') and
													sc.estado='cerrado' and
													d.estado='aprobado' and
													d.tipo_certificado='ANIMAL' and
													d.estado_seguimiento='false'
												ORDER BY 1 DESC
													offset $datoIncremento rows
													fetch next $incremento rows only ");
		}
				return $res;
	}
	
	
	public function abrirDatosSADDA ($conexion, $idDestinacionAduanera){
		$cid = $conexion->ejecutarConsulta("SELECT distinct
												op.razon_social,
												op.identificador,
												si.nombre_lugar nombre_sitio,
												si.provincia,
												si.canton,
												si.parroquia,
												si.direccion,
												drt.nombre_representante veterinario_autorizado,
												SUM(distinct dpp.unidad) cantidad,
												string_agg(distinct dpp.nombre_producto,', ') as nombre_producto,
												d.pais_exportacion,
												to_char(d.fecha_arribo,'DD/MM/YYYY') fecha_arribo
											FROM
												g_dda.destinacion_aduanera d 
												INNER JOIN g_dda.destinacion_aduanera_productos dpp ON d.id_destinacion_aduanera = dpp.id_destinacion_aduanera
												INNER JOIN g_importaciones.importaciones i ON d.permiso_importacion = i.id_vue
												INNER JOIN g_operadores.areas ar ON i.id_area_seguimiento = ar.id_area
												INNER JOIN g_operadores.productos_areas_operacion pao ON ar.id_area = pao.id_area
												INNER JOIN g_operadores.operaciones ope ON pao.id_operacion = ope.id_operacion
												INNER JOIN g_operadores.representantes_tecnicos rt ON ope.id_operador_tipo_operacion=rt.id_operador_tipo_operacion
												INNER JOIN g_operadores.detalle_representantes_tecnicos drt ON rt.id_representante_tecnico=drt.id_representante_tecnico
												INNER JOIN g_operadores.operadores op ON op.identificador=d.identificador_operador
												INNER JOIN g_operadores.sitios si ON ar.id_sitio = si.id_sitio
											WHERE
												d.id_destinacion_aduanera = '$idDestinacionAduanera'
												and drt.estado IN ('registrado','creado')
											GROUP BY 
												op.razon_social,
												op.identificador,
												si.nombre_lugar,
												si.provincia,
												si.canton,
												si.parroquia,
												si.direccion,
												drt.nombre_representante,
												d.pais_exportacion,
												fecha_arribo;");
	
				while ($fila = pg_fetch_assoc($cid)){
					$res[] = array(
							identificadorOperador=>$fila['identificador'],
							propietario=>$fila['razon_social'],
							nombreSitio=>$fila['nombre_sitio'],
							provincia=>$fila['provincia'],
							canton=>$fila['canton'],
							parroquia=>$fila['parroquia'],
							direccion=>$fila['direccion'],
							veterinarioAutorizado=>$fila['veterinario_autorizado'],
							cantidad=>$fila['cantidad'],
							nombreProducto=>$fila['nombre_producto'],
							paisOrigen=>$fila['pais_exportacion'],
							fechaIngresoEcuador=>$fila['fecha_arribo'],
					);
				}
	
		return $res;
	}
		
	public function actualizarDatosSeguimientoCuarentenario ($conexion, $idImportacion, $estado , $idArea){
		$res = $conexion->ejecutarConsulta("UPDATE
												g_importaciones.importaciones
											SET
												estado_seguimiento = '$estado',
												id_area_seguimiento='$idArea'
											WHERE
												id_importacion = $idImportacion;");
		return $res;
	}
	
	public function actualizarEstadoSeguimientoTrueDDA ($conexion, $idDestinacionAduanera){
		$res = $conexion->ejecutarConsulta("UPDATE
												g_dda.destinacion_aduanera
											SET
												estado_seguimiento = 'TRUE'
											WHERE
												id_destinacion_aduanera = $idDestinacionAduanera;");
		return $res;
	}
	
	public function imprimirLineaSeguimientosCuarentenariosSA($idDetalleSeguimientosCuarentenariosSa,$contador, $fechaRegistro, $cantidadTotalSeguimiento, $resultadoInspeccion){
		return '<tr id="R' . $idDetalleSeguimientosCuarentenariosSa . '">' .
					'<td class="contador" >' .$contador .'</td>' .
					'<td>' .$fechaRegistro.'</td>' .
					'<td align="center">' .$cantidadTotalSeguimiento.'</td>' .
					'<td class="resutadoInspeccion">' .$resultadoInspeccion.'</td>' .
					'<td>' .
						'<form class="abrir" data-rutaAplicacion="seguimientoCuarentenario" data-opcion="modificarSeguimientoSA" data-destino="vistaSeguimientoAbierto" data-accionEnExito="NADA" >' .
							'<input type="hidden" id="id" name="id" value="'.$idDetalleSeguimientosCuarentenariosSa.'"/>'.
							'<input type="hidden" id="ultimoRegistro"  name="ultimoRegistro" value="no" >'.
						'<button class="icono" type="submit" ></button>' .
						'</form>'.
					'</td>'.
				'</tr>';
	}
	
	public function guardarNuevoSeguimientoSADDA ($conexion, $idDestinacionAduanera, $estado, $fechaElaboracion, $coordenadaX, $coordenadaY, $coordenadaZ,  $lote, $csmt, $aic, $fechaIngresoEcuador, $rutaInicioCuarentena){
		$res=$conexion->ejecutarConsulta("INSERT INTO 
												g_seguimiento_cuarentenario.seguimientos_cuarentenarios_sa(
												id_destinacion_aduanera, estado, fecha_elaboracion, coordenada_x, coordenada_y, coordenada_z, lote, csmt, aic, fecha_ingreso_ecuador, ruta_inicio_cuarentena)
											VALUES ('$idDestinacionAduanera', '$estado', '$fechaElaboracion', '$coordenadaX', '$coordenadaY', '$coordenadaZ',  '$lote', '$csmt', '$aic', '$fechaIngresoEcuador', '$rutaInicioCuarentena') RETURNING id_seguimiento_cuarentenario_sa;");
		return $res;
	}
	
	public function guardarNuevoDetalleSeguimientoSADDA ($conexion, $idSeguimientoCuarentenarioSa, $resultadoInspeccion, $cantidadSanos, $cantidadEnfermos, $cantidadMuertos, $cantidadTotal, $DatosJson ,$rutaSacrificioSanitario, $usuario){
		$res=$conexion->ejecutarConsulta("INSERT INTO 
												g_seguimiento_cuarentenario.detalle_seguimientos_cuarentenarios_sa(
												id_seguimiento_cuarentenario_sa, resultado_inspeccion, cantidad_sanos, cantidad_enfermos, cantidad_muertos, cantidad_total_seguimiento, datos_seguimiento ,ruta_sacrificio_sanitario, usuario_registro )
											VALUES 
												('$idSeguimientoCuarentenarioSa','$resultadoInspeccion', '$cantidadSanos', '$cantidadEnfermos', '$cantidadMuertos', '$cantidadTotal', '$DatosJson', '$rutaSacrificioSanitario', '$usuario') RETURNING id_detalle_seguimientos_cuarentenarios_sa;");
		return $res;
	}
	
	public function actualizarDetalleSeguimientoSADDA ($conexion, $idDetalleSeguimientoCuarentenarioSa, $resultadoInspeccion, $cantidadSanos, $cantidadEnfermos, $cantidadMuertos, $cantidadTotal, $DatosJson ,$rutaSacrificioSanitario, $usuario){
		$res=$conexion->ejecutarConsulta("UPDATE 
												g_seguimiento_cuarentenario.detalle_seguimientos_cuarentenarios_sa
   											SET
												resultado_inspeccion='$resultadoInspeccion', cantidad_sanos='$cantidadSanos', cantidad_enfermos='$cantidadEnfermos', cantidad_muertos='$cantidadMuertos', cantidad_total_seguimiento='$cantidadTotal', datos_seguimiento='$DatosJson' ,ruta_sacrificio_sanitario='$rutaSacrificioSanitario', usuario_modificacion='$usuario'
 											WHERE 
												id_detalle_seguimientos_cuarentenarios_sa='$idDetalleSeguimientoCuarentenarioSa';");
		return $res;
	}
		
	public function listarSeguimientoSADDA ($conexion,$idDestinacionAduanera){
		$res =$conexion->ejecutarConsulta("SELECT 
												id_seguimiento_cuarentenario_sa, 
                                                estado, 
                                                coordenada_x, 
                                                coordenada_y, 
                                                coordenada_z, 
                                                lote, 
                                                csmt, 
                                                aic, 
                                                fecha_registro, 
                                                to_char(fecha_ingreso_ecuador,'DD/MM/YYYY') fecha_ingreso_ecuador, 
                                                to_char(fecha_elaboracion,'DD/MM/YYYY') fecha_elaboracion,
												ruta_inicio_cuarentena, 
                                                usuario_cierre,	
                                                ruta_informe_laboratorio, 
                                                ruta_levantamiento_cuarentena, 
                                                to_char(fecha_cierre,'DD/MM/YYYY') fecha_cierre, 
                                                id_destinacion_aduanera
 											FROM 
												g_seguimiento_cuarentenario.seguimientos_cuarentenarios_sa 
                                            WHERE 
                                                id_destinacion_aduanera=$idDestinacionAduanera ;");
		return $res;
	}
		
	public function listarDetalleSeguimientoSADDA ($conexion,$idSeguimientoCuarentenarioSa){
		$res =$conexion->ejecutarConsulta("SELECT 
												id_detalle_seguimientos_cuarentenarios_sa, 
                                                id_seguimiento_cuarentenario_sa, 
                                                resultado_inspeccion, 
                                                cantidad_sanos, 
                                                cantidad_muertos, 
												cantidad_enfermos, 
                                                cantidad_total_seguimiento, 
                                                datos_seguimiento,  
                                                to_char(fecha_registro,'DD/MM/YYYY') fecha_registro
  											FROM 
												g_seguimiento_cuarentenario.detalle_seguimientos_cuarentenarios_sa
					 						WHERE 
												id_seguimiento_cuarentenario_sa=$idSeguimientoCuarentenarioSa 
                                            ORDER BY 
                                                id_detalle_seguimientos_cuarentenarios_sa asc ;");
		return $res;
	}
		
	public function abrirDetalleSeguimientoSADDA ($conexion,$idDetalleSeguimientoCuarentenarioSa){
		$res =$conexion->ejecutarConsulta("SELECT 
												id_detalle_seguimientos_cuarentenarios_sa, id_seguimiento_cuarentenario_sa, resultado_inspeccion, cantidad_sanos, cantidad_muertos, cantidad_enfermos,
												cantidad_total_seguimiento, datos_seguimiento,  to_char(fecha_registro,'DD/MM/YYYY') fecha_registro, ruta_sacrificio_sanitario
											FROM 
												g_seguimiento_cuarentenario.detalle_seguimientos_cuarentenarios_sa
											WHERE 
												id_detalle_seguimientos_cuarentenarios_sa=$idDetalleSeguimientoCuarentenarioSa ;");
		return $res;
	}
		
	public function consultarSeguimientoSADDA ($conexion, $idDestinacionAduanera){
		$res=$conexion->ejecutarConsulta("SELECT
												id_seguimiento_cuarentenario_sa
											FROM
												g_seguimiento_cuarentenario.seguimientos_cuarentenarios_sa
											WHERE
												id_destinacion_aduanera='$idDestinacionAduanera';");
				return $res;
	}
		
	public function actualizarSeguimientoSADDACierre ($conexion,$idDestinacionAduanera, $usuarioCierre,$fechaCierre,$rutaInformeLaboratorio,$rutaLevantamientoCuarentena){
		$conexion->ejecutarConsulta("UPDATE
										g_seguimiento_cuarentenario.seguimientos_cuarentenarios_sa
									SET
										usuario_cierre=$usuarioCierre,
										fecha_cierre='$fechaCierre',
										ruta_informe_laboratorio='$rutaInformeLaboratorio',
										ruta_levantamiento_cuarentena='$rutaLevantamientoCuarentena',
										estado='cerrado'
									WHERE
										id_destinacion_aduanera='$idDestinacionAduanera';");
	}
	
	public function listarProductosSeguimientoSA ($conexion){

		$consulta = "SELECT 
						distinct dp.id_producto, dp.nombre_producto ||' -> '|| sp.nombre as nombre_producto      		
					FROM 
						g_dda.destinacion_aduanera da inner join g_dda.destinacion_aduanera_productos dp on da.id_destinacion_aduanera = dp.id_destinacion_aduanera	
						inner join g_catalogos.productos p on dp.id_producto = p.id_producto		
						inner join g_catalogos.subtipo_productos sp on p.id_subtipo_producto = sp.id_subtipo_producto
						inner join g_catalogos.tipo_productos tp on sp.id_tipo_producto = tp.id_tipo_producto
					WHERE
						da.tipo_certificado='ANIMAL' 		
						and da.estado_seguimiento is not null
						and tp.id_area = 'SA'
						and dp.estado='aprobado' or dp.estado = 'ampliado'		
						ORDER BY 2";
		
		return $conexion->ejecutarConsulta($consulta);
	}
		
}
