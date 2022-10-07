<?php

include_once('tbszip.php');

class ControladorCapacitacion{
		
	public function guardarFuncionariosReplicados($conexion, $idRequerimiento, $replicador,$replicado){
		
		$sqlScript="INSERT INTO g_capacitacion.funcionarios_replicados
						(identificador_replicante, identificador_replicado,	fecha_modificacion,	id_requerimiento)
					VALUES ('$replicador', '$replicado', 'now()', '$idRequerimiento') RETURNING id_funcionarios_replicados;";
		
		$res = $conexion->ejecutarConsulta($sqlScript);
		
		return $res;
			
	}
	
	public function bloqueoAsistentes($conexion,$idRequerimiento,$estado){
		
		$sqlScript="UPDATE 
						g_capacitacion.participantes
					SET
						bloqueo='$estado'
					WHERE 
						id_requerimiento='$idRequerimiento';";
		
		$res = $conexion->ejecutarConsulta($sqlScript);
		
		return $res;	
	}
	
	public function actualizarArchivoNoReplica($conexion,$idRequerimiento,$archivo){
	
		$sqlScript="UPDATE
						g_capacitacion.participantes
					SET
						archivo_firmado='$archivo'
					WHERE
						id_requerimiento='$idRequerimiento';";
	
		$res = $conexion->ejecutarConsulta($sqlScript);
	
		return $res;
	}
	
	public function actualizarPresupuesto($conexion,$idRequerimiento,$presupuestoIndividual){
		
		$sqlScript="UPDATE 
						g_capacitacion.participantes
					SET
						presupuesto_individual='$presupuestoIndividual'
					WHERE
						id_requerimiento='$idRequerimiento';";
		
		$res = $conexion->ejecutarConsulta($sqlScript);
	
		return $res;
	}
	
	public function actualizarEstadoRequerimiento($conexion,$idRequerimiento,$estado,$observacion=null){
	
		if($observacion!=null){
			$observacion=" observacion='$observacion' ,";
		}
	
		$sqlScript="UPDATE
						g_capacitacion.requerimiento 
					SET
						".$observacion."
					 	estado_requerimiento='$estado',  fecha_modificacion=now() 
					WHERE
					 	id_requerimiento='$idRequerimiento';";

	$res = $conexion->ejecutarConsulta($sqlScript);

	return $res;
	}
	
	public function actualizarAprobacionTH($conexion,$idRequerimiento,$estado,$capacitacionProgramada,$observacionTH){

		$sqlScript="UPDATE
						g_capacitacion.requerimiento
					SET
						 estado_requerimiento='$estado', capacitacion_programada='$capacitacionProgramada', observacion_talento_humano='$observacionTH' ,fecha_modificacion=now()
					 WHERE
						 id_requerimiento='$idRequerimiento';";
	
		$res = $conexion->ejecutarConsulta($sqlScript);
	
		return $res;
	}
	
	public function actualizarEstadoElaborarInforme($conexion,$idRequerimiento,$estado,$rutaDocumento,$objetivoCurso,$justificacionTH){
		
		$sqlScript="UPDATE 
						g_capacitacion.requerimiento
					SET 
						estado_requerimiento='$estado', ruta_informe='$rutaDocumento', objetivo_curso='$objetivoCurso', justificacion_th='$justificacionTH' ,fecha_modificacion=now() 
					WHERE 
						id_requerimiento='$idRequerimiento';";
	
		$res = $conexion->ejecutarConsulta($sqlScript);
	
		return $res;
	}
	
	public function actualizarAprobacionFinanciero($conexion,$idRequerimiento,$estado,$numeroCertificacion,$nombrePartida,$fechaPartida,$rutaArchivo){
		
		$sqlScript="UPDATE 
						g_capacitacion.requerimiento
					SET 
						estado_requerimiento='$estado', numero_certificacion='$numeroCertificacion', nombre_certificacion='$nombrePartida', fecha_partida='$fechaPartida', archivo='$rutaArchivo', fecha_modificacion=now() 
					WHERE 
						id_requerimiento='$idRequerimiento';";
	
		$res = $conexion->ejecutarConsulta($sqlScript);
	
		return $res;
	}
	
