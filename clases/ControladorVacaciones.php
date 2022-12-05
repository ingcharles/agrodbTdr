<?php

class ControladorVacaciones
{

	public function obtenerTipoPermiso($conexion)
	{

		$sqlScript = "select * from g_catalogos.tipo_permiso;";
		$res = $conexion->ejecutarConsulta($sqlScript);
		return $res;
	}

	public function obtenerSubTipoPermiso($conexion, $tipoAcceso = null, $subtipo = null)
	{
		$busqueda = ";";
		if ($subtipo != null)
			$busqueda = "where id_subtipo_permiso='" . $subtipo . "' ;";

		if ($tipoAcceso != null)
			$busqueda = "where tipo_acceso='" . $tipoAcceso . "' ;";

		if ($tipoAcceso != null and $subtipo != null)
			$busqueda = "where tipo_acceso='" . $tipoAcceso . "' and id_subtipo_permiso='" . $subtipo . "' ;";

		$sqlScript = "
				select 
						*
			 	from 
						g_catalogos.subtipo_permiso 
				" . $busqueda . "";
		$res = $conexion->ejecutarConsulta($sqlScript);
		return $res;
	}

	public function nuevoPermiso(
		$conexion,
		$subTipoSolicitud,
		$fechaSalida,
		$fechaRetorno,
		$horaSalida,
		$horaRetorno,
		$identificador,
		$minutos_utilizados,
		$fecha_maxima_justificar,
		$rutaArchivo,
		$periodosTomados,
		$fechaSuceso,
		$idAreaPermiso,
		$destinoComision,
		$pivTipo
	) {

		$fechaSalida = $fechaSalida != "" ? "'" . $fechaSalida . "'" : "null";
		$fechaRetorno = $fechaRetorno != "" ? "'" . $fechaRetorno . "'" : "null";
		$fechaSuceso = $fechaSuceso != "" ? "'" . $fechaSuceso . "'" : "null";
		$fecha_maxima_justificar = $fecha_maxima_justificar != "" ? "'" . $fecha_maxima_justificar . "'" : "null";
		$sql = "Insert into 
												g_vacaciones.permiso_empleado(
													    	sub_tipo,
															fecha_inicio,
															fecha_fin,
															identificador,
															minutos_utilizados,
															fecha_maxima_presentar_justificacion,
															estado,
															ruta_archivo,
															periodos_tomados,
															fecha_suceso,
															fecha_solicitud,
															id_area_permiso,
															destino_comision,
															piv_tipo
														)
										 		values(
															'" . $subTipoSolicitud . "',
															" . $fechaSalida . ",
															" . $fechaRetorno . ",
															'" . $identificador . "',
															'" . $minutos_utilizados . "',
															" . $fecha_maxima_justificar . ",
															'creado',
															'" . $rutaArchivo . "',
															'" . $periodosTomados . "',
															" . $fechaSuceso . ",
															'now()',
															'" . $idAreaPermiso . "',
															'" . $destinoComision . "',
															'" . $pivTipo . "'
														)
											RETURNING 
												id_permiso_empleado;";
		$res = $conexion->ejecutarConsultaLOGS($sql);

		return $res;
	}

	public function identificadorJefeSuperior($conexion, $identificadorRevisor, $idRequerimiento, $areaJefe)
	{
		$sqlScript = "UPDATE
							g_vacaciones.permiso_empleado
						SET
							identificador_jefe_superior = '$identificadorRevisor',
							id_area_jefe = '$areaJefe'
						WHERE
							id_permiso_empleado = $idRequerimiento";
		$res = $conexion->ejecutarConsulta($sqlScript);

		return $res;
	}


	public function actualizarRevisorRequerimiento($conexion, $idArea, $identificadorRevisor, $idRequerimiento, $tipo)
	{

		if ($tipo == 'identificadorDistritoA') {

			$sqlScript = "UPDATE
							g_vacaciones.permiso_empleado
						SET
							identificador_distrital_a = '$identificadorRevisor',
							id_area_distrital_a = '$idArea'
						WHERE
							id_permiso_empleado = $idRequerimiento";
		} else {

			$sqlScript = "UPDATE
							g_vacaciones.permiso_empleado
						SET
							identificador_distrital_b = '$identificadorRevisor',
							id_area_distrital_b = '$idArea'
						WHERE
							id_permiso_empleado = $idRequerimiento";
		}

		$res = $conexion->ejecutarConsulta($sqlScript);

		return $res;
	}


	public function listarPermisosSolicitados($conexion, $identificador)
	{

		$sqlScript = "select 	
							pe.id_permiso_empleado,pe.estado,pe.estado_minutos,pe.fecha_inicio, pe.fecha_fin, sp.descripcion_subtipo, pe.sub_tipo, sp.codigo 
					from 
							g_vacaciones.permiso_empleado pe, g_catalogos.subtipo_permiso sp
					where 
							pe.sub_tipo=sp.id_subtipo_permiso
							and pe.identificador='" . $identificador . "' order by 4 desc;";
		$res = $conexion->ejecutarConsulta($sqlScript);
		return $res;
	}
	

	public function obtenerDiaIngresoEmpleado($conexion, $dia)
	{

		$sqlScript = "SELECT 
						EXTRACT(DAY FROM fecha_inicio) AS dia, 
						fecha_inicio, 
						identificador,
						upper(regimen_laboral) as regimen_laboral
					FROM 
						g_uath.datos_contrato 
					WHERE 
						estado=1 
						and regimen_laboral not in ('Servicios Civiles - Profesionales')
						and EXTRACT(DAY FROM fecha_inicio) = $dia;"; //TODO: ordenar por fecha de contrato
		$res = $conexion->ejecutarConsulta($sqlScript);

		return $res;
	}

	public function obtenerPermisoSolicitado($conexion, $id_permiso)
	{

		$sqlScript = "select
							pe.*,sp.*,tp.id_permiso
					from
							g_vacaciones.permiso_empleado pe, g_catalogos.subtipo_permiso sp,g_catalogos.tipo_permiso tp
					where
							pe.sub_tipo=sp.id_subtipo_permiso and
				            sp.id_tipo_permiso=tp.id_permiso
							and pe.id_permiso_empleado='" . $id_permiso . "';";

		$res = $conexion->ejecutarConsulta($sqlScript);

		return $res;
	}

