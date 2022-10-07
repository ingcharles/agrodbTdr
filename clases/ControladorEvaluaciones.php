<?php

class ControladorEvaluaciones{


	
	public function listarEvaluacionesHabilitadas ($conexion,$identificador,$tipo){
		
		$busqueda = '';
		switch ($tipo){
			case 'EVALUACION': $busqueda = 'a.estado = true and'; break;
			case 'REIMPRESION': $busqueda = 'a.estado = false and'; break;
		}
		
		$res = $conexion->ejecutarConsulta("select
												a.identificador,
												a.estado as estado_evaluacion,
												e.fecha_creacion,
												e.estado as estado_evaluacion,
												e.id_evaluacion,
												e.nombre
											from
												g_evaluacion.aplicantes a,
												g_evaluacion.evaluaciones e
											where
												a.id_evaluacion = e.id_evaluacion and 
												".$busqueda."
												e.estado = 1 and
												a.identificador = '$identificador'
											order by
												e.fecha_creacion;");
		return $res;
	}
	
		
	public function obtenerDatosEvaluacion($conexion, $evaluacion){
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_evaluacion.evaluaciones e
											where
												e.id_evaluacion = $evaluacion");
				return $res;
	}
	
	public function obtenerPreguntas($conexion, $evaluacion){
		$res = $conexion->ejecutarConsulta("select												
												*												
											from
												g_evaluacion.preguntas p,
												g_evaluacion.evaluaciones e
											where	
												p.id_evaluacion = e.id_evaluacion and
												p.id_evaluacion = $evaluacion");
		return $res;
	}
	
	public function obtenerOpciones($conexion, $evaluacion){
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_evaluacion.opciones
											where
												id_evaluacion = $evaluacion
											order by
												id_opcion, id_pregunta");
		return $res;
	}
	
	public function guardarPregunta($conexion, $evaluacion, $pregunta, $identificador, $idResultadoEvaluacion){
		$res = $conexion->ejecutarConsulta("INSERT INTO 
														g_evaluacion.respuestas(id_evaluacion, id_pregunta, identificador, id_resultado_evaluacion)
   											VALUES ($evaluacion,$pregunta,'$identificador','$idResultadoEvaluacion');");
		return $res;
	}
	
	
	public function grabarRespuesta($conexion, $pregunta,$opcion,$identificador,$evaluacion){
		
		$res = $conexion->ejecutarConsulta("update
												g_evaluacion.respuestas
											set
												id_opcion = $opcion
											where
												identificador = '$identificador' and
												id_evaluacion = $evaluacion and 
												id_pregunta = $pregunta and
												id_resultado_evaluacion = (SELECT max(id_resultado_evaluacion) FROM g_evaluacion.respuestas WHERE identificador = '$identificador' and id_evaluacion = $evaluacion);");
	
				//$this->actualizarNotificacion($conexion, $identificador);
	
				return $res;
	}
	
	
	public function grabarHora($conexion, $identificador, $evaluacion, $duracion){
		$res = $conexion->ejecutarConsulta("UPDATE
												g_evaluacion.aplicantes
											SET
												fecha_inicio = NOW(),
												fecha_fin = (NOW() + interval '$duracion minutes')
											WHERE
												identificador = '$identificador'
												AND
												id_evaluacion = $evaluacion;");
	
	
				return $res;
	}
	
	
	
	public function estadoAplicante ($conexion,$identificador,$evaluacion){
		$res = $conexion->ejecutarConsulta("select
													*
											from
													g_evaluacion.aplicantes a
											where
													a.id_evaluacion = $evaluacion and
													a.identificador = '$identificador';");
				return $res;
	}
	
	public function preguntasAplicante ($conexion,$identificador,$evaluacion, $idResultadoEvaluacion){
		$res = $conexion->ejecutarConsulta("select
												r.id_pregunta,
												p.descripcion,
												p.ruta_imagen
											from
												g_evaluacion.respuestas r,
												g_evaluacion.preguntas p
											where
												r.id_pregunta = p.id_pregunta and
												r.id_evaluacion = $evaluacion and
												r.identificador = '$identificador'and 
												r.id_resultado_evaluacion = $idResultadoEvaluacion;");
		return $res;
	}
	
	public function obtenerCalificacion($conexion, $evaluacion, $identificador, $numeroResultadoEvaluacion){
		$res = $conexion->ejecutarConsulta("select
												COUNT(id_respuesta) as num_preguntas,
												SUM(ponderacion) as calificacion
											from
												g_evaluacion.respuestas
											where
												id_evaluacion = '$evaluacion' and
												identificador = '$identificador' and
												id_resultado_evaluacion = '$numeroResultadoEvaluacion';");
				return $res;
	}
	
	public function quitarEvaluacion($conexion, $identificador, $evaluacion){
		$res = $conexion->ejecutarConsulta("update 
												g_evaluacion.aplicantes
											set 
												estado = false,
												fecha_fin = now()
											where
												identificador = '$identificador' and
												id_evaluacion = $evaluacion;");
		
		$this->actualizarNotificacion($conexion, $identificador);
		
		return $res;
	}
	
		
	public function actualizarNotificacion($conexion, $identificador){
	$res = $conexion->ejecutarConsulta("select 
												g_programas.actualizarnotificaciones(
													-1,
													'$identificador',
											(SELECT
											a.id_aplicacion
											FROM
											g_programas.aplicaciones a
											WHERE
											a.codificacion_aplicacion='PRG_EVALUACION'));");
			return $res;
	}
	
	public function datosImpresion($conexion, $identificador, $evaluacion){
		
		$res = $conexion->ejecutarConsulta("select
												e.nombre as evaluacion,
												a.nombre,
												a.apellido,
												a.fecha_inicio,
												a.fecha_fin,
												e.imprimir
											from
												g_evaluacion.aplicantes a,
												g_evaluacion.evaluaciones e
											where
												a.id_evaluacion = e.id_evaluacion and
												a.id_evaluacion = '$evaluacion' and
												a.identificador = '$identificador';");
		return $res;
		
	}
	
	public function guardarResultadoEvaluacion($conexion, $identificador, $oportunidad, $evaluacion){
		$res = $conexion->ejecutarConsulta("INSERT INTO
													g_evaluacion.resultado_evaluaciones(identificador, fecha_inicio, numero_oportunidad, id_evaluacion)
											VALUES ('$identificador',now(),$oportunidad, $evaluacion) returning id_resultado_evaluacion ;");
				return $res;
	}
	
	public function buscarNumeroOportunidad($conexion, $identificador, $idEvaluacion){
		$res = $conexion->ejecutarConsulta("SELECT 
												COALESCE(
													MAX(
														CAST(numero_oportunidad as  numeric(5))),0)+1 as codigo 
											FROM 
												g_evaluacion.resultado_evaluaciones 
											WHERE 
												identificador  = '$identificador' and
												id_evaluacion = '$idEvaluacion';");
		return $res;
	}
	
	public function buscarOportunidadActual($conexion, $identificador, $idEvaluacion){
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_evaluacion.resultado_evaluaciones
											WHERE
												identificador  = '$identificador' and
												id_evaluacion = '$idEvaluacion'and 
												numero_oportunidad = (	SELECT 
																			MAX(numero_oportunidad) 
																		FROM
																			g_evaluacion.resultado_evaluaciones
																		WHERE
																			identificador  = '$identificador' and
																			id_evaluacion = '$idEvaluacion');");
				return $res;
	}
	
	public function actualizarResultadoEvaluacion($conexion, $identificador, $idResultadoEvaluacion, $calificacion){
		$res = $conexion->ejecutarConsulta("update
												g_evaluacion.resultado_evaluaciones
											set
												fecha_fin = 'now()',
												calificacion = $calificacion
											where
												identificador = '$identificador' and
												id_resultado_evaluacion = $idResultadoEvaluacion;");
	
				$this->actualizarNotificacion($conexion, $identificador);
	
		return $res;
	}
	
	public function obtenerAplicantesPendientes($conexion,$estado,$busqueda){
		$consulta="SELECT 
						id_activacion,identificador,nombre,apellido, id_evaluacion 
					FROM
						g_evaluacion.activacion_evaluaciones
					WHERE
						estado=$estado
						$busqueda";
		
		$res=$conexion->ejecutarConsulta($consulta);
		return $res;		
		
	}
	
	public function ingresarAplicantes($conexion,$identificador,$nombre,$apellido,$idEvaluacion){		
		$consulta ="select g_evaluacion.insertarOactualizarAplicantes('$identificador',$idEvaluacion,'$nombre','$apellido',true)";
		$res=$conexion->ejecutarConsulta($consulta);
		return $res;
	}
	
	public function inactivarAplicantes($conexion,$identificador,$idEvaluacion){
		$consulta="UPDATE
						g_evaluacion.aplicantes
					SET
						estado='false'
					WHERE
						identificador='$identificador'
						and id_evaluacion=$idEvaluacion";
			
		$res=$conexion->ejecutarConsulta($consulta);
		return $res;
	}
	
	public function actualizarEstadoActivacion($conexion,$idActivacion,$estado){
		$consulta="UPDATE 
						g_evaluacion.activacion_evaluaciones
					 SET
						 estado=$estado
					 WHERE
					 	id_activacion=$idActivacion";
		
		$res=$conexion->ejecutarConsulta($consulta);
		return $res;
	}
	
}
