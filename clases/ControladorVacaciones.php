<?php
require_once 'ControladorComplementos.php';
class ControladorVacaciones{
	

	public function obtenerTipoPermiso($conexion,$codigo=null){
		$busqueda = "";
		if ($codigo != null){
			$busqueda = "where codigo in ('" . $codigo . "')";
		}
		 $sqlScript = "select 
							* 
					  from 
							g_catalogos.tipo_permiso
							". $busqueda ." ;";
		$res = $conexion->ejecutarConsulta($sqlScript);
		return $res;
	}

	public function obtenerSubTipoPermiso($conexion, $tipoAcceso = null, $subtipo = null){
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

	public function nuevoPermiso($conexion, $subTipoSolicitud, $fechaSalida, $fechaRetorno, $horaSalida, $horaRetorno, $identificador, $minutos_utilizados, $fecha_maxima_justificar, $rutaArchivo, $periodosTomados, $fechaSuceso, $idAreaPermiso, $destinoComision, $pivTipo){
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

	public function identificadorJefeSuperior($conexion, $identificadorRevisor, $idRequerimiento, $areaJefe){
		$sqlScript = "UPDATE
							g_vacaciones.permiso_empleado
						SET
							identificador_jefe_superior = '$identificadorRevisor',
							id_area_jefe = '$areaJefe'
						WHERE
							id_permiso_empleado = $idRequerimiento";
		$res = $conexion->ejecutarConsultaLOGS($sqlScript);

		return $res;
	}

	public function actualizarRevisorRequerimiento($conexion, $idArea, $identificadorRevisor, $idRequerimiento, $tipo){
		if ($tipo == 'identificadorDistritoA'){

			$sqlScript = "UPDATE
							g_vacaciones.permiso_empleado
						SET
							identificador_distrital_a = '$identificadorRevisor',
							id_area_distrital_a = '$idArea'
						WHERE
							id_permiso_empleado = $idRequerimiento";
		}else{

			$sqlScript = "UPDATE
							g_vacaciones.permiso_empleado
						SET
							identificador_distrital_b = '$identificadorRevisor',
							id_area_distrital_b = '$idArea'
						WHERE
							id_permiso_empleado = $idRequerimiento";
		}

		$res = $conexion->ejecutarConsultaLOGS($sqlScript);

		return $res;
	}

	public function listarPermisosSolicitados($conexion, $identificador){
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

	public function obtenerDiaIngresoEmpleado($conexion, $dia){
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
						and EXTRACT(DAY FROM fecha_inicio) = $dia;"; // TODO: ordenar por fecha de contrato

		$res = $conexion->ejecutarConsulta($sqlScript);

		return $res;
	}

	public function obtenerPermisoSolicitado($conexion, $id_permiso){
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

	public function obtenerDatosPermiso($conexion, $id_permiso){
		
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

	//***************************************obtener datos permiso reintegro************************************
	
	public function obtenerDatosPermisoReintegro($conexion, $id_permiso,$codigoSubtipo){
		
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
							                    sp.id_tipo_permiso=tp.id_permiso and
							                    pe.identificador=fe.identificador and
							                    pe.identificador=dc.identificador and
							                    dc.estado=1 and
												sp.codigo = '".$codigoSubtipo."' and
												pe.id_permiso_empleado='" . $id_permiso . "';");
		
		return $res;
	}
	//************************************************************************************************************
	public function obtenerNombreDirector($conexion, $identificadorTH){
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

	public function obtenerSolicitudes($conexion, $tipo_permiso, $fecha_desde, $fecha_hasta, $id_solicitud, $identificador, $id_director, $estado){
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
	//*****************************obtener acciones para firma manual****************************************
	public function obtenerPermisosRevisionManual($conexion, $tipo_permiso, $fecha_desde, $fecha_hasta, $id_solicitud, $identificador, $id_director, $idArea){
		$tipo_permiso = $tipo_permiso != "" ? "'" . $tipo_permiso . "'" : "null";
		$fecha_desde = $fecha_desde != "" ? "'" . $fecha_desde . "'" : "null";
		$fecha_hasta = $fecha_hasta != "" ? "'" . $fecha_hasta . "'" : "null";
		$identificador = $identificador != "" ? "'" . $identificador . "'" : "null";
		$idArea = "'" . $idArea . "'";
				
		$sqlScript = "Select
						*
					from
						g_vacaciones.mostrar_solicitudes_revision_proceso_manual(" . $tipo_permiso . "," . $fecha_desde . "," . $fecha_hasta . "," . $idArea . "," . $identificador . ") order by 1 desc;";
		
		
		$res = $conexion->ejecutarConsulta($sqlScript);
		return $res;
	}
	//********************************************************************************************************

	public function obtenerPermisosRevisionProceso($conexion, $tipo_permiso, $fecha_desde, $fecha_hasta, $id_solicitud, $identificador, $id_director, $estado, $idArea){
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

	public function obtenerPermisosRevisionProcesoLimit($conexion, $tipo_permiso, $fecha_desde, $fecha_hasta, $id_solicitud, $identificador, $id_director, $estado, $idArea, $limit, $offset, $contador){
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

	// ------------------------------------------------------------------------------------------------------------
	public function devolverObservacionPermiso($conexion, $idPermiso){
		$sqlScript = "select
						observacion
					from
						g_vacaciones.permiso_empleado
					where
						id_permiso_empleado=$idPermiso";
		$res = $conexion->ejecutarConsulta($sqlScript);
		return $res;
	}

	// ------------------------------------------------------------------------------------------------------------
	public function consultarRegistrosEstadoMinutos($conexion, $estado_minutos){
		$sqlScript = "select 
						id_permiso_empleado, minutos_utilizados, identificador 
					from 
						g_vacaciones.permiso_empleado
					where
						estado_minutos='1'";
		$res = $conexion->ejecutarConsulta($sqlScript);
		return $res;
	}

	public function actualizarEstadoPermiso($conexion, $id_solicitud_permiso, $estado_solicitud){
		$sqlScript = "Update
						 	g_vacaciones.permiso_empleado 
				    set
							estado='" . $estado_solicitud . "'			
					where
							id_permiso_empleado='" . $id_solicitud_permiso . "';";

		$res = $conexion->ejecutarConsultaLOGS($sqlScript);
		return $res;
	}
	
	//*************************actualizar estado reintegro***************************************
	
	public function actualizarEstadoPermisoReintegro($conexion, $id_solicitud_permiso, $reintegro){
		$sqlScript = "Update
						 	g_vacaciones.permiso_empleado
				    set
							reintegro='" . $reintegro . "'
					where
							id_permiso_empleado='" . $id_solicitud_permiso . "';";
		
		$res = $conexion->ejecutarConsultaLOGS($sqlScript);
		return $res;
	}
	//*********************************************************************************************
	public function actualizarRutaDocumento($conexion, $id_solicitud_permiso, $ruta){
		$sqlScript = "Update
						 	g_vacaciones.permiso_empleado 
				    set
							ruta_informe='" . $ruta . "'			
					where
							id_permiso_empleado='" . $id_solicitud_permiso . "';";

		$res = $conexion->ejecutarConsultaLOGS($sqlScript);
		return $res;
	}
	
	//*****************************actualizar ruta de reintegro******************************************************
	public function actualizarRutaDocumentoReintegro($conexion, $id_solicitud_permiso, $ruta,$idSubtipoReintegro){
		$sqlScript = "Update
						 	g_vacaciones.permiso_empleado
				    set
							ruta_archivo_reintegro='" . $ruta . "',
							subtipo_permiso_reintegro = ".$idSubtipoReintegro."
					where
							id_permiso_empleado='" . $id_solicitud_permiso . "';";
		
		$res = $conexion->ejecutarConsultaLOGS($sqlScript);
		return $res;
	}
	//***************************************************************************************************************

	public function actualizarMinutosActuales($conexion, $id_solicitud_permiso, $minutos){
		$sqlScript = "Update
							g_vacaciones.permiso_empleado
					set
							minutos_actuales='$minutos'
					where
							id_permiso_empleado='" . $id_solicitud_permiso . "';";

		$res = $conexion->ejecutarConsultaLOGS($sqlScript);
		return $res;
	}

	public function cambiarEstadoXMinutos($conexion, $estado_minutos){
		$sqlScript = "Update
						 	g_vacaciones.permiso_empleado 
				    set
							estado_minutos='" . $estado_minutos . "'			
					where
							fecha_maxima_presentar_justificacion < current_date and estado='Aprobado';";
		$res = $conexion->ejecutarConsultaLOGS($sqlScript);
		return $res;
	}

	public function incrementarSaldosFuncionario($conexion, $usuario, $minutosGenerados, $anio, $observacion = Null){
		$sqlScript2 = "update
      g_vacaciones.minutos_disponibles_funcionarios
      set
      minutos_disponibles = minutos_disponibles+" . $minutosGenerados . ",
      observacion = '" . $observacion . "'
      where
      activo=true and
      anio = $anio and
     identificador='" . $usuario . "';";

		$conexion->ejecutarConsultaLOGS($sqlScript2);
	}

	public function incrementarSaldosFuncionarioNuevoAnio($conexion, $usuario, $minutosGenerados, $anio, $secuencial = 1, $observacion = null){
		$sqlScript2 = "INSERT INTO g_vacaciones.minutos_disponibles_funcionarios
     VALUES ('$usuario',$anio, $minutosGenerados, TRUE, $secuencial, '$observacion');";

		$conexion->ejecutarConsultaLOGS($sqlScript2);
	}

	// ***************incrementar saldo funcionarios en nueva tabla de tiempo**************************************************************
	public function incrementarSaldosFuncionarioNuevaTabla($conexion, $usuario, $minutosGenerados, $anio, $mes, $secuencial = 1, $observacion = null, $fechaInicialContrato){
		$sqlScript2 = "INSERT INTO g_vacaciones.tiempo_disponible_funcionarios(
                                   identificador, anio, mes, minutos_disponibles,observacion, secuencial,fecha_inicial_contrato)
                       VALUES ('$usuario',$anio, '$mes' ,$minutosGenerados, '$observacion',$secuencial,'$fechaInicialContrato');";

		$conexion->ejecutarConsultaLOGS($sqlScript2);
	}

	// ***************verificar información si tiene registro para determinar el secuencial
	public function verificarInforTiempoDisponible($conexion, $identificador, $anio, $mes){
		$sql = "SELECT 
                       case when activo then 1
	                       else
	                        0
	                       end::integer as activo,
						   secuencial+1 as secuencial
				  FROM 
						g_vacaciones.tiempo_disponible_funcionarios 
				  WHERE 
					  anio = " . $anio . " and 
					  mes = '" . $mes . "' and 
					  identificador='" . $identificador . "';";

		$consulta = $conexion->ejecutarConsulta($sql);

		return $consulta;
	}

	// *************************************************************************************************************************************
	public function crearObservacionVacacion($conexion, $id_permiso, $descripcion, $identificador){
		$sqlScript = "Insert into g_vacaciones.observaciones
				    (descripcion,codigo_permiso_empleado,identificador)
				 	values('" . $descripcion . "'," . $id_permiso . ",'" . $identificador . "');";

		$res = $conexion->ejecutarConsultaLOGS($sqlScript);

		return $res;
	}

	// ******************************************descontar saldo mayor a 30 dias**************************************************************
	public function verificarSaldosMayores60Xfuncionarios($conexion){
		$consultar = $this->obtenerSaldosMayoresA60($conexion);
		$contador = 0;
		while ($fila = pg_fetch_assoc($consultar)){
			$saldo1 = pg_fetch_result($this->consultarSaldoFuncionario($conexion, $fila['identificador']), 0, 'minutos_disponibles');
			
			echo '<br/> '.++$contador.'>>> Minutos adicionales en usuario ' . $fila['identificador'] . ' eliminados ' . $fila['diferencia'];
			$observacion = 'Minutos adicionales eliminados ' . $fila['diferencia'] . ' proceso automatico';
			
			if ($fila['diferencia'] <= $saldo1){
				$this->descontarSaldoXAnios($conexion, $fila['identificador'], $fila['diferencia'], 0, $observacion,false);
			}else{
				$minutosADescontar = $fila['diferencia'] - $saldo1;
				if ($saldo1 != 0){
					$this->descontarSaldoXAnios($conexion, $fila['identificador'], $saldo1, 0, $observacion);
				}
				$this->descontarSaldoXMeses($conexion, $fila['identificador'], $minutosADescontar, 0, $observacion,false);
			}
		}
	}

	// ******************************************obtener saldos mayor a 30 dias**************************************************************
	public function obtenerSaldosMayoresA60($conexion){
		$sql = "
					SELECT 
					   sum(minutos) as minutos_totales,identificador, sum(minutos) - 28800 as diferencia 
					FROM(
					SELECT
							identificador,
							sum(minutos_disponibles) as minutos
						FROM 
								g_vacaciones.minutos_disponibles_funcionarios							
						WHERE
								activo=true 
						GROUP BY identificador
										
					UNION
					
					SELECT
						identificador,
						sum(minutos_disponibles) as minutos
						FROM 
								g_vacaciones.tiempo_disponible_funcionarios							
						WHERE
								activo=true 
						GROUP BY identificador
						
					
						)as result GROUP BY 2 HAVING sum(minutos)>28800;";

		$result = $conexion->ejecutarConsulta($sql);
		return $result;
	}

	// ***************************************************************************************************************************************

	// ***************************************************************************************************************************************
	public function verificarSaldosMayores60($conexion){
		$sqlScript = "select
        					identificador,sum(minutos_disponibles), sum(minutos_disponibles)-28800 as diferencia
					from 
							g_vacaciones.minutos_disponibles_funcionarios							
					where
							activo=true 
					group by identificador
					having sum(minutos_disponibles)>28800";

		$resMayores = $conexion->ejecutarConsulta($sqlScript);

		while ($fila = pg_fetch_assoc($resMayores)){

			echo '<br/> >>> Usuario ' . $fila['identificador'] . 'con minutos adicionales ' . $fila['diferencia'];

			$sqlRegistro = "select *
						  from g_vacaciones.minutos_disponibles_funcionarios
						  where activo=true and identificador='" . $fila['identificador'] . "' order by anio";

			$resConsulta = $conexion->ejecutarConsulta($sqlRegistro);

			$minutosRestantes = 0;
			while ($filaFuncionario = pg_fetch_assoc($resConsulta)){

				if ($filaFuncionario['minutos_disponibles'] <= $fila['diferencia']){

					$mensaje = 'Desactivación de año ' . $filaFuncionario['anio'] . ', 0 días disponibles proceso automatico';

					$minutosRestantes = $fila['diferencia'] - $filaFuncionario['minutos_disponibles'];

					$sqlScript2 = "UPDATE
									g_vacaciones.minutos_disponibles_funcionarios
								SET
									minutos_disponibles = 0,
									activo = FALSE,
                                    observacion='" . $mensaje . "'
								WHERE
									identificador='" . $fila['identificador'] . "'
									and anio=" . $filaFuncionario['anio'] . ";";

					$conexion->ejecutarConsulta($sqlScript2);

					$fila['diferencia'] = $minutosRestantes;

					echo '<br/> >>>' . $mensaje;
				}else{
					$mensaje = 'Minutos adicionales eliminados ' . $fila['diferencia'] . ' proceso automatico';
					$sqlScript2 = "UPDATE 
									g_vacaciones.minutos_disponibles_funcionarios
								SET
									minutos_disponibles = minutos_disponibles-" . $fila['diferencia'] . ",
                                    observacion = '" . $mensaje . "'
								WHERE
									identificador='" . $fila['identificador'] . "' 
									and anio=" . $filaFuncionario['anio'] . "
									and activo=true;";

					$conexion->ejecutarConsulta($sqlScript2);

					echo '<br/> >>>' . $mensaje;

					break;
				}
			}
		}
	}

	public function actualizarSaldosFuncionario($conexion, $usuario, $minutosConsumidos, $idPermisoEmpleado){
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

		while ($fila = pg_fetch_assoc($resVacaciones)){

			if ($minutosPendientes > 0){
				if (abs(intval($fila['minutos_disponibles'])) <= $minutosPendientes){
					$minutosPendientes = $minutosPendientes - $fila['minutos_disponibles'];
					$minutosADescontar = $fila['minutos_disponibles'];
					$estado = 'false';
				}else{
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

				$res1 = $conexion->ejecutarConsultaLOGS("	INSERT INTO 
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

		return $res;
	}

	public function consultarSaldoFuncionario($conexion, $usuario, $activo='TRUE'){
		$res = $conexion->ejecutarConsulta("select
											sum(minutos_disponibles) as minutos_disponibles,
											string_agg(distinct anio::character varying,', ') as anio
										  from
											g_vacaciones.minutos_disponibles_funcionarios
										  where
											activo=".$activo."
											and identificador='" . $usuario . "'
											group by identificador");

		return $res;
	}

	// **********************consultar saldo funcionarios nueva tabla******************************
	public function consultarSaldoFuncionarioNuevo($conexion, $usuario,$activo='TRUE'){
		$res = $conexion->ejecutarConsulta("select
											sum(minutos_disponibles) as minutos_disponibles,
											string_agg(distinct anio::character varying,', ') as anio
										  from
											g_vacaciones.tiempo_disponible_funcionarios
										  where
											activo=".$activo."
											and identificador='" . $usuario . "'
											group by identificador ;");

		return $res;
	}

	public function actualizarPermiso($conexion, $id_registro, $subTipoSolicitud, $fechaSalida, $fechaRetorno, $identificador, $minutos_utilizados, $fecha_maxima_justificar, $rutaArchivo, $fechaSuceso, $idAreaPermiso, $destinoComision, $pivTipo){
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

	public function agregarObservacion($conexion, $descripcion, $idPermiso, $identificador){
		$res = $conexion->ejecutarConsultaLOGS("INSERT INTO 
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

	// --------------------------------------------------------------------------------------------------
	public function agregarObservacionPermiso($conexion, $observacion, $idPermiso){
		$res = $conexion->ejecutarConsulta("
				 UPDATE 
						g_vacaciones.permiso_empleado
   				 SET 
						observacion='$observacion'
				 WHERE 
						id_permiso_empleado=$idPermiso;");

		return $res;
	}

	// --------------------------------------------------------------------------------------------------
	public function buscarPermisosXSubtipo($conexion, $identificador, $idSubtipo, $estado){
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

	public function obtenerSubTipoPermisoXCodigo($conexion, $codigo){
		$res = $conexion->ejecutarConsulta("select 
												* 
											from 
												g_catalogos.subtipo_permiso 
											where 
												codigo in ($codigo);");

		return $res;
	}

	public function buscarPermisosRangoFecha($conexion, $fechaInicio, $fechaFin, $identificador, $idPermiso = 0){
		if ($idPermiso != 0){
			$mensaje = "pe.id_permiso_empleado not in ($idPermiso) and";
		}else{
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

	public function obtenerTiempoDisponibleFuncionario($conexion, $identificador){
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

	public function generarNumeroAccionPersonal($conexion, $idArea){
		$res = $conexion->ejecutarConsulta("SELECT
												MAX(numero_accion_personal)::integer +1 as numero
											FROM
												g_vacaciones.permiso_empleado
											WHERE
												id_area_permiso = '$idArea';");

		return $res;
	}

	public function actualizarNumeroAccionPersonal($conexion, $idSolicitudPermiso, $numeroSolicitud){
		$res = $conexion->ejecutarConsulta("Update
												g_vacaciones.permiso_empleado
											set
												numero_accion_personal='" . $numeroSolicitud . "'
											where
												id_permiso_empleado='" . $idSolicitudPermiso . "';");

		return $res;
	}

	public function obtenerAnioMayor($conexion, $identificador){
		$res = $conexion->ejecutarConsulta("SELECT
												max(anio) as anio
											FROM
												g_vacaciones.minutos_disponibles_funcionarios
											WHERE
												activo=true and
												identificador='$identificador';");

		return $res;
	}

	public function obtenerSecuencialanio($conexion, $identificador, $anio){
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

	public function obtenerContratosActivos($conexion){
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

	public function actualizarEstadoContrato($conexion, $identificador){
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

	public function actualizarCertificadoPermiso($conexion, $id_registro, $rutaArchivo){
		$res = $conexion->ejecutarConsulta("update
												g_vacaciones.permiso_empleado
											set
												ruta_archivo='" . $rutaArchivo . "'
											where
												id_permiso_empleado=" . $id_registro . ";");

		return $res;
	}

	public function filtroObtenerReporteSaldoUsuario($conexion, $identificador, $estado, $apellido, $nombre, $area, $tipoReporte){
		$busqueda = '';
		$parametros = '';
		$agrupacion = '';
		$orden = '';

		if ($tipoReporte != 'unico'){
			$parametros = "mdf.*, fe.nombre, fe.apellido";
			$orden = "ORDER BY 1,2";
		}else{
			$parametros = "sum(minutos_disponibles) as minutos_disponibles, fe.nombre, fe.apellido, mdf.identificador,string_agg(distinct mdf.anio::character varying,', ') as anio";
			$agrupacion = "GROUP BY fe.nombre, fe.apellido, mdf.identificador";
		}

		if ($identificador != ''){
			$busqueda = "and mdf.identificador IN ('$identificador')";
		}

		if ($apellido != ''){
			$busqueda .= " and mdf.identificador IN (SELECT identificador FROM g_uath.ficha_empleado WHERE upper(apellido) like upper('%$apellido%'))";
		}

		if ($nombre != ''){
			$busqueda .= " and mdf.identificador IN (SELECT identificador FROM g_uath.ficha_empleado WHERE upper(nombre) like upper('%$nombre%'))";
		}

		if ($area != ''){

			if ($area == 'DE'){

				$areaSubproceso = "'" . $area . "',";
			}else{
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

				while ($fila = pg_fetch_assoc($areaProceso)){
					$areaSubproceso .= "'" . $fila['id_area'] . "',";
				}
			}

			$areaSubproceso = "(" . rtrim($areaSubproceso, ',') . ")";

			$busqueda .= ' and mdf.identificador IN (SELECT identificador FROM g_estructura.funcionarios WHERE id_area IN ' . $areaSubproceso . ')';
		}

		if ($estado != ''){
			$estadosql = "and mdf.activo = '$estado'";
		}else{
			$estadosql = '';
		}
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

	public function filtroObtenerReporteHistoricoUsuario($conexion, $identificador, $apellido, $nombre, $fechaInicio, $fechaFin, $tipoPermiso, $subtipoPermiso, $estadoVacacion, $area, $opt='si'){
		$busqueda = '';

		if ($identificador != ''){
			$busqueda = "and pe.identificador IN ('$identificador')";
		}

		if ($apellido != ''){
			$busqueda .= " and pe.identificador IN (SELECT identificador FROM g_uath.ficha_empleado WHERE upper(apellido) like upper('%$apellido%'))";
		}

		if ($nombre != ''){
			$busqueda .= " and pe.identificador IN (SELECT identificador FROM g_uath.ficha_empleado WHERE upper(nombre) like upper('%$nombre%'))";
		}

		if ($fechaInicio != ''){
			$fechaInicio = $fechaInicio . ' 00:00:00';

			$busqueda .= " and pe.fecha_inicio > '$fechaInicio' ";
		}

		if ($fechaFin != ''){
			$fechaFin = $fechaFin . ' 24:00:00';

			$busqueda .= " and pe.fecha_inicio < '$fechaFin' ";
		}

		if ($tipoPermiso != ''){
			$busqueda .= " and tp.id_permiso = $tipoPermiso";
		}
	
		if($opt == 'si'){
			if ($subtipoPermiso != ''){
				$busqueda .= " and pe.sub_tipo = $subtipoPermiso";
			}
		}else{
			if ($subtipoPermiso != ''){
				$busqueda .= " and pe.subtipo_permiso_reintegro = $subtipoPermiso";
			}
		}

		if ($estadoVacacion != ''){
			$busqueda .= " and pe.estado = '$estadoVacacion'";
		}

		if ($area != ''){

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

			while ($fila = pg_fetch_assoc($areaProceso)){
				$areaSubproceso .= "'" . $fila['id_area'] . "',";
			}

			$areaSubproceso = "(" . rtrim($areaSubproceso, ',') . ")";

			$busqueda .= ' and pe.identificador IN (SELECT identificador FROM g_estructura.funcionarios WHERE id_area IN ' . $areaSubproceso . ')';
		}
		if($opt == 'si'){
			$buscar = "pe.sub_tipo = sp.id_subtipo_permiso and";
		}else{
			$buscar = "pe.subtipo_permiso_reintegro = sp.id_subtipo_permiso and";
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
												subtipo_permiso_reintegro,
												sp.codigo
											FROM
													g_vacaciones.permiso_empleado pe,
													g_catalogos.subtipo_permiso sp,
													g_catalogos.tipo_permiso tp,
													g_uath.ficha_empleado fe
											WHERE
												" .$buscar. "
												sp.id_tipo_permiso = tp.id_permiso and
												pe.identificador = fe.identificador 
												" . $busqueda . " order by id_permiso_empleado desc");

		return $res;
	}

	public function DiasHabiles($fecha_inicial, $fecha_final){
		list ($year, $mes, $dia) = explode("-", $fecha_inicial);
		$ini = mktime(0, 0, 0, $mes, $dia, $year);
		list ($yearf, $mesf, $diaf) = explode("-", $fecha_final);
		$fin = mktime(0, 0, 0, $mesf, $diaf, $yearf);

		$r = 0;
		while ($ini != $fin){
			$date = date('N', mktime(0, 0, 0, $mes, $dia + $r, $year));

			if ($date != 6 && $date != 7){
				$newArray[] .= $date;
			}

			$ini = mktime(0, 0, 0, $mes, $dia + $r, $year);
			$r ++;
		}
		return $newArray;
	}

	// ----------------------------------------------------------------------------------------------------------------------------------------
	public function devolverNumDias($fecha_inicial, $fecha_final){
		list ($year, $mes, $dia) = explode("-", $fecha_inicial);
		$ini = mktime(0, 0, 0, $mes, $dia, $year);
		list ($yearf, $mesf, $diaf) = explode("-", $fecha_final);
		$fin = mktime(0, 0, 0, $mesf, $diaf, $yearf);

		$r = 0;
		while ($ini != $fin){
			$date = date('N', mktime(0, 0, 0, $mes, $dia + $r, $year));
			$newArray[] .= $date;
			$ini = mktime(0, 0, 0, $mes, $dia + $r, $year);
			$r ++;
		}
		return $newArray;
	}

	// ----------------------------------------------------------------------------------------------------------------------------------------
	public function devolverMinutSaldRetor($horaS, $horaR, $diasHabiles){
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
		// -------------------------------CALCULAR TIEMPO DE SALIDA---------------------------------------------------
		if ($hora1S > $hora1Sxx){
			if ($horaSalida == 8){
				$minutosd1 = 420 + $minutosSalidaRest;
			}else{
				$ban = 1;
				$horaRest = abs(16 - $horaSalida);
				$minutosSalida .= $minutosSalida + 30;

				if ($horaRest > 8){
					$ban = 0;
					$horaRest = 8;
					$minutosd1 = abs(($horaRest * 60) - $minutosSalida);
				}
				if ($horaRest < 8){

					$minutosd1 = abs(($horaRest * 60) + $minutosSalida);
					$ban = 0;
				}
				if ($horaRest == 0){
					$minutosd1 = 30;
					$ban = 0;
				}
				if ($ban == 1)
					$minutosd1 = abs($horaRest * 60 - $minutosSalida);
			}
			$diasHabiles --;
		}
		// -------------------------------CALCULAR TIEMPO DE RETORNO---------------------------------------------
		if ($hora2R < $hora2Rxx and $hora2R >= $hora1Sxx){
			if ($horaRetorno == 16){
				$minutosd2 = 450 + $minutosRetorno;
			}else{
				$ban = 1;
				$horaRest = abs($horaRetorno - 8);
				if ($horaRest > 8){
					$ban = 0;
					$horaRest = 8;
					$minutosd2 = abs(($horaRest * 60) - $minutosRetorno);
				}
				if ($horaRest < 8){
					$ban = 0;
					$minutosd2 = $horaRest * 60 + $minutosRetorno;
				}
				if ($horaRest == 0){
					$minutosd2 = $minutosRetorno;
					$ban = 0;
				}
				if ($ban == 1)
					$minutosd2 = abs(($horaRest - 1) * 60 + $minutosRetorno);
			}
			$diasHabiles --;
		}
		if ($hora2R > $hora2Rxx){
			$minutosd2 = 480;
			$diasHabiles --;
		}
		if ($hora2R < $hora1Sxx){
			$diasHabiles --;
		}
		// -----------------------------------------------------------------------------------------------
		if ($diasHabiles <= 0){
			$minutos = $minutosd1 + $minutosd2;
		}else{
			$minutos = ($diasHabiles * 480) + $minutosd1 + $minutosd2;
		}
		return $minutos;
	}

	// ----------------------------------------------------------------------------------------------------------------------------------------
	public function devolverMinutUnDia($horaS, $horaR){
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

		// ----------------------------------SALIDA---------------------------------------------------
		if ($hora1S == $hora1Sxx && $hora2R == $hora2Rxx){
			$minutos = 480;
		}else{
			$horasRest = $horaRetorno - $horaSalida;
			if ($horaSalida < 12 && $horaRetorno >= 12){
				$hora1 = 12 - $horaSalida;
				if ($hora1 == 4)
					$hora1 --;
				$horaMinu1 = ($hora1 * 60) - $minutosSalida;
				$hora2Minu2 = (($horaRetorno - 12) * 60) + $minutosRetorno;
				$minutos = $horaMinu1 + $hora2Minu2;
			}
		}
		return $minutos;
	}

	// --------------------------------------------------------------------------------------------
	public function devolverMinutosHoras($hhhh, $mmm, $banfecha){
		$horaSalida = substr($hhhh, 0, 2);
		$minutosSalida = substr($hhhh, 3, 2);
		$horaRetorno = substr($mmm, 0, 2);
		$minutosRetorno = substr($mmm, 3, 2);

		$hora1S = strtotime($hhhh);
		$hora2R = strtotime($mmm);
		$horaNeutral = strtotime("12:00");
		$minutos = 0;

		if ($hora1S < $horaNeutral && $hora2R > $horaNeutral && $banfecha != 1){
			$horaT1 = 12 - $horaSalida;
			$minutosd1 = ($horaT1 * 60) - $minutosSalida;
			$horaT2 = $horaRetorno - 12;
			$minutosd2 = ($horaT2 * 60) + $minutosRetorno;
			$minutos = $minutosd1 + $minutosd2;
		}
		if ($hora1S < $horaNeutral && $hora2R <= $horaNeutral && $banfecha != 1){
			$horasT = abs($horaSalida - $horaRetorno);

			if ($minutosSalida > $minutosRetorno){
				$minutosT = $minutosSalida - $minutosRetorno;
				$minutos = abs(($horasT * 60) - $minutosT);
			}
			if ($minutosSalida < $minutosRetorno){
				$minutosT = $minutosRetorno - $minutosSalida;
				$minutos = abs(($horasT * 60) + $minutosT);
			}
			if ($minutosSalida == $minutosRetorno){
				$minutos = $horasT * 60;
			}
			if ($horaRetorno == $horaSalida){
				if ($minutosSalida > $minutosRetorno)
					$minutos = 0;
			}
			if ($horaRetorno < $horaSalida){
				$minutos = 0;
			}
		}
		if ($hora1S >= $horaNeutral && $hora2R > $horaNeutral && $banfecha != 1){
			$horasT = abs($horaRetorno - $horaSalida);

			if ($minutosSalida > $minutosRetorno){
				$minutosT = $minutosSalida - $minutosRetorno;
				$minutos = abs(($horasT * 60) - $minutosT);
			}
			if ($minutosSalida < $minutosRetorno){
				$minutosT = $minutosRetorno - $minutosSalida;
				$minutos = abs(($horasT * 60) + $minutosT);
			}
			if ($minutosSalida == $minutosRetorno){
				$minutos = $horasT * 60;
			}
			if ($horaRetorno == $horaSalida){
				if ($minutosSalida > $minutosRetorno)
					$minutos = 0;
			}
			if ($horaRetorno < $horaSalida){
				$minutos = 0;
			}
		}
		if ($hora1S >= $horaNeutral && $hora2R < $horaNeutral && $banfecha == 1){
			$horaT1 = 24 - $horaSalida;
			$minutosd1 = ($horaT1 * 60) - $minutosSalida;

			$minutos = $minutosd1 + ($horaRetorno * 60) + $minutosRetorno;
		}
		return $minutos;
	}

	// ----------------------------------------------------------------------------------------------
	public function devolverJefeImnediato($conexion, $identificadorUsuario){

		// Área de usuario para revisión y aprobación de jefe inmediato
		$areaUsuario = pg_fetch_assoc($conexion->ejecutarConsulta("select
																			a.*
																	from
																			g_estructura.area as a,
																			g_estructura.funcionarios as f
																	where
																			a.id_area = f.id_area
																			and f.identificador = '$identificadorUsuario'"));
		$idAreaFuncionario = $areaUsuario['id_area'];

		$areaRecursiva = $this->nivelesAreaConsultada($conexion, $idAreaFuncionario);

		$tipoArea = $areaRecursiva['clasificacion'];
		$arrayAreas = explode(',', $areaRecursiva['path']);
		$numAreas = sizeof($arrayAreas) - 1;

		// -------verificar responsabilidad---------------------------------------------
		$verificar = $this->verificarResponsable($conexion, $identificadorUsuario);
		while ($fila = pg_fetch_assoc($verificar)){
			if ($fila['categoria_area'] < $areaUsuario['categoria_area']){
				$areaUsuario = $fila['id_area'];
				$numAreas --;
			}
		}
		$idArea = $areaUsuario;
		$ban = 0;
		for ($i = $numAreas, $j = 0; $j < $numAreas; $i --, $j ++){
			$idArea = $arrayAreas[$i];
			$identificadorJefe = pg_fetch_result($conexion->ejecutarConsulta("select
															*
													from
															g_estructura.responsables
													where
															id_area = '$idArea'
															and responsable = true
															and estado = 1;"), 0, 'identificador');

			if (! strcmp($identificadorJefe, $identificadorUsuario) == 0 and $identificadorJefe != ''){
				break;
			}
		}
		$idAreaPermiso = '';
		for ($i = $numAreas, $j = 0; $j < $numAreas; $i --, $j ++){
			$idAreaP = $arrayAreas[$i];
			if ($areaUsuario['clasificacion'] == 'Planta Central'){
				$idAreaPermiso = 'DGATH';
				break;
			}else{
				$idAreaPermiso = pg_fetch_result(($conexion->ejecutarConsulta("SELECT
						*
						FROM
								g_estructura.area
						WHERE
								id_area_padre = '$idAreaP'
								and clasificacion = 'Dirección Distrital A';")), 0, 'id_area');
				if ($idAreaPermiso != '')
					break;
			}
		}
		if ($idArea == 'DE')
			$idAreaPermiso = 'DGATH';
		$usuarioDatos = pg_fetch_assoc($this->datosFuncionario($conexion, $identificadorJefe));

		$resultConsulta = array(
			'identificador' => $identificadorJefe,
			'idarea' => $idAreaPermiso,
			'idareajefe' => $idArea,
			'usuario' => $usuarioDatos['user'],
			'idareafuncionario' => $idAreaFuncionario);
		return $resultConsulta;
	}

	// ---------------------------------------------------------------------------------------------
	public function nivelesAreaConsultada($conexion, $idAreaFuncionario){

		// ---devolver niveles del área consultada-----------------------------------------------------------------------
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

	// ----------------------------------------------------------------------------------------------
	public function datosFuncionario($conexion, $identificador){
		$sqlScript = "SELECT 
	            apellido ||' '||nombre as user
		FROM
			g_uath.ficha_empleado
		WHERE
		identificador='$identificador'";
		$res = $conexion->ejecutarConsulta($sqlScript);
		return $res;
	}

	// ----------------------------------------------------------------------------------------------
	public function verificarResponsable($conexion, $identificador){
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

	// ----------------------------------------------------------------------------------------------
	public function graficoEstructura($conexion, $clasificacion, $padre){
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

	// ----------------------------------------------------------------------------------------------
	public function graficoEstructura2($conexion, $categoria, $padre){
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

	// ------------------------------------------------------------------------------------------------
	public function buscarExcelDescuentos($conexion, $mes, $ano, $idExcelDescuento = null){
		$mes = $mes != "" ? "'" . $mes . "'" : "NULL";
		$ano = $ano != "" ? "'" . $ano . "'" : "NULL";
		$idExcelDescuento = $idExcelDescuento != "" ? "'" . $idExcelDescuento . "'" : "NULL";
		if (($mes == "NULL") && ($ano == "NULL") && ($idExcelDescuento == "NULL")){
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

	// ------------------------------------------------------------------------------------------------------------------------
	public function guardarNuevoExcelDescuento($conexion, $mes, $ano, $rutaArchivoExcel, $nombreArchivoExcel){
		$sql = "
	 INSERT INTO 
	 		g_vacaciones.excel_descuentos(mes_descuento, anio_descuento, ruta_archivo,nombre_archivo, fecha_creacion)
	 VALUES ( '$mes', '$ano', '$rutaArchivoExcel','$nombreArchivoExcel', now()) RETURNING id_excel_descuento;";
		$res = $conexion->ejecutarConsultaLOGS($sql);
		return $res;
	}

	// -------------------------------------------------------------------------------------------------------
	public function nuevoPermisoDescuento($conexion, $subTipoSolicitud, $fechaSalida, $fechaRetorno, $horaSalida, $horaRetorno, $identificador, $minutos_utilizados, $fecha_maxima_justificar, $rutaArchivo, $periodosTomados, $fechaSuceso, $idAreaPermiso, $destinoComision, $pivTipo){
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

	public function devolverFormatoDiasDisponibles($minutosutilizados){
		if ($minutosutilizados >= 1)
			$valor = '';
		else
			$valor = '- ';
		$minutosutilizados = abs($minutosutilizados);
		$diasDescontados = '';
		$separador = '';
		$dias = floor(intval($minutosutilizados) / 480);
		if ($dias != 0){
			if ($dias >= 2)
				$diasDescontados .= $valor . $dias . ' días';
			else
				$diasDescontados .= $valor . $dias . ' día';
			$separador = ' ';
			$valor = '';
		}
		$horas = floor((intval($minutosutilizados) - $dias * 480) / 60);
		if ($horas != 0){
			$valor = '';
			if ($horas >= 2)
				$diasDescontados .= $separador . $valor . $horas . ' horas';
			else
				$diasDescontados .= $separador . $valor . $horas . ' hora';
			$separador = ' ';
		}
		$minutos = (intval($minutosutilizados) - $dias * 480) - $horas * 60;
		if ($minutos != 0){
			if ($minutos >= 2)
				$diasDescontados .= $separador . $valor . $minutos . ' minutos';
			else
				$diasDescontados .= $separador . $valor . $minutos . ' minuto';
		}
		return $diasDescontados;
	}

	public function obtenerTiempoPermisoSolicitado($conexion, $id_permiso){
		$sqlScript = "select
					*
				from
						g_vacaciones.permiso_empleado pe
				where
						pe.id_permiso_empleado='" . $id_permiso . "';";

		$res = $conexion->ejecutarConsulta($sqlScript);
		return $res;
	}

	public function obtenerFuncionariosDescuento($conexion, $fechainicio, $fechafin, $idexcel){
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

	// ---------------------------------------------------------------------------------------------------------
	public function actualizarEncargado($conexion, $id_registro, $identificador_responsable, $identificador_encargado, $id_area, $id_area_encargado, $nombrePuesto, $nombrePuestoEncargado, $fechaInicio, $fechaFin, $designacion, $archivoSub){
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

	// ---------------------------------------------------------------------------------------------------------
	public function nuevoEncargado($conexion, $identificador_responsable, $identificador_encargado, $id_area, $id_area_encargado, $nombrePuesto, $nombrePuestoEncargado, $fechaInicio, $fechaFin, $idPermiso, $designacion, $archivoSub){
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

	// ---------------------------------------------------------------------------------------------------------
	public function obtenerEncargadoPuestoArea($conexion, $identificador_responsable, $identificador_encargado, $id_area, $estado, $id_registro, $nombre_puesto){
		$identificador_responsable = $identificador_responsable != "" ? "'" . $identificador_responsable . "'" : "NULL";
		$identificador_encargado = $identificador_encargado != "" ? "'" . $identificador_encargado . "'" : "NULL";
		$id_area = $id_area != "" ? "'" . $id_area . "'" : "NULL";
		$estado = $estado != "" ? "'" . $estado . "'" : "NULL";
		$id_registro = $id_registro != "" ? "'" . $id_registro . "'" : "NULL";
		$nombre_puesto = $nombre_puesto != "" ? "'" . $nombre_puesto . "'" : "NULL";

		// ($mes is NULL or mes_descuento = $mes) and
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

	// ---------------------------------------------------------------------------------------------------------
	public function actualizarEstadoResponsablePuesto($conexion, $id_solicitud_permiso, $estado_solicitud){
		$sqlScript = "Update
							g_subrogacion.responsables_puestos
						set
							estado='" . $estado_solicitud . "'
						where
							id_permiso_empleado='" . $id_solicitud_permiso . "';";

		$res = $conexion->ejecutarConsultaLOGS($sqlScript);
		return $res;
	}

	// --------------------------------------------------------------------------------------------------------
	public function consultarPermisosAprobados($conexion){
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
	// ---------------------DEVOLVER PERMISOS PARA REINTEGRO-----------------------------------------------------------------------------------
	public function consultarPermisosReintegro($conexion){
		 $sql = "SELECT 
				pv.id_permiso_empleado,
				pv.identificador,
				pv.id_area_permiso,
				rh.identificador as identificadorrhh,
				stp.codigo
			FROM
				g_vacaciones.permiso_empleado pv, 
				g_uath.encargo_recursos_humanos rh,
				g_catalogos.subtipo_permiso stp
			
			where
                stp.codigo in (	'EN-EF',
				'EN-EC',
				'NA-MA',
				'NA-MPA',
				'NA-PPN',
				'NA-PPM',
				'NA-PPC',
				'NA-NP',
				'NA-NED',
				'NA-PM',
				'VA-VA') and 
				pv.sub_tipo = stp.id_subtipo_permiso and
				pv.estado = 'InformeGenerado' and
				id_area_permiso <> '' and
				rh.zona_area = id_area_permiso and
				rh.estado = 'activo' and 
				pv.reintegro = FALSE and 
				EXTRACT(YEAR FROM fecha_solicitud) > 2018;";
		$res = $conexion->ejecutarConsulta($sql);
		return $res;
	}

	// ---------------------------devolver tiempo actual sin mes caido-----------------------------------------------------------------------------
	public function devolverTiempoActual($conexion, $result, $identificador, $estado = NULL){
		$contador = pg_num_rows($result);
		$secuencia = 1;
		$fechaInicial = pg_fetch_assoc($this->devolverFechaInicalContrato($conexion, $identificador));
		$tiempoIncrementar = pg_fetch_assoc($this->devolverRegimenLaboralTiempo($conexion, $identificador));
		$resultado = array();
		$saldoTotal = 0;

		// **********************llenar informacion por años ***************************************
		while ($fila = pg_fetch_assoc($result)){
			$minutosDisponibles = $fila['minutos_disponibles'];
			$saldoTotal = $saldoTotal + $minutosDisponibles;
			if ($contador > 1){
				if ($contador == $secuencia){
					// fin
					$minutosDisponibles = $minutosDisponibles - $tiempoIncrementar['tiempo'];
					if ($minutosDisponibles >= 0){
						if ($fila['anio'] == date('Y')){
							// echo 'nivel 1 <br>';
							// $minutosDisponibles = $minutosDisponibles + $tiempoIncrementar['tiempo'];
						}else{
							// echo 'nivel 2 <br>';
						}
					}else{
						// echo 'nivel 1.1';
						$minutosDisponibles = 0;
					}
				}else if ($secuencia == 1){
					// inicial
					if ($fila['anio'] == $fechaInicial['anio_inicial']){

						// echo 'nivel 3 <br>';
						if ($estado == 'TRUE'){
							$minutosDisponibles = $minutosDisponibles + $tiempoIncrementar['tiempo'];
						}
					}else{
						if ($tiempoIncrementar['tiempo'] == 1200){
							$saldoAnual = 14400;
						}else{
							$saldoAnual = 7200;
						}

						echo $minutosDisponibles . '>' . $saldoAnual . '-' . $tiempoIncrementar['tiempo'];

						if ($minutosDisponibles > ($saldoAnual - $tiempoIncrementar['tiempo'])){
							// si el saldo es igual a 30 días crear un año anterior
							// echo 'nivel 4.1 <br>';
							$nuevoSaldo = $tiempoIncrementar['tiempo'] - ($saldoAnual - $minutosDisponibles);
							$conversion = $this->devolverMinutosDisponiblesNoDisponibles($nuevoSaldo, $tiempoIncrementar['tiempo']);
							$resultado[] = array(
								'identificador' => $fila['identificador'],
								'servidor' => $fila['apellido'] . ' ' . $fila['nombre'],
								'anio' => $fila['anio'] - 1,
								'utilizado' => $conversion['disponible'],
								'libre' => $conversion['noDisponible'],
								'tiempo' => number_format(($nuevoSaldo / 480), 2),
								'tiempoTotal' => $saldoTotal);
							$nuevoSaldo = ($saldoAnual - $minutosDisponibles);
						}else{
							// sumar saldo al primer año
							// echo 'nivel 4.2 <br>';
							if ($estado == 'TRUE'){
								$minutosDisponibles = $minutosDisponibles + $tiempoIncrementar['tiempo'];
							}
							// echo $nuevoSaldo = $tiempoIncrementar['tiempo']-($saldoAnual-$minutosDisponibles);
						}
					}
				}else{
					// año intermedio no hacer nada
					// echo 'nivel 5 <br>';
				}
				$secuencia ++;
			}else{
				if ($fila['anio'] == $fechaInicial['anio_inicial']){
					// primer año de labores no descontar
					// echo 'nivel 6.1 <br>';
				}else{
					// arrastre de saldo agregar año anterior;
					// echo 'nivel 6.2 <br>';
					$minutosAgregar = 0;
					if ($minutosDisponibles >= $tiempoIncrementar['tiempo']){
						$minutosDisponibles = $minutosDisponibles - $tiempoIncrementar['tiempo'];
						$minutosAgregar = $tiempoIncrementar['tiempo'];
					}else{
						$minutosAgregar = $minutosDisponibles;
						$minutosDisponibles = 0;
					}
					$conversion = $this->devolverMinutosDisponiblesNoDisponibles($minutosAgregar, $tiempoIncrementar['tiempo']);
					$resultado[] = array(
						'identificador' => $fila['identificador'],
						'servidor' => $fila['apellido'] . ' ' . $fila['nombre'],
						'anio' => $fila['anio'] - 1,
						'utilizado' => $conversion['disponible'],
						'libre' => $conversion['noDisponible'],
						'tiempo' => number_format(($minutosAgregar / 480), 2),
						'tiempoTotal' => $saldoTotal);
				}
			}
			// echo 'ultimox' . $minutosDisponibles . 'x' . $nuevoSaldo . '*' . $tiempoIncrementar['tiempo'] . 'mm';
			$conversion = $this->devolverMinutosDisponiblesNoDisponibles(($minutosDisponibles + $nuevoSaldo), $tiempoIncrementar['tiempo']);
			$resultado[] = array(
				'identificador' => $fila['identificador'],
				'servidor' => $fila['apellido'] . ' ' . $fila['nombre'],
				'anio' => $fila['anio'],
				'mes' => '',
				'utilizado' => $conversion['disponible'],
				'libre' => $conversion['noDisponible'],
				'tiempo' => number_format((($minutosDisponibles + $nuevoSaldo) / 480), 2),
				'tiempoTotal' => $this->devolverTiempoFormateadoDHM($saldoTotal));
			$nuevoSaldo = 0;
		}
		// *******************verificar saldo de vacaciones nueva información****************************

		$listaReporte = $this->filtroObtenerReporteSaldoFuncionario($conexion, $identificador, $estado, '', '', '', 'individual');
		while ($fila = pg_fetch_assoc($listaReporte)){
			// echo 'nueva tabla';
			$conversion = $this->devolverMinutosDisponiblesNoDisponibles($fila['minutos_disponibles'], $tiempoIncrementar['tiempo']);
			$saldoTotal = $saldoTotal + $fila['minutos_disponibles'];
			$resultado[] = array(
				'identificador' => $fila['identificador'],
				'servidor' => $fila['apellido'] . ' ' . $fila['nombre'],
				'anio' => $fila['anio'],
				'mes' => $fila['mes'],
				'utilizado' => $conversion['disponible'],
				'libre' => $conversion['noDisponible'],
				'tiempo' => number_format((($fila['minutos_disponibles']) / 480), 2),
				'tiempoTotal' => $this->devolverTiempoFormateadoDHM($saldoTotal));
		}
		// *********************************************************************************************
		return $resultado;
	}

	// ---------------------------devolver tiempo en días horas minutos-----------------------------------------------------------------------------
	public function devolverTiempoFormateadoDHM($tiempo){
		$dias = floor(intval($tiempo) / 480);
		$horas = floor((intval($tiempo) - $dias * 480) / 60);
		$minutos = (intval($tiempo) - $dias * 480) - $horas * 60;
		return $dias . ' días ' . $horas . ' horas ' . $minutos . ' minutos';
	}

	// ---------------------------convertir minutos a disponibles y no disponibles-------------------------------------------------------------------
	public function devolverMinutosDisponiblesNoDisponibles($tiempo, $tiempoRegimen){
		$resultado = array();
		if ($tiempoRegimen == 1200){
			$disponibleMin = 880;
			$noDisponibleMin = 320;
		}else{
			$disponibleMin = 440;
			$noDisponibleMin = 160;
		}
		$calculoDisponible = round($disponibleMin * ($tiempo / $tiempoRegimen));
		$calculoNoDisponible = round($noDisponibleMin * ($tiempo / $tiempoRegimen));

		$resultado = array(
			'disponible' => $this->devolverTiempoFormateadoDHM($calculoDisponible),
			'noDisponible' => $this->devolverTiempoFormateadoDHM($calculoNoDisponible),
			'disponibleTiempo' => $calculoDisponible,
			'noDisponibleTiempo' => $calculoNoDisponible
		);
		return $resultado;
	}

	// ---------------------------devolver fecha inicial de contrato de trabajo-----------------------------------------------------------------------------
	public function devolverFechaInicalContrato($conexion, $identificador){
		$sqlScript = "Select 
                            fecha_inicial,
                            extract(month from (fecha_inicial::timestamp ))::double precision as mes,
                            extract(month from (now()))::double precision as mes_actual,
                            extract(year from (fecha_inicial::timestamp))::double precision as anio_inicial
                      from
                            g_certificados_uath.devolver_fecha_inicial('" . $identificador . "');";

		$res = $conexion->ejecutarConsulta($sqlScript);
		return $res;
	}

	// ---------------------------devolver el regimen laboral----------------------------------------------------
	public function devolverRegimenLaboralTiempo($conexion, $identificador){
		$sqlScript = "SELECT  
	                       case when upper(regimen_laboral) in ('CÓDIGO DE TRABAJO', 'SUJETOS CÓDIGO DE TRABAJO') then 600
	                       else
	                        1200
	                       end::integer as tiempo
                        FROM g_uath.datos_contrato where estado = 1 and identificador = '" . $identificador . "'";

		$res = $conexion->ejecutarConsulta($sqlScript);
		return $res;
	}

	// ---------------------------obtener listado de fecha de inicio--------------------------------------------
	public function obtenerDiaIngresoFuncionarios($conexion){
		$consulta = array();
		$sql = "SELECT
                    DISTINCT dc.identificador,
                    dc.fecha_inicio,
                    EXTRACT(DAY FROM dc.fecha_inicio) AS dia,
					upper(dc.regimen_laboral) as regimen_laboral,
                    case 
                       when upper(dc.regimen_laboral) in ('CÓDIGO DE TRABAJO', 'SUJETOS CÓDIGO DE TRABAJO') then 600
	                else
	                    1200
	                end::integer as tiempo,
	                tdf.activo
				FROM
					g_uath.datos_contrato dc
					LEFT JOIN g_vacaciones.tiempo_disponible_funcionarios tdf
					ON dc.identificador = tdf.identificador
				WHERE
					dc.estado=1
					and dc.regimen_laboral not in ('Servicios Civiles - Profesionales');";
		$res = $conexion->ejecutarConsulta($sql);

		while ($fila = pg_fetch_assoc($res)){

			$result = $conexion->ejecutarConsulta("
                      SELECT
                        fecha_inicial,
                        EXTRACT(DAY FROM fecha_inicial) AS dia
                      FROM
                        g_certificados_uath.devolver_fecha_inicial('" . $fila['identificador'] . "') ");

			if (pg_num_rows($result) > 0){

				$datos = pg_fetch_assoc($result);
				$consulta[] = array(
					'dia' => $datos['dia'],
					'identificador' => $fila['identificador'],
					'regimen_laboral' => $fila['regimen_laboral'],
					'tiempo' => $fila['tiempo'],
					'fecha_inicial_actual' => $fila['fecha_inicio'],
					'fecha_inicial' => $datos['fecha_inicial'],
					'activo' => $fila['activo']);
			}else{
				echo 'no salio ' . $fila['identificador'];
			}
		}

		return $consulta;
	}

	// ----------------------------------funcion para agregar minutos a funcionarios ------
	public function agregarMinutosServidores($conexion, $identificador, $tiempo, $observacion){
		$anioEmpleado = pg_fetch_assoc($this->obtenerAnioMayor($conexion, $identificador));
		if ($anioEmpleado['anio'] != ''){
			if ($anioEmpleado['anio'] == date('Y')){
				$anio = $anioEmpleado['anio'];
				$this->incrementarSaldosFuncionario($conexion, $identificador, $tiempo, $anio, $observacion);
			}else{
				$anio = $anioEmpleado['anio'] + 1;
				$this->incrementarSaldosFuncionarioNuevoAnio($conexion, $identificador, $tiempo, $anio, 1, $observacion);
			}
		}else{
			$anio = date('Y');
			$secuencial = pg_fetch_assoc($this->obtenerSecuencialanio($conexion, $identificador, $anio));
			if ($secuencial['secuencial'] == '')
				$secu = 1;
			else
				$secu = $secuencial['secuencial'] + 1;
			$this->incrementarSaldosFuncionarioNuevoAnio($conexion, $identificador, $tiempo, $anio, $secu, $observacion);
		}
	}

	// ----------------------------------funcion para agregar minutos a funcionarios con meses ----------------------
	public function agregarMinutosServidoresMes($conexion, $identificador, $tiempo, $anio, $mes, $observacion, $fechaInicialContrato){
		$consulta = $this->verificarInforTiempoDisponible($conexion, $identificador, $anio, $mes);
		if (pg_num_rows($consulta) > 0){
			$ban = 1;
			while ($fila = pg_fetch_assoc($consulta)){
				if ($fila['activo'] == 1){
					$ban = 0;
					// no realizar ninguna acción puesto que se duplica la información
				}
			}
			if ($ban){
				$this->incrementarSaldosFuncionarioNuevaTabla($conexion, $identificador, $tiempo, $anio, $mes, $fila['secuencial'], $observacion, $fechaInicialContrato);
			}
		}else{
			$this->incrementarSaldosFuncionarioNuevaTabla($conexion, $identificador, $tiempo, $anio, $mes, 1, $observacion, $fechaInicialContrato);
		}
	}

	// ----------------------------------funcion para calcular el proporcional de liquidacion-----------------------------------------
	public function devolverTiempoProporcional($fechaFinalContra, $tiempo){
		$fechaFinalContr = date('Y-m-d', strtotime($fechaFinalContra));
		$anio = date("Y", strtotime($fechaFinalContra));
		$mes = date("m", strtotime($fechaFinalContra));
	 	$totalDias = cal_days_in_month(CAL_GREGORIAN, $mes, $anio);
		$fechaInicial = $anio . '-' . $mes . '-01';
		$fechaInicial = date('Y-m-d', strtotime($fechaInicial));
		$fechaFinal = $anio . '-' . $mes . '-' . $totalDias;
		$fechaFinal = date('Y-m-d', strtotime($fechaFinal));
		
		if($fechaInicial == $fechaFinal){
			$diasHabiles = 0;
		}else{
			$diasHabiles = count($this->devolverNumDias($fechaInicial, $fechaFinalContr));
			if($mes == 2){
				if($diasHabiles > 27){
					$diasHabiles=30;
				}
			}else{
				if($diasHabiles > 30){
					$diasHabiles=30;
				}
			}
		}
		$proporcionalTiempo = ($tiempo * ($diasHabiles)) / 30;
		return $proporcionalTiempo;
	}
	// --------------------------funcion para calcular el proporcional de inicio-----------------------------------------
	public function devolverTiempoProporcionalInicial($fechaInicialContra, $tiempo){
		$fechaInicialContr = date('Y-m-d', strtotime($fechaInicialContra));
		$anio = date("Y", strtotime($fechaInicialContr));
		$mes = date("m", strtotime($fechaInicialContr));
		
		$fechaFinal = $anio . '-' . $mes . '-30';
		$fechaFinal = date('Y-m-d', strtotime($fechaFinal));
		
		if($fechaInicialContr == $fechaFinal){
			$diasHabiles = 0;
		}else{
			$diasHabiles = count($this->devolverNumDias($fechaInicialContr, $fechaFinal));
			if($mes == 2){
				if($diasHabiles > 27){
					$diasHabiles=30;
				}
			}else{
				if($diasHabiles > 30){
					$diasHabiles=30;
				}
			}
		
		}
		$proporcionalTiempo = ($tiempo * ($diasHabiles-1)) / 30;
		return $proporcionalTiempo;
	}
	

	// ----------------------------------funcion para devolver fechas de ultimo contrato y tiempo de que se agrega en minutos mensual------
	public function devolverFechasUltimoContratoTiempo($conexion, $identificador){
		 $sql = "select 
                     fecha_inicio, fecha_fin, regimen_laboral,
                      case 
                        when upper(regimen_laboral) in ('CÓDIGO DE TRABAJO', 'SUJETOS CÓDIGO DE TRABAJO') then 600
                	  else
                	    1200
                	  end::integer as tiempo
                 from 
                    g_uath.datos_contrato 
                 where 
                    identificador='" . $identificador . "' and tipo_contrato not in ('Subrogación','Encargo') 
                 order by 1 DESC LIMIT 1;";

		$res = $conexion->ejecutarConsulta($sql);
		return $res;
	}

	// --------------------------------filtro para obtener el saldo de vacaciones de funcionarios--------------------------------------------------------------------
	public function filtroObtenerReporteSaldoFuncionario($conexion, $identificador, $estado, $apellido, $nombre, $area, $tipoReporte){
		$busqueda = '';
		$parametros = '';
		$agrupacion = '';
		$orden = '';

		if ($tipoReporte != 'unico'){
			$parametros = "mdf.*, fe.nombre, fe.apellido";
			$orden = "ORDER BY 1,2";
		}else{
			$parametros = "sum(minutos_disponibles) as minutos_disponibles, fe.nombre, fe.apellido, mdf.identificador,string_agg(distinct mdf.anio::character varying,'- ') as anio";
			$agrupacion = "GROUP BY fe.nombre, fe.apellido, mdf.identificador";
		}

		if ($identificador != ''){
			$busqueda = "and mdf.identificador IN ('$identificador')";
		}

		if ($apellido != ''){
			$busqueda .= " and mdf.identificador IN (SELECT identificador FROM g_uath.ficha_empleado WHERE upper(apellido) like upper('%$apellido%'))";
		}

		if ($nombre != ''){
			$busqueda .= " and mdf.identificador IN (SELECT identificador FROM g_uath.ficha_empleado WHERE upper(nombre) like upper('%$nombre%'))";
		}

		if ($area != ''){

			if ($area == 'DE'){

				$areaSubproceso = "'" . $area . "',";
			}else{
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

				while ($fila = pg_fetch_assoc($areaProceso)){
					$areaSubproceso .= "'" . $fila['id_area'] . "',";
				}
			}

			$areaSubproceso = "(" . rtrim($areaSubproceso, ',') . ")";

			$busqueda .= ' and mdf.identificador IN (SELECT identificador FROM g_estructura.funcionarios WHERE id_area IN ' . $areaSubproceso . ')';
		}

		if ($estado != ''){
			$estadosql = "and mdf.activo = '$estado'";
		}else{
			$estadosql = '';
		}
		
		$res = $conexion->ejecutarConsulta("SELECT
												" . $parametros . "
											FROM
												g_vacaciones.tiempo_disponible_funcionarios mdf,
												g_uath.ficha_empleado fe
											WHERE
												mdf.identificador = fe.identificador
												" . $estadosql . "
												" . $busqueda . "
												" . $agrupacion . "
												" . $orden . "");

		return $res;
	}
	// ----------------------------------filtro para obtener los saldos de vacaciones de funcionarios liquidados-----------------------------------------------
	public function filtroObtenerReporteFuncionariosLiquidar($conexion, $identificador, $estado, $apellido, $nombre, $area, $tipoReporte){
		$busqueda = '';
		$parametros = '';
		$agrupacion = '';
		$orden = '';
		
		if ($tipoReporte != 'unico'){
			$parametros = "lv.*, fe.nombre, fe.apellido";
			$orden = "ORDER BY 1,2";
		}else{
			$parametros = "id_liquidacion_vacaciones, minutos_liquidados, fe.nombre, fe.apellido, lv.identificador,anios_liquidados, numero_cur";
			//$agrupacion = "GROUP BY fe.nombre, fe.apellido, lv.identificador";
		}
		
		if ($identificador != ''){
			$busqueda = "and lv.identificador IN ('$identificador')";
		}
		
		if ($apellido != ''){
			$busqueda .= " and lv.identificador IN (SELECT identificador FROM g_uath.ficha_empleado WHERE upper(apellido) like upper('%$apellido%'))";
		}
		
		if ($nombre != ''){
			$busqueda .= " and lv.identificador IN (SELECT identificador FROM g_uath.ficha_empleado WHERE upper(nombre) like upper('%$nombre%'))";
		}
		
		if ($area != ''){
			
			if ($area == 'DE'){
				
				$areaSubproceso = "'" . $area . "',";
			}else{
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
				
				while ($fila = pg_fetch_assoc($areaProceso)){
					$areaSubproceso .= "'" . $fila['id_area'] . "',";
				}
			}
			
			$areaSubproceso = "(" . rtrim($areaSubproceso, ',') . ")";
			
			$busqueda .= ' and lv.identificador IN (SELECT identificador FROM g_estructura.funcionarios WHERE id_area IN ' . $areaSubproceso . ')';
		}
		
		if ($estado != ''){
			$estadosql = "and lv.estado = '$estado'";
		}else{
			$estadosql = '';
		}
		$res = $conexion->ejecutarConsulta("SELECT
												" . $parametros . "
											FROM
												g_vacaciones.liquidacion_vacaciones lv,
												g_uath.ficha_empleado fe
											WHERE
												lv.identificador = fe.identificador
												" . $estadosql . "
												" . $busqueda . "
												" . $agrupacion . "
												" . $orden . "");
		
		return $res;
	}
	// ----------------------------------filtro para obtener los saldos de vacaciones de funcionarios liquidados-----------------------------------------------
	public function filtroObtenerSaldoFuncionarioLiquidar($conexion, $identificador, $estado, $idLiquidacion=NULL){
		
		if ($idLiquidacion != NULL){
			$busqueda = "and lv.id_liquidacion_vacaciones = '$idLiquidacion'";
		}
		$res = $conexion->ejecutarConsulta("
									SELECT 
											id_liquidacion_vacaciones, numero_cur, fecha_liquidacion,lv.minutos_liquidados,dc.gestion, fe.apellido ||''||fe.nombre as funcionario
									FROM 
											g_vacaciones.liquidacion_vacaciones lv 
											INNER JOIN g_uath.datos_contrato dc ON lv.identificador = dc.identificador
											INNER JOIN g_uath.ficha_empleado fe ON fe.identificador = dc.identificador
									WHERE 
											lv.estado='".$estado."' and 
											lv.identificador = '".$identificador."'
											$busqueda ");
		
		return $res;
	}
	// ---------------------------------------------------------------------------------------------------------
	public function devolverMes($numMes){
		$mes = '';
		switch ($numMes) {
			case 1:
				$mes = 'Enero';
			break;
			case 2:
				$mes = 'Febrero';
			break;
			case 3:
				$mes = 'Marzo';
			break;
			case 4:
				$mes = 'Abril';
			break;
			case 5:
				$mes = 'Mayo';
			break;
			case 6:
				$mes = 'Junio';
			break;
			case 7:
				$mes = 'Julio';
			break;
			case 8:
				$mes = 'Agosto';
			break;
			case 9:
				$mes = 'Septiembre';
			break;
			case 10:
				$mes = 'Octubre';
			break;
			case 11:
				$mes = 'Noviembre';
			break;
			case 12:
				$mes = 'Diciembre';
			break;
			default:
				$mes = $numMes;
			break;
		}

		return $mes;
	}

	// ************************************************************************************************
	public function actualizarSaldosFuncionarioNuevo($conexion, $usuario, $minutosConsumidos, $idPermisoEmpleado){
		$saldo1 = pg_fetch_result($this->consultarSaldoFuncionario($conexion, $usuario), 0, 'minutos_disponibles');
		$saldo2 = pg_fetch_result($this->consultarSaldoFuncionarioNuevo($conexion, $usuario), 0, 'minutos_disponibles');

		$saldoTotal = $saldo1 + $saldo2;
		if ($minutosConsumidos <= $saldoTotal){
			$observacion = 'Tiempo descontado por aprobación de permiso #: ' . $idPermisoEmpleado;
			if ($minutosConsumidos <= $saldo1){
				$this->descontarSaldoXAnios($conexion, $usuario, $minutosConsumidos, $idPermisoEmpleado, $observacion);
			}else{
				$minutosADescontar = $minutosConsumidos - $saldo1;
				if ($saldo1 != 0){
					$this->descontarSaldoXAnios($conexion, $usuario, $saldo1, $idPermisoEmpleado, $observacion);
				}
				$this->descontarSaldoXMeses($conexion, $usuario, $minutosADescontar, $idPermisoEmpleado, $observacion);
			}
		}
	}

	// *************************************actualizar los minutos disponibles por año*****************************************************
	public function actualizarMinutosDisponibles($conexion, $usuario, $estado, $anio, $minutosADescontar, $observacion){
		$conexion->ejecutarConsultaLOGS("update
														g_vacaciones.minutos_disponibles_funcionarios
													set
														minutos_disponibles=minutos_disponibles-" . $minutosADescontar . ",
														activo = " . $estado . ",
														observacion = '" . $observacion . "'
													where
														identificador='" . $usuario . "' and
														anio='" . $anio . "'
														and activo=true ;");
	}

	// **********************************actualizar los minutos disponibles por mes*********************************************************
	public function actualizarMinutosDisponiblesXMes($conexion, $usuario, $estado, $anio, $mes, $minutosADescontar, $observacion){
		$conexion->ejecutarConsultaLOGS("update
														g_vacaciones.tiempo_disponible_funcionarios
													set
														minutos_disponibles = minutos_disponibles-" . $minutosADescontar . ",
														activo = " . $estado . ",
														observacion = '" . $observacion . "'
													where
														identificador='" . $usuario . "' and
														anio=" . $anio . " and
														mes = '" . $mes . "'
														and activo=true ;");
	}

	// ****************************************guardar detalle el descuento de tiempo******************************************************************
	public function guardarDetalleDescuento($conexion, $usuario, $anio, $minutosADescontar, $idPermisoEmpleado){
		$conexion->ejecutarConsultaLOGS("	INSERT INTO
													g_vacaciones.detalle_descuento_tiempo(
											            id_permiso_empleado,
														identificador,
														anio,
											            tiempo,
														estado)
											    VALUES (" . $idPermisoEmpleado . ",
														'" . $usuario . "',
														'" . $anio . "',
														" . $minutosADescontar . ",
														'creado');");
	}

	// ******************************devolver el saldo de funcionarios por año**********************************************************************************************
	public function devolverSaldoFuncionarios($conexion, $usuario, $estado = 'true'){
		$resVacaciones = $conexion->ejecutarConsulta("	Select
																minutos_disponibles, anio
														from
																g_vacaciones.minutos_disponibles_funcionarios
														where
																activo=". $estado ." and
																identificador='" . $usuario . "'
														ORDER BY
																anio");
		return $resVacaciones;
	}

	// ******************************devolver el saldo de funcionarios por meses**********************************************************************************************
	public function devolverSaldoFuncionariosXMes($conexion, $usuario, $estado='true'){
		$resVacaciones = $conexion->ejecutarConsulta("	Select
																minutos_disponibles, anio, mes
														from
																g_vacaciones.tiempo_disponible_funcionarios
														where
																activo=". $estado ." and
																identificador='" . $usuario . "'
														ORDER BY
																anio, id_tiempo_disponible_funcionario");
		return $resVacaciones;
	}

	// *******************************descontar saldo de funcionarios por año*********************************************************
	public function descontarSaldoXAnios($conexion, $usuario, $minutosConsumidos, $idPermisoEmpleado, $observacion,$opt=true){
		$resVacaciones = $this->devolverSaldoFuncionarios($conexion, $usuario);
		$minutosPendientes = $minutosConsumidos;
		$minutosADescontar = 0;
		while ($fila = pg_fetch_assoc($resVacaciones)){

			if ($minutosPendientes > 0){
				if (abs(intval($fila['minutos_disponibles'])) <= $minutosPendientes){
					$minutosPendientes = $minutosPendientes - $fila['minutos_disponibles'];
					$minutosADescontar = $fila['minutos_disponibles'];
					$estado = 'false';
				}else{
					$minutosADescontar = $minutosPendientes;
					$minutosPendientes -= $minutosPendientes;
					$estado = 'true';
				}

				$this->actualizarMinutosDisponibles($conexion, $usuario, $estado, $fila['anio'], $minutosADescontar, $observacion);
				if($opt){
					$this->guardarDetalleDescuento($conexion, $usuario, $fila['anio'], $minutosADescontar, $idPermisoEmpleado);
				}
			}
		}
	}

	// ****************************************descontar saldo de funcionarios por mes**********************************************************
	public function descontarSaldoXMeses($conexion, $usuario, $minutosConsumidos, $idPermisoEmpleado, $observacion,$opt=true){
		$resVacaciones = $this->devolverSaldoFuncionariosXMes($conexion, $usuario);
		$minutosPendientes = $minutosConsumidos;
		$minutosADescontar = 0;
		while ($fila = pg_fetch_assoc($resVacaciones)){

			if ($minutosPendientes > 0){
				if (abs(intval($fila['minutos_disponibles'])) <= $minutosPendientes){
					$minutosPendientes = $minutosPendientes - $fila['minutos_disponibles'];
					$minutosADescontar = $fila['minutos_disponibles'];
					$estado = 'false';
				}else{
					$minutosADescontar = $minutosPendientes;
					$minutosPendientes -= $minutosPendientes;
					$estado = 'true';
				}

				$this->actualizarMinutosDisponiblesXMes($conexion, $usuario, $estado, $fila['anio'], $fila['mes'], $minutosADescontar, $observacion);
				if($opt){
					$this->guardarDetalleDescuento($conexion, $usuario, $fila['anio'], $minutosADescontar, $idPermisoEmpleado);
				}
			}
		}
	}
	// ****************************************guardar registro de liquidacion de vacaciones ******************************************************************
	public function guardarLiquidacionVacaciones($conexion, $identificador,$minutosLiquidados, $anio_liquidados, $idDatosContrato, $identificadorRegistro, $tiempoProporcional){
		$res = $conexion->ejecutarConsultaLOGS("	INSERT INTO
													g_vacaciones.liquidacion_vacaciones(
											            identificador,
														minutos_liquidados,
														anios_liquidados,
											            id_datos_contrato,
														identificador_registro,
														proporcional_mes_final)
											    VALUES ('" . $identificador . "',
														'" . $minutosLiquidados . "',
														'" . $anio_liquidados . "',
														" . $idDatosContrato . ",
														'" . $identificadorRegistro . "',
														'" . $tiempoProporcional . "' )
                                             RETURNING 
												id_liquidacion_vacaciones;");
		return $res;
	}
	
	// ****************************************guardar detalle el descuento de tiempo******************************************************************
	public function guardarDetalleDescuentoLiquidar($conexion, $idLiquidacionVacaciones, $numCur, $fechaLiquidacion, $identificadorLiquidacion){
	
		$conexion->ejecutarConsultaLOGS("
											UPDATE 
													g_vacaciones.liquidacion_vacaciones
   											SET 
													numero_cur=".$numCur.", 
													estado = 'Liquidado',
													fecha_liquidacion='".$fechaLiquidacion."',
													fecha_registro_liquidacion=now(),
													identificador_liquidacion = '".$identificadorLiquidacion."'
 											WHERE   id_liquidacion_vacaciones = ".$idLiquidacionVacaciones." ;");
		
	}
	//*****************************************obtener informacion de funcionario de accion de personal***************************************************************************
	public function obtenerInformacioAccionPersonalFuncionario($conexion , $identificador){
		 $sql = "SELECT
				     ficha_empleado.identificador AS ficha_empleado_identificador,
				     ficha_empleado.nombre AS ficha_empleado_nombre,
				     ficha_empleado.apellido AS ficha_empleado_apellido,
				     datos_contrato.id_datos_contrato AS datos_contrato_id_datos_contrato,
				     datos_contrato.identificador AS datos_contrato_identificador,
				     datos_contrato.gestion AS datos_contrato_gestion,
				     datos_contrato.coordinacion AS datos_contrato_coordinacion,
				     datos_contrato.direccion AS datos_contrato_direccion,
				     datos_contrato.gestion AS datos_contrato_gestion,
					 datos_contrato.nombre_puesto datos_contrato_puesto,
				     datos_contrato.provincia AS datos_contrato_provincia,
				     datos_contrato.grupo_ocupacional AS datos_contrato_grupo_ocupacional,
				     datos_contrato.remuneracion AS datos_contrato_remuneracion,
				     datos_contrato.partida_presupuestaria AS datos_contrato_partida_presupuestaria,
				     datos_contrato.partida_individual AS datos_contrato_partida_individual
				FROM
			    	 g_uath.ficha_empleado ficha_empleado 	
					 INNER JOIN  g_uath.datos_contrato datos_contrato ON ficha_empleado.identificador = datos_contrato.identificador and
					 datos_contrato.estado = 1 and
					 ficha_empleado.identificador = '".$identificador."';";
		
		
		$consulta = $conexion->ejecutarConsultaLOGS( $sql );
		return $consulta;
	}
	
	//************************** Datos firma electrónica EN DATA BASE  ******************************************
	public function obtenerFirmaElectronica($conexion,$identificador)
	{
		$consulta = "SELECT
                        id_firma_electronica,
                    	nombre_firma,
                        ubicacion,
                        razon,
                        info_contacto,
                        ruta_certificado,
                        clave
                    FROM
                    	g_certificados_uath.firma_electronica
                    WHERE
                        identificador = '" . $identificador . "' and
                        estado = 'activo';";
		
		return $conexion->ejecutarConsultaLOGS( $consulta );
	}
	
	// ************************** Datos firma electrónica ******************************************
	public function obtenerDatosCertificado($conexion,$identificador)
	{
		$consulta = pg_fetch_assoc($this->obtenerFirmaElectronica($conexion,$identificador));
		$id = rtrim($identificador);
		$scr = crc32($id);
		$key = hash('sha256', $scr);
		$claveCifrada = $consulta['clave'];
		$cifrar = new ControladorComplementos();
		$password = $cifrar->desencriptarClave($claveCifrada, $key);
		$certificate = 'file://' . $consulta['ruta_certificado'];
		$info = array(
			'Name' => $consulta['nombre_firma'],
			'Location' => $consulta['ubicacion'],
			'Reason' => $consulta['razon'],
			'ContactInfo' => $consulta['info_contacto']
		);
		$datos = array();
		$datos['rutaCertificado'] = $certificate;
		$datos['info'] = $info;
		$datos['password'] = $password;
		
		return $datos;
	}
	// --------------------------devolver responsable de la zona u planta central--------------------------------------------------------------------
	public function devolverResponsable($conexion, $idArea){
		$sqlScript = "select 
							nombre ||' '|| apellido as funcionario, res.identificador
						from 
							g_estructura.responsables res,
							g_uath.ficha_empleado fe
						where 
							res.identificador = fe.identificador and
							res.id_area = '".$idArea."' and 
							res.responsable = 'TRUE' and
							res.activo = 1 and
							res.estado = 1;";
		$res = $conexion->ejecutarConsulta($sqlScript);
		return $res;
	}
	// ------------------------devolver responsable de registro control----------------------------------------------------------------------
	public function devolverResponsableRegistro($conexion, $idArea){
	    $sqlScript = "select
							nombre ||' '|| apellido as funcionario, rrc.identificador
						from
							g_vacaciones.responsable_registro_control rrc,
							g_uath.ficha_empleado fe
						where
							rrc.identificador = fe.identificador and
							rrc.id_area = '".$idArea."' and
							rrc.estado = TRUE;";
		$res = $conexion->ejecutarConsulta($sqlScript);
		return $res;
	}
	
	//*****************************actualizar si requiere firma manual o no****************************************
	public function actualizarFirmaManualPermiso($conexion, $id_solicitud_permiso, $estado_firma){
		$sqlScript = "Update
						 	g_vacaciones.permiso_empleado
				    set
							firma_manual='" . $estado_firma . "'
					where
							id_permiso_empleado='" . $id_solicitud_permiso . "';";
		
		$res = $conexion->ejecutarConsultaLOGS($sqlScript);
		return $res;
	}
	//************************encerar saldo de vacaciones de funcionario****************************************	
	public function encerarSaldoFuncionario($conexion, $identificador, $idLiquidacion){
		
		$mensaje ="Saldo encerado por liquidacion de vacaciones con id de liquidación ".$idLiquidacion;
		$sqlScript2 = "UPDATE
									g_vacaciones.minutos_disponibles_funcionarios
								SET
									minutos_disponibles = 0,
									activo = FALSE,
                                    observacion='" . $mensaje . "'
								WHERE
									identificador='" . $identificador . "' and minutos_disponibles > 0;";
		
		$conexion->ejecutarConsultaLOGS($sqlScript2);
		
		$sqlScript2 = "UPDATE
									g_vacaciones.tiempo_disponible_funcionarios
								SET
									minutos_disponibles = 0,
									activo = FALSE,
                                    observacion='" . $mensaje . "'
								WHERE
									identificador='" . $identificador . "' and minutos_disponibles > 0;";
		
		$conexion->ejecutarConsultaLOGS($sqlScript2);
	}
	//*************************guardar historico de tiempo liquidado*******************************************
	public function insertarDetalleLiquidacion($conexion, $idLiquidacion, $anio, $mes, $minutos){
		
		$sqlScript2 = "INSERT INTO g_vacaciones.detalle_liquidacion_vacaciones(
                                   id_liquidacion_vacaciones, anio, mes, minutos_utilizados)
                       VALUES ($idLiquidacion,$anio, '$mes' ,$minutos);";
		
		$res = $conexion->ejecutarConsultaLOGS($sqlScript2);
		return $res;
	}
	
	// ------------------------devolver detalle saldo liquidado----------------------------------------------------------------------
	public function devolverDetalleLiquidacion($conexion, $idLiquidacion){
		$sqlScript = "select
							anio, mes, minutos_utilizados
						from
							g_vacaciones.detalle_liquidacion_vacaciones
						where
							id_liquidacion_vacaciones=".$idLiquidacion.";";
		$res = $conexion->ejecutarConsulta($sqlScript);
		return $res;
	}
	//**********************************************************************************************************	
}