	public function actualizarinformeGenerado($conexion, $idRequerimiento,$idFuncionario, $rutaArchivo){
		
		$sqlScript="UPDATE 
						g_capacitacion.participantes
					SET
						archivo_firmado='$rutaArchivo'
					WHERE 
						id_requerimiento='$idRequerimiento'
						and identificador='$idFuncionario';";
		$res = $conexion->ejecutarConsulta($sqlScript);
	   
		return $res;
	
	}
	
	
	public function actualizarDocumentoyConocimientos($conexion, $idRequerimiento,$idFuncionario, $conocimientosTransmitidos,$rutaArchivo){
		
		$sqlScript="UPDATE 
						g_capacitacion.participantes
					SET
						conocimientos_transmitidos='$conocimientosTransmitidos',
						archivo_generado='$rutaArchivo'
					WHERE
						id_requerimiento='$idRequerimiento'
						and identificador='$idFuncionario';";
		
		$res = $conexion->ejecutarConsulta($sqlScript);
	
		return $res;
	
	}
	
	public function actualizarEstadoReplicacion($conexion,$idFuncionariosReplicados, $estado){
		
		$sqlScript="UPDATE 
						g_capacitacion.funcionarios_replicados
					SET
						estado='$estado',
						fecha_modificacion=now()
					WHERE 
						id_funcionarios_replicados = '$idFuncionariosReplicados';";
		
		$res = $conexion->ejecutarConsulta($sqlScript);
	
		return $res;
	
	}
	
	public function obtenerRequerimientos ($conexion,$tituloCapacitacion,$fechaInicio,$fechaFin,$idRequerimiento,$identificador,$idDirectorA,$estadoInicio,$estadoFin,$idDirectorB=null){

		$tituloCapacitacion = $tituloCapacitacion!="" ? "'%" . $tituloCapacitacion . "%'" : "null";
		$fechaInicio = $fechaInicio!="" ? "'" . $fechaInicio . "'" : "null";
		$fechaFin= $fechaFin!="" ? "'" . $fechaFin . "'" : "null";
		$idRequerimiento = $idRequerimiento!="" ? "'" . $idRequerimiento . "'" : "null";
		$identificador = $identificador!="" ? "'" . $identificador . "'" : "null";
		$idDirectorA = $idDirectorA!="" ? "'" . $idDirectorA . "'" : "null";
		$idDirectorB = $idDirectorB!="" ? "'" . $idDirectorB . "'" : "null";
		$estadoInicio = $estadoInicio!="" ? "'" . $estadoInicio . "'" : "null";
		$estadoFin = $estadoFin!="" ? "'" . $estadoFin . "'" : "null";
	
		
		$sqlScript="SELECT 
						*
					FROM
						g_capacitacion.mostrar_requerimientos(".$tituloCapacitacion.",".$fechaInicio.",".$fechaFin.",".$idRequerimiento.",".$identificador.",".$idDirectorA.",".$estadoInicio.",".$estadoFin.",".$idDirectorB.")
				 	ORDER BY 
						fecha_modificacion;";
			
	
		$res = $conexion->ejecutarConsulta($sqlScript);
		
		return $res;
	}
	
