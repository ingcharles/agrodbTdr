<?php

class ControladorInscripciones {
	
	public function listarEventosDisponibles($conexion, $identificador){
		$res = $conexion->ejecutarConsulta("
				SELECT 
					*
				FROM
					g_inscripciones.inscripciones i
					, g_inscripciones.eventos e
				WHERE
					i.identificador = '$identificador'
					and i.id_evento = e.id_evento
					and i.estado is null;");
		
		return $res;
	}
	
	public function abrirInscripcionCarrera($conexion, $idInscripcion){
		$res = $conexion->ejecutarConsulta("
					SELECT
						*
					FROM
						g_inscripciones.inscripciones i,
						g_inscripciones.eventos e
					WHERE
						i.id_inscripcion = $idInscripcion
						and i.id_evento = e.id_evento");
		return $res;
	}
	
	public function guardarInscripcionCarrera($conexion, $inscripcion, $equipo, $tipoCarrera, $estado){
		$bandera = ($estado == 'Aceptar')?1:0;
		$res = $conexion->ejecutarConsulta("
				UPDATE
					g_inscripciones.inscripciones
				SET
					estado = $bandera::boolean,
					fecha_inscripcion = now()
				WHERE
					id_inscripcion = $inscripcion");
		
		if($bandera){
			$res = $conexion->ejecutarConsulta("
					INSERT INTO
					g_inscripciones.detalle_carrera
					VALUES
					($inscripcion,upper('$equipo'), '$tipoCarrera')");
		} 
		
		return $res;
	}
}