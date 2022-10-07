<?php

class ControladorGestionAplicacionesPerfiles{

	public function guardarGestionAplicacion($conexion,$identificador,$codigo){
		$res = $conexion->ejecutarConsulta("
										INSERT INTO g_gestion_aplicaciones_perfiles.aplicaciones(identificador, codificacion_aplicacion)
    									SELECT '$identificador','$codigo'
										WHERE NOT EXISTS (SELECT id_aplicacion FROM g_gestion_aplicaciones_perfiles.aplicaciones WHERE identificador = '$identificador' and codificacion_aplicacion = '$codigo')
										RETURNING id_aplicacion;");
		
		if(pg_num_rows($res) == 0){
			
			$res = $conexion->ejecutarConsulta("UPDATE
													g_gestion_aplicaciones_perfiles.aplicaciones
												SET
													estado = 'FALSE'
												WHERE
													identificador = '$identificador' and codificacion_aplicacion = '$codigo'
												RETURNING id_aplicacion;");
		}
		
		return $res;
	}
	
	public function guardarGestionPerfil($conexion,$identificador,$codigo){
		$res = $conexion->ejecutarConsulta("
										INSERT INTO g_gestion_aplicaciones_perfiles.perfiles(identificador, codificacion_perfil)
    									SELECT '$identificador', '$codigo'  
										WHERE NOT EXISTS (SELECT id_perfil FROM g_gestion_aplicaciones_perfiles.perfiles WHERE identificador = '$identificador' and codificacion_perfil = '$codigo') 
										RETURNING id_perfil;");
		
		if(pg_num_rows($res) == 0){

			$res = $conexion->ejecutarConsulta("UPDATE 
													g_gestion_aplicaciones_perfiles.perfiles 
												SET 
													estado = 'FALSE' 
												WHERE 
													identificador = '$identificador' and codificacion_perfil = '$codigo'
												RETURNING id_perfil;");
		}
		
		return $res;
	}
	
	public function obtenerGrupoPerfilXAplicacion ($conexion,$aplicacion,$codificacionPerfil){

		$res = $conexion->ejecutarConsulta("SELECT
												id_perfil, codificacion_perfil
											FROM
												g_usuario.perfiles
											WHERE
												id_aplicacion = '$aplicacion' and
												codificacion_perfil IN $codificacionPerfil ;");
		return $res;
	}
	
	public function obtenerGrupoAplicacion ($conexion,$aplicacion){
	
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_programas.aplicaciones
											where
												codificacion_aplicacion IN $aplicacion;");
				return $res;
	}
	
	public function obtenerGestionAplicacionAActivar ($conexion,$estado){
	
		$res = $conexion->ejecutarConsulta("SELECT id_aplicacion, identificador, codificacion_aplicacion
				FROM g_gestion_aplicaciones_perfiles.aplicaciones where estado='$estado' and identificador != '';");
		return $res;
	}
	
	public function actualizarGestionAplicacionEstado ($conexion,$idAplicacion,$estado){
	
		$res = $conexion->ejecutarConsulta("UPDATE g_gestion_aplicaciones_perfiles.aplicaciones
	SET 
					estado='$estado'
	WHERE id_aplicacion='$idAplicacion';");
		return $res;
	}
	
	
	public function obtenerGestionPerfilAActivar ($conexion,$estado){
	
		$res = $conexion->ejecutarConsulta("SELECT id_perfil, identificador, codificacion_perfil
				FROM g_gestion_aplicaciones_perfiles.perfiles where estado='$estado' and identificador != '';");
		return $res;
	}
	
	public function actualizarGestionPerfilEstado ($conexion,$idPerfil,$estado){
	
		$res = $conexion->ejecutarConsulta("UPDATE g_gestion_aplicaciones_perfiles.perfiles
				SET
				estado='$estado'
				WHERE id_perfil='$idPerfil';");
		return $res;
	}
	


	
}
//?>