	public function obtenerAreaUsuario($conexion, $identificador) {
	
		$res = $conexion->ejecutarConsulta("SELECT
												a.id_area,
												a.nombre,
												a.id_area_padre
											FROM
												g_estructura.funcionarios f,
												g_estructura.area a
											WHERE
												f.id_area = a.id_area and
												f.identificador = '$identificador';");
	
		return $res;
	
	}
	
	public function obtenerRequerimientosUsuario ($conexion,$tituloCapacitacion,$fechaInicio,$fechaFin,$identificador,$estadoInicio,$estadoFin,$codigo_area,$idRequerimiento){
	
		$tituloCapacitacion = $tituloCapacitacion!="" ? "'%" . $tituloCapacitacion . "%'" : "null";
		$fechaInicio = $fechaInicio!="" ? "'" . $fechaInicio . "'" : "null";
		$fechaFin= $fechaFin!="" ? "'" . $fechaFin . "'" : "null";
		$identificador = $identificador!="" ? "'" . $identificador . "'" : "null";
		$estadoInicio = $estadoInicio!="" ? "'" . $estadoInicio . "'" : "null";
		$estadoFin = $estadoFin!="" ? "'" . $estadoFin . "'" : "null";
		$idRequerimiento = $idRequerimiento!="" ? "'" . $idRequerimiento . "'" : "null";
		$codigo_area="'".$codigo_area."'";
		
		$sqlScript="SELECT 
						*
					FROM
						g_capacitacion.requerimientos_asistentes(".$tituloCapacitacion.",".$fechaInicio.",".$fechaFin.",".$idRequerimiento.",".$identificador.",".$estadoInicio.",".$estadoFin.",".$codigo_area.")
				 	ORDER BY
						fecha_modificacion;";
		
		
		$res = $conexion->ejecutarConsulta($sqlScript);
		return $res;
	}
	
	public function listarReplicacionUsuario ($conexion,$tituloCapacitacion,$idRequerimiento,$nombreReplicador,$identificador,$estadoInicio,$estadoFin){
		$tituloCapacitacion = $tituloCapacitacion!="" ? "'%" . $tituloCapacitacion . "%'" : "null";
		$nombreReplicador = $nombreReplicador!="" ? "'%" . $nombreReplicador . "%'" : "null";
		$identificador = $identificador!="" ? "'" . $identificador . "'" : "null";
		$idRequerimiento = $idRequerimiento!="" ? "'" . $idRequerimiento . "'" : "null";
		$estadoInicio = $estadoInicio!="" ? "'" . $estadoInicio . "'" : "null";
		$estadoFin = $estadoFin!="" ? "'" . $estadoFin . "'" : "null";
		
		$sqlScript="SELECT
						*
					FROM
						g_capacitacion.requerimientos_replicados(".$tituloCapacitacion.",".$nombreReplicador.",".$identificador.",".$idRequerimiento.",".$estadoInicio.",".$estadoFin.")";
	    
		$res = $conexion->ejecutarConsulta($sqlScript);
		return $res;
	}
	
	public function listarCalificacion($conexion,$idFuncionariosReplicados){
		
		$sqlScript="SELECT 
						*
					FROM 
						g_capacitacion.calificacion_replicador 
					WHERE 
						id_funcionarios_replicados=".$idFuncionariosReplicados.";";
		
		$res = $conexion->ejecutarConsulta($sqlScript);
		
		return $res;
		
	}
	
	public function insertarCalificacionReplicador($conexion,$idFuncionariosReplicados,$conocimiento_tema,$respuesta_inquietudes,$manejo_grupo,$cumplimiento_agenda,
													$conocimientos_relacionados,$aplicara_institucion,$asesoriaInterna){
		
		$sqlScript="INSERT INTO g_capacitacion.calificacion_replicador
						(id_funcionarios_replicados, conocimiento_tema, respuesta_inquietudes, manejo_grupo, cumplimiento_agenda_programada, conocimientos_relacionados_funcion_desempeniada,
						conocimientos_aplicados_gestion_institucion, conocimientos_utiles_asesorar_internamente, fecha_calificacion)
					VALUES ('$idFuncionariosReplicados', '$conocimiento_tema', '$respuesta_inquietudes', '$manejo_grupo', '$cumplimiento_agenda', '$conocimientos_relacionados',
								'$aplicara_institucion', '$asesoriaInterna', 'now()');";
		
		$res = $conexion->ejecutarConsulta($sqlScript);
								
		return $res;
		
	}
	
	public function obtenerFuncionarios($conexion,$idRequerimiento){
		$sqlScript="SELECT 
						pa.id_participantes,
						pa.identificador,
						pa.id_requerimiento,
						fe.nombre,
						fe.apellido, 
						pa.archivo_firmado
					FROM 
						g_capacitacion.participantes as pa LEFT JOIN g_uath.ficha_empleado as fe
						ON (pa.identificador=fe.identificador )
					WHERE 
						pa.id_requerimiento='".$idRequerimiento."';";
	
		$res = $conexion->ejecutarConsulta($sqlScript);
		
		return $res;
	}
	
	public function obtenerFuncionariosReplicados($conexion,$idRequerimiento,$idFuncionario){
		
		$sqlScript="SELECT 
						fr.identificador_replicante,
					    fr.id_requerimiento,
						fr.identificador_replicado,
						fe.nombre,
						fe.apellido,
						fr.id_funcionarios_replicados,
						(cr.conocimiento_tema+
						cr.respuesta_inquietudes+
						cr.manejo_grupo+
						cr.cumplimiento_agenda_programada) as calificacion
					FROM 
						g_capacitacion.funcionarios_replicados fr inner join
						g_uath.ficha_empleado as fe ON 	
						fe.identificador=fr.identificador_replicado	 left join
						g_capacitacion.calificacion_replicador cr ON
						fr.id_funcionarios_replicados=cr.id_funcionarios_replicados	
					WHERE 
						fr.id_requerimiento='".$idRequerimiento."' and
						fr.identificador_replicante='".$idFuncionario."';";
		
		$res = $conexion->ejecutarConsulta($sqlScript);
	
		return $res;
	}
	
	public function nuevoRequerimiento ($conexion,$tipoEvento,$tipoCertificado,$nombreEvento,$empresaCapacitadora,$fechaInicio,$fechaFin,
										$eventoPagado,$costoUnitaria,$horas,$localizacion,$pais,$provincia,$canton,$ciudad,$justificacion,$identificador,$capacitacionInterna){
		
		$fecha_inicio = $fecha_inicio!="" ? "'" . $fecha_inicio . "'" : "null";
		$fecha_fin= $fecha_fin!="" ? "'" . $fecha_fin . "'" : "null";
		
		$sqlScript="INSERT into g_capacitacion.requerimiento (tipo_evento, tipo_certificado, nombre_evento, empresa_capacitadora, fecha_inicio, fecha_fin, evento_pagado,
															costo_unitario, horas, localizacion, pais, provincia, canton, ciudad, justificacion, fecha_modificacion,
															identificador, estado_requerimiento, capacitacion_interna)	
					VALUES('$tipoEvento', '$tipoCertificado', '$nombreEvento', '$empresaCapacitadora', '$fechaInicio', '$fechaFin', '$eventoPagado', '$costoUnitaria',
							'$horas', '$localizacion', '$pais', '$provincia', '$canton', '$ciudad', '$justificacion', 'now()', '$identificador', '6', '$capacitacionInterna') 
					RETURNING id_requerimiento;";
		
		$res = $conexion->ejecutarConsulta($sqlScript);
		
		return $res;
	}
	
