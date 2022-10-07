<?php

class ControladorProgramasControlOficial{
	
/******** MURCIÉLAGOS HEMATÓFAGOS ********/
	
	//Archivo listaControlMurcielagos
	public function listarControlMurcielagosHematofagos ($conexion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programas_control_oficial.vista_busqueda_murcielagos_hematofagos
											WHERE
												estado = 'activo';");
	
		return $res;
	}
	
	public function buscarControlMurcielagosHematofagos ($conexion, $numSolicitud, $fecha, $nombrePredio, $nombrePropietario,
											$idProvincia, $idCanton, $idParroquia, $sitio, $idOficina, 
											$nuevaInspeccion, $estado){
		
		$numSolicitud = $numSolicitud!="" ? "'%" . $numSolicitud . "%'" : "null";
		$fecha = $fecha!="" ? "'" . $fecha . "'" : "null";
		$nombrePredio = $nombrePredio!="" ? "'%" . $nombrePredio . "%'" : "null";
		$nombrePropietario = $nombrePropietario!="" ? "'%" . $nombrePropietario . "%'" : "null";
		$idProvincia = $idProvincia!="" ? "" . $idProvincia . "" : "null";
		$idCanton = $idCanton!="" ? "" . $idCanton . "" : "null";
		$idParroquia = $idParroquia!="" ? "" . $idParroquia . "" : "null";
		$sitio = $sitio!="" ? "'%" . $sitio . "%'" : "null";
		$idOficina = $idOficina!="" ? "" . $idOficina . "" : "null";
		$nuevaInspeccion = $nuevaInspeccion!="" ? "'" . $nuevaInspeccion . "'" : "null";
		$estado = $estado!="" ? "'" . $estado . "'" : "null";		
		
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programas_control_oficial.busqueda_murcielagos_hematofagos(
												$numSolicitud, $fecha, $nombrePredio,
												$nombrePropietario, $idProvincia, $idCanton,
												$idParroquia, $sitio, $idOficina,
												$nuevaInspeccion, $estado)
											ORDER BY
												fecha_creacion desc;");
												
		return $res;
	}
	
	//Archivo guardarControlMurcielagosHematofagos
	public function nuevoControlMurcielagosHematofagos ($conexion, $identificador, $numSolicitud, 
										            $fecha, $nombrePredio, $nombrePropietario, $personaRefugio, 
													$idTipoRefugio, $refugio,
										            $idProvincia, $provincia, $idCanton, $canton, $idParroquia, $parroquia, 
										            $sitio, $id_oficina, $oficina, $x, $y, $z, $altitud,
													$latitud, $longitud, $zona, $imagenMapa, $informe){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO 
												g_programas_control_oficial.murcielagos_hematofagos(
										            identificador, fecha_creacion, num_solicitud, 
										            fecha, nombre_predio, nombre_propietario, persona_refugio, 
													id_tipo_refugio, tipo_refugio,
										            id_provincia, provincia, id_canton, canton, id_parroquia, parroquia, 
										            sitio, id_oficina, oficina, utm_x, utm_y, utm_z, altitud,
													latitud, longitud, zona, estado, imagen_mapa, ruta_informe)
										    VALUES ('$identificador', now(), '$numSolicitud',  
										            '$fecha', '$nombrePredio', '$nombrePropietario', '$personaRefugio',
													$idTipoRefugio, '$refugio',
										            $idProvincia, '$provincia', $idCanton, '$canton', $idParroquia, '$parroquia', 
										            '$sitio', $id_oficina, '$oficina', '$x', '$y', '$z', '$altitud',
													'$latitud', '$longitud', '$zona', 'activo', '$imagenMapa', '$informe')
											RETURNING id_murcielagos_hematofagos;");
		
		return $res;
	}
	
