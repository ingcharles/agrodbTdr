<?php

class ControladorControlEpidemiologico{
	
public function guardarNuevaNotificacion($conexion, $identificadorNotificante, $nombreNotificante, $apellidoNotificante, $telefonoNotificante, $celularNotificante,
											$identificadorOperador, $codigoSitio, $idEspecie, $especie, $poblacionAfectada, $patologia){
		$res = $conexion->ejecutarConsulta("INSERT INTO 
												g_control_epidemiologico.notificacion_epidemia(
										            identificador_notificante, nombre_notificante, 
										            apellido_notificante, telefono_notificante, celular_notificante, 
										            identificador_operador, codigo_sitio, id_especie, especie, 
										            poblacion_afectada, patologia_notificada)
										    VALUES ('$identificadorNotificante', '$nombreNotificante', 
										            '$apellidoNotificante', '$telefonoNotificante', '$celularNotificante', 
										            '$identificadorOperador', '$codigoSitio', $idEspecie, '$especie', 
										            $poblacionAfectada, '$patologia');");
		return $res;
	}
	
	public function listarNotificaciones ($conexion){
		
		$res = $conexion->ejecutarConsulta("select
												e.*,
												s.*,
												o.*
											from
												g_control_epidemiologico.notificacion_epidemia e,
												g_operadores.sitios s,
												g_operadores.operadores o
											where
												e.codigo_sitio = s.codigo and
												e.identificador_operador = s.identificador_operador and
												s.identificador_operador = o.identificador;");
		
		return $res;
	}
	
	public function abrirNotificacion ($conexion, $idNotificacion){
	
		$res = $conexion->ejecutarConsulta("select
												e.*,
												s.*
											from
												g_control_epidemiologico.notificacion_epidemia e,
												g_operadores.sitios s
											where
												e.id_notificacion = $idNotificacion and
												e.codigo_sitio = s.codigo and
												e.identificador_operador = s.identificador_operador;");
	
		return $res;
	}
	
	public function actualizarNotificacion ($conexion, $idNotificacion, $identificadorNotificante, $nombreNotificante, $apellidoNotificante,
											$telefonoNotificante, $celularNotificante, $codigoSitio, $idEspecie, $especie, $poblacionAfectada, 
											$patologiaNotificada){
	
		$res = $conexion->ejecutarConsulta("UPDATE 
												g_control_epidemiologico.notificacion_epidemia
											SET  
												identificador_notificante='$identificadorNotificante', 
												nombre_notificante='$nombreNotificante', 
												apellido_notificante='$apellidoNotificante', 
												telefono_notificante='$telefonoNotificante', 
												celular_notificante='$celularNotificante',
												codigo_sitio='$codigoSitio', 
												id_especie=$idEspecie, 
												especie='$especie', 
												poblacion_afectada=$poblacionAfectada, 
												patologia_notificada='$patologiaNotificada'
 											WHERE id_notificacion= $idNotificacion;");
	
				return $res;
	}
}