	public function actualizarInformeRequerimiento($conexion,$idRequerimiento,$rutaDocumento,$estado,$objetivoCurso,$justificacionTH){
		$fecha_partida= $fecha_partida!="" ? "'" . $fecha_partida . "'" : "null";
		
		$sqlScript="UPDATE 
						g_capacitacion.requerimiento
					SET
						ruta_informe='$rutaDocumento',
						estado_requerimiento='$estado',
						objetivo_curso='$objetivoCurso',
						justificacion_th='$justificacionTH',
						fecha_modificacion=now()
					WHERE 
						id_requerimiento='$idRequerimiento';";
		$res = $conexion->ejecutarConsulta($sqlScript);
		
		return $res;
		
	}
	
	public function actualizarRequerimiento($conexion,$idRequerimiento,$tipoEnvento,$tipoCertificado,$nombreEvento,$empresaCapacitadora,$fechaInicio,$fechaFin,
					$eventoPagado,$costoUnitario,$horas,$localizacion,$pais,$provincia,$canton,$ciudad,$justificacion,$estado,$observacion,$observacionTH,
					$numero_certificacion,$archivo,$nombre_certificacion,$fecha_partida,$programada,$capacitacionInterna){
		
		$fecha_partida= $fecha_partida!="" ? "'" . $fecha_partida . "'" : "null";
		
		$sqlScript="UPDATE 
						g_capacitacion.requerimiento
					SET 
						tipo_evento='$tipoEnvento', tipo_certificado='$tipoCertificado', nombre_evento='$nombreEvento', empresa_capacitadora='$empresaCapacitadora',
						fecha_inicio='$fechaInicio', fecha_fin='$fechaFin', evento_pagado='$eventoPagado', costo_unitario='$costoUnitario', horas='$horas',
						localizacion='$localizacion', pais='$pais', provincia='$provincia', canton='$canton', ciudad='$ciudad', justificacion='$justificacion',
						estado_requerimiento='$estado', observacion='$observacion', observacion_talento_humano='$observacionTH', numero_certificacion='$numero_certificacion',
						nombre_certificacion='$nombre_certificacion', fecha_partida=$fecha_partida, capacitacion_programada='$programada', capacitacion_interna='$capacitacionInterna',
						archivo='$archivo', fecha_modificacion=now() 
					WHERE
						id_requerimiento='$idRequerimiento';";
		
		$res = $conexion->ejecutarConsulta($sqlScript);
		
		return $res;
	}
	
