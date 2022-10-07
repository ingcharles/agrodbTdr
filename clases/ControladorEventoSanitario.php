 <?php

class ControladorEventoSanitario{
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
	
	public function listarCatalogosHijos($conexion,$tipo, $codigoPadre, $tipoPadre){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_seguimiento_eventos_sanitarios.catalogo
											WHERE
												tipo_catalogo = '$tipo' and
												id_codigo_padre = '$codigoPadre' and
												tipo_catalogo_padre = '$tipoPadre';");
	
		return $res;
	
	}

	//-------------------------------------------------  Evento Sanitario ----------------------------------------------------------------------
	public function buscarEventoSanitarioFiltrado ($conexion, $numSolicitud, $fecha, $idProvincia, 
			$idCanton, $idParroquia, $sitio, $nombrePredio, $estado, $sindrome){
	
		$numSolicitud = $numSolicitud!="" ? "'%" . $numSolicitud . "%'" : "null";
		$fecha = $fecha!="" ? "'" . $fecha . "'" : "null";
		$idProvincia = $idProvincia!="" ? "" . $idProvincia . "" : "null";
		$idCanton = $idCanton!="" ? "" . $idCanton . "" : "null";
		$idParroquia = $idParroquia!="" ? "" . $idParroquia . "" : "null";
		$sitio = $sitio!="" ? "'%" . $sitio . "%'" : "null";
		$nombrePredio = $nombrePredio!="" ? "'%" . $nombrePredio . "%'" : "null";
		$estado = $estado!="" ? "'" . $estado . "'" : "null";
		$sindrome = $sindrome!="" ? "'" . $sindrome . "'" : "null";
	
		$res = $conexion->ejecutarConsulta("SELECT 
												* 
											FROM 
												g_seguimiento_eventos_sanitarios.busqueda_evento_sanitario(
												$numSolicitud, $fecha, $idProvincia, $idCanton, 
												$idParroquia, $sitio, $nombrePredio, $estado, $sindrome) 
											ORDER BY 
												fecha_creacion desc;");

		return $res;
	}
	
	public function buscarEventoSanitarioLaboratorioFiltrado ($conexion, $numSolicitud, $fecha, $idProvincia, 
			$idCanton, $idParroquia, $sitio, $nombrePredio, $estado, $sindrome, $idLaboratorio){
	
		$numSolicitud = $numSolicitud!="" ? "'%" . $numSolicitud . "%'" : "null";
		$fecha = $fecha!="" ? "'" . $fecha . "'" : "null";
		$idProvincia = $idProvincia!="" ? "" . $idProvincia . "" : "null";
		$idCanton = $idCanton!="" ? "" . $idCanton . "" : "null";
		$idParroquia = $idParroquia!="" ? "" . $idParroquia . "" : "null";
		$sitio = $sitio!="" ? "'%" . $sitio . "%'" : "null";
		$nombrePredio = $nombrePredio!="" ? "'%" . $nombrePredio . "%'" : "null";
		$estado = $estado!="" ? "'" . $estado . "'" : "null";
		$sindrome = $sindrome!="" ? "'" . $sindrome . "'" : "null";
		$idLaboratorio = $idLaboratorio!="" ? "" . $idLaboratorio . "" : "null";
	
		$res = $conexion->ejecutarConsulta("SELECT
				*
				FROM
				g_seguimiento_eventos_sanitarios.busqueda_evento_sanitario_laboratorio(
				$numSolicitud, $fecha, $idProvincia, $idCanton,
				$idParroquia, $sitio, $nombrePredio, $estado, $idLaboratorio, $sindrome)
				ORDER BY
				fecha_creacion desc;");
	
				return $res;
	}
	
	public function nuevoEventoSanitario($conexion, 
														$identificador,
														$numeroFormulario, $fecha, $idOrigen, $nombreOrigen, $idCanal, $nombreCanal, $nombrePropietario,
														$cedulaPropietario, $telefonoPropietario, $celularPropietario, $correoElectronicoPropietario,
														$nombrePredio, $extencionPredio, $idMedida, $medida, $otrosPredios, $numeroPredios, $bioseg,
														$idProvincia, $nombreProvincia, $idCanton, $nombreCanton, $idParroquia, $nombreParroquia,
														$idOficina, $oficina, $semana, $husoZona, $utmX, $utmY, $utmZ,													
														$sitioPredio){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO 
												g_seguimiento_eventos_sanitarios.evento_sanitario(
														identificador,	fecha_creacion, numero_formulario, fecha, id_origen,  nombre_origen,
														id_canal, nombre_canal, nombre_propietario, cedula_propietario, telefono_propietario,
														celular_propietario, correo_electronico_propietario, nombre_predio, extencion_predio,
														id_medida, medida, otros_predios, numero_predios, bioseg, 
														id_provincia, provincia, id_canton, canton, id_parroquia, parroquia, id_oficina, oficina,
														semana, huso_zona, utm_x, utm_y, utm_z, sitio_predio, estado) 
											VALUES (	'$identificador',now(),
														'$numeroFormulario', '$fecha', $idOrigen, '$nombreOrigen', $idCanal, '$nombreCanal', '$nombrePropietario',
														'$cedulaPropietario', '$telefonoPropietario', '$celularPropietario', '$correoElectronicoPropietario',
														'$nombrePredio', $extencionPredio, $idMedida, '$medida', '$otrosPredios', $numeroPredios, '$bioseg',
														 $idProvincia,'$nombreProvincia', $idCanton, '$nombreCanton', $idParroquia, '$nombreParroquia',
														 $idOficina, '$oficina', $semana, $husoZona, $utmX, $utmY, $utmZ,
														 '$sitioPredio', 'Creado') 									
											RETURNING
												id_evento_sanitario;");
			
		return $res;
	}
	
	public function abrirEventoSanitario ($conexion, $idEventoSanitario){

		$res = $conexion->ejecutarConsulta("SELECT 
												* 
											FROM 
												g_seguimiento_eventos_sanitarios.evento_sanitario 
											WHERE 
												id_evento_sanitario = '$idEventoSanitario';");
	
		return $res;
	}
	
	public function abrirEventoSanitarioCodigo ($conexion, $codigo){

		$res = $conexion->ejecutarConsulta("SELECT 
												* 
											FROM 
												g_seguimiento_eventos_sanitarios.evento_sanitario 
											WHERE 
												numero_formulario = '$codigo';");
	
		return $res;
	}
	
	
	public function modificarEventoSanitario($conexion, $idEventoSanitario, $identificador, 
														$nombrePropietario,$cedulaPropietario, $telefonoPropietario, $celularPropietario, $correoElectronicoPropietario,
														$nombrePredio, $extencionPredio, $idMedida, $medida, $otrosPredios, $numeroPredios, $bioseg,
														$idOficina, $oficina, $semana, $husoZona, $utmX, $utmY, $utmZ,													
														$sitioPredio){
	echo "UPDATE 
													g_seguimiento_eventos_sanitarios.evento_sanitario 
												SET 
													nombre_propietario = '$nombrePropietario',
													cedula_propietario = '$cedulaPropietario',
													telefono_propietario = '$telefonoPropietario',
													celular_propietario = '$celularPropietario',
													correo_electronico_propietario = '$correoElectronicoPropietario',
													id_oficina = $idOficina,
													oficina = '$oficina', 
													semana = '$semana', 
													huso_zona = '$husoZona', 
													utm_x = '$utmX', 
													utm_y = '$utmY', 
													utm_z = '$utmZ', 
													sitio_predio = '$sitioPredio',
													nombre_predio = '$nombrePredio',
													extencion_predio = '$extencionPredio', 
													id_medida = $idMedida,
													medida = '$medida', 
													otros_predios = '$otrosPredios',
													numero_predios = '$numeroPredios',
													bioseg = '$bioseg', 
													identificador_modificacion = '$identificador',
													fecha_modificacion = now() 
												WHERE 
													id_evento_sanitario=$idEventoSanitario;";
				$res = $conexion->ejecutarConsulta("UPDATE 
													g_seguimiento_eventos_sanitarios.evento_sanitario 
												SET 
													nombre_propietario = '$nombrePropietario',
													cedula_propietario = '$cedulaPropietario',
													telefono_propietario = '$telefonoPropietario',
													celular_propietario = '$celularPropietario',
													correo_electronico_propietario = '$correoElectronicoPropietario',
													id_oficina = $idOficina,
													oficina = '$oficina', 
													semana = '$semana', 
													huso_zona = '$husoZona', 
													utm_x = '$utmX', 
													utm_y = '$utmY', 
													utm_z = '$utmZ', 
													sitio_predio = '$sitioPredio',
													nombre_predio = '$nombrePredio',
													extencion_predio = '$extencionPredio', 
													id_medida = $idMedida,
													medida = '$medida', 
													otros_predios = '$otrosPredios',
													numero_predios = '$numeroPredios',
													bioseg = '$bioseg', 
													identificador_modificacion = '$identificador',
													fecha_modificacion = now() 
												WHERE 
													id_evento_sanitario=$idEventoSanitario;");

			return $res;
	}
	
	public function actualizarMapaEventoSanitario($conexion,$idEventoSanitario,$rutaArchivo){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_seguimiento_eventos_sanitarios.evento_sanitario
											SET
												ruta_mapa='".$rutaArchivo."'
											WHERE
												id_evento_sanitario=".$idEventoSanitario.";");
	
		return $res;
	
	}
	
	public function actualizarMapaPVEventoSanitario($conexion,$idEventoSanitario,$rutaArchivo){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_seguimiento_eventos_sanitarios.medida_sanitaria
											SET
												ruta_mapa_medidas='".$rutaArchivo."'
											WHERE
												id_evento_sanitario=".$idEventoSanitario.";");
	
		return $res;
	
	}
	
	public function actualizarImagenesPVEventoSanitario($conexion,$idEventoSanitario,$rutaArchivo){

		$res = $conexion->ejecutarConsulta("UPDATE 
												g_seguimiento_eventos_sanitarios.medida_sanitaria 
											SET 
												ruta_fotos='".$rutaArchivo."' 
											WHERE 
												id_evento_sanitario=".$idEventoSanitario.";");
	
		return $res;
	
	}
	
	public function actualizarActaEventoSanitario($conexion,$idEventoSanitario,$rutaArchivo){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_seguimiento_eventos_sanitarios.evento_sanitario
											SET
												ruta_acta_final='".$rutaArchivo."'
											WHERE
												id_evento_sanitario=".$idEventoSanitario.";");
	
		return $res;
	
	}
	
	public function actualizarDocumentosEventoSanitario($conexion,$idEventoSanitario,$numVisita, $rutaArchivo){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_seguimiento_eventos_sanitarios.medida_sanitaria
											SET
												ruta_fotos='".$rutaArchivo."'
											WHERE
												id_evento_sanitario=".$idEventoSanitario." and
												numero_visita='".$numVisita."';");
	
		return $res;
	
	}
	
	public function actualizarInformeCierreEventoSanitario($conexion,$idEventoSanitario,$rutaArchivo){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_seguimiento_eventos_sanitarios.evento_sanitario
											SET
												ruta_informe_cierre='".$rutaArchivo."'
											WHERE
												id_evento_sanitario=".$idEventoSanitario.";");
	
		return $res;
	
	}
	
//-------------------------------------------------  Listas ----------------------------------------------------------------------
	public function listarTiposExplotaciones($conexion, $idEventoSanitario){ 
	
		
		$res = $conexion->ejecutarConsulta("SELECT 
												* 
											FROM 
												g_seguimiento_eventos_sanitarios.explotaciones
											WHERE 
												id_evento_sanitario = $idEventoSanitario
											ORDER BY 
												id_evento_sanitario asc;");
	
		return $res;
	}
	
	public function listarTiposExplotacionesAves($conexion, $idEventoSanitario){ 
	
		$res = $conexion->ejecutarConsulta("SELECT 
												* 
											FROM 
												g_seguimiento_eventos_sanitarios.explotaciones_aves
											WHERE 
												id_evento_sanitario = $idEventoSanitario
											ORDER BY 
												id_evento_sanitario asc;");
	
		return $res;
	}
	
	public function listarCronologias($conexion, $idEventoSanitario){ 
	
		$res = $conexion->ejecutarConsulta("SELECT 
												* 
											FROM 
												g_seguimiento_eventos_sanitarios.cronologias
											WHERE 
												id_evento_sanitario = $idEventoSanitario
											ORDER BY 
												id_evento_sanitario asc;");
	
		return $res;
	}
	
	public function listarEspecieAnimalAfactada($conexion, $idEventoSanitario){ 
	
		$res = $conexion->ejecutarConsulta("SELECT 
												* 
											FROM 
												g_seguimiento_eventos_sanitarios.especies_afectadas_evento_sanitario
											WHERE 
												id_evento_sanitario = $idEventoSanitario
											ORDER BY 
												id_evento_sanitario asc;");
	
		return $res;
	}
	
	public function listarVacunacionAftosa($conexion, $idEventoSanitario){ 
	
		$res = $conexion->ejecutarConsulta("SELECT 
												* 
											FROM 
												g_seguimiento_eventos_sanitarios.vacunacion_aftosa
											WHERE 
												id_evento_sanitario = $idEventoSanitario
											ORDER BY 
												id_evento_sanitario asc;");
	
		return $res;
	}
	
	public function listarVacunaciones($conexion, $idEventoSanitario){ 
	
		$res = $conexion->ejecutarConsulta("SELECT 
												* 
											FROM 
												g_seguimiento_eventos_sanitarios.vacunacion
											WHERE 
												id_evento_sanitario = $idEventoSanitario
											ORDER BY 
												id_evento_sanitario asc;");
	
		return $res;
	}
	
	public function listarVacunacionesAves($conexion, $idEventoSanitario){ 
	
		$res = $conexion->ejecutarConsulta("SELECT 
												* 
											FROM 
												g_seguimiento_eventos_sanitarios.vacunacion_aves
											WHERE 
												id_evento_sanitario = $idEventoSanitario
											ORDER BY 
												id_evento_sanitario asc;");
	
		return $res;
	}
	
	public function listarProcedimientosAves($conexion, $idEventoSanitario){ 
	
		$res = $conexion->ejecutarConsulta("SELECT 
												* 
											FROM 
												g_seguimiento_eventos_sanitarios.procedimientos_aves
											WHERE 
												id_evento_sanitario = $idEventoSanitario
											ORDER BY 
												id_evento_sanitario asc;");
	
		return $res;
	}
	
	public function listarMuestras($conexion, $idEventoSanitario){ 
	
		$res = $conexion->ejecutarConsulta("SELECT 
												* 
											FROM 
												g_seguimiento_eventos_sanitarios.muestras
											WHERE 
												id_evento_sanitario = $idEventoSanitario
											ORDER BY 
												id_evento_sanitario asc;");
	
		return $res;
	}
	
	public function listarMuestrasDetalle($conexion, $idEventoSanitario){
	
		$res = $conexion->ejecutarConsulta("SELECT
				*
				FROM
				g_seguimiento_eventos_sanitarios.detalle_muestras
				WHERE
				id_evento_sanitario = $idEventoSanitario
				ORDER BY
				id_evento_sanitario asc;");
	
		return $res;
	}
	
	public function listarMuestrasDetalleInspeccion($conexion, $idEventoSanitario, $numVisita){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_seguimiento_eventos_sanitarios.detalle_muestras
											WHERE
												id_evento_sanitario = $idEventoSanitario and
												numero_visita = '$numVisita'
											ORDER BY
												id_evento_sanitario asc;");
	
		return $res;
	}
	
	public function listarMuestrasPorVisita($conexion, $idEventoSanitario, $numVisita){

		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_seguimiento_eventos_sanitarios.muestras
											WHERE
												id_evento_sanitario = $idEventoSanitario and
												numero_visita = '$numVisita'
											ORDER BY
												id_evento_sanitario asc;");
	
		return $res;
	}
	
	public function listarMuestrasDetallePorVisita($conexion, $idEventoSanitario, $numVisita){
	
		$res = $conexion->ejecutarConsulta("SELECT
												dm.*,
												m.numero_visita
											FROM
												g_seguimiento_eventos_sanitarios.detalle_muestras dm,
												g_seguimiento_eventos_sanitarios.muestras m
											WHERE
												dm.id_evento_sanitario = $idEventoSanitario and
												dm.id_muestra = m.id_muestras and
												m.numero_visita = '$numVisita'
											ORDER BY
												dm.id_evento_sanitario asc;");
	
		return $res;
	}
	
	public function listarOrigenes($conexion, $idEventoSanitario){ 
	
		$res = $conexion->ejecutarConsulta("SELECT 
												* 
											FROM 
												g_seguimiento_eventos_sanitarios.origenes
											WHERE 
												id_evento_sanitario = $idEventoSanitario
											ORDER BY 
												id_evento_sanitario asc;");
	
		return $res;
	}
	
	public function listarOrigenesInspeccion($conexion, $idEventoSanitario, $numVisita){

		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_seguimiento_eventos_sanitarios.origenes
											WHERE
												id_evento_sanitario = $idEventoSanitario and
												numero_visita = '$numVisita'
											ORDER BY
												id_evento_sanitario asc;");
	
		return $res;
	}
	
	public function listarPoblaciones($conexion, $idEventoSanitario){ 
	
		$res = $conexion->ejecutarConsulta("SELECT 
												* 
											FROM 
												g_seguimiento_eventos_sanitarios.poblacion_animal
											WHERE 
												id_evento_sanitario = $idEventoSanitario
											ORDER BY 
												id_evento_sanitario asc;");
	
		return $res;
	}
	
	public function listarPoblacionesInspeccion($conexion, $idEventoSanitario, $numVisita){

		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_seguimiento_eventos_sanitarios.poblacion_animal
											WHERE
												id_evento_sanitario = $idEventoSanitario and
												numero_visita = '$numVisita'
											ORDER BY
												id_evento_sanitario asc;");
	
		return $res;
	}
	
	public function listarPoblacionesAves($conexion, $idEventoSanitario){ 
	
		$res = $conexion->ejecutarConsulta("SELECT 
												* 
											FROM 
												g_seguimiento_eventos_sanitarios.poblacion_aves
											WHERE 
												id_evento_sanitario = $idEventoSanitario
											ORDER BY 
												id_evento_sanitario asc;");
	
		return $res;
	}
	
	public function listarPoblacionesAvesInspeccion($conexion, $idEventoSanitario, $numVisita){

		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_seguimiento_eventos_sanitarios.poblacion_aves
											WHERE
												id_evento_sanitario = $idEventoSanitario and
												numero_visita = '$numVisita'
											ORDER BY
												id_evento_sanitario asc;");
	
		return $res;
	}
	
	public function listarEgresos($conexion, $idEventoSanitario){ 
	
		$res = $conexion->ejecutarConsulta("SELECT 
												* 
											FROM 
												g_seguimiento_eventos_sanitarios.egresos_animales
											WHERE 
												id_evento_sanitario = $idEventoSanitario
											ORDER BY 
												id_evento_sanitario asc;");
	
		return $res;
	}
	
	public function listarEgresosInspeccion($conexion, $idEventoSanitario, $numVisita){
		
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_seguimiento_eventos_sanitarios.egresos_animales
											WHERE
												id_evento_sanitario = $idEventoSanitario and
												numero_visita = '$numVisita'
											ORDER BY
												id_evento_sanitario asc;");
	
		return $res;
	}
	
	public function listarIngresos($conexion, $idEventoSanitario){ 
	
		$res = $conexion->ejecutarConsulta("SELECT 
												* 
											FROM 
												g_seguimiento_eventos_sanitarios.ingresos_animales
											WHERE 
												id_evento_sanitario = $idEventoSanitario
											ORDER BY 
												id_evento_sanitario asc;");
	
		return $res;
	}
	
	public function listarIngresosInspeccion($conexion, $idEventoSanitario, $numVisita){

		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_seguimiento_eventos_sanitarios.ingresos_animales
											WHERE
												id_evento_sanitario = $idEventoSanitario and
												numero_visita = '$numVisita'
											ORDER BY
												id_evento_sanitario asc;");
	
		return $res;
	}
	
	public function listarMovimientosAves($conexion, $idEventoSanitario){ 
	
		$res = $conexion->ejecutarConsulta("SELECT 
												* 
											FROM 
												g_seguimiento_eventos_sanitarios.movimientos_aves
											WHERE 
												id_evento_sanitario = $idEventoSanitario
											ORDER BY 
												id_evento_sanitario asc;");
	
		return $res;
	}
	
	public function listarOrigenMedida($conexion, $idEventoSanitario){
	
		$res = $conexion->ejecutarConsulta("SELECT
				*
				FROM
				g_seguimiento_eventos_sanitarios.medida_sanitaria
				WHERE
				id_evento_sanitario = $idEventoSanitario
				ORDER BY
				numero_visita asc;");
	
		return $res;
	}
	
	public function listarMovimientosAvesInspeccion($conexion, $idEventoSanitario, $numVisita){

		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_seguimiento_eventos_sanitarios.movimientos_aves
											WHERE
												id_evento_sanitario = $idEventoSanitario and
												numero_visita = '$numVisita'
											ORDER BY
												id_evento_sanitario asc;");
	
		return $res;
	}
	
	public function listarCronologiasFinales($conexion, $idEventoSanitario){ 
	
		$res = $conexion->ejecutarConsulta("SELECT 
												* 
											FROM 
												g_seguimiento_eventos_sanitarios.cronologias_finales
											WHERE 
												id_evento_sanitario = $idEventoSanitario
											ORDER BY 
												id_evento_sanitario asc;");
	
		return $res;
	}
	
	public function listarDiagnosticos($conexion, $idEventoSanitario){ 
	
		$res = $conexion->ejecutarConsulta("SELECT 
												* 
											FROM 
												g_seguimiento_eventos_sanitarios.diagnosticos
											WHERE 
												id_evento_sanitario = $idEventoSanitario
											ORDER BY 
												id_evento_sanitario asc;");
	
		return $res;
	}
	
	public function listarPoblacionesFinales($conexion, $idEventoSanitario){ 
	
		$res = $conexion->ejecutarConsulta("SELECT 
												* 
											FROM 
												g_seguimiento_eventos_sanitarios.poblacion_animal_final
											WHERE 
												id_evento_sanitario = $idEventoSanitario
											ORDER BY 
												id_evento_sanitario asc;");
	
		return $res;
	}
	
	public function listarPoblacionesFinalesAves($conexion, $idEventoSanitario){ 
	
		$res = $conexion->ejecutarConsulta("SELECT 
												* 
											FROM 
												g_seguimiento_eventos_sanitarios.poblacion_aves_final
											WHERE 
												id_evento_sanitario = $idEventoSanitario
											ORDER BY 
												id_evento_sanitario asc;");
	
		return $res;
	}
	
	public function listarVacunacionFinales($conexion, $idEventoSanitario){ 
	
		$res = $conexion->ejecutarConsulta("SELECT 
												* 
											FROM 
												g_seguimiento_eventos_sanitarios.vacunacion_final
											WHERE 
												id_evento_sanitario = $idEventoSanitario
											ORDER BY 
												id_evento_sanitario asc;");
	
		return $res;
	}
	
//-------------------------------------------------  Impresión  ----------------------------------------------------------------------
	
	public function imprimirLineaTipoExplotacion(	$idExplotacionRegistrada, $idEventoSanitario, $nombreEspecie, 
													$nombreTipoExplotacion, $ruta ){
																	
		return '<tr id="R' . $idExplotacionRegistrada . '">' .
					'<td width="30%">' .
						$nombreEspecie.
					'</td>' .
					'<td width="30%">' .
						$nombreTipoExplotacion.
					'</td>' .
					'<td>' .
						'<form id="borrarRegistro" class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarExplotacionAnimal" data-destino="detalleItem" data-accionEnExito="NADA" >' .
							'<input type="hidden" id="idExplotacion" name="idExplotacion" value="' . $idExplotacionRegistrada . '" >' .
							'<button class="icono" type="submit" ></button>' .
						'</form>' .
					'</td>' .
				'</tr>';
	}
	
	public function imprimirLineaTipoExplotacionConsulta(	$idExplotacionRegistrada, $idEventoSanitario, $nombreEspecie, 
															$nombreTipoExplotacion, $ruta ){
																	
		return '<tr id="R' . $idExplotacionRegistrada . '">' .
					'<td width="30%">' .
						$nombreEspecie.
					'</td>' .
					'<td width="30%">' .
						$nombreTipoExplotacion.
					'</td>' .
				'</tr>';
	}
			
	public function imprimirLineaTipoExplotacionAves(	$idExplotacionAves, $idEventoSanitario, $numeroRegistroGranja, 
															$numeroCertInspeccion, $numeroGalpones, $capacidadInstalada,
															$capacidadOcupada, $nombreTipoAve, $nombreLineaAve,
															$tipoExplotacion, $descripcionExplotacion, $plantaIncuvacion,
															$faenadoraAves, $viaPrincipal, $lagunasHumedales,
															$centroPoblado, $diagnosticoGranja, $nombreEnfermedad,
															$fechaDiagnostico, $ruta ){
																	
		return '<tr id="R' . $idExplotacionAves . '">' .
					'<td width="30%">' .
						$numeroRegistroGranja.
					'</td>' .
					'<td width="30%">' .
						$numeroCertInspeccion.
					'</td>' .
					'<td width="30%">' .
						$numeroGalpones.
					'</td>' .
					'<td width="30%">' .
						$capacidadInstalada.
					'</td>' .
					'<td width="30%">' .
						$capacidadOcupada.
					'</td>' .
					'<td width="30%">' .
						$nombreTipoAve.
					'</td>' .
					'<td width="30%">' .
						$nombreLineaAve.
					'</td>' .
					'<td width="30%">' .
						$tipoExplotacion.
					'</td>' .
					'<td width="30%">' .
						$descripcionExplotacion.
					'</td>' .
					'<td width="30%">' .
						$plantaIncuvacion.
					'</td>' .
					'<td width="30%">' .
						$faenadoraAves.
					'</td>' .
					'<td width="30%">' .
						$viaPrincipal.
					'</td>' .
					'<td width="30%">' .
						$lagunasHumedales.
					'</td>' .
					'<td width="30%">' .
						$centroPoblado.
					'</td>' .
					'<td width="30%">' .
						$diagnosticoGranja.
					'</td>' .
					'<td width="30%">' .
						$nombreEnfermedad.
					'</td>' .
					'<td width="30%">' .
						$fechaDiagnostico.
					'</td>' .
					'<td>' .
						'<form id="borrarRegistro" class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarExplotacionAves" data-destino="detalleItem" data-accionEnExito="NADA" >' .
							'<input type="hidden" name="idExplotacionAves" value="' . $idExplotacionAves . '" >' .
							'<button class="icono" type="submit" ></button>' .
						'</form>' .
					'</td>' .
				'</tr>';
	}
	
	public function imprimirLineaTipoExplotacionAvesConsulta(	$idExplotacionAves, $idEventoSanitario, $numeroRegistroGranja, 
																	$numeroCertInspeccion, $numeroGalpones, $capacidadInstalada,
																	$capacidadOcupada, $nombreTipoAve, $nombreLineaAve,
																	$tipoExplotacion, $descripcionExplotacion, $plantaIncuvacion,
																	$faenadoraAves, $viaPrincipal, $lagunasHumedales,
																	$centroPoblado, $diagnosticoGranja, $nombreEnfermedad,
																	$fechaDiagnostico, $ruta ){
																	
		return '<tr id="R' . $idExplotacionAves . '">' .
					'<td width="30%">' .
						$numeroRegistroGranja.
					'</td>' .
					'<td width="30%">' .
						$numeroCertInspeccion.
					'</td>' .
					'<td width="30%">' .
						$numeroGalpones.
					'</td>' .
					'<td width="30%">' .
						$capacidadInstalada.
					'</td>' .
					'<td width="30%">' .
						$capacidadOcupada.
					'</td>' .
					'<td width="30%">' .
						$nombreTipoAve.
					'</td>' .
					'<td width="30%">' .
						$nombreLineaAve.
					'</td>' .
					'<td width="30%">' .
						$tipoExplotacion.
					'</td>' .
					'<td width="30%">' .
						$descripcionExplotacion.
					'</td>' .
					'<td width="30%">' .
						$plantaIncuvacion.
					'</td>' .
					'<td width="30%">' .
						$faenadoraAves.
					'</td>' .
					'<td width="30%">' .
						$viaPrincipal.
					'</td>' .
					'<td width="30%">' .
						$lagunasHumedales.
					'</td>' .
					'<td width="30%">' .
						$centroPoblado.
					'</td>' .
					'<td width="30%">' .
						$diagnosticoGranja.
					'</td>' .
					'<td width="30%">' .
						$nombreEnfermedad.
					'</td>' .
					'<td width="30%">' .
						$fechaDiagnostico.
					'</td>' .
				'</tr>';
	}

	public function imprimirLineaCronologia(	$idCronologia, $idEventoSanitario, $nombreTipoCronologia, $fechaCronologia, 
												$horaCronologia, $ruta ){
																	
		return '<tr id="R' . $idCronologia . '">' .
					'<td width="30%">' .
						$nombreTipoCronologia.
					'</td>' .
					'<td width="30%">' .
						$fechaCronologia.
					'</td>' .
					'<td>' .
						'<form id="borrarRegistro" class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarCronologia" data-destino="detalleItem" data-accionEnExito="NADA" >' .
							'<input type="hidden" name="idCronologia" value="' . $idCronologia . '" >' .
							'<button class="icono" type="submit" ></button>' .
						'</form>' .
					'</td>' .
				'</tr>';
	}
	
	public function imprimirLineaCronologiaConsulta(	$idCronologia, $idEventoSanitario, $nombreTipoCronologia, 
														$fechaCronologia, $horaCronologia, $ruta ){
																	
		return '<tr id="R' . $idCronologia . '">' .
					'<td width="30%">' .
						$nombreTipoCronologia.
					'</td>' .
					'<td width="30%">' .
						$fechaCronologia.
					'</td>' .
				'</tr>';
	}
	
	public function imprimirLineaEspecieAfectada($idEspecieAfectadaEventoSanitario,	$idEventoSanitario, $nombreEspecieAfectada, 
													$especificacionEspecieAfectada, $ruta ){
																	
		return '<tr id="R' . $idEspecieAfectadaEventoSanitario . '">' .
					'<td width="30%">' .
						$nombreEspecieAfectada.
					'</td>' .
					'<td width="30%">' .
						$especificacionEspecieAfectada.
					'</td>' .
					'<td>' .
						'<form id="borrarRegistro" class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarEspecieAfectada" data-destino="detalleItem" data-accionEnExito="NADA" >' .
							'<input type="hidden" name="idEspecieAfectadaEventoSanitario" value="' . $idEspecieAfectadaEventoSanitario . '" >' .
							'<button class="icono" type="submit" ></button>' .
						'</form>' .
					'</td>' .
				'</tr>';
	}
	
	public function imprimirLineaEspecieAfectadaConsulta(	$idEspeciesAfectada, $idEventoSanitario, $nombreEspecieAfectada, 
															$especificacionEspecieAfectada, $ruta ){
																	
		return '<tr id="R' . $idEspeciesAfectada . '">' .
					'<td width="30%">' .
						$nombreEspecieAfectada.
					'</td>' .
					'<td width="30%">' .
						$especificacionEspecieAfectada.
					'</td>' .
				'</tr>';
	}
		
	public function imprimirLineaVacunacionAftosa(	$idVacunacionAftosa, $idEventoSanitario,  $nombreTipoVacunacionAftosa, 
													$fechaVacunacionAftosa, $loteVacunacionAftosa, $numeroCertificadoVacunacionAftosa,
													$nombreLaboratorioVacunacionAftosa, $ruta, $enfermedad, $observaciones ){
																	
		return '<tr id="R' . $idVacunacionAftosa . '">' .
					'<td width="30%">' .
						$enfermedad.
					'</td>' .
					'<td width="30%">' .
					$nombreTipoVacunacionAftosa.
					'</td>' .
					'<td width="30%">' .
						$fechaVacunacionAftosa.
					'</td>' .
					'<td width="30%">' .
						$loteVacunacionAftosa.
					'</td>' .
					'<td width="30%">' .
						$numeroCertificadoVacunacionAftosa.
					'</td>' .
					'<td width="30%">' .
						$nombreLaboratorioVacunacionAftosa.
					'</td>' .
					'<td width="30%">' .
					$observaciones.
					'</td>' .
					'<td>' .
						'<form id="borrarRegistro" class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarVacunacionAftosa" data-destino="detalleItem" data-accionEnExito="NADA" >' .
							'<input type="hidden" name="idVacunacionAftosa" value="' . $idVacunacionAftosa . '" >' .
							'<button class="icono" type="submit" ></button>' .
						'</form>' .
					'</td>' .
				'</tr>';
	}
	
	public function imprimirLineaVacunacionAftosaConsulta(	$idVacunacionAftosa, $idEventoSanitario, $nombreTipoVacunacionAftosa, 
															$fechaVacunacionAftosa, $loteVacunacionAftosa, $numeroCertificadoVacunacionAftosa,
															$nombreLaboratorioVacunacionAftosa, $ruta, $enfermedad, $observaciones ){
																	
		return '<tr id="R' . $idVacunacionAftosa . '">' .
					'<td width="30%">' .
						$nombreTipoVacunacionAftosa.
					'</td>' .
					'<td width="30%">' .
					$enfermedad.
					'</td>' .
					'<td width="30%">' .
						$fechaVacunacionAftosa.
					'</td>' .
					'<td width="30%">' .
						$loteVacunacionAftosa.
					'</td>' .
					'<td width="30%">' .
						$numeroCertificadoVacunacionAftosa.
					'</td>' .
					'<td width="30%">' .
						$nombreLaboratorioVacunacionAftosa.
					'</td>' .
					'<td width="30%">' .
					$observaciones.
					'</td>' .
				'</tr>';
	}
	
	public function imprimirLineaVacunacion(	$idVacunacion, $idEventoSanitario, $tipoVacunacion, $numeroAnimalesVacunados, 
												$fechaVacunacion, $observacionVacunacion, $ruta ){
																	
		return '<tr id="R' . $idVacunacion . '">' .
					'<td width="30%">' .
						$tipoVacunacion.
					'</td>' .
					'<td width="30%">' .
						$numeroAnimalesVacunados.
					'</td>' .
					'<td width="30%">' .
						$fechaVacunacion.
					'</td>' .
					'<td width="30%">' .
						$observacionVacunacion.
					'</td>' .
					'<td>' .
						'<form id="borrarRegistro" class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarVacunacion" data-destino="detalleItem" data-accionEnExito="NADA" >' .
							'<input type="hidden" name="idVacunacion" value="' . $idVacunacion . '" >' .
							'<button class="icono" type="submit" ></button>' .
						'</form>' .
					'</td>' .
				'</tr>';
	}
	
	public function imprimirLineaVacunacionConsulta(	$idVacunacion, $idEventoSanitario, $tipoVacunacion, $numeroAnimalesVacunados, 
														$fechaVacunacion, $observacionVacunacion, $ruta ){
																	
		return '<tr id="R' . $idVacunacion . '">' .
					'<td width="30%">' .
						$tipoVacunacion.
					'</td>' .
					'<td width="30%">' .
						$numeroAnimalesVacunados.
					'</td>' .
					'<td width="30%">' .
						$fechaVacunacion.
					'</td>' .
					'<td width="30%">' .
						$observacionVacunacion.
					'</td>' .
				'</tr>';
	}
	
	public function imprimirLineaVacunacionAves(	$idVacunacionAves, $idEventoSanitario, $numeroGalponesVacunacionAves, 
													$numeroLoteVacunacionAves, $enfermedadVacunacionAves, $edadVacunacionAves, 
													$diasVacunacionAves, $mesesVacunacionAves, $tipoVacunacionAves, 
													$cepaVacunacionAves, $viaVacunacionAves, $fechaVacunacionAves, 
													$observacionVacunacionAves, $ruta ){
																	
		return '<tr id="R' . $idVacunacionAves . '">' .
					'<td width="30%">' .
						$numeroGalponesVacunacionAves.
					'</td>' .
					'<td width="30%">' .
						$numeroLoteVacunacionAves.
					'</td>' .
					'<td width="30%">' .
						$enfermedadVacunacionAves.
					'</td>' .
					'<td width="30%">' .
						$edadVacunacionAves.
					'</td>' .
					'<td width="30%">' .
					$diasVacunacionAves.
					'</td>' .
					'<td width="30%">' .
						$mesesVacunacionAves.
					'</td>' .
					'<td width="30%">' .
						$tipoVacunacionAves.
					'</td>' .
					'<td width="30%">' .
						$cepaVacunacionAves.
					'</td>' .
					'<td width="30%">' .
						$viaVacunacionAves.
					'</td>' .
					'<td width="30%">' .
						$fechaVacunacionAves.
					'</td>' .
					'<td width="30%">' .
						$observacionVacunacionAves.
					'</td>' .
					'<td>' .
						'<form id="borrarRegistro" class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarVacunacionAves" data-destino="detalleItem" data-accionEnExito="NADA" >' .
							'<input type="hidden" name="idVacunacionAves" value="' . $idVacunacionAves . '" >' .
							'<button class="icono" type="submit" ></button>' .
						'</form>' .
					'</td>' .
				'</tr>';
	}
	
	public function imprimirLineaVacunacionAvesConsulta(	$idVacunacionAves, $idEventoSanitario, $numeroGalponesVacunacionAves, 
															$numeroLoteVacunacionAves, $enfermedadVacunacionAves, $edadVacunacionAves, 
															$diasVacunacionAves, $mesesVacunacionAves, $tipoVacunacionAves, 
															$cepaVacunacionAves, $viaVacunacionAves,  $fechaVacunacionAves, 
															$observacionVacunacionAves, $ruta ){
																	
		return '<tr id="R' . $idVacunacionAves . '">' .
					'<td width="30%">' .
						$numeroGalponesVacunacionAves.
					'</td>' .
					'<td width="30%">' .
						$numeroLoteVacunacionAves.
					'</td>' .
					'<td width="30%">' .
						$enfermedadVacunacionAves.
					'</td>' .
					'<td width="30%">' .
						$edadVacunacionAves.
					'</td>' .
					'<td width="30%">' .
					$diasVacunacionAves.
					'</td>' .
					'<td width="30%">' .
						$mesesVacunacionAves.
					'</td>' .
					'<td width="30%">' .
						$tipoVacunacionAves.
					'</td>' .
					'<td width="30%">' .
						$cepaVacunacionAves.
					'</td>' .
					'<td width="30%">' .
						$viaVacunacionAves.
					'</td>' .
					'<td width="30%">' .
						$fechaVacunacionAves.
					'</td>' .
					'<td width="30%">' .
						$observacionVacunacionAves.
					'</td>' .
				'</tr>';
	}
	
	public function imprimirLineaProcedimientoAves(	$idProcedimientosAves, $idEventoSanitario, $principioActivoAves,  
													$dosisProcedimientoAves, $fechaInicioProcedimientoAves, $fechaFinProcedimientoAves, 
													$nombreFinalidadProcedimientoAves, $ruta ){
																	
		return '<tr id="R' . $idProcedimientosAves . '">' .
					'<td width="30%">' .
						$principioActivoAves.
					'</td>' .
					'<td width="30%">' .
						$dosisProcedimientoAves.
					'</td>' .
					'<td width="30%">' .
						$fechaInicioProcedimientoAves.
					'</td>' .
					'<td width="30%">' .
						$fechaFinProcedimientoAves.
					'</td>' .
					'<td width="30%">' .
						$nombreFinalidadProcedimientoAves.
					'</td>' .
					'<td>' .
						'<form id="borrarRegistro" class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarProcedimientoAves" data-destino="detalleItem" data-accionEnExito="NADA" >' .
							'<input type="hidden" name="idProcedimientosAves" value="' . $idProcedimientosAves . '" >' .
							'<button class="icono" type="submit" ></button>' .
						'</form>' .
					'</td>' .
				'</tr>';
	}
	
	public function imprimirLineaProcedimientoAvesConsulta(	$idProcedimientosAves, $idEventoSanitario, $principioActivoAves,  
															$dosisProcedimientoAves, $fechaInicioProcedimientoAves, $fechaFinProcedimientoAves, 
															$nombreFinalidadProcedimientoAves, $ruta ){
																	
		return '<tr id="R' . $idProcedimientosAves . '">' .
					'<td width="30%">' .
						$principioActivoAves.
					'</td>' .
					'<td width="30%">' .
						$dosisProcedimientoAves.
					'</td>' .
					'<td width="30%">' .
						$fechaInicioProcedimientoAves.
					'</td>' .
					'<td width="30%">' .
						$fechaFinProcedimientoAves.
					'</td>' .
					'<td width="30%">' .
						$nombreFinalidadProcedimientoAves.
					'</td>' .
				'</tr>';
	}
	
	public function imprimirLineaMuestra(	$idDetalleMuestra, $idMuestra, $idEventoSanitario, $nombreEspecieMuestra, 
											$nombrePruebaMuestra, $nombreTipoMuestra, $numeroMuestras, 
											$fechaColectaMuestra, $fechaEnvioMuestra, 
											$ruta, $numeroVisita ){
																	
		return '<tr id="R' . $idDetalleMuestra . '">' .
					'<td width="30%">' .
						$numeroVisita.
					'</td>' .
					'<td width="30%">' .
					$nombreEspecieMuestra.
					'</td>' .
					'<td width="30%">' .
						$nombrePruebaMuestra.
					'</td>' .
					'<td width="30%">' .
					$nombreTipoMuestra.
					'</td>' .
					'<td width="30%">' .
						$numeroMuestras.
					'</td>' .
					'<td width="30%">' .
						$fechaColectaMuestra.
					'</td>' .
					'<td width="30%">' .
						$fechaEnvioMuestra.
					'</td>' .
					'<td>' .
						'<form id="borrarRegistro" class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarDetalleMuestra" data-destino="detalleItem" data-accionEnExito="NADA" >' .
							'<input type="hidden" name="idDetalleMuestra" value="' . $idDetalleMuestra . '" >' .
							'<button class="icono" type="submit" ></button>' .
						'</form>' .
					'</td>' .
				'</tr>';
	}
	
	public function imprimirLineaMuestraConsulta(	$idDetalleMuestra, $idMuestra, $idEventoSanitario, $nombreEspecieMuestra, 
													$nombrePruebaMuestra, $nombreTipoMuestra, $numeroMuestras, 
													$fechaColectaMuestra, $fechaEnvioMuestra, 
													$ruta, $numeroVisita ){
																	
		return '<tr id="R' . $idDetalleMuestra . '">' .
					'<td width="30%">' .
						$numeroVisita.
					'</td>' .
					'<td width="30%">' .
					$nombreEspecieMuestra.
					'</td>' .
					'<td width="30%">' .
						$nombrePruebaMuestra.
					'</td>' .
					'<td width="30%">' .
						$nombreTipoMuestra.
					'</td>' .
					'<td width="30%">' .
						$numeroMuestras.
					'</td>' .
					'<td width="30%">' .
						$fechaColectaMuestra.
					'</td>' .
					'<td width="30%">' .
						$fechaEnvioMuestra.
					'</td>' .
				'</tr>';
	}
	
	public function imprimirLineaOrigen(	$idOrigenAnimales, $idEventoSanitario, $nombreOrigen, $nombrePaisOrigen, $nombreProvinciaOrigen,
											$nombreCantonOrigen, $fechaOrigen, $ruta, $numVisita ){
																	
		return '<tr id="R' . $idOrigenAnimales . '">' .
					'<td width="30%">' .
						$numVisita.
					'</td>' .
					'<td width="30%">' .
						$nombreOrigen.
					'</td>' .
					'<td width="30%">' .
						$nombrePaisOrigen.
					'</td>' .
					'<td width="30%">' .
						$nombreProvinciaOrigen.
					'</td>' .
					'<td width="30%">' .
					$nombreCantonOrigen.
					'</td>' .
					'<td width="30%">' .
						$fechaOrigen.
					'</td>' .
					'<td>' .
						'<form id="borrarRegistro" class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarOrigenAnimales" data-destino="detalleItem" data-accionEnExito="NADA" >' .
							'<input type="hidden" name="idOrigenAnimales" value="' . $idOrigenAnimales . '" >' .
							'<button class="icono" type="submit" ></button>' .
						'</form>' .
					'</td>' .
				'</tr>';
	}
	
	public function imprimirLineaOrigenConsulta(	$idOrigenAnimales,  $idEventoSanitario, $nombreOrigen, $nombrePaisOrigen, $nombreProvinciaOrigen,
													$nombreCantonOrigen, $fechaOrigen, $ruta, $numVisita ){
																	
		return '<tr id="R' . $idOrigenAnimales . '">' .
					'<td width="30%">' .
						$numVisita.
					'</td>' .
					'<td width="30%">' .
						$nombreOrigen.
					'</td>' .
					'<td width="30%">' .
						$nombrePaisOrigen.
					'</td>' .
					'<td width="30%">' .
						$nombreProvinciaOrigen.
					'</td>' .
					'<td width="30%">' .
					$nombreCantonOrigen.
					'</td>' .
					'<td width="30%">' .
						$fechaOrigen.
					'</td>' .
				'</tr>';
	}
		
	public function imprimirLineaPoblacion(	$idPoblacionAnimales, $idEventoSanitario, $numeroVisita, $nombreEspeciePoblacion, 
											$tipoEspeciePoblacion, $existentes, $enfermos, $muertos,  $sacrificados,  
											$totalSinVacunar, $enfermosSinVacunas, $ruta ){
																	
		return '<tr id="R' . $idPoblacionAnimales . '">' .
					'<td width="30%">' .
						$numeroVisita.
					'</td>' .
					'<td width="30%">' .
						$nombreEspeciePoblacion.
					'</td>' .
					'<td width="30%">' .
						$tipoEspeciePoblacion.
					'</td>' .
					'<td width="30%">' .
						$existentes.
					'</td>' .
					'<td width="30%">' .
						$enfermos.
					'</td>' .
					'<td width="30%">' .
						$muertos.
					'</td>' .
					'<td width="30%">' .
						$sacrificados.
					'</td>' .
					'<td width="30%">' .
						$enfermosSinVacunas.
					'</td>' .
					'<td width="30%">' .
						$totalSinVacunar.
					'</td>' .
					'<td>' .
						'<form id="borrarRegistro" class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarPoblacionAnimales" data-destino="detalleItem" data-accionEnExito="NADA" >' .
							'<input type="hidden" name="idPoblacionAnimales" value="' . $idPoblacionAnimales . '" >' .
							'<button class="icono" type="submit" ></button>' .
						'</form>' .
					'</td>' .
				'</tr>';
	}
	
	public function imprimirLineaPoblacionConsulta(	$idPoblacionAnimales, $idEventoSanitario, $nombreVisita, $nombreEspeciePoblacion, 
													$tipoEspeciePoblacion, $existentes, $enfermos, $muertos, $sacrificados,  
													$totalSinVacunar, $enfermosSinVacunas, $ruta ){
																	
		return '<tr id="R' . $idPoblacionAnimales . '">' .
					'<td width="30%">' .
						$nombreVisita.
					'</td>' .
					'<td width="30%">' .
						$nombreEspeciePoblacion.
					'</td>' .
					'<td width="30%">' .
						$tipoEspeciePoblacion.
					'</td>' .
					'<td width="30%">' .
						$existentes.
					'</td>' .
					'<td width="30%">' .
						$enfermos.
					'</td>' .
					'<td width="30%">' .
						$muertos.
					'</td>' .
					'<td width="30%">' .
						$sacrificados.
					'</td>' .
					'<td width="30%">' .
						$enfermosSinVacunas.
					'</td>' .
					'<td width="30%">' .
						$totalSinVacunar.
					'</td>' .
				'</tr>';
	}
	
	
	public function imprimirLineaPoblacionAves(	$idPoblacionAves,  $idEventoSanitario, $nombreEspeciePoblacionAves, $numeroVisita, $numeroLotePoblacionAves, 
												$numeroGalponPoblacionAves, $edadPoblacionAves, $existentesPoblacionAves,														
												$enfermosPoblacionAves, $muertasPoblacionAves, $destruidasPoblacionAves, $sacrificadasPoblacionAves,	
												$ruta ){
																	
		return '<tr id="R' . $idPoblacionAves . '">' .
					'<td width="30%">' .
						$numeroVisita.
					'</td>' .
					'<td width="30%">' .
						$nombreEspeciePoblacionAves.
					'</td>' .
					'<td width="30%">' .
						$numeroLotePoblacionAves.
					'</td>' .
					'<td width="30%">' .
						$numeroGalponPoblacionAves.
					'</td>' .
					'<td width="30%">' .
						$edadPoblacionAves.
					'</td>' .
					'<td width="30%">' .
						$existentesPoblacionAves.
					'</td>' .
					'<td width="30%">' .
						$enfermosPoblacionAves.
					'</td>' .
					'<td width="30%">' .
						$muertasPoblacionAves.
					'</td>' .
					'<td width="30%">' .
						$destruidasPoblacionAves.
					'</td>' .
					'<td width="30%">' .
						$sacrificadasPoblacionAves.
					'</td>' .
					'<td>' .
						'<form id="borrarRegistro" class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarPoblacionAves" data-destino="detalleItem" data-accionEnExito="NADA" >' .
							'<input type="hidden" name="idPoblacionAves" value="' . $idPoblacionAves . '" >' .
							'<button class="icono" type="submit" ></button>' .
						'</form>' .
					'</td>' .
				'</tr>';
	}
	
	public function imprimirLineaPoblacionAvesConsulta(	$idPoblacionAves, $idEventoSanitario, $nombreEspeciePoblacionAves, $nombreVisita, $numeroLotePoblacionAves, 
														$numeroGalponPoblacionAves, $edadPoblacionAves, $existentesPoblacionAves,														
														$enfermosPoblacionAves, $muertasPoblacionAves, $destruidasPoblacionAves, 
														$sacrificadasPoblacionAves, $ruta ){
																	
		return '<tr id="R' . $idPoblacionAves . '">' .
					'<td width="30%">' .
						$nombreVisita.
					'</td>' .
					'<td width="30%">' .
					$nombreEspeciePoblacionAves.
					'</td>' .
					'<td width="30%">' .
						$numeroLotePoblacionAves.
					'</td>' .
					'<td width="30%">' .
						$numeroGalponPoblacionAves.
					'</td>' .
					'<td width="30%">' .
						$edadPoblacionAves.
					'</td>' .
					'<td width="30%">' .
						$existentesPoblacionAves.
					'</td>' .
					'<td width="30%">' .
						$enfermosPoblacionAves.
					'</td>' .
					'<td width="30%">' .
						$muertasPoblacionAves.
					'</td>' .
					'<td width="30%">' .
						$destruidasPoblacionAves.
					'</td>' .
					'<td width="30%">' .
						$sacrificadasPoblacionAves.
					'</td>' .
				'</tr>';
	}
	
	public function imprimirLineaIngresos(	$idIngreso, $idEventoSanitario, $numeroVisita, $nombreProvincia, $nombreCanton, 
											$nombreParroquia, $nombreEspecie, $propietarioMovimiento, $fincaMovimiento,  $fechaMovimiento, 
											$ruta, $numeroAnimales ){
																	
		return '<tr id="R' . $idIngreso . '">' .
					'<td width="30%">' .
						$numeroVisita.
					'</td>' .
					'<td width="30%">' .
						$nombreProvincia.
					'</td>' .
					'<td width="30%">' .
						$nombreCanton.
					'</td>' .
					'<td width="30%">' .
						$nombreParroquia.
					'</td>' .
					'<td width="30%">' .
						$nombreEspecie.
					'</td>' .
					'<td width="30%">' .
						$propietarioMovimiento.
					'</td>' .
					'<td width="30%">' .
						$fincaMovimiento.
					'</td>' .
					'<td width="30%">' .
						$fechaMovimiento.
					'</td>' .
					'<td width="30%">' .
					$numeroAnimales.
					'</td>' .
					'<td>' .
						'<form id="borrarRegistro" class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarIngreso" data-destino="detalleItem" data-accionEnExito="NADA" >' .
							'<input type="hidden" name="idIngreso" value="' . $idIngreso . '" >' .
							'<button class="icono" type="submit" ></button>' .
						'</form>' .
					'</td>' .
				'</tr>';
	}
	
	public function imprimirLineaIngresosConsulta(	$idIngreso, $idEventoSanitario, $numeroVisita, $nombreProvincia, 
													$nombreCanton, $nombreParroquia, $nombreEspecie, $propietarioMovimiento, 
													$fincaMovimiento,  $fechaMovimiento, $ruta, $numeroAnimales ){
																	
		return '<tr id="R' . $idIngreso . '">' .
					'<td width="30%">' .
						$numeroVisita.
					'</td>' .
					'<td width="30%">' .
						$nombreProvincia.
					'</td>' .
					'<td width="30%">' .
						$nombreCanton.
					'</td>' .
					'<td width="30%">' .
						$nombreParroquia.
					'</td>' .
					'<td width="30%">' .
						$nombreEspecie.
					'</td>' .
					'<td width="30%">' .
						$propietarioMovimiento.
					'</td>' .
					'<td width="30%">' .
						$fincaMovimiento.
					'</td>' .
					'<td width="30%">' .
						$fechaMovimiento.
					'</td>' .
					'<td width="30%">' .
					$numeroAnimales.
					'</td>' .
				'</tr>';
	}
	
	public function imprimirLineaEgresos(	$idEgreso,  $idEventoSanitario, $numeroVisita, $nombreProvincia,  $nombreCanton, 
											$nombreParroquia,  $nombreEspecie,  $propietarioMovimiento,  $fincaMovimiento,  $fechaMovimiento, 
											$ruta, $numeroAnimales ){
																	
		return '<tr id="R' . $idEgreso . '">' .
					'<td width="30%">' .
						$numeroVisita.
					'</td>' .
					'<td width="30%">' .
						$nombreProvincia.
					'</td>' .
					'<td width="30%">' .
						$nombreCanton.
					'</td>' .
					'<td width="30%">' .
						$nombreParroquia.
					'</td>' .
					'<td width="30%">' .
						$nombreEspecie.
					'</td>' .
					'<td width="30%">' .
						$propietarioMovimiento.
					'</td>' .
					'<td width="30%">' .
						$fincaMovimiento.
					'</td>' .
					'<td width="30%">' .
						$fechaMovimiento.
					'</td>' .
					'<td width="30%">' .
					$numeroAnimales.
					'</td>' .
					'<td>' .
						'<form id="borrarRegistro" class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarEgreso" data-destino="detalleItem" data-accionEnExito="NADA" >' .
							'<input type="hidden" name="idEgreso" value="' . $idEgreso . '" >' .
							'<button class="icono" type="submit" ></button>' .
						'</form>' .
					'</td>' .
				'</tr>';
	}
	
	public function imprimirLineaEgresosConsulta(	$idEgreso,  $idEventoSanitario, $numeroVisita, $nombreProvincia,  $nombreCanton, 
													$nombreParroquia, $nombreEspecie,  $propietarioMovimiento, $fincaMovimiento,  $fechaMovimiento, 
													$ruta, $numeroAnimales ){
																	
		return '<tr id="R' . $idEgreso . '">' .
					'<td width="30%">' .
						$numeroVisita.
					'</td>' .
					'<td width="30%">' .
						$nombreProvincia.
					'</td>' .
					'<td width="30%">' .
						$nombreCanton.
					'</td>' .
					'<td width="30%">' .
						$nombreParroquia.
					'</td>' .
					'<td width="30%">' .
						$nombreEspecie.
					'</td>' .
					'<td width="30%">' .
						$propietarioMovimiento.
					'</td>' .
					'<td width="30%">' .
						$fincaMovimiento.
					'</td>' .
					'<td width="30%">' .
						$fechaMovimiento.
					'</td>' .
					'<td width="30%">' .
					$numeroAnimales.
					'</td>' .
				'</tr>';
	}
	
	public function imprimirLineaMovimientoAves(	$idMovimientoAnimalesAves,  $idEventoSanitario, $numeroVisita, $origenMovimientoAves, $paisProvincia, $fechaLlegada,  $huboMovimientoAves, 
													$tipoAves,  $provincia,  $canton,  $parroquia,  $especieAves,  $numeroAvesMovilizadas,  $propietario, 
													$proveedor,  $finalidad,  $fecha,  $ruta ){
																	
		return '<tr id="R' . $idMovimientoAnimalesAves . '">' .
					'<td width="30%">' .
						$numeroVisita.
					'</td>' .
					'<td width="30%">' .
					$origenMovimientoAves.
					'</td>' .
					'<td width="30%">' .
						$paisProvincia.
					'</td>' .
					'<td width="30%">' .
						$fechaLlegada.
					'</td>' .
					'<td width="30%">' .
						$huboMovimientoAves.
					'</td>' .
					'<td width="30%">' .
						$tipoAves.
					'</td>' .
					'<td width="30%">' .
						$provincia.
					'</td>' .
					'<td width="30%">' .
						$canton.
					'</td>' .
					'<td width="30%">' .
						$parroquia.
					'</td>' .
					'<td width="30%">' .
						$especieAves.
					'</td>' .
					'<td width="30%">' .
						$numeroAvesMovilizadas.
					'</td>' .
					'<td width="30%">' .
						$propietario.
					'</td>' .
					'<td width="30%">' .
						$proveedor.
					'</td>' .
					'<td width="30%">' .
						$finalidad.
					'</td>' .
					'<td width="30%">' .
						$fecha.
					'</td>' .
					'<td>' .
						'<form id="borrarRegistro" class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarMovimientoAnimalesAves" data-destino="detalleItem" data-accionEnExito="NADA" >' .
							'<input type="hidden" name="idMovimientoAnimalesAves" value="' . $idMovimientoAnimalesAves . '" >' .
							'<button class="icono" type="submit" ></button>' .
						'</form>' .
					'</td>' .
				'</tr>';
	}
	
	public function imprimirLineaMovimientoAvesConsulta(	$idMovimientoAnimalesAves,  $idEventoSanitario, $numeroVisita, $origenMovimientoAves, $paisProvincia,  $fechaLlegada,  $huboMovimientoAves, 
															$tipoAves,  $provincia,  $canton,  $parroquia,  $especieAves,  $numeroAvesMovilizadas, 
															$propietario,  $proveedor,  $finalidad,  $fecha, 
															$ruta ){
																	
		return '<tr id="R' . $idMovimientoAnimalesAves . '">' .
					'<td width="30%">' .
						$numeroVisita.
					'</td>' .
					'<td width="30%">' .
					$origenMovimientoAves.
					'</td>' .
					'<td width="30%">' .
						$paisProvincia.
					'</td>' .
					'<td width="30%">' .
						$fechaLlegada.
					'</td>' .
					'<td width="30%">' .
						$huboMovimientoAves.
					'</td>' .
					'<td width="30%">' .
						$tipoAves.
					'</td>' .
					'<td width="30%">' .
						$provincia.
					'</td>' .
					'<td width="30%">' .
						$canton.
					'</td>' .
					'<td width="30%">' .
						$parroquia.
					'</td>' .
					'<td width="30%">' .
						$especieAves.
					'</td>' .
					'<td width="30%">' .
						$numeroAvesMovilizadas.
					'</td>' .
					'<td width="30%">' .
						$propietario.
					'</td>' .
					'<td width="30%">' .
						$proveedor.
					'</td>' .
					'<td width="30%">' .
						$finalidad.
					'</td>' .
					'<td width="30%">' .
						$fecha.
					'</td>' .
				'</tr>';
	}
	
	public function imprimirLineaOrigenMedidaConsulta(	$idMedidaSanitaria, $numeroVisita,
			$origenEnfermedad,  $cuarentenaPredio,  $numeroActa,  $medidasSanitarias,  $observaciones,  $rutaMapa,
			$rutaFotos, $ruta ){
					
				return '<tr id="R' . $idMedidaSanitaria . '">' .
						'<td width="30%">' .
						$numeroVisita.
						'</td>' .
						'<td width="30%">' .
						$origenEnfermedad.
						'</td>' .
						'<td width="30%">' .
						$cuarentenaPredio.
						'</td>' .
						'<td width="30%">' .
						$numeroActa.
						'</td>' .
						'<td width="30%">' .
						$medidasSanitarias.
						'</td>' .
						'<td width="30%">' .
						$observaciones.
						'</td>' .
						'<td width="30%">' .
						($rutaFotos==''? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$rutaFotos.' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo cargada</a>').
						'</td>' .
						'</tr>';
	}
	
	public function imprimirLineaCronologiaFinal(	$idCronologiaFinal,  $idEventoSanitario, $nombreTipoCronologiaFinal,  $fechaCronologiaFinal,
													$ruta ){
																	
		return '<tr id="R' . $idCronologiaFinal . '">' .
					'<td width="30%">' .
						$nombreTipoCronologiaFinal.
					'</td>' .
					'<td width="30%">' .
						$fechaCronologiaFinal.
					'</td>' .
					'<td>' .
						'<form id="borrarRegistro" class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarCronologiaFinal" data-destino="detalleItem" data-accionEnExito="NADA" >' .
							'<input type="hidden" name="idCronologiaFinal" value="' . $idCronologiaFinal . '" >' .
							'<button class="icono" type="submit" ></button>' .
						'</form>' .
					'</td>' .
				'</tr>';
	}
	
	public function imprimirLineaCronologiaFinalConsulta(	$idCronologiaFinal,  $idEventoSanitario, $nombreTipoCronologiaFinal,  $fechaCronologiaFinal,
															$ruta ){
																	
		return '<tr id="R' . $idCronologiaFinal . '">' .
					'<td width="30%">' .
						$nombreTipoCronologiaFinal.
					'</td>' .
					'<td width="30%">' .
						$fechaCronologiaFinal.
					'</td>' .
				'</tr>';
	}
	
	public function imprimirLineaDiagnosticoFinal(	$idDiagnosticoFinal,  $idEventoSanitario, $nombreDiagnosticoFinal,  $enfermedad, $descricionDiagnosticoFinal,
													$ruta ){
																	
		return '<tr id="R' . $idDiagnosticoFinal . '">' .
					'<td width="30%">' .
						$nombreDiagnosticoFinal.
					'</td>' .
					'<td width="30%">' .
					$enfermedad.
					'</td>' .
					'<td width="30%">' .
						$descricionDiagnosticoFinal.
					'</td>' .
					'<td>' .
						'<form id="borrarRegistro" class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarDiagnosticoFinal" data-destino="detalleItem" data-accionEnExito="NADA" >' .
							'<input type="hidden" name="idDiagnosticoFinal" value="' . $idDiagnosticoFinal . '" >' .
							'<button class="icono" type="submit" ></button>' .
						'</form>' .
					'</td>' .
				'</tr>';
	}
	
	public function imprimirLineaDiagnosticoFinalConsulta(	$idDiagnosticoFinal,  $idEventoSanitario, $nombreDiagnosticoFinal, $enfermedad, $descricionDiagnosticoFinal,
															$ruta ){
																	
		return '<tr id="R' . $idDiagnosticoFinal . '">' .
					'<td width="30%">' .
						$nombreDiagnosticoFinal.
					'</td>' .
					'<td width="30%">' .
					$enfermedad.
					'</td>' .
					'<td width="30%">' .
						$descricionDiagnosticoFinal.
					'</td>' .
				'</tr>';
	}
	
	public function imprimirLineaPoblacionFinal(	$idPoblacionFinal,  $idEventoSanitario, $nombreEspeciePoblacionFinal,  $nombreCategoriaPoblacionFinal, 
													$existentesPoblacionFinal,  $enfermosPoblacionFinal,  $muertosPoblacionFinal,  $sacrificadosPoblacionFinal, 
													$matadosEliminadosPoblacionFinal, $ruta ){
																	
		return '<tr id="R' . $idPoblacionFinal . '">' .
					'<td width="30%">' .
						$nombreEspeciePoblacionFinal.
					'</td>' .
					'<td width="30%">' .
						$nombreCategoriaPoblacionFinal.
					'</td>' .
					'<td width="30%">' .
						$existentesPoblacionFinal.
					'</td>' .
					'<td width="30%">' .
						$enfermosPoblacionFinal.
					'</td>' .
					'<td width="30%">' .
						$muertosPoblacionFinal.
					'</td>' .
					'<td width="30%">' .
						$sacrificadosPoblacionFinal.
					'</td>' .
					'<td width="30%">' .
						$matadosEliminadosPoblacionFinal.
					'</td>' .
					'<td>' .
						'<form id="borrarRegistro" class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarPoblacionFinal" data-destino="detalleItem" data-accionEnExito="NADA" >' .
							'<input type="hidden" name="idPoblacionFinal" value="' . $idPoblacionFinal . '" >' .
							'<button class="icono" type="submit" ></button>' .
						'</form>' .
					'</td>' .
				'</tr>';
	}
	
	public function imprimirLineaPoblacionFinalConsulta(	$idPoblacionFinal,  $idEventoSanitario, $nombreEspeciePoblacionFinal,  $nombreCategoriaPoblacionFinal, 
															$existentesPoblacionFinal,  $enfermosPoblacionFinal,  $muertosPoblacionFinal,  $sacrificadosPoblacionFinal, 
															$matadosEliminadosPoblacionFinal,
															$ruta ){
																	
		return '<tr id="R' . $idPoblacionFinal . '">' .
					'<td width="30%">' .
						$nombreEspeciePoblacionFinal.
					'</td>' .
					'<td width="30%">' .
						$nombreCategoriaPoblacionFinal.
					'</td>' .
					'<td width="30%">' .
						$existentesPoblacionFinal.
					'</td>' .
					'<td width="30%">' .
						$enfermosPoblacionFinal.
					'</td>' .
					'<td width="30%">' .
						$muertosPoblacionFinal.
					'</td>' .
					'<td width="30%">' .
						$sacrificadosPoblacionFinal.
					'</td>' .
					'<td width="30%">' .
						$matadosEliminadosPoblacionFinal.
					'</td>' .
				'</tr>';
	}
	
	public function imprimirLineaPoblacionFinalAves(	$idPoblacionFinalAves,  $idEventoSanitario, $nombreEspeciePoblacionFinalAves,  $existentesPoblacionFinalAves, 
														$enfermosPoblacionFinalAves,  $muertosPoblacionFinalAves,  $destruidasPoblacionFinalAves, 
														$sacrificadosPoblacionFinalAves,
														$ruta ){
																	
		return '<tr id="R' . $idPoblacionFinalAves . '">' .
					'<td width="30%">' .
						$nombreEspeciePoblacionFinalAves.
					'</td>' .
					'<td width="30%">' .
						$existentesPoblacionFinalAves.
					'</td>' .
					'<td width="30%">' .
						$enfermosPoblacionFinalAves.
					'</td>' .
					'<td width="30%">' .
						$muertosPoblacionFinalAves.
					'</td>' .
					'<td width="30%">' .
						$destruidasPoblacionFinalAves.
					'</td>' .
					'<td width="30%">' .
						$sacrificadosPoblacionFinalAves.
					'</td>' .
					'<td>' .
						'<form id="borrarRegistro" class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarPoblacionFinalAves" data-destino="detalleItem" data-accionEnExito="NADA" >' .
							'<input type="hidden" name="idPoblacionFinalAves" value="' . $idPoblacionFinalAves . '" >' .
							'<button class="icono" type="submit" ></button>' .
						'</form>' .
					'</td>' .
				'</tr>';
	}
	
	public function imprimirLineaPoblacionFinalAvesConsulta(	$idPoblacionFinalAves,  $idEventoSanitario, $nombreEspeciePoblacionFinalAves,  $existentesPoblacionFinalAves, 
																$enfermosPoblacionFinalAves,  $muertosPoblacionFinalAves,  $destruidasPoblacionFinalAves, 
																$sacrificadosPoblacionFinalAves,
																$ruta ){
																	
		return '<tr id="R' . $idPoblacionFinalAves . '">' .
					'<td width="30%">' .
						$nombreEspeciePoblacionFinalAves.
					'</td>' .
					'<td width="30%">' .
						$existentesPoblacionFinalAves.
					'</td>' .
					'<td width="30%">' .
						$enfermosPoblacionFinalAves.
					'</td>' .
					'<td width="30%">' .
						$muertosPoblacionFinalAves.
					'</td>' .
					'<td width="30%">' .
						$destruidasPoblacionFinalAves.
					'</td>' .
					'<td width="30%">' .
						$sacrificadosPoblacionFinalAves.
					'</td>' .
				'</tr>';
	}
	
	public function imprimirLineaVacunacionFinal(	$idVacunacionFinal,  $idEventoSanitario, $nombreTipoVacunacionFinal,  $dosisAplicadaVacunacionFinal, 
													$prediosVacunacionFinal,  $nombreLaboratoriosVacunacionFinal,  $loteVacunacionFinal,
													$ruta ){
																	
		return '<tr id="R' . $idVacunacionFinal . '">' .
					'<td width="30%">' .
						$nombreTipoVacunacionFinal.
					'</td>' .
					'<td width="30%">' .
						$dosisAplicadaVacunacionFinal.
					'</td>' .
					'<td width="30%">' .
						$prediosVacunacionFinal.
					'</td>' .
					'<td width="30%">' .
						$nombreLaboratoriosVacunacionFinal.
					'</td>' .
					'<td width="30%">' .
						$loteVacunacionFinal.
					'</td>' .
					'<td>' .
						'<form id="borrarRegistro" class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarVacunacionFinal" data-destino="detalleItem" data-accionEnExito="NADA" >' .
							'<input type="hidden" name="idVacunacionFinal" value="' . $idVacunacionFinal . '" >' .
							'<button class="icono" type="submit" ></button>' .
						'</form>' .
					'</td>' .
				'</tr>';
	}
	
	public function imprimirLineaVacunacionFinalConsulta(	$idVacunacionFinal,  $idEventoSanitario, $nombreTipoVacunacionFinal,  $dosisAplicadaVacunacionFinal, 
															$prediosVacunacionFinal,  $nombreLaboratoriosVacunacionFinal,  $loteVacunacionFinal,
															$ruta ){
																	
		return '<tr id="R' . $idVacunacionFinal . '">' .
					'<td width="30%">' .
						$nombreTipoVacunacionFinal.
					'</td>' .
					'<td width="30%">' .
						$dosisAplicadaVacunacionFinal.
					'</td>' .
					'<td width="30%">' .
						$prediosVacunacionFinal.
					'</td>' .
					'<td width="30%">' .
						$nombreLaboratoriosVacunacionFinal.
					'</td>' .
					'<td width="30%">' .
						$loteVacunacionFinal.
					'</td>' .
				'</tr>';
	}
	
	public function imprimirLineaPoblacionVisita(	$idPoblacionAnimales, $idEventoSanitario, $nombreVisita, $nombreEspeciePoblacion, 
											$tipoEspeciePoblacion, $existentes, $enfermos, $muertos,  $sacrificados, $matadosEliminados, $ruta ){
																	
		return '<tr id="R' . $idPoblacionAnimales . '">' .
					'<td width="30%">' .
						$nombreVisita.
					'</td>' .
					'<td width="30%">' .
						$nombreEspeciePoblacion.
					'</td>' .
					'<td width="30%">' .
						$tipoEspeciePoblacion.
					'</td>' .
					'<td width="30%">' .
						$existentes.
					'</td>' .
					'<td width="30%">' .
						$enfermos.
					'</td>' .
					'<td width="30%">' .
						$muertos.
					'</td>' .
					'<td width="30%">' .
						$sacrificados.
					'</td>' .
					'<td width="30%">' .
						$matadosEliminados.
					'</td>' .
					'<td>' .
						'<form id="borrarRegistro" class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarPoblacionAnimales" data-destino="detalleItem" data-accionEnExito="NADA" >' .
							'<input type="hidden" name="idPoblacionAnimales" value="' . $idPoblacionAnimales . '" >' .
							'<button class="icono" type="submit" ></button>' .
						'</form>' .
					'</td>' .
				'</tr>';
	}
	
	public function imprimirLineaPoblacionVisitasConsulta(	$idPoblacionAnimales, $idEventoSanitario, $nombreVisita, $nombreEspeciePoblacion, 
													$tipoEspeciePoblacion, $existentes, $enfermos, $muertos, $sacrificados, $matadosEliminados, $ruta ){
																	
		return '<tr id="R' . $idPoblacionAnimales . '">' .
					'<td width="30%">' .
						$nombreVisita.
					'</td>' .
					'<td width="30%">' .
						$nombreEspeciePoblacion.
					'</td>' .
					'<td width="30%">' .
						$tipoEspeciePoblacion.
					'</td>' .
					'<td width="30%">' .
						$existentes.
					'</td>' .
					'<td width="30%">' .
						$enfermos.
					'</td>' .
					'<td width="30%">' .
						$muertos.
					'</td>' .
					'<td width="30%">' .
						$sacrificados.
					'</td>' .
					'<td width="30%">' .
						$matadosEliminados.
					'</td>' .
				'</tr>';
	}
	
	
	
//-------------------------------------------------  Eliminaciones  ----------------------------------------------------------------------
	public function eliminarTiposExplotaciones($conexion, $idExplotacionRegistrada){ 
	
		$res = $conexion->ejecutarConsulta("DELETE FROM 
												g_seguimiento_eventos_sanitarios.explotaciones
											WHERE 
												id_explotacion = $idExplotacionRegistrada");
		return $res;
	}
	
	public function eliminarTiposExplotacionesAves($conexion, $idExplotacionAves){ 
	
		$res = $conexion->ejecutarConsulta("DELETE FROM 
												g_seguimiento_eventos_sanitarios.explotaciones_aves
											WHERE 
												id_explotacion_aves = $idExplotacionAves");
		return $res;
	}
	
	public function eliminarCronologias($conexion, $idCronologia){ 
	
		$res = $conexion->ejecutarConsulta("DELETE FROM 
												g_seguimiento_eventos_sanitarios.cronologias
											WHERE 
												id_cronologia = $idCronologia");
	
		return $res;
	}
	
	public function eliminarEspecieAnimalAfactada($conexion, $idEspecieAfectadaEventoSanitario){ 
	
		$res = $conexion->ejecutarConsulta("DELETE FROM 
												g_seguimiento_eventos_sanitarios.especies_afectadas_evento_sanitario
											WHERE 
												id_especie_afectada_evento_sanitario = $idEspecieAfectadaEventoSanitario");
	
		return $res;
	}
	
	public function eliminarVacunacionAftosa($conexion, $idVacunacionAftosa){ 
	
		$res = $conexion->ejecutarConsulta("DELETE FROM 
												g_seguimiento_eventos_sanitarios.vacunacion_aftosa
											WHERE 
												id_vacunacion_aftosa = $idVacunacionAftosa");
	
		return $res;
	}
	
	public function eliminarVacunaciones($conexion, $idVacunacion){ 
	
		$res = $conexion->ejecutarConsulta("DELETE FROM 
												g_seguimiento_eventos_sanitarios.vacunacion
											WHERE 
												id_vacunacion = $idVacunacion");
	
		return $res;
	}
	
	public function eliminarVacunacionesAves($conexion, $idVacunacionAves){ 
	
		$res = $conexion->ejecutarConsulta("DELETE FROM 
												g_seguimiento_eventos_sanitarios.vacunacion_aves
											WHERE 
												id_vacunacion_aves = $idVacunacionAves");
	
		return $res;
	}
	
	public function eliminarProcedimientosAves($conexion, $idProcedimientoAves){ 
	
		$res = $conexion->ejecutarConsulta("DELETE FROM 
												g_seguimiento_eventos_sanitarios.procedimientos_aves
											WHERE 
												id_procedimientos_aves = $idProcedimientoAves");
	
		return $res;
	}
	
	public function eliminarMuestras($conexion, $idMuestra){ 
	
		$res = $conexion->ejecutarConsulta("DELETE FROM 
												g_seguimiento_eventos_sanitarios.muestras
											WHERE 
												id_muestra = $idMuestra");
	
		return $res;
	}
		
	public function eliminarDetalleMuestras($conexion, $idDetalleMuestra){ 
	
		$res = $conexion->ejecutarConsulta("DELETE FROM 
												g_seguimiento_eventos_sanitarios.detalle_muestras
											WHERE 
												id_detalle_muestra = $idDetalleMuestra");
	
		return $res;
	}
	
	public function eliminarOrigenes($conexion, $idOrigenAnimales){ 
	
		$res = $conexion->ejecutarConsulta("DELETE FROM 
												g_seguimiento_eventos_sanitarios.origenes
											WHERE 
												id_origen_animales = $idOrigenAnimales");
	
		return $res;
	}
	
	public function eliminarPoblaciones($conexion, $idPoblacionAnimales){ 
	
		$res = $conexion->ejecutarConsulta("DELETE FROM 
												g_seguimiento_eventos_sanitarios.poblacion_animal
											WHERE 
												id_poblacion_animales = $idPoblacionAnimales");
	
		return $res;
	}
	
	public function eliminarPoblacionesAves($conexion, $idPoblacionAves){ 
	
		$res = $conexion->ejecutarConsulta("DELETE FROM 
												g_seguimiento_eventos_sanitarios.poblacion_aves
											WHERE 
												id_poblacion_aves = $idPoblacionAves");
	
		return $res;
	}
	
	public function eliminarIngresos($conexion, $idIngreso){ 
	
		$res = $conexion->ejecutarConsulta("DELETE FROM 
												g_seguimiento_eventos_sanitarios.ingresos_animales
											WHERE 
												id_ingreso = $idIngreso");
	
		return $res;
	}
	
	public function eliminarEgresos($conexion, $idEgreso){ 
	
		$res = $conexion->ejecutarConsulta("DELETE FROM 
												g_seguimiento_eventos_sanitarios.egresos_animales
											WHERE 
												id_egreso = $idEgreso");
	
		return $res;
	}
	
	public function eliminarMovimientosAves($conexion, $idMovimientoAnimalesAves){ 
	
		$res = $conexion->ejecutarConsulta("DELETE FROM 
												g_seguimiento_eventos_sanitarios.movimientos_aves
											WHERE 
												id_movimiento_animales_aves = $idMovimientoAnimalesAves");
	
		return $res;
	}
	
	public function eliminarCronologiasFinales($conexion, $idCronologiaFinal){ 
	
		$res = $conexion->ejecutarConsulta("DELETE FROM 
												g_seguimiento_eventos_sanitarios.cronologias_finales
											WHERE 
												id_cronologia_final = $idCronologiaFinal");
	
		return $res;
	}
	
	public function eliminarDiagnosticos($conexion, $idDiagnosticoFinal){ 
	
		$res = $conexion->ejecutarConsulta("DELETE FROM 
												g_seguimiento_eventos_sanitarios.diagnosticos
											WHERE 
												id_diagnosticos_final = $idDiagnosticoFinal");
	
		return $res;
	}
	
	public function eliminarPoblacionesFinales($conexion, $idPoblacionFinal){ 
	
		$res = $conexion->ejecutarConsulta("DELETE FROM 
												g_seguimiento_eventos_sanitarios.poblacion_animal_final
											WHERE 
												id_poblacion_final = $idPoblacionFinal");
	
		return $res;
	}
	
	public function eliminarPoblacionesFinalesAves($conexion, $idPoblacionFinalAves){ 
	
		$res = $conexion->ejecutarConsulta("DELETE FROM 
												g_seguimiento_eventos_sanitarios.poblacion_aves_final
											WHERE 
												id_poblacion_final_aves = $idPoblacionFinalAves");
	
		return $res;
	}
	
	public function eliminarVacunacionFinales($conexion, $idVacunacionFinal){ 
	
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_seguimiento_eventos_sanitarios.vacunacion_final
											WHERE 
												id_vacunacion_final = $idVacunacionFinal");
	
		return $res;
	}
	
//-------------------------------------------------  Busquedas  ----------------------------------------------------------------------
	public function buscarTiposExplotaciones($conexion, $idEventoSanitario, $nombreEspecie, $nombreTipoExplotacion){ 
	
		$res = $conexion->ejecutarConsulta("SELECT * 
											FROM 
												g_seguimiento_eventos_sanitarios.explotaciones
											WHERE 
												id_evento_sanitario = $idEventoSanitario and
												upper(especie) = upper('$nombreEspecie')  and 
												upper(tipo_explotacion) = upper('$nombreTipoExplotacion');");	
		return $res;
	}
	
	public function buscarTiposExplotacionesAves($conexion, $idEventoSanitario, $nombreTipoAve, $nombreLineaAve){ 
	
		$res = $conexion->ejecutarConsulta("SELECT * 
											FROM 
												g_seguimiento_eventos_sanitarios.explotaciones_aves
											WHERE 
												id_evento_sanitario = $idEventoSanitario and
											upper(tipo_ave) = upper('$nombreTipoAve')  and 
												upper(linea_ave) = upper('$nombreLineaAve');");
		
		return $res;
	}
	
	public function buscarCronologias($conexion, $idEventoSanitario, $nombreTipoCronologia){ 
	
		$res = $conexion->ejecutarConsulta("SELECT *
											FROM 
												g_seguimiento_eventos_sanitarios.cronologias
											WHERE 
												id_evento_sanitario = $idEventoSanitario and
												upper(nombre_tipo_cronologia) = upper('$nombreTipoCronologia');");
	
		return $res;
	}
	
	public function buscarEspecieAnimalAfactada($conexion, $idEventoSanitario, $nombreEspecieAfectada){ 
	
		$res = $conexion->ejecutarConsulta("SELECT *
											FROM 
												g_seguimiento_eventos_sanitarios.especies_afectadas_evento_sanitario
											WHERE 
												id_evento_sanitario = $idEventoSanitario and
												upper(nombre_especie_afectada) = upper('$nombreEspecieAfectada');");
	
		return $res;
	}
	
	public function buscarVacunacionAftosa($conexion, $idEventoSanitario, $nombreVacunacion, $nombreEnfermedad){ 
	
		$res = $conexion->ejecutarConsulta("SELECT *
											FROM 
												g_seguimiento_eventos_sanitarios.vacunacion_aftosa
											WHERE 
												id_evento_sanitario = $idEventoSanitario and
												upper(nombre_tipo_vacunacion_aftosa) = upper('$nombreVacunacion') and
												upper(enfermedad) = upper('$nombreEnfermedad');");
	
		return $res;
	}
	
	public function buscarVacunaciones($conexion, $idEventoSanitario, $tipoVacunacion){ 
	
		$res = $conexion->ejecutarConsulta("SELECT *
											FROM 
												g_seguimiento_eventos_sanitarios.vacunacion
											WHERE 
												id_evento_sanitario = $idEventoSanitario and
												upper(tipo_vacunacion) = upper('$tipoVacunacion')  ;");
	
		return $res;
	}
	
	public function buscarVacunacionesAves($conexion, $idEventoSanitario, $enfermedadVacunacionAves,$tipoVacunacionAves){ 
	
		$res = $conexion->ejecutarConsulta("SELECT *
											FROM 
												g_seguimiento_eventos_sanitarios.vacunacion_aves
											WHERE 
												id_evento_sanitario = $idEventoSanitario and
												upper(enfermedad_vacunacion_aves) = upper('$enfermedadVacunacionAves')  and 
												upper(tipo_vacunacion_aves) = upper('$tipoVacunacionAves');");
	
		return $res;
	}
	
	public function buscarProcedimientosAves($conexion, $idEventoSanitario, $nombreFinalidadProcedimientoAves){ 
	
		$res = $conexion->ejecutarConsulta("SELECT *
											FROM 
												g_seguimiento_eventos_sanitarios.procedimientos_aves
											WHERE 
												id_evento_sanitario = $idEventoSanitario and
												upper(finalidad_procedimiento_aves) = upper('$nombreFinalidadProcedimientoAves');");
	
		return $res;
	}
	
	public function buscarMuestras($conexion, $idEventoSanitario){
	
		$res = $conexion->ejecutarConsulta("SELECT *
				FROM
				g_seguimiento_eventos_sanitarios.muestras
				WHERE
				id_evento_sanitario = $idEventoSanitario;");
	
		return $res;
	}
	
	public function buscarDetalleMuestras($conexion, $idEventoSanitario, $nombreEspecieMuestra, $nombreTipoMuestra, $numVisita){ 
	
		$res = $conexion->ejecutarConsulta("SELECT *
											FROM 
												g_seguimiento_eventos_sanitarios.muestras
											WHERE 
												id_evento_sanitario = $idEventoSanitario and
												upper(nombre_especie_muestra) = upper('$nombreEspecieMuestra')  and 
												upper(nombre_tipo_muestra) = upper('$nombreTipoMuestra') and
												numero_visita = '$numVisita';");
	
		return $res;
	}
	
	public function buscarMuestrasDetalle($conexion, $idEventoSanitario, $idEspecieMuestra, $idPruebasMuestra, $idTipoMuestra, $numeroVisita){
		
		$res = $conexion->ejecutarConsulta("SELECT 
												*
											FROM
												g_seguimiento_eventos_sanitarios.detalle_muestras
											WHERE
												id_evento_sanitario = $idEventoSanitario and
												id_especie_muestra = $idEspecieMuestra and
												id_prueba_muestra = $idPruebasMuestra and
												id_tipo_muestra = $idTipoMuestra and
												numero_visita = '$numeroVisita';");
	
		return $res;
	}
	
	public function buscarOrigenes($conexion, $idEventoSanitario, $nombreOrigen){ 
	
		$res = $conexion->ejecutarConsulta("SELECT *
											FROM 
												g_seguimiento_eventos_sanitarios.origenes
											WHERE 
												id_evento_sanitario = $idEventoSanitario and
												upper(nombre_origen) = upper('$nombreOrigen');");
	
		return $res;
	}
	
	public function buscarPoblaciones($conexion, $idEventoSanitario, $nombreVisita, $nombreEspeciePoblacion, $tipoEspeciePoblacion){ 
	
		$res = $conexion->ejecutarConsulta("SELECT *
											FROM 
												g_seguimiento_eventos_sanitarios.poblacion_animal
											WHERE 
												id_evento_sanitario = $idEventoSanitario and
												upper(numero_visita) = upper('$nombreVisita')  and 
												upper(nombre_especie_poblacion) = upper('$nombreEspeciePoblacion')  and 
												upper(tipo_especie_poblacion) = upper('$tipoEspeciePoblacion');");
	
		return $res;
	}
	
	public function buscarPoblacionesAves($conexion, $idEventoSanitario, $nombreEspeciePoblacionAves, $numeroVisita){ 
	
		$res = $conexion->ejecutarConsulta("SELECT *
											FROM 
												g_seguimiento_eventos_sanitarios.poblacion_aves
											WHERE 
												id_evento_sanitario = $idEventoSanitario and
												upper(nombre_especie_poblacion_aves) = upper('$nombreEspeciePoblacionAves')  and 
												upper(numero_visita) = upper('$numeroVisita');");
	
		return $res;
	}
	
	public function buscarIngresos($conexion, $idEventoSanitario, $nombreTipoMovimientoIngreso){ 
	
		$res = $conexion->ejecutarConsulta("SELECT *
											FROM 
												g_seguimiento_eventos_sanitarios.ingresos_animales
											WHERE 
												id_evento_sanitario = $idEventoSanitario and
												upper(nombre_tipo_movimiento_ingreso) = upper('$nombreTipoMovimientoIngreso');");
	
		return $res;
	}
	
	public function buscarEgresos($conexion, $idEventoSanitario, $nombreTipoMovimientoEgreso){ 
	
		$res = $conexion->ejecutarConsulta("SELECT *
											FROM 
												g_seguimiento_eventos_sanitarios.egresos_animales
											WHERE 
												id_evento_sanitario = $idEventoSanitario and
												upper(nombre_tipo_movimiento_egreso) = upper('$nombreTipoMovimientoEgreso');");
	
		return $res;
	}

	public function buscarMovimientosAves($conexion, $idEventoSanitario, $numeroVisita, $origenMovimientoAves, $tipoAves, $especieAves){ 
		
		$res = $conexion->ejecutarConsulta("SELECT *
											FROM 
												g_seguimiento_eventos_sanitarios.movimientos_aves
											WHERE 
												id_evento_sanitario = $idEventoSanitario and
												upper(origen_movimiento_aves) = upper('$origenMovimientoAves') and
												upper(tipo_aves) = upper('$tipoAves')  and 
												upper(nombre_especie_aves) = upper('$especieAves') and
												numero_visita = '$numeroVisita';");
	
		return $res;
	}
	
	public function buscarCronologiasFinales($conexion, $idEventoSanitario, $nombreTipoCronologiaFinal){ 
	
		$res = $conexion->ejecutarConsulta("SELECT *
											FROM 
												g_seguimiento_eventos_sanitarios.cronologias_finales
											WHERE 
												id_evento_sanitario = $idEventoSanitario and
												upper(nombre_tipo_cronologia_final) = upper('$nombreTipoCronologiaFinal');");
	
		return $res;
	}
	
	public function buscarDiagnosticos($conexion, $idEventoSanitario, $nombreDiagnosticoFinal, $nombreEnfermedadFinal){ 
	
		$res = $conexion->ejecutarConsulta("SELECT *
											FROM 
												g_seguimiento_eventos_sanitarios.diagnosticos
											WHERE 
												id_evento_sanitario = $idEventoSanitario and
												upper(nombre_diagnostico_final) = upper('$nombreDiagnosticoFinal') and
												upper(enfermedad) = upper('$nombreEnfermedadFinal');");
	
		return $res;
	}
	
	public function buscarPoblacionesFinales($conexion, $idEventoSanitario, $nombreEspeciePoblacionFinal,  $nombreCategoriaPoblacionFinal){ 
	
		
		$res = $conexion->ejecutarConsulta("SELECT *
											FROM 
												g_seguimiento_eventos_sanitarios.poblacion_animal_final
											WHERE 
												id_evento_sanitario = $idEventoSanitario and
												upper(nombre_especie_poblacion_final) = upper('$nombreEspeciePoblacionFinal')  and 
												upper(nombre_categoria_poblacion_final) = upper('$nombreCategoriaPoblacionFinal');");
	
		return $res;
	}
	
	public function buscarPoblacionesFinalesAves($conexion, $idEventoSanitario, $nombreEspeciePoblacionFinalAves){ 
	
		$res = $conexion->ejecutarConsulta("SELECT *
											FROM 
												g_seguimiento_eventos_sanitarios.poblacion_aves_final
											WHERE 
												id_evento_sanitario = $idEventoSanitario and
												upper(nombre_especie_poblacion_final_aves) = upper('$nombreEspeciePoblacionFinalAves');");
	
		return $res;
	}
	
	public function buscarVacunacionFinales($conexion, $idEventoSanitario, $nombreTipoVacunacionFinal){ 
	
		$res = $conexion->ejecutarConsulta("SELECT *
											FROM 
												g_seguimiento_eventos_sanitarios.vacunacion_final
											WHERE 
												id_evento_sanitario = $idEventoSanitario and
												upper(nombre_tipo_vacunacion_final) = upper('$nombreTipoVacunacionFinal');");
	
		return $res;
	}
	
//-------------------------------------------------  nuevos  ----------------------------------------------------------------------
	public function nuevaExplotacion(	$conexion, $idEventoSanitario, $idEspecie, $nombreEspecie,
										$idTipoExplotacion, $nombreTipoExplotacion, $identificador
													){
												
														
														
		$res = $conexion->ejecutarConsulta("INSERT INTO
											g_seguimiento_eventos_sanitarios.explotaciones(
												id_evento_sanitario, id_especie, especie, id_tipo_explotacion, tipo_explotacion,
												identificador, fecha_creacion
											)
											VALUES (
												$idEventoSanitario, $idEspecie, '$nombreEspecie', $idTipoExplotacion, '$nombreTipoExplotacion', 
												'$identificador', now()
											) 
											RETURNING 
												id_explotacion;");
							
		return $res;
	}

	public function nuevaExplotacionAves(	$conexion, $idEventoSanitario, $explotacionAves, $numeroRegistroGranja, 
												$numeroCertInspeccion, $numeroGalpones, $capacidadInstalada,
												$capacidadOcupada, $idTipoAve, $nombreTipoAve, $idLineaAve,$nombreLineaAve,
												$tipoExplotacion, $descripcionExplotacion, $plantaIncuvacion,
												$faenadoraAves, $viaPrincipal, $lagunasHumedales,
												$centroPoblado, $diagnosticoGranja, $idEnfermedad,$nombreEnfermedad,
												$fechaDiagnostico, $identificador
													){
															
		$res = $conexion->ejecutarConsulta("INSERT INTO	
											g_seguimiento_eventos_sanitarios.explotaciones_aves (
												id_evento_sanitario, explotacion_aves, numero_registro_granja, numero_cert_inspeccion, 
												numero_galpones, capacidad_instalada, capacidad_ocupada, id_tipo_ave, tipo_ave, 
												id_linea_ave, linea_ave, 
												tipo_explotacion, descripcion_explotacion, planta_incuvacion, faenadora_aves, via_principal, 
												lagunas_humedales, centro_poblado, diagnostico_granja, id_enfermedad, enfermedad, fecha_diagnostico,
												identificador, fecha_creacion
											)
											VALUES (
												$idEventoSanitario, '$explotacionAves', $numeroRegistroGranja, 
												$numeroCertInspeccion, $numeroGalpones, $capacidadInstalada,
												$capacidadOcupada, $idTipoAve, '$nombreTipoAve', $idLineaAve,'$nombreLineaAve',
												'$tipoExplotacion', '$descripcionExplotacion', $plantaIncuvacion,
												$faenadoraAves, $viaPrincipal, $lagunasHumedales,
												$centroPoblado, '$diagnosticoGranja', $idEnfermedad,'$nombreEnfermedad',
												'$fechaDiagnostico', 
												'$identificador', now()
											) 
											RETURNING 
												id_explotacion_aves;");
									
		return $res;
	}

	public function nuevaCronologia(	$conexion, $idEventoSanitario, $idTipoCronologia, $nombreTipoCronologia, $fechaCronologia, 
										$horaCronologia , $identificador
													){
												
		$res = $conexion->ejecutarConsulta("INSERT INTO
											g_seguimiento_eventos_sanitarios.cronologias(
													id_evento_sanitario, id_tipo_cronologia,  nombre_tipo_cronologia, 
													fecha_cronologia,  hora_cronologia,
												identificador, fecha_creacion 
											)
											VALUES (
												$idEventoSanitario, $idTipoCronologia, '$nombreTipoCronologia', '$fechaCronologia', 
												'$horaCronologia', 
												'$identificador', now()
											) 
											RETURNING 
												id_cronologia;");
							
		return $res;
	}

	public function nuevaEspeciesAfectadasEventoSanitario(	$conexion, $idEventoSanitario, $idEspecieAfectada,  $nombreEspecieAfectada,
													$especificacionEspecieAfectada, $identificador
													){
												
		$res = $conexion->ejecutarConsulta("INSERT INTO
											g_seguimiento_eventos_sanitarios.especies_afectadas_evento_sanitario (
												id_evento_sanitario, id_especie_afectada, 
												nombre_especie_afectada, especificacion_especie_afectada,
												identificador, fecha_creacion
											)
											VALUES (
												$idEventoSanitario, $idEspecieAfectada,  '$nombreEspecieAfectada',
													'$especificacionEspecieAfectada', 
												'$identificador', now()
											) 
											RETURNING 
												id_especie_afectada_evento_sanitario;");
							
		return $res;
	}

	public function nuevaVacunacionAftosa(	$conexion, $idEventoSanitario,  $idTipoVacunacionAftosa,  $nombreTipoVacunacionAftosa,
												$fechaVacunacionAftosa, $loteVacunacionAftosa, $numeroCertificadoVacunacionAftosa,
												$nombreLaboratorioVacunacionAftosa, $identificador, $idEnfermedad, $enfermedad, $observaciones
													){

		$res = $conexion->ejecutarConsulta("INSERT INTO
											g_seguimiento_eventos_sanitarios.vacunacion_aftosa (
												id_evento_sanitario, id_tipo_vacunacion_aftosa, 
												nombre_tipo_vacunacion_aftosa, fecha_vacunacion_aftosa, 
												lote_vacunacion_aftosa, numero_certificado_vacunacion_aftosa, 
												nombre_laboratorio_vacunacion_aftosa,
												identificador, fecha_creacion, id_enfermedad, enfermedad, observaciones
											)
											VALUES (
												$idEventoSanitario,  $idTipoVacunacionAftosa,  '$nombreTipoVacunacionAftosa',
												'$fechaVacunacionAftosa', '$loteVacunacionAftosa', '$numeroCertificadoVacunacionAftosa',
												'$nombreLaboratorioVacunacionAftosa', 
												'$identificador', now(), $idEnfermedad, '$enfermedad', '$observaciones'
											) 
											RETURNING 
												id_vacunacion_aftosa;");
							
		return $res;
	}

	public function nuevaVacunacion(	$conexion,  $idEventoSanitario, $idTipoVacunacion, $tipoVacunacion,$numeroAnimalesVacunados, 
													$fechaVacunacion, $observacionVacunacion, $identificador
													){
												
		$res = $conexion->ejecutarConsulta("INSERT INTO
												g_seguimiento_eventos_sanitarios.vacunacion (
													id_evento_sanitario, id_tipo_vacunacion, 
													tipo_vacunacion, numero_animales_vacunados, fecha_vacunacion, 
													observacion_vacunacion,
												identificador, fecha_creacion 
													)
											VALUES (
													$idEventoSanitario, $idTipoVacunacion, '$tipoVacunacion',$numeroAnimalesVacunados, 
													'$fechaVacunacion', '$observacionVacunacion', 
												'$identificador', now()
											) 
											RETURNING 
												id_vacunacion;");
							
		return $res;
	}

	public function nuevaVacunacionAves(	$conexion, $idEventoSanitario, $numeroGalponesVacunacionAves, 
												$numeroLoteVacunacionAves, $idEnfermedadVacunacionAves, $enfermedadVacunacionAves, $edadVacunacionAves, 
												$diasVacunacionAves, $mesesVacunacionAves, $idTipoVacunacionAves,  $tipoVacunacionAves,
												$cepaVacunacionAves, $viaVacunacionAves, $fechaVacunacionAves, 
												$observacionVacunacionAves, $identificador
													){
														
		$res = $conexion->ejecutarConsulta("INSERT INTO
											g_seguimiento_eventos_sanitarios.vacunacion_aves (
												id_evento_sanitario, numero_galpones_vacunacion_aves, 
												numero_lote_vacunacion_aves, id_enfermedad_vacunacion_aves, enfermedad_vacunacion_aves, edad_vacunacion_aves, 
												dias_vacunacion_aves, meses_vacunacion_aves, id_tipo_vacunacion_aves,
												tipo_vacunacion_aves, cepa_vacunacion_aves, via_vacunacion_aves, 
												fecha_vacunacion_aves, observacion_vacunacion_aves,
												identificador, fecha_creacion
												)
											VALUES (
												$idEventoSanitario, $numeroGalponesVacunacionAves, 
												$numeroLoteVacunacionAves, $idEnfermedadVacunacionAves, '$enfermedadVacunacionAves', $edadVacunacionAves, 
												$diasVacunacionAves, $mesesVacunacionAves, $idTipoVacunacionAves,  '$tipoVacunacionAves',
												'$cepaVacunacionAves', '$viaVacunacionAves', '$fechaVacunacionAves', 
												'$observacionVacunacionAves', 
												'$identificador', now()
											) 
											RETURNING 
												id_vacunacion_aves;");
							
		return $res;
	}

	public function nuevaProcedimientosAves(	$conexion, $idEventoSanitario, $principioActivoAves,
													$dosisProcedimientoAves, $fechaInicioProcedimientoAves, $fechaFinProcedimientoAves, 
													$idFinalidadProcedimientoAves,$nombreFinalidadProcedimientoAves, $identificador
													){
		
		$res = $conexion->ejecutarConsulta("INSERT INTO
											g_seguimiento_eventos_sanitarios.procedimientos_aves (
												id_evento_sanitario,
												principio_activo_aves, 
												dosis_procedimiento_aves, fecha_inicio_procedimiento_aves, 
												fecha_fin_procedimiento_aves,  id_finalidad_procedimiento_aves,finalidad_procedimiento_aves,
												identificador, fecha_creacion
												)
											VALUES (
												$idEventoSanitario, '$principioActivoAves',
													'$dosisProcedimientoAves', '$fechaInicioProcedimientoAves', '$fechaFinProcedimientoAves', 
													$idFinalidadProcedimientoAves,'$nombreFinalidadProcedimientoAves', '$identificador', now()
											) 
											RETURNING 
												id_procedimientos_aves;");
							
		return $res;
	}
	
	
	public function nuevaMuestra(	$conexion, $idEventoSanitario, $numeroVisita, $colectaMaterial, $razonesMuestra,  
												$laboratorioMuestra, $nombreLaboratorioMuestra,
												$identificador, $anexo){

		$res = $conexion->ejecutarConsulta("INSERT INTO
											g_seguimiento_eventos_sanitarios.muestras (
												id_evento_sanitario, numero_visita, colecta_material,
												razones_muestra, laboratorio_muestra, nombre_laboratorio_muestra,
												identificador, fecha_creacion, anexo
												)
											VALUES (
												$idEventoSanitario, '$numeroVisita', '$colectaMaterial', '$razonesMuestra',  
												$laboratorioMuestra, '$nombreLaboratorioMuestra',
												'$identificador', now(), '$anexo'
											) 
											RETURNING 
												id_muestras;");
							
		return $res;
	}
	
	public function actualizarMuestra ($conexion, $idEventoSanitario, $numeroVisita, $colectaMaterial, $razonesMuestra, $pruebasMuestra,
			$laboratorioMuestra, $nombreLaboratorioMuestra,
			$identificador){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_seguimiento_eventos_sanitarios.muestras 
											SET
												colecta_material= '$colectaMaterial',
												--razones_muestra= '$razonesMuestra', 
												--pruebas_muestra= '$pruebasMuestra', 
												--laboratorio_muestra= $laboratorioMuestra, 
												--nombre_laboratorio_muestra= '$nombreLaboratorioMuestra',
												identificador= '$identificador', 
												fecha_creacion= now()
											WHERE
												id_evento_sanitario= $idEventoSanitario and
												numero_visita= '$numeroVisita';");
					
		return $res;
	}

	public function nuevaDetalleMuestras(	$conexion, $idMuestra, $idEventoSanitario, $idEspecieMuestra, $nombreEspecieMuestra,
													$pruebasMuestra, $nombrePruebasMuestra, $tipoMuestra, $nombreTipoMuestra, $numeroMuestras, 
													$fechaColectaMuestra, $horaColectaMuestra, $fechaEnvioMuestra, $horaEnvioMuestra, 
													$identificador, $numeroVisita){
												
														
		$res = $conexion->ejecutarConsulta("INSERT INTO	
											g_seguimiento_eventos_sanitarios.detalle_muestras (
												id_muestra,  id_evento_sanitario, id_especie_muestra,
												especie_muestra, id_prueba_muestra,  prueba_muestra, id_tipo_muestra,  tipo_muestra, numero_muestras, 
												fecha_colecta_muestra,  hora_colecta_muestra,  fecha_envio_muestra, 
												hora_envio_muestra,
												identificador, fecha_creacion, numero_visita
												)
											VALUES (
												$idMuestra, $idEventoSanitario, $idEspecieMuestra, '$nombreEspecieMuestra',
													$pruebasMuestra, '$nombrePruebasMuestra',
													$tipoMuestra, '$nombreTipoMuestra', $numeroMuestras, 
													'$fechaColectaMuestra', '$horaColectaMuestra', '$fechaEnvioMuestra', '$horaEnvioMuestra', 
												'$identificador', now(), '$numeroVisita'
											) 
											RETURNING 
												id_detalle_muestra;");
							
		return $res;
	}

	public function nuevaOrigenes(	$conexion,  $idEventoSanitario, $idOrigen, $nombreOrigen, $idPaisOrigen, 
										$nombrePaisOrigen, $idProvinciaOrigen, $nombreProvinciaOrigen, 
										$fechaOrigen, $identificador, $numVisita, $idCantonOrigen, $nombreCantonOrigen){
										
		$res = $conexion->ejecutarConsulta("INSERT INTO
											g_seguimiento_eventos_sanitarios.origenes (
												id_evento_sanitario, id_origen, 
												nombre_origen,  id_pais_origen,  nombre_pais, 
												id_provincia_origen,  nombre_provincia, fecha_origen,
												identificador, fecha_creacion, numero_visita, id_canton, canton
												)
											VALUES (
												$idEventoSanitario, $idOrigen, '$nombreOrigen', $idPaisOrigen, 
												'$nombrePaisOrigen', $idProvinciaOrigen, '$nombreProvinciaOrigen', '$fechaOrigen', 
												'$identificador', now(), '$numVisita', $idCantonOrigen, '$nombreCantonOrigen'
											) 
											RETURNING 
												id_origen_animales;");
							
		return $res;
	}

	public function nuevaPoblacionAnimal(	$conexion, $idEventoSanitario, $numeroVisita, $idEspeciePoblacion, $nombreEspeciePoblacion, 
												$idTipoEspeciePoblacion, $tipoEspeciePoblacion, $existentes, $enfermos, $muertos,  $sacrificados, 
												$totalSinVacunar, $enfermosSinVacunas, $identificador
													){
												
		$res = $conexion->ejecutarConsulta("INSERT INTO
											g_seguimiento_eventos_sanitarios.poblacion_animal (
												id_evento_sanitario, numero_visita,  id_especie_poblacion,  nombre_especie_poblacion, 
												id_tipo_especie_poblacion, tipo_especie_poblacion, existentes, 
												enfermos, muertos,  sacrificados,  
												total_sin_vacunar, enfermos_sin_vacunas, 
												identificador, fecha_creacion	
												)
											VALUES (
												$idEventoSanitario, '$numeroVisita', $idEspeciePoblacion, '$nombreEspeciePoblacion', 
												$idTipoEspeciePoblacion, '$tipoEspeciePoblacion', $existentes, $enfermos, $muertos,  $sacrificados, 
												$totalSinVacunar, $enfermosSinVacunas,
												'$identificador', now()
											) 
											RETURNING 
												id_poblacion_animales;");
							
		return $res;
	}

	public function nuevaPoblacionAves(	$conexion, $idEventoSanitario, $numeroVisita,
													$idEspeciePoblacionAves,  $nombreEspeciePoblacionAves, $numeroLotePoblacionAves, 
													$numeroGalponPoblacionAves, $edadPoblacionAves, $existentesPoblacionAves,														
													$enfermosPoblacionAves, $muertasPoblacionAves, $destruidasPoblacionAves, $sacrificadasPoblacionAves, $identificador
													){
		$res = $conexion->ejecutarConsulta("INSERT INTO
											g_seguimiento_eventos_sanitarios.poblacion_aves (
												id_evento_sanitario, numero_visita, 
												id_nombre_especie_poblacion_aves, nombre_especie_poblacion_aves,  
												numero_lote_poblacion_aves, numero_galpon_poblacion_aves,
												edad_poblacion_aves, existentes_poblacion_aves,														
												enfermos_poblacion_aves,  muertas_poblacion_aves,														
												destruidas_poblacion_aves,  sacrificadas_poblacion_aves,
												identificador, fecha_creacion
												)
											VALUES (
												$idEventoSanitario, '$numeroVisita',
													$idEspeciePoblacionAves,  '$nombreEspeciePoblacionAves', $numeroLotePoblacionAves, 
													$numeroGalponPoblacionAves, $edadPoblacionAves, $existentesPoblacionAves,														
													$enfermosPoblacionAves, $muertasPoblacionAves, $destruidasPoblacionAves, $sacrificadasPoblacionAves, 
												'$identificador', now()
											) 
											RETURNING 
												id_poblacion_aves;");
							
		return $res;
	}

	public function nuevaIngresosAnimales(	$conexion, 
													$idEventoSanitario, $numeroVisita, $idTipoMovimientoIngreso, $nombreTipoMovimientoIngreso, 
													$idProvincia, $nombreProvincia, $idCanton,  $nombreCanton, 
													$idParroquia, $nombreParroquia, $idEspecie,$nombreEspecie, $propietarioMovimiento, 
													$fincaMovimiento,  $fechaMovimiento, $identificador, $numeroAnimales
													){
												
		$res = $conexion->ejecutarConsulta("INSERT INTO
											g_seguimiento_eventos_sanitarios.ingresos_animales (
												id_evento_sanitario, id_tipo_movimiento_ingreso,
												nombre_tipo_movimiento_ingreso, id_provincia,  nombre_provincia, 
												id_canton,  nombre_canton,  id_parroquia,  nombre_parroquia, 
												id_especie,  nombre_especie,  propietario_movimiento, 
												finca_movimiento,  fecha_movimiento,
												identificador, fecha_creacion, numero_visita, numero_animales
												)
											VALUES (
												$idEventoSanitario, $idTipoMovimientoIngreso, '$nombreTipoMovimientoIngreso', 
													$idProvincia, '$nombreProvincia', $idCanton,  '$nombreCanton', 
													$idParroquia, '$nombreParroquia', $idEspecie,'$nombreEspecie', '$propietarioMovimiento', 
													'$fincaMovimiento',  '$fechaMovimiento', 
												'$identificador', now(), '$numeroVisita', $numeroAnimales
											) 
											RETURNING 
												id_ingreso;");
							
		return $res;
	}
	
	public function nuevaEgresosAnimales(	$conexion, 
													$idEventoSanitario, $numeroVisita, $idTipoMovimientoEgreso, $nombreTipoMovimientoEgreso,
													$idProvincia,  $nombreProvincia,  $idCanton, $nombreCanton, 
													$idParroquia,  $nombreParroquia,  $idEspecie, $nombreEspecie,  
													$propietarioMovimiento,  $fincaMovimiento,  $fechaMovimiento, $identificador,
													$numeroAnimales){
												
		$res = $conexion->ejecutarConsulta("INSERT INTO
											g_seguimiento_eventos_sanitarios.egresos_animales (
												id_evento_sanitario, id_tipo_movimiento_egreso,
												nombre_tipo_movimiento_egreso, id_provincia,  nombre_provincia, 
												id_canton,  nombre_canton,  id_parroquia,  nombre_parroquia, 
												id_especie,  nombre_especie,  propietario_movimiento,  finca_movimiento, 
												fecha_movimiento,
												identificador, fecha_creacion, numero_visita, numero_animales
												)
											VALUES (
												$idEventoSanitario, $idTipoMovimientoEgreso, '$nombreTipoMovimientoEgreso',
													$idProvincia,  '$nombreProvincia',  $idCanton, '$nombreCanton', 
													$idParroquia,  '$nombreParroquia',  $idEspecie, '$nombreEspecie',  
													'$propietarioMovimiento',  '$fincaMovimiento',  '$fechaMovimiento', 
												'$identificador', now(), '$numeroVisita', $numeroAnimales
											) 
											RETURNING 
												id_egreso;");
							
		return $res;
	}

	public function nuevaMovimientosAves(	$conexion, 
											$idEventoSanitario, $numeroVisita, $origenMovimientoAves, $paisProvincia, $fechaLlegada,  $huboMovimientoAves, 
											$tipoAves,  $idProvincia,  $provincia,  $idCanton, $canton,  $idParroquia,  
											$parroquia,  $idEspecieAves, $especieAves,  $numeroAvesMovilizadas,  $propietario, 
											$proveedor,  $finalidad,  $fecha, $identificador){
		
		$res = $conexion->ejecutarConsulta("INSERT INTO
										g_seguimiento_eventos_sanitarios.movimientos_aves (
											id_evento_sanitario, origen_movimiento_aves, pais_provincia, 
											fecha_llegada,  hubo_movimiento_aves, tipo_aves, 
											id_provincia,  provincia,  id_canton,  canton,  id_parroquia, 
											parroquia, id_especie_aves, nombre_especie_aves,  numero_aves_movilizadas, 
											propietario,  proveedor,  finalidad,  fecha,
												identificador, fecha_creacion, numero_visita
											)
											VALUES (
												$idEventoSanitario, '$origenMovimientoAves', '$paisProvincia', '$fechaLlegada',  '$huboMovimientoAves', 
											'$tipoAves',  $idProvincia,  '$provincia',  $idCanton, '$canton',  $idParroquia,  
											'$parroquia',  $idEspecieAves, '$especieAves',  $numeroAvesMovilizadas,  '$propietario', 
											'$proveedor',  '$finalidad',  '$fecha', 
												'$identificador', now(), '$numeroVisita'
											) 
											RETURNING 
												id_movimiento_animales_aves;");
							
		return $res;
	}

	public function nuevaCronologiasFinales(	$conexion, 
												$idEventoSanitario, $idTipoCronologiaFinal, $nombreTipoCronologiaFinal,  $fechaCronologiaFinal, $identificador){
												
		$res = $conexion->ejecutarConsulta("INSERT INTO
											g_seguimiento_eventos_sanitarios.cronologias_finales (
												id_evento_sanitario, id_tipo_cronologia_final, 
												nombre_tipo_cronologia_final,  fecha_cronologia_final,
												identificador, fecha_creacion
												)
											VALUES (
												$idEventoSanitario, $idTipoCronologiaFinal, '$nombreTipoCronologiaFinal',  '$fechaCronologiaFinal', 
												'$identificador', now()
											) 
											RETURNING 
												id_cronologia_final;");
							
		return $res;
	}

	public function nuevaDiagnosticos(	$conexion, 
										$idEventoSanitario, $idDiagnosticosFinal,  $nombreDiagnosticoFinal, $idEnfermedadFinal, $nombreEnfermedadFinal, $descricionDiagnosticoFinal, $identificador){
									
		$res = $conexion->ejecutarConsulta("INSERT INTO
											g_seguimiento_eventos_sanitarios.diagnosticos (
												id_evento_sanitario,
												id_diagnostico_final,  nombre_diagnostico_final, 
												descricion_diagnostico_final,
												identificador, fecha_creacion, id_enfermedad, enfermedad
												)
											VALUES (
												$idEventoSanitario, $idDiagnosticosFinal,  '$nombreDiagnosticoFinal', 
												'$descricionDiagnosticoFinal', 
												$identificador, now(), $idEnfermedadFinal, '$nombreEnfermedadFinal' 
											) 
											RETURNING 
												id_diagnosticos_final;");
							
		return $res;
	}

	public function nuevaPoblacionAnimalFinal(	$conexion, 
													$idEventoSanitario, $idEspeciePoblacionFinal,  $nombreEspeciePoblacionFinal,
													$idCategoriaPoblacionFinal,  $nombreCategoriaPoblacionFinal,
													$existentesPoblacionFinal,  $enfermosPoblacionFinal,  $muertosPoblacionFinal,  
													$sacrificadosPoblacionFinal,  $matadosEliminadosPoblacionFinal, $identificador
											){
												
		$res = $conexion->ejecutarConsulta("INSERT INTO
											g_seguimiento_eventos_sanitarios.poblacion_animal_final (
											id_evento_sanitario, id_especie_poblacion_final, 
											nombre_especie_poblacion_final,  id_categoria_poblacion_final, 
											nombre_categoria_poblacion_final,  existentes_poblacion_final, 
											enfermos_poblacion_final,  muertos_poblacion_final, 
											sacrificados_poblacion_final, matados_eliminados_poblacion_final,
												identificador, fecha_creacion
											)
											VALUES (
												$idEventoSanitario, $idEspeciePoblacionFinal,  '$nombreEspeciePoblacionFinal',
													$idCategoriaPoblacionFinal,  '$nombreCategoriaPoblacionFinal',
													$existentesPoblacionFinal,  $enfermosPoblacionFinal,  $muertosPoblacionFinal,  
													$sacrificadosPoblacionFinal,  $matadosEliminadosPoblacionFinal, 
												'$identificador', now()
											) 
											RETURNING 
												id_poblacion_final;");
							
		return $res;
	}

	public function nuevaPoblacionAvesFinal(	$conexion, 
													$idEventoSanitario, $idEspeciePoblacionFinalAves,  $nombreEspeciePoblacionFinalAves,
													$existentesPoblacionFinalAves, 
													$enfermosPoblacionFinalAves,  $muertosPoblacionFinalAves,  $destruidasPoblacionFinalAves, 
													$sacrificadosPoblacionFinalAves, $identificador
	){
												
		$res = $conexion->ejecutarConsulta("INSERT INTO
											g_seguimiento_eventos_sanitarios.poblacion_aves_final (
												id_evento_sanitario,
												id_especie_poblacion_final_aves,  nombre_especie_poblacion_final_aves, 
												existentes_poblacion_final_aves,  enfermos_poblacion_final_aves, 
												muertos_poblacion_final_aves,  destruidas_poblacion_final_aves, 
												sacrificados_poblacion_final_aves,
												identificador, fecha_creacion
												)
											VALUES (
												$idEventoSanitario, $idEspeciePoblacionFinalAves,  '$nombreEspeciePoblacionFinalAves',
													$existentesPoblacionFinalAves, 
													$enfermosPoblacionFinalAves,  $muertosPoblacionFinalAves,  $destruidasPoblacionFinalAves, 
													$sacrificadosPoblacionFinalAves, 
												'$identificador', now()
											) 
											RETURNING 
												id_poblacion_final_aves;");
							
		return $res;
	}

	public function nuevaVacunacionFinal(	$conexion, 
												$idEventoSanitario, $idTipoVacunacionFinal,  
												$nombreTipoVacunacionFinal, $dosisAplicadaVacunacionFinal, 
												$prediosVacunacionFinal,    
												$nombreLaboratoriosVacunacionFinal, $loteVacunacionFinal, $identificador
											){
											
		$res = $conexion->ejecutarConsulta("INSERT INTO
											g_seguimiento_eventos_sanitarios.vacunacion_final (
												id_evento_sanitario, id_tipo_vacunacion_final, 
												nombre_tipo_vacunacion_final,  dosis_aplicada_vacunacion_final, 
												predios_vacunacion_final,   
												nombre_laboratorios_vacunacion_final, lote_vacunacion_final,
												identificador, fecha_creacion
												)
											VALUES (
												$idEventoSanitario, $idTipoVacunacionFinal,  
												'$nombreTipoVacunacionFinal', '$dosisAplicadaVacunacionFinal', 
												'$prediosVacunacionFinal',    
												'$nombreLaboratoriosVacunacionFinal', '$loteVacunacionFinal', 
												'$identificador', now()
											) 
											RETURNING 
												id_vacunacion_final;");
							
		return $res;
	}
	
	public function nuevaPoblacionAnimalVisita(	$conexion, $idEventoSanitario, $idVisita, $nombreVisita, $idEspeciePoblacion, $nombreEspeciePoblacion, 
												$idTipoEspeciePoblacion, $tipoEspeciePoblacion, $existentes, $enfermos, $muertos,  $sacrificados, $matadosEliminados, $identificador
													){
												
		$res = $conexion->ejecutarConsulta("INSERT INTO
											g_seguimiento_eventos_sanitarios.poblacion_animal (
												id_evento_sanitario, id_visita, 
												nombre_visita,  id_especie_poblacion,  nombre_especie_poblacion, 
												id_tipo_especie_poblacion, tipo_especie_poblacion, existentes, 
												enfermos, muertos,  sacrificados, matados_eliminados,
												identificador, fecha_creacion	
												)
											VALUES (
												$idEventoSanitario, $idVisita, '$nombreVisita', $idEspeciePoblacion, '$nombreEspeciePoblacion', 
												$idTipoEspeciePoblacion, '$tipoEspeciePoblacion', $existentes, $enfermos, $muertos,  $sacrificados, $matadosEliminados, 
												'$identificador', now()
											) 
											RETURNING 
												id_poblacion_animales;");
							
		return $res;
	}

	public function nuevaMedidaSanitaria($conexion, $idEventoSanitario, $numeroVisita, $origenEnfermedad, $cuarentenaPredio, 
															$numeroActa,  $medidasSanitarias, $observaciones, $identificador){
			
		$res = $conexion->ejecutarConsulta("INSERT INTO
										g_seguimiento_eventos_sanitarios.medida_sanitaria (
											id_evento_sanitario, origen_enfermedad, cuarentena_predio, 
											numero_acta, medidas_sanitarias, observaciones, identificador,fecha_creacion, numero_visita	
										)
										VALUES (
											$idEventoSanitario, '$origenEnfermedad', '$cuarentenaPredio', 
											'$numeroActa',  '$medidasSanitarias', '$observaciones', '$identificador', now(), '$numeroVisita'
										) 
										RETURNING 
											id_medida_sanitaria;");
		return $res;
	}	
	
	public function abrirMedidaSanitaria($conexion, $idEventoSanitario, $numeroVisita){
					
		$res = $conexion->ejecutarConsulta("SELECT 
												* 
											FROM
												g_seguimiento_eventos_sanitarios.medida_sanitaria 
											WHERE 
												id_evento_sanitario=$idEventoSanitario and
												numero_visita='$numeroVisita';");
		
		return $res;
	}
	
	public function modificarMedidaSanitaria($conexion, $idEventoSanitario, $numeroVisita, $origenEnfermedad, $cuarentenaPredio,
			$numeroActa,  $medidasSanitarias, $observaciones, $identificador){
					
		$res = $conexion->ejecutarConsulta("UPDATE
												g_seguimiento_eventos_sanitarios.medida_sanitaria 
											SET
												origen_enfermedad= '$origenEnfermedad',
												cuarentena_predio= '$cuarentenaPredio',
												numero_acta= '$numeroActa', 
												medidas_sanitarias= '$medidasSanitarias', 
												observaciones= '$observaciones', 
												identificador= '$identificador',
												fecha_creacion= now()
											WHERE
												id_evento_sanitario= $idEventoSanitario and
												numero_visita= '$numeroVisita';");
		
		return $res;
	}
	
	public function nuevaMedidaSanitariaVisita($conexion, $idEventoSanitario, $cuarentenaPredio, $numeroVisitaMedidaSanitaria, $nombreVisitaMedidaSanitaria,
															$numeroActa,  $medidasSanitarias, $observaciones, $identificador){
			$res = $conexion->ejecutarConsulta("INSERT INTO
												g_seguimiento_eventos_sanitarios.medida_sanitaria (
													cuarentena_predio, numero_visita_medida_sanitaria, nombre_visita_medida_sanitaria,
													numero_acta, medidas_sanitarias, observaciones, identificador,fecha_creacion	
												)
												VALUES (
													$idEventoSanitario, '$cuarentenaPredio', $numeroVisitaMedidaSanitaria, '$nombreVisitaMedidaSanitaria',
													'$numeroActa',  '$medidasSanitarias', '$observaciones', '$identificador', now()
												) 
												RETURNING 
													id_medida_sanitaria;");
				return $res;
	}	
	
	
	//-------------------------------------------------  actualizaciones  ----------------------------------------------------------------------
	
	public function modificarEventoSanitarioExplotaAves ($conexion, $idEventoSanitario, $explotacionAves, $identificador){		
		
		$res = $conexion->ejecutarConsulta("UPDATE 
												g_seguimiento_eventos_sanitarios.evento_sanitario 
											SET 
												id_realiza_explotacion_aves = $explotacionAves,
												identificador_modificacion = '$identificador',
												fecha_modificacion = now()
											WHERE 
												id_evento_sanitario=$idEventoSanitario;");
											
		return $res;
	}								
	
	public function modificarEventoSanitarioVacunacionAves($conexion, $idEventoSanitario, $vacunacionAves, $identificador){
	
				$res = $conexion->ejecutarConsulta("UPDATE 
													g_seguimiento_eventos_sanitarios.evento_sanitario 
												SET 
													vacunacion_aves = $vacunacionAves,
													identificador_modificacion = '$identificador',
													fecha_modificacion = now(), 
													estado ='Pendiente' 
												WHERE 
													id_evento_sanitario=$idEventoSanitario;");
				return $res;
	}
	
	public function modificarEventoSanitarioFarmacosAves($conexion, $idEventoSanitario, $farmacoAves, $identificador){
	
				$res = $conexion->ejecutarConsulta("UPDATE 
													g_seguimiento_eventos_sanitarios.evento_sanitario 
												SET 
													farmaco_aves = $farmacoAves,
													identificador_modificacion = '$identificador',
													fecha_modificacion = now(), 
													estado ='Pendiente' 
												WHERE 
													id_evento_sanitario=$idEventoSanitario;");
				return $res;
	}
	
	public function modificarEventoSanitarioProcedimiento($conexion, $idEventoSanitario, $sintomatologia, $lecionesNecropsia, $especiePrimerAnimal, $nombreEspeciePrimerAnimal, 
															$edadPrimerAnimal, $ingresadoPrimerAnimal, $sindromePresuntivo, $identificador, $numInspeccion
															){
			
				$res = $conexion->ejecutarConsulta("UPDATE 
													g_seguimiento_eventos_sanitarios.evento_sanitario 
												SET 
													sintomatologia = '$sintomatologia', 
													leciones_necropsia = '$lecionesNecropsia', 
													id_especie_primer_animal = $especiePrimerAnimal, 
													nombre_especie_primer_animal = '$nombreEspeciePrimerAnimal', 
													edad_primer_animal = $edadPrimerAnimal, 
													ingresado_primer_animal = '$ingresadoPrimerAnimal', 
													sindrome_presuntivo = '$sindromePresuntivo',
													identificador_modificacion = '$identificador',
													fecha_modificacion = now(), 
													estado ='primeraVisita',
													num_inspeccion = '$numInspeccion'
												WHERE 
													id_evento_sanitario=$idEventoSanitario;");
				return $res;
	}														
	
	public function modificarEventoSanitarioPrediosAfectados($conexion, $idEventoSanitario, $prediosAfectados, $cuantosPrediosAfectados, $identificador){
		
		$res = $conexion->ejecutarConsulta("UPDATE 
													g_seguimiento_eventos_sanitarios.evento_sanitario 
												SET 
													otros_predios_afectados = '$prediosAfectados', 
													numero_predios_afectados = $cuantosPrediosAfectados,
													identificador_modificacion = '$identificador',
													fecha_modificacion = now()
												WHERE 
													id_evento_sanitario=$idEventoSanitario;");
		return $res;
	}			
	
	public function modificarEventoSanitarioMovimientosAnimales($conexion, $idEventoSanitario, $movimientoAnimal, $enfermaronMovimientoAnimal, $identificador){
			$res = $conexion->ejecutarConsulta("UPDATE 
													g_seguimiento_eventos_sanitarios.evento_sanitario 
												SET 
													movimiento_animal = '$movimientoAnimal', 
													--enfermaron_movimiento_animal = '$enfermaronMovimientoAnimal',
													identificador_modificacion = '$identificador',
													fecha_modificacion = now()
												WHERE 
													id_evento_sanitario=$idEventoSanitario;");
				return $res;
	}		
	
	public function modificarEventoSanitarioVacunacionFinal($conexion, $idEventoSanitario, $vacunacionFinal, $identificador){	
		
		$res = $conexion->ejecutarConsulta("UPDATE 
													g_seguimiento_eventos_sanitarios.evento_sanitario 
												SET 
													vacunacion_final = '$vacunacionFinal', 
													identificador_modificacion = '$identificador',
													fecha_modificacion = now()
												WHERE 
													id_evento_sanitario=$idEventoSanitario;");
				return $res;
	}		
	
	public function modificarEventoSanitarioConclusionFinal($conexion, $idEventoSanitario, $conclusionFinal, $identificador){
			
		$res = $conexion->ejecutarConsulta("UPDATE 
													g_seguimiento_eventos_sanitarios.evento_sanitario 
												SET 
													conclusion_final = '$conclusionFinal', 
													identificador_cierre = '$identificador',
													fecha_cierre = now(),
													estado ='cerrado'
												WHERE 
													id_evento_sanitario=$idEventoSanitario;");
		return $res;
	}		
	
	// generacion de serial
	
	public function generarNumeroEventoSanitario($conexion, $codigo){
		
		$res = $conexion->ejecutarConsulta("SELECT 
												MAX(numero_formulario) as num_solicitud 
											FROM 
												g_seguimiento_eventos_sanitarios.evento_sanitario 
											WHERE 
												numero_formulario LIKE '%$codigo%';");
		return $res;
	}
	
	//Laboratorios Registrados
	public function abrirCatalogoLaboratorios ($conexion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_seguimiento_eventos_sanitarios.laboratorios
											WHERE
												estado = 'activo';");
	
		return $res;
	}
	
	
	//Cambios de estado
	public function cierreVisitaTecnico($conexion, $idEventoSanitario, $identificador,
			$observaciones, $estado, $numeroVisita,
			$idLaboratorio, $laboratorio, $movimientoAnimal){
	
				if($estado == 'tomaMuestras'){
					$res = $conexion->ejecutarConsulta("UPDATE
							g_seguimiento_eventos_sanitarios.evento_sanitario
							SET
							identificador_modificacion='$identificador',
							fecha_modificacion=now(),
							observaciones='$observaciones',
							estado='$estado',
							id_laboratorio = $idLaboratorio,
							laboratorio = '$laboratorio',
                                                        movimiento_animal = $movimientoAnimal
							WHERE
							id_evento_sanitario=$idEventoSanitario;");
				}else{
					$res = $conexion->ejecutarConsulta("UPDATE
							g_seguimiento_eventos_sanitarios.evento_sanitario
							SET
							identificador_modificacion='$identificador',
							fecha_modificacion=now(),
							observaciones='$observaciones',
							estado='$estado',
							num_inspeccion = '$numeroVisita',
                                                        movimiento_animal = $movimientoAnimal
							WHERE
							id_evento_sanitario=$idEventoSanitario;");
				}	
					
				return $res;
	}
	
	public function generarNumeroVisitaEventoSanitario($conexion, $codigo, $numSolicitud){
	
		$res = $conexion->ejecutarConsulta("SELECT
				MAX(num_inspeccion) as num_inspeccion
				FROM
				g_seguimiento_eventos_sanitarios.evento_sanitario
				WHERE
				num_inspeccion LIKE '%$codigo%' and
				numero_formulario = '$numSolicitud';");
	
		return $res;
	}
	
	/******** RESULTADOS DE LABORATORIO EVENTO SANITARIO ********/
	//Archivo listaEventoSanitarioLaboratorios
	public function buscarLaboratorioUsuario($conexion, $identificador){
	
		$res = $conexion->ejecutarConsulta("SELECT
				*
				FROM
				g_seguimiento_eventos_sanitarios.usuarios_laboratorio
				WHERE
				identificador = '$identificador';");
	
	
		return $res;
	}
	
	//Archivo abrirEventoSanitarioLaboratorios
	public function generarNumeroMuestraEventoSanitario($conexion, $codigo){

		$res = $conexion->ejecutarConsulta("SELECT
												MAX(num_muestra) as num_muestra
											FROM
												g_seguimiento_eventos_sanitarios.resultado_laboratorio
											WHERE
												num_solicitud LIKE '%$codigo%';");
	
	
		return $res;
	}
	
	//Archivo guardarPruebaLaboratorio
	public function nuevoResultadoLaboratorio($conexion, $idEventoSanitario, $identificador,
			$numeroSolicitud, $numeroMuestra, $resultadoAnalisisLaboratorio, $informe,
			$observaciones, $numInspeccion){
	
				$res = $conexion->ejecutarConsulta("INSERT INTO
						g_seguimiento_eventos_sanitarios.resultado_laboratorio(
						id_evento_sanitario, identificador, fecha_creacion,
						num_solicitud, num_muestra, resultado_analisis,
						archivo_informe, observaciones, num_inspeccion)
						VALUES ($idEventoSanitario, '$identificador', now(),
						'$numeroSolicitud', '$numeroMuestra', '$resultadoAnalisisLaboratorio',
						'$informe', '$observaciones', '$numInspeccion')
						RETURNING id_resultado_laboratorio;");
	
				return $res;
	}
	
	public function nuevoDetalleResultadoLaboratorio($conexion, $idResultadoLaboratorio,
			$idEventoSanitario, $identificador,
			$muestra, $fechaMuestra, $idEnfermedad, $enfermedad,
			$cantidadMuestras, $numPositivos, $numNegativos,
			$numIndeterminados, $numReactivos, $numSospechosos,
			$idPruebaLaboratorio, $pruebaLaboratorio,
			$resultadoLaboratorio, $observacionesMuestra, $numInspeccion){
	
				$res = $conexion->ejecutarConsulta("INSERT INTO
						g_seguimiento_eventos_sanitarios.resultado_laboratorio_detalle(
						id_resultado_laboratorio, id_evento_sanitario, identificador,
						fecha_creacion, muestra, fecha_muestra, id_enfermedad, enfermedad,
						cantidad_muestras, num_positivos, num_negativos,
						num_indeterminados, num_reactivos, num_sospechosos,
						id_prueba_laboratorio, prueba_laboratorio, resultado,
						observaciones_muestra, num_inspeccion)
						VALUES ($idResultadoLaboratorio, $idEventoSanitario, '$identificador',
						now(), '$muestra', '$fechaMuestra', $idEnfermedad, '$enfermedad',
						$cantidadMuestras, $numPositivos, $numNegativos,
						$numIndeterminados, $numReactivos, $numSospechosos,
						$idPruebaLaboratorio, '$pruebaLaboratorio', '$resultadoLaboratorio',
						'$observacionesMuestra', '$numInspeccion');");
	
						return $res;
	}
	
	public function cambiarEstadoEventoSanitario($conexion, $idEventoSanitario, $identificador, $estado, $numInspeccion){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_seguimiento_eventos_sanitarios.evento_sanitario
											SET
												estado='$estado',
												identificador_modificacion='$identificador',
												fecha_modificacion=now(),
												num_inspeccion = '$numInspeccion'
											WHERE
												id_evento_sanitario=$idEventoSanitario;");
	
		return $res;
	}
	
	
	
	//Archivo
	public function buscarPruebaLaboratorio($conexion, $idEventoSanitario, $muestra, $idPruebaLaboratorio){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_seguimiento_eventos_sanitarios.resultado_laboratorio
											WHERE
												id_evento_sanitario = $idEventoSanitario and
												muestra = '$muestra' and
												id_prueba_laboratorio = $idPruebaLaboratorio;");
	
		return $res;
	}
	
	
	//Archivo abrirCertificacionBTPC
	public function abrirResultadoLaboratorio ($conexion, $idEventoSanitario){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_seguimiento_eventos_sanitarios.resultado_laboratorio
											WHERE
												id_evento_sanitario = $idEventoSanitario;");
	
		return $res;
	}
	
	public function abrirResultadoLaboratorioVisita ($conexion, $idEventoSanitario, $numVisita){
	
		$res = $conexion->ejecutarConsulta("SELECT
				*
				FROM
				g_seguimiento_eventos_sanitarios.resultado_laboratorio
				WHERE
				id_evento_sanitario = $idEventoSanitario and
				num_inspeccion = '$numVisita';");
	
		return $res;
	}
	
	public function abrirResultadoLaboratorioDetalle ($conexion, $idEventoSanitario, $idResultadoLaboratorio){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_seguimiento_eventos_sanitarios.resultado_laboratorio_detalle
											WHERE
												id_evento_sanitario = $idEventoSanitario and
												id_resultado_laboratorio = $idResultadoLaboratorio;");
									
		return $res;
	}
}
?>