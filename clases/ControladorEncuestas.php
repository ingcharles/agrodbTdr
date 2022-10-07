<?php

class ControladorEncuestas{


	
	public function listarEncuestasHabilitadas ($conexion,$identificador){
		$res = $conexion->ejecutarConsulta("select
												a.identificador,
												a.estado as estado_encuestador,
												e.fecha_creacion,
												e.estado as estado_encuesta,
												e.id_encuesta,
												e.nombre
											from
												g_encuesta.aplicantes a,
												g_encuesta.encuestas e
											where
												a.id_encuesta = e.id_encuesta and 
												a.estado = true and
												e.estado = 1 and
												a.identificador = '$identificador'
											order by
												e.fecha_creacion;");
		return $res;
	}
	
	public function obtenerPreguntas($conexion, $encuesta){
		$res = $conexion->ejecutarConsulta("select												
												p.*,
												e.nombre,
												e.objetivo
											from
												g_encuesta.preguntas p,
												g_encuesta.encuestas e
											where	
												p.id_encuesta = e.id_encuesta and
												p.id_encuesta = $encuesta
											order by
												p.id_pregunta asc;");
		return $res;
	}
	
	public function obtenerOpciones($conexion, $encuesta){
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_encuesta.opciones
											where
												id_encuesta = $encuesta
											order by
												id_pregunta,id_opcion");
		return $res;
	}
	
	public function grabarRespuestas($conexion, $sentencia){
		$res = $conexion->ejecutarConsulta(rtrim($sentencia, ","));
		return $res;
	}
	
	public function quitarEncuesta($conexion, $identificador, $encuesta){
		$res = $conexion->ejecutarConsulta("update 
												g_encuesta.aplicantes
											set 
												estado = false
											where
												identificador = '$identificador' and
												id_encuesta = $encuesta;");
		
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
											a.codificacion_aplicacion='PRG_ENCUESTAS'));");
			return $res;
	}
	
}