	public function guardarParticipantesEvento($conexion, $idRequerimiento, $idFuncionario,$bloqueo){
		$sqlScript="INSERT INTO g_capacitacion.participantes(id_requerimiento,identificador,bloqueo)
				    VALUES ($idRequerimiento,'$idFuncionario','$bloqueo') 
					RETURNING id_participantes;";
		
		$res = $conexion->ejecutarConsulta($sqlScript);
		
		return $res;
	}
	
	public function eliminarParticipantes($conexion, $idRequerimiento){
		
		$sqlScript="DELETE FROM
						g_capacitacion.participantes
					WHERE
						id_requerimiento='$idRequerimiento'";
		
		$res = $conexion->ejecutarConsulta($sqlScript);
		
		return $res;
	}
	
	public function eliminarParticipantesXid($conexion, $idParticipante){
	
		$sqlScript="DELETE FROM
						g_capacitacion.participantes
					WHERE
						id_participantes='$idParticipante'";
				
		$res = $conexion->ejecutarConsulta($sqlScript);
	
		return $res;
	}
	
	public function eliminarReplicadoXid($conexion, $idFuncionarioReplicado){
	
		$sqlScript="DELETE FROM
						g_capacitacion.funcionarios_replicados
					WHERE
						id_funcionarios_replicados='$idFuncionarioReplicado'";
	
		$res = $conexion->ejecutarConsulta($sqlScript);
	
		return $res;
	}
	
	function rtf($plantilla, $nombre_archivo, $valores, $directorioPlantilla='plantillas/', $retorno='descarga',$directorio_salida='generados/') {
		
		$zip = new clsTbsZip();
		$zip->Open($directorioPlantilla . $plantilla . '.docx');
			
		$contenido = $zip->FileRead('word/document.xml');
		$encabezado = $zip->FileRead('word/header1.xml');
		$pie = $zip->FileRead('word/footer1.xml');
			
		$contenido = $this->reemplazo($contenido, $valores);
		$encabezado = $this->reemplazo($encabezado, $valores);
		$pie = $this->reemplazo($pie, $valores);
			
		$zip->FileReplace('word/document.xml', $contenido, TBSZIP_STRING);
		$zip->FileReplace('word/header1.xml', $encabezado, TBSZIP_STRING);
		$zip->FileReplace('word/footer1.xml', $pie, TBSZIP_STRING);
			
		$zip->Flush(TBSZIP_FILE, $directorio_salida.$nombre_archivo . '.docx');
			
		
	}
	
