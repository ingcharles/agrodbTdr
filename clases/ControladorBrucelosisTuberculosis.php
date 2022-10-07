<?php

class ControladorBrucelosisTuberculosis{
	
/******** CERTIFICACIÓN BRUCELOSIS Y TUBERCULOSIS ********/
	
	//Archivo listaSolicitudCertificacionBrucelosisTuberculosis
	public function buscarCertificacionBT ($conexion, $numSolicitud, $fecha, $nombrePredio, $nombrePropietario,
											$idProvincia, $idCanton, $idParroquia, $certificacion, $estado, $idLaboratorio){
		
		$numSolicitud = $numSolicitud!="" ? "'%" . $numSolicitud . "%'" : "null";
		$fecha = $fecha!="" ? "'" . $fecha . "'" : "null";
		$nombrePredio = $nombrePredio!="" ? "'%" . $nombrePredio . "%'" : "null";
		$nombrePropietario = $nombrePropietario!="" ? "'%" . $nombrePropietario . "%'" : "null";
		$idProvincia = $idProvincia!="" ? "" . $idProvincia . "" : "null";
		$idCanton = $idCanton!="" ? "" . $idCanton . "" : "null";
		$idParroquia = $idParroquia!="" ? "" . $idParroquia . "" : "null";
		$certificacion = $certificacion!="" ? "'" . $certificacion . "'" : "null";
		$estado = $estado!="" ? "'" . $estado . "'" : "null";	
		$idLaboratorio = $idLaboratorio!="" ? "" . $idLaboratorio . "" : "null";
		
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.busqueda_certificados_bt(
												$numSolicitud, $fecha, $nombrePredio,
												$nombrePropietario, $idProvincia, $idCanton,
												$idParroquia, $certificacion, $estado, $idLaboratorio);");
												
		return $res;
	}
	
