<?php

class ControladorInscripcionCaravana {
    public function abrirInscripcion($conexion, $identificador) {
        $res = $conexion->ejecutarConsulta("select
												*
											from
												g_inscripcion_caravana.inscripciones i
											where
												i.identificador = '$identificador'
											");
        return $res;
    }

    public function guardarInscripcion($conexion, $identificador, $a, $b, $c, $d, $e, $f, $g, $h, $i, $j, $k, $l, $m) {
    	
        $res = $conexion->ejecutarConsulta("insert into
												g_inscripcion_caravana.inscripciones(identificador, a, b, c, d, e, f, g, h, i, j, k, l, m)
											values(
												'$identificador'
												,'$a' ,'$b'	,'$c' ,'$d'	,'$e' ,'$f'	,'$g' ,'$h'	,'$i' ,'$j','$k','$l','$m'
											)
											returning id_inscripcion;
											");
        return $res;
    }

    public function listarAplicaciones($conexion) {
        $res = $conexion->ejecutarConsulta("select
												*
											from
												g_inscripcion_caravana.inscripciones i
											order by
                                                case i.estado
                                                  when 'Ingresada' then 1
                                                  when 'Aprobada' then 2
                                                  when 'Rechazada' then 3
                                                  else 4
                                                end/*TODO:REVISAR EL ORDEN*/
                                                , i.fecha_inscripcion
                                            ");
        return $res;
    }
    
    public function actualizarInscripcion($conexion, $idInscripcion, $nuevoEstado, $observacion){
    	    	
    	$res = $conexion->ejecutarConsulta("UPDATE
												g_inscripcion_caravana.inscripciones i
											SET
												estado = '$nuevoEstado',
    											observacion = '$observacion'
    										WHERE
    											id_inscripcion = $idInscripcion");
    	return $res;
    }
}