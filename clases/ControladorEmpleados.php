<?php 

class ControladorEmpleados{
	
	public function obtenerFichaEmpleado ($conexion,$identificador){
				
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_uath.ficha_empleado 
											where
												identificador='$identificador';");
				return $res;
	}
	
	public function obtenerDatosPersonales ($conexion,$identificador){
		$res = $conexion->ejecutarConsulta("select 
												*,
												g_uath.cambia_formato_nombre(fe.nombre,fe.apellido) AS nombres_completos	
											from 
												g_uath.ficha_empleado fe
											where 
												fe.identificador='$identificador';");
		return $res;
	}
	
	public function actualizarDatosPersonales($conexion, $identificador, $nombre, $apellido, $sexo, $estadoCivil, $nacimiento,$sangre,$nacionalidad,$etnia,$indigena,$domicilio,$convencional,$celular,$mailPersonal,$mailInstitucional,
			$discapacidad_empleado,$carnet_conadis_empleado,$representante_discapacitado,$carnet_conadis_familiar,$provincia,$canton,$parroquia,$edad,$enfermedad_catastrofica,$nombre_enfermedad_catastrofica,$extension_magap,$tipo_documento,
	    $provinciaNacimiento,$cantonNacimiento,$parroquiaNacimiento,$jornadaLaboral,$religion,$orientacionSexual,$lateralidad,$libretaMilitar,$telefonoInstitucional, $rutaPerfilPublico, $rutaQrPerfilPublico){
		
		$sqlScript="update
												g_uath.ficha_empleado
											set
												nombre='$nombre', 
												apellido='$apellido', 
												nacionalidad='$nacionalidad',
										        genero='$sexo', 
												estado_civil='$estadoCivil', 
												fecha_nacimiento='$nacimiento',
										        tipo_sangre='$sangre', 
												identificacion_etnica='$etnia', 
												nacionalidad_indigena='$indigena',
												domicilio='$domicilio',
				                                convencional='$convencional',
												celular='$celular',
												mail_personal='$mailPersonal',
				                                mail_institucional='$mailInstitucional',
								                tiene_discapacidad='$discapacidad_empleado',
				                                carnet_conadis_empleado='$carnet_conadis_empleado',
				                                representante_familiar_discapacidad='$representante_discapacitado',
												carnet_conadis_familiar='$carnet_conadis_familiar',
												id_localizacion_provincia='$provincia',
												id_localizacion_canton='$canton',
				                                id_localizacion_parroquia='$parroquia',
				                                edad='$edad',
				                                tiene_enfermedad_catastrofica='$enfermedad_catastrofica',
				                                nombre_enfermedad_catastrofica='$nombre_enfermedad_catastrofica',
				                                extension_magap='$extension_magap',
				                                tipo_documento='$tipo_documento',
										       	fecha_modificacion=now(),
										       	estado_empleado = 'activo',
                                                provincia_nacimiento = $provinciaNacimiento,
                                                canton_nacimiento = $cantonNacimiento,
                                                parroquia_nacimiento = $parroquiaNacimiento,
                                                jornada_laboral = '$jornadaLaboral',
                                                religion = '$religion',
                                                orientacion_sexual = '$orientacionSexual',
                                                lateralidad = '$lateralidad',
                                                libreta_militar = '$libretaMilitar',
                                                telefono_institucional = '$telefonoInstitucional',
                                                ruta_perfil_publico = '$rutaPerfilPublico', 
                                                ruta_qr_perfil_publico = '$rutaQrPerfilPublico'
											where
												identificador='$identificador';";
		$res = $conexion->ejecutarConsulta($sqlScript);
		return $res;
	}
	
	public function actualizarFoto($conexion, $identificador, $rutaFoto){
		$res = $conexion->ejecutarConsulta("update
												g_uath.ficha_empleado
											set
												fotografia='$rutaFoto',
										       	fecha_modificacion=now()
											where
												identificador='$identificador';");
		return $res;
	}
	
	public function obtenerUsuariosPorPerfil($conexion,$pametroDeBusqueda){
		$res = $conexion->ejecutarConsulta("
											SELECT
												fe.*
											FROM
												g_uath.ficha_empleado fe
												,g_usuario.usuarios u
												,g_usuario.perfiles p
												,g_usuario.usuarios_perfiles up
											WHERE
												up.id_perfil = p.id_perfil
												and up.identificador = u.identificador
												and u.identificador = fe.identificador
												and p.nombre = '$pametroDeBusqueda';");
	
		return $res;
	}
	
	
	public function  verificarCorreoElectronicoUsuarioInterno($conexion, $identificador, $mail){
				
		$res = $conexion->ejecutarConsulta("SELECT 
												*
											FROM 
												g_uath.ficha_empleado
											WHERE
												(identificador = '$identificador') and 
												(mail_personal = '$mail'
												or mail_institucional = '$mail')");
		return $res;
	}
	
	public function obtenerUsuariosPorFiltro($conexion, $identificador, $nombres, $apellidos)
	{
		$condicion = '';
		$condicion = ($identificador != '') ? " AND fe.identificador LIKE '$identificador%'" : '';
		$condicion .= ($nombres != '') ? " AND UPPER(fe.nombre) LIKE UPPER('$nombres%')" : '';
		$condicion .= ($apellidos != '')? " AND UPPER(fe.apellido) LIKE UPPER('$apellidos%')":'';
	
		$res = $conexion->ejecutarConsulta("
            SELECT
                fe.identificador,
                fe.nombre,
                fe.apellido,
                fe.estado_empleado
            FROM
                g_uath.ficha_empleado fe
            WHERE
                " . substr($condicion, 5) . "
            ORDER BY
                (CASE WHEN fe.estado_empleado = 'activo' THEN 1
                      WHEN fe.estado_empleado = 'inactivo' THEN 2
                END),
                fe.apellido,
                fe.nombre
        ");
		return $res;
	}
	
	public function modificarCorreosUsuarioSistema($conexion, $identificador, $mailInstitucional, $mailPersonal){
	    
	    $res = $conexion->ejecutarConsulta("
											UPDATE
												g_uath.ficha_empleado
											SET
												mail_personal = '$mailPersonal',
                                                mail_institucional = '$mailInstitucional'
											WHERE
												identificador = '$identificador';");
	    
	    return $res;
	}
}