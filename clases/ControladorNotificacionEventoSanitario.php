 <?php

class ControladorNotificacionEventoSanitario{
	//-------------------------------------------------  catalogos ----------------------------------------------------------------------
	public function listarCatalogos($conexion,$tipo){
		
		$res = $conexion->ejecutarConsulta("SELECT
												* 
											FROM
												g_seguimiento_eventos_sanitarios.catalogo
											WHERE
												tipo_catalogo = '$tipo';");
	
		return $res;
		
	}

	//-------------------------------------------------  Notificacion Evento Sanitario ----------------------------------------------------------------------
	public function buscarNotificacionEventoSanitario($conexion, $idNotificacionEventoSanitario){
	
		$res = $conexion->ejecutarConsulta("SELECT 
												*
											FROM 
												g_seguimiento_eventos_sanitarios.notificacion_evento_sanitario
											WHERE 
												id_notificacion_evento_sanitario = $idNotificacionEventoSanitario;");
	
		return $res;
	}
	
	public function eliminarNotificacionEventoSanitario($conexion, $idNotificacionEventoSanitario){
	
		$res = $conexion->ejecutarConsulta("DELETE FROM 
												g_seguimiento_eventos_sanitarios.notificacion_evento_sanitario
											WHERE 
												id_notificacion_evento_sanitario=$idNotificacionEventoSanitario;");
	
		return $res;
	}
	
	public function nuevaNotificacionEventoSanitario($conexion, $idNotificacionEventoSanitario,
														$identificador,
														$numero, $fecha, $idOrigen, $nombreOrigen, $idCanal, $nombreCanal, $nombreInformante,
														$telefonoInformante, $celularInformante, $correoElectronicoInformante, 	$idProvincia,
														$nombreProvincia, $idCanton, $nombreCanton, $idParroquia, $nombreParroquia, $sitioPredio, 
														$fincaPredio, $archivoInforme){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO 
												g_seguimiento_eventos_sanitarios.notificacion_evento_sanitario(
														identificador, fecha_creacion,
														numero, fecha, id_origen, nombre_origen, id_canal, nombre_canal, nombre_informante,
														telefono_informante, celular_informante, correo_electronico_informante, id_provincia,
														provincia, id_canton, canton, id_parroquia, parroquia, sitio_predio, finca_predio, 
														estado, ruta_informe) 
											VALUES (	'$identificador',now(),
														'$numero', '$fecha', $idOrigen, '$nombreOrigen', $idCanal, '$nombreCanal', '$nombreInformante',
														'$telefonoInformante', '$celularInformante', '$correoElectronicoInformante', 	$idProvincia,
														'$nombreProvincia', $idCanton, '$nombreCanton', $idParroquia, '$nombreParroquia', '$sitioPredio', 
														'$fincaPredio' , 'Creado', '$archivoInforme') 									
											RETURNING
												id_notificacion_evento_sanitario;");
			
		return $res;
	}
	
	
	
	public function modificarNotificacionEventoSanitario($conexion, $idNotificacionEventoSanitario, $identificador, 
														$nombreInformante,$telefonoInformante, $celularInformante, $correoElectronicoInformante, $sitioPredio, $fincaPredio){
	
				$res = $conexion->ejecutarConsulta("UPDATE 
													g_seguimiento_eventos_sanitarios.notificacion_evento_sanitario 
												SET 
													nombre_informante = '$nombreInformante', 
													telefono_informante = '$telefonoInformante', 
													celular_informante = '$celularInformante', 
													correo_electronico_informante = '$correoElectronicoInformante', 
													sitio_predio = '$sitioPredio',  
													finca_predio ='$fincaPredio', 
													identificador_modificacion='$identificador', 
													fecha_modificacion=now() 
												WHERE 
													id_notificacion_evento_sanitario=$idNotificacionEventoSanitario;");

			return $res;
	}
	
	public function actualizarInformeNotificacionEventoSanitario($conexion,$idNotificacionEventoSanitario,$rutaArchivo){

		$res = $conexion->ejecutarConsulta("UPDATE 
												g_seguimiento_eventos_sanitarios.notificacion_evento_sanitario 
											SET 
												ruta_informe='".$rutaArchivo."' 
											WHERE 
												id_notificacion_evento_sanitario=".$idNotificacionEventoSanitario.";");
	
		return $res;
	
	}
	
	public function buscarNotificacionEventoSanitarioFiltrado ($conexion, $numSolicitud, $fecha, $idProvincia, $idCanton, $idParroquia, $sitio, $finca, $estado){
	
		$numSolicitud = $numSolicitud!="" ? "'%" . $numSolicitud . "%'" : "null";
		$fecha = $fecha!="" ? "'" . $fecha . "'" : "null";
		$idProvincia = $idProvincia!="" ? "" . $idProvincia . "" : "null";
		$idCanton = $idCanton!="" ? "" . $idCanton . "" : "null";
		$idParroquia = $idParroquia!="" ? "" . $idParroquia . "" : "null";
		$sitio = $sitio!="" ? "'%" . $sitio . "%'" : "null";
		$finca = $finca!="" ? "'%" . $finca . "%'" : "null";
		$estado = $estado!="" ? "'" . $estado . "'" : "null";

		$res = $conexion->ejecutarConsulta("SELECT 
												* 
											FROM 
												g_seguimiento_eventos_sanitarios.busqueda_notificacion_evento_sanitario(
												$numSolicitud, $fecha, $idProvincia, $idCanton, 
												$idParroquia, $sitio, $finca, $estado) 
											ORDER BY 
												fecha_creacion desc;");

		return $res;
	}
		
	public function abrirNotificacionEventoSanitario ($conexion, $idNotificacionEventoSanitario){
		
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_seguimiento_eventos_sanitarios.notificacion_evento_sanitario
											WHERE
												id_notificacion_evento_sanitario = '$idNotificacionEventoSanitario';");
	
		return $res;
	}
	
	//-------------------------------------------------  Patologia, Espacie afectada ----------------------------------------------------------------------
	

	public function buscarPatologiaEspecieAfectada($conexion, $idNotificacionEventoSanitario, $nombrePatologia, $nombreEspecie){

		$res = $conexion->ejecutarConsulta("SELECT 
												* 
											FROM 
												g_seguimiento_eventos_sanitarios.patologia_especie_afectada 
											WHERE 
												id_notificacion_evento_sanitario = $idNotificacionEventoSanitario and 
												upper(nombre_patologia) = upper('$nombrePatologia')  and 
												upper(nombre_especie) = upper('$nombreEspecie');");
	
		return $res;
	}
	
	public function nuevaPatologiaEspecieAfectada(	$conexion, 
													$idNotificacionEventoSanitario, $identificador, $idPatologia, 
													$nombrePatologia, $idEspecie, $nombreEspecie, 
													$animalesEnfermos, $animalesMuertos){
												
		$res = $conexion->ejecutarConsulta("INSERT INTO 
												g_seguimiento_eventos_sanitarios.patologia_especie_afectada(
										            id_notificacion_evento_sanitario, identificador, fecha_creacion, 
										            id_patologia, nombre_patologia, id_especie, nombre_especie,
													animales_enfermos, animales_muertos)
    										VALUES 
												($idNotificacionEventoSanitario, '$identificador', now(),
            									$idPatologia, '$nombrePatologia', $idEspecie, '$nombreEspecie', 
													$animalesEnfermos, $animalesMuertos)
											RETURNING 
												id_patologia_especie_afectada;");
							
		return $res;
	}
	
	
	public function imprimirLineaPatologiaEspecieAfectadaConsulta($idPatologiaEspecieAfectada,
																$idNotificacionEventoSanitario, $nombrePatologia, $nombreEspecie, 
																$animalesEnfermos, $animalesMuertos,  $ruta ){
	
		return '<tr id="R' . $idPatologiaEspecieAfectada . '">' .
					'<td width="30%">' .
						$nombrePatologia.
					'</td>' .
					'<td width="30%">' .
						$nombreEspecie.
					'</td>' .
					'<td width="30%">' .
						$animalesEnfermos.
					'</td>' .
					'<td width="30%">' .
						$animalesMuertos.
					'</td>' .
					'</tr>';
	}
	
	public function eliminarPatologiaEspecieAfectada($conexion, $idPatologiaEspecieAfectada){
	
		$res = $conexion->ejecutarConsulta("DELETE FROM 
												g_seguimiento_eventos_sanitarios.patologia_especie_afectada 
											WHERE 
												id_patologia_especie_afectada=$idPatologiaEspecieAfectada;");
	
		return $res;
	}
	
	public function listarTipoPatologiaEspecieAfectada($conexion, $idNotificacionEventoSanitario){ 
	
		$res = $conexion->ejecutarConsulta("SELECT 
												* 
											FROM 
												g_seguimiento_eventos_sanitarios.patologia_especie_afectada
											WHERE 
												id_notificacion_evento_sanitario = $idNotificacionEventoSanitario
											ORDER BY 
												id_notificacion_evento_sanitario asc;");
	
		return $res;
	}
	
	public function imprimirLineaPatologiaEspecieAfectada($idPatologiaEspecieAfectada,
																$idNotificacionEventoSanitario, $nombrePatologia, $nombreEspecie, 
																$animalesEnfermos, $animalesMuertos, $ruta ){
																	
		return '<tr id="R' . $idPatologiaEspecieAfectada . '">' .
					'<td width="30%">' .
						$nombrePatologia.
					'</td>' .
					'<td width="30%">' .
						$nombreEspecie.
					'</td>' .
					'<td width="30%">' .
						$animalesEnfermos.
					'</td>' .
					'<td width="30%">' .
						$animalesMuertos.
					'</td>' .
					'<td>' .
						'<form id="borrarRegistro" class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarPatologiaEspecieAfectada" data-destino="detalleItem" data-accionEnExito="NADA" >' .
							'<input type="hidden" name="idPatologiaEspecieAfectada" value="' . $idPatologiaEspecieAfectada . '" >' .
							'<button class="icono" type="submit" ></button>' .
						'</form>' .
					'</td>' .
				'</tr>';
	}
	
	
	//-------------------------------------------------  Cierre  Notificacion Evento Sanitario ----------------------------------------------------------------------	


	public function cierreNotificacionEventoSanitario($conexion, $idNotificacionEventoSanitario, $identificador, $fechaNuevaInspeccion, $estado, $esEventoSanitario, 
													   $justificacionEventoSanitario, $numeroSolicitud){
												
		$res = $conexion->ejecutarConsulta("UPDATE 
												g_seguimiento_eventos_sanitarios.notificacion_evento_sanitario 
											SET 
												identificador_cierre='$identificador', 
												fecha_cierre=now(), 
												fecha_nueva_inspeccion='$fechaNuevaInspeccion', 
												estado='$estado',
												es_evento_sanitario='$esEventoSanitario', 
												justificacion_evento_sanitario='$justificacionEventoSanitario',
												numero_formulario = '$numeroSolicitud' 
											WHERE 
												id_notificacion_evento_sanitario=$idNotificacionEventoSanitario;");
	
		return $res;
	}	
	
	// generacion de serial
	
	public function generarNumeroEventoSanitario($conexion, $codigo){
			
		$res = $conexion->ejecutarConsulta("SELECT 
												MAX(numero) as num_solicitud 
											FROM 
												g_seguimiento_eventos_sanitarios.notificacion_evento_sanitario 
											WHERE 
												numero LIKE '%$codigo%';");
		return $res;
	}
	
	//-----------------------------------------------------------Evento Sanitario ----------------------------------------------------------------------------------------
	
	public function generarEventoSanitario($conexion, 
														$identificador,
														$numeroFormulario, $fecha, $idOrigen, $nombreOrigen, $idCanal, $nombreCanal, 
														$idProvincia, $nombreProvincia, $idCanton, $nombreCanton, $idParroquia, $nombreParroquia,
														$sitioPredio){
															
		$res = $conexion->ejecutarConsulta("INSERT INTO 
												g_seguimiento_eventos_sanitarios.evento_sanitario(
														identificador,	fecha_creacion, numero_formulario, fecha, id_origen,  nombre_origen,
														id_canal, nombre_canal, 
														id_provincia, provincia, id_canton, canton, id_parroquia, parroquia,sitio_predio, estado) 
											VALUES (	'$identificador',now(),
														'$numeroFormulario', '$fecha', $idOrigen, '$nombreOrigen', $idCanal, '$nombreCanal', 
														 $idProvincia,'$nombreProvincia', $idCanton, '$nombreCanton', $idParroquia, '$nombreParroquia',
														 '$sitioPredio', 'Creado') 									
											RETURNING
												id_evento_sanitario;");
			
		return $res;
	}
}
?>