	public function obtenerDatosPermiso($conexion, $id_permiso)
	{

		$res = $conexion->ejecutarConsulta("select
												pe.*,sp.*,tp.id_permiso,fe.nombre, fe.apellido, 
												dc.remuneracion, dc.oficina, dc.nombre_puesto,
												dc.tipo_contrato, dc.direccion, dc.coordinacion
											from
												g_vacaciones.permiso_empleado pe,
												g_catalogos.subtipo_permiso sp,
												g_catalogos.tipo_permiso tp, 
												g_uath.ficha_empleado fe,
												g_uath.datos_contrato dc
											where
												pe.sub_tipo=sp.id_subtipo_permiso and
							                    sp.id_tipo_permiso=tp.id_permiso and
							                    pe.identificador=fe.identificador and
							                    pe.identificador=dc.identificador and
							                    dc.estado=1 and 
												pe.id_permiso_empleado='" . $id_permiso . "';");

		return $res;
	}

	public function obtenerNombreDirector($conexion, $identificadorTH)
	{

		$sqlScript = "select dc.nombre_puesto as puestoTH,
		                   fe.nombre || ' ' || fe.apellido as DirectoraTH,
		                   f.id_area
                    from 
                    		g_uath.ficha_empleado fe, g_uath.datos_contrato dc, g_estructura.funcionarios f
					where 
							dc.identificador=fe.identificador and
							dc.identificador='" . $identificadorTH . "' and
							dc.identificador=f.identificador and
							dc.estado=1;";

		$res = $conexion->ejecutarConsulta($sqlScript);
		return $res;
	}


	public function obtenerSolicitudes($conexion, $tipo_permiso, $fecha_desde, $fecha_hasta, $id_solicitud, $identificador, $id_director, $estado)
	{

		$tipo_permiso = $tipo_permiso != "" ? "'" . $tipo_permiso . "'" : "null";
		$fecha_desde = $fecha_desde != "" ? "'" . $fecha_desde . "'" : "null";
		$fecha_hasta = $fecha_hasta != "" ? "'" . $fecha_hasta . "'" : "null";
		$id_solicitud = $id_solicitud != "" ? "'" . $id_solicitud . "'" : "null";
		$identificador = $identificador != "" ? "'" . $identificador . "'" : "null";
		$id_director = $id_director != "" ? "'" . $id_director . "'" : "null";
		$estado = $estado != "" ? "'" . $estado . "'" : "null";

		$sqlScript = "Select *
				from
				g_vacaciones.mostrar_solicitudes_vacacion(" . $tipo_permiso . "," . $fecha_desde . "," . $fecha_hasta . "," . $id_solicitud . "," . $identificador . "," . $id_director . "," . $estado . ")
				order by fecha_inicio asc";

		$res = $conexion->ejecutarConsulta($sqlScript);
		return $res;
	}

	public function obtenerPermisosRevisionProceso($conexion, $tipo_permiso, $fecha_desde, $fecha_hasta, $id_solicitud, $identificador, $id_director, $estado, $idArea)
	{

		$tipo_permiso = $tipo_permiso != "" ? "'" . $tipo_permiso . "'" : "null";
		$fecha_desde = $fecha_desde != "" ? "'" . $fecha_desde . "'" : "null";
		$fecha_hasta = $fecha_hasta != "" ? "'" . $fecha_hasta . "'" : "null";
		$estado = $estado != "" ? "'" . $estado . "'" : "null";
		$identificador = $identificador != "" ? "'" . $identificador . "'" : "null";
		$idArea = "'" . $idArea . "'";

		$sqlScript = "Select 
						*
					from
						g_vacaciones.mostrar_solicitudes_revision_proceso(" . $tipo_permiso . "," . $fecha_desde . "," . $fecha_hasta . "," . $estado . "," . $idArea . "," . $identificador . ") order by 1 desc;";

		$res = $conexion->ejecutarConsulta($sqlScript);
		return $res;
	}
	public function obtenerPermisosRevisionProcesoLimit($conexion, $tipo_permiso, $fecha_desde, $fecha_hasta, $id_solicitud, $identificador, $id_director, $estado, $idArea, $limit, $offset, $contador)
	{

		$tipo_permiso = $tipo_permiso != "" ? "'" . $tipo_permiso . "'" : "null";
		$fecha_desde = $fecha_desde != "" ? "'" . $fecha_desde . "'" : "null";
		$fecha_hasta = $fecha_hasta != "" ? "'" . $fecha_hasta . "'" : "null";
		$estado = $estado != "" ? "'" . $estado . "'" : "null";
		$identificador = $identificador != "" ? "'" . $identificador . "'" : "null";
		$idArea = "'" . $idArea . "'";

		$sqlScript = "Select
						*
					from
						g_vacaciones.mostrar_solicitudes_revision_proceso(" . $tipo_permiso . "," . $fecha_desde . "," . $fecha_hasta . "," . $estado . "," . $idArea . "," . $identificador . ")
					" . ($contador == 0 ? " limit $limit offset $offset " : "") . " ;";

		$res = $conexion->ejecutarConsulta($sqlScript);
		return $res;
	}
	//------------------------------------------------------------------------------------------------------------
	public function devolverObservacionPermiso($conexion, $idPermiso)
	{
		$sqlScript = "select
						observacion
					from
						g_vacaciones.permiso_empleado
					where
						id_permiso_empleado=$idPermiso";
		$res = $conexion->ejecutarConsulta($sqlScript);
		return $res;
	}

	//------------------------------------------------------------------------------------------------------------	
	public function consultarRegistrosEstadoMinutos($conexion, $estado_minutos)
	{

		$sqlScript = "select 
						id_permiso_empleado, minutos_utilizados, identificador 
					from 
						g_vacaciones.permiso_empleado
					where
						estado_minutos='1'";
		$res = $conexion->ejecutarConsulta($sqlScript);
		return $res;
	}

	public function actualizarEstadoPermiso($conexion, $id_solicitud_permiso, $estado_solicitud)
	{
		$sqlScript = "Update
						 	g_vacaciones.permiso_empleado 
				    set
							estado='" . $estado_solicitud . "'			
					where
							id_permiso_empleado='" . $id_solicitud_permiso . "';";

		$res = $conexion->ejecutarConsulta($sqlScript);
		return $res;
	}

	public function actualizarRutaDocumento($conexion, $id_solicitud_permiso, $ruta)
	{
		$sqlScript = "Update
						 	g_vacaciones.permiso_empleado 
				    set
							ruta_informe='" . $ruta . "'			
					where
							id_permiso_empleado='" . $id_solicitud_permiso . "';";

		$res = $conexion->ejecutarConsulta($sqlScript);
		return $res;
	}

	public function actualizarMinutosActuales($conexion, $id_solicitud_permiso, $minutos)
	{
		$sqlScript = "Update
							g_vacaciones.permiso_empleado
					set
							minutos_actuales='$minutos'
					where
							id_permiso_empleado='" . $id_solicitud_permiso . "';";

		$res = $conexion->ejecutarConsulta($sqlScript);
		return $res;
	}

	public function cambiarEstadoXMinutos($conexion, $estado_minutos)
	{
		$sqlScript = "Update
						 	g_vacaciones.permiso_empleado 
				    set
							estado_minutos='" . $estado_minutos . "'			
					where
							fecha_maxima_presentar_justificacion < current_date and estado='Aprobado';";
		$res = $conexion->ejecutarConsulta($sqlScript);
		return $res;
	}

	public function incrementarSaldosFuncionario($conexion, $usuario, $minutosGenerados, $anio)
	{

		$sqlScript2 = "update
							g_vacaciones.minutos_disponibles_funcionarios
					set
							minutos_disponibles=minutos_disponibles+" . $minutosGenerados . "
					where
							activo=true and 
							anio = $anio and
							identificador='" . $usuario . "';";

		$res = $conexion->ejecutarConsultaLOGS($sqlScript2);
	}

	public function incrementarSaldosFuncionarioNuevoAnio($conexion, $usuario, $minutosGenerados, $anio, $secuencial = 1)
	{

		$sqlScript2 = "INSERT INTO g_vacaciones.minutos_disponibles_funcionarios
					 VALUES ('$usuario',$anio, $minutosGenerados, TRUE,$secuencial);";

		$res = $conexion->ejecutarConsultaLOGS($sqlScript2);
	}

	public function crearObservacionVacacion($conexion, $id_permiso, $descripcion, $identificador)
	{

		$sqlScript = "Insert into g_vacaciones.observaciones
				    (descripcion,codigo_permiso_empleado,identificador)
				 	values('" . $descripcion . "'," . $id_permiso . ",'" . $identificador . "');";

		$res = $conexion->ejecutarConsultaLOGS($sqlScript);

		return $res;
	}


	public function verificarSaldosMayores60($conexion)
	{

		$sqlScript = "select
        					identificador,sum(minutos_disponibles), sum(minutos_disponibles)-28800 as diferencia
					from 
							g_vacaciones.minutos_disponibles_funcionarios							
					where
							activo=true 
					group by identificador
					having sum(minutos_disponibles)>28800";

		$resMayores = $conexion->ejecutarConsulta($sqlScript);

		while ($fila = pg_fetch_assoc($resMayores)) {

			echo '<br/> >>> Usuario ' . $fila['identificador'] . 'con minutos adicionales ' . $fila['diferencia'];

			$sqlRegistro = "select *
						  from g_vacaciones.minutos_disponibles_funcionarios
						  where activo=true and identificador='" . $fila['identificador'] . "' order by anio";

			$resConsulta = $conexion->ejecutarConsulta($sqlRegistro);

			$minutosRestantes = 0;
			while ($filaFuncionario = pg_fetch_assoc($resConsulta)) {

				if ($filaFuncionario['minutos_disponibles'] <= $fila['diferencia']) {

					$minutosRestantes = $fila['diferencia'] - $filaFuncionario['minutos_disponibles'];

					$sqlScript2 = "UPDATE
									g_vacaciones.minutos_disponibles_funcionarios
								SET
									minutos_disponibles = 0,
									activo = FALSE
								WHERE
									identificador='" . $fila['identificador'] . "'
									and anio=" . $filaFuncionario['anio'] . ";";

					$res = $conexion->ejecutarConsulta($sqlScript2);

					$fila['diferencia'] = $minutosRestantes;

					echo '<br/> >>> Desactivación de año ' . $filaFuncionario['anio'] . ', 0 días disponibles';
				} else {

					$sqlScript2 = "UPDATE 
									g_vacaciones.minutos_disponibles_funcionarios
								SET
									minutos_disponibles = minutos_disponibles-" . $fila['diferencia'] . "
								WHERE
									identificador='" . $fila['identificador'] . "' 
									and anio=" . $filaFuncionario['anio'] . "
									and activo=true;";

					$res = $conexion->ejecutarConsulta($sqlScript2);

					echo '<br/> >>> Minutos adicionales eliminados ' . $fila['diferencia'];

					break;
				}
			}
		}
	}

	public function actualizarSaldosFuncionario($conexion, $usuario, $minutosConsumidos, $idPermisoEmpleado)
	{

		$resVacaciones = $conexion->ejecutarConsulta("	Select 
																* 
														from
																g_vacaciones.minutos_disponibles_funcionarios
														where 
																activo=true and			
																identificador='" . $usuario . "'
														ORDER BY
																anio");

		$minutosPendientes = $minutosConsumidos;
		$minutosADescontar = 0;

		while ($fila = pg_fetch_assoc($resVacaciones)) {

			if ($minutosPendientes > 0) {
				if (abs(intval($fila['minutos_disponibles'])) <= $minutosPendientes) {
					$minutosPendientes = $minutosPendientes - $fila['minutos_disponibles'];
					$minutosADescontar = $fila['minutos_disponibles'];
					$estado = 'false';
				} else {
					$minutosADescontar = $minutosPendientes;
					$minutosPendientes -= $minutosPendientes;
					$estado = 'true';
				}

				$res = $conexion->ejecutarConsulta("update
														g_vacaciones.minutos_disponibles_funcionarios
													set
														minutos_disponibles=minutos_disponibles-" . $minutosADescontar . ",
														activo = " . $estado . "
													where
														identificador='" . $usuario . "' and 
														anio='" . $fila['anio'] . "'
														and activo=true ;");

				$res1 = $conexion->ejecutarConsulta("	INSERT INTO 
															g_vacaciones.detalle_descuento_tiempo(
													            id_permiso_empleado, 
																identificador, 
																anio, 
													            tiempo, 
																estado)
													    VALUES (" . $idPermisoEmpleado . ", 
																'" . $usuario . "', 
																'" . $fila['anio'] . "', 
																" . $minutosADescontar . ", 
																'creado');");
			}
		}

		/*
		 $resVacaciones = $conexion->ejecutarConsulta("	Select 
																* 
														from
																g_vacaciones.minutos_disponibles_funcionarios
														where 
																activo=true and			
																identificador='".$usuario."'
														ORDER BY
																anio");
		
		$minutosRestantes=0;
		
		while($fila = pg_fetch_assoc($resVacaciones)){
									
			if(intval($fila['minutos_disponibles'])<$minutosConsumidos){
				$minutosRestantes = $minutosConsumidos - $fila['minutos_disponibles'];	
				$minutosADescontar = $fila['minutos_disponibles'];
			}else{
				$minutosADescontar = $minutosRestantes;
			}

			
			$res = $conexion->ejecutarConsulta("update
													g_vacaciones.minutos_disponibles_funcionarios
												set
													minutos_disponibles=minutos_disponibles-".$minutosADescontar."
												where
													identificador='".$usuario."' and 
													anio='".$fila['anio']."';");
			
		}
		*/
		return $res;
	}

	public function consultarSaldoFuncionario($conexion, $usuario)
	{

		$res = $conexion->ejecutarConsulta("select
											sum(minutos_disponibles) as minutos_disponibles
										  from
											g_vacaciones.minutos_disponibles_funcionarios
										  where
											activo=true
											and identificador='" . $usuario . "'
											group by identificador");

		return $res;
	}

	public function actualizarPermiso(
		$conexion,
		$id_registro,
		$subTipoSolicitud,
		$fechaSalida,
		$fechaRetorno,
		$identificador,
		$minutos_utilizados,
		$fecha_maxima_justificar,
		$rutaArchivo,
		$fechaSuceso,
		$idAreaPermiso,
		$destinoComision,
		$pivTipo
	) {
		$fechaSalida = $fechaSalida != "" ? "'" . $fechaSalida . "'" : "null";
		$fechaRetorno = $fechaRetorno != "" ? "'" . $fechaRetorno . "'" : "null";
		$fechaSuceso = $fechaSuceso != "" ? "'" . $fechaSuceso . "'" : "null";
		$fecha_maxima_justificar = $fecha_maxima_justificar != "" ? "'" . $fecha_maxima_justificar . "'" : "null";

		$sqlScript = "update
							g_vacaciones.permiso_empleado
					set
							sub_tipo='" . $subTipoSolicitud . "',
							fecha_inicio=" . $fechaSalida . ",
							fecha_fin=" . $fechaRetorno . ",
							identificador='" . $identificador . "',
							minutos_utilizados=" . $minutos_utilizados . ",
							fecha_maxima_presentar_justificacion=" . $fecha_maxima_justificar . ",
							ruta_archivo='" . $rutaArchivo . "',
							fecha_suceso=" . $fechaSuceso . ",
							fecha_solicitud='now()',
							id_area_permiso='" . $idAreaPermiso . "',
							destino_comision='" . $destinoComision . "',
							piv_tipo='" . $pivTipo . "',
							estado='creado'
													
					where
							id_permiso_empleado='" . $id_registro . "';";

		$res = $conexion->ejecutarConsultaLOGS($sqlScript);
	}

	public function  incrementoDiaPasadoCincoAnios($conexion, $anio, $minuto)
	{
		$res = $conexion->ejecutarConsulta("SELECT g_vacaciones.incrementodiapasadocincoanios(
			" . $anio . ",
			" . $minuto . "
		);");

		return $res;
	}

	public function agregarObservacion($conexion, $descripcion, $idPermiso, $identificador)
	{

		$res = $conexion->ejecutarConsulta("INSERT INTO 
											g_vacaciones.observaciones(
										            descripcion, 
													codigo_permiso_empleado, 
													identificador, 
										            fecha)
										  VALUES (  '$descripcion', 
													$idPermiso, 
													'$identificador', 
										            now());");

		return $res;
	}

	//--------------------------------------------------------------------------------------------------
	public function agregarObservacionPermiso($conexion, $observacion, $idPermiso)
	{

		$res = $conexion->ejecutarConsulta("
				 UPDATE 
						g_vacaciones.permiso_empleado
   				 SET 
						observacion='$observacion'
				 WHERE 
						id_permiso_empleado=$idPermiso;");

		return $res;
	}

	//--------------------------------------------------------------------------------------------------

	public function buscarPermisosXSubtipo($conexion, $identificador, $idSubtipo, $estado)
	{

		$res = $conexion->ejecutarConsulta("	SELECT 
												*
											FROM 
												g_vacaciones.permiso_empleado
											WHERE
												sub_tipo in ($idSubtipo) and
												identificador='$identificador' and
												estado in ($estado);");

		return $res;
	}

	public function obtenerSubTipoPermisoXCodigo($conexion, $codigo)
	{

		$res = $conexion->ejecutarConsulta("select 
												* 
											from 
												g_catalogos.subtipo_permiso 
											where 
												codigo in ($codigo);");

		return $res;
	}

	public function buscarPermisosRangoFecha($conexion, $fechaInicio, $fechaFin, $identificador, $idPermiso = 0)
	{

		if ($idPermiso != 0) {
			$mensaje = "pe.id_permiso_empleado not in ($idPermiso) and";
		} else {
			$mensaje = null;
		}

		$sql = "	SELECT 
				pe.*
			FROM 
				g_vacaciones.permiso_empleado pe,
				g_catalogos.subtipo_permiso sp
			WHERE 
				pe.identificador = '$identificador' and
				pe.estado not in ('Rechazado', 'eliminado', 'Eliminado') and
				" . $mensaje . "
				((pe.fecha_inicio between '" . $fechaInicio . "' and '" . $fechaFin . "') or (pe.fecha_fin between '" . $fechaInicio . "' and '" . $fechaFin . "')) 
				--(pe.fecha_inicio::DATE, pe.fecha_fin::DATE) OVERLAPS ('" . $fechaInicio . "'::DATE, '" . $fechaFin . "'::DATE) 
				and pe.sub_tipo = sp.id_subtipo_permiso and
				sp.codigo not in (
									'EN-EF',
									'EN-EC',
									'EC-HH',
									'NA-MA',
									'CD-FEP',
									'PE-ER',
									'PE-RN',
									'EN-RE',
									'NA-NED',
									'PE-AM'
								 );";
		$res = $conexion->ejecutarConsulta($sql);

		return $res;
	}

	public function obtenerTiempoDisponibleFuncionario($conexion, $identificador)
	{

		$res = $conexion->ejecutarConsulta("select 
												*
											from 
												g_vacaciones.minutos_disponibles_funcionarios
											where 
												activo=true and 
												identificador='$identificador' 
											order by 
												anio asc
											limit 1;");

		return $res;
	}

	public function generarNumeroAccionPersonal($conexion, $idArea)
	{

		$res = $conexion->ejecutarConsulta("SELECT
												MAX(numero_accion_personal)::integer +1 as numero
											FROM
												g_vacaciones.permiso_empleado
											WHERE
												id_area_permiso = '$idArea';");

		return $res;
	}

	public function actualizarNumeroAccionPersonal($conexion, $idSolicitudPermiso, $numeroSolicitud)
	{
		$res = $conexion->ejecutarConsulta("Update
												g_vacaciones.permiso_empleado
											set
												numero_accion_personal='" . $numeroSolicitud . "'
											where
												id_permiso_empleado='" . $idSolicitudPermiso . "';");

		return $res;
	}

	public function obtenerAnioMayor($conexion, $identificador)
	{

		$res = $conexion->ejecutarConsulta("SELECT
												max(anio) as anio
											FROM
												g_vacaciones.minutos_disponibles_funcionarios
											WHERE
												activo=true and
												identificador='$identificador';");

		return $res;
	}

	public function obtenerSecuencialanio($conexion, $identificador, $anio)
	{

		$sql = "SELECT												
												max(secuencial) as secuencial
											FROM
												g_vacaciones.minutos_disponibles_funcionarios
											WHERE												
												identificador='$identificador' and
												anio=$anio;";
		$res = $conexion->ejecutarConsulta($sql);

		return $res;
	}


	public function obtenerContratosActivos($conexion)
	{

		$res = $conexion->ejecutarConsulta("SELECT
												identificador
											FROM
												g_uath.datos_contrato
											WHERE
												estado = 1
											GROUP BY 
												identificador
											HAVING
												count(identificador) > 1;");

		return $res;
	}

	public function actualizarEstadoContrato($conexion, $identificador)
	{

		$res = $conexion->ejecutarConsulta("UPDATE 
												g_uath.datos_contrato
											SET
												estado = 2
											WHERE
												id_datos_contrato != (SELECT max(id_datos_contrato) FROM g_uath.datos_contrato WHERE identificador = '$identificador') and
												identificador = '$identificador' and
												estado IN (1)");

		return $res;
	}

	public function actualizarCertificadoPermiso($conexion, $id_registro, $rutaArchivo)
	{

		$res = $conexion->ejecutarConsulta("update
												g_vacaciones.permiso_empleado
											set
												ruta_archivo='" . $rutaArchivo . "'
											where
												id_permiso_empleado=" . $id_registro . ";");

		return $res;
	}

	public function filtroObtenerReporteSaldoUsuario($conexion, $identificador, $estado, $apellido, $nombre, $area, $tipoReporte)
	{

		$busqueda = '';
		$parametros = '';
		$agrupacion = '';
		$orden = '';


		if ($tipoReporte != 'unico') {
			$parametros = "mdf.*, fe.nombre, fe.apellido";
			$orden = "ORDER BY 1,2";
		} else {
			$parametros = "sum(minutos_disponibles) as minutos_disponibles, fe.nombre, fe.apellido, mdf.identificador";
			$agrupacion = "GROUP BY fe.nombre, fe.apellido, mdf.identificador";
		}

		if ($identificador != '') {
			$busqueda = "and mdf.identificador IN ('$identificador')";
		}

		if ($apellido != '') {
			$busqueda .= " and mdf.identificador IN (SELECT identificador FROM g_uath.ficha_empleado WHERE upper(apellido) like upper('%$apellido%'))";
		}

		if ($nombre != '') {
			$busqueda .= " and mdf.identificador IN (SELECT identificador FROM g_uath.ficha_empleado WHERE upper(nombre) like upper('%$nombre%'))";
		}

		if ($area != '') {

			if ($area == 'DE') {

				$areaSubproceso = "'" . $area . "',";
			} else {
				$areaProceso = $conexion->ejecutarConsulta("select
						*
						from
								g_estructura.area
						where
								id_area_padre = '$area'
						UNION
						select
						*
						from
								g_estructura.area
						where
								id_area = '$area'
						order by
								id_area asc;");

				while ($fila = pg_fetch_assoc($areaProceso)) {
					$areaSubproceso .= "'" . $fila['id_area'] . "',";
				}
			}

			$areaSubproceso = "(" . rtrim($areaSubproceso, ',') . ")";

			$busqueda .= ' and mdf.identificador IN (SELECT identificador FROM g_estructura.funcionarios WHERE id_area IN ' . $areaSubproceso . ')';
		}

		if ($estado != '')
			$estadosql = "and mdf.activo = '$estado'";
		else $estadosql = '';


		$res = $conexion->ejecutarConsulta("SELECT
												" . $parametros . "
											FROM	
												g_vacaciones.minutos_disponibles_funcionarios mdf,
												g_uath.ficha_empleado fe
											WHERE
												mdf.identificador = fe.identificador
												" . $estadosql . "
												" . $busqueda . "
												" . $agrupacion . "
												" . $orden . "");


		return $res;
	}

	public function filtroObtenerReporteHistoricoUsuario($conexion, $identificador, $apellido, $nombre, $fechaInicio, $fechaFin, $tipoPermiso, $subtipoPermiso, $estadoVacacion, $area)
	{


		$busqueda = '';

		if ($identificador != '') {
			$busqueda = "and pe.identificador IN ('$identificador')";
		}

		if ($apellido != '') {
			$busqueda .= " and pe.identificador IN (SELECT identificador FROM g_uath.ficha_empleado WHERE upper(apellido) like upper('%$apellido%'))";
		}

		if ($nombre != '') {
			$busqueda .= " and pe.identificador IN (SELECT identificador FROM g_uath.ficha_empleado WHERE upper(nombre) like upper('%$nombre%'))";
		}

		if ($fechaInicio != '') {
			$fechaInicio = $fechaInicio . ' 00:00:00';

			$busqueda .= " and pe.fecha_inicio > '$fechaInicio' ";
		}

		if ($fechaFin != '') {
			$fechaFin = $fechaFin . ' 24:00:00';

			$busqueda .= " and pe.fecha_inicio < '$fechaFin' ";
		}

		if ($tipoPermiso != '') {
			$busqueda .= " and tp.id_permiso = $tipoPermiso";
		}

		if ($subtipoPermiso != '') {
			$busqueda .= " and pe.sub_tipo = $subtipoPermiso";
		}

		if ($estadoVacacion != '') {
			$busqueda .= " and pe.estado = '$estadoVacacion'";
		}

		if ($area != '') {

			$areaProceso = $conexion->ejecutarConsulta("select
															*
														from
															g_estructura.area
														where
															id_area_padre = '$area'
															
														UNION
															
															select
																*
															from
																g_estructura.area
															where
																id_area = '$area'
															order by
																id_area asc;");

			while ($fila = pg_fetch_assoc($areaProceso)) {
				$areaSubproceso .= "'" . $fila['id_area'] . "',";
			}

			$areaSubproceso = "(" . rtrim($areaSubproceso, ',') . ")";

			$busqueda .= ' and pe.identificador IN (SELECT identificador FROM g_estructura.funcionarios WHERE id_area IN ' . $areaSubproceso . ')';
		}

		$res = $conexion->ejecutarConsulta("SELECT
												id_permiso_empleado,
												fecha_inicio,
												fecha_fin,
												fe.identificador,
												minutos_utilizados,
												estado,
												descripcion_subtipo,
												fe.apellido ||' '||fe.nombre as nombre,
												minutos_actuales,
												id_subtipo_permiso,
												sp.codigo
											FROM
													g_vacaciones.permiso_empleado pe,
													g_catalogos.subtipo_permiso sp,
													g_catalogos.tipo_permiso tp,
													g_uath.ficha_empleado fe
											WHERE
												pe.sub_tipo = sp.id_subtipo_permiso and
												sp.id_tipo_permiso = tp.id_permiso and
												pe.identificador = fe.identificador 
												" . $busqueda . " order by id_permiso_empleado desc");


		return $res;
	}

	public function DiasHabiles($fecha_inicial, $fecha_final)
	{
		list($year, $mes, $dia) = explode("-", $fecha_inicial);
		$ini = mktime(0, 0, 0, $mes, $dia, $year);
		list($yearf, $mesf, $diaf) = explode("-", $fecha_final);
		$fin = mktime(0, 0, 0, $mesf, $diaf, $yearf);

		$r = 0;
		while ($ini != $fin) {
			$date = date('N', mktime(0, 0, 0, $mes, $dia + $r, $year));

			if ($date != 6 && $date != 7) {
				$newArray[] .= $date;
			}

			$ini = mktime(0, 0, 0, $mes, $dia + $r, $year);
			$r++;
		}
		return $newArray;
	}
	//----------------------------------------------------------------------------------------------------------------------------------------
	public function devolverNumDias($fecha_inicial, $fecha_final)
	{
		list($year, $mes, $dia) = explode("-", $fecha_inicial);
		$ini = mktime(0, 0, 0, $mes, $dia, $year);
		list($yearf, $mesf, $diaf) = explode("-", $fecha_final);
		$fin = mktime(0, 0, 0, $mesf, $diaf, $yearf);

		$r = 0;
		while ($ini != $fin) {
			$date = date('N', mktime(0, 0, 0, $mes, $dia + $r, $year));
			$newArray[] .= $date;
			$ini = mktime(0, 0, 0, $mes, $dia + $r, $year);
			$r++;
		}
		return $newArray;
	}

	//----------------------------------------------------------------------------------------------------------------------------------------
	public function devolverMinutSaldRetor($horaS, $horaR, $diasHabiles)
	{
		$horaSalida = substr($horaS, 0, 2);
		$minutosSalida = substr($horaS, 3, 2);

		$horaRetorno = substr($horaR, 0, 2);
		$minutosRetorno = substr($horaR, 3, 2);

		$hora1S = strtotime($horaS);
		$hora2R = strtotime($horaR);
		$hora1Sxx = strtotime("08:00");
		$hora2Rxx = strtotime("16:30");

		$minutosd1 = $minutosd2 = 0;
		$minutosSalidaRest = 60 - $minutosSalida;
		//-------------------------------CALCULAR TIEMPO DE SALIDA---------------------------------------------------
		if ($hora1S > $hora1Sxx) {
			if ($horaSalida == 8) {
				$minutosd1 = 420 + $minutosSalidaRest;
			} else {
				$ban = 1;
				$horaRest = abs(16 - $horaSalida);
				$minutosSalida .= $minutosSalida + 30;

				if ($horaRest > 8) {
					$ban = 0;
					$horaRest = 8;
					$minutosd1 = abs(($horaRest * 60) - $minutosSalida);
				}
				if ($horaRest < 8) {

					$minutosd1 = abs(($horaRest * 60) + $minutosSalida);
					$ban = 0;
				}
				if ($horaRest == 0) {
					$minutosd1 = 30;
					$ban = 0;
				}
				if ($ban == 1) $minutosd1 = abs($horaRest * 60 - $minutosSalida);
			}
			$diasHabiles--;
		}
		//-------------------------------CALCULAR TIEMPO DE RETORNO---------------------------------------------
		if ($hora2R < $hora2Rxx and $hora2R >= $hora1Sxx) {
			if ($horaRetorno == 16) {
				$minutosd2 = 450 + $minutosRetorno;
			} else {
				$ban = 1;
				$horaRest = abs($horaRetorno - 8);
				if ($horaRest > 8) {
					$ban = 0;
					$horaRest = 8;
					$minutosd2 = abs(($horaRest * 60) - $minutosRetorno);
				}
				if ($horaRest < 8) {
					$ban = 0;
					$minutosd2 = $horaRest * 60 + $minutosRetorno;
				}
				if ($horaRest == 0) {
					$minutosd2 = $minutosRetorno;
					$ban = 0;
				}
				if ($ban == 1) $minutosd2 = abs(($horaRest - 1) * 60 + $minutosRetorno);
			}
			$diasHabiles--;
		}
		if ($hora2R > $hora2Rxx) {
			$minutosd2 = 480;
			$diasHabiles--;
		}
		if ($hora2R < $hora1Sxx) {
			$diasHabiles--;
		}
		//-----------------------------------------------------------------------------------------------
		if ($diasHabiles <= 0) {
			$minutos = $minutosd1 + $minutosd2;
		} else {
			$minutos = ($diasHabiles * 480) + $minutosd1 + $minutosd2;
		}
		return $minutos;
	}
	//----------------------------------------------------------------------------------------------------------------------------------------
	public function devolverMinutUnDia($horaS, $horaR)
	{
		$horaSalida = substr($horaS, 0, 2);
		$minutosSalida = substr($horaS, 3, 2);

		$horaRetorno = substr($horaR, 0, 2);
		$minutosRetorno = substr($horaR, 3, 2);

		$hora1S = strtotime($horaS);
		$hora2R = strtotime($horaR);
		$hora1Sxx = strtotime("08:00");
		$hora2Rxx = strtotime("16:30");

		$minutosd1 = $minutosd2 = 0;
		$minutosSalidaRest = 60 - $minutosSalida;

		//----------------------------------SALIDA---------------------------------------------------	
		if ($hora1S == $hora1Sxx && $hora2R == $hora2Rxx) {
			$minutos = 480;
		} else {
			$horasRest = $horaRetorno - $horaSalida;
			if ($horaSalida < 12 && $horaRetorno >= 12) {
				$hora1 = 12 - $horaSalida;
				if ($hora1 == 4) $hora1--;
				$horaMinu1 = ($hora1 * 60) - $minutosSalida;
				$hora2Minu2 = (($horaRetorno - 12) * 60) + $minutosRetorno;
				$minutos = $horaMinu1 + $hora2Minu2;
			}
		}
		return $minutos;
	}
	//-------------------------------------------------------------------------------------------- 
	public function devolverMinutosHoras($hhhh, $mmm, $banfecha)
	{

		$horaSalida = substr($hhhh, 0, 2);
		$minutosSalida = substr($hhhh, 3, 2);
		$horaRetorno = substr($mmm, 0, 2);
		$minutosRetorno = substr($mmm, 3, 2);

		$hora1S = strtotime($hhhh);
		$hora2R = strtotime($mmm);
		$horaNeutral = strtotime("12:00");
		$minutos = 0;

		if ($hora1S < $horaNeutral && $hora2R > $horaNeutral && $banfecha != 1) {
			$horaT1 = 12 - $horaSalida;
			$minutosd1 = ($horaT1 * 60) - $minutosSalida;
			$horaT2 = $horaRetorno - 12;
			$minutosd2 = ($horaT2 * 60) + $minutosRetorno;
			$minutos = $minutosd1 + $minutosd2;
		}
		if ($hora1S < $horaNeutral && $hora2R <= $horaNeutral && $banfecha != 1) {
			$horasT = abs($horaSalida - $horaRetorno);

			if ($minutosSalida > $minutosRetorno) {
				$minutosT = $minutosSalida - $minutosRetorno;
				$minutos = abs(($horasT * 60) - $minutosT);
			}
			if ($minutosSalida < $minutosRetorno) {
				$minutosT = $minutosRetorno - $minutosSalida;
				$minutos = abs(($horasT * 60) + $minutosT);
			}
			if ($minutosSalida == $minutosRetorno) {
				$minutos = $horasT * 60;
			}
			if ($horaRetorno == $horaSalida) {
				if ($minutosSalida > $minutosRetorno)
					$minutos = 0;
			}
			if ($horaRetorno < $horaSalida) {
				$minutos = 0;
			}
		}
		if ($hora1S >= $horaNeutral && $hora2R > $horaNeutral && $banfecha != 1) {
			$horasT = abs($horaRetorno - $horaSalida);

			if ($minutosSalida > $minutosRetorno) {
				$minutosT = $minutosSalida - $minutosRetorno;
				$minutos = abs(($horasT * 60) - $minutosT);
			}
			if ($minutosSalida < $minutosRetorno) {
				$minutosT = $minutosRetorno - $minutosSalida;
				$minutos = abs(($horasT * 60) + $minutosT);
			}
			if ($minutosSalida == $minutosRetorno) {
				$minutos = $horasT * 60;
			}
			if ($horaRetorno == $horaSalida) {
				if ($minutosSalida > $minutosRetorno)
					$minutos = 0;
			}
			if ($horaRetorno < $horaSalida) {
				$minutos = 0;
			}
		}
		if ($hora1S >= $horaNeutral && $hora2R < $horaNeutral && $banfecha == 1) {
			$horaT1 = 24 - $horaSalida;
			$minutosd1 = ($horaT1 * 60) - $minutosSalida;

			$minutos = $minutosd1 + ($horaRetorno * 60) + $minutosRetorno;
		}
		return $minutos;
	}

	//----------------------------------------------------------------------------------------------
	public function devolverJefeImnediato($conexion, $identificadorUsuario)
	{

		//Área de usuario para revisión y aprobación de jefe inmediato
		$areaUsuario = pg_fetch_assoc($conexion->ejecutarConsulta("select
																			a.*
																	from
																			g_estructura.area as a,
																			g_estructura.funcionarios as f
																	where
																			a.id_area = f.id_area
																			and f.identificador = '$identificadorUsuario'"));
		$idAreaFuncionario = $areaUsuario['id_area'];
		/*
		//---devolver niveles del área consultada-----------------------------------------------------------------------
		$areaRecursiva = pg_fetch_assoc($conexion->ejecutarConsulta("WITH RECURSIVE area_cte(id, nombre_area, path,clasificacion) AS (
																	SELECT 
																			tn.id_area,tn.nombre,tn.id_area::TEXT AS path,tn.clasificacion
																	FROM
																			g_estructura.area AS tn
																	WHERE
																			tn.id_area_padre IS NULL and estado=1
																	UNION ALL
																	SELECT
																			c.id_area, c.nombre,(p.path || ',' || c.id_area::TEXT),c.clasificacion
																	FROM
																			area_cte AS p,g_estructura.area AS c
																	WHERE
																			c.id_area_padre = p.id and
																			c.estado=1
																	)SELECT
																			*
																	FROM
																			area_cte AS n
																	WHERE
																			n.id='$idAreaFuncionario'
																	ORDER BY n.id ASC;"));*/
		$areaRecursiva = $this->nivelesAreaConsultada($conexion, $idAreaFuncionario);

		$tipoArea = $areaRecursiva['clasificacion'];
		$arrayAreas = explode(',', $areaRecursiva['path']);
		$numAreas = sizeof($arrayAreas) - 1;

		//-------verificar responsabilidad---------------------------------------------
		$verificar = $this->verificarResponsable($conexion, $identificadorUsuario);
		while ($fila = pg_fetch_assoc($verificar)) {
			if ($fila['categoria_area'] < $areaUsuario['categoria_area']) {
				//$areaUsuario=$fila['id_area'];
				$numAreas--;
			}
		}
		$idArea = $areaUsuario;
		$ban = 0;
		for ($i = $numAreas, $j = 0; $j < $numAreas; $i--, $j++) {
			$idArea = $arrayAreas[$i];
			$identificadorJefe = pg_fetch_result($conexion->ejecutarConsulta("select
															*
													from
															g_estructura.responsables
													where
															id_area = '$idArea'
															and responsable = true
															and estado = 1;"), 0, 'identificador');

			if (!strcmp($identificadorJefe, $identificadorUsuario) == 0 and $identificadorJefe != '') {
				break;
			}
		}
		$idAreaPermiso = '';
		for ($i = $numAreas, $j = 0; $j < $numAreas; $i--, $j++) {
			$idAreaP = $arrayAreas[$i];
			if ($areaUsuario['clasificacion'] == 'Planta Central') {
				$idAreaPermiso = 'DGATH';
				break;
			} else {
				$idAreaPermiso = pg_fetch_result(($conexion->ejecutarConsulta("SELECT
						*
						FROM
								g_estructura.area
						WHERE
								id_area_padre = '$idAreaP'
								and clasificacion = 'Dirección Distrital A';")), 0, 'id_area');
				if ($idAreaPermiso != '') break;
			}
		}
		if ($idArea == 'DE')
			$idAreaPermiso = 'DGATH';
		$usuarioDatos = pg_fetch_assoc($this->datosFuncionario($conexion, $identificadorJefe));

		$resultConsulta = array(
			'identificador'	=>	$identificadorJefe,
			'idarea'	=>	$idAreaPermiso,
			'idareajefe'	=>	$idArea,
			'usuario'	=> $usuarioDatos['user'],
			'idareafuncionario' => $idAreaFuncionario
		);
		return $resultConsulta;
	}
	//---------------------------------------------------------------------------------------------
	public function nivelesAreaConsultada($conexion, $idAreaFuncionario)
	{

		//---devolver niveles del área consultada-----------------------------------------------------------------------
		$areaRecursiva = pg_fetch_assoc($conexion->ejecutarConsulta("WITH RECURSIVE area_cte(id, nombre_area, path,clasificacion) AS (
			SELECT
			tn.id_area,tn.nombre,tn.id_area::TEXT AS path,tn.clasificacion
			FROM
			g_estructura.area AS tn
			WHERE
			tn.id_area_padre IS NULL and estado=1
			UNION ALL
			SELECT
			c.id_area, c.nombre,(p.path || ',' || c.id_area::TEXT),c.clasificacion
			FROM
			area_cte AS p,g_estructura.area AS c
			WHERE
			c.id_area_padre = p.id and
			c.estado=1
	)SELECT
			*
			FROM
			area_cte AS n
			WHERE
			n.id='$idAreaFuncionario'
			ORDER BY n.id ASC;"));

		return $areaRecursiva;
	}


	//---------------------------------------------------------------------------------------------- 
	public function datosFuncionario($conexion, $identificador)
	{

		$sqlScript = "SELECT 
	            apellido ||' '||nombre as user
		FROM
			g_uath.ficha_empleado
		WHERE
		identificador='$identificador'";
		$res = $conexion->ejecutarConsulta($sqlScript);
		return $res;
	}


	//----------------------------------------------------------------------------------------------
	public function verificarResponsable($conexion, $identificador)
	{

		$sqlScript = "select 
					*
				from g_estructura.responsables res,
					g_estructura.area ar 
				where 
					res.identificador='$identificador' and 
					res.responsable = true and
					ar.id_area = res.id_area";
		$res = $conexion->ejecutarConsulta($sqlScript);
		return $res;
	}



	//----------------------------------------------------------------------------------------------
	public function graficoEstructura($conexion, $clasificacion, $padre)
	{

		$sql = "SELECT
	   					 *
				FROM
						g_estructura.area a
				WHERE
						a.estado = 1 and 
						a.id_area_padre='" . $padre . "' and 
						a.clasificacion='" . $clasificacion . "'";

		$res = $conexion->ejecutarConsulta($sql);
		return $res;
	}
	//----------------------------------------------------------------------------------------------
	public function graficoEstructura2($conexion, $categoria, $padre)
	{

		$sql = "SELECT
	*
	FROM
	g_estructura.area a
	WHERE
	a.estado = 1 and
	a.id_area_padre='" . $padre . "' and
	a.categoria_area=$categoria order by 5";

		$res = $conexion->ejecutarConsulta($sql);
		return $res;
	}
	//------------------------------------------------------------------------------------------------
	public function buscarExcelDescuentos($conexion, $mes, $ano, $idExcelDescuento = null)
	{
		$mes = $mes != "" ? "'" .  $mes  . "'" : "NULL";
		$ano = $ano != "" ? "'" .  $ano  . "'" : "NULL";
		$idExcelDescuento = $idExcelDescuento != "" ? "'" .  $idExcelDescuento  . "'" : "NULL";
		if (($mes == "NULL") && ($ano == "NULL") && ($idExcelDescuento == "NULL")) {
			$busqueda = " limit 24";
		}
		$sql = "SELECT
					mes_descuento,
					anio_descuento,
					ruta_archivo,
					id_excel_descuento,
					nombre_archivo
			FROM
					g_vacaciones.excel_descuentos
					where
					($mes is NULL or  mes_descuento = $mes) and
					($ano is NULL or  anio_descuento = $ano) and
					($idExcelDescuento is NULL or  id_excel_descuento = $idExcelDescuento)
					order by 4 desc " . $busqueda . " ;";
		$res = $conexion->ejecutarConsulta($sql);
		return $res;
	}
	//------------------------------------------------------------------------------------------------------------------------

	public function guardarNuevoExcelDescuento($conexion, $mes, $ano, $rutaArchivoExcel, $nombreArchivoExcel)
	{
		$sql = "
	 INSERT INTO 
	 		g_vacaciones.excel_descuentos(mes_descuento, anio_descuento, ruta_archivo,nombre_archivo, fecha_creacion)
	 VALUES ( '$mes', '$ano', '$rutaArchivoExcel','$nombreArchivoExcel', now()) RETURNING id_excel_descuento;";
		$res = $conexion->ejecutarConsulta($sql);
		return $res;
	}

	//-------------------------------------------------------------------------------------------------------
	public function nuevoPermisoDescuento(
		$conexion,
		$subTipoSolicitud,
		$fechaSalida,
		$fechaRetorno,
		$horaSalida,
		$horaRetorno,
		$identificador,
		$minutos_utilizados,
		$fecha_maxima_justificar,
		$rutaArchivo,
		$periodosTomados,
		$fechaSuceso,
		$idAreaPermiso,
		$destinoComision,
		$pivTipo
	) {

		$fechaSalida = $fechaSalida != "" ? "'" . $fechaSalida . "'" : "null";
		$fechaRetorno = $fechaRetorno != "" ? "'" . $fechaRetorno . "'" : "null";
		$fechaSuceso = $fechaSuceso != "" ? "'" . $fechaSuceso . "'" : "null";
		$fecha_maxima_justificar = $fecha_maxima_justificar != "" ? "'" . $fecha_maxima_justificar . "'" : "null";
		$sql = "Insert into
					g_vacaciones.permiso_empleado(
					sub_tipo,
					fecha_inicio,
					fecha_fin,
					identificador,
					minutos_utilizados,
					fecha_maxima_presentar_justificacion,
					estado,
					ruta_archivo,
					periodos_tomados,
					fecha_suceso,
					fecha_solicitud,
					id_area_permiso,
					destino_comision,
					piv_tipo
				)
				values(
					'" . $subTipoSolicitud . "',
					" . $fechaSalida . ",
					" . $fechaRetorno . ",
					'" . $identificador . "',
					'" . $minutos_utilizados . "',
					" . $fecha_maxima_justificar . ",
					'Aprobado',
					'" . $rutaArchivo . "',
					'" . $periodosTomados . "',
					" . $fechaSuceso . ",
					'now()',
					'" . $idAreaPermiso . "',
					'" . $destinoComision . "',
					'" . $pivTipo . "'
					)
				RETURNING
				id_permiso_empleado;";
		$res = $conexion->ejecutarConsultaLOGS($sql);
		return $res;
	}

	public function devolverFormatoDiasDisponibles($minutosutilizados)
	{
		if ($minutosutilizados >= 1) $valor = '';
		else $valor = '- ';
		$minutosutilizados = abs($minutosutilizados);
		$diasDescontados = '';
		$separador = '';
		$dias = floor(intval($minutosutilizados) / 480);
		if ($dias != 0) {
			if ($dias >= 2)
				$diasDescontados .= $valor . $dias . ' días';
			else
				$diasDescontados .= $valor . $dias . ' día';
			$separador = ' ';
			$valor = '';
		}
		$horas = floor((intval($minutosutilizados) - $dias * 480) / 60);
		if ($horas != 0) {
			$valor = '';
			if ($horas >= 2)
				$diasDescontados .= $separador . $valor . $horas . ' horas';
			else
				$diasDescontados .= $separador . $valor . $horas . ' hora';
			$separador = ' ';
		}
		$minutos = (intval($minutosutilizados) - $dias * 480) - $horas * 60;
		if ($minutos != 0) {
			if ($minutos >= 2)
				$diasDescontados .= $separador . $valor . $minutos . ' minutos';
			else
				$diasDescontados .= $separador . $valor . $minutos . ' minuto';
		}
		return $diasDescontados;
	}
	public function obtenerTiempoPermisoSolicitado($conexion, $id_permiso)
	{

		$sqlScript = "select
					*
				from
						g_vacaciones.permiso_empleado pe
				where
						pe.id_permiso_empleado='" . $id_permiso . "';";

		$res = $conexion->ejecutarConsulta($sqlScript);
		return $res;
	}

	public function obtenerFuncionariosDescuento($conexion, $fechainicio, $fechafin, $idexcel)
	{

		$sql = " SELECT 
				pe.minutos_utilizados,
				Upper(fe.apellido ||' '||fe.nombre) as nombre_completo,
				mes_descuento,
				anio_descuento 
			FROM 
				g_vacaciones.permiso_empleado pe, 
				g_uath.ficha_empleado fe, 
				g_catalogos.subtipo_permiso subpe,
				g_vacaciones.excel_descuentos ex 
			WHERE 
				fe.identificador = pe.identificador and 
				subpe.id_subtipo_permiso = pe.sub_tipo and 
				subpe.codigo='PE-DA' AND
				pe.estado in ('Aprobado','InformeGenerado') and 
				ex.id_excel_descuento='$idexcel' and
				((fecha_inicio between '$fechainicio' and '$fechafin') and
				(fecha_fin between '$fechainicio' and '$fechafin')) order by 2";

		$res = $conexion->ejecutarConsulta($sql);
		return $res;
	}

	//---------------------------------------------------------------------------------------------------------

	public function actualizarEncargado($conexion, $id_registro, $identificador_responsable, $identificador_encargado, $id_area, $id_area_encargado, $nombrePuesto, $nombrePuestoEncargado, $fechaInicio, $fechaFin, $designacion, $archivoSub)
	{

		$sql = "UPDATE 
					g_subrogacion.responsables_puestos
   				SET
   					identificador_responsable='" . $identificador_responsable . "', 
   					identificador_encargado='" . $identificador_encargado . "', 
       				id_area='" . $id_area . "',
       				id_area_encargado='" . $id_area_encargado . "', 
       				nombre_puesto='" . $nombrePuesto . "',
       				nombre_puesto_encargado='" . $nombrePuestoEncargado . "', 
       				fecha_ini='" . $fechaInicio . "', 
       				fecha_fin='" . $fechaFin . "',
       				designacion='" . $designacion . "', 
       				ruta_subrogacion='" . $archivoSub . "'
 				WHERE
					id_permiso_empleado=" . $id_registro . ";";

		$res = $conexion->ejecutarConsultaLOGS($sql);
	}

	//---------------------------------------------------------------------------------------------------------

	public function nuevoEncargado(
		$conexion,
		$identificador_responsable,
		$identificador_encargado,
		$id_area,
		$id_area_encargado,
		$nombrePuesto,
		$nombrePuestoEncargado,
		$fechaInicio,
		$fechaFin,
		$idPermiso,
		$designacion,
		$archivoSub
	) {

		$sql = " INSERT INTO g_subrogacion.responsables_puestos(
		            identificador_responsable, 
		            identificador_encargado, 
		            id_area, 
		            id_area_encargado,
		            nombre_puesto,
		            nombre_puesto_encargado, 
		            fecha_ini, 
		            fecha_fin, 
		            estado, 
		            id_permiso_empleado,
		            designacion,
		            fecha_creacion,
					ruta_subrogacion)
    		VALUES
					(
					'" . $identificador_responsable . "',
					'" . $identificador_encargado . "',
					'" . $id_area . "',
					'" . $id_area_encargado . "',
					'" . $nombrePuesto . "',
					'" . $nombrePuestoEncargado . "',
					'" . $fechaInicio . "',
					'" . $fechaFin . "',
					'creado',
					" . $idPermiso . ",
					'" . $designacion . "',
					'now()',
					'" . $archivoSub . "'		
					);";
		$res = $conexion->ejecutarConsultaLOGS($sql);
		return $res;
	}
	//---------------------------------------------------------------------------------------------------------

	public function obtenerEncargadoPuestoArea($conexion, $identificador_responsable, $identificador_encargado, $id_area, $estado, $id_registro, $nombre_puesto)
	{

		$identificador_responsable = $identificador_responsable != "" ? "'" .  $identificador_responsable  . "'" : "NULL";
		$identificador_encargado = $identificador_encargado != "" ? "'" .  $identificador_encargado  . "'" : "NULL";
		$id_area = $id_area != "" ? "'" .  $id_area  . "'" : "NULL";
		$estado = $estado != "" ? "'" .  $estado  . "'" : "NULL";
		$id_registro = $id_registro != "" ? "'" .  $id_registro  . "'" : "NULL";
		$nombre_puesto = $nombre_puesto != "" ? "'" .  $nombre_puesto  . "'" : "NULL";

		//($mes is NULL or  mes_descuento = $mes) and
		$sql = " SELECT *
  		   FROM 
				g_subrogacion.responsables_puestos 
		   where 
		   		($identificador_responsable is NULL or  identificador_responsable = $identificador_responsable) and
		   		($identificador_encargado is NULL or  identificador_encargado = $identificador_encargado) and
		   		($id_area is NULL or  id_area = $id_area) and
		   		($estado is NULL or  estado = $estado) and
		   		($id_registro is NULL or  id_permiso_empleado = $id_registro) and
		   		($nombre_puesto is NULL or  nombre_puesto = $nombre_puesto);";

		$res = $conexion->ejecutarConsulta($sql);
		return $res;
	}
	//---------------------------------------------------------------------------------------------------------
	public function actualizarEstadoResponsablePuesto($conexion, $id_solicitud_permiso, $estado_solicitud)
	{
		$sqlScript = "Update
					g_subrogacion.responsables_puestos
				set
					estado='" . $estado_solicitud . "'
				where
					id_permiso_empleado='" . $id_solicitud_permiso . "';";

		$res = $conexion->ejecutarConsulta($sqlScript);
		return $res;
	}

	//--------------------------------------------------------------------------------------------------------
	public function consultarPermisosAprobados($conexion)
	{

		$sql = "SELECT 
				pv.id_permiso_empleado, 
				pv.identificador,
				pv.id_area_permiso,
				rh.identificador as identificadorrhh
			FROM 
				g_vacaciones.permiso_empleado pv,
				g_uath.encargo_recursos_humanos rh
				
			where 
				pv.sub_tipo in (25,18,29,14,17) and 
				pv.estado = 'Aprobado' and 
				id_area_permiso <> '' and 
				rh.zona_area = id_area_permiso and 
				rh.estado = 'activo' 
			order by 3 desc;";

		$res = $conexion->ejecutarConsulta($sql);
		return $res;
	}
	//--------------------------------------------------------------------------------------------------------
	// ----------------------------------EXISTEN PERMISOS---------------------------
	public function obtenerPermisosCreados($conexion,$idAreaJefe){
			$sql=	"
			SELECT 
			(CASE WHEN  COUNT(pe.identificador) > 0 THEN 1 ELSE 0 END)  as existe  
			FROM g_vacaciones.permiso_empleado pe
			WHERE pe.estado='creado' and pe.id_area_jefe =
			'" . $idAreaJefe . "'";
			$res = $conexion->ejecutarConsulta($sql);
			return $res;
	}

	public function filtroObtenerReporteHistoricoCronogramavacacion($conexion, $anio, $identificador, $nombre)
	{

		$identificador = $identificador != "" ? "'" . $identificador . "'" : "NULL";
		$nombre = $nombre != "" ? "'%" . $nombre . "%'" : "NULL";

		$consulta =	"SELECT 
					tcv.id_cronograma_vacacion
					, tfe.identificador
					, tfe.nombres_completos
					, tfe.provincia
					, tfe.canton
					, tfe.oficina
					, tfe.nombre_area_padre as nombre_unidad_administrativa
					, tfe.nombre_area as nombre_gestion_administrativa
					, tfe.nombre_puesto as puesto_institucional
					, COALESCE (tcv.anio_cronograma_vacacion::text, 'N/A') anio_cronograma_vacacion
					, CASE
							WHEN tcv.estado_cronograma_vacacion='RevisionJefe' THEN 
							'Revisión jefe'
							WHEN tcv.estado_cronograma_vacacion='EnviadoTthh' THEN 
							'Enviado talento humano'
							WHEN tcv.estado_cronograma_vacacion='EnviadoDe' THEN 
							'Enviado director ejecutivo'
							WHEN tcv.estado_cronograma_vacacion='Rechazado' THEN 
							'Rechazado'
							WHEN tcv.estado_cronograma_vacacion='RechazadoDe' THEN 
							'Rechazado director ejecutivo'
							WHEN tcv.estado_cronograma_vacacion='Aprobado' THEN 
							'Aprobado'
							ELSE 'No planificado'
							END AS estado
				FROM
					(SELECT 
						fe.identificador
						, CONCAT(fe.apellido,' ',fe.nombre) as nombres_completos
						, dc.nombre_puesto
						, dc.provincia
						, dc.canton
						, dc.oficina
						, a.nombre as nombre_area
						, ap.nombre as nombre_area_padre
					FROM 
						g_uath.datos_contrato dc 
					INNER JOIN g_uath.ficha_empleado fe ON fe.identificador = dc.identificador
					INNER JOIN g_estructura.area a ON a.id_area = dc.id_gestion
					INNER JOIN g_estructura.area ap ON ap.id_area = a.id_area_padre
					WHERE 
						dc.estado = 1 
						and dc.tipo_contrato <> 'Contrato de Servicios Profesionales') tfe
				LEFT JOIN 
					(SELECT
						cv.id_cronograma_vacacion
						, cv.identificador_funcionario
						, cv.nombre_funcionario
						, cv.nombre_puesto
						, cv.estado_cronograma_vacacion
						, cv.anio_cronograma_vacacion 
					FROM 
						g_vacaciones.cronograma_vacaciones cv
					INNER JOIN g_vacaciones.periodo_cronograma_vacaciones pcv ON pcv.id_cronograma_vacacion = cv.id_cronograma_vacacion
					WHERE
						cv.anio_cronograma_vacacion = " . $anio . "
					) tcv ON tcv.identificador_funcionario = tfe.identificador
				WHERE 
					($identificador is NULL or tfe.identificador = " . $identificador. ")
					and ($nombre is NULL or tfe.nombres_completos ilike " . $nombre. ")
				ORDER BY tfe.provincia, tfe.canton, tfe.oficina, tfe.nombre_area_padre ASC";

			$res = $conexion->ejecutarConsulta($consulta);
			return $res;
	}


}