	function reemplazo($seccion, $arreglo){
		foreach ($arreglo as $k => $v) {
			while(strpos($seccion, $k)==true){
			$posicionDePalabra = strpos($seccion, $k);
			if ($posicionDePalabra != null){
				$seccion = substr_replace($seccion, $v, $posicionDePalabra, strlen($k));
			}
			}
		}
		return $seccion;
	}
	
	public function eliminarRequerimiento($conexion, $idRequerimiento){
	
		$sqlScriptP="DELETE FROM 
						g_capacitacion.participantes 
					 WHERE 
						 id_requerimiento='$idRequerimiento';";
		
		$res = $conexion->ejecutarConsulta($sqlScriptP);
		
		$sqlScript="DELETE FROM 
						g_capacitacion.requerimiento
					WHERE 
						id_requerimiento='$idRequerimiento';";
	
		$res = $conexion->ejecutarConsulta($sqlScript);
	}
	
	public function buscarRequerimiento($conexion, $idRequerimiento){
	
		$sqlScript="SELECT 
						id_requerimiento
					 FROM 
						g_capacitacion.requerimiento
					 WHERE 
						id_requerimiento='$idRequerimiento' and 
						(estado_requerimiento='0' or estado_requerimiento='6'); ";
	
		$res = $conexion->ejecutarConsulta($sqlScript);
	
		return $res;
	}
	
	public function actualizarRevisorRequerimiento($conexion, $idArea, $identificadorRevisor, $idRequerimiento, $tipo){
		
		if($tipo == 'identificadorDistritoA'){
			$sqlScript="UPDATE
							g_capacitacion.requerimiento
						SET
							identificador_distrital_a = '$identificadorRevisor',
							id_area_distrital_a = '$idArea'
						WHERE
							id_requerimiento = $idRequerimiento";
		}else{
			
			$sqlScript="UPDATE
							g_capacitacion.requerimiento
						SET
							identificador_distrital_b = '$identificadorRevisor',
							id_area_distrital_b = '$idArea'
						WHERE
							id_requerimiento = $idRequerimiento";	
		}

		$res = $conexion->ejecutarConsulta($sqlScript);
			
		return $res;
	}
	
	public function obtenerRequerimientosRevisionProceso($conexion,$tituloCapacitacion,$fechaDesde,$fechaHasta,$estadoInicio,$estadoFin,$idArea){
	
		$tituloCapacitacion = $tituloCapacitacion!="" ? "'%" . $tituloCapacitacion . "%'" : "null";
		$fechaDesde = $fechaDesde!="" ? "'" . $fechaDesde . "'" : "null";
		$fechaHasta= $fechaHasta!="" ? "'" . $fechaHasta . "'" : "null";
		$estadoInicio = $estadoInicio!="" ? "'" . $estadoInicio . "'" : "null";
		$estadoFin = $estadoFin!="" ? "'" . $estadoFin . "'" : "null";
		$idArea="'".$idArea."'";

		$sqlScript="SELECT 
						*
					FROM
						g_capacitacion.mostrar_requerimientos_revision_proceso(".$tituloCapacitacion.",".$fechaDesde.",".$fechaHasta.",".$estadoInicio.",".$estadoFin.",".$idArea.")
					ORDER BY 
						fecha_modificacion;";
	
		$res = $conexion->ejecutarConsulta($sqlScript);
		return $res;
	}
	
	public function verificarCambioEstadoReplica($conexion,$idRequerimiento){
	
		$sqlScript="SELECT 
							count(id_funcionarios_replicados) as total, count(estado) as estado
						FROM 
							g_capacitacion.funcionarios_replicados
						WHERE
							id_requerimiento = $idRequerimiento;";
	
		$res = $conexion->ejecutarConsulta($sqlScript);
		return $res;
	}
	