	public function generarNumeroControlMurcielagosHematofagos($conexion, $codigo){
	
		$res = $conexion->ejecutarConsulta("SELECT
												MAX(num_solicitud) as num_solicitud
											FROM
												g_programas_control_oficial.murcielagos_hematofagos
											WHERE 
												num_solicitud LIKE '%$codigo%';");
		return $res;
	}
	
	//Archivo abrirControlMurcielagosHematofagos
	public function abrirControlMurcielagosHematofagos ($conexion, $idMurcielagosHematofagos){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programas_control_oficial.murcielagos_hematofagos
											WHERE
												id_murcielagos_hematofagos = $idMurcielagosHematofagos;");
	
		return $res;
	}
	
	public function listarInspeccionMurcielagosHematofagos($conexion, $idMurcielagosHematofagos){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programas_control_oficial.murcielagos_hematofagos_inspecciones
											WHERE
												id_murcielagos_hematofagos = $idMurcielagosHematofagos and
												estado not in ('eliminado')
											ORDER BY
												num_inspeccion asc;");
	
		return $res;
	}
	
	//Archivo modificarControlMurcielagosHematofagos
	public function modificarControlMurcielagosHematofagos($conexion, $idMurcielagosHematofagos, $identificador, 
															 $fecha, $nombre_predio, 
															 $nombre_propietario, $persona_refugio,
															 $idTipoRefugio, $tipoRefugio,															 
															 $sitio, $id_oficina, $oficina, $utm_x, $utm_y, $utm_z,
															 $latitud, $longitud, $zona){
	
		$res = $conexion->ejecutarConsulta("UPDATE 
												g_programas_control_oficial.murcielagos_hematofagos
   											SET 
												fecha='$fecha', 
												nombre_predio='$nombre_predio', 
												nombre_propietario='$nombre_propietario', 
						       					persona_refugio='$persona_refugio', 
												id_tipo_refugio=$idTipoRefugio,
												tipo_refugio='$tipoRefugio',
												sitio='$sitio', 
												id_oficina=$id_oficina, 
						       					oficina='$oficina', 
												utm_x='$utm_x', 
												utm_y='$utm_y', 
												utm_z='$utm_z', 
												latitud='$latitud', 
												longitud='$longitud', 
						       					zona='$zona',
												identificador_modificacion='$identificador', 
												fecha_modificacion=now()
 											WHERE 
												id_murcielagos_hematofagos=$idMurcielagosHematofagos;");
	
		return $res;
	}
	
	//Archivo guardarInspeccionMurcielagosHematofagos
	public function buscarInspeccionMurcielagosHematofagos($conexion, $idMurcielagosHematofagos, $numInspeccion){
	
		$res = $conexion->ejecutarConsulta("SELECT 
												*
											FROM 
												g_programas_control_oficial.murcielagos_hematofagos_inspecciones
											WHERE
												id_murcielagos_hematofagos = $idMurcielagosHematofagos and
												num_inspeccion = '$numInspeccion';");
	
		return $res;
	}
	
	public function nuevaInspeccionMurcielagosHematofagos($conexion, $idMurcielagosHematofagos, $identificador, 
															$numInspeccion, $fechaInspeccion, 
															$presenciaMH, $controlRealizado, $numMachos,
															$numHembras, $observaciones){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO 
												g_programas_control_oficial.murcielagos_hematofagos_inspecciones(
										            id_murcielagos_hematofagos, identificador, fecha_creacion, 
										            num_inspeccion, fecha_inspeccion,  
										            presencia_mh, control_realizado, num_machos, 
										            num_hembras, observaciones, estado)
										    VALUES ($idMurcielagosHematofagos, '$identificador', now(),
													'$numInspeccion', '$fechaInspeccion',
													'$presenciaMH', '$controlRealizado', $numMachos,
													$numHembras, '$observaciones', 'creado')
											RETURNING id_murcielagos_hematofagos_inspecciones;");
															
		return $res;
	}
	
	public function imprimirLineaInspeccionMurcielagosHematofagos($idMurcielagosHematofagosInspecciones, $idMurcielagosHematofagos, 
																	$numInspeccion, $fechaInspeccion, 
																	$presenciaMH, $controlRealizado, 
																	$numMachos, $numHembras, $observaciones, $ruta){
	
		return '<tr id="R' . $idMurcielagosHematofagosInspecciones . '">' .
					'<td width="30%">' .
						$numInspeccion .
					'</td>' .
					'<td width="30%">' .
						$fechaInspeccion.
					'</td>' .
					'<td width="10%">' .
						$presenciaMH .
					'</td>
					<td width="10%">' .
						$controlRealizado .
					'</td>
					<td>'.
						$numMachos.
					'</td>
					<td>'.
						$numHembras.
					'</td>
					<td>' .
						'<form id="borrarRegistro" class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarInspeccionMurcielagosHematofagos" data-destino="detalleItem" data-accionEnExito="NADA" >' .
							'<input type="hidden" name="idMurcielagosHematofagosInspecciones" value="' . $idMurcielagosHematofagosInspecciones . '" >' .
							'<button class="icono" type="submit" ></button>' .
						'</form>' .
					'</td>' .
				'</tr>';
	}
	
	public function imprimirLineaInspeccionMurcielagosHematofagosConsulta($idMurcielagosHematofagosInspecciones, $idMurcielagosHematofagos,
			$numInspeccion, $fechaInspeccion,
			$presenciaMH, $controlRealizado,
			$numMachos, $numHembras, $observaciones, $ruta){
	
				return '<tr id="R' . $idMurcielagosHematofagosInspecciones . '">' .
						'<td width="30%">' .
						$numInspeccion .
						'</td>' .
						'<td width="30%">' .
						$fechaInspeccion.
						'</td>' .
						'<td width="10%">' .
						$presenciaMH .
						'</td>
					<td width="10%">' .
						$controlRealizado .
						'</td>
					<td>'.
						$numMachos.
						'</td>
					<td>'.
						$numHembras.
					'</td>
					<td>'.
						$observaciones.
					'</td>
						</tr>';
	}
		
	//Archivo eliminarInspeccionMurcielagosHematofagos
	public function eliminarInspeccionMurcielagosHematofagos($conexion, $idMurcielagosHematofagosInspecciones, $identificador){
	
		$res = $conexion->ejecutarConsulta("DELETE FROM 
												g_programas_control_oficial.murcielagos_hematofagos_inspecciones
								   			WHERE 
												id_murcielagos_hematofagos_inspecciones=$idMurcielagosHematofagosInspecciones;");
	
		return $res;
	}
	
	//Archivo guardarPlanificacionInspeccionMurcielagosHematofagos
	public function planificacionNuevaInspeccionMurcielagosHematofagos($conexion, $idMurcielagosHematofagos, 
													$identificador, $nuevaInspeccion, $nuevaFechaInspeccion){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_programas_control_oficial.murcielagos_hematofagos
											SET
												nueva_inspeccion='$nuevaInspeccion',
												fecha_nueva_inspeccion='$nuevaFechaInspeccion',
												identificador_modificacion='$identificador',
												fecha_modificacion=now(),
												estado='inspeccion'
											WHERE
												id_murcielagos_hematofagos=$idMurcielagosHematofagos;");

		return $res;
	}
	
	public function cierreControlMurcielagosHematofagos($conexion, $idMurcielagosHematofagos, $identificador,
														$nuevaInspeccion){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_programas_control_oficial.murcielagos_hematofagos
											SET
												nueva_inspeccion='$nuevaInspeccion',
												fecha_nueva_inspeccion=null,
												identificador_cierre='$identificador',
												fecha_cierre=now(),
												estado='cerrado'
											WHERE
												id_murcielagos_hematofagos=$idMurcielagosHematofagos;");

		return $res;
	}
	
	//Archivo subirArchivo
	public function actualizarImagenMapaMurcielagosHematofagos($conexion,$idMurcielagosHematofagos,$rutaArchivo){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_programas_control_oficial.murcielagos_hematofagos
											SET
												imagen_mapa='".$rutaArchivo."'
											WHERE
												id_murcielagos_hematofagos=".$idMurcielagosHematofagos.";");
	
		return $res;
	
	}
	
	public function actualizarInformeMurcielagosHematofagos($conexion,$idMurcielagosHematofagos,$rutaArchivo){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_programas_control_oficial.murcielagos_hematofagos
											SET
												ruta_informe='".$rutaArchivo."'
											WHERE
												id_murcielagos_hematofagos=".$idMurcielagosHematofagos.";");
	
		return $res;
	
	}
	
	
	/******** CONTROL DE VECTORES ********/
	
	//Archivo listaControlVectores
	public function buscarControlVectores ($conexion, $numSolicitud, $fecha, $nombrePredio, $nombrePropietario,
			$idProvincia, $idCanton, $idParroquia, $sitio, $estado){
	
				$numSolicitud = $numSolicitud!="" ? "'%" . $numSolicitud . "%'" : "null";
				$fecha = $fecha!="" ? "'" . $fecha . "'" : "null";
				$nombrePredio = $nombrePredio!="" ? "'%" . $nombrePredio . "%'" : "null";
				$nombrePropietario = $nombrePropietario!="" ? "'%" . $nombrePropietario . "%'" : "null";
				$idProvincia = $idProvincia!="" ? "" . $idProvincia . "" : "null";
				$idCanton = $idCanton!="" ? "" . $idCanton . "" : "null";
				$idParroquia = $idParroquia!="" ? "" . $idParroquia . "" : "null";
				$sitio = $sitio!="" ? "'%" . $sitio . "%'" : "null";
				$estado = $estado!="" ? "'" . $estado . "'" : "null";
	
				$res = $conexion->ejecutarConsulta("SELECT
														*
													FROM
														g_programas_control_oficial.busqueda_control_vectores(
														$numSolicitud, $fecha, $nombrePredio,
														$nombrePropietario, $idProvincia, $idCanton,
														$idParroquia, $sitio, $estado)
													ORDER BY
														fecha_creacion desc;");
	
				return $res;
	}
	
	//Archivo guardarControlVectores
	public function nuevoControlVectores ($conexion, $identificador, $numeroSolicitud, $fecha,  
											$duracion, $idFaseLunar, $faseLunar, $fechaDesde, $fechaHasta, 
											$nombrePredio, $nombrePropietario, $idProvincia, $provincia, 
											$idCanton, $canton, $idParroquia, $parroquia, $sitio, 
											$idSitioCaptura, $nombreSitioCaptura, 
											$coberturaCorral, $x, $y, $z, $altitud, $latitud, $longitud, 
											$zona, $imagenMapa, $informe){										

			$res = $conexion->ejecutarConsulta("INSERT INTO
												g_programas_control_oficial.control_vectores(
										            identificador, fecha_creacion, num_solicitud, fecha, id_fase_lunar, 
										            fase_lunar, duracion, fecha_desde, fecha_hasta, nombre_predio, 
										            nombre_propietario, id_provincia, provincia, id_canton, canton, 
										            id_parroquia, parroquia, sitio, id_sitio_captura, sitio_captura,
													cobertura_corral, utm_x, utm_y, utm_z, altitud, 
										            latitud, longitud, zona, estado, imagen_mapa, ruta_informe)
											VALUES 
												('$identificador', now(), '$numeroSolicitud', '$fecha', $idFaseLunar, 
												'$faseLunar', '$duracion', '$fechaDesde', '$fechaHasta', '$nombrePredio', 
												'$nombrePropietario', $idProvincia, '$provincia', $idCanton, '$canton', 
												$idParroquia, '$parroquia','$sitio', $idSitioCaptura, '$nombreSitioCaptura',
												'$coberturaCorral', '$x', '$y', '$z', '$altitud',
												'$latitud', '$longitud', '$zona', 'activo', '$imagenMapa', '$informe')
											RETURNING id_control_vectores;");
			
		return $res;
	}
	
	public function generarNumeroControlVectores($conexion, $codigo){
		
		$res = $conexion->ejecutarConsulta("SELECT
												MAX(num_solicitud) as num_solicitud
											FROM
												g_programas_control_oficial.control_vectores
											WHERE
												num_solicitud LIKE '%$codigo%';");
		return $res;
	}
	
	//Archivo abrirControlVectores
	public function abrirControlVectores ($conexion, $idControlVectores){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programas_control_oficial.control_vectores
											WHERE
												id_control_vectores = $idControlVectores;");
	
		return $res;
	}
	
	
	public function listarEspeciesAtacadasControlVectores($conexion, $idControlVectores){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programas_control_oficial.control_vectores_especies_atacadas
											WHERE
												id_control_vectores = $idControlVectores
											ORDER BY
												id_especie asc;");
	
		return $res;
	}
	
	public function listarQuiropterosCapturadosControlVectores($conexion, $idControlVectores){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programas_control_oficial.control_vectores_quiropteros_capturados
											WHERE
												id_control_vectores = $idControlVectores
											ORDER BY
												id_quiroptero asc;");
	
		return $res;
	}
	
	
	public function listarQuiropterosTratadosControlVectores($conexion, $idControlVectores){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programas_control_oficial.control_vectores_quiropteros_tratados
											WHERE
												id_control_vectores = $idControlVectores;");
	
		return $res;
	}
	public function listarSitiosCapturaControlVectores($conexion, $idControlVectores){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programas_control_oficial.control_vectores_sitios_captura
											WHERE
												id_control_vectores = $idControlVectores
											ORDER BY
												malla, id_especie asc;");
	
		return $res;
	}
	
	//Archivo modificarControlVectores
	public function modificarControlVectores( $conexion, $idControlVectores, $identificador, 
												$fecha, $idFaseLunar, $faseLunar, $duracion,
												$fechaDesde, $fechaHasta, $nombrePredio,
												$nombrePropietario, 
												$sitio, $x, $y, $z, $altitud, $latitud, $longitud, $zona){
	
				$res = $conexion->ejecutarConsulta("UPDATE 
														g_programas_control_oficial.control_vectores
													SET 
													    fecha='$fecha', 
													    id_fase_lunar=$idFaseLunar, 
													    fase_lunar='$faseLunar', 
													    duracion='$duracion', 
													    fecha_desde='$fechaDesde', 
													    fecha_hasta='$fechaHasta', 
													    nombre_predio='$nombrePredio', 
													    nombre_propietario='$nombrePropietario', 
													    sitio='$sitio', 
													    utm_x='$x', 
													    utm_y='$y', 
													    utm_z='$z', 
													    altitud='$altitud', 
														latitud='$latitud', 
													    longitud='$longitud', 
													    zona='$zona', 
													    identificador_modificacion='$identificador', 
													    fecha_modificacion=now()
													WHERE 
														id_control_vectores=$idControlVectores;");
	
				return $res;
	}
	
	//Archivo guardarEspecieAtacadaControlVectores
	public function buscarEspecieAtacadaControlVectores($conexion, $idControlVectores, $nombreEspecie){
	
		
		$res = $conexion->ejecutarConsulta("SELECT 
												*
  											FROM 
												g_programas_control_oficial.control_vectores_especies_atacadas
											WHERE
												id_control_vectores = $idControlVectores and
												upper(especie) = upper('$nombreEspecie');");
	
		return $res;
	}
	
	public function nuevaEspecieAtacadaControlVectores($conexion, $idControlVectores, $identificador,
														 $idEspecie, $nombreEspecie, $especieExistente,
														 $especieMordeduras){
														 	
		$res = $conexion->ejecutarConsulta("INSERT INTO 
												g_programas_control_oficial.control_vectores_especies_atacadas(
									            id_control_vectores, identificador, fecha_creacion, 
									            id_especie, especie, existencia_predio, 
												animales_mordeduras)
										    VALUES ($idControlVectores, '$identificador', now(), 
										            $idEspecie, '$nombreEspecie', $especieExistente, $especieMordeduras)
											RETURNING id_control_vectores_especie_atacada;");
					
		return $res;
	}
	
	public function imprimirLineaEspecieAtacadaControlVectores($idControlVectoresEspecieAtacada,
																$idControlVectores, $nombreEspecie, 
																$especieExistente, $especieMordeduras, 
																$ruta){
	
				return '<tr id="R' . $idControlVectoresEspecieAtacada . '">' .
						'<td width="30%">' .
						$nombreEspecie .
						'</td>' .
						'<td width="30%">' .
						$especieExistente.
						'</td>' .
						'<td width="10%">' .
						$especieMordeduras.
						'</td>
					<td>' .
						'<form id="borrarRegistro" class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarEspecieAtacadaControlVectores" data-destino="detalleItem" data-accionEnExito="NADA" >' .
						'<input type="hidden" id="idControlVectoresEspecieAtacada" name="idControlVectoresEspecieAtacada" value="' . $idControlVectoresEspecieAtacada . '" >' .
						'<button class="icono" type="submit" ></button>' .
						'</form>' .
						'</td>' .
						'</tr>';
	}
	
	public function imprimirLineaEspecieAtacadaControlVectoresConsulta($idControlVectoresEspecieAtacada,
			$idControlVectores, $nombreEspecie,
			$especieExistente, $especieMordeduras,
			$ruta){
	
				return '<tr id="R' . $idControlVectoresEspecieAtacada . '">' .
						'<td width="30%">' .
						$nombreEspecie .
						'</td>' .
						'<td width="30%">' .
						$especieExistente.
						'</td>' .
						'<td width="10%">' .
						$especieMordeduras.
						'</td>' .
						'</tr>';
	}
	
	//Archivo eliminarEspecieAtacadaControlVectores
	public function eliminarEspecieAtacadaControlVectores($conexion, $idControlVectoresEspecieAtacada){

		$res = $conexion->ejecutarConsulta("DELETE FROM 
												g_programas_control_oficial.control_vectores_especies_atacadas
											WHERE 
												id_control_vectores_especie_atacada=$idControlVectoresEspecieAtacada;");
	
		return $res;
	}
	
	//Archivo guardarQuiropteroCapturadoControlVectores
	public function buscarQuiropteroCapturadoControlVectores($conexion, $idControlVectores, $nombreQuiropteros){
	
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programas_control_oficial.control_vectores_quiropteros_capturados
											WHERE
												id_control_vectores = $idControlVectores and
												quiroptero = '$nombreQuiropteros';");
	
		return $res;
	}
	
	public function nuevoQuiropteroCapturadoControlVectores($conexion, $idControlVectores, $identificador,
			$idQuiropteros, $quiropteros, $numeroQuiropteros){
	
			$res = $conexion->ejecutarConsulta("INSERT INTO 
													g_programas_control_oficial.control_vectores_quiropteros_capturados(
												    	id_control_vectores, identificador, fecha_creacion,
												        id_quiroptero, quiroptero, num_quiropteros)
											    VALUES ($idControlVectores, '$identificador', now(),
											            $idQuiropteros, '$quiropteros', $numeroQuiropteros)
												RETURNING 
													id_control_vectores_quiropteros_capturados;");
							
			return $res;
	}
	
	public function imprimirLineaQuiropterosCapturadosControlVectores($idControlVectoresQuiropterosCapturados,
																$idControlVectores, $nombreQuiropteros, 
																$numeroQuiropteros, $ruta){
	
				return '<tr id="R' . $idControlVectoresQuiropterosCapturados . '">' .
						'<td width="15%">' .
						$nombreQuiropteros .
						'</td>' .
						'<td width="15%">' .
						$numeroQuiropteros.
						'</td>
					<td>' .
						'<form id="borrarRegistroQC" class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarQuiropterosCapturadosControlVectores" data-destino="detalleItem" data-accionEnExito="NADA" >' .
						'<input type="hidden" name="idControlVectores" value="' . $idControlVectores . '" >' .
						'<input type="hidden" name="idControlVectoresQuiropterosCapturados" value="' . $idControlVectoresQuiropterosCapturados . '" >' .
						'<button class="icono" type="submit" ></button>' .
						'</form>' .
						'</td>' .
						'</tr>';
	}
	
	public function imprimirLineaQuiropterosCapturadosControlVectoresConsulta($idControlVectoresQuiropterosCapturados,
			$idControlVectores, $nombreQuiropteros,
			$numeroQuiropteros, $ruta){
	
				return '<tr id="R' . $idControlVectoresQuiropterosCapturados . '">' .
						'<td width="15%">' .
						$nombreQuiropteros .
						'</td>' .
						'<td width="15%">' .
						$numeroQuiropteros.
						'</td>
						</tr>';
	}
	
	//Archivo eliminarQuiropterosCapturadosControlVectores
	public function eliminarQuiropterosCapturadosControlVectores($conexion, $idControlVectoresQuiropterosCapturados){
	
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_programas_control_oficial.control_vectores_quiropteros_capturados
											WHERE
												id_control_vectores_quiropteros_capturados=$idControlVectoresQuiropterosCapturados;");
	
		return $res;
	}
	
	//Archivo guardarQuiropteroTratadoControlVectores
	public function cantidadQuiropterosCapturadosControlVectores($conexion, $idControlVectores){
	
		$res = $conexion->ejecutarConsulta("SELECT
												SUM(num_quiropteros) as quiropteros_capturados
											FROM
												g_programas_control_oficial.control_vectores_quiropteros_capturados
											WHERE
												id_control_vectores = $idControlVectores
											GROUP BY
												id_control_vectores;");
	
		return $res;
	}
	
	public function buscarQuiropteroTratadoControlVectores($conexion, $idControlVectores){
	
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programas_control_oficial.control_vectores_quiropteros_tratados
											WHERE
												id_control_vectores = $idControlVectores;");
	
		return $res;
	}
	
	public function nuevoQuiropteroTratadoControlVectores($conexion, $idControlVectores, $identificador,
			$numeroVampirosTratados, $numeroVampirosNoTratados, $numeroVampirosLaboratorio){
	
				$res = $conexion->ejecutarConsulta("INSERT INTO
														g_programas_control_oficial.control_vectores_quiropteros_tratados(
															id_control_vectores, identificador, fecha_creacion, 
															vampiros_tratados, vampiros_no_tratados, vampiros_laboratorio)
													VALUES ($idControlVectores, '$identificador', now(),
															$numeroVampirosTratados, $numeroVampirosNoTratados, $numeroVampirosLaboratorio)
													RETURNING
														id_control_vectores_quiropteros_tratados;");
							
						return $res;
	}
	
	public function imprimirLineaQuiropterosTratadosControlVectores($idControlVectoresQuiropterosTratados,
														$idControlVectores, $numeroVampirosTratados, $numeroVampirosNoTratados,
														$numeroVampirosLaboratorio, $ruta){
	
				return '<tr id="R' . $idControlVectoresQuiropterosTratados . '">' .
						'<td width="15%">' .
						$numeroVampirosTratados .
						'</td>' .
						'<td width="15%">' .
						$numeroVampirosNoTratados.
						'</td>
						<td width="15%">' .
						$numeroVampirosLaboratorio.
						'</td>
						<td>' .
						'<form id="borrarRegistro" class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarQuiropterosTratadosControlVectores" data-destino="detalleItem" data-accionEnExito="NADA" >' .
						'<input type="hidden" name="idControlVectoresQuiropterosTratados" value="' . $idControlVectoresQuiropterosTratados . '" >' .
						'<input type="hidden" name="idControlVectores" value="' . $idControlVectores . '" >' .
						'<button class="icono" type="submit" ></button>' .
						'</form>' .
						'</td>' .
						'</tr>';
	}
	
	public function imprimirLineaQuiropterosTratadosControlVectoresConsulta($idControlVectoresQuiropterosTratados,
			$idControlVectores, $numeroVampirosTratados, $numeroVampirosNoTratados,
			$numeroVampirosLaboratorio, $ruta){
	
				return '<tr id="R' . $idControlVectoresQuiropterosTratados . '">' .
						'<td width="15%">' .
						$numeroVampirosTratados .
						'</td>' .
						'<td width="15%">' .
						$numeroVampirosNoTratados.
						'</td>
						<td width="15%">' .
						$numeroVampirosLaboratorio.
						'</td>
						</tr>';
	}
	
	//Archivo eliminarQuiropterosTratadosControlVectores
	public function eliminarQuiropterosTratadosControlVectores($conexion, $idControlVectoresQuiropterosTratados){
	
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_programas_control_oficial.control_vectores_quiropteros_tratados
											WHERE
												id_control_vectores_quiropteros_tratados=$idControlVectoresQuiropterosTratados;");
	
		return $res;
	}
	
	public function eliminarTotalQuiropterosTratadosControlVectores($conexion, $idControlVectores){
	
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_programas_control_oficial.control_vectores_quiropteros_tratados
											WHERE
												id_control_vectores=$idControlVectores;");
	
		return $res;
	}
	
	
	//Archivo guardarSitioCapturaControlVectores
	public function buscarSitioCapturaControlVectores($conexion, $idControlVectores, $malla, $idEspecieMalla){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programas_control_oficial.control_vectores_sitios_captura
											WHERE
												id_control_vectores = $idControlVectores and
												malla = '$malla' and
												id_especie = $idEspecieMalla;");
	
		return $res;
	}	
	
	public function nuevoSitioCapturaControlVectores($conexion, $idControlVectores, $identificador,
			$malla, $idEspecieMalla, $especieMalla, $numeroCapturadosMalla, $observacionesMalla){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO 
														g_programas_control_oficial.control_vectores_sitios_captura(
												            id_control_vectores, identificador, fecha_creacion, 
												            malla, id_especie, especie,  
												            num_capturas_malla, observaciones_malla)
    												VALUES ($idControlVectores, '$identificador', now(), 
												            '$malla', $idEspecieMalla, '$especieMalla',
															 $numeroCapturadosMalla, '$observacionesMalla')
													RETURNING 
														id_control_vectores_sitio_captura;");
					
		return $res;
	}
	
	public function imprimirLineaSitioCapturaControlVectores($idControlVectoresSitioCaptura,
																$idControlVectores, $malla, $especieMalla,
																$numeroCapturadosMalla, $observacionesMalla, $ruta){
	
		return '<tr id="R' . $idControlVectoresSitioCaptura . '">' .
				'<td width="15%">' .
				$malla .
				'</td>' .
				'<td width="15%">' .
				$especieMalla.
				'</td>
				<td width="15%">' .
				$numeroCapturadosMalla .
				'</td>' .
				'<td width="15%">' .
				$observacionesMalla.
				'</td>
			<td>' .
				'<form id="borrarRegistro" class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarSitioCapturaControlVectores" data-destino="detalleItem" data-accionEnExito="NADA" >' .
				'<input type="hidden" name="idControlVectoresSitioCaptura" value="' . $idControlVectoresSitioCaptura . '" >' .
				'<button class="icono" type="submit" ></button>' .
				'</form>' .
				'</td>' .
				'</tr>';
	}
	
	public function imprimirLineaSitioCapturaControlVectoresConsulta($idControlVectoresSitioCaptura,
			$idControlVectores, $malla, $especieMalla,
			$numeroCapturadosMalla, $observacionesMalla, $ruta){
	
				return '<tr id="R' . $idControlVectoresSitioCaptura . '">' .
						'<td width="15%">' .
						$malla .
						'</td>' .
						'<td width="15%">' .
						$especieMalla.
						'</td>
				<td width="15%">' .
					$numeroCapturadosMalla .
					'</td>' .
					'<td width="15%">' .
					$observacionesMalla.
					'</td>
					</tr>';
	}
	
	//Archivo eliminarSitioCapturaControlVectores
	public function eliminarSitioCapturaControlVectores($conexion, $idControlVectoresSitioCaptura){
	
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_programas_control_oficial.control_vectores_sitios_captura
											WHERE
												id_control_vectores_sitio_captura=$idControlVectoresSitioCaptura;");
	
		return $res;
	}
	
	
	//Archivo guardarCierreControlVectores
	public function cierreControlVectores($conexion, $idControlVectores, $identificador,
													$observaciones){
	
		$res = $conexion->ejecutarConsulta("UPDATE 
												g_programas_control_oficial.control_vectores
											SET 
												identificador_cierre='$identificador', 
											    fecha_cierre=now(), 
											    observaciones='$observaciones',
												estado='cerrado'
											WHERE 
												id_control_vectores=$idControlVectores;");
	
		return $res;
	}
	
	//Archivo subirArchivo
	public function actualizarImagenMapaControlVectores($conexion,$idControlVectores,$rutaArchivo){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_programas_control_oficial.control_vectores
											SET
												imagen_mapa='".$rutaArchivo."'
											WHERE
												id_control_vectores=".$idControlVectores.";");
	
		return $res;
	
	}
	
	public function actualizarInformeControlVectores($conexion,$idControlVectores,$rutaArchivo){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_programas_control_oficial.control_vectores
											SET
												ruta_informe='".$rutaArchivo."'
											WHERE
												id_control_vectores=".$idControlVectores.";");
	
		return $res;
	
	}

/******** INSPECCIÓN DE OVINOS, CAPRINOS Y CAMÉLIDOS SUDAMERICANOS ********/
	
	//Archivo listaInspeccionOCCS
	public function buscarInspeccionOCCS ($conexion, $numSolicitud, $fecha, $nombrePredio, $nombrePropietario,
			$nombreAsociacion, $idProvincia, $idCanton, $idParroquia, $sector, $estado){
	
		$numSolicitud = $numSolicitud!="" ? "'%" . $numSolicitud . "%'" : "null";
		$fecha = $fecha!="" ? "'" . $fecha . "'" : "null";
		$nombrePredio = $nombrePredio!="" ? "'%" . $nombrePredio . "%'" : "null";
		$nombrePropietario = $nombrePropietario!="" ? "'%" . $nombrePropietario . "%'" : "null";
		$nombreAsociacion = $nombreAsociacion!="" ? "'%" . $nombreAsociacion . "%'" : "null";
		$idProvincia = $idProvincia!="" ? "" . $idProvincia . "" : "null";
		$idCanton = $idCanton!="" ? "" . $idCanton . "" : "null";
		$idParroquia = $idParroquia!="" ? "" . $idParroquia . "" : "null";
		$sector = $sector!="" ? "'%" . $sector . "%'" : "null";
		$estado = $estado!="" ? "'" . $estado . "'" : "null";

		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programas_control_oficial.busqueda_inspeccion_occs(
												$numSolicitud, $fecha, $nombrePredio, $nombrePropietario,
												$nombreAsociacion, $idProvincia, $idCanton,
												$idParroquia, $sector, $estado)
											ORDER BY
												fecha_creacion desc;");

		return $res;
	}
	
	//Archivo guardarInspeccionOCCS
	public function generarNumeroInspeccionOCCS($conexion, $codigo){
	
		$res = $conexion->ejecutarConsulta("SELECT
												MAX(num_solicitud) as num_solicitud
											FROM
												g_programas_control_oficial.inspeccion_occs
											WHERE
												num_solicitud LIKE '%$codigo%';");
		
		return $res;
	}
	
	public function nuevaInspeccionOCCS ($conexion, $identificador, $numSolicitud,
											$fecha, $nombrePredio, $nombrePropietario, $cedulaPropietario,
											$telefono, $correoElectronico, $nombreAsociacion,
											$idProvincia, $provincia, $idCanton, $canton, $idParroquia, $parroquia,
											$sector, $x, $y, $z, $altitud,
											$latitud, $longitud, $zona, $imagenMapa, $informe){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO 
												g_programas_control_oficial.inspeccion_occs(
										            identificador, fecha_creacion, num_solicitud, 
										            fecha, nombre_predio, nombre_propietario, cedula_propietario, 
										            telefono, correo_electronico, nombre_asociacion, id_provincia, 
										            provincia, id_canton, canton, id_parroquia, parroquia, sector, 
										            utm_x, utm_y, utm_z, altitud, latitud, longitud, zona, estado, 
													imagen_mapa, ruta_informe)
										    VALUES ('$identificador', now(), '$numSolicitud', 
										            '$fecha', '$nombrePredio', '$nombrePropietario', '$cedulaPropietario', 
										            '$telefono', '$correoElectronico', '$nombreAsociacion', $idProvincia, 
										            '$provincia', $idCanton, '$canton', $idParroquia, '$parroquia', '$sector', 
										            '$x', '$y', '$z', '$altitud', '$latitud', '$longitud', '$zona', 'activo',
													'$imagenMapa', '$informe') 
											RETURNING 
													id_inspeccion_occs");
	
		return $res;
	}
	
	//Archivo abrirInspeccionOCCS
	public function abrirInspeccionOCCS ($conexion, $idInspeccionOCCS){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programas_control_oficial.inspeccion_occs
											WHERE
												id_inspeccion_occs = $idInspeccionOCCS;");
	
		return $res;
	}
	
	//Archivo modificarInspeccionOCCS
	public function modificarInspeccionOCCS($conexion, $idInspeccionOCCS, $identificador,
											$fecha, $nombrePredio, $nombrePropietario, $cedulaPropietario,
											$telefono, $correoElectronico, $nombreAsociacion,
											$sector, $x, $y, $z, $altitud,
											$latitud, $longitud, $zona){
	
			$res = $conexion->ejecutarConsulta("UPDATE
													g_programas_control_oficial.inspeccion_occs
												SET
													fecha='$fecha',
													nombre_predio='$nombrePredio',
													nombre_propietario='$nombrePropietario',
													cedula_propietario='$cedulaPropietario',
													telefono='$telefono',
													correo_electronico='$correoElectronico',
													nombre_asociacion='$nombreAsociacion',
													sector='$sector',
													utm_x='$x',
													utm_y='$y',
													utm_z='$z',
													altitud='$altitud',
													latitud='$latitud',
													longitud='$longitud',
													zona='$zona',
													identificador_modificacion='$identificador',
													fecha_modificacion=now()
												WHERE
													id_inspeccion_occs=$idInspeccionOCCS;");

			return $res;
	}
	
	public function listarTipoExplotacionInspeccionOCCS($conexion, $idInspeccionOCCS){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programas_control_oficial.inspeccion_occs_tipo_explotacion
											WHERE
												id_inspeccion_occs = $idInspeccionOCCS
											ORDER BY
												id_explotacion asc;");
	
		return $res;
	}
	
	public function listarEspecieInspeccionOCCS($conexion, $idInspeccionOCCS){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programas_control_oficial.inspeccion_occs_especie
											WHERE
												id_inspeccion_occs = $idInspeccionOCCS
											ORDER BY
												id_especie, id_raza, id_categoria asc;");
	
		return $res;
	}
	
	public function listarBioseguridadInspeccionOCCS($conexion, $idInspeccionOCCS){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programas_control_oficial.inspeccion_occs_bioseguridad
											WHERE
												id_inspeccion_occs = $idInspeccionOCCS
											ORDER BY
												id_sector_perteneciente, id_tipo_produccion asc;");
	
		return $res;
	}
	
	public function listarHistorialPatologiasInspeccionOCCS($conexion, $idInspeccionOCCS){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programas_control_oficial.inspeccion_occs_historial_patologias
											WHERE
												id_inspeccion_occs = $idInspeccionOCCS
											ORDER BY
												id_enfermedad asc;");
									
		return $res;
	}
	
	//Archivo guardarExplotacionInspeccionOCCS
	public function buscarExplotacionInspeccionOCCS($conexion, $idExplotacionOCCS, $nombreExplotacion){
	
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programas_control_oficial.inspeccion_occs_tipo_explotacion
											WHERE
												id_inspeccion_occs = $idExplotacionOCCS and
												upper(explotacion) = upper('$nombreExplotacion');");
	
		return $res;
	}
	
	public function nuevaExplotacionInspeccionOCCS($conexion, $identificador, $idInspeccionOCCS, $idExplotacion,
														$nombreExplotacion, $superficieExplotacion){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO 
												g_programas_control_oficial.inspeccion_occs_tipo_explotacion(
										            id_inspeccion_occs, identificador, fecha_creacion, 
										            id_explotacion, explotacion, superficie_explotacion,
													fecha_catastro)
    										VALUES 
												($idInspeccionOCCS, '$identificador', now(),
            									$idExplotacion, '$nombreExplotacion', $superficieExplotacion,
												now())
											RETURNING 
												id_inspeccion_occs_tipo_explotacion;");
							
		return $res;
	}
	
	public function imprimirLineaTipoExplotacionInspeccionOCCS($idTipoExplotacionInspeccionOCCS,
																$nombreExplotacion, $superficieExplotacion,
																$ruta){
	
		return '<tr id="R' . $idTipoExplotacionInspeccionOCCS . '">' .
					'<td width="30%">' .
					$nombreExplotacion .
					'</td>' .
					'<td width="30%">' .
					$superficieExplotacion.
					'</td>' .
					'<td>' .
					'<form id="borrarRegistro" class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarTipoExplotacionesInspeccionOCCS" data-destino="detalleItem" data-accionEnExito="NADA" >' .
					'<input type="hidden" name="idTipoExplotacionInspeccionOCCS" value="' . $idTipoExplotacionInspeccionOCCS . '" >' .
					'<button class="icono" type="submit" ></button>' .
					'</form>' .
					'</td>' .
				'</tr>';
	}
	
	public function imprimirLineaTipoExplotacionInspeccionOCCSConsulta($idTipoExplotacionInspeccionOCCS,
																			$nombreExplotacion, $superficieExplotacion,
																			$ruta){
	
				return '<tr id="R' . $idTipoExplotacionInspeccionOCCS . '">' .
						'<td width="30%">' .
						$nombreExplotacion .
						'</td>' .
						'<td width="30%">' .
						$superficieExplotacion.
						'</td>' .
						'</tr>';
	}
	
	//Archivo eliminarTipoExplotacionesInspeccionOCCS
	public function eliminarTipoExplotacionesInspeccionOCCS($conexion, $idTipoExplotacionInspeccionOCCS){
	
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_programas_control_oficial.inspeccion_occs_tipo_explotacion
											WHERE
												id_inspeccion_occs_tipo_explotacion=$idTipoExplotacionInspeccionOCCS;");
	
		return $res;
	}
	
	//Archivo guardarEspecieInspeccionOCCS
	public function buscarEspecieInspeccionOCCS($conexion, $idInspeccionOCCS, $nombreEspecie,
												  $nombreRaza, $nombreCategoria){
	
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programas_control_oficial.inspeccion_occs_especie
											WHERE
												id_inspeccion_occs = $idInspeccionOCCS and
												upper(especie) = upper('$nombreEspecie') and
												upper(raza) = upper('$nombreRaza') and
												upper(categoria) = upper('$nombreCategoria');");
	
		return $res;
	}
	
	public function nuevaEspecieInspeccionOCCS($conexion, $idInspeccionOCCS, $identificador, $idEspecie, 
														$nombreEspecie, $idRaza, $nombreRaza, $idCategoria,
														$nombreCategoria, $numeroAnimales){
	
				$res = $conexion->ejecutarConsulta("INSERT INTO 
														g_programas_control_oficial.inspeccion_occs_especie(
													            id_inspeccion_occs, identificador, 
													            fecha_creacion, id_especie, especie, id_raza, raza, id_categoria, 
													            categoria, numero_animales)
													VALUES ($idInspeccionOCCS, '$identificador', 
													        now(), $idEspecie, '$nombreEspecie', $idRaza, '$nombreRaza', $idCategoria, 
													        '$nombreCategoria', $numeroAnimales)
													RETURNING 
														id_inspeccion_occs_especie;");
							
						return $res;
	}
	
	public function imprimirLineaEspecieInspeccionOCCS($idEspecieInspeccionOCCS,
														$idInspeccionOCCS, $nombreEspecie, 
														$nombreRaza, $nombreCategoria, 
														$numeroAnimales, $ruta){
	
				return '<tr id="R' . $idEspecieInspeccionOCCS . '">' .
						'<td width="30%">' .
						$nombreEspecie .
						'</td>' .
						'<td width="30%">' .
						$nombreRaza.
						'</td>' .
						'<td width="10%">' .
						$nombreCategoria.
						'</td>
						<td width="10%">' .
						$numeroAnimales.
						'</td>
						<td>' .
						'<form id="borrarRegistro" class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarEspecieInspeccionOCCS" data-destino="detalleItem" data-accionEnExito="NADA" >' .
						'<input type="hidden" id="idEspecieInspeccionOCCS" name="idEspecieInspeccionOCCS" value="' . $idEspecieInspeccionOCCS . '" >' .
						'<button class="icono" type="submit" ></button>' .
						'</form>' .
						'</td>' .
						'</tr>';
	}
	
	public function imprimirLineaEspecieInspeccionOCCSConsulta($idEspecieInspeccionOCCS,
																$idInspeccionOCCS, $nombreEspecie,
																$nombreRaza, $nombreCategoria,
																$numeroAnimales, $ruta){
	
				return '<tr id="R' . $idEspecieInspeccionOCCS . '">' .
						'<td width="30%">' .
						$nombreEspecie .
						'</td>' .
						'<td width="30%">' .
						$nombreRaza.
						'</td>' .
						'<td width="10%">' .
						$nombreCategoria.
						'</td>
						<td width="10%">' .
							$numeroAnimales.
						'</td>
						</tr>';
	}
	
	//Archivo eliminarEspecieInspeccionOCCS
	public function eliminarEspecieInspeccionOCCS($conexion, $idEspecieInspeccionOCCS){
	
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_programas_control_oficial.inspeccion_occs_especie
											WHERE
												id_inspeccion_occs_especie=$idEspecieInspeccionOCCS;");
	
		return $res;
	}

	//Archivo guardarBioseguridadInspeccionOCCS
	public function buscarBioseguridadInspeccionOCCS($conexion, $idInspeccionOCCS, $nombreSectorPerteneciente){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programas_control_oficial.inspeccion_occs_bioseguridad
											WHERE
												id_inspeccion_occs = $idInspeccionOCCS and
												upper(sector_perteneciente) = upper('$nombreSectorPerteneciente');");
	
		return $res;
	}
	
	public function nuevaBioseguridadInspeccionOCCS($conexion, $idInspeccionOCCS, $identificador,
														$calendarioVacunacion, $vacuna, $calendarioDesparacitacion,
														$frecuencia, $asesoramientoTecnico, $nombreAsesor, $profesionAsesor,
														$identificacionIndividual, $tipoIdentificacion, $idTipoAlimentacion,
														$nombreTipoAlimentacion, $corralManejo, $registrosProductivos,
														$idTipoProduccion, $nombreTipoProduccion, $idSectorPerteneciente,
														$nombreSectorPerteneciente){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO
												g_programas_control_oficial.inspeccion_occs_bioseguridad(
													id_inspeccion_occs, identificador, 
										            fecha_creacion, calendario_vacunacion, vacuna, calendario_desparacitacion, 
										            frecuencia, asesoramiento_tecnico, nombre_asesor_tecnico, profesion, 
										            identificacion_individual, tipo_identificacion, id_tipo_alimentacion, 
										            tipo_alimentacion, corral_manejo, registros_productivos, id_tipo_produccion, 
										            tipo_produccion, id_sector_perteneciente, sector_perteneciente)
											VALUES ($idInspeccionOCCS, '$identificador',
													now(), '$calendarioVacunacion', '$vacuna', '$calendarioDesparacitacion',
													'$frecuencia', '$asesoramientoTecnico', '$nombreAsesor', '$profesionAsesor',
													'$identificacionIndividual', '$tipoIdentificacion', $idTipoAlimentacion,
													'$nombreTipoAlimentacion', '$corralManejo', '$registrosProductivos', $idTipoProduccion,
													'$nombreTipoProduccion', $idSectorPerteneciente, '$nombreSectorPerteneciente')									
											RETURNING
												id_inspeccion_occs_bioseguridad;");
			
		return $res;
	}
	
	public function imprimirLineaBioseguridadInspeccionOCCS($idBioseguridadInspeccionOCCS,$idInspeccionOCCS,
														$vacuna, $frecuencia, $tipoIdentificacion,
														$nombreTipoAlimentacion, $nombreTipoProduccion,
														$nombreSectorPerteneciente, $ruta){
	
				return '<tr id="R' . $idBioseguridadInspeccionOCCS . '">' .
						'<td width="30%">' .
						$vacuna .
						'</td>' .
						'<td width="30%">' .
						$frecuencia.
						'</td>' .
						'<td width="10%">' .
						$tipoIdentificacion.
						'</td>
						<td width="10%">' .
						$nombreTipoAlimentacion.
						'</td>
						<td width="30%">' .
						$nombreTipoProduccion .
						'</td>' .
						'<td width="30%">' .
						$nombreSectorPerteneciente.
						'</td>
						<td>' .
						'<form class="abrir" data-rutaAplicacion="'.$ruta.'" data-opcion="abrirBioseguridadInspeccionOCCS" data-destino="detalleItem" data-accionEnExito="NADA" >' .
						'<input type="hidden" name="idBioseguridadInspeccionOCCS" value="' . $idBioseguridadInspeccionOCCS . '" >' .
						'<input type="hidden" name="idInspeccionOCCS" value="' . $idInspeccionOCCS . '" >' .
						'<button class="icono" type="submit" ></button>' .
						'</form>' .
						'</td>
						<td>' .
						'<form id="borrarRegistro" class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarBioseguridadInspeccionOCCS" data-destino="detalleItem" data-accionEnExito="NADA" >' .
						'<input type="hidden" id="idBioseguridadInspeccionOCCS" name="idBioseguridadInspeccionOCCS" value="' . $idBioseguridadInspeccionOCCS . '" >' .
						'<button class="icono" type="submit" ></button>' .
						'</form>' .
						'</td>' .
						'</tr>';
	}
	
	public function imprimirLineaBioseguridadInspeccionOCCSConsulta($idBioseguridadInspeccionOCCS,$idInspeccionOCCS,
														$vacuna, $frecuencia, $tipoIdentificacion,
														$nombreTipoAlimentacion, $nombreTipoProduccion,
														$nombreSectorPerteneciente, $ruta){
	
				return '<tr id="R' . $idBioseguridadInspeccionOCCS . '">' .
						'<td width="30%">' .
						$vacuna .
						'</td>' .
						'<td width="30%">' .
						$frecuencia.
						'</td>' .
						'<td width="10%">' .
						$tipoIdentificacion.
						'</td>
						<td width="10%">' .
						$nombreTipoAlimentacion.
						'</td>
						<td width="30%">' .
						$nombreTipoProduccion .
						'</td>' .
						'<td width="30%">' .
						$nombreSectorPerteneciente.
						'</td>
						<td>' .
						'<form class="abrir" data-rutaAplicacion="'.$ruta.'" data-opcion="abrirBioseguridadInspeccionOCCS" data-destino="detalleItem" data-accionEnExito="NADA" >' .
						'<input type="hidden" name="idBioseguridadInspeccionOCCS" value="' . $idBioseguridadInspeccionOCCS . '" >' .
						'<input type="hidden" name="idInspeccionOCCS" value="' . $idInspeccionOCCS . '" >' .
						'<button class="icono" type="submit" ></button>' .
						'</form>' .
						'</td>
						</tr>';
	}
	
	//Archivo abrirBioseguridadInspeccionOCCS
	public function abrirBioseguridadInspeccionOCCS($conexion, $idBioseguridadInspeccionOCCS){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programas_control_oficial.inspeccion_occs_bioseguridad
											WHERE
												id_inspeccion_occs_bioseguridad = $idBioseguridadInspeccionOCCS;");
	
		return $res;
	}
	
	//Archivo eliminarBioseguridadInspeccionOCCS
	public function eliminarBioseguridadInspeccionOCCS($conexion, $idBioseguridadInspeccionOCCS){
	
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_programas_control_oficial.inspeccion_occs_bioseguridad
											WHERE
												id_inspeccion_occs_bioseguridad=$idBioseguridadInspeccionOCCS;");
	
		return $res;
	}
	
	//Archivo guardarHistorialPatologiasInspeccionOCCS
	public function buscarHistorialPatologiasInspeccionOCCS($conexion, $idInspeccionOCCS, $nombreEnfermedad){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programas_control_oficial.inspeccion_occs_historial_patologias
											WHERE
												id_inspeccion_occs = $idInspeccionOCCS and
												upper(enfermedad) = upper('$nombreEnfermedad');");
	
		return $res;
	}
	
	public function nuevoHistorialPatologiasInspeccionOCCS($conexion, $idInspeccionOCCS, $identificador, $idEnfermedad, $nombreEnfermedad){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO
												g_programas_control_oficial.inspeccion_occs_historial_patologias(
													id_inspeccion_occs, identificador,
													fecha_creacion, id_enfermedad, enfermedad)
											VALUES ($idInspeccionOCCS, '$identificador',
													now(), $idEnfermedad, '$nombreEnfermedad')
											RETURNING
												id_inspeccion_occs_historial_patologias;");
			
		return $res;
	}
	
	public function imprimirLineaHistorialPatologiasInspeccionOCCS($idEnfermedadInspeccionOCCS,
																$idInspeccionOCCS, $nombreEnfermedad, $ruta){
	
				return '<tr id="R' . $idEnfermedadInspeccionOCCS . '">' .
						'<td width="30%">' .
						$nombreEnfermedad.
						'</td>
						<td>' .
							'<form id="borrarRegistro" class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarHistorialPatologiasInspeccionOCCS" data-destino="detalleItem" data-accionEnExito="NADA" >' .
							'<input type="hidden" id="idEnfermedadInspeccionOCCS" name="idEnfermedadInspeccionOCCS" value="' . $idEnfermedadInspeccionOCCS . '" >' .
							'<button class="icono" type="submit" ></button>' .
							'</form>' .
							'</td>' .
							'</tr>';
	}
	
	public function imprimirLineaHistorialPatologiasInspeccionOCCSConsulta($idEnfermedadInspeccionOCCS,
																$idInspeccionOCCS, $nombreEnfermedad, $ruta){
	
				return '<tr id="R' . $idEnfermedadInspeccionOCCS . '">' .
						'<td width="30%">' .
						$nombreEnfermedad.
						'</td>
						</tr>';
	}
	
	//Archivo eliminarHistorialPatologiasInspeccionOCCS
	public function eliminarHistorialPatologiasInspeccionOCCS($conexion, $idEnfermedadInspeccionOCCS){
	
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_programas_control_oficial.inspeccion_occs_historial_patologias
											WHERE
												id_inspeccion_occs_historial_patologias=$idEnfermedadInspeccionOCCS;");
	
		return $res;
	}
	
	//Archivo guardarCierreInspeccionOCCS
	public function cierreInspeccionOCCS($conexion, $idInspeccionOCCS, $identificador,
											$observaciones){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_programas_control_oficial.inspeccion_occs
											SET
												identificador_cierre='$identificador',
												fecha_cierre=now(),
												observaciones='$observaciones',
												estado='cerrado'
											WHERE
												id_inspeccion_occs=$idInspeccionOCCS;");
	
		return $res;
	}
	
	//Archivo subirArchivo
	public function actualizarImagenMapaInspeccionOCCS($conexion,$idInspeccionOCCS,$rutaArchivo){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_programas_control_oficial.inspeccion_occs
											SET
												imagen_mapa='".$rutaArchivo."'
											WHERE
												id_inspeccion_occs=$idInspeccionOCCS;");
	
		return $res;
	
	}
	
	public function actualizarInformeInspeccionOCCS($conexion,$idInspeccionOCCS,$rutaArchivo){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_programas_control_oficial.inspeccion_occs
											SET
												ruta_informe='".$rutaArchivo."'
											WHERE
												id_inspeccion_occs=$idInspeccionOCCS;");
	
		return $res;
	
	}
	
/******** CATASTRO DE PREDIOS DE ÉQUIDOS ********/
	
	//Archivo listaCatastroPredioEquidos
	public function buscarCatastroPredioEquidos ($conexion, $numSolicitud, $fecha, $nombrePredio, $nombrePropietario,
			$nombreAdministrador, $idProvincia, $idCanton, $idParroquia, $estado){
	
				$numSolicitud = $numSolicitud!="" ? "'%" . $numSolicitud . "%'" : "null";
				$fecha = $fecha!="" ? "'" . $fecha . "'" : "null";
				$nombrePredio = $nombrePredio!="" ? "'%" . $nombrePredio . "%'" : "null";
				$nombrePropietario = $nombrePropietario!="" ? "'%" . $nombrePropietario . "%'" : "null";
				$nombreAdministrador = $nombreAdministrador!="" ? "'%" . $nombreAdministrador . "%'" : "null";
				$idProvincia = $idProvincia!="" ? "" . $idProvincia . "" : "null";
				$idCanton = $idCanton!="" ? "" . $idCanton . "" : "null";
				$idParroquia = $idParroquia!="" ? "" . $idParroquia . "" : "null";
				$estado = $estado!="" ? "'" . $estado . "'" : "null";
	
				$res = $conexion->ejecutarConsulta("SELECT
														*
													FROM
														g_programas_control_oficial.busqueda_catastro_predio_equidos(
														$numSolicitud, $fecha, $nombrePredio, $nombrePropietario,
														$nombreAdministrador, $idProvincia, $idCanton,
														$idParroquia, $estado)
													ORDER BY
														fecha_creacion desc;");
	
				return $res;
	}
	//Archivo guardarCatastroPredioEquidos
	public function generarNumeroCatastroPredioEquidos($conexion, $codigo){
	
		$res = $conexion->ejecutarConsulta("SELECT
												MAX(num_solicitud) as num_solicitud
											FROM
												g_programas_control_oficial.catastro_predio_equidos
											WHERE
												num_solicitud LIKE '%$codigo%';");
	
		return $res;
	}
	
	public function nuevoCatastroPredioEquidos ($conexion, $identificador, $numSolicitud,
													$fecha, $nombrePredio, $nombrePropietario, $cedulaPropietario,
													$telefonoPropietario, $correoElectronicoPropietario, 
													$nombreAdministrador, $cedulaAdministrador,
													$telefonoAdministrador, $correoElectronicoAdministrador,
													$idProvincia, $provincia, $idCanton, $canton, $idParroquia, $parroquia,
													$direccionPredio, $x, $y, $z, $altitud, $latitud, $longitud, $zona, $extension, $imagenMapa, $informe){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO g_programas_control_oficial.catastro_predio_equidos(
									            identificador, fecha_creacion, num_solicitud, 
									            fecha, nombre_predio, nombre_propietario, cedula_propietario, 
									            telefono_propietario, correo_electronico_propietario, nombre_administrador, 
									            cedula_administrador, telefono_administrador, correo_electronico_administrador, 
									            id_provincia, provincia, id_canton, canton, id_parroquia, parroquia, 
									            direccion_predio, utm_x, utm_y, utm_z, altitud, extension, 
												latitud, longitud, zona, estado, imagen_mapa, ruta_informe)
									    	VALUES ('$identificador', now(), '$numSolicitud', 
									            '$fecha', '$nombrePredio', '$nombrePropietario', '$cedulaPropietario', 
									            '$telefonoPropietario', '$correoElectronicoPropietario', 
												'$nombreAdministrador', '$cedulaAdministrador', 
									            '$telefonoAdministrador', '$correoElectronicoAdministrador', 
												$idProvincia, '$provincia', $idCanton, '$canton', $idParroquia, '$parroquia',
									            '$direccionPredio', '$x', '$y', '$z', '$altitud', $extension, 
									            '$latitud', '$longitud', '$zona', 'activo', '$imagenMapa', '$informe')
											RETURNING
												id_catastro_predio_equidos");

		return $res;
	}
	
	//Archivo abrirCatastroPredioEquidos
	public function abrirCatastroPredioEquidos ($conexion, $idCatastroPredioEquidos){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programas_control_oficial.catastro_predio_equidos
											WHERE
												id_catastro_predio_equidos = $idCatastroPredioEquidos;");
	
		return $res;
	}
	
	public function listarMotivoCatastroPredioEquidos($conexion, $idPredioEquidos){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programas_control_oficial.catastro_predio_equidos_catastro
											WHERE
												id_catastro_predio_equidos = $idPredioEquidos
											ORDER BY
												id_catastro asc;");
	
		return $res;
	}
	
	public function listarTipoActividadPredioEquidos($conexion, $idPredioEquidos){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programas_control_oficial.catastro_predio_equidos_tipo_actividad
											WHERE
												id_catastro_predio_equidos = $idPredioEquidos
											ORDER BY
												id_tipo_actividad asc;");
	
		return $res;
	}
	
	public function listarEspeciePredioEquidos($conexion, $idPredioEquidos){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programas_control_oficial.catastro_predio_equidos_especie
											WHERE
												id_catastro_predio_equidos = $idPredioEquidos
											ORDER BY
												id_especie, id_raza, id_categoria asc;");
	
		return $res;
	}
	
	public function listarBioseguridadPredioEquidos($conexion, $idPredioEquidos){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programas_control_oficial.catastro_predio_equidos_bioseguridad
											WHERE
												id_catastro_predio_equidos = $idPredioEquidos
											ORDER BY
												id_bioseguridad asc;");
	
		return $res;
	}
	
	public function listarSanidadPredioEquidos($conexion, $idPredioEquidos){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programas_control_oficial.catastro_predio_equidos_sanidad
											WHERE
												id_catastro_predio_equidos = $idPredioEquidos;");
	
		return $res;
	}
	
	public function listarHistorialPatologiasPredioEquidos($conexion, $idPredioEquidos){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programas_control_oficial.catastro_predio_equidos_historial_patologias
											WHERE
												id_catastro_predio_equidos = $idPredioEquidos;");
	
		return $res;
	}
	
	//Archivo modificarCatastroPredioEquidos
	public function modificarCatastroPredioEquidos(	$conexion, $idCatastroPredioEquidos, $identificador,
														$fecha, $nombrePredio, $nombrePropietario, $cedulaPropietario,
														$telefonoPropietario, $correoElectronicoPropietario, 
														$nombreAdministrador, $cedulaAdministrador,
														$telefonoAdministrador, $correoElectronicoAdministrador,
														$direccionPredio, $x, $y, $z, $altitud, 
														$latitud, $longitud, $zona, $extension){
	
		$res = $conexion->ejecutarConsulta("UPDATE 
												g_programas_control_oficial.catastro_predio_equidos
										   SET 
											   fecha='$fecha', 
											   nombre_predio='$nombrePredio', 
											   nombre_propietario='$nombrePropietario', 
											   cedula_propietario='$cedulaPropietario', 
											   telefono_propietario='$telefonoPropietario', 
											   correo_electronico_propietario='$correoElectronicoPropietario', 
											   nombre_administrador='$nombreAdministrador', 
											   cedula_administrador='$cedulaAdministrador', 
											   telefono_administrador='$telefonoAdministrador', 
											   correo_electronico_administrador='$correoElectronicoAdministrador', 
											   direccion_predio='$direccionPredio', 
											   utm_x='$x', 
											   utm_y='$y', 
											   utm_z='$z', 
											   altitud='$altitud', 
											   extension='$extension', 
											   latitud='$latitud', 
											   longitud='$longitud', 
											   zona='$zona',
											   identificador_modificacion='$identificador', 
   											   fecha_modificacion=now()
										 	WHERE
												id_catastro_predio_equidos=$idCatastroPredioEquidos;");
	
		return $res;
	}
	
	//Archivo guardarMotivoCatastroPredioEquidos
	public function buscarMotivoCatastroPredioEquidos($conexion, $idCatastroPredioEquidos, $nombreMotivoCatastro){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programas_control_oficial.catastro_predio_equidos_catastro
											WHERE
												id_catastro_predio_equidos = $idCatastroPredioEquidos and
												upper(catastro) = upper('$nombreMotivoCatastro');");
	
		return $res;
	}
	
	public function nuevoMotivoCatastroPredioEquidos($conexion, $idCatastroPredioEquidos, $identificador, 
														$idMotivoCatastro, $nombreMotivoCatastro){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO 
												g_programas_control_oficial.catastro_predio_equidos_catastro(
											    	id_catastro_predio_equidos, identificador,  
											        fecha_creacion, id_catastro, catastro)
											VALUES ($idCatastroPredioEquidos, '$identificador',
												    now(), $idMotivoCatastro, '$nombreMotivoCatastro')
											RETURNING
												id_catastro_predio_equidos_catastro;");
			
		return $res;
	}
	
	public function imprimirLineaMotivoCatastroPredioEquidos($idMotivoCatastroPredioEquidos,
																$idCatastroPredioEquidos, $nombreMotivoCatastro, $ruta){
	
				return '<tr id="R' . $idMotivoCatastroPredioEquidos . '">' .
						'<td width="30%">' .
						$nombreMotivoCatastro.
						'</td>
						<td>' .
						'<form id="borrarRegistro" class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarMotivoCatastroPredioEquidos" data-destino="detalleItem" data-accionEnExito="NADA" >' .
						'<input type="hidden" id="idMotivoCatastroPredioEquidos" name="idMotivoCatastroPredioEquidos" value="' . $idMotivoCatastroPredioEquidos . '" >' .
						'<button class="icono" type="submit" ></button>' .
						'</form>' .
						'</td>' .
						'</tr>';
	}
	
	public function imprimirLineaMotivoCatastroPredioEquidosConsulta($idMotivoCatastroPredioEquidos,
																$idCatastroPredioEquidos, $nombreMotivoCatastro, $ruta){
	
				return '<tr id="R' . $idMotivoCatastroPredioEquidos . '">' .
						'<td width="30%">' .
						$nombreMotivoCatastro.
						'</td>
						<td>
						</tr>';
	}
	
	//Archivo eliminarMotivoCatastroPredioEquidos
	public function eliminarMotivoCatastroPredioEquidos($conexion, $idMotivoCatastroPredioEquidos){
	
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_programas_control_oficial.catastro_predio_equidos_catastro
											WHERE
												id_catastro_predio_equidos_catastro=$idMotivoCatastroPredioEquidos;");
	
		return $res;
	}
	
	//Archivo guardarMotivoCatastroPredioEquidos
	public function buscarTipoActividadPredioEquidos($conexion, $idCatastroPredioEquidos, $nombreActividad){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programas_control_oficial.catastro_predio_equidos_tipo_actividad
											WHERE
												id_catastro_predio_equidos = $idCatastroPredioEquidos and
												upper(tipo_actividad) = upper('$nombreActividad');");
	
		return $res;
	}
	
	public function nuevoTipoActividadCatastroPredioEquidos($conexion, $idCatastroPredioEquidos, $identificador,
			$idTipoActividad, $nombreActividad, $extensionActividad){
	
				$res = $conexion->ejecutarConsulta("INSERT INTO g_programas_control_oficial.catastro_predio_equidos_tipo_actividad(
												            id_catastro_predio_equidos, identificador, fecha_creacion, 
												            id_tipo_actividad, tipo_actividad, 
												            extension_actividad)
												    VALUES ($idCatastroPredioEquidos, '$identificador', now(),
												            $idTipoActividad, '$nombreActividad', $extensionActividad)
													RETURNING
														id_catastro_predio_equidos_tipo_actividad;");
					
				return $res;
	}
	
	public function imprimirLineaTipoActividadPredioEquidos($idTipoActividadPredioEquidos, $idCatastroPredioEquidos, 
			$nombreActividad, $extensionActividad, $ruta){
	
				return '<tr id="R' . $idTipoActividadPredioEquidos . '">' .
						'<td width="30%">' .
						$nombreActividad.
						'</td>
						<td width="30%">' .
						$extensionActividad.
						'</td>
						<td>' .
						'<form id="borrarRegistro" class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarTipoActividadPredioEquidos" data-destino="detalleItem" data-accionEnExito="NADA" >' .
						'<input type="hidden" id="idTipoActividadPredioEquidos" name="idTipoActividadPredioEquidos" value="' . $idTipoActividadPredioEquidos . '" >' .
						'<button class="icono" type="submit" ></button>' .
						'</form>' .
						'</td>' .
						'</tr>';
	}
	
	public function imprimirLineaTipoActividadPredioEquidosConsulta($idTipoActividadPredioEquidos, $idCatastroPredioEquidos,
			$nombreActividad, $extensionActividad, $ruta){
	
		return '<tr id="R' . $idTipoActividadPredioEquidos . '">' .
				'<td width="30%">' .
				$nombreActividad.
				'</td>
				<td width="30%">' .
				$extensionActividad.
				'</td>
				</tr>';
	}
	
	//Archivo eliminarTipoActividadPredioEquidos
	public function eliminarTipoActividadPredioEquidos($conexion, $idTipoActividadPredioEquidos){
	
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_programas_control_oficial.catastro_predio_equidos_tipo_actividad
											WHERE
												id_catastro_predio_equidos_tipo_actividad=$idTipoActividadPredioEquidos;");
	
		return $res;
	}
	
	//Archivo guardarEspecieCatastroPredioEquidos
	public function buscarEspeciePredioEquidos($conexion, $idCatastroPredioEquidos, $nombreEspecie,
												$nombreRaza, $nombreCategoria){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programas_control_oficial.catastro_predio_equidos_especie
											WHERE
												id_catastro_predio_equidos = $idCatastroPredioEquidos and
												upper(nombre_especie) = upper('$nombreEspecie') and
												upper(nombre_raza) = upper('$nombreRaza') and
												upper(nombre_categoria) = upper('$nombreCategoria');");
	
		return $res;
	}
	
	public function actualizarEspeciePredioEquidos($conexion, $idCatastroPredioEquidosEspecie, $numeroAnimales){
	        
	        $res = $conexion->ejecutarConsulta("UPDATE 
                                                    g_programas_control_oficial.catastro_predio_equidos_especie 
                                                SET
									                numero_animales = numero_animales + $numeroAnimales
    										    WHERE
                                                    id_catastro_predio_equidos_especie = $idCatastroPredioEquidosEspecie
                                                RETURNING numero_animales;");
										            
			return $res;
	}
	
	public function nuevaEspeciePredioEquidos($conexion, $idCatastroPredioEquidos, $identificador,
			$idEspecie, $nombreEspecie, $idRaza, $nombreRaza, $idCategoria, $nombreCategoria, $numeroAnimales){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO g_programas_control_oficial.catastro_predio_equidos_especie(
									            id_catastro_predio_equidos, identificador, fecha_creacion,  
									            id_especie, nombre_especie, id_raza, nombre_raza, 
			            						id_categoria, nombre_categoria, numero_animales)
										    VALUES ($idCatastroPredioEquidos, '$identificador', now(), 
										            $idEspecie, '$nombreEspecie', $idRaza, '$nombreRaza', 
										            $idCategoria, '$nombreCategoria', $numeroAnimales)
											RETURNING
												id_catastro_predio_equidos_especie;");
					
				return $res;
	}
	
	public function imprimirLineaEspeciePredioEquidos($idEspeciePredioEquidos, $idCatastroPredioEquidos,
			$nombreEspecie, $nombreRaza, $nombreCategoria, $numeroAnimales, $ruta){
	
				return '<tr id="R' . $idEspeciePredioEquidos . '">' .
						'<td width="30%">' .
						$nombreEspecie.
						'</td>
						<td width="30%">' .
						$nombreRaza.
						'</td>
						<td width="30%">' .
						$nombreCategoria.
						'</td>
						<td width="30%">' .
						$numeroAnimales.
						'</td>
						<td>' .
						'<form id="borrarRegistro" class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarEspeciePredioEquidos" data-destino="detalleItem" data-accionEnExito="NADA" >' .
						'<input type="hidden" id="EspeciePredioEquidos" name="idEspeciePredioEquidos" value="' . $idEspeciePredioEquidos . '" >' .
						'<button class="icono" type="submit" ></button>' .
						'</form>' .
						'</td>' .
						'</tr>';
	}
	
	public function imprimirLineaEspeciePredioEquidosConsulta($idEspeciePredioEquidos, $idCatastroPredioEquidos,
			$nombreEspecie, $nombreRaza, $nombreCategoria, $numeroAnimales, $ruta){
	
				return '<tr id="R' . $idEspeciePredioEquidos . '">' .
						'<td width="30%">' .
						$nombreEspecie.
						'</td>
						<td width="30%">' .
						$nombreRaza.
						'</td>
						<td width="30%">' .
						$nombreCategoria.
						'</td>
						<td width="30%">' .
						$numeroAnimales.
						'</td>' .
						'</tr>';
	}
	
	//Archivo eliminarEspeciePredioEquidos
	public function eliminarEspeciePredioEquidos($conexion, $idEspeciePredioEquidos){
	
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_programas_control_oficial.catastro_predio_equidos_especie
											WHERE
												id_catastro_predio_equidos_especie=$idEspeciePredioEquidos;");
	
		return $res;
	}
	
	//Archivo guardarBioseguridadPredioEquidos
	public function buscarBioseguridadPredioEquidos($conexion, $idCatastroPredioEquidos, $nombreBioseguridad){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programas_control_oficial.catastro_predio_equidos_bioseguridad
											WHERE
											id_catastro_predio_equidos = $idCatastroPredioEquidos and
											upper(bioseguridad) = upper('$nombreBioseguridad');");

		return $res;
	}
	
	public function nuevaBioseguridadPredioEquidos($conexion, $idCatastroPredioEquidos, $identificador,
			$idBioseguridad, $nombreBioseguridad){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO g_programas_control_oficial.catastro_predio_equidos_bioseguridad(
									            id_catastro_predio_equidos, identificador, fecha_creacion,  
									            id_bioseguridad, bioseguridad)
									    	VALUES ($idCatastroPredioEquidos, $identificador, now(),
									        	    $idBioseguridad, '$nombreBioseguridad')
											RETURNING
												id_catastro_predio_equidos_bioseguridad;");
					
		return $res;
	}
	
	public function eliminarTotalPredioEquidos($conexion, $idCatastroPredioEquidos){
	
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_programas_control_oficial.catastro_predio_equidos_bioseguridad
											WHERE
												id_catastro_predio_equidos=$idCatastroPredioEquidos and
												id_bioseguridad != 0;");
	
		return $res;
	}
	
	public function eliminarTotalPredioEquidosNA($conexion, $idCatastroPredioEquidos){
	
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_programas_control_oficial.catastro_predio_equidos_bioseguridad
											WHERE
												id_catastro_predio_equidos=$idCatastroPredioEquidos and
												id_bioseguridad = 0;");
	
		return $res;
	}
	
	public function imprimirLineaBioseguridadPredioEquidos($idBioseguridadPredioEquidos, $idCatastroPredioEquidos,
			$nombreBioseguridad, $ruta){
	
		return '<tr id="R' . $idBioseguridadPredioEquidos . '">' .
				'<td width="30%">' .
				$nombreBioseguridad.
				'</td>
				<td>' .
				'<form id="borrarRegistro" class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarBioseguridadPredioEquidos" data-destino="detalleItem" data-accionEnExito="NADA" >' .
				'<input type="hidden" id="idBioseguridadPredioEquidos" name="idBioseguridadPredioEquidos" value="' . $idBioseguridadPredioEquidos . '" >' .
				'<button class="icono" type="submit" ></button>' .
				'</form>' .
				'</td>' .
				'</tr>';
	}
	
	public function imprimirLineaBioseguridadPredioEquidosConsulta($idBioseguridadPredioEquidos, $idCatastroPredioEquidos,
			$nombreBioseguridad, $ruta){
	
		return '<tr id="R' . $idBioseguridadPredioEquidos . '">' .
				'<td width="30%">' .
				$nombreBioseguridad.
				'</td>
				</tr>';
	}
	
	//Archivo eliminarBioseguridadPredioEquidos
	public function eliminarBioseguridadPredioEquidos($conexion, $idBioseguridadPredioEquidos){
	
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_programas_control_oficial.catastro_predio_equidos_bioseguridad
											WHERE
												id_catastro_predio_equidos_bioseguridad=$idBioseguridadPredioEquidos;");
	
		return $res;
	}
	
	//Archivo guardarSanidadPredioEquidos
	public function buscarSanidadPredioEquidos($conexion, $idCatastroPredioEquidos){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programas_control_oficial.catastro_predio_equidos_sanidad
											WHERE
												id_catastro_predio_equidos = $idCatastroPredioEquidos;");
	
		return $res;
	}
	
	public function nuevaSanidadPredioEquidos($conexion, $idCatastroPredioEquidos, $identificador,
												$profesionalTecnico, $pesebreras, $areaCuarentena, $eliminacionDesechos, $controlVectores,
												$usoAperosIndividuales, $reportePositivoAIE, $idMedidaSanitaria, $nombreMedidaSanitaria){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO g_programas_control_oficial.catastro_predio_equidos_sanidad(
									            id_catastro_predio_equidos, identificador, fecha_creacion,  
									            profesional_tecnico, pesebreras, area_cuarentena, 
									            eliminacion_desechos, control_vectores, uso_aperos_individuales, 
									            reporte_positivo_aie, id_medida_sanitaria, medida_sanitaria)
										    VALUES ($idCatastroPredioEquidos, '$identificador', now(),  
										            '$profesionalTecnico', '$pesebreras', '$areaCuarentena',
										            '$eliminacionDesechos', '$controlVectores', '$usoAperosIndividuales', 
										            '$reportePositivoAIE', $idMedidaSanitaria, '$nombreMedidaSanitaria')
											RETURNING
												id_catastro_predio_equidos_sanidad;");
					
		return $res;
	}
	
	public function imprimirLineaSanidadPredioEquidos($idSanidadPredioEquidos, $idCatastroPredioEquidos,
													$profesionalTecnico, $pesebreras, $areaCuarentena, 
													$eliminacionDesechos, $controlVectores,
													$usoAperosIndividuales, $reportePositivoAIE, 
													$nombreMedidaSanitaria, $ruta){
	
				return '<tr id="R' . $idSanidadPredioEquidos . '">' .
						'<td width="30%">' .
						$profesionalTecnico.
						'</td>
						<td width="30%">' .
						$pesebreras.
						'</td>
						<td width="30%">' .
						$areaCuarentena.
						'</td>
						<td width="30%">' .
						$eliminacionDesechos.
						'</td>
						<td width="30%">' .
						$controlVectores.
						'</td>
						<td width="30%">' .
						$usoAperosIndividuales.
						'</td>
						<td width="30%">' .
						$reportePositivoAIE.
						'</td>
						<td width="30%">' .
						$nombreMedidaSanitaria.
						'</td>
						<td>' .
						'<form id="borrarRegistro" class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarSanidadPredioEquidos" data-destino="detalleItem" data-accionEnExito="NADA" >' .
						'<input type="hidden" id="idSanidadPredioEquidos" name="idSanidadPredioEquidos" value="' . $idSanidadPredioEquidos . '" >' .
						'<button class="icono" type="submit" ></button>' .
						'</form>' .
						'</td>' .
						'</tr>';
	}
	
	public function imprimirLineaSanidadPredioEquidosConsulta($idSanidadPredioEquidos, $idCatastroPredioEquidos,
													$profesionalTecnico, $pesebreras, $areaCuarentena, 
													$eliminacionDesechos, $controlVectores,
													$usoAperosIndividuales, $reportePositivoAIE, 
													$nombreMedidaSanitaria, $ruta){
	
				return '<tr id="R' . $idSanidadPredioEquidos . '">' .
						'<td width="30%">' .
						$profesionalTecnico.
						'</td>
						<td width="30%">' .
						$pesebreras.
						'</td>
						<td width="30%">' .
						$areaCuarentena.
						'</td>
						<td width="30%">' .
						$eliminacionDesechos.
						'</td>
						<td width="30%">' .
						$controlVectores.
						'</td>
						<td width="30%">' .
						$usoAperosIndividuales.
						'</td>
						<td width="30%">' .
						$reportePositivoAIE.
						'</td>
						<td width="30%">' .
						$nombreMedidaSanitaria.
						'</td>
						<td>
						</tr>';
	}
	
	//Archivo eliminarSanidadPredioEquidos
	public function eliminarSanidadPredioEquidos($conexion, $idSanidadPredioEquidos){
	
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_programas_control_oficial.catastro_predio_equidos_sanidad
											WHERE
												id_catastro_predio_equidos_sanidad=$idSanidadPredioEquidos;");
	
		return $res;
	}
	
	//Archivo guardarHistorialPatologiasCatastroPredioEquidos
	public function buscarHistorialPatologiasPredioEquidos($conexion, $idCatastroPredioEquidos, $nombreEnfermedad, $nombreVacuna){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programas_control_oficial.catastro_predio_equidos_historial_patologias
											WHERE
												id_catastro_predio_equidos = $idCatastroPredioEquidos and
												upper(enfermedad) = upper('$nombreEnfermedad') and
												upper(vacuna) = upper('$nombreVacuna')
											ORDER BY
												id_enfermedad, id_vacuna asc;");
	
		return $res;
	}
	
	public function nuevoHistorialPatologiaPredioEquidos($conexion, $idCatastroPredioEquidos, $identificador,
															$idEnfermedad, $enfermedad, $idVacuna, $vacuna, $laboratorio){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO 
												g_programas_control_oficial.catastro_predio_equidos_historial_patologias(
										            id_catastro_predio_equidos, identificador, fecha_creacion, 
										            id_enfermedad, enfermedad, id_vacuna, vacuna,
										            laboratorio)
										    VALUES ($idCatastroPredioEquidos, '$identificador', now(), 
										            $idEnfermedad, '$enfermedad', $idVacuna, '$vacuna', '$laboratorio')
											RETURNING
												id_catastro_predio_equidos_historial_patologias;");
			
		return $res;
	}
	
	public function imprimirLineaHistorialPatologiaPredioEquidos($idHistorialPatologiaPredioEquidos, $idCatastroPredioEquidos, 
																	$enfermedad, $vacuna, $laboratorio, $ruta){
	
				return '<tr id="R' . $idHistorialPatologiaPredioEquidos . '">' .
						'<td width="30%">' .
							$enfermedad.
							'</td>
						<td width="30%">' .
							$vacuna.
							'</td>
						<td width="30%">' .
							$laboratorio.
							'</td>
						<td>' .
							'<form id="borrarRegistro" class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarHistorialPatologiasCatastroPredioEquidos" data-destino="detalleItem" data-accionEnExito="NADA" >' .
							'<input type="hidden" id="idHistorialPatologiaPredioEquidos" name="idHistorialPatologiaPredioEquidos" value="' . $idHistorialPatologiaPredioEquidos . '" >' .
							'<button class="icono" type="submit" ></button>'.
							'</form>' .
							'</td>' .
							'</tr>';
	}
	
	public function imprimirLineaHistorialPatologiaPredioEquidosConsulta($idHistorialPatologiaPredioEquidos, $idCatastroPredioEquidos,
			$enfermedad, $vacuna, $laboratorio, $ruta){
	
				return '<tr id="R' . $idHistorialPatologiaPredioEquidos . '">' .
						'<td width="30%">' .
							$enfermedad.
							'</td>
						<td width="30%">' .
							$vacuna.
							'</td>
						<td width="30%">' .
							$laboratorio.
							'</td>
						<td>
						</tr>';
	}
	
	//Archivo eliminarHistorialPatologiasPredioEquidos
	public function eliminarHistorialPatologiasPredioEquidos($conexion, $idHistorialPatologiaPredioEquidos){
	
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_programas_control_oficial.catastro_predio_equidos_historial_patologias
											WHERE
												id_catastro_predio_equidos_historial_patologias=$idHistorialPatologiaPredioEquidos;");
	
		return $res;
	}
	
	//Archivo guardarCierreCatastroPredioEquidos
	public function cierreCatastroPredioEquidos($conexion, $idCatastroPredioEquidos, $identificador,
													$observaciones){
	
				$res = $conexion->ejecutarConsulta("UPDATE
														g_programas_control_oficial.catastro_predio_equidos
													SET
														identificador_cierre='$identificador',
														fecha_cierre=now(),
														observaciones='$observaciones',
														estado='cerrado'
													WHERE
														id_catastro_predio_equidos=$idCatastroPredioEquidos;");
	
				return $res;
	}
	
	//Archivo subirArchivo
	public function actualizarImagenMapaCatastroPredioEquidos($conexion,$idCatastroPredioEquidos,$rutaArchivo){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_programas_control_oficial.catastro_predio_equidos
											SET
												imagen_mapa='".$rutaArchivo."'
											WHERE
												id_catastro_predio_equidos=$idCatastroPredioEquidos;");
	
		return $res;
	
	}
	
	public function actualizarInformeCatastroPredioEquidos($conexion,$idCatastroPredioEquidos,$rutaArchivo){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_programas_control_oficial.catastro_predio_equidos
											SET
												ruta_informe='".$rutaArchivo."'
											WHERE
												id_catastro_predio_equidos=$idCatastroPredioEquidos;");
	
		return $res;
	
	}
	
	public function buscarCatastroPredioEquidosRegistroOperador($conexion, $idOperacion, $codigoTipoOperacion){
	    
	    $res = $conexion->ejecutarConsulta("SELECT 
                                            	o.id_operacion,
                                            	o.identificador_operador,
                                            	op.razon_social,
                                            	o.fecha_aprobacion,
                                            	o.fecha_finalizacion,
                                            	s.nombre_lugar,
                                            	s.provincia,
                                            	s.canton,
                                            	s.parroquia,
                                            	s.latitud,
                                            	s.longitud,
                                            	s.zona,
                                            	pco.nombre_predio,
                                            	pco.nombre_propietario,
                                            	pco.utm_x,
                                            	pco.utm_y,
                                            	pco.utm_z,
                                            	pco.id_catastro_predio_equidos
                                            FROM
                                            	g_operadores.operaciones o
                                            	INNER JOIN g_operadores.operadores op ON o.identificador_operador = op.identificador
                                            	INNER JOIN g_operadores.productos_areas_operacion pao ON o.id_operacion = pao.id_operacion 
                                            	INNER JOIN g_operadores.areas a ON pao.id_area = a.id_area
                                            	INNER JOIN g_operadores.sitios s ON a.id_sitio = s.id_sitio
                                            	INNER JOIN g_programas_control_oficial.catastro_predio_equidos pco ON o.identificador_operador = pco.cedula_propietario
                                            	INNER JOIN g_catalogos.tipos_operacion tio ON tio.id_tipo_operacion = o.id_tipo_operacion
                                            WHERE
                                            	o.id_operacion = $idOperacion and
                                            	tio.codigo = '$codigoTipoOperacion' and tio.id_area='SA' and
                                            	pco.provincia = s.provincia and
                                            	pco.canton = s.canton and
                                            	pco.parroquia = s.parroquia 
                                            LIMIT 1;");
	    
	    return $res;
	    
	}
}
?>