	//Archivo nuevaCertificacionBT
	public function buscarOperadorBovinos($conexion, $identificador){
				
		$res = $conexion->ejecutarConsulta("select
												distinct(op.*)
											from
												g_operadores.operaciones o,
												g_operadores.operadores op
											where
												op.identificador = o.identificador_operador and
												o.identificador_operador = '$identificador' and
												o.id_producto in (
															select 
																p.id_producto
															from
																g_catalogos.tipo_productos tp,
																g_catalogos.subtipo_productos sp,
																g_catalogos.productos p
															where 
																tp.id_area='SA' and
																tp.id_tipo_producto=sp.id_tipo_producto and
																sp.id_subtipo_producto=p.id_subtipo_producto and
																upper(sp.nombre) like upper('bovinos')
														) and
												o.estado = 'registrado';");	
							
		return $res;
	}	
	
	public function buscarSitiosOperador($conexion, $identificador){
	
		$res = $conexion->ejecutarConsulta("select
												--tp.id_tipo_operacion,
												--tp.nombre,
												--o.id_producto,
												--o.nombre_producto,
												distinct(s.*)
											from
												g_operadores.operaciones o,
												g_operadores.productos_areas_operacion sa,
												g_operadores.areas a,
												g_operadores.sitios s,
												g_catalogos.tipos_operacion tp
											where
												o.id_tipo_operacion = tp.id_tipo_operacion and
												o.identificador_operador = '$identificador' and
												o.id_producto in (
																	select
																		p.id_producto
																	from
																		g_catalogos.tipo_productos tp,
																		g_catalogos.subtipo_productos sp,
																		g_catalogos.productos p
																	where
																		tp.id_area='SA' and
																		tp.id_tipo_producto=sp.id_tipo_producto and
																		sp.id_subtipo_producto=p.id_subtipo_producto and
																		upper(sp.nombre) like upper('bovinos')
												) and
												o.id_operacion = sa.id_operacion and
												a.id_area = sa.id_area and
												a.id_sitio = s.id_sitio and
												o.estado = 'registrado';");
	
	
			
		return $res;
	}
	
	public function buscarSitioOperador($conexion, $identificador, $idSitio){
	
		$res = $conexion->ejecutarConsulta("select
												distinct(s.*)
											from
												g_operadores.sitios s
											where
												s.identificador_operador = '$identificador' and
												s.id_sitio = $idSitio and
												s.estado = 'creado';");
	
	
			
		return $res;
	}
	
	//Archivo guardarCertificacionBT
	public function generarNumeroCertificacionBT($conexion, $codigo){
	
		$res = $conexion->ejecutarConsulta("SELECT
												MAX(num_solicitud) as num_solicitud
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt
											WHERE
												num_solicitud LIKE '%$codigo%';");
		
		return $res;
	}
	
	public function nuevaCertificacionBT ($conexion, $identificador, $numSolicitud, $fecha, 
								            $nombreEncuestado, $idPredio, $nombrePredio, $nombrePropietario, 
											$cedulaPropietario, $telefonoPropietario, $celularPropietario, 
											$correoElectronicoPropietario, $idProvincia, $provincia, $idCanton, 
								            $canton, $idParroquia, $parroquia, $numCertFiebreAftosa, 
											$certificacion, $x, $y, $z, $huso, $imagenMapa, $informe, $numeroInspeccion){
		
		$res = $conexion->ejecutarConsulta("INSERT INTO g_certificacion_brucelosis_tuberculosis.certificacion_bt(
									            identificador, fecha_creacion, num_solicitud,
									            fecha, nombre_encuestado, id_predio, nombre_predio, nombre_propietario, 
									            cedula_propietario, telefono_propietario, celular_propietario, 
									            correo_electronico_propietario, id_provincia, provincia, id_canton, 
									            canton, id_parroquia, parroquia, numero_certificado_fiebre_aftosa, 
									            certificacion_bt, utm_x, utm_y, utm_z, huso_zona, estado, imagen_mapa, 
												ruta_informe, num_inspeccion)
										    VALUES ('$identificador', now(), '$numSolicitud',
										            '$fecha', '$nombreEncuestado', $idPredio, '$nombrePredio', '$nombrePropietario', 
										            '$cedulaPropietario', '$telefonoPropietario', '$celularPropietario', 
										            '$correoElectronicoPropietario', $idProvincia, '$provincia', $idCanton, 
										            '$canton', $idParroquia, '$parroquia', '$numCertFiebreAftosa', 
										            '$certificacion', '$x', '$y', '$z', '$huso', 'activo', 
													'$imagenMapa', '$informe', '$numeroInspeccion')
											RETURNING id_certificacion_bt;");
		
		return $res;
	}
	
	public function generarNumeroInspeccionCertificacionBT($conexion, $codigo, $numSolicitud){
	
		$res = $conexion->ejecutarConsulta("SELECT
												MAX(num_inspeccion) as num_inspeccion
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt
											WHERE
												num_inspeccion LIKE '%$codigo%' and
												num_solicitud = '$numSolicitud';");
	
		return $res;
	}
	
	//Archivo abrirCertificacionBT
	public function abrirCatalogoLaboratoriosCertificacionBT ($conexion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.laboratorios
											WHERE
												estado = 'activo';");
	
		return $res;
	}
	
	public function abrirCertificacionBT ($conexion, $idCertificacionBT){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt
											WHERE
												id_certificacion_bt = $idCertificacionBT;");
	
		return $res;
	}
	
	public function abrirInformacionPredioCertificacionBT ($conexion, $idCertificacionBT){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_informacion_predio
											WHERE
												id_certificacion_bt = $idCertificacionBT;");
	
		return $res;
	}
	
	public function abrirProduccionCertificacionBT ($conexion, $idCertificacionBT){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_produccion
											WHERE
												id_certificacion_bt = $idCertificacionBT;");
	
		return $res;
	}
	
	public function abrirInventarioAnimalCertificacionBT ($conexion, $idCertificacionBT){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_inventario_animal
											WHERE
												id_certificacion_bt = $idCertificacionBT;");
	
		return $res;
	}
	
	public function abrirPediluvioCertificacionBT ($conexion, $idCertificacionBT){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_pediluvio
											WHERE
												id_certificacion_bt = $idCertificacionBT;");
	
		return $res;
	}
	
	public function abrirManejoAnimalCertificacionBT ($conexion, $idCertificacionBT){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_manejo_animales_potreros
											WHERE
												id_certificacion_bt = $idCertificacionBT;");
	
		return $res;
	}
	
	public function abrirAdquisicionAnimalesCertificacionBT ($conexion, $idCertificacionBT){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_adquisicion_animales
											WHERE
												id_certificacion_bt = $idCertificacionBT;");
	
		return $res;
	}
	
	public function abrirProcedenciaAguaCertificacionBT ($conexion, $idCertificacionBT){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_procedencia_agua
											WHERE
												id_certificacion_bt = $idCertificacionBT;");
	
		return $res;
	}
	
	public function abrirVeterinarioCertificacionBT ($conexion, $idCertificacionBT){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_veterinario
											WHERE
												id_certificacion_bt = $idCertificacionBT;");
	
		return $res;
	}
	
	public function abrirVacunacionCertificacionBT ($conexion, $idCertificacionBT){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_informacion_vacunacion
											WHERE
												id_certificacion_bt = $idCertificacionBT;");
	
		return $res;
	}
	
	public function abrirReproduccionCertificacionBT ($conexion, $idCertificacionBT){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_reproduccion
											WHERE
												id_certificacion_bt = $idCertificacionBT;");
	
		return $res;
	}
	
	public function abrirPatologiaBrucelosisCertificacionBT ($conexion, $idCertificacionBT){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_patologia_brucelosis
											WHERE
												id_certificacion_bt = $idCertificacionBT;");
	
		return $res;
	}
	
	public function abrirAbortosBrucelosisCertificacionBT ($conexion, $idCertificacionBT){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_abortos_brucelosis
											WHERE
												id_certificacion_bt = $idCertificacionBT;");
	
		return $res;
	}
	
	public function abrirPruebasBrucelosisLecheCertificacionBT ($conexion, $idCertificacionBT){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_prueba_brucelosis_leche
											WHERE
												id_certificacion_bt = $idCertificacionBT;");
	
		return $res;
	}
	
	public function abrirPruebasBrucelosisSangreCertificacionBT ($conexion, $idCertificacionBT){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_prueba_brucelosis_sangre
											WHERE
												id_certificacion_bt = $idCertificacionBT;");
	
		return $res;
	}
	
	public function abrirPatologiaTuberculosisCertificacionBT ($conexion, $idCertificacionBT){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_patologia_tuberculosis
											WHERE
												id_certificacion_bt = $idCertificacionBT;");
	
		return $res;
	}
	
	public function abrirPruebaTuberculosisLecheCertificacionBT ($conexion, $idCertificacionBT){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_prueba_tuberculosis_leche
											WHERE
												id_certificacion_bt = $idCertificacionBT;");
	
		return $res;
	}
	
	public function abrirPruebaTuberculinaCertificacionBT ($conexion, $idCertificacionBT){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_prueba_tuberculina
											WHERE
												id_certificacion_bt = $idCertificacionBT;");
	
		return $res;
	}
	
	//Archivo subirArchivo
	public function actualizarImagenMapaCertificacionBT($conexion,$idCertificacionBT,$rutaArchivo){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_certificacion_brucelosis_tuberculosis.certificacion_bt
											SET
												imagen_mapa='$rutaArchivo'
											WHERE
												id_certificacion_bt = '$idCertificacionBT';");
	
		return $res;
	
	}
	
	public function actualizarInformeCertificacionBT($conexion,$idCertificacionBT,$rutaArchivo){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_certificacion_brucelosis_tuberculosis.certificacion_bt
											SET
												ruta_informe='$rutaArchivo'
											WHERE
												id_certificacion_bt = '$idCertificacionBT';");
	
		return $res;
	
	}
	
	public function actualizarInformeRecertificacionBT($conexion,$idRecertificacionBT,$rutaArchivo){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_certificacion_brucelosis_tuberculosis.recertificacion_bt
											SET
												ruta_informe='$rutaArchivo'
											WHERE
												id_recertificacion_bt = '$idRecertificacionBT';");
	
		return $res;
	
	}
	
	//Archivo modificarCertificacionBT
	public function modificarCertificacionBT ($conexion, $idCertificacionBT, $identificador, $fecha, $nombreEncuestado,  
												$telefonoPropietario, 
												$celularPropietario, $correoElectronicoPropietario,  
												$numCertFiebreAftosa, $x, $y, $z, $huso){
	
		$res = $conexion->ejecutarConsulta("UPDATE 
												g_certificacion_brucelosis_tuberculosis.certificacion_bt
											SET 
												fecha='$fecha', 
												nombre_encuestado='$nombreEncuestado', 
												telefono_propietario='$telefonoPropietario', 
												celular_propietario='$celularPropietario', 
											    correo_electronico_propietario='$correoElectronicoPropietario', 
												numero_certificado_fiebre_aftosa='$numCertFiebreAftosa', 
											    utm_x='$x', 
												utm_y='$y', 
												utm_z='$z', 
												huso_zona='$huso', 
												identificador_modificacion='$identificador', 
												fecha_modificacion=now()
											 WHERE 
												id_certificacion_bt=$idCertificacionBT;");
	
		return $res;
	}
	
	//Archivo guardarDatosGeneralesCertificacionBT
	public function buscarInformacionPredioCertificacionBT($conexion, $idCertificacionBT, $numInspeccion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_informacion_predio
											WHERE
												id_certificacion_bt = $idCertificacionBT and
												num_inspeccion = '$numInspeccion';");
	
		return $res;
	}
	
	public function guardarInformacionPredioCertificacionBT ($conexion, $idCertificacionBT, $identificador, 
																$superficiePredio, $superficiePastos, $cerramientoExterno,
																$controlIngresoPersonas, $mangaEmbudoBrete, 
																$identificacionBovinos, $controlIngresoAnimales, $numInspeccion){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO 
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_informacion_predio(
										            identificador, fecha_creacion, 
										            id_certificacion_bt, superficie_predio, superficie_pastos, 
													cerramientos, control_ingreso_personas, manga_embudo_brete,
										             identificacion_bovinos, control_ingreso_animal, num_inspeccion)
										    VALUES ('$identificador', now(), 
										            $idCertificacionBT, $superficiePredio, $superficiePastos, 
													'$cerramientoExterno', '$controlIngresoPersonas', '$mangaEmbudoBrete',
										            '$identificacionBovinos', '$controlIngresoAnimales', '$numInspeccion')
											RETURNING
												id_certificacion_bt_informacion_predio;");
	
		return $res;
	}
	
	public function imprimirLineaInformacionPredioCertificacionBT($idInformacionPredio, $superficiePredio,
			$superficiePastos, $cerramientoExterno, $controlIngresoPersonas, $mangaEmbudoBrete, 
			$identificacionBovinos, $controlIngresoAnimales, $ruta, $numInspeccion){
	
				return '<tr id="R' . $idInformacionPredio . '">
						<td width="30%">' .
						$numInspeccion.
						'</td>
						<td width="30%">' .
						$superficiePredio.
						'</td>
						<td width="30%">' .
						$superficiePastos.
						'</td>
						<td width="30%">' .
						$cerramientoExterno.
						'</td>
						<td width="30%">' .
						$controlIngresoPersonas.
						'</td>
						<td width="30%">' .
						$mangaEmbudoBrete.
						'</td>
						<td width="30%">' .
						$identificacionBovinos.
						'</td>
						<td width="30%">' .
						$controlIngresoAnimales.
						'</td>
						<td>' .
						'<form id="borrarRegistro" class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarDatosGenerales" data-destino="detalleItem" data-accionEnExito="NADA" >' .
						'<input type="hidden" id="idInformacionPredio" name="idInformacionPredio" value="' . $idInformacionPredio . '" >' .
						'<button class="icono" type="submit" ></button>' .
						'</form>' .
						'</td>' .
						'</tr>';
	}
	
	public function imprimirLineaInformacionPredioBTConsulta($idInformacionPredio, $superficiePredio,
			$superficiePastos, $cerramientoExterno, $controlIngresoPersonas, $mangaEmbudoBrete, 
			$identificacionBovinos, $controlIngresoAnimales, $ruta, $numInspeccion){
	
				return '<tr id="R' . $idInformacionPredio . '"> 
						<td width="30%">' .
						$numInspeccion.
						'</td>
						<td width="30%">' .
						$superficiePredio.
						'</td>
						<td width="30%">' .
						$superficiePastos.
						'</td>
						<td width="30%">' .
						$cerramientoExterno.
						'</td>
						<td width="30%">' .
						$controlIngresoPersonas.
						'</td>
						<td width="30%">' .
						$mangaEmbudoBrete.
						'</td>
						<td width="30%">' .
						$identificacionBovinos.
						'</td>
						<td width="30%">' .
						$controlIngresoAnimales.
						'</td>
						</tr>';
	}
	
	//Archivo eliminarDatosGenerales
	public function eliminarInformacionPredio($conexion, $idInformacionPredio){
	
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_informacion_predio
											WHERE
												id_certificacion_bt_informacion_predio=$idInformacionPredio;");
	
		return $res;
	}
	
	//Archivo guardarProduccionExplotacionDestino
	public function buscarProduccionExplotacionDestinoCertificacionBT($conexion, $idCertificacionBT, 
											$idTipoProduccion, $idDestinoLeche, $idTipoExplotacion, $numInspeccion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_produccion
											WHERE
												id_certificacion_bt = $idCertificacionBT and
												id_tipo_produccion = $idTipoProduccion and
												id_destino_leche = $idDestinoLeche and
												id_tipo_explotacion = $idTipoExplotacion and
												num_inspeccion = '$numInspeccion';");
	
		return $res;
	}
	
	public function guardarProduccionExplotacionDestinoCertificacionBT ($conexion, $identificador, $idCertificacionBT, 
											$idTipoProduccion, $tipoProduccion, $idDestinoLeche, 
											$destinoLeche, $idTipoExplotacion, $tipoExplotacion, $numInspeccion){
												
		$res = $conexion->ejecutarConsulta("INSERT INTO 
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_produccion(
										            identificador, fecha_creacion, 
										            id_certificacion_bt, id_tipo_produccion, tipo_produccion, id_destino_leche, 
										            destino_leche, id_tipo_explotacion, tipo_explotacion, num_inspeccion)
										    VALUES ('$identificador', now(),  
										            $idCertificacionBT, $idTipoProduccion, '$tipoProduccion', $idDestinoLeche, 
										            '$destinoLeche', $idTipoExplotacion, '$tipoExplotacion', '$numInspeccion')
											RETURNING
												id_certificacion_bt_produccion;");

		return $res;
	}
	
	public function imprimirLineaProduccionCertificacionBT($idProduccion, $tipoProduccion, $destinoLeche, 
															$tipoExplotacion, $ruta, $numInspeccion){
	
				return '<tr id="R' . $idProduccion . '">
						<td width="30%">' .
						$numInspeccion.
						'</td>
						<td width="30%">' .
						$tipoProduccion.
						'</td>
						<td width="30%">' .
							$destinoLeche.
							'</td>
						<td width="30%">' .
							$tipoExplotacion.
							'</td>
						<td>' .
							'<form id="borrarRegistro" class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarProduccionExplotacionDestino" data-destino="detalleItem" data-accionEnExito="NADA" >' .
							'<input type="hidden" id="idProduccion" name="idProduccion" value="' . $idProduccion . '" >' .
							'<button class="icono" type="submit" ></button>' .
							'</form>' .
							'</td>' .
							'</tr>';
	}
	
	public function imprimirLineaProduccionCertificacionBTConsulta($idProduccion, $tipoProduccion, $destinoLeche, 
															$tipoExplotacion, $ruta, $numInspeccion){
	
				return '<tr id="R' . $idProduccion . '">
						<td width="30%">' .
						$numInspeccion.
						'</td>
						<td width="30%">' .
						$tipoProduccion.
						'</td>
						<td width="30%">' .
						$destinoLeche.
						'</td>
						<td width="30%">' .
						$tipoExplotacion.
						'</td>
						</tr>';
	}
	
	//Archivo eliminarProduccionExplotacionDestino
	public function eliminarProduccionExplotacionDestino($conexion, $idProduccion){
	
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_produccion
											WHERE
												id_certificacion_bt_produccion=$idProduccion;");
	
		return $res;
	}
	
	//Archivo guardarInventarioAnimalCertificacionBT
	public function buscarInventarioAnimalCertificacionBT($conexion, $idCertificacionBT, $idAnimalesPredio, $numInspeccion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_inventario_animal
											WHERE
												id_certificacion_bt = $idCertificacionBT and
												id_animales_predio = $idAnimalesPredio and
												num_inspeccion = '$numInspeccion';");
	
		return $res;
	}
	
	public function guardarInventarioAnimalCertificacionBT ($conexion, $idCertificacionBT, $identificador,
																$idAnimalesPredio, $animalesPredio, $numeroAnimalesPredio,
																$numInspeccion){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_inventario_animal(
												identificador, fecha_creacion, id_certificacion_bt, id_animales_predio,  
            									animales_predio, numero_animales_predio, num_inspeccion)
											VALUES ('$identificador', now(), $idCertificacionBT, $idAnimalesPredio,
													'$animalesPredio', $numeroAnimalesPredio, '$numInspeccion')
											RETURNING
												id_certificacion_bt_inventario_animal;");

		return $res;
	}
	
	public function imprimirLineaInventarioAnimalCertificacionBT($idInventarioAnimal, $animalesPredio, 
																	$numeroAnimalesPredio, $ruta, $numInspeccion){
	
				return '<tr id="R' . $idInventarioAnimal . '">
						<td width="30%">' .
						$numInspeccion.
						'</td>
						<td width="30%">' .
						$animalesPredio.
						'</td>
						<td width="30%">' .
							$numeroAnimalesPredio.
							'</td>
						<td>' .
							'<form id="borrarRegistro" class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarInventarioAnimal" data-destino="detalleItem" data-accionEnExito="NADA" >' .
							'<input type="hidden" id="idInventarioAnimal" name="idInventarioAnimal" value="' . $idInventarioAnimal . '" >' .
							'<button class="icono" type="submit" ></button>' .
							'</form>' .
							'</td>' .
							'</tr>';
	}
	
	public function imprimirLineaInventarioPredioCertificacionBTConsulta($idInventarioAnimal, $animalesPredio, 
																		$numeroAnimalesPredio, $ruta, $numInspeccion){
	
				return '<tr id="R' . $idInventarioAnimal . '">
						<td width="30%">' .
						$numInspeccion.
						'</td>
						<td width="30%">' .
						$animalesPredio.
						'</td>
						<td width="30%">' .
							$numeroAnimalesPredio.
							'</td>
						</tr>';
	}
	
	//Archivo eliminarInventarioAnimal
	public function eliminarInventarioAnimal($conexion, $idInventarioAnimal){
	
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_inventario_animal
											WHERE
												id_certificacion_bt_inventario_animal=$idInventarioAnimal;");
	
		return $res;
	}
	
	//Archivo guardarPediluvio
	public function buscarPediluvioCertificacionBT($conexion, $idCertificacionBT, $idPediluvio, $numInspeccion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_pediluvio
											WHERE
												id_certificacion_bt = $idCertificacionBT and
												id_pediluvio = $idPediluvio and
												num_inspeccion = '$numInspeccion';");
	
		return $res;
	}
	
	public function guardarPediluvioCertificacionBT ($conexion, $idCertificacionBT, $identificador,
														$idPediluvio, $pediluvio, $numInspeccion){
	
				$res = $conexion->ejecutarConsulta("INSERT INTO
														g_certificacion_brucelosis_tuberculosis.certificacion_bt_pediluvio(
														identificador, fecha_creacion, id_certificacion_bt, id_pediluvio,
														pediluvio, num_inspeccion)
													VALUES ('$identificador', now(), $idCertificacionBT, $idPediluvio,
														'$pediluvio', '$numInspeccion')
													RETURNING
														id_certificacion_bt_pediluvio;");
	
				return $res;
	}
	
	public function imprimirLineaPediluvioCertificacionBT($idPediluvio, $pediluvio, $ruta, $numInspeccion){
	
				return '<tr id="R' . $idPediluvio . '">
						<td width="30%">' .
						$numInspeccion.
							'</td>
						<td width="30%">' .
						$pediluvio.
							'</td>
						<td>' .
							'<form id="borrarRegistro" class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarPediluvio" data-destino="detalleItem" data-accionEnExito="NADA" >' .
							'<input type="hidden" id="idPediluvio" name="idPediluvio" value="' . $idPediluvio . '" >' .
							'<button class="icono" type="submit" ></button>' .
							'</form>' .
							'</td>' .
							'</tr>';
	}
	
	public function imprimirLineaPediluvioCertificacionBTConsulta($idPediluvio, $pediluvio, $ruta, $numInspeccion){
	
				return '<tr id="R' . $idPediluvio . '">
						<td width="30%">' .
						$numInspeccion.
							'</td>
						<td width="30%">' .
						$pediluvio.
							'</td>
						</tr>';
	}
	
	//Archivo eliminarPediluvio
	public function eliminarPediluvio($conexion, $idPediluvio){
		
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_pediluvio
											WHERE
												id_certificacion_bt_pediluvio=$idPediluvio;");
	
		return $res;
	}
	
	//Archivo guardarManejoAnimalesPotreros
	public function buscarManejoAnimalesPotrerosCertificacionBT($conexion, $idCertificacionBT, $numInspeccion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_manejo_animales_potreros
											WHERE
												id_certificacion_bt = $idCertificacionBT and
												num_inspeccion = '$numInspeccion';");
	
		return $res;
	}
	
	public function guardarManejoAnimalesPotrerosCertificacionBT ($conexion, $idCertificacionBT, $identificador,
																	$pastosComunales, $arriendaPotreros, 
																	$arriendaPotrerosOtroPredio,
																	$utilizaEstiercol, $feriaExposicion, 
																	$desinfectaAnimales, $trabajadoresAnimalesPredio, 
																	$programaPrediosLibres, $numInspeccion){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO 
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_manejo_animales_potreros(
										            identificador, fecha_creacion, id_certificacion_bt, 
										            pastos_comunales, arrienda_potreros, 
										            arrienda_otros_potreros, estiercol_abono, 
													animales_ferias, desinfecta_animales, 
										            trabajadores_animales_predio, dentro_programa_predios_libres,
													num_inspeccion)
										    VALUES ('$identificador', now(),  $idCertificacionBT,
										            '$pastosComunales', '$arriendaPotreros',
										            '$arriendaPotrerosOtroPredio', '$utilizaEstiercol', 
										            '$feriaExposicion', '$desinfectaAnimales',
													'$trabajadoresAnimalesPredio', '$programaPrediosLibres',
													'$numInspeccion')
											RETURNING
												id_certificacion_bt_manejo_animales_potreros;");

		return $res;
	}
	
	public function imprimirLineaManejoAnimalesPotrerosCertificacionBT($idManejoAnimal, $pastosComunales, 
																		$arriendaPotreros, 
																		$arriendaPotrerosOtroPredio,
																		$utilizaEstiercol, $feriaExposicion, 
																		$desinfectaAnimales, 
																		$trabajadoresAnimalesPredio, 
																		$programaPrediosLibres, $ruta, $numInspeccion){
	
				return '<tr id="R' . $idManejoAnimal . '">
						<td width="30%">' .
						$numInspeccion.
						'</td>
						<td width="30%">' .
						$pastosComunales.
						'</td>
						<td width="30%">' .
							$arriendaPotreros.
							'</td>
						<td width="30%">' .
							$arriendaPotrerosOtroPredio.
							'</td>
						<td width="30%">' .
							$utilizaEstiercol.
							'</td>
						<td width="30%">' .
							$feriaExposicion.
							'</td>
						<td width="30%">' .
							$desinfectaAnimales.
							'</td>
						<td width="30%">' .
							$trabajadoresAnimalesPredio.
							'</td>
						<td width="30%">' .
							$programaPrediosLibres.
							'</td>
						<td>' .
							'<form id="borrarRegistro" class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarManejoAnimalesPredio" data-destino="detalleItem" data-accionEnExito="NADA" >' .
							'<input type="hidden" id="idManejoAnimal" name="idManejoAnimal" value="' . $idManejoAnimal . '" >' .
							'<button class="icono" type="submit" ></button>' .
							'</form>' .
							'</td>' .
							'</tr>';
	}
	
	public function imprimirLineaManejoAnimalesPotrerosCertificacionBTConsulta($idManejoAnimal, $pastosComunales, 
																				$arriendaPotreros, 
																				$arriendaPotrerosOtroPredio,
																				$utilizaEstiercol, $feriaExposicion, 
																				$desinfectaAnimales, 
																				$trabajadoresAnimalesPredio, 
																				$programaPrediosLibres, $ruta, $numInspeccion){
	
				return '<tr id="R' . $idManejoAnimal . '">
						<td width="30%">' .
						$numInspeccion.
						'</td>
						<td width="30%">' .
						$pastosComunales.
						'</td>
						<td width="30%">' .
							$arriendaPotreros.
							'</td>
						<td width="30%">' .
							$arriendaPotrerosOtroPredio.
							'</td>
						<td width="30%">' .
							$utilizaEstiercol.
							'</td>
						<td width="30%">' .
							$feriaExposicion.
							'</td>
						<td width="30%">' .
							$desinfectaAnimales.
							'</td>
						<td width="30%">' .
							$trabajadoresAnimalesPredio.
							'</td>
						<td width="30%">' .
							$programaPrediosLibres.
							'</td>
						</tr>';
	}
	
	//Archivo eliminarManejoAnimal
	public function eliminarManejoAnimal($conexion, $idManejoAnimal){

		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_manejo_animales_potreros
											WHERE
												id_certificacion_bt_manejo_animales_potreros=$idManejoAnimal;");
	
		return $res;
	}
	
	//Archivo guardarAdquisicionAnimalesCertificacionBT
	public function buscarAdquisicionAnimalesCertificacionBT($conexion, $idCertificacionBT, $idProcedenciaAnimales, 
																$idCategoriaAnimalesAdquiere, $numInspeccion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_adquisicion_animales
											WHERE
												id_certificacion_bt = $idCertificacionBT and
												id_procedencia_animales = $idProcedenciaAnimales and
												id_categoria_animales_adquiriente = $idCategoriaAnimalesAdquiere and
												num_inspeccion = '$numInspeccion';");
	
		return $res;
	}
	
	public function guardarAdquisicionAnimalesCertificacionBT ($conexion, $idCertificacionBT, $identificador,
																$idProcedenciaAnimales, $procedenciaAnimales,  
																$idCategoriaAnimalesAdquiere, $categoriaAnimalesAdquiere,
																$numInspeccion){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO 
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_adquisicion_animales(
										            identificador, fecha_creacion, 
										            id_certificacion_bt, id_procedencia_animales, procedencia_animales, 
										            id_categoria_animales_adquiriente, categoria_animales_adquiriente,
													num_inspeccion)
										    VALUES ('$identificador', now(), 
										            $idCertificacionBT, $idProcedenciaAnimales, '$procedenciaAnimales', 
										            $idCategoriaAnimalesAdquiere, '$categoriaAnimalesAdquiere',
													'$numInspeccion')
											RETURNING
												id_certificacion_bt_adquisicion_animales;");

		return $res;
	}
	
	public function imprimirLineaAdquisicionAnimalesCertificacionBT($idAdquisicionAnimal, $procedenciaAnimales,   
																	$categoriaAnimalesAdquiere, $ruta, $numInspeccion){
	
				return '<tr id="R' . $idAdquisicionAnimal . '">
						<td width="30%">' .
						$numInspeccion.
						'</td>
						<td width="30%">' .
						$procedenciaAnimales.
						'</td>
						<td width="30%">' .
							$categoriaAnimalesAdquiere.
							'</td>
						<td>' .
							'<form id="borrarRegistro" class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarAdquisicionAnimales" data-destino="detalleItem" data-accionEnExito="NADA" >' .
							'<input type="hidden" id="idAdquisicionAnimal" name="idAdquisicionAnimal" value="' . $idAdquisicionAnimal . '" >' .
							'<button class="icono" type="submit" ></button>' .
							'</form>' .
							'</td>' .
							'</tr>';
	}
	
	public function imprimirLineaAdquisicionAnimalesCertificacionBTConsulta($idAdquisicionAnimal, $procedenciaAnimales,   
																	$categoriaAnimalesAdquiere, $ruta, $numInspeccion){
	
				return '<tr id="R' . $idAdquisicionAnimal . '">
						<td width="30%">' .
						$numInspeccion.
						'</td>
						<td width="30%">' .
						$procedenciaAnimales.
						'</td>
						<td width="30%">' .
							$categoriaAnimalesAdquiere.
							'</td>
						</tr>';
	}
	
	//Archivo eliminarAdquisicionAnimal
	public function eliminarAdquisicionAnimal($conexion, $idAdquisicionAnimal){
	
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_adquisicion_animales
											WHERE
												id_certificacion_bt_adquisicion_animales=$idAdquisicionAnimal;");
	
		return $res;
	}
	
	//Archivo guardarProcedenciaAguaCertificacionBT
	public function buscarProcedenciaAguaCertificacionBT($conexion, $idCertificacionBT, $idProcedenciaAgua, $numInspeccion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_procedencia_agua
											WHERE
												id_certificacion_bt = $idCertificacionBT and
												id_procedencia_agua = $idProcedenciaAgua and
												num_inspeccion = '$numInspeccion';");

		return $res;
	}
	
	public function guardarProcedenciaAguaCertificacionBT ($conexion, $idCertificacionBT, $identificador,
																$idProcedenciaAgua, $procedenciaAgua, $numInspeccion){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO
											g_certificacion_brucelosis_tuberculosis.certificacion_bt_procedencia_agua(
												identificador, fecha_creacion,
												id_certificacion_bt, id_procedencia_agua, procedencia_agua, num_inspeccion)
											VALUES ('$identificador', now(),
												$idCertificacionBT, $idProcedenciaAgua, '$procedenciaAgua', '$numInspeccion')
											RETURNING
												id_certificacion_bt_procedencia_agua;");
	
		return $res;
	}
	
	public function imprimirLineaProcedenciaAguaCertificacionBT($idProcedenciaAguaPredio, $procedenciaAgua, $ruta, $numInspeccion){
	
				return '<tr id="R' . $idProcedenciaAguaPredio . '">
						<td width="30%">' .
						$numInspeccion.
							'</td>
						<td width="30%">' .
						$procedenciaAgua.
							'</td>
						<td>' .
							'<form id="borrarRegistro" class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarProcedenciaAgua" data-destino="detalleItem" data-accionEnExito="NADA" >' .
							'<input type="hidden" id="idProcedenciaAgua" name="idProcedenciaAgua" value="' . $idProcedenciaAguaPredio . '" >' .
							'<button class="icono" type="submit" ></button>' .
							'</form>' .
							'</td>' .
							'</tr>';
	}
	
	public function imprimirLineaProcedenciaAguaCertificacionBTConsulta($idProcedenciaAguaPredio, $procedenciaAgua, $ruta, $numInspeccion){
	
				return '<tr id="R' . $idProcedenciaAguaPredio . '">
						<td width="30%">' .
						$numInspeccion.
							'</td>
						<td width="30%">' .
						$procedenciaAgua.
							'</td>
						</tr>';
	}
	
	//Archivo eliminarProcedenciaAgua
	public function eliminarProcedenciaAgua($conexion, $idProcedenciaAguaPredio){
	
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_procedencia_agua
											WHERE
												id_certificacion_bt_procedencia_agua=$idProcedenciaAguaPredio;");
	
		return $res;
	}
	
	//Archivo guardarVeterinarioCertificacionBT
	public function buscarVeterinarioCertificacionBT($conexion, $idCertificacionBT, $numInspeccion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_veterinario
											WHERE
												id_certificacion_bt = $idCertificacionBT and
												num_inspeccion = '$numInspeccion';");
	
		return $res;
	}
	
	public function guardarVeterinarioCertificacionBT($conexion, $idCertificacionBT, $identificador,
														$nombreVeterinario, $telefonoVeterinario, $celularVeterinario,
														$correoElectronicoVeterinario, $idFrecuenciaVisitaVeterinario,
														$frecuenciaVisitaVeterinario, $numInspeccion){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO 
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_veterinario(
										            identificador, fecha_creacion, 
										            id_certificacion_bt, nombre_veterinario, telefono_veterinario, 
										            celular_veterinario, correo_electronico_veterinario,  
										            id_frecuencia_visita_veterinario, frecuencia_visita_veterinario,
													num_inspeccion)
										    VALUES ('$identificador', now(), 
													$idCertificacionBT, '$nombreVeterinario', '$telefonoVeterinario', 
										            '$celularVeterinario', '$correoElectronicoVeterinario', 
													$idFrecuenciaVisitaVeterinario, '$frecuenciaVisitaVeterinario',
													'$numInspeccion')
											RETURNING
												id_certificacion_bt_veterinario;");
	
		return $res;
	}
	
	public function imprimirLineaVeterinarioCertificacionBT($idVeterinario, $nombreVeterinario, $telefonoVeterinario, 
														$celularVeterinario, $correoElectronicoVeterinario, 
														$frecuenciaVisitaVeterinario, $ruta, $numInspeccion){
	
				return '<tr id="R' . $idVeterinario . '">
						<td width="30%">' .
						$numInspeccion.
						'</td>
						<td width="30%">' .
						$nombreVeterinario.
						'</td>
						<td width="30%">' .
							$telefonoVeterinario.
							'</td>
						<td width="30%">' .
							$celularVeterinario.
							'</td>
						<td width="30%">' .
							$correoElectronicoVeterinario.
							'</td>
						<td width="30%">' .
							$frecuenciaVisitaVeterinario.
							'</td>
						<td>' .
							'<form id="borrarRegistro" class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarVeterinario" data-destino="detalleItem" data-accionEnExito="NADA" >' .
							'<input type="hidden" id="idVeterinario" name="idVeterinario" value="' . $idVeterinario . '" >' .
							'<button class="icono" type="submit" ></button>' .
							'</form>' .
							'</td>' .
							'</tr>';
	}
	
	public function imprimirLineaVeterinarioCertificacionBTConsulta($idVeterinario, $nombreVeterinario, $telefonoVeterinario, 
														$celularVeterinario, $correoElectronicoVeterinario, 
														$frecuenciaVisitaVeterinario, $ruta, $numInspeccion){
	
				return '<tr id="R' . $idVeterinario . '">
						<td width="30%">' .
						$numInspeccion.
						'</td>
						<td width="30%">' .
						$nombreVeterinario.
						'</td>
						<td width="30%">' .
							$telefonoVeterinario.
							'</td>
						<td width="30%">' .
							$celularVeterinario.
							'</td>
						<td width="30%">' .
							$correoElectronicoVeterinario.
							'</td>
						<td width="30%">' .
							$frecuenciaVisitaVeterinario.
							'</td>
						</tr>';
	}
	
	//Archivo eliminarVeterinario
	public function eliminarVeterinario($conexion, $idVeterinario){
	
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_veterinario
											WHERE
												id_certificacion_bt_veterinario=$idVeterinario;");
	
		return $res;
	}
	
	//Archivo guardarInformacionVacunacion
	public function buscarInformacionVacunacionCertificacionBT($conexion, $idCertificacionBT, 
											$idMotivoVacunacion, $idVacunasAplicadas, $idProcedenciaVacunas,
											$numInspeccion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_informacion_vacunacion
											WHERE
												id_certificacion_bt = $idCertificacionBT and
												id_motivo_vacunacion = $idMotivoVacunacion and
												id_vacunas_aplicadas = $idVacunasAplicadas and
												id_procedencia_vacunas = $idProcedenciaVacunas and
												num_inspeccion = '$numInspeccion';");
	
		return $res;
	}
        
        public function buscarInformacionVacunacionCertificacionBTNoAplica($conexion, $idCertificacionBT, $numInspeccion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_informacion_vacunacion
											WHERE
												id_certificacion_bt = $idCertificacionBT and
												id_motivo_vacunacion = 0 and
												num_inspeccion = '$numInspeccion';");
	
		return $res;
	}
        
        public function buscarInformacionVacunacionCertificacionBTAplica($conexion, $idCertificacionBT, $numInspeccion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_informacion_vacunacion
											WHERE
												id_certificacion_bt = $idCertificacionBT and
												id_motivo_vacunacion > 0 and
												num_inspeccion = '$numInspeccion';");
	
		return $res;
	}
	
	public function guardarInformacionVacunacionCertificacionBT ($conexion, $idCertificacionBT, $identificador,
																	$idMotivoVacunacion, $motivoVacunacion,
																	$idVacunasAplicadas, $vacunasAplicadas,
																	$idProcedenciaVacunas, $procedenciaVacunas,
																	$fechaVacunacion, $numInspeccion, $calendarioVacunacion){
	
				$res = $conexion->ejecutarConsulta("INSERT INTO 
														g_certificacion_brucelosis_tuberculosis.certificacion_bt_informacion_vacunacion(
												            identificador, fecha_creacion, 
												            id_certificacion_bt, id_motivo_vacunacion, motivo_vacunacion, 
												            id_vacunas_aplicadas, vacunas_aplicadas, id_procedencia_vacunas, 
												            procedencia_vacunas, fecha_vacunacion, num_inspeccion, calendario_vacunacion)
												    VALUES ('$identificador', now(), 
												            $idCertificacionBT, $idMotivoVacunacion, '$motivoVacunacion', 
												            $idVacunasAplicadas, '$vacunasAplicadas', $idProcedenciaVacunas, 
												            '$procedenciaVacunas', '$fechaVacunacion', '$numInspeccion', '$calendarioVacunacion')
													RETURNING
														id_certificacion_bt_informacion_vacunacion;");
	
				return $res;
	}
	
	public function imprimirLineaInformacionVacunacionCertificacionBT($idInformacionVacunacion, $motivoVacunacion,
																	$vacunasAplicadas, $procedenciaVacunas,
																	$fechaVacunacion, $ruta, $numInspeccion, $calendarioVacunacion){
	
				return '<tr id="R' . $idInformacionVacunacion . '">
						<td width="30%">' .
						$numInspeccion.
						'</td>
						<td width="30%">' .
						$calendarioVacunacion.
						'</td>
						<td width="30%">' .
						$motivoVacunacion.
						'</td>
						<td width="30%">' .
							$vacunasAplicadas.
							'</td>
						<td width="30%">' .
							$procedenciaVacunas.
							'</td>
						<td width="30%">' .
							$fechaVacunacion.
							'</td>
						<td>' .
							'<form id="borrarRegistro" class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarInformacionVacunacion" data-destino="detalleItem" data-accionEnExito="NADA" >' .
							'<input type="hidden" id="idInformacionVacunacion" name="idInformacionVacunacion" value="' . $idInformacionVacunacion . '" >' .
							'<button class="icono" type="submit" ></button>' .
							'</form>' .
							'</td>' .
							'</tr>';
	}
	
	public function imprimirLineaInformacionVacunacionCertificacionBTConsulta($idInformacionVacunacion, $motivoVacunacion,
																	$vacunasAplicadas, $procedenciaVacunas,
																	$fechaVacunacion, $ruta, $numInspeccion, $calendarioVacunacion){
	
				return '<tr id="R' . $idInformacionVacunacion . '">
						<td width="30%">' .
						$numInspeccion.
						'</td>
						<td width="30%">' .
						$calendarioVacunacion.
						'</td>
						<td width="30%">' .
						$motivoVacunacion.
						'</td>
						<td width="30%">' .
							$vacunasAplicadas.
							'</td>
						<td width="30%">' .
							$procedenciaVacunas.
							'</td>
						<td width="30%">' .
							$fechaVacunacion.
							'</td>
						</tr>';
	}
	
	//Archivo eliminarInformacionVacunacion
	public function eliminarInformacionVacunacion($conexion, $idInformacionVacunacion){
	
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_informacion_vacunacion
											WHERE
												id_certificacion_bt_informacion_vacunacion=$idInformacionVacunacion;");
	
		return $res;
	}
	
	//Archivo guardarReproduccion
	public function buscarReproduccionCertificacionBT($conexion, $idCertificacionBT, 
											$idSistemaEmpleado, $idProcedenciaPajuelas, $idLugarPariciones,
											$realizaDesinfeccion, $numInspeccion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_reproduccion
											WHERE
												id_certificacion_bt = $idCertificacionBT and
												id_sistema_empleado = $idSistemaEmpleado and
												id_procedencia_pajuelas = $idProcedenciaPajuelas and
												id_lugar_pariciones = $idLugarPariciones and
												realiza_desinfeccion = '$realizaDesinfeccion' and
												num_inspeccion = '$numInspeccion';");

		return $res;
	}
	
	public function guardarReproduccionCertificacionBT ($conexion, $idCertificacionBT, $identificador,
											$idSistemaEmpleado, $sistemaEmpleado,
											$idProcedenciaPajuelas, $procedenciaPajuelas,
											$idLugarPariciones, $lugarPariciones,
											$realizaDesinfeccion, $numInspeccion){
		$res = $conexion->ejecutarConsulta("INSERT INTO 
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_reproduccion(
										            identificador, fecha_creacion, 
										            id_certificacion_bt, id_sistema_empleado, sistema_empleado, id_procedencia_pajuelas, 
										            procedencia_pajuelas, id_lugar_pariciones, lugar_pariciones, 
										            realiza_desinfeccion, num_inspeccion)
										    VALUES ('$identificador', now(), 
										            $idCertificacionBT, $idSistemaEmpleado, '$sistemaEmpleado', $idProcedenciaPajuelas, 
										            '$procedenciaPajuelas', $idLugarPariciones, '$lugarPariciones', 
										            '$realizaDesinfeccion', '$numInspeccion')
											RETURNING
												id_certificacion_bt_reproduccion;");

		return $res;
	}
	
	public function imprimirLineaReproduccionCertificacionBT($idReproduccion, $sistemaEmpleado,
																$procedenciaPajuelas, $lugarPariciones,
																$realizaDesinfeccion, $ruta,
																$numInspeccion){
	
				return '<tr id="R' . $idReproduccion . '">
						<td width="30%">' .
						$numInspeccion.
						'</td>
						<td width="30%">' .
						$sistemaEmpleado.
						'</td>
						<td width="30%">' .
							$procedenciaPajuelas.
							'</td>
						<td width="30%">' .
							$lugarPariciones.
							'</td>
						<td width="30%">' .
							$realizaDesinfeccion.
							'</td>
						<td>' .
							'<form id="borrarRegistro" class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarReproduccion" data-destino="detalleItem" data-accionEnExito="NADA" >' .
							'<input type="hidden" id="idReproduccion" name="idReproduccion" value="' . $idReproduccion . '" >' .
							'<button class="icono" type="submit" ></button>' .
							'</form>' .
							'</td>' .
							'</tr>';
	}
	
	public function imprimirLineaReproduccionCertificacionBTConsulta($idReproduccion, $sistemaEmpleado,
																$procedenciaPajuelas, $lugarPariciones,
																$realizaDesinfeccion, $ruta, $numInspeccion){
	
				return '<tr id="R' . $idReproduccion . '">
						<td width="30%">' .
						$numInspeccion.
						'</td>
						<td width="30%">' .
						$sistemaEmpleado.
						'</td>
						<td width="30%">' .
							$procedenciaPajuelas.
							'</td>
						<td width="30%">' .
							$lugarPariciones.
							'</td>
						<td width="30%">' .
							$realizaDesinfeccion.
							'</td>
						</tr>';
	}
	
	//Archivo eliminarReproduccion
	public function eliminarReproduccion($conexion, $idReproduccion){
	
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_reproduccion
											WHERE
												id_certificacion_bt_reproduccion=$idReproduccion;");
	
		return $res;
	}
	
	//Archivo guardarPatologiaBrucelosis
	public function buscarPatologiaBrucelosisCertificacionBT($conexion, $idCertificacionBT, $numInspeccion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_patologia_brucelosis
											WHERE
												id_certificacion_bt = $idCertificacionBT and
												num_inspeccion = '$numInspeccion';");

		return $res;
	}
	
	public function guardarPatologiaBrucelosisCertificacionBT($conexion, $idCertificacionBT, $identificador,
																$retencionPlacenta, $nacimientoTernerosDebiles,
																$problemasEsterilidad, $metritisPostParto, 
																$hinchazonArticulaciones, $epididimitisOrquitis,
																$numInspeccion){
		
				$res = $conexion->ejecutarConsulta("INSERT INTO 
														g_certificacion_brucelosis_tuberculosis.certificacion_bt_patologia_brucelosis(
												            identificador, fecha_creacion, 
												            id_certificacion_bt, retencion_placenta, nacimient_terneros_debiles, 
												            problemas_esterilidad, metritis_post_parto, hinchazon_articulaciones, 
												            epididimitis_orquitis, num_inspeccion)
												    VALUES ('$identificador', now(), 
												            $idCertificacionBT, '$retencionPlacenta', '$nacimientoTernerosDebiles',
												            '$problemasEsterilidad', '$metritisPostParto', '$hinchazonArticulaciones', 
												            '$epididimitisOrquitis', '$numInspeccion')
													RETURNING
														id_certificacion_bt_patologia_brucelosis;");
	
						return $res;
	}
	
	public function imprimirLineaPatologiaBrucelosisCertificacionBT($idPatologiaBrucelosis, $retencionPlacenta, 
																$nacimientoTernerosDebiles, $problemasEsterilidad, 
																$metritisPostParto, $hinchazonArticulaciones,  
																$epididimitisOrquitis, $ruta, $numInspeccion){
	
				return '<tr id="R' . $idPatologiaBrucelosis . '">
						<td width="30%">' .
						$numInspeccion.
						'</td>
						<td width="30%">' .
						$retencionPlacenta.
						'</td>
						<td width="30%">' .
							$nacimientoTernerosDebiles.
							'</td>
						<td width="30%">' .
							$problemasEsterilidad.
							'</td>
						<td width="30%">' .
							$metritisPostParto.
							'</td>
						<td width="30%">' .
						$hinchazonArticulaciones.
						'</td>
						<td width="30%">' .
							$epididimitisOrquitis.
							'</td>
						<td>' .
							'<form id="borrarRegistro" class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarPatologiaBrucelosis" data-destino="detalleItem" data-accionEnExito="NADA" >' .
							'<input type="hidden" id="idPatologiaBrucelosis" name="idPatologiaBrucelosis" value="' . $idPatologiaBrucelosis . '" >' .
							'<button class="icono" type="submit" ></button>' .
							'</form>' .
							'</td>' .
							'</tr>';
	}
	
	public function imprimirLineaPatologiaBrucelosisCertificacionBTConsulta($idPatologiaBrucelosis, $retencionPlacenta, 
																$nacimientoTernerosDebiles, $problemasEsterilidad, 
																$metritisPostParto, $hinchazonArticulaciones,  
																$epididimitisOrquitis, $ruta, $numInspeccion){
	
				return '<tr id="R' . $idPatologiaBrucelosis . '">
						<td width="30%">' .
						$numInspeccion.
						'</td>
						<td width="30%">' .
						$retencionPlacenta.
						'</td>
						<td width="30%">' .
							$nacimientoTernerosDebiles.
							'</td>
						<td width="30%">' .
							$problemasEsterilidad.
							'</td>
						<td width="30%">' .
							$metritisPostParto.
							'</td>
						<td width="30%">' .
						$hinchazonArticulaciones.
						'</td>
						<td width="30%">' .
							$epididimitisOrquitis.
							'</td>
						</tr>';
	}
	
	//Archivo eliminarPatologiaBrucelosis
	public function eliminarPatologiaBrucelosis($conexion, $idPatologiaBrucelosis){
	
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_patologia_brucelosis
											WHERE
												id_certificacion_bt_patologia_brucelosis=$idPatologiaBrucelosis;");
	
		return $res;
	}
	
	//Archivo guardarAbortoBrucelosis
	public function buscarAbortoBrucelosisCertificacionBT($conexion, $idCertificacionBT, $abortos, $idTejidosAbortados, $numInspeccion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_abortos_brucelosis
											WHERE
												id_certificacion_bt = $idCertificacionBT and
												abortos = '$abortos' and
												id_tejidos_abortados = $idTejidosAbortados and
												num_inspeccion = '$numInspeccion';");
	
		return $res;
	}
        
        public function buscarAbortoBrucelosisCertificacionBTNoAplica($conexion, $idCertificacionBT, $numInspeccion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_abortos_brucelosis
											WHERE
												id_certificacion_bt = $idCertificacionBT and
												abortos = 'No' and
												num_inspeccion = '$numInspeccion';");
	
		return $res;
	}
        
        public function buscarAbortoBrucelosisCertificacionBTAplica($conexion, $idCertificacionBT, $numInspeccion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_abortos_brucelosis
											WHERE
												id_certificacion_bt = $idCertificacionBT and
												abortos = 'Si' and
												num_inspeccion = '$numInspeccion';");
	
		return $res;
	}
	
	public function guardarAbortosBrucelosisCertificacionBT($conexion, $idCertificacionBT, $identificador,
																$abortos, $numeroAbortos,
																$idTejidosAbortados, $tejidosAbortados,
																$numInspeccion){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO 
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_abortos_brucelosis(
										            identificador, fecha_creacion, 
										            id_certificacion_bt, abortos, numero_abortos, id_tejidos_abortados, 
										            tejidos_abortados, num_inspeccion)
										    VALUES ('$identificador', now(), 
										            $idCertificacionBT, '$abortos', $numeroAbortos, $idTejidosAbortados, 
										            '$tejidosAbortados', '$numInspeccion')
											RETURNING
												id_certificacion_bt_abortos_brucelosis;");
	
		return $res;
	}
	
	public function imprimirLineaAbortosBrucelosisCertificacionBT($idAbortosBrucelosis, $abortos, $numeroAbortos,
																	$tejidosAbortados, $ruta, $numInspeccion){
	
				return '<tr id="R' . $idAbortosBrucelosis . '">
						<td width="30%">' .
						$numInspeccion.
						'</td>
						<td width="30%">' .
						$abortos.
						'</td>
						<td width="30%">' .
							$numeroAbortos.
							'</td>
						<td width="30%">' .
							$tejidosAbortados.
							'</td>
						<td>' .
							'<form id="borrarRegistro" class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarAbortosBrucelosis" data-destino="detalleItem" data-accionEnExito="NADA" >' .
							'<input type="hidden" id="idAbortosBrucelosis" name="idAbortosBrucelosis" value="' . $idAbortosBrucelosis . '" >' .
							'<button class="icono" type="submit" ></button>' .
							'</form>' .
							'</td>' .
							'</tr>';
	}
	
	public function imprimirLineaAbortosBrucelosisCertificacionBTConsulta($idAbortosBrucelosis, $abortos, $numeroAbortos,
																	$tejidosAbortados, $ruta, $numInspeccion){
	
				return '<tr id="R' . $idAbortosBrucelosis . '">
						<td width="30%">' .
						$numInspeccion.
						'</td>
						<td width="30%">' .
						$abortos.
						'</td>
						<td width="30%">' .
							$numeroAbortos.
							'</td>
						<td width="30%">' .
							$tejidosAbortados.
							'</td>
						</tr>';
	}
	
	//Archivo eliminarAbortosBrucelosis
	public function eliminarAbortosBrucelosis($conexion, $idAbortosBrucelosis){
	
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_abortos_brucelosis
											WHERE
												id_certificacion_bt_abortos_brucelosis=$idAbortosBrucelosis;");
	
		return $res;
	}
	
	//Archivo guardarPruebaBrucelosisLeche
	public function buscarPruebaBrucelosisLecheCertificacionBT($conexion, $idCertificacionBT, 
																$pruebasBrucelosisLeche, $resultadoBrucelosisLeche,
																$numInspeccion, $idPruebasLaboratorioLeche,
																$idLaboratorioLeche){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_prueba_brucelosis_leche
											WHERE
												id_certificacion_bt = $idCertificacionBT and
												pruebas_brucelosis_leche = '$pruebasBrucelosisLeche' and
												resultado_brucelosis_leche = '$resultadoBrucelosisLeche' and
												num_inspeccion = '$numInspeccion' and
												id_pruebas_laboratorio = $idPruebasLaboratorioLeche and
												id_laboratorio = $idLaboratorioLeche;");
	
		return $res;
	}

        public function buscarPruebaBrucelosisLecheCertificacionBTNoAplica($conexion, $idCertificacionBT, $numInspeccion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_prueba_brucelosis_leche
											WHERE
												id_certificacion_bt = $idCertificacionBT and
												pruebas_brucelosis_leche = 'No' and
												num_inspeccion = '$numInspeccion';");
	
		return $res;
	}
        
        public function buscarPruebaBrucelosisLecheCertificacionBTAplica($conexion, $idCertificacionBT, $numInspeccion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_prueba_brucelosis_leche
											WHERE
												id_certificacion_bt = $idCertificacionBT and
												pruebas_brucelosis_leche = 'Si' and
												num_inspeccion = '$numInspeccion';");
	
		return $res;
	}
	
	public function guardarPruebaBrucelosisLecheCertificacionBT($conexion, $idCertificacionBT, $identificador,
																  $pruebasBrucelosisLeche, $resultadoBrucelosisLeche,
																  $numInspeccion, $idPruebasLaboratorioLeche, $pruebasLaboratorioLeche,
																  $idLaboratorioLeche, $laboratorioLeche){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO 
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_prueba_brucelosis_leche(
										            identificador, fecha_creacion, 
										            id_certificacion_bt, pruebas_brucelosis_leche, resultado_brucelosis_leche,
													num_inspeccion, id_pruebas_laboratorio, pruebas_laboratorio, 
													id_laboratorio, laboratorio)
										    VALUES ('$identificador', now(), 
										            $idCertificacionBT, '$pruebasBrucelosisLeche', '$resultadoBrucelosisLeche',
													'$numInspeccion', $idPruebasLaboratorioLeche, '$pruebasLaboratorioLeche',
													$idLaboratorioLeche, '$laboratorioLeche')
											RETURNING
												id_certificacion_bt_prueba_brucelosis_leche;");
	
		return $res;
	}
	
	public function imprimirLineaPruebaBrucelosisLecheCertificacionBT($idPruebaBrucelosisLeche, $pruebasBrucelosisLeche,  
																		$resultadoBrucelosisLeche, $ruta, $numInspeccion,
																		$pruebasLaboratorioLeche, $laboratorioLeche){
	
				return '<tr id="R' . $idPruebaBrucelosisLeche . '">
						<td width="30%">' .
						$numInspeccion.
						'</td>
						<td width="30%">' .
						$pruebasBrucelosisLeche.
						'</td>
						<td width="30%">' .
							$resultadoBrucelosisLeche.
							'</td>
						<td width="30%">' .
						$pruebasLaboratorioLeche.
						'</td>
						<td width="30%">' .
						$laboratorioLeche.
						'</td>
						<td>' .
							'<form id="borrarRegistro" class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarPruebaBrucelosisLeche" data-destino="detalleItem" data-accionEnExito="NADA" >' .
							'<input type="hidden" id="idPruebaBrucelosisLeche" name="idPruebaBrucelosisLeche" value="' . $idPruebaBrucelosisLeche . '" >' .
							'<button class="icono" type="submit" ></button>' .
							'</form>' .
							'</td>' .
							'</tr>';
	}
	
	public function imprimirLineaPruebaBrucelosisLecheCertificacionBTConsulta($idPruebaBrucelosisLeche, $pruebasBrucelosisLeche,  
																				$resultadoBrucelosisLeche, $ruta, $numInspeccion,
																				$pruebasLaboratorioLeche, $laboratorioLeche){
	
				return '<tr id="R' . $idPruebaBrucelosisLeche . '">
						<td width="30%">' .
						$numInspeccion.
						'</td>
						<td width="30%">' .
						$pruebasBrucelosisLeche.
						'</td>
						<td width="30%">' .
						$resultadoBrucelosisLeche.
						'</td>
						<td width="30%">' .
						$pruebasLaboratorioLeche.
						'</td>
						<td width="30%">' .
						$laboratorioLeche.
						'</td>
						</tr>';
	}
	
	//Archivo eliminarPruebaBrucelosisLeche
	public function eliminarPruebaBrucelosisLeche($conexion, $idPruebaBrucelosisLeche){
	
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_prueba_brucelosis_leche
											WHERE
												id_certificacion_bt_prueba_brucelosis_leche=$idPruebaBrucelosisLeche;");
	
		return $res;
	}
	
	//Archivo guardarPruebaBrucelosisSangre
	public function buscarPruebaBrucelosisSangreCertificacionBT($conexion, $idCertificacionBT,
											$pruebasBrucelosisSangre, $resultadoBrucelosisSangre, 
											$idPruebasLaboratorio, $idLaboratorio, 
											$idDestinoAnimalesPositivos, $numInspeccion){
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_prueba_brucelosis_sangre
											WHERE
												id_certificacion_bt = $idCertificacionBT and
												pruebas_brucelosis_sangre = '$pruebasBrucelosisSangre' and
												resultado_brucelosis_sangre = '$resultadoBrucelosisSangre' and
												id_pruebas_laboratorio = $idPruebasLaboratorio and
												id_laboratorio = $idLaboratorio and
												id_destino_animales_positivos = $idDestinoAnimalesPositivos and
												num_inspeccion = '$numInspeccion';");

		return $res;
	}
        
        public function buscarPruebaBrucelosisSangreCertificacionBTNoAplica($conexion, $idCertificacionBT, $numInspeccion){
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_prueba_brucelosis_sangre
											WHERE
												id_certificacion_bt = $idCertificacionBT and
												pruebas_brucelosis_sangre = 'No' and
												num_inspeccion = '$numInspeccion';");

		return $res;
	}
        
        public function buscarPruebaBrucelosisSangreCertificacionBTAplica($conexion, $idCertificacionBT, $numInspeccion){
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_prueba_brucelosis_sangre
											WHERE
												id_certificacion_bt = $idCertificacionBT and
												pruebas_brucelosis_sangre = 'Si' and
												num_inspeccion = '$numInspeccion';");

		return $res;
	}
	
	public function guardarPruebaBrucelosisSangreCertificacionBT($conexion, $idCertificacionBT, $identificador,
															$pruebasBrucelosisSangre, $resultadoBrucelosisSangre, 
															$idPruebasLaboratorio, $pruebasLaboratorio,
															$idLaboratorio, $laboratorio,
															$idDestinoAnimalesPositivos, $destinoAnimalesPositivos,
															$numInspeccion){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO 
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_prueba_brucelosis_sangre(
										            identificador, fecha_creacion, 
										            id_certificacion_bt, pruebas_brucelosis_sangre, 
										            resultado_brucelosis_sangre, id_pruebas_laboratorio, pruebas_laboratorio, 
										            id_laboratorio, laboratorio, id_destino_animales_positivos, destino_animales_positivos,
													num_inspeccion)
										    VALUES ('$identificador', now(), 
										            $idCertificacionBT, '$pruebasBrucelosisSangre',
										            '$resultadoBrucelosisSangre', $idPruebasLaboratorio, '$pruebasLaboratorio', 
										            $idLaboratorio, '$laboratorio', $idDestinoAnimalesPositivos, '$destinoAnimalesPositivos',
													'$numInspeccion')
											RETURNING
												id_certificacion_bt_prueba_brucelosis_sangre;");
	
		return $res;
	}
	
	public function imprimirLineaPruebaBrucelosisSangreCertificacionBT($idPruebaBrucelosisSangre, $pruebasBrucelosisSangre, 
															$resultadoBrucelosisSangre, $pruebasLaboratorio,
															$laboratorio, $destinoAnimalesPositivos, $ruta, $numInspeccion){
	
				return '<tr id="R' . $idPruebaBrucelosisSangre . '">
						<td width="30%">' .
						$numInspeccion.
						'</td>
						<td width="30%">' .
						$pruebasBrucelosisSangre.
						'</td>
						<td width="30%">' .
						$resultadoBrucelosisSangre.
						'</td>
						<td width="30%">' .
						$laboratorio.
						'</td>
						<td width="30%">' .
						$destinoAnimalesPositivos.
						'</td>
						<td>' .
							'<form id="borrarRegistro" class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarPruebaBrucelosisSangre" data-destino="detalleItem" data-accionEnExito="NADA" >' .
							'<input type="hidden" id="idPruebaBrucelosisSangre" name="idPruebaBrucelosisSangre" value="' . $idPruebaBrucelosisSangre . '" >' .
							'<button class="icono" type="submit" ></button>' .
							'</form>' .
							'</td>' .
							'</tr>';
	}
	
	public function imprimirLineaPruebaBrucelosisSangreCertificacionBTConsulta($idPruebaBrucelosisSangre, $pruebasBrucelosisSangre, 
															$resultadoBrucelosisSangre, $pruebasLaboratorio,
															$laboratorio, $destinoAnimalesPositivos, $ruta, $numInspeccion){
	
				return '<tr id="R' . $idPruebaBrucelosisSangre . '">
						<td width="30%">' .
						$numInspeccion.
						'</td>
						<td width="30%">' .
						$pruebasBrucelosisSangre.
						'</td>
						<td width="30%">' .
						$resultadoBrucelosisSangre.
						'</td>
						<td width="30%">' .
						$laboratorio.
						'</td>
						<td width="30%">' .
						$destinoAnimalesPositivos.
						'</td>
						</tr>';
	}
	
	//Archivo eliminarPruebaBrucelosisSangre
	public function eliminarPruebaBrucelosisSangre($conexion, $idPruebaBrucelosisSangre){
	
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_prueba_brucelosis_sangre
											WHERE
												id_certificacion_bt_prueba_brucelosis_sangre=$idPruebaBrucelosisSangre;");
	
		return $res;
	}
	
	//Archivo guardarPatologiaTuberculosis
	public function buscarPatologiaTuberculosisCertificacionBT($conexion, $idCertificacionBT, $numInspeccion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_patologia_tuberculosis
											WHERE
												id_certificacion_bt = $idCertificacionBT and
												num_inspeccion = '$numInspeccion';");
	
		return $res;
	}
	
	public function guardarPatologiaTuberculosisCertificacionBT($conexion, $idCertificacionBT, $identificador,
																$perdidaPeso, $perdidaApetito,
																$problemasRespiratorios, $tosIntermitente,
																$abultamiento, $fiebreFluctuante, $numInspeccion){
																	
				$res = $conexion->ejecutarConsulta("INSERT INTO 
														g_certificacion_brucelosis_tuberculosis.certificacion_bt_patologia_tuberculosis(
												            identificador, fecha_creacion, 
												            id_certificacion_bt, perdida_peso, perdida_apetito, problemas_respiratorios, 
												            tos_intermitente, abultamiento, fiebre_fluctuante,
															num_inspeccion)
												    VALUES ('$identificador', now(), 
												            $idCertificacionBT, '$perdidaPeso', '$perdidaApetito', '$problemasRespiratorios', 
												            '$tosIntermitente', '$abultamiento', '$fiebreFluctuante',
															'$numInspeccion')
													RETURNING
														id_certificacion_bt_patologia_tuberculosis;");
	
						return $res;
	}
	
	public function imprimirLineaPatologiaTuberculosisCertificacionBT($idPatologiaTuberculosis, $perdidaPeso, 
																		$perdidaApetito, $problemasRespiratorios, 
																		$tosIntermitente, $abultamiento, 
																		$fiebreFluctuante, $ruta, $numInspeccion){
	
				return '<tr id="R' . $idPatologiaTuberculosis . '">
						<td width="30%">' .
						$numInspeccion.
						'</td>
						<td width="30%">' .
						$perdidaPeso.
						'</td>
						<td width="30%">' .
							$perdidaApetito.
							'</td>
						<td width="30%">' .
							$problemasRespiratorios.
							'</td>
						<td width="30%">' .
							$tosIntermitente.
							'</td>
						<td width="30%">' .
							$abultamiento.
							'</td>
						<td width="30%">' .
							$fiebreFluctuante.
							'</td>
						<td>' .
							'<form id="borrarRegistro" class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarPatologiaTuberculosis" data-destino="detalleItem" data-accionEnExito="NADA" >' .
							'<input type="hidden" id="idPatologiaTuberculosis" name="idPatologiaTuberculosis" value="' . $idPatologiaTuberculosis . '" >' .
							'<button class="icono" type="submit" ></button>' .
							'</form>' .
							'</td>' .
							'</tr>';
	}
	
	public function imprimirLineaPatologiaTuberculosisCertificacionBTConsulta($idPatologiaTuberculosis, $perdidaPeso, 
																		$perdidaApetito, $problemasRespiratorios, 
																		$tosIntermitente, $abultamiento, 
																		$fiebreFluctuante, $ruta, $numInspeccion){
	
				return '<tr id="R' . $idPatologiaTuberculosis . '">
						<td width="30%">' .
						$numInspeccion.
						'</td>
						<td width="30%">' .
						$perdidaPeso.
						'</td>
						<td width="30%">' .
							$perdidaApetito.
							'</td>
						<td width="30%">' .
							$problemasRespiratorios.
							'</td>
						<td width="30%">' .
							$tosIntermitente.
							'</td>
						<td width="30%">' .
							$abultamiento.
							'</td>
						<td width="30%">' .
							$fiebreFluctuante.
							'</td>
						</tr>';
	}
	
	//Archivo eliminarPatologiaTuberculosis
	public function eliminarPatologiaTuberculosis($conexion, $idPatologiaTuberculosis){
	
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_patologia_tuberculosis
											WHERE
												id_certificacion_bt_patologia_tuberculosis=$idPatologiaTuberculosis;");
	
		return $res;
	}
	
	//Archivo guardarPruebaTuberculosisLeche
	public function buscarPruebaTuberculosisLecheCertificacionBT($conexion, $idCertificacionBT,
			$pruebasTuberculosisLeche, $resultadoTuberculosisLeche, $numInspeccion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_prueba_tuberculosis_leche
											WHERE
												id_certificacion_bt = $idCertificacionBT and
												pruebas_tuberculosis_leche = '$pruebasTuberculosisLeche' and
												resultado_tuberculosis_leche = '$resultadoTuberculosisLeche' and
												num_inspeccion = '$numInspeccion';");

		return $res;
	}
	
	public function guardarPruebaTuberculosisLecheCertificacionBT($conexion, $idCertificacionBT, $identificador,
															$pruebasTuberculosisLeche, $resultadoTuberculosisLeche,
															$numInspeccion){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_prueba_tuberculosis_leche(
													identificador, fecha_creacion,
													id_certificacion_bt, pruebas_tuberculosis_leche, resultado_tuberculosis_leche,
													num_inspeccion)
											VALUES ('$identificador', now(),
												$idCertificacionBT, '$pruebasTuberculosisLeche', '$resultadoTuberculosisLeche',
												'$numInspeccion')
											RETURNING
												id_certificacion_bt_prueba_tuberculosis_leche;");

		return $res;
	}
	
	public function imprimirLineaPruebaTuberculosisLecheCertificacionBT($idPruebaTuberculosisLeche, $pruebasTuberculosisLeche,
																			$resultadoTuberculosisLeche, $ruta, $numInspeccion){
	
				return '<tr id="R' . $idPruebaTuberculosisLeche . '">
						<td width="30%">' .
						$numInspeccion.
						'</td>
						<td width="30%">' .
						$pruebasTuberculosisLeche.
						'</td>
						<td width="30%">' .
							$resultadoTuberculosisLeche.
							'</td>
						<td>' .
							'<form id="borrarRegistro" class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarPruebaTuberculosisLeche" data-destino="detalleItem" data-accionEnExito="NADA" >' .
							'<input type="hidden" id="idPruebaTuberculosisLeche" name="idPruebaTuberculosisLeche" value="' . $idPruebaTuberculosisLeche . '" >' .
							'<button class="icono" type="submit" ></button>' .
							'</form>' .
							'</td>' .
							'</tr>';
	}
	
	public function imprimirLineaPruebaTuberculosisLecheCertificacionBTConsulta($idPruebaTuberculosisLeche, $pruebasTuberculosisLeche,
																			$resultadoTuberculosisLeche, $ruta, $numInspeccion){
	
				return '<tr id="R' . $idPruebaTuberculosisLeche . '">
						<td width="30%">' .
						$numInspeccion.
						'</td>
						<td width="30%">' .
						$pruebasTuberculosisLeche.
						'</td>
						<td width="30%">' .
							$resultadoTuberculosisLeche.
							'</td>
						</tr>';
	}
	
	//Archivo eliminarPruebaTuberculosisLeche
	public function eliminarPruebaTuberculosisLeche($conexion, $idPruebaTuberculosisLeche){
	
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_prueba_tuberculosis_leche
											WHERE
												id_certificacion_bt_prueba_tuberculosis_leche=$idPruebaTuberculosisLeche;");
	
		return $res;
	}
	
	//Archivo guardarPruebaTuberculina
	public function buscarPruebaTuberculinaCertificacionBT ($conexion, $idCertificacionBT,
																$pruebasTuberculina, $resultadoTuberculina,
																$idLaboratorioTuberculina, $idDestinoAnimalesPositivosTuberculina,
																$numInspeccion, $idPruebasLaboratorio){
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_prueba_tuberculina
											WHERE
												id_certificacion_bt = $idCertificacionBT and
												pruebas_tuberculina = '$pruebasTuberculina' and
												resultado_tuberculina = '$resultadoTuberculina' and
												id_laboratorio = $idLaboratorioTuberculina and
												id_destino_animales_positivos = $idDestinoAnimalesPositivosTuberculina and
												num_inspeccion = '$numInspeccion' and
												id_pruebas_laboratorio = $idPruebasLaboratorio;");
	
		return $res;
	}
        
        public function buscarPruebaTuberculinaCertificacionBTNoAplica ($conexion, $idCertificacionBT, $numInspeccion){
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_prueba_tuberculina
											WHERE
												id_certificacion_bt = $idCertificacionBT and
												pruebas_tuberculina = 'No' and
												num_inspeccion = '$numInspeccion';");
	
		return $res;
	}
	
        public function buscarPruebaTuberculinaCertificacionBTAplica ($conexion, $idCertificacionBT, $numInspeccion){
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_prueba_tuberculina
											WHERE
												id_certificacion_bt = $idCertificacionBT and
												pruebas_tuberculina = 'Si' and
												num_inspeccion = '$numInspeccion';");
	
		return $res;
	}
        
	public function guardarPruebaTuberculinaCertificacionBT($conexion, $idCertificacionBT, $identificador,
																$pruebasTuberculina, $resultadoTuberculina,
																$idLaboratorioTuberculina, $laboratorioTuberculina,
																$idDestinoAnimalesPositivosTuberculina,
																$destinoAnimalesPositivosTuberculina, $numInspeccion,
																$idPruebasLaboratorio, $pruebasLaboratorio){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO 
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_prueba_tuberculina(
										            identificador, fecha_creacion, 
										            id_certificacion_bt, pruebas_tuberculina, resultado_tuberculina, 
										            id_laboratorio, laboratorio, 
										            id_destino_animales_positivos, destino_animales_positivos, num_inspeccion,
													id_pruebas_laboratorio, pruebas_laboratorio)
										    VALUES ('$identificador', now(), 
										            $idCertificacionBT, '$pruebasTuberculina', '$resultadoTuberculina', 
										            $idLaboratorioTuberculina, '$laboratorioTuberculina',
										            $idDestinoAnimalesPositivosTuberculina, '$destinoAnimalesPositivosTuberculina',
													'$numInspeccion', $idPruebasLaboratorio, '$pruebasLaboratorio')
											RETURNING
												id_certificacion_bt_prueba_tuberculina;");
	
		return $res;
	}
	
	public function imprimirLineaPruebaTuberculinaCertificacionBT($idPruebaTuberculina, $pruebasTuberculina, 
																	$resultadoTuberculina, $laboratorioTuberculina,
																	$destinoAnimalesPositivosTuberculina, $ruta, $numInspeccion,
																	$pruebasLaboratorio){
	
				return '<tr id="R' . $idPruebaTuberculina . '">
						<td width="30%">' .
						$numInspeccion.
						'</td>
						<td width="30%">' .
						$pruebasTuberculina.
						'</td>
						<td width="30%">' .
							$resultadoTuberculina.
							'</td>
						<td width="30%">' .
							$pruebasLaboratorio.
							'</td>
						<td width="30%">' .
							$laboratorioTuberculina.
							'</td>
						<td width="30%">' .
							$destinoAnimalesPositivosTuberculina.
							'</td>
						<td>' .
							'<form id="borrarRegistro" class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarPruebaTuberculina" data-destino="detalleItem" data-accionEnExito="NADA" >' .
							'<input type="hidden" id="idPruebaTuberculina" name="idPruebaTuberculina" value="' . $idPruebaTuberculina . '" >' .
							'<button class="icono" type="submit" ></button>' .
							'</form>' .
							'</td>' .
							'</tr>';
	}
	
	public function imprimirLineaPruebaTuberculinaCertificacionBTConsulta($idPruebaTuberculina, $pruebasTuberculina, 
																	$resultadoTuberculina, $laboratorioTuberculina,
																	$destinoAnimalesPositivosTuberculina, $ruta, $numInspeccion,
																	$pruebasLaboratorio){
	
				return '<tr id="R' . $idPruebaTuberculina . '">
						<td width="30%">' .
						$numInspeccion.
						'</td>
						<td width="30%">' .
						$pruebasTuberculina.
						'</td>
						<td width="30%">' .
							$resultadoTuberculina.
							'</td>
						<td width="30%">' .
							$pruebasLaboratorio.
							'</td>
						<td width="30%">' .
							$laboratorioTuberculina.
							'</td>
						<td width="30%">' .
							$destinoAnimalesPositivosTuberculina.
							'</td>
						</tr>';
	}
	
	//Archivo eliminarPruebaTuberculina
	public function eliminarPruebaTuberculina($conexion, $idPruebaTuberculina){
	
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_prueba_tuberculina
											WHERE
												id_certificacion_bt_prueba_tuberculina=$idPruebaTuberculina;");
	
		return $res;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	//Archivo guardarCierreCertificacionBTTecnico
	public function cierreCertificacionBTTecnico($conexion, $idCertificacionBT, $identificador,
													$observaciones, $estado, $numeroInspeccion,
													$idLaboratorio, $laboratorio, $fechaInspeccion){
	
		if($estado == 'tomaMuestras'){
			$res = $conexion->ejecutarConsulta("UPDATE
													g_certificacion_brucelosis_tuberculosis.certificacion_bt
												SET
													identificador_modificacion='$identificador',
													fecha_modificacion=now(),
													observaciones='$observaciones',
													estado='$estado',
													num_inspeccion = '$numeroInspeccion',
													id_laboratorio = $idLaboratorio,
													laboratorio = '$laboratorio',
													nueva_inspeccion = 'Si',
													fecha_nueva_inspeccion = '$fechaInspeccion'
												WHERE
													id_certificacion_bt=$idCertificacionBT;");
		}else{
			$res = $conexion->ejecutarConsulta("UPDATE
													g_certificacion_brucelosis_tuberculosis.certificacion_bt
												SET
													identificador_modificacion='$identificador',
													fecha_modificacion=now(),
													observaciones='$observaciones',
													estado='$estado',
													num_inspeccion = '$numeroInspeccion',
													nueva_inspeccion = null,
													fecha_nueva_inspeccion = null
												WHERE
													id_certificacion_bt=$idCertificacionBT;");
		}
														
		
													
		return $res;
	}
	
	public function actualizarCertificadoAprobacion($conexion, $idCertificacionBT, $identificador,
														$rutaCertificado){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_certificacion_brucelosis_tuberculosis.certificacion_bt
											SET
												ruta_certificado = '$rutaCertificado',
												identificador_aprobador = '$identificador',
												fecha_aprobacion = now()
											WHERE
												id_certificacion_bt=$idCertificacionBT;");
		
		return $res;
	}
	
	public function abrirInformacionPredioCertificacionBTInspeccion ($conexion, $idCertificacionBT, $numInspeccion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_informacion_predio
											WHERE
												id_certificacion_bt = $idCertificacionBT and
												num_inspeccion = '$numInspeccion';");
	
		return $res;
	}
	
	public function abrirProduccionCertificacionBTInspeccion ($conexion, $idCertificacionBT, $numInspeccion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_produccion
											WHERE
												id_certificacion_bt = $idCertificacionBT and
												num_inspeccion = '$numInspeccion';");
	
		return $res;
	}
	
	public function abrirInventarioAnimalCertificacionBTInspeccion ($conexion, $idCertificacionBT, $numInspeccion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_inventario_animal
											WHERE
												id_certificacion_bt = $idCertificacionBT and
												num_inspeccion = '$numInspeccion';");
	
		return $res;
	}
	
	public function abrirPediluvioCertificacionBTInspeccion ($conexion, $idCertificacionBT, $numInspeccion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_pediluvio
											WHERE
												id_certificacion_bt = $idCertificacionBT and
												num_inspeccion = '$numInspeccion';");
	
		return $res;
	}
	
	public function abrirManejoAnimalCertificacionBTInspeccion ($conexion, $idCertificacionBT, $numInspeccion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_manejo_animales_potreros
											WHERE
												id_certificacion_bt = $idCertificacionBT and
												num_inspeccion = '$numInspeccion';");
									
		return $res;
	}
	
	public function abrirAdquisicionAnimalesCertificacionBTInspeccion ($conexion, $idCertificacionBT, $numInspeccion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_adquisicion_animales
											WHERE
												id_certificacion_bt = $idCertificacionBT and
												num_inspeccion = '$numInspeccion';");
	
		return $res;
	}
	
	public function abrirProcedenciaAguaCertificacionBTInspeccion ($conexion, $idCertificacionBT, $numInspeccion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_procedencia_agua
											WHERE
												id_certificacion_bt = $idCertificacionBT and
												num_inspeccion = '$numInspeccion';");
	
		return $res;
	}
	
	public function abrirVeterinarioCertificacionBTInspeccion ($conexion, $idCertificacionBT, $numInspeccion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_veterinario
											WHERE
												id_certificacion_bt = $idCertificacionBT and
												num_inspeccion = '$numInspeccion';");
	
		return $res;
	}
	
	public function abrirVacunacionCertificacionBTInspeccion ($conexion, $idCertificacionBT, $numInspeccion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_informacion_vacunacion
											WHERE
												id_certificacion_bt = $idCertificacionBT and
												num_inspeccion = '$numInspeccion';");
	
		return $res;
	}
	
	public function abrirReproduccionCertificacionBTInspeccion ($conexion, $idCertificacionBT, $numInspeccion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_reproduccion
											WHERE
												id_certificacion_bt = $idCertificacionBT and
												num_inspeccion = '$numInspeccion';");
	
		return $res;
	}
	
	public function abrirPatologiaBrucelosisCertificacionBTInspeccion ($conexion, $idCertificacionBT, $numInspeccion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_patologia_brucelosis
											WHERE
												id_certificacion_bt = $idCertificacionBT and
												num_inspeccion = '$numInspeccion';");
	
		return $res;
	}
	
	public function abrirAbortosBrucelosisCertificacionBTInspeccion ($conexion, $idCertificacionBT, $numInspeccion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_abortos_brucelosis
											WHERE
												id_certificacion_bt = $idCertificacionBT and
												num_inspeccion = '$numInspeccion';");
	
		return $res;
	}
	
	public function abrirPruebasBrucelosisLecheCertificacionBTInspeccion ($conexion, $idCertificacionBT, $numInspeccion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_prueba_brucelosis_leche
											WHERE
												id_certificacion_bt = $idCertificacionBT and
												num_inspeccion = '$numInspeccion';");
	
		return $res;
	}
	
	public function abrirPruebasBrucelosisSangreCertificacionBTInspeccion ($conexion, $idCertificacionBT, $numInspeccion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_prueba_brucelosis_sangre
											WHERE
												id_certificacion_bt = $idCertificacionBT and
												num_inspeccion = '$numInspeccion';");
	
		return $res;
	}
	
	public function abrirPatologiaTuberculosisCertificacionBTInspeccion ($conexion, $idCertificacionBT, $numInspeccion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_patologia_tuberculosis
											WHERE
												id_certificacion_bt = $idCertificacionBT and
												num_inspeccion = '$numInspeccion';");
	
		return $res;
	}
	
	public function abrirPruebaTuberculosisLecheCertificacionBTInspeccion ($conexion, $idCertificacionBT, $numInspeccion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_prueba_tuberculosis_leche
											WHERE
												id_certificacion_bt = $idCertificacionBT and
												num_inspeccion = '$numInspeccion';");
	
		return $res;
	}
	
	public function abrirPruebaTuberculinaCertificacionBTInspeccion ($conexion, $idCertificacionBT, $numInspeccion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_prueba_tuberculina
											WHERE
												id_certificacion_bt = $idCertificacionBT and
												num_inspeccion = '$numInspeccion';");
	
		return $res;
	}
	
	

/******** RESULTADOS DE LABORATORIO CERTIFICACIÓN BRUCELOSIS Y TUBERCULOSIS ********/
	//Archivo listarCertificacionBTLaboratorios
	public function buscarLaboratorioUsuario($conexion, $identificador){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.usuarios_laboratorio
											WHERE
												identificador = '$identificador';");
	
	
		return $res;
	}
	
	//Archivo abrirCertificacionBTLaboratorios
	public function generarNumeroMuestraCertificacionBT($conexion, $codigo){

		$res = $conexion->ejecutarConsulta("SELECT
												MAX(num_muestra) as num_muestra
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_resultado_laboratorio
											WHERE
												num_solicitud LIKE '%$codigo%';");
		
	
		return $res;
	}
	
	//Archivo guardarPruebaLaboratorio
	public function nuevoResultadoLaboratorio($conexion, $idCertificacionBT, $identificador, 
												$numeroSolicitud, $numeroMuestra, $resultadoAnalisisLaboratorio, $informe,
												$observaciones, $numInspeccion){
		
		$res = $conexion->ejecutarConsulta("INSERT INTO 
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_resultado_laboratorio(
													id_certificacion_bt, identificador, fecha_creacion, 
													num_solicitud, num_muestra, resultado_analisis,
													archivo_informe, observaciones, num_inspeccion)
												VALUES ($idCertificacionBT, '$identificador', now(),
														'$numeroSolicitud', '$numeroMuestra', '$resultadoAnalisisLaboratorio', 
														'$informe', '$observaciones', '$numInspeccion')
												RETURNING id_certificacion_bt_resultado_laboratorio;");
	
		return $res;
	}
	
	public function nuevoDetalleResultadoLaboratorio($conexion, $idResultadoLaboratorio, 
														$idCertificacionBT, $identificador,
														$muestra, $fechaMuestra, $enfermedad, 
														$cantidadMuestras, $numPositivos, $numNegativos,
														$numIndeterminados, $numReactivos, $numSospechosos,			
														$idPruebaLaboratorio, $pruebaLaboratorio,
														$resultadoLaboratorio, $observacionesMuestra, $numInspeccion){
		
		$res = $conexion->ejecutarConsulta("INSERT INTO 
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_resultado_laboratorio_detalle(
										            id_certificacion_bt_resultado_laboratorio, id_certificacion_bt, identificador, 
										            fecha_creacion, muestra, fecha_muestra, enfermedad, 
													cantidad_muestras, num_positivos, num_negativos,
  													num_indeterminados, num_reactivos, num_sospechosos,
										            id_prueba_laboratorio, prueba_laboratorio, resultado,
													observaciones_muestra, num_inspeccion)
										    VALUES ($idResultadoLaboratorio, $idCertificacionBT, '$identificador',
										            now(), '$muestra', '$fechaMuestra', '$enfermedad',
													$cantidadMuestras, $numPositivos, $numNegativos,
													$numIndeterminados, $numReactivos, $numSospechosos,				
										            $idPruebaLaboratorio, '$pruebaLaboratorio', '$resultadoLaboratorio',
													'$observacionesMuestra', '$numInspeccion');");
	
		return $res;
	}
	
	public function cambiarEstadoCertificadoBT($conexion, $idCertificacionBT, $identificador, $estado, $numInspeccion){
	
		$res = $conexion->ejecutarConsulta("UPDATE 
												g_certificacion_brucelosis_tuberculosis.certificacion_bt
   											SET 
												estado='$estado', 
												identificador_modificacion='$identificador', 
												fecha_modificacion=now(),
												num_inspeccion = '$numInspeccion'
								 			WHERE 
												id_certificacion_bt=$idCertificacionBT;");
	
		return $res;
	}
	
	
	
	//Archivo
	public function buscarPruebaLaboratorio($conexion, $idCertificacionBT, $muestra, $idPruebaLaboratorio){
	
		$res = $conexion->ejecutarConsulta("SELECT 
												*
											FROM 
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_resultado_laboratorio
											WHERE	
												id_certificacion_bt = $idCertificacionBT and
												muestra = '$muestra' and
												id_prueba_laboratorio = $idPruebaLaboratorio;");
	
		return $res;
	}
	
	public function nuevaPruebaLaboratorio($conexion, $idCertificacionBT, $identificador, $muestra,
											$fechaMuestra, $idPruebaLaboratorio, 
											$pruebaLaboratorio, $resultadoLaboratorio){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO 
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_resultado_laboratorio(
													id_certificacion_bt, identificador, fecha_creacion,
													muestra, fecha_muestra, id_prueba_laboratorio, 
													prueba_laboratorio, resultado_analisis)
											VALUES ($idCertificacionBT, '$identificador', now(),
													'$muestra', '$fechaMuestra', $idPruebaLaboratorio,
													'$pruebaLaboratorio', '$resultadoLaboratorio')
											RETURNING
												id_certificacion_bt_resultado_laboratorio;");
	
		return $res;
	}
	
	public function imprimirLineaPruebaLaboratorio($idResultadoLaboratorio, $idCertificacionBT,
													$muestra, $fechaMuestra, $pruebaLaboratorio, 
													$resultadoLaboratorio, $ruta){
	
		return '<tr id="R' . $idResultadoLaboratorio . '">' .
				'<td width="30%">' .
				$muestra.
				'</td>
				<td width="30%">' .
				$fechaMuestra.
				'</td>
				<td width="30%">' .
				$pruebaLaboratorio.
				'</td>
				<td width="30%">' .
				$resultadoLaboratorio.
				'</td>
				<td>' .
				'<form id="borrarRegistro" class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarPruebaLaboratorio" data-destino="detalleItem" data-accionEnExito="NADA" >' .
				'<input type="hidden" id="idCertificacionBTResultadoLaboratorio" name="idCertificacionBTResultadoLaboratorio" value="' . $idCertificacionBTResultadoLaboratorio . '" >' .
				'<button class="icono" type="submit" ></button>' .
				'</form>' .
				'</td>' .
				'</tr>';
	}
	
	public function imprimirLineaPruebaLaboratorioConsulta($idResultadoLaboratorio, $idCertificacionBT,
															$muestra, $fechaMuestra, $pruebaLaboratorio, 
															$resultadoLaboratorio, $ruta){
	
		return '<tr id="R' . $idResultadoLaboratorio . '">' .
				'<td width="30%">' .
				$muestra.
				'</td>
				<td width="30%">' .
				$fechaMuestra.
				'</td>
				<td width="30%">' .
				$pruebaLaboratorio.
				'</td>
				<td width="30%">' .
				$resultadoLaboratorio.
				'</td>
				</tr>';
	}
	
	//Archivo abrirCertificacionBTPC
	public function abrirResultadoLaboratorio ($conexion, $idCertificacionBT){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_resultado_laboratorio
											WHERE
												id_certificacion_bt = $idCertificacionBT;");
	
		return $res;
	}
	
	public function abrirResultadoLaboratorioDetalle ($conexion, $idCertificacionBT, $idResultadoLaboratorio){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt_resultado_laboratorio_detalle
											WHERE
												id_certificacion_bt = $idCertificacionBT and
												id_certificacion_bt_resultado_laboratorio = $idResultadoLaboratorio;");
	
		return $res;
	}
	
	
	
	/******** RECERTIFICACIÓN BRUCELOSIS Y TUBERCULOSIS ********/
	
	//Archivo listaSolicitudRecertificacionBrucelosisTuberculosis
	public function buscarRecertificacionBT ($conexion, $numSolicitud, $fecha, $nombrePredio, $nombrePropietario,
			$idProvincia, $idCanton, $idParroquia, $certificacion, $estado){
	
			$numSolicitud = $numSolicitud!="" ? "'%" . $numSolicitud . "%'" : "null";
			$fecha = $fecha!="" ? "'" . $fecha . "'" : "null";
			$nombrePredio = $nombrePredio!="" ? "'%" . $nombrePredio . "%'" : "null";
			$nombrePropietario = $nombrePropietario!="" ? "'%" . $nombrePropietario . "%'" : "null";
			$idProvincia = $idProvincia!="" ? "" . $idProvincia . "" : "null";
			$idCanton = $idCanton!="" ? "" . $idCanton . "" : "null";
			$idParroquia = $idParroquia!="" ? "" . $idParroquia . "" : "null";
			$certificacion = $certificacion!="" ? "'" . $certificacion . "'" : "null";
			$estado = $estado!="" ? "'" . $estado . "'" : "null";

			$res = $conexion->ejecutarConsulta("SELECT
													*
												FROM
													g_certificacion_brucelosis_tuberculosis.busqueda_certificados_bt_recertificacion(
													$numSolicitud, $fecha, $nombrePredio,
													$nombrePropietario, $idProvincia, $idCanton,
													$idParroquia, $certificacion, $estado);");

			return $res;
	}
	
	//Archivo guardarRecertificacionBT
	public function generarNumeroRecertificacionBT($conexion, $codigo, $numSolicitud){
	
		$res = $conexion->ejecutarConsulta("SELECT
												MAX(num_recertificacion) as num_recertificacion
											FROM
												g_certificacion_brucelosis_tuberculosis.recertificacion_bt
											WHERE
												num_recertificacion LIKE '%$codigo%' and
												num_solicitud = '$numSolicitud';");
	
		return $res;
	}
	
	public function nuevaRecertificacionBT ($conexion, $identificador, $idCertificacionBT, $numSolicitud, $fecha,
			$nombreEncuestado, $idPredio, $nombrePredio, $nombrePropietario,
			$cedulaPropietario, $telefonoPropietario, $celularPropietario,
			$correoElectronicoPropietario, $idProvincia, $provincia, $idCanton,
			$canton, $idParroquia, $parroquia, $numCertFiebreAftosa,
			$certificacion, $x, $y, $z, $huso, $imagenMapa, $informe, $numeroInspeccion, $numRecertificacion,
			$fechaMuestreoBrucelosis, $fechaTuberculinizacion, $nombreTecnicoResponsable){
	
			if($certificacion == 'Brucelosis'){
				
				$res = $conexion->ejecutarConsulta("INSERT INTO g_certificacion_brucelosis_tuberculosis.recertificacion_bt(
											            identificador, fecha_creacion, id_certificacion_bt, 
											            num_solicitud, fecha, nombre_encuestado, id_predio, nombre_predio, 
											            nombre_propietario, cedula_propietario, telefono_propietario, 
											            celular_propietario, correo_electronico_propietario, id_provincia, 
											            provincia, id_canton, canton, id_parroquia, parroquia, numero_certificado_fiebre_aftosa, 
											            certificacion_bt, utm_x, utm_y, utm_z, huso_zona, estado,
											            imagen_mapa, ruta_informe, num_inspeccion, num_recertificacion,
														fecha_muestreo_brucelosis, nombre_tecnico_responsable)
													VALUES ('$identificador', now(), $idCertificacionBT, 
															'$numSolicitud', '$fecha', '$nombreEncuestado', $idPredio, '$nombrePredio', 
															'$nombrePropietario', '$cedulaPropietario', '$telefonoPropietario', 
															'$celularPropietario', '$correoElectronicoPropietario', $idProvincia, 
															'$provincia', $idCanton, '$canton', $idParroquia, '$parroquia', '$numCertFiebreAftosa',															
															'$certificacion', '$x', '$y', '$z', '$huso', 'activo',
															'$imagenMapa', '$informe', '$numeroInspeccion', '$numRecertificacion',
															'$fechaMuestreoBrucelosis', '$nombreTecnicoResponsable')
													RETURNING id_recertificacion_bt;");
			}else{
				$res = $conexion->ejecutarConsulta("INSERT INTO g_certificacion_brucelosis_tuberculosis.recertificacion_bt(
														identificador, fecha_creacion, id_certificacion_bt,
														num_solicitud, fecha, nombre_encuestado, id_predio, nombre_predio,
														nombre_propietario, cedula_propietario, telefono_propietario,
														celular_propietario, correo_electronico_propietario, id_provincia,
														provincia, id_canton, canton, id_parroquia, parroquia, numero_certificado_fiebre_aftosa,
														certificacion_bt, utm_x, utm_y, utm_z, huso_zona, estado,
														imagen_mapa, ruta_informe, num_inspeccion, num_recertificacion,
														fecha_tuberculinizacion, nombre_tecnico_responsable)
													VALUES ('$identificador', now(), $idCertificacionBT,
															'$numSolicitud', '$fecha', '$nombreEncuestado', $idPredio, '$nombrePredio',
															'$nombrePropietario', '$cedulaPropietario', '$telefonoPropietario',
															'$celularPropietario', '$correoElectronicoPropietario', $idProvincia,
															'$provincia', $idCanton, '$canton', $idParroquia, '$parroquia', '$numCertFiebreAftosa',
															'$certificacion', '$x', '$y', '$z', '$huso', 'activo',
															'$imagenMapa', '$informe', '$numeroInspeccion', '$numRecertificacion',
															'$fechaTuberculinizacion', '$nombreTecnicoResponsable')
													RETURNING id_recertificacion_bt;");
			}

			return $res;
	}
	
	public function actualizarEstadoCertificacionBT($conexion,$idCertificacionBT,$estado){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_certificacion_brucelosis_tuberculosis.certificacion_bt
											SET
												estado='$estado'
											WHERE
												id_certificacion_bt = '$idCertificacionBT';");
	
		return $res;
	
	}
	
	//Archivo abrirCertificacionBT
	public function abrirRecertificacionBT ($conexion, $idRecertificacionBT){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.recertificacion_bt
											WHERE
												id_recertificacion_bt = $idRecertificacionBT;");
	
		return $res;
	}
	
	public function abrirInformacionPredioRecertificacionBT ($conexion, $idRecertificacionBT){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.recertificacion_bt_informacion_predio
											WHERE
												id_recertificacion_bt = $idRecertificacionBT;");
	
		return $res;
	}
	
	public function abrirInventarioAnimalRecertificacionBT ($conexion, $idRecertificacionBT){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.recertificacion_bt_inventario_animal
											WHERE
												id_recertificacion_bt = $idRecertificacionBT;");
	
		return $res;
	}
	
	public function abrirManejoAnimalRecertificacionBT ($conexion, $idRecertificacionBT){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.recertificacion_bt_manejo_animales_potreros
											WHERE
												id_recertificacion_bt = $idRecertificacionBT;");
	
		return $res;
	}
	
	public function abrirAdquisicionAnimalesRecertificacionBT ($conexion, $idRecertificacionBT){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.recertificacion_bt_adquisicion_animales
											WHERE
												id_recertificacion_bt = $idRecertificacionBT;");
	
		return $res;
	}
	
	public function abrirVeterinarioRecertificacionBT ($conexion, $idRecertificacionBT){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.recertificacion_bt_veterinario
											WHERE
												id_recertificacion_bt = $idRecertificacionBT;");
	
		return $res;
	}
	
	public function abrirVacunacionRecertificacionBT ($conexion, $idRecertificacionBT){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.recertificacion_bt_informacion_vacunacion
											WHERE
												id_recertificacion_bt = $idRecertificacionBT;");
	
		return $res;
	}
	
	public function abrirPatologiaBrucelosisRecertificacionBT ($conexion, $idRecertificacionBT){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.recertificacion_bt_patologia_brucelosis
											WHERE
												id_recertificacion_bt = $idRecertificacionBT;");
	
		return $res;
	}
	
	//Archivo modificarRecertificacionBT
	public function modificarRecertificacionBT ($conexion, $idRecertificacionBT, $identificador, $fecha, 
													$nombreEncuestado, $fechaMuestreoBrucelosis, 
													$fechaTuberculinizacion, $certificacion){
	
		if($certificacion == 'Brucelosis'){
			
			$res = $conexion->ejecutarConsulta("UPDATE
													g_certificacion_brucelosis_tuberculosis.recertificacion_bt
												SET
													fecha='$fecha',
													nombre_encuestado='$nombreEncuestado',
													fecha_muestreo_brucelosis='$fechaMuestreoBrucelosis',
													identificador_modificacion='$identificador',
													fecha_modificacion=now()
												WHERE
													id_recertificacion_bt=$idRecertificacionBT;");
		}else{
			
			$res = $conexion->ejecutarConsulta("UPDATE
													g_certificacion_brucelosis_tuberculosis.recertificacion_bt
												SET
													fecha='$fecha',
													nombre_encuestado='$nombreEncuestado',
													fecha_tuberculinizacion='$fechaTuberculinizacion',
													identificador_modificacion='$identificador',
													fecha_modificacion=now()
												WHERE
													id_recertificacion_bt=$idRecertificacionBT;");
		}
	
		return $res;
	}
	
	//Archivo guardarRecertificacionDatosGeneralesCertificacionBT
	public function generarNumeroInspeccionRecertificacionBT($conexion, $codigo, $numSolicitud){
	
		$res = $conexion->ejecutarConsulta("SELECT
												MAX(num_inspeccion) as num_inspeccion
											FROM
												g_certificacion_brucelosis_tuberculosis.recertificacion_bt
											WHERE
												num_inspeccion LIKE '%$codigo%' and
												num_solicitud = '$numSolicitud';");
	
		return $res;
	}
	
	public function buscarInformacionPredioRecertificacionBT($conexion, $idRecertificacionBT, $numInspeccion){
		
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.recertificacion_bt_informacion_predio
											WHERE
												id_recertificacion_bt = $idRecertificacionBT and
												num_inspeccion = '$numInspeccion';");
	
		return $res;
	}
	
	public function guardarInformacionPredioRecertificacionBT ($conexion, $idRecertificacionBT, $identificador,
																	$cerramientoExterno, $controlIngresoPersonas, 
																	$mangaEmbudoBrete, $identificacionBovinos, 
																	$controlIngresoAnimales, $numInspeccion){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO
												g_certificacion_brucelosis_tuberculosis.recertificacion_bt_informacion_predio(
													identificador, fecha_creacion,
													id_recertificacion_bt, 
													cerramientos, control_ingreso_personas, manga_embudo_brete,
													identificacion_bovinos, control_ingreso_animal, num_inspeccion)
											VALUES ('$identificador', now(),
													$idRecertificacionBT, 
													'$cerramientoExterno', '$controlIngresoPersonas', '$mangaEmbudoBrete',
													'$identificacionBovinos', '$controlIngresoAnimales', '$numInspeccion')
											RETURNING
												id_recertificacion_bt_informacion_predio;");
	
		return $res;
	}
	
	public function imprimirLineaInformacionPredioRecertificacionBT($idInformacionPredio, $cerramientoExterno, 
																		$controlIngresoPersonas, $mangaEmbudoBrete,
																		$identificacionBovinos, $controlIngresoAnimales, 
																		$ruta, $numInspeccion){
	
				return '<tr id="R' . $idInformacionPredio . '">
						<td width="30%">' .
							$numInspeccion.
							'</td>
						<td width="30%">' .
							$cerramientoExterno.
							'</td>
						<td width="30%">' .
							$controlIngresoPersonas.
							'</td>
						<td width="30%">' .
							$mangaEmbudoBrete.
							'</td>
						<td width="30%">' .
							$identificacionBovinos.
							'</td>
						<td width="30%">' .
							$controlIngresoAnimales.
							'</td>
						<td>' .
							'<form id="borrarRegistro" class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarRecertificacionDatosGenerales" data-destino="detalleItem" data-accionEnExito="NADA" >' .
							'<input type="hidden" id="idInformacionPredio" name="idInformacionPredio" value="' . $idInformacionPredio . '" >' .
							'<button class="icono" type="submit" ></button>' .
							'</form>' .
							'</td>' .
							'</tr>';
	}
	
	public function imprimirLineaInformacionPredioRecertificacionBTConsulta($idInformacionPredio, $cerramientoExterno, 
																$controlIngresoPersonas, $mangaEmbudoBrete,
																$identificacionBovinos, $controlIngresoAnimales, 
																$ruta, $numInspeccion){
	
				return '<tr id="R' . $idInformacionPredio . '">
						<td width="30%">' .
							$numInspeccion.
							'</td>
						<td width="30%">' .
							$cerramientoExterno.
						'</td>
						<td width="30%">' .
							$controlIngresoPersonas.
						'</td>
						<td width="30%">' .
							$controlIngresoAnimales.
						'</td>
						<td width="30%">' .
							$identificacionBovinos.
						'</td>
						<td width="30%">' .
							$mangaEmbudoBrete.
						'</td>
						
						</tr>';
	}
	
	//Archivo eliminarDatosGenerales
	public function eliminarInformacionPredioRecertificacion($conexion, $idInformacionPredio){
	
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_certificacion_brucelosis_tuberculosis.recertificacion_bt_informacion_predio
											WHERE
												id_recertificacion_bt_informacion_predio=$idInformacionPredio;");
	
		return $res;
	}
	
	//Archivo guardarInventarioAnimalRecertificacionBT
	public function buscarInventarioAnimalRecertificacionBT($conexion, $idRecertificacionBT, $idAnimalesPredio, $numInspeccion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.recertificacion_bt_inventario_animal
											WHERE
												id_recertificacion_bt = $idRecertificacionBT and
												id_animales_predio = $idAnimalesPredio and
												num_inspeccion = '$numInspeccion';");
	
		return $res;
	}
	
	public function guardarInventarioAnimalRecertificacionBT ($conexion, $idRecertificacionBT, $identificador,
			$idAnimalesPredio, $animalesPredio, $numeroAnimalesPredio,
			$numInspeccion){
		
				$res = $conexion->ejecutarConsulta("INSERT INTO
														g_certificacion_brucelosis_tuberculosis.recertificacion_bt_inventario_animal(
														identificador, fecha_creacion, id_recertificacion_bt, id_animales_predio,
														animales_predio, numero_animales_predio, num_inspeccion)
													VALUES ('$identificador', now(), $idRecertificacionBT, $idAnimalesPredio,
														'$animalesPredio', $numeroAnimalesPredio, '$numInspeccion')
													RETURNING
														id_recertificacion_bt_inventario_animal;");
	
				return $res;
	}
	
	public function imprimirLineaInventarioAnimalRecertificacionBT($idInventarioAnimal, $animalesPredio,
			$numeroAnimalesPredio, $ruta, $numInspeccion){
	
				return '<tr id="R' . $idInventarioAnimal . '">
						<td width="30%">' .
							$numInspeccion.
							'</td>
						<td width="30%">' .
							$animalesPredio.
							'</td>
						<td width="30%">' .
							$numeroAnimalesPredio.
							'</td>
						<td>' .
							'<form id="borrarRegistro" class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarInventarioAnimalRecertificacion" data-destino="detalleItem" data-accionEnExito="NADA" >' .
							'<input type="hidden" id="idInventarioAnimal" name="idInventarioAnimal" value="' . $idInventarioAnimal . '" >' .
							'<button class="icono" type="submit" ></button>' .
							'</form>' .
							'</td>' .
							'</tr>';
	}
	
	public function imprimirLineaInventarioPredioRecertificacionBTConsulta($idInventarioAnimal, $animalesPredio,
			$numeroAnimalesPredio, $ruta, $numInspeccion){
	
				return '<tr id="R' . $idInventarioAnimal . '">
						<td width="30%">' .
							$numInspeccion.
							'</td>
						<td width="30%">' .
							$animalesPredio.
							'</td>
						<td width="30%">' .
							$numeroAnimalesPredio.
							'</td>
						</tr>';
	}
	
	//Archivo eliminarInventarioAnimalRecertificacion
	public function eliminarInventarioAnimalRecertificacion($conexion, $idInventarioAnimal){
	
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_certificacion_brucelosis_tuberculosis.recertificacion_bt_inventario_animal
											WHERE
												id_recertificacion_bt_inventario_animal=$idInventarioAnimal;");
	
		return $res;
	}
	
	//Archivo guardarManejoAnimalesPotrerosRecertificacion
	public function buscarManejoAnimalesPotrerosRecertificacionBT($conexion, $idRecertificacionBT, $numInspeccion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.recertificacion_bt_manejo_animales_potreros
											WHERE
												id_recertificacion_bt = $idRecertificacionBT and
												num_inspeccion = '$numInspeccion';");
	
		return $res;
	}
	
	public function guardarManejoAnimalesPotrerosRecertificacionBT ($conexion, $idRecertificacionBT, $identificador,
																		$pastosComunales, $arriendaPotreros,
																		$arriendaPotrerosOtroPredio,
																		$feriaExposicion,
																		$desinfectaAnimales, 
																		$programaPrediosLibres, $numInspeccion){
	
				$res = $conexion->ejecutarConsulta("INSERT INTO
														g_certificacion_brucelosis_tuberculosis.recertificacion_bt_manejo_animales_potreros(
														identificador, fecha_creacion, id_recertificacion_bt,
														pastos_comunales, arrienda_potreros,
														arrienda_otros_potreros, 
														animales_ferias, desinfecta_animales,
														dentro_programa_predios_libres,
														num_inspeccion)
													VALUES ('$identificador', now(),  $idRecertificacionBT,
														'$pastosComunales', '$arriendaPotreros',
														'$arriendaPotrerosOtroPredio',
														'$feriaExposicion', '$desinfectaAnimales',
														'$programaPrediosLibres',
														'$numInspeccion')
													RETURNING
														id_recertificacion_bt_manejo_animales_potreros;");
	
				return $res;
	}
	
	public function imprimirLineaManejoAnimalesPotrerosRecertificacionBT($idManejoAnimal, $pastosComunales,
			$arriendaPotreros,
			$arriendaPotrerosOtroPredio,
			$feriaExposicion,
			$desinfectaAnimales,
			$programaPrediosLibres, $ruta, $numInspeccion){
	
				return '<tr id="R' . $idManejoAnimal . '">
						<td width="30%">' .
							$numInspeccion.
							'</td>
						<td width="30%">' .
							$pastosComunales.
							'</td>
						<td width="30%">' .
							$arriendaPotreros.
							'</td>
						<td width="30%">' .
							$arriendaPotrerosOtroPredio.
							'</td>
						<td width="30%">' .
							$feriaExposicion.
							'</td>
						<td width="30%">' .
							$desinfectaAnimales.
							'</td>
						<td width="30%">' .
							$programaPrediosLibres.
							'</td>
						<td>' .
							'<form id="borrarRegistro" class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarManejoAnimalesPredioRecertificacion" data-destino="detalleItem" data-accionEnExito="NADA" >' .
							'<input type="hidden" id="idManejoAnimal" name="idManejoAnimal" value="' . $idManejoAnimal . '" >' .
							'<button class="icono" type="submit" ></button>' .
							'</form>' .
							'</td>' .
							'</tr>';
	}
	
	public function imprimirLineaManejoAnimalesPotrerosRecertificacionBTConsulta($idManejoAnimal, $pastosComunales,
																					$arriendaPotreros,
																					$arriendaPotrerosOtroPredio,
																					$feriaExposicion,
																					$desinfectaAnimales,
																					$programaPrediosLibres, $ruta, $numInspeccion){
	
				return '<tr id="R' . $idManejoAnimal . '">
						<td width="30%">' .
							$numInspeccion.
							'</td>
						<td width="30%">' .
							$pastosComunales.
							'</td>
						<td width="30%">' .
							$arriendaPotreros.
							'</td>
						<td width="30%">' .
							$arriendaPotrerosOtroPredio.
							'</td>
						<td width="30%">' .
							$feriaExposicion.
							'</td>
						<td width="30%">' .
							$desinfectaAnimales.
							'</td>
						<td width="30%">' .
							$programaPrediosLibres.
							'</td>
						</tr>';
	}
	
	//Archivo eliminarManejoAnimal
	public function eliminarManejoAnimalRecertificacion($conexion, $idManejoAnimal){
	
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_certificacion_brucelosis_tuberculosis.recertificacion_bt_manejo_animales_potreros
											WHERE
												id_recertificacion_bt_manejo_animales_potreros=$idManejoAnimal;");
									
		return $res;
	}
	
	//Archivo guardarAdquisicionAnimalesRecertificacionBT
	public function buscarAdquisicionAnimalesRecertificacionBT($conexion, $idRecertificacionBT, $idProcedenciaAnimales,
			$idCategoriaAnimalesAdquiere, $numInspeccion){
	
				$res = $conexion->ejecutarConsulta("SELECT
														*
													FROM
														g_certificacion_brucelosis_tuberculosis.recertificacion_bt_adquisicion_animales
													WHERE
														id_recertificacion_bt = $idRecertificacionBT and
														id_procedencia_animales = $idProcedenciaAnimales and
														id_categoria_animales_adquiriente = $idCategoriaAnimalesAdquiere and
														num_inspeccion = '$numInspeccion';");
	
				return $res;
	}
	
	public function guardarAdquisicionAnimalesRecertificacionBT ($conexion, $idRecertificacionBT, $identificador,
			$idProcedenciaAnimales, $procedenciaAnimales,
			$idCategoriaAnimalesAdquiere, $categoriaAnimalesAdquiere,
			$numInspeccion){
	
				$res = $conexion->ejecutarConsulta("INSERT INTO
														g_certificacion_brucelosis_tuberculosis.recertificacion_bt_adquisicion_animales(
														identificador, fecha_creacion,
														id_recertificacion_bt, id_procedencia_animales, procedencia_animales,
														id_categoria_animales_adquiriente, categoria_animales_adquiriente,
														num_inspeccion)
													VALUES ('$identificador', now(),
														$idRecertificacionBT, $idProcedenciaAnimales, '$procedenciaAnimales',
														$idCategoriaAnimalesAdquiere, '$categoriaAnimalesAdquiere',
														'$numInspeccion')
													RETURNING
														id_recertificacion_bt_adquisicion_animales;");
	
				return $res;
	}
	
	public function imprimirLineaAdquisicionAnimalesRecertificacionBT($idAdquisicionAnimal, $procedenciaAnimales,
			$categoriaAnimalesAdquiere, $ruta, $numInspeccion){
	
				return '<tr id="R' . $idAdquisicionAnimal . '">
						<td width="30%">' .
							$numInspeccion.
							'</td>
						<td width="30%">' .
							$procedenciaAnimales.
							'</td>
						<td width="30%">' .
							$categoriaAnimalesAdquiere.
							'</td>
						<td>' .
							'<form id="borrarRegistro" class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarAdquisicionAnimalesRecertificacion" data-destino="detalleItem" data-accionEnExito="NADA" >' .
							'<input type="hidden" id="idAdquisicionAnimal" name="idAdquisicionAnimal" value="' . $idAdquisicionAnimal . '" >' .
							'<button class="icono" type="submit" ></button>' .
							'</form>' .
							'</td>' .
							'</tr>';
	}
	
	public function imprimirLineaAdquisicionAnimalesRecertificacionBTConsulta($idAdquisicionAnimal, $procedenciaAnimales,
			$categoriaAnimalesAdquiere, $ruta, $numInspeccion){
	
				return '<tr id="R' . $idAdquisicionAnimal . '">
						<td width="30%">' .
							$numInspeccion.
							'</td>
						<td width="30%">' .
							$procedenciaAnimales.
							'</td>
						<td width="30%">' .
							$categoriaAnimalesAdquiere.
							'</td>
						</tr>';
	}
	
	//Archivo eliminarAdquisicionAnimalRecertificacion
	public function eliminarAdquisicionAnimalRecertificacion($conexion, $idAdquisicionAnimal){
	
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_certificacion_brucelosis_tuberculosis.recertificacion_bt_adquisicion_animales
											WHERE
												id_recertificacion_bt_adquisicion_animales=$idAdquisicionAnimal;");
	
		return $res;
	}
	
	//Archivo guardarVeterinarioRecertificacionBT
	public function buscarVeterinarioRecertificacionBT($conexion, $idRecertificacionBT, $numInspeccion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.recertificacion_bt_veterinario
											WHERE
												id_recertificacion_bt = $idRecertificacionBT and
												num_inspeccion = '$numInspeccion';");
	
		return $res;
	}
	
	public function guardarVeterinarioRecertificacionBT($conexion, $idRecertificacionBT, $identificador,
			$nombreVeterinario, $telefonoVeterinario, $celularVeterinario,
			$correoElectronicoVeterinario, $idFrecuenciaVisitaVeterinario,
			$frecuenciaVisitaVeterinario, $numInspeccion){
	
				$res = $conexion->ejecutarConsulta("INSERT INTO
														g_certificacion_brucelosis_tuberculosis.recertificacion_bt_veterinario(
														identificador, fecha_creacion,
														id_recertificacion_bt, nombre_veterinario, telefono_veterinario,
														celular_veterinario, correo_electronico_veterinario,
														id_frecuencia_visita_veterinario, frecuencia_visita_veterinario,
														num_inspeccion)
													VALUES ('$identificador', now(),
														$idRecertificacionBT, '$nombreVeterinario', '$telefonoVeterinario',
														'$celularVeterinario', '$correoElectronicoVeterinario',
														$idFrecuenciaVisitaVeterinario, '$frecuenciaVisitaVeterinario',
														'$numInspeccion')
													RETURNING
														id_recertificacion_bt_veterinario;");
	
				return $res;
	}
	
	public function imprimirLineaVeterinarioRecertificacionBT($idVeterinario, $nombreVeterinario, $telefonoVeterinario,
			$celularVeterinario, $correoElectronicoVeterinario,
			$frecuenciaVisitaVeterinario, $ruta, $numInspeccion){
	
				return '<tr id="R' . $idVeterinario . '">
						<td width="30%">' .
							$numInspeccion.
							'</td>
						<td width="30%">' .
							$nombreVeterinario.
							'</td>
						<td width="30%">' .
							$telefonoVeterinario.
							'</td>
						<td width="30%">' .
							$celularVeterinario.
							'</td>
						<td width="30%">' .
							$correoElectronicoVeterinario.
							'</td>
						<td width="30%">' .
							$frecuenciaVisitaVeterinario.
							'</td>
						<td>' .
							'<form id="borrarRegistro" class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarVeterinarioRecertificacion" data-destino="detalleItem" data-accionEnExito="NADA" >' .
							'<input type="hidden" id="idVeterinario" name="idVeterinario" value="' . $idVeterinario . '" >' .
							'<button class="icono" type="submit" ></button>' .
							'</form>' .
							'</td>' .
							'</tr>';
	}
	
	public function imprimirLineaVeterinarioRecertificacionBTConsulta($idVeterinario, $nombreVeterinario, $telefonoVeterinario,
			$celularVeterinario, $correoElectronicoVeterinario,
			$frecuenciaVisitaVeterinario, $ruta, $numInspeccion){
	
				return '<tr id="R' . $idVeterinario . '">
						<td width="30%">' .
							$numInspeccion.
							'</td>
						<td width="30%">' .
							$nombreVeterinario.
							'</td>
						<td width="30%">' .
							$telefonoVeterinario.
							'</td>
						<td width="30%">' .
							$celularVeterinario.
							'</td>
						<td width="30%">' .
							$correoElectronicoVeterinario.
							'</td>
						<td width="30%">' .
							$frecuenciaVisitaVeterinario.
							'</td>
						</tr>';
	}
	
	//Archivo eliminarVeterinarioRecertificacion
	public function eliminarVeterinarioRecertificacion($conexion, $idVeterinario){
	
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_certificacion_brucelosis_tuberculosis.recertificacion_bt_veterinario
											WHERE
												id_recertificacion_bt_veterinario=$idVeterinario;");
	
		return $res;
	}
	
	//Archivo guardarInformacionVacunacionRecertificacion
	public function buscarInformacionVacunacionRecertificacionBT($conexion, $idRecertificacionBT, $idMotivoVacunacion,
			$idVacunasAplicadas, $loteVacuna,
			$numInspeccion, $calendarioVacunacion){
	
				$res = $conexion->ejecutarConsulta("SELECT
														*
													FROM
														g_certificacion_brucelosis_tuberculosis.recertificacion_bt_informacion_vacunacion
													WHERE
														id_recertificacion_bt = $idRecertificacionBT and
														id_motivo_vacunacion = $idMotivoVacunacion and
														id_vacunas_aplicadas = $idVacunasAplicadas and
														lote_vacuna = '$loteVacuna' and
														num_inspeccion = '$numInspeccion' and
														calendario_vacunacion = '$calendarioVacunacion';");
	
				return $res;
	}
	
	public function guardarInformacionVacunacionRecertificacionBT ($conexion, $idRecertificacionBT, $identificador,
																	$idMotivoVacunacion, $motivoVacunacion,
																	$idVacunasAplicadas, $vacunasAplicadas,
																	$fechaVacunacion, $loteVacuna, $numInspeccion,
																	$calendarioVacunacion){
	
				$res = $conexion->ejecutarConsulta("INSERT INTO
														g_certificacion_brucelosis_tuberculosis.recertificacion_bt_informacion_vacunacion(
														identificador, fecha_creacion,
														id_recertificacion_bt, id_motivo_vacunacion, motivo_vacunacion,
														id_vacunas_aplicadas, vacunas_aplicadas, fecha_vacunacion, lote_vacuna, num_inspeccion,
														calendario_vacunacion)
													VALUES ('$identificador', now(),
														$idRecertificacionBT, $idMotivoVacunacion, '$motivoVacunacion',
														$idVacunasAplicadas, '$vacunasAplicadas', '$fechaVacunacion', '$loteVacuna', '$numInspeccion',
														'$calendarioVacunacion')
													RETURNING
														id_recertificacion_bt_informacion_vacunacion;");
	
						return $res;
	}
	
	public function imprimirLineaInformacionVacunacionRecertificacionBT($idInformacionVacunacion, $motivoVacunacion,
			$vacunasAplicadas, $loteVacuna,
			$fechaVacunacion, $ruta, $numInspeccion, $calendarioVacunacion){
	
				return '<tr id="R' . $idInformacionVacunacion . '">
						<td width="30%">' .
							$numInspeccion.
							'</td>
						<td width="30%">' .
							$calendarioVacunacion.
							'</td>
						<td width="30%">' .
							$motivoVacunacion.
							'</td>
						<td width="30%">' .
							$vacunasAplicadas.
							'</td>
						<td width="30%">' .
							$loteVacuna.
							'</td>
						<td width="30%">' .
							$fechaVacunacion.
							'</td>
						<td>' .
							'<form id="borrarRegistro" class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarInformacionVacunacionRecertificacion" data-destino="detalleItem" data-accionEnExito="NADA" >' .
							'<input type="hidden" id="idInformacionVacunacion" name="idInformacionVacunacion" value="' . $idInformacionVacunacion . '" >' .
							'<button class="icono" type="submit" ></button>' .
							'</form>' .
							'</td>' .
							'</tr>';
	}
	
	public function imprimirLineaInformacionVacunacionRecertificacionBTConsulta($idInformacionVacunacion, $motivoVacunacion,
			$vacunasAplicadas, $loteVacuna,
			$fechaVacunacion, $ruta, $numInspeccion, $calendarioVacunacion){
	
				return '<tr id="R' . $idInformacionVacunacion . '">
						<td width="30%">' .
							$numInspeccion.
							'</td>
						<td width="30%">' .
							$calendarioVacunacion.
							'</td>
						<td width="30%">' .
							$motivoVacunacion.
							'</td>
						<td width="30%">' .
							$vacunasAplicadas.
							'</td>
						<td width="30%">' .
							$loteVacuna.
							'</td>
						<td width="30%">' .
							$fechaVacunacion.
							'</td>
						</tr>';
	}
	
	//Archivo eliminarInformacionVacunacion
	public function eliminarInformacionVacunacionRecertificacion($conexion, $idInformacionVacunacion){
	
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_certificacion_brucelosis_tuberculosis.recertificacion_bt_informacion_vacunacion
											WHERE
												id_recertificacion_bt_informacion_vacunacion=$idInformacionVacunacion;");
	
		return $res;
	}
	
	//Archivo guardarPatologiaBrucelosisRecertificacion
	public function buscarPatologiaBrucelosisRecertificacionBT($conexion, $idRecertificacionBT, $numInspeccion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.recertificacion_bt_patologia_brucelosis
											WHERE
												id_recertificacion_bt = $idRecertificacionBT and
												num_inspeccion = '$numInspeccion';");
	
		return $res;
	}
	
	public function guardarPatologiaBrucelosisRecertificacionBT($conexion, $idRecertificacionBT, $identificador,
			$retencionPlacenta, $nacimientoTernerosDebiles,
			$metritisPostParto,
			$abortos, $fiebre,
			$numInspeccion){
				
				$res = $conexion->ejecutarConsulta("INSERT INTO
														g_certificacion_brucelosis_tuberculosis.recertificacion_bt_patologia_brucelosis(
														identificador, fecha_creacion,
														id_recertificacion_bt, retencion_placenta, nacimiento_terneros_debiles,
														metritis_post_parto, 
														abortos, fiebre, num_inspeccion)
													VALUES ('$identificador', now(),
														$idRecertificacionBT, '$retencionPlacenta', '$nacimientoTernerosDebiles',
														'$metritisPostParto',
														'$abortos', '$fiebre', '$numInspeccion')
													RETURNING
														id_recertificacion_bt_patologia_brucelosis;");
	
				return $res;
	}
	
	public function imprimirLineaPatologiaBrucelosisRecertificacionBT($idPatologiaBrucelosis, $retencionPlacenta,
			$nacimientoTernerosDebiles, 
			$metritisPostParto, $abortos,
			$fiebre, $ruta, $numInspeccion){
	
				return '<tr id="R' . $idPatologiaBrucelosis . '">
						<td width="30%">' .
							$numInspeccion.
							'</td>
						<td width="30%">' .
							$retencionPlacenta.
							'</td>
						<td width="30%">' .
							$nacimientoTernerosDebiles.
							'</td>
						<td width="30%">' .
							$metritisPostParto.
							'</td>
						<td width="30%">' .
							$abortos.
							'</td>
						<td width="30%">' .
							$fiebre.
							'</td>
						<td>' .
							'<form id="borrarRegistro" class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarPatologiaBrucelosisRecertificacion" data-destino="detalleItem" data-accionEnExito="NADA" >' .
							'<input type="hidden" id="idPatologiaBrucelosis" name="idPatologiaBrucelosis" value="' . $idPatologiaBrucelosis . '" >' .
							'<button class="icono" type="submit" ></button>' .
							'</form>' .
							'</td>' .
							'</tr>';
	}
	
	public function imprimirLineaPatologiaBrucelosisRecertificacionBTConsulta($idPatologiaBrucelosis, $retencionPlacenta,
			$nacimientoTernerosDebiles, 
			$metritisPostParto, $abortos,
			$fiebre, $ruta, $numInspeccion){
	
				return '<tr id="R' . $idPatologiaBrucelosis . '">
						<td width="30%">' .
							$numInspeccion.
							'</td>
						<td width="30%">' .
							$retencionPlacenta.
							'</td>
						<td width="30%">' .
							$nacimientoTernerosDebiles.
							'</td>
						<td width="30%">' .
							$metritisPostParto.
							'</td>
						<td width="30%">' .
							$abortos.
							'</td>
						<td width="30%">' .
							$fiebre.
							'</td>
						</tr>';
	}
	
	//Archivo eliminarPatologiaBrucelosisRecertificacion
	public function eliminarPatologiaBrucelosisRecertificacion($conexion, $idPatologiaBrucelosis){
	
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_certificacion_brucelosis_tuberculosis.recertificacion_bt_patologia_brucelosis
											WHERE
												id_recertificacion_bt_patologia_brucelosis=$idPatologiaBrucelosis;");
	
		return $res;
	}
	
	//Archivo guardarCierreRecertificacionBTTecnico
	public function cierreRecertificacionBTTecnico($conexion, $idRecertificacionBT, $identificador,
			$observaciones, $estado, $numeroInspeccion,
			$idLaboratorio, $laboratorio, $fechaInspeccion){
	
				if($estado == 'tomaMuestras'){
					
					$res = $conexion->ejecutarConsulta("UPDATE
															g_certificacion_brucelosis_tuberculosis.recertificacion_bt
														SET
															identificador_modificacion='$identificador',
															fecha_modificacion=now(),
															observaciones='$observaciones',
															estado='$estado',
															num_inspeccion = '$numeroInspeccion',
															id_laboratorio = $idLaboratorio,
															laboratorio = '$laboratorio',
															nueva_inspeccion = 'Si',
															fecha_nueva_inspeccion = '$fechaInspeccion'
														WHERE
															id_recertificacion_bt=$idRecertificacionBT;");
				}else{
					
					$res = $conexion->ejecutarConsulta("UPDATE
															g_certificacion_brucelosis_tuberculosis.recertificacion_bt
														SET
															identificador_modificacion='$identificador',
															fecha_modificacion=now(),
															observaciones='$observaciones',
															estado='$estado',
															num_inspeccion = '$numeroInspeccion'
														WHERE
															id_recertificacion_bt=$idRecertificacionBT;");
				}
	
					
				return $res;
	}
	
	public function actualizarCertificadoAprobacionRecertificacion($conexion, $idRecertificacionBT, $identificador,
			$rutaCertificado){
	
				$res = $conexion->ejecutarConsulta("UPDATE
														g_certificacion_brucelosis_tuberculosis.recertificacion_bt
													SET
														ruta_certificado = '$rutaCertificado',
														identificador_aprobador = '$identificador',
														fecha_aprobacion = now()
													WHERE
														id_recertificacion_bt=$idRecertificacionBT;");
	
				return $res;
	}
	
	public function abrirInformacionPredioRecertificacionBTInspeccion ($conexion, $idRecertificacionBT, $numInspeccion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.recertificacion_bt_informacion_predio
											WHERE
												id_recertificacion_bt = $idRecertificacionBT and
												num_inspeccion = '$numInspeccion';");
	
		return $res;
	}
	
	public function abrirInventarioAnimalRecertificacionBTInspeccion ($conexion, $idRecertificacionBT, $numInspeccion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.recertificacion_bt_inventario_animal
											WHERE
												id_recertificacion_bt = $idRecertificacionBT and
												num_inspeccion = '$numInspeccion';");
	
		return $res;
	}
	
	public function abrirManejoAnimalRecertificacionBTInspeccion ($conexion, $idRecertificacionBT, $numInspeccion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.recertificacion_bt_manejo_animales_potreros
											WHERE
												id_recertificacion_bt = $idRecertificacionBT and
												num_inspeccion = '$numInspeccion';");
			
		return $res;
	}
	
	public function abrirAdquisicionAnimalesRecertificacionBTInspeccion ($conexion, $idRecertificacionBT, $numInspeccion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.recertificacion_bt_adquisicion_animales
											WHERE
												id_recertificacion_bt = $idRecertificacionBT and
												num_inspeccion = '$numInspeccion';");
	
		return $res;
	}
	
	public function abrirVeterinarioRecertificacionBTInspeccion ($conexion, $idRecertificacionBT, $numInspeccion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.recertificacion_bt_veterinario
											WHERE
												id_recertificacion_bt = $idRecertificacionBT and
												num_inspeccion = '$numInspeccion';");
	
		return $res;
	}
	
	public function abrirVacunacionRecertificacionBTInspeccion ($conexion, $idRecertificacionBT, $numInspeccion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.recertificacion_bt_informacion_vacunacion
											WHERE
												id_recertificacion_bt = $idRecertificacionBT and
												num_inspeccion = '$numInspeccion';");
	
		return $res;
	}
	
	public function abrirPatologiaBrucelosisRecertificacionBTInspeccion ($conexion, $idRecertificacionBT, $numInspeccion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.recertificacion_bt_patologia_brucelosis
											WHERE
												id_recertificacion_bt = $idRecertificacionBT and
												num_inspeccion = '$numInspeccion';");
	
		return $res;
	}
	
	//Archivo abrirRecertificacionBTLaboratorios
	public function generarNumeroMuestraRecertificacionBT($conexion, $codigo){
	
		$res = $conexion->ejecutarConsulta("SELECT
												MAX(num_muestra) as num_muestra
											FROM
												g_certificacion_brucelosis_tuberculosis.recertificacion_bt_resultado_laboratorio
											WHERE
												num_solicitud LIKE '%$codigo%';");
	
	
		return $res;
	}
	
	//Archivo guardarPruebaLaboratorioRecertificacion
	public function nuevoResultadoLaboratorioRecertificacion($conexion, $idRecertificacionBT, $identificador,
			$numeroSolicitud, $numeroMuestra, $resultadoAnalisisLaboratorio, $informe,
			$observaciones, $numInspeccion){
				
				$res = $conexion->ejecutarConsulta("INSERT INTO
														g_certificacion_brucelosis_tuberculosis.recertificacion_bt_resultado_laboratorio(
														id_recertificacion_bt, identificador, fecha_creacion,
														num_solicitud, num_muestra, resultado_analisis,
														archivo_informe, observaciones, num_inspeccion)
													VALUES ($idRecertificacionBT, '$identificador', now(),
														'$numeroSolicitud', '$numeroMuestra', '$resultadoAnalisisLaboratorio',
														'$informe', '$observaciones', '$numInspeccion')
													RETURNING id_recertificacion_bt_resultado_laboratorio;");
	
				return $res;
	}
	
	public function nuevoDetalleResultadoLaboratorioRecertificacion($conexion, $idResultadoLaboratorio,
			$idRecertificacionBT, $identificador,
			$muestra, $fechaMuestra, $enfermedad,
			$cantidadMuestras, $numPositivos, $numNegativos,
			$numIndeterminados, $numReactivos, $numSospechosos,
			$idPruebaLaboratorio, $pruebaLaboratorio,
			$resultadoLaboratorio, $observacionesMuestra, $numInspeccion){
														
				$res = $conexion->ejecutarConsulta("INSERT INTO
														g_certificacion_brucelosis_tuberculosis.recertificacion_bt_resultado_laboratorio_detalle(
														id_recertificacion_bt_resultado_laboratorio, id_recertificacion_bt, identificador,
														fecha_creacion, muestra, fecha_muestra, enfermedad,
														cantidad_muestras, num_positivos, num_negativos,
														num_indeterminados, num_reactivos, num_sospechosos,
														id_prueba_laboratorio, prueba_laboratorio, resultado,
														observaciones_muestra, num_inspeccion)
													VALUES ($idResultadoLaboratorio, $idRecertificacionBT, '$identificador',
														now(), '$muestra', '$fechaMuestra', '$enfermedad',
														$cantidadMuestras, $numPositivos, $numNegativos,
														$numIndeterminados, $numReactivos, $numSospechosos,
														$idPruebaLaboratorio, '$pruebaLaboratorio', '$resultadoLaboratorio',
														'$observacionesMuestra', '$numInspeccion');");
	
						return $res;
	}
	
	public function cambiarEstadoRecertificadoBT($conexion, $idRecertificacionBT, $identificador, $estado, $numInspeccion){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_certificacion_brucelosis_tuberculosis.recertificacion_bt
											SET
												estado='$estado',
												identificador_modificacion='$identificador',
												fecha_modificacion=now(),
												num_inspeccion = '$numInspeccion'
											WHERE
												id_recertificacion_bt=$idRecertificacionBT;");
	
		return $res;
	}
	
	
	
	//Archivo
	public function buscarPruebaLaboratorioRecertificacion($conexion, $idRecertificacionBT, $muestra, $idPruebaLaboratorio){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.recertificacion_bt_resultado_laboratorio
											WHERE
												id_recertificacion_bt = $idCertificacionBT and
												muestra = '$muestra' and
												id_prueba_laboratorio = $idPruebaLaboratorio;");
	
		return $res;
	}
	
	//Archivo abrirRecertificacionBTPC
	public function abrirResultadoLaboratorioRecertificacion ($conexion, $idRecertificacionBT){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.recertificacion_bt_resultado_laboratorio
											WHERE
												id_recertificacion_bt = $idRecertificacionBT;");
	
		return $res;
	}
	
	public function abrirResultadoLaboratorioDetalleRecertificacion ($conexion, $idRecertificacionBT, $idResultadoLaboratorio){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.recertificacion_bt_resultado_laboratorio_detalle
											WHERE
												id_recertificacion_bt = $idRecertificacionBT and
												id_recertificacion_bt_resultado_laboratorio = $idResultadoLaboratorio;");
	
		return $res;
	}
	
	public function abrirResultadoLaboratorioRecertificacionPorInspeccion ($conexion, $idRecertificacionBT, $numInspeccion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.recertificacion_bt_resultado_laboratorio
											WHERE
												id_recertificacion_bt = $idRecertificacionBT and
												num_inspeccion = '$numInspeccion';");
	
		return $res;
	}
	
	public function abrirResultadoLaboratorioCertificacionPorInspeccion ($conexion, $idCertificacionBT, $numInspeccion){
	
		$res = $conexion->ejecutarConsulta("SELECT
				*
				FROM
				g_certificacion_brucelosis_tuberculosis.certificacion_bt_resultado_laboratorio
				WHERE
				id_certificacion_bt = $idCertificacionBT and
				num_inspeccion = '$numInspeccion';");
	
		return $res;
	}
	
	//PROCESOS AUTOMATICOS
	public function listarCertificadosPorExpirar ($conexion, $estado){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_brucelosis_tuberculosis.certificacion_bt
											WHERE
												estado = '$estado';");
	
		return $res;
	}
}
?>