	public function verificarCambioEstadoFormatoReplica($conexion,$idRequerimiento){
	
		$sqlScript="SELECT
						count(id_participantes) as total, count(conocimientos_transmitidos) as estado
					FROM
						g_capacitacion.participantes
					WHERE
						id_requerimiento = $idRequerimiento;";
	
		$res = $conexion->ejecutarConsulta($sqlScript);
		return $res;
	}

	
	public function verificarCalificacionReplicado($conexion,$idRequerimiento, $identificadorReplicado){
	
		$sqlScript="SELECT
						*
					FROM 
						g_capacitacion.funcionarios_replicados fr,
						g_capacitacion.calificacion_replicador cr						
					WHERE 
						fr.id_funcionarios_replicados = cr.id_funcionarios_replicados and
						identificador_replicado = '$identificadorReplicado' and
						id_requerimiento = $idRequerimiento;";
		
	
		$res = $conexion->ejecutarConsulta($sqlScript);
		return $res;
	}
	
	public function verificarFormatoReplicaUsuario($conexion,$idRequerimiento, $identificador){
	
		$sqlScript="SELECT
						*
					FROM
						g_capacitacion.participantes	
					WHERE
						identificador = '$identificador' and
						id_requerimiento = $idRequerimiento;";
	
	
		$res = $conexion->ejecutarConsulta($sqlScript);
		return $res;
	}
	
	public function actualizarTipoReplicaRequerimiento($conexion, $idRequerimiento, $tipoReplica, $descripcionReplica, $modoReplica){
	
		$sqlScript="UPDATE
						g_capacitacion.requerimiento
					SET
						tipo_replica='$tipoReplica',
						descripcion_replica='$descripcionReplica',
						modo_replica = '$modoReplica'
					WHERE
						id_requerimiento='$idRequerimiento';";
			
		$res = $conexion->ejecutarConsulta($sqlScript);
	
		return $res;
	
	}
	
	public function actualizarArchivoReplicaIndividual($conexion, $idRequerimiento, $archivoReplica, $identificador, $estado, $estadoReplica){
		
		$sqlScript="UPDATE 
						g_capacitacion.funcionarios_replicados
					SET
						archivo_replica = '$archivoReplica',
						estado = $estado,
						estado_replica = '$estadoReplica'
					WHERE
						id_requerimiento = $idRequerimiento and
						identificador_replicado = '$identificador'";
	
		$res = $conexion->ejecutarConsulta($sqlScript);
	
		return $res;
			
	}
	
	public function actualizarArchivoReplicaGrupal($conexion, $idRequerimiento, $archivoReplica, $estado, $estadoReplica){
	
		$sqlScript="UPDATE
						g_capacitacion.funcionarios_replicados
					SET
						archivo_replica = '$archivoReplica',
						estado = $estado,
						estado_replica = '$estadoReplica'
					WHERE
						id_requerimiento = $idRequerimiento";
	
		$res = $conexion->ejecutarConsulta($sqlScript);
	
		return $res;
			
	}
	
	public function ObtenerFuncionarioReplicacionArchivo($conexion, $idRequerimiento, $identificador){
	
		$sqlScript="SELECT 
						* 
					FROM
						g_capacitacion.funcionarios_replicados			
					WHERE
						id_requerimiento = $idRequerimiento and
						identificador_replicado = '$identificador'";
	
		$res = $conexion->ejecutarConsulta($sqlScript);
	
		return $res;
			
	}
	
	public function listarRevisionArchivoReplica($conexion, $estado, $idRequerimiento){
	
		$sqlScript="SELECT 
						r.*
					FROM
						g_capacitacion.requerimiento r,
						g_capacitacion.funcionarios_replicados fr
					WHERE
						r.id_requerimiento = fr.id_requerimiento
						and estado_replica = '$estado'
						and r.id_requerimiento=$idRequerimiento;";
	
		$res = $conexion->ejecutarConsulta($sqlScript);
	
		return $res;
			
	}
	
	public function obtenerArchivoReplicaRevision($conexion, $idRequisito, $estado){
	
		$sqlScript="SELECT
						fr.*,
						upper(fe.apellido ||' '||fe.nombre) as nombre_completo
					FROM
						g_capacitacion.funcionarios_replicados fr,
						g_uath.ficha_empleado as fe
					WHERE
						fr.identificador_replicado = fe.identificador and
						id_requerimiento = $idRequisito;";
	
		$res = $conexion->ejecutarConsulta($sqlScript);
	
		return $res;
			
	}
	
	public function actualizarArchivoReplicaJefe($conexion, $idRequerimiento, $identificador, $estado, $observacion){
	
		$sqlScript="UPDATE
						g_capacitacion.funcionarios_replicados
					SET
						observacion_replica = '$observacion',
						estado_replica = '$estado'
					WHERE
						id_requerimiento = $idRequerimiento and
						identificador_replicado = '$identificador'";
	
		$res = $conexion->ejecutarConsulta($sqlScript);
	
		return $res;
			
	}
	
	public function actualizarEstadoReplicacionProcedimiento($conexion, $identificadorReplicado, $estado, $idRequerimiento){
		
		$sqlScript="UPDATE
						g_capacitacion.funcionarios_replicados
					SET
						estado='$estado',
						fecha_modificacion=now()
					WHERE
						identificador_replicado = '$identificadorReplicado' and
						id_requerimiento = $idRequerimiento;";
	
		$res = $conexion->ejecutarConsulta($sqlScript);
	
		return $res;
	
	}
	
	public function eliminarFuncionariosReplicantes($conexion, $idRequerimiento){
	
		$sqlScript="DELETE FROM
						g_capacitacion.funcionarios_replicados
					WHERE
						id_requerimiento='$idRequerimiento'";
	
		$res = $conexion->ejecutarConsulta($sqlScript);
	
		return $res;
	}
	
	public function imprimirLineaAsistenteCapacitacion($idParticipante, $nombreParticipante){
	
		return '<tr id="R'. $idParticipante .'">' .
				'<td width="100%">' . $nombreParticipante.	'</td>' .
				'<td>' .
				'<form class="borrar" data-rutaAplicacion="capacitacion" data-destino="detalleItem" data-opcion="eliminarParticipante">' .
				'<input type="hidden" name="idParticipante" value="' . $idParticipante . '" >' .
				'<button type="submit" class="menos inhabilitar">Quitar</button>' .
				'</form>' .
				'</td>' .
				'</tr>';
	}
	
	public function imprimirLineaReplicado($idFuncionarioReplicado, $nombreReplicado, $identificadorReplicador){
	
		return '<tr id="R' . $idFuncionarioReplicado .'">' .
				'<td width="100%">' . $nombreReplicado.	'</td>' .
				'<td>' .
				'<form class="borrar" data-rutaAplicacion="capacitacion" data-destino="detalleItem" data-opcion="eliminarReplicado">' .
				'<input type="hidden" name="idFuncionarioReplicado" value="' . $idFuncionarioReplicado . '" >' .
				'<button type="submit" onclick="quitarReplicante(\''.$identificadorReplicador.'\')" class="menos inhabilitar">Quitar</button>' .
				'</form>' .
				'</td>' .
				'</tr>';
	}
	
	public function consultarFuncionarioCapacitadoBloqueado($conexion, $identificador, $estado){
	
		$sqlScript="SELECT 
						id_participantes
					FROM 
						g_capacitacion.participantes 
					WHERE 
						identificador='$identificador' and bloqueo='$estado' ;";
	
		$res = $conexion->ejecutarConsulta($sqlScript);
	
		return $res;
	}
	
	public function consultarFuncionarioReplicadoBloqueado($conexion, $idRequerimiento, $identificadorReplicado){
	
		$sqlScript="SELECT 
						id_funcionarios_replicados
					FROM 
						g_capacitacion.funcionarios_replicados 
					WHERE 
						id_requerimiento='$idRequerimiento' and identificador_replicado='$identificadorReplicado' ;";
	
		$res = $conexion->ejecutarConsulta($sqlScript);
	
		return $res;
	}
